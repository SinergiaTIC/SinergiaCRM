<?php

function generateMenu($items)
{
    global $app_list_strings, $app_strings;
    $html = '<ul id="main-menu" class="sm sm-blue">';
    foreach ($items as $item) {

        $text = ($app_list_strings['moduleList'][$item['id']] ?? '');
        if (empty($text)) {
            $text = $app_strings[$item['id']];
        }
        if (empty($text)) {
            $text = str_replace('_', ' ', $item['id']);
        }

        $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
        $html .= '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>';
        $html .= '<a href="#">' . $text . '</a>';
        if ($hasChildren) {
            $html .= generateMenu($item['children']);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function addTextProperty(&$array)
{
    global $app_list_strings, $app_strings;
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            if (isset($value['id'])) {
                $value['text'] = ($app_list_strings['moduleList'][$value['id']] ?? '');
                if (empty($value['text'])) {
                    $value['text'] = $app_strings[$value['id']];
                }
                if (empty($value['text'])) {
                    $value['text'] = str_replace('_', ' ', $value['id']);
                }
            }
            addTextProperty($value); // Recursivamente procesar sub-arrays
        }
    }
}