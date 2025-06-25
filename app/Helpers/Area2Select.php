<?php

if (! function_exists('area2Select')) {
    function area2Select($range): string
    {
        $var = explode(',', $range);
        $html = '';
        foreach ($var as $pozycja) {
            $html .= '<li data-value="'.$pozycja.'"><a class="dropdown-item" href="#">'.$pozycja.' m<sup>2</sup></a></li>';
        }
        return $html;
    }
}


