<?php

/**
 * Katini
 * Bootstrap Icons Helper
 */
if (! function_exists('bi')) {
    function bi(string $alias, bool $fill = false): string
    {
        $icon = match ($alias) {
            default => $alias,
            // Navigation and actions
            'add'      => 'plus-lg',
            'delete'   => 'x-lg',
            'donation' => 'cash-coin',
            'edit'     => 'pencil',
            'grip'     => 'grip-vertical',
            'list'     => 'list-ul',
            'refresh'  => 'arrow-clockwise',
            'save'     => 'check-lg',
            'find'     => 'search',

            // Time and Date
            'date'    => 'calendar2-event',
            'home'    => 'house',
            'mission' => 'globe',
            'time'    => 'clock',
            'today'   => 'calendar2-check',

            // Settings
            'user' => 'person',

            // Other
            'dot' => 'dot',
        };

        if ($fill) {
            $icon .= '-fill';
        }

        return "<i class=\"bi bi-{$icon}\"></i>";
    }
}
