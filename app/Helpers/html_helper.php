<?php

/**
 * Custom select
 */
if (! function_exists('custom_select')) {
    function custom_select(array $options, $selected, array $settings = [])
    {
        $name  = $settings['name'] ?? 'customSelect';
        $id    = $settings['id'] ?? $name;
        $class = $settings['class'] ?? 'form-select';

        $html = "<select name='{$name}' id='{$id}' class='{$class}'>";

        foreach ($options as $value => $label) {
            $html .= "<option value='{$value}' " . ($value === $selected ? ' selected' : '') . ">{$label}</option>";
        }

        $html .= '</select>';

        return $html;
    }
}
