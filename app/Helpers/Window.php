<?php

if (!function_exists('window')) {
    function window(array|int|string $numbers): ?string
    {
        $directions = [
            1 => 'Północ',
            2 => 'Południe',
            3 => 'Wschód',
            4 => 'Zachód',
            5 => 'Północny wschód',
            6 => 'Północny zachód',
            7 => 'Południowy wschód',
            8 => 'Południowy zachód',
            9 => 'Wschód, Północ',
            10 => 'Wschód, Południe',
            11 => 'Zachód, Północ',
            12 => 'Zachód, Południe'
        ];

        // Handle JSON string (from database)
        if (is_string($numbers)) {
            $decoded = json_decode($numbers, true);
            if (is_array($decoded)) {
                $numbers = $decoded;
            } else {
                $numbers = explode(',', $numbers);
            }
        }

        // Convert all values to integers
        $numbers = array_map('intval', (array) $numbers);

        $result = [];

        foreach ($numbers as $number) {
            if (isset($directions[$number])) {
                $result[] = $directions[$number];
            }
        }

        return !empty($result) ? implode(', ', $result) : null;
    }
}
