<?php

if (! function_exists('multiselect')) {
    function multiselect($jsondata)
    {
        if ($jsondata) {
            $selectedValues = json_decode($jsondata);
            return array_map('intval', $selectedValues);
        } else {
            return [];
        }
    }
}
