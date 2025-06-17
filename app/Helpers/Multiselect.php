<?php

if (! function_exists('multiselect')) {
    function multiselect($jsondata)
    {
        if ($jsondata) {
            $selectedValues = json_decode($jsondata, true); // decode as array
            if (is_array($selectedValues)) {
                return array_map('intval', $selectedValues);
            }
        }

        return [];
    }
}
