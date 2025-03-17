<?php

namespace App\Http\Controllers\Admin\Developro\Import;

use App\Http\Controllers\Controller;
use App\Imports\ExcelImportClass;
use App\Models\Property;
use Maatwebsite\Excel\Facades\Excel;

class IndexController extends Controller
{
    public function index()
    {
        $importedData = Excel::toArray(new ExcelImportClass(), public_path('investment/import/p_5_b_b.xls'));

        $additionalAreaTypes = [
            'TARAS' => 'terrace_area',
            'BALKON' => 'balcony_area'
        ];


//        '1' => 'Mieszkanie / Apartament',
//        '2' => 'Komórka lokatorska',
//        '3' => 'Miejsce parkingowe'

//        '1' => 'Na sprzedaż',
//        '2' => 'Rezerwacja',
//        '3' => 'Sprzedane',
//        '4' => 'Wynajęte'

        foreach ($importedData as &$sheet) {

            // remove first row (header)
            unset($sheet[0]);

            foreach($sheet as $key => $row) {

                if($row['nazwa_powierzchni'] == "Mieszkanie"){

                $property = new Property();
                $property['investment_id'] = 2;
                $property->building_id = 4;
                $property->floor_id = $this->floor($row['pietro']);

//                if (isset($additionalAreaTypes[$row['powiechrznia_dod']])) {
//                    $property->{$additionalAreaTypes[$row['powiechrznia_dod']]} = $row['powiechrznia_dod_m'];
//                }

//Array
//(
//    [0] => Nazwa inwestycji
//    [1] => Nazwa powierzchni
//    [2] => Numer powierzchni
//    [3] => Cena Netto
//    [4] => Cena Brutto
//    [5] => Stawka Vat
//    [6] => Status
//)

                $property['status'] = $this->status($row['status']);
                $property['name'] = mb_convert_case(mb_strtolower($row['nazwa_powierzchni'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8').' '.$row['nr_powierzchni'];
                $property['name_list'] = mb_convert_case(mb_strtolower($row['nazwa_powierzchni'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                $property['number'] = $row['nr_powierzchni'];
                $property['number_order'] = $key + 1;

                $property['rooms'] = $row['pokoje'];
                $property['area'] = $row['metraz'];
                $property['area_search'] = $row['metraz_z_kropka'];
                $property['kitchen_type'] = $this->kuchnia($row['kuchniaaneks']);
//
//                if(isset($row[4])) {
//                    $property['price'] = round(floatval($row[4]) / 1.23);
//                }
//
                if(isset($row['cena_brutto'])) {
                    $property['price_brutto'] = $row['cena_brutto'];
                }
//
//                if(isset($row['cena_promocyjna'])){
//                    $property['price_promotion'] = $row['cena_promocyjna'];
//
//                    if (is_numeric($row['cena_promocyjna']) && $row['cena_promocyjna'] > 0) {
//                        $property['specialoffer'] = 1;
//                    } else {
//                        $property['specialoffer'] = 0;
//                    }
//                }
//
//                if(isset($row['cena_30_dni'])) {
//                    $property['price_30'] = $row['cena_30_dni'];
//                }
//
//                if(isset($row['widok_360'])) {
//                    $property['view_360'] = $row['widok_360'];
//                }
//                if(isset($row['spacer_3d'])) {
//                    $property['view_3d'] = $row['spacer_3d'];
//                }

//                if (strpos($row[1], 'KOMÓRKA LOKATORSKA') !== false) {
//                    $property['type'] = 2;
//                } else {
//                    $property['type'] = 3;
//                }

                $property['type'] = 1;
                $property['active'] = 1;
//
//                echo '<pre>';
//                print_r($property->toArray());
//                echo '</pre>';

                $property->save();
                }
            }
        }

        //return view('admin.developro.import.index', ['importedData' => $importedData[0]]);
    }

//    public function building($building) {
//        if (isset($building)) {
//            switch ($building) {
//                case 'D':
//                    return 1;
//                case 'E':
//                    return 2;
//                default:
//                    return 0; // Return 0 for unknown buildings
//            }
//        }
//        return 0; // Return 0 if building is not set
//    }

    public function status($status) {
        if (isset($status)) {
            switch ($status) {
                case 'sprzedane':
                    return 3;
                case 'zarezerwowane':
                    return 2;
                default:
                    return 1; // Return 0 for unknown buildings
            }
        }
        return 1; // Return 0 if building is not set
    }

    public function kuchnia($kuchnia) {
        switch ($kuchnia) {
            case 'aneks':
                return 1;
            case 'kuchnia':
                return 2;
            default:
                return 0;
        }
    }

    public function floor($pietro) {
        switch ($pietro) {

            // P4 - B: A
//            case 'parter':
//                return 1;
//            case 'piętro 1':
//                return 2;
//            case 'piętro 2':
//                return 3;
//            case 'piętro 3':
//                return 4;
            // P4 - B: B
//            case 'parter':
//                return 5;
//            case 'piętro 1':
//                return 6;
//            case 'piętro 2':
//                return 7;
//            case 'piętro 3':
//                return 8;
            // P5 - B: A
            case 'parter':
                return 13;
            case 'piętro 1':
                return 14;
            case 'piętro 2':
                return 15;
            case 'piętro 3':
                return 16;
            default:
                return 0;
        }
    }
}

//Poligonowa 4 - ID: 1
//- Budynek A - ID: 1
//-- Parter - ID: 1
//-- Piętro 1 - ID: 2
//-- Piętro 2 - ID: 3
//-- Piętro 3 - ID: 4
//
//- Budynek B - ID: 2
//-- Parter - ID: 5
//-- Piętro 1 - ID: 6
//-- Piętro 2 - ID: 7
//-- Piętro 3 - ID: 8
//
//Poligonowa 5 - ID: 2
//- Budynek A - ID: 3
//-- Parter - ID: 9
//-- Piętro 1 - ID: 10
//-- Piętro 2 - ID: 11
//-- Piętro 3 - ID: 12
//
//- Budynek B - ID: 4
//-- Parter - ID: 13
//-- Piętro 1 - ID: 14
//-- Piętro 2 - ID: 15
//-- Piętro 3 - ID: 16