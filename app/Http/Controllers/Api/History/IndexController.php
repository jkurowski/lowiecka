<?php

namespace App\Http\Controllers\Api\History;

use App\Helpers\PropertyAreaTypes;
use App\Helpers\ProvinceTypes;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use DOMDocument;
use DOMXPath;

class IndexController extends Controller
{
    public function createXml()
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        // root
        $datasets = $doc->createElementNS("urn:otwarte-dane:harvester:1.13", "ns2:datasets");
        $doc->appendChild($datasets);

        // dataset
        $dataset = $doc->createElement("dataset");
        $dataset->setAttribute("status", "published");
        $datasets->appendChild($dataset);

        $extIdent = bin2hex(random_bytes(18));
        $dataset->appendChild($doc->createElement("extIdent", $extIdent));

        $company = "EPS Development Sp. z o.o.";
        $year = 2025;

        // title
        $title = $doc->createElement("title");
        $title->appendChild($doc->createElement("polish", "Ceny ofertowe mieszkań dewelopera {$company} w {$year} r."));
        $title->appendChild($doc->createElement("english", "Offer prices of apartments of developer {$company} in {$year}."));
        $dataset->appendChild($title);

        // description
        $description = $doc->createElement("description");
        $description->appendChild($doc->createElement("polish", "Zbiór danych zawiera informacje o cenach ofertowych mieszkań dewelopera {$company} udostępniane zgodnie z art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $description->appendChild($doc->createElement("english", "The dataset contains information on offer prices of apartments of the developer {$company} made available in accordance with art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $dataset->appendChild($description);

        $dataset->appendChild($doc->createElement("updateFrequency", "daily"));
        $dataset->appendChild($doc->createElement("hasDynamicData", "false"));
        $dataset->appendChild($doc->createElement("hasHighValueData", "true"));
        $dataset->appendChild($doc->createElement("hasHighValueDataFromEuropeanCommissionList", "false"));
        $dataset->appendChild($doc->createElement("hasResearchData", "false"));

        $categories = $doc->createElement("categories");
        $category = $doc->createElement("category", "ECON");
        $categories->appendChild($category);
        $dataset->appendChild($categories);

        // resources
        $resources = $doc->createElement("resources");
        $dataset->appendChild($resources);

        $tags = $doc->createElement("tags");
        $tag = $doc->createElement("tag", "Deweloper");
        $tag->setAttribute("lang", "pl");
        $tags->appendChild($tag);
        $dataset->appendChild($tags);

        $path = public_path('datasets/developer.xml');

        if (!file_exists($path)) {
            $doc->save($path);
        } else {
            throw new \Exception("Plik developer.xml już istnieje!");
        }

        return response()->json(['message' => 'Plik XML utworzony', 'path' => $path]);
    }

    /**
     * Aktualizuje istniejący XML dopisując nowy <resource>
     */
    public function updateXml()
    {
        $xmlPath  = public_path('datasets/developer.xml');

        if (!file_exists($xmlPath)) {
            return response()->json(['error' => 'Plik XML nie istnieje. Najpierw uruchom createXml()'], 404);
        }

        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; // usuwa stare wcięcia
        $doc->formatOutput = true;         // włącza ładne wcięcia
        $doc->load($xmlPath);

        $xpath = new DOMXPath($doc);
        $resources = $xpath->query("//resources")->item(0);

        if (!$resources) {
            return response()->json(['error' => 'Brak sekcji <resources> w XML'], 500);
        }

        $today = now()->format('Y-m-d');
        $investments = Investment::where('status', 1)->with(['company', 'salePoint'])->get();

        // Najpierw wszystkie wiersze, dopiero potem zapis - błąd w danych jednej inwestycji
        // nie zostawia na dysku połowy plików i niedokończonego XML-a.
        $reports = [];
        foreach ($investments as $investment) {
            $reports[] = [$investment, $this->reportRows($investment)];
        }

        foreach ($reports as [$investment, $rows]) {
            $filename = "ceny-ofertowe-" . $investment->slug . "-" . $today . ".csv";

            file_put_contents(public_path("datasets/{$filename}"), $this->csvContent($rows));

            $data = [
                'company_name' => $investment->company->name ?? 'Brak nazwy firmy',
                'file_csv' => $filename
            ];

            $this->appendResource($doc, $resources, $data);
        }

        $doc->save($xmlPath);
        $this->generateMD5();

        return response()->json(['message' => 'Dodano nowy resource', 'path' => $xmlPath]);
    }

    /**
     * Podgląd raportu jako tabela HTML - te same kolumny i wartości co w CSV
     */
    public function showAsTable(Investment $investment)
    {
        $investment->load(['company', 'salePoint']);

        echo '<table border="1">';
        echo '<thead><tr>';
        foreach (self::REPORT_HEADERS as $header) {
            echo '<th>' . e($header) . '</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';

        foreach ($this->reportRows($investment) as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . e($cell) . '</td>';
            }
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    /**
     * Nazwy pól wg załącznika do rozporządzenia Ministra Cyfryzacji z 10.11.2026 w sprawie struktury danych
     * zawartych w informacjach dotyczących sprzedaży lokali mieszkalnych i domów jednorodzinnych
     * (Dz.U. 2026 poz. 1489, obowiązuje od 11.11.2026). Brzmienie i kolejność nagłówków dokładnie jak
     * we wzorcowym pliku CSV z dane.gov.pl (grupy A-H, 48 pól) - nie poprawiać "po swojemu".
     */
    private const REPORT_HEADERS = [
        // A. Dane identyfikacyjne podmiotu obowiązanego
        "Firma",
        "Numer identyfikacji podatkowej (NIP)",

        // B. Adres siedziby albo głównego miejsca wykonywania działalności gospodarczej
        "Województwo adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Powiat adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Gmina adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Miejscowość adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Ulica adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Numer nieruchomości adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Numer lokalu adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",
        "Kod pocztowy adresu siedziby albo głównego miejsca wykonywania działalności gospodarczej",

        // C. Adres lokalu, w którym jest prowadzona sprzedaż
        "Województwo adresu lokalu, w którym jest prowadzona sprzedaż",
        "Powiat adresu lokalu, w którym jest prowadzona sprzedaż",
        "Gmina adresu lokalu, w którym jest prowadzona sprzedaż",
        "Miejscowość adresu lokalu, w którym jest prowadzona sprzedaż",
        "Ulica adresu lokalu, w którym jest prowadzona sprzedaż",
        "Numer nieruchomości adresu lokalu, w którym jest prowadzona sprzedaż",
        "Numer lokalu adresu lokalu, w którym jest prowadzona sprzedaż",
        "Kod pocztowy adresu lokalu, w którym jest prowadzona sprzedaż",
        "Dodatkowe lokalizacje, w których jest prowadzona sprzedaż",
        "Sposób kontaktu nabywcy z podmiotem obowiązanym",

        // D. Lokalizacja przedsięwzięcia deweloperskiego lub zadania inwestycyjnego
        "Województwo lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Powiat lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Gmina lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Miejscowość lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Ulica lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Numer nieruchomości lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Kod pocztowy lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Długość geograficzna punktu określającego lokalizację przestrzenną przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Szerokość geograficzna punktu określającego lokalizację przestrzenną przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",

        // E. Dane dotyczące lokalu mieszkalnego albo domu jednorodzinnego lub ich części
        "Rodzaj nieruchomości",
        "Identyfikator lokalu mieszkalnego albo domu jednorodzinnego",
        "Cena 1 m2 powierzchni użytkowej lokalu mieszkalnego albo domu jednorodzinnego",
        "Data i czas, od których obowiązuje cena 1 m2 powierzchni użytkowej lokalu mieszkalnego albo domu jednorodzinnego",
        "Cena lokalu mieszkalnego albo domu jednorodzinnego lub ich części będących przedmiotem umowy",
        "Data i czas, od których obowiązuje cena lokalu mieszkalnego albo domu jednorodzinnego lub ich części",

        // F. Pomieszczenia przynależne
        "Rodzaj pomieszczenia przynależnego, o którym mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Identyfikator pomieszczenia przynależnego, o którym mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Cena pomieszczenia przynależnego, o którym mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Data i czas, od których obowiązuje cena pomieszczenia przynależnego, o którym mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Identyfikator lokalu mieszkalnego albo domu jednorodzinnego dla pomieszczenia przynależnego",

        // G. Prawa niezbędne do korzystania z lokalu mieszkalnego albo domu jednorodzinnego
        "Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego albo domu jednorodzinnego",
        "Wartość praw niezbędnych do korzystania z lokalu mieszkalnego albo domu jednorodzinnego",
        "Data i czas, od których obowiązuje wartość praw niezbędnych do korzystania z lokalu mieszkalnego albo domu jednorodzinnego",
        "Identyfikator lokalu mieszkalnego albo domu jednorodzinnego dla prawa niezbędnego",

        // H. Inne świadczenia pieniężne
        "Wyszczególnienie rodzaju innego świadczenia pieniężnego, które nabywca jest zobowiązany spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",
        "Wartość innego świadczenia pieniężnego, które nabywca jest zobowiązany spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",
        "Data i czas, od których obowiązuje wartość innego świadczenia pieniężnego, które nabywca jest zobowiązany spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",
        "Identyfikator lokalu mieszkalnego albo domu jednorodzinnego dla innego świadczenia",
    ];

    /**
     * Data obowiązywania ceny, gdy lokal nie ma wpisu w historii cen (property_prices).
     * Historia cen jest prowadzona od 16.09.2025; wcześniej raport podawał stałą datę 09.09.2025.
     */
    private const FALLBACK_PRICE_DATE = '2025-09-09 00:00:00';

    /** Skróty rodzajów pomieszczeń w identyfikatorze (pole F2), np. "MP-13". Wzorzec MC: "GAR03", "PIW03". */
    private const PREMISES_CODES = [
        PropertyAreaTypes::STORAGE => 'KL',
        PropertyAreaTypes::PARKING => 'MP',
        PropertyAreaTypes::GARDEN => 'OG',
        PropertyAreaTypes::PARKING_STORAGE => 'MPK',
    ];

    private function reportProperties(Investment $investment)
    {
        // lokale usługowe nie są lokalami mieszkalnymi ani ich częściami - nie podlegają raportowi
        return $investment->activeProperties()
            ->where('type', '!=', PropertyAreaTypes::SHOP)
            ->with(['priceHistory', 'relatedProperties.priceHistory', 'priceComponents'])
            ->get();
    }

    /**
     * Wszystkie wiersze raportu dla inwestycji, kolumny jak REPORT_HEADERS. Pole bez zastosowania = "X".
     *
     * Układ wierszy jak we wzorcowym pliku MC:
     * - mieszkanie = tyle wierszy, ile ma pozycji w najdłuższej z grup F (pomieszczenia powiązane z ceną)
     *   i H (składniki ceny), co najmniej jeden; dane lokalu (E) powtarzają się w każdym wierszu,
     *   a F5 / H4 wskazują identyfikator lokalu z E2. Jedna komórka = jedna wartość (bez list po przecinku).
     * - komórka / miejsce postojowe / ogródek sprzedawane osobno (nieprzypisane do mieszkania z raportu)
     *   = własny wiersz z grupą F, bez danych lokalu (E = "X", F5 = "X").
     *   Pomieszczenie przypisane do mieszkania występuje tylko w wierszu tego mieszkania.
     */
    private function reportRows(Investment $investment): array
    {
        $properties = $this->reportProperties($investment);

        $apartments = $properties->filter(fn ($p) => $this->isApartment($p))->values();

        $relatedOf = [];
        $attachedIds = [];
        foreach ($apartments as $apartment) {
            $relatedOf[$apartment->id] = $apartment->relatedProperties
                ->filter(fn ($p) => !$this->isApartment($p)
                    && $p->type != PropertyAreaTypes::SHOP
                    && $this->offerPrice($p) !== null)
                ->values();

            foreach ($relatedOf[$apartment->id] as $related) {
                $attachedIds[$related->id] = true;
            }
        }

        $standalone = $properties
            ->filter(fn ($p) => !$this->isApartment($p) && !isset($attachedIds[$p->id]))
            ->values();

        $identifiers = $this->identifiers($investment);
        $idOf = fn (Property $p) => $identifiers[$p->id] ?? $this->baseIdentifier($p);

        $head = $this->investmentCells($investment);
        $rows = [];

        foreach ($apartments as $apartment) {
            $id = $idOf($apartment);
            $price = $this->offerPrice($apartment);
            $priceDate = $this->priceDate($apartment);
            $hasArea = (float) $apartment->area > 0;

            $e = [
                'lokal mieszkalny',
                $id,
                ($price !== null && $hasArea) ? $this->money($price / (float) $apartment->area) : 'X',
                ($price !== null && $hasArea) ? $priceDate : 'X',
                $price !== null ? $this->money($price) : 'X',
                $price !== null ? $priceDate : 'X',
            ];

            $f = $relatedOf[$apartment->id]->map(fn ($p) => $this->premisesCells($p, $idOf($p), $id))->all();
            $h = $this->componentCells($apartment, $priceDate, $id);

            for ($i = 0, $n = max(1, count($f), count($h)); $i < $n; $i++) {
                $rows[] = $this->row($head, $e, $f[$i] ?? null, $h[$i] ?? null);
            }
        }

        foreach ($standalone as $premises) {
            $f = $this->premisesCells($premises, $idOf($premises), 'X');
            $h = $this->componentCells($premises, $this->priceDate($premises), 'X');

            for ($i = 0, $n = max(1, count($h)); $i < $n; $i++) {
                $rows[] = $this->row($head, null, $i === 0 ? $f : null, $h[$i] ?? null);
            }
        }

        return $rows;
    }

    /** Składa wiersz z grup; brak grupy = "X" w każdym jej polu. Pilnuje liczby kolumn. */
    private function row(array $head, ?array $e, ?array $f, ?array $h): array
    {
        $row = array_merge(
            $head,
            $e ?? array_fill(0, 6, 'X'),
            $f ?? array_fill(0, 5, 'X'),
            // G - prawa niezbędne do korzystania z lokalu: brak takich pozycji w ofercie
            array_fill(0, 4, 'X'),
            $h ?? array_fill(0, 4, 'X')
        );

        if (count($row) !== count(self::REPORT_HEADERS)) {
            throw new \LogicException('Raport cen: liczba wartości (' . count($row) . ') różna od liczby kolumn (' . count(self::REPORT_HEADERS) . ')');
        }

        return $row;
    }

    /** Grupy A-D - wspólne dla wszystkich wierszy inwestycji (29 pól). */
    private function investmentCells(Investment $investment): array
    {
        $company = $investment->company;
        $salePoint = $investment->salePoint;

        return [
            // A
            $this->value($company?->name),
            $this->value(preg_replace('/\D+/', '', (string) $company?->nip)),

            // B
            $this->province($company?->province),
            $this->value($company?->district),
            $this->value($company?->municipality),
            $this->value($company?->city),
            $this->value($company?->street),
            $this->value($company?->building_number),
            $this->value($company?->apartment_number),
            $this->value($company?->postal_code),

            // C
            $this->province($salePoint?->province),
            $this->value($salePoint?->district),
            $this->value($salePoint?->municipality),
            $this->value($salePoint?->city),
            $this->value($salePoint?->street),
            $this->value($salePoint?->building_number),
            $this->value($salePoint?->apartment_number),
            $this->value($salePoint?->postal_code),
            $this->value($salePoint?->additional_locations),
            $this->value($salePoint?->contact_method),

            // D
            $this->province($investment->inv_province),
            $this->value($investment->inv_county),
            $this->value($investment->inv_municipality),
            $this->value($investment->inv_city),
            $this->value($investment->inv_street),
            $this->value($investment->inv_property_number),
            $this->value($investment->inv_postal_code),
            $this->coordinate($investment->inv_longitude),
            $this->coordinate($investment->inv_latitude),
        ];
    }

    /** Grupa F dla jednego pomieszczenia (5 pól). */
    private function premisesCells(Property $premises, string $identifier, string $apartmentIdentifier): array
    {
        $price = $this->offerPrice($premises);

        return [
            $this->value(PropertyAreaTypes::getStatusText((int) $premises->type)),
            $identifier,
            $price !== null ? $this->money($price) : 'X',
            $price !== null ? $this->priceDate($premises) : 'X',
            $apartmentIdentifier,
        ];
    }

    /**
     * Grupa H - po jednym zestawie 4 pól na składnik ceny. Nazwa to nazwa składnika (pivot.category to tylko
     * obowiązkowy/opcjonalny), kwota stała albo za m2 x powierzchnia; bez kwoty "X" (wtedy data też "X").
     */
    private function componentCells(Property $property, string $priceDate, string $apartmentIdentifier): array
    {
        return $property->priceComponents->map(function ($component) use ($property, $priceDate, $apartmentIdentifier) {
            $amount = $this->componentValue($component, $property);
            $changedAt = $component->pivot->updated_at ?? $component->pivot->created_at;

            return [
                $this->value($component->name),
                $amount,
                $amount === 'X' ? 'X' : ($changedAt ? $this->dateTime($changedAt) : $priceDate),
                $apartmentIdentifier,
            ];
        })->all();
    }

    /**
     * Identyfikatory unikalne w ramach inwestycji (E2, F2), klucz = id rekordu.
     *
     * Podstawą jest numer nadany w panelu. Gdy dwa rekordy dają ten sam identyfikator (np. miejsce nr 9
     * w garażu i nr 9 na parkingu naziemnym), każdy z nich dostaje dopisaną nazwę kondygnacji:
     * "MP-9 / Parking Naziemny". Dopiero gdy i to nie rozróżnia (albo brak kondygnacji) - id rekordu.
     *
     * Powtórzenia liczone są po WSZYSTKICH lokalach inwestycji, także sprzedanych, a nie tylko po tych
     * z dzisiejszego raportu - inaczej identyfikator zmieniłby się w dniu, w którym "bliźniak" znika z oferty,
     * a rozporządzenie wymaga, żeby zachowywał tę samą wartość przez cały czas oferowania.
     */
    private function identifiers(Investment $investment): array
    {
        $all = $investment->properties()
            ->where('type', '!=', PropertyAreaTypes::SHOP)
            ->with('floor')
            ->get();

        $groups = [];
        foreach ($all as $property) {
            $groups[$this->baseIdentifier($property)][] = $property;
        }

        $identifiers = [];
        foreach ($groups as $base => $group) {
            if (count($group) === 1 && $base !== 'X') {
                $identifiers[$group[0]->id] = (string) $base;
                continue;
            }

            $withFloor = [];
            foreach ($group as $property) {
                $floor = $this->value($property->floor?->name);
                $withFloor[$property->id] = $floor === 'X' ? null : $base . ' / ' . $floor;
            }

            $distinct = !in_array(null, $withFloor, true) && count(array_unique($withFloor)) === count($withFloor);

            foreach ($group as $property) {
                $identifiers[$property->id] = $distinct ? $withFloor[$property->id] : $base . '-' . $property->id;
            }
        }

        return $identifiers;
    }

    /**
     * Identyfikator przed rozróżnianiem powtórzeń: mieszkanie = numer z panelu; pomieszczenie = oznaczenie
     * z panelu, gdy zaczyna się od litery (np. "K14", "G15" - te same co w dotychczasowych raportach),
     * a przy samym numerze skrót rodzaju + numer, np. "MP-13".
     */
    private function baseIdentifier(Property $property): string
    {
        $number = $this->value($property->number);

        if ($this->isApartment($property)) {
            return $number;
        }

        $code = self::PREMISES_CODES[$property->type] ?? 'P';

        return preg_match('/^\p{L}/u', $number) ? $number : $code . '-' . $number;
    }

    private function isApartment(Property $property): bool
    {
        return $property->type == PropertyAreaTypes::ROOM_APARTMENT;
    }

    /** Cena ofertowa - przy aktywnej promocji cena promocyjna (tak jak na stronie i w historii cen). */
    private function offerPrice(Property $property): ?float
    {
        if ($property->highlighted && (float) $property->promotion_price > 0) {
            return (float) $property->promotion_price;
        }

        return (float) $property->price_brutto > 0 ? (float) $property->price_brutto : null;
    }

    /** Data i czas, od których obowiązuje aktualna cena (RRRR-MM-DD GG:MM:SS) - ostatnia zmiana z historii cen. */
    private function priceDate(Property $property): string
    {
        $latest = $property->priceHistory->first();

        return $latest && $latest->changed_at
            ? $this->dateTime($latest->changed_at)
            : self::FALLBACK_PRICE_DATE;
    }

    private function dateTime($value): string
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    /** Wartość składnika ceny: kwota stała, a gdy jej brak - cena za m2 x powierzchnia lokalu; bez kwoty "X". */
    private function componentValue($component, Property $property): string
    {
        $value = str_replace(',', '.', (string) $component->pivot->value);
        if (is_numeric($value) && (float) $value > 0) {
            return $this->money($value);
        }

        $valueM2 = str_replace(',', '.', (string) $component->pivot->value_m2);
        if (is_numeric($valueM2) && (float) $valueM2 > 0 && (float) $property->area > 0) {
            return $this->money((float) $valueM2 * (float) $property->area);
        }

        return 'X';
    }

    /** Kwota w zł: dwa miejsca po przecinku, przecinek dziesiętny (jak we wzorcowym CSV), bez separatora tysięcy. */
    private function money($amount): string
    {
        return number_format((float) $amount, 2, ',', '');
    }

    /** Współrzędna WGS-84: sześć miejsc po przecinku, przecinek dziesiętny; brak w panelu = "X". */
    private function coordinate($value): string
    {
        $value = str_replace(',', '.', trim((string) $value));

        return is_numeric($value) ? number_format((float) $value, 6, ',', '') : 'X';
    }

    /** Województwo: w panelu zapisany jest klucz bez polskich znaków ("lodzkie") - do raportu idzie nazwa ("łódzkie"). */
    private function province($value): string
    {
        $value = $this->value($value);
        $names = ProvinceTypes::getProvinces();

        return isset($names[$value]) ? mb_strtolower($names[$value]) : $value;
    }

    private function value($value): string
    {
        $value = is_string($value) ? trim($value) : $value;

        return ($value === null || $value === '') ? 'X' : (string) $value;
    }

    /**
     * Treść pliku CSV w formacie wzorcowego dokumentu MC: UTF-8 z BOM, separator pól ";", koniec wiersza CRLF.
     * Składane ręcznie (bez league/csv i bez parametru eol w fputcsv) - działa tak samo na każdej wersji PHP.
     */
    private function csvContent(array $rows): string
    {
        $lines = [$this->csvLine(self::REPORT_HEADERS)];
        foreach ($rows as $row) {
            $lines[] = $this->csvLine($row);
        }

        return "\xEF\xBB\xBF" . implode("\r\n", $lines) . "\r\n";
    }

    private function csvLine(array $cells): string
    {
        return implode(';', array_map(function ($cell) {
            // znak nowej linii w polu rozbiłby rekord - zamieniamy na spację
            $cell = preg_replace('/\s*[\r\n]+\s*/', ' ', (string) $cell);

            return (strpbrk($cell, ';"') !== false) ? '"' . str_replace('"', '""', $cell) . '"' : $cell;
        }, $cells));
    }

    public function generateMD5()
    {
        // Ścieżka do XML
        $xmlPath = public_path('datasets/developer.xml');

        // Sprawdź czy plik istnieje
        if (!file_exists($xmlPath)) {
            throw new \Exception("Plik XML nie istnieje: " . $xmlPath);
        }

        // Oblicz hash MD5 z pliku
        $md5Hash = md5_file($xmlPath);

        // Ścieżka do pliku .md5
        $md5Path = public_path('datasets/developer.md5');

        // Zapisz hash do pliku
        file_put_contents($md5Path, $md5Hash);

        return "Plik MD5 wygenerowany: " . $md5Path;
    }
    /**
     * Helper: tworzy <resource>
     */
    private function appendResource(DOMDocument $doc, $resourcesNode, $data)
    {
        $today = now()->format('Y-m-d');

        $resource = $doc->createElement("resource");
        $resource->setAttribute("status", "published");

        $resource->appendChild($doc->createElement("extIdent", $this->generateExtIdent()));

        $baseUrl = rtrim(config('app.url'), '/') . '/';
        $resource->appendChild(
            $doc->createElement(
                "url",
                $baseUrl . "datasets/".$data['file_csv']
            )
        );

        $title = $doc->createElement("title");
        $title->appendChild($doc->createElement("polish", "Ceny ofertowe mieszkań dewelopera {$data['company_name']} {$today}"));
        $title->appendChild($doc->createElement("english", "Offer prices for developer's apartments {$data['company_name']} {$today}"));
        $resource->appendChild($title);

        $description = $doc->createElement("description");
        $description->appendChild($doc->createElement("polish", "Dane dotyczące cen ofertowych mieszkań dewelopera {$data['company_name']} udostępnione {$today} zgodnie z art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)”[1]."));
        $description->appendChild($doc->createElement("english", "Data on offer prices of apartments of the developer {$data['company_name']} made available {$today} in accordance with art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $resource->appendChild($description);

        $resource->appendChild($doc->createElement("availability", "remote"));
        $resource->appendChild($doc->createElement("dataDate", $today));

        $specialSigns = $doc->createElement("specialSigns");
        $specialSign = $doc->createElement("specialSign", "X");
        $specialSigns->appendChild($specialSign);
        $resource->appendChild($specialSigns);

        $resource->appendChild($doc->createElement("hasDynamicData", "false"));
        $resource->appendChild($doc->createElement("hasHighValueData", "true"));
        $resource->appendChild($doc->createElement("hasHighValueDataFromEuropeanCommissionList", "false"));
        $resource->appendChild($doc->createElement("hasResearchData", "false"));
        $resource->appendChild($doc->createElement("containsProtectedData", "false"));

        $resourcesNode->appendChild($resource);
    }

    private function generateExtIdent($prefix = 'dewab_') {
        $date = date('Ymd'); // YYYYMMDD
        $lengthRandom = 36 - strlen($prefix) - strlen($date); // 36 - 6 - 8 = 22
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, $lengthRandom);
        return $prefix . $randomString . $date;
    }
}
