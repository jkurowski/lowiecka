<?php

namespace App\Http\Controllers\Api\History;

use App\Helpers\PropertyAreaTypes;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use DOMDocument;
use DOMXPath;
use League\Csv\Writer;
use SplTempFileObject;

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
        foreach ($investments as $investment) {

            $filename = "ceny-ofertowe-" . $investment->slug . "-" . $today . ".csv";
            $csvPath = public_path("datasets/{$filename}");

            $csv = Writer::createFromPath($csvPath, 'w+');
            $csv->insertOne(self::REPORT_HEADERS);

            foreach ($this->reportProperties($investment) as $property) {
                $csv->insertOne($this->reportRow($investment, $property));
            }

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

        foreach ($this->reportProperties($investment) as $property) {
            echo '<tr>';
            foreach ($this->reportRow($investment, $property) as $cell) {
                echo '<td>' . e($cell) . '</td>';
            }
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    /**
     * Nazwy pól wg załącznika do rozporządzenia Ministra Cyfryzacji w sprawie struktury danych
     * zawartych w informacjach dotyczących sprzedaży lokali mieszkalnych i domów jednorodzinnych
     * (obowiązuje od 11.11.2026). Kolejność kolumn = kolejność pól w załączniku (grupy A-J, 57 pól).
     */
    private const REPORT_HEADERS = [
        // A. Podmiot obowiązany
        "Firma",
        "Forma prawna",
        "Numer w Krajowym Rejestrze Sądowym (KRS)",
        "Numer wpisu do Centralnej Ewidencji i Informacji o Działalności Gospodarczej (CEIDG)",
        "Numer Identyfikacji Podatkowej (NIP)",
        "Numer identyfikacyjny REGON",
        "Numer telefonu",
        "Adres poczty elektronicznej",
        "Adres strony internetowej",

        // B. Adres siedziby albo głównego miejsca wykonywania działalności
        "Województwo adresu siedziby albo głównego miejsca wykonywania działalności",
        "Powiat adresu siedziby albo głównego miejsca wykonywania działalności",
        "Gmina adresu siedziby albo głównego miejsca wykonywania działalności",
        "Miejscowość adresu siedziby albo głównego miejsca wykonywania działalności",
        "Ulica adresu siedziby albo głównego miejsca wykonywania działalności",
        "Numer nieruchomości adresu siedziby albo głównego miejsca wykonywania działalności",
        "Numer lokalu adresu siedziby albo głównego miejsca wykonywania działalności",
        "Kod pocztowy adresu siedziby albo głównego miejsca wykonywania działalności",

        // C. Adres lokalu, w którym prowadzona jest sprzedaż
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

        // D. Lokalizacja przedsięwzięcia deweloperskiego albo zadania inwestycyjnego
        "Województwo lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Powiat lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Gmina lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Miejscowość lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Ulica lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Numer nieruchomości lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
        "Kod pocztowy lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",

        // E. Lokal mieszkalny albo dom jednorodzinny
        "Rodzaj nieruchomości",
        "Numer lokalu mieszkalnego albo domu jednorodzinnego, nadany przez podmiot obowiązany",
        "Cena 1 m2 powierzchni użytkowej lokalu mieszkalnego albo domu jednorodzinnego",
        "Data, od której obowiązuje cena 1 m2 powierzchni użytkowej lokalu mieszkalnego albo domu jednorodzinnego",
        "Cena lokalu mieszkalnego albo domu jednorodzinnego, będącego przedmiotem umowy, stanowiąca iloczyn ceny m2 oraz powierzchni użytkowej",
        "Data, od której obowiązuje cena lokalu mieszkalnego albo domu jednorodzinnego, będącego przedmiotem umowy, stanowiąca iloczyn ceny m2 oraz powierzchni użytkowej",
        "Cena lokalu mieszkalnego albo domu jednorodzinnego, obejmująca łączną cenę całej nieruchomości albo jej części będącej przedmiotem umowy oraz cenę i świadczenia pieniężne, o których mowa w art. 19a ust. 1 pkt 2 i 3 ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym",
        "Data, od której obowiązuje cena lokalu mieszkalnego albo domu jednorodzinnego, obejmująca łączną cenę całej nieruchomości albo jej części będącej przedmiotem umowy oraz cenę i świadczenia pieniężne, o których mowa w art. 19a ust. 1 pkt 2 i 3 ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym",

        // F. Części nieruchomości będące przedmiotem umowy
        "Rodzaj części nieruchomości będącej przedmiotem umowy",
        "Oznaczenie części nieruchomości nadane przez podmiot obowiązany",
        "Cena części nieruchomości będącej przedmiotem umowy",
        "Data, od której obowiązuje cena części nieruchomości, będących przedmiotem umowy",

        // G. Pomieszczenia przynależne
        "Rodzaj pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Oznaczenie pomieszczenia przynależnego, o którym mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Wyszczególnienie cen pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
        "Data, od której obowiązuje cena wyszczególnionych pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",

        // H. Prawa niezbędne do korzystania z lokalu mieszkalnego albo domu jednorodzinnego
        "Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
        "Wartość praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
        "Data, od której obowiązuje wartości praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",

        // I. Inne świadczenia pieniężne
        "Wyszczególnienie rodzajów innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",
        "Wartość innego świadczenia pieniężnego, które nabywca zobowiązany jest spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",
        "Data, od której obowiązuje cena wartości innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz podmiotu obowiązanego w wykonaniu umowy przenoszącej własność",

        // J. Dodatkowe dane
        "Adres strony internetowej, pod którym jest dostępny prospekt informacyjny",
    ];

    /**
     * Data obowiązywania ceny, gdy lokal nie ma wpisu w historii cen (property_prices).
     * Historia cen jest prowadzona od 16.09.2025; wcześniej raport podawał stałą datę 09.09.2025.
     */
    private const FALLBACK_PRICE_DATE = '2025-09-09';

    private function reportProperties(Investment $investment)
    {
        return $investment->activeProperties()
            ->with(['priceHistory', 'relatedProperties.priceHistory', 'priceComponents'])
            ->get();
    }

    /**
     * Jeden rekord raportu = jeden lokal (mieszkanie albo część nieruchomości), kolumny jak REPORT_HEADERS.
     * Pole bez zastosowania = "X".
     */
    private function reportRow(Investment $investment, Property $property): array
    {
        $company = $investment->company;
        $salePoint = $investment->salePoint;

        $isApartment = $property->type == PropertyAreaTypes::ROOM_APARTMENT;
        $price = $this->offerPrice($property);
        $priceDate = $this->priceDate($property);

        $related = $isApartment ? $property->relatedProperties : collect();
        $relatedWithPrice = $related->filter(fn ($p) => $this->offerPrice($p) !== null);
        $components = $property->priceComponents;

        $baseUrl = rtrim(config('app.url'), '/') . '/';

        $row = [
            // A
            $this->value($company?->name),
            $this->value($company?->legal_form),
            $this->value($company?->krs_number),
            $this->value($company?->ceidg_number),
            $this->value($company?->nip),
            $this->value($company?->regon),
            $this->value($company?->phone),
            $this->value($company?->email),
            $this->value($company?->website),

            // B
            $this->value($company?->province),
            $this->value($company?->district),
            $this->value($company?->municipality),
            $this->value($company?->city),
            $this->value($company?->street),
            $this->value($company?->building_number),
            $this->value($company?->apartment_number),
            $this->value($company?->postal_code),

            // C
            $this->value($salePoint?->province),
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
            $this->value($investment->inv_province),
            $this->value($investment->inv_county),
            $this->value($investment->inv_municipality),
            $this->value($investment->inv_city),
            $this->value($investment->inv_street),
            $this->value($investment->inv_property_number),
            $this->value($investment->inv_postal_code),

            // E - tylko dla lokalu mieszkalnego
            $isApartment ? 'lokal mieszkalny' : 'X',
            $isApartment ? $this->value($property->number) : 'X',
            ($isApartment && $price !== null && (float) $property->area > 0)
                ? $this->money($price / (float) $property->area)
                : 'X',
            $isApartment ? $priceDate : 'X',
            ($isApartment && $price !== null) ? $this->money($price) : 'X',
            $isApartment ? $priceDate : 'X',
            ($isApartment && $price !== null)
                ? $this->money($price + $relatedWithPrice->sum(fn ($p) => $this->offerPrice($p)))
                : 'X',
            $isApartment ? $this->latestDate(array_merge([$priceDate], $relatedWithPrice->map(fn ($p) => $this->priceDate($p))->all())) : 'X',

            // F - komórki, miejsca postojowe itp. raportowane jako osobne rekordy
            !$isApartment ? $this->value(PropertyAreaTypes::getStatusText($property->type)) : 'X',
            !$isApartment ? $this->value($property->number) : 'X',
            (!$isApartment && $price !== null) ? $this->money($price) : 'X',
            (!$isApartment && $price !== null) ? $priceDate : 'X',

            // G - pomieszczenia przynależne powiązane z mieszkaniem
            $relatedWithPrice->isNotEmpty()
                ? $relatedWithPrice->map(fn ($p) => PropertyAreaTypes::getStatusText($p->type) ?? 'X')->implode(', ')
                : 'X',
            $relatedWithPrice->isNotEmpty()
                ? $relatedWithPrice->map(fn ($p) => $p->number ?? 'X')->implode(', ')
                : 'X',
            $relatedWithPrice->isNotEmpty()
                ? $relatedWithPrice->map(fn ($p) => $this->money($this->offerPrice($p)))->implode(', ')
                : 'X',
            $relatedWithPrice->isNotEmpty()
                ? $this->latestDate($relatedWithPrice->map(fn ($p) => $this->priceDate($p))->all())
                : 'X',

            // H - brak takich praw w ofercie
            'X',
            'X',
            'X',

            // I
            $components->isNotEmpty()
                ? $components->map(fn ($c) => $c->pivot->category ?? 'X')->implode(', ')
                : 'X',
            $components->isNotEmpty()
                ? $components->map(fn ($c) => is_numeric($c->pivot->value) ? $this->money($c->pivot->value) : 'X')->implode(', ')
                : 'X',
            $components->isNotEmpty()
                ? $this->latestDate($components->map(fn ($c) => optional($c->pivot->updated_at)->toDateString() ?? $priceDate)->all())
                : 'X',

            // J
            $investment->file_brochure
                ? $baseUrl . 'investment/brochure/' . $investment->file_brochure
                : 'X',
        ];

        if (count($row) !== count(self::REPORT_HEADERS)) {
            throw new \LogicException('Raport cen: liczba wartości (' . count($row) . ') różna od liczby kolumn (' . count(self::REPORT_HEADERS) . ')');
        }

        return $row;
    }

    /** Cena ofertowa - przy aktywnej promocji cena promocyjna (tak jak na stronie i w historii cen). */
    private function offerPrice(Property $property): ?float
    {
        if ($property->highlighted && (float) $property->promotion_price > 0) {
            return (float) $property->promotion_price;
        }

        return (float) $property->price_brutto > 0 ? (float) $property->price_brutto : null;
    }

    /** Data, od której obowiązuje aktualna cena (RRRR-MM-DD) - ostatnia zmiana z historii cen. */
    private function priceDate(Property $property): string
    {
        $latest = $property->priceHistory->first();

        return $latest && $latest->changed_at
            ? $latest->changed_at->toDateString()
            : self::FALLBACK_PRICE_DATE;
    }

    private function latestDate(array $dates): string
    {
        $dates = array_filter($dates);

        return $dates ? max($dates) : self::FALLBACK_PRICE_DATE;
    }

    /** Kwota w zł z dokładnością do dwóch miejsc po przecinku. */
    private function money($amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    private function value($value): string
    {
        $value = is_string($value) ? trim($value) : $value;

        return ($value === null || $value === '') ? 'X' : (string) $value;
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
