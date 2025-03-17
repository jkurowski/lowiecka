<?php
if (! function_exists('eventTitleIcon')) {
    function eventTitleIcon(int $number, string $title)
    {
        $events = Config::get('events');
        $event_index = array_search($number, array_column($events, 'id'));
        $event = $events[$event_index];
        return '<i class="'.$event['icon'].'"></i> <span>'.$title.'</span>';
    }
}
