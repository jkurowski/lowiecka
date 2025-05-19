<?php

if (!function_exists('prevent_orphans')) {
    function prevent_orphans($content) {
        return preg_replace_callback('/>[\s\S]*?</', function($matches) {
            return preg_replace(
                '/(\s)([a-zA-ZąćęłńóśżźĄĆĘŁŃÓŚŻŹ0-9,.!?()\'"]{1,2})(\s)/u',
                '$1$2&nbsp;',
                $matches[0]
            );
        }, $content);
    }
}
