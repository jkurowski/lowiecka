<?php

use App\Models\ClientRules;

if (!function_exists('clientRule')) {
    function clientRule($client_rule)
    {
        if ($client_rule && isset($client_rule->status)) {
            return ruleStatusText($client_rule->rule_id).' '.ruleStatusBadge($client_rule->status);
        } else {
            return 'No record found';
        }
    }

    function ruleStatusText(int $number)
    {
        switch ($number) {
            case 1:
                return 'RODO - Zgoda na telefon:';
            case 2:
                return 'RODO - Zgoda na e-mail:';
            case 3:
                return 'RODO - Zgoda na profilowanie';
        }
    }

    function ruleStatusBadge(int $number)
    {
        switch ($number) {
            case 1:
                return '<span class="status-1 float-end">Tak</span>';
            default:
                return '<span class="status-2 float-end">Nie</span>';
        }
    }
}