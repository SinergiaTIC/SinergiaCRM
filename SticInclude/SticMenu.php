<?php

/**
 * Genera un menú HTML a partir de una lista de elementos.
 *
 * Esta función construye una lista HTML para un menú de navegación utilizando
 * una estructura recursiva si los elementos tienen submenús. Se apoya en variables
 * globales para obtener los textos correspondientes de los ítems del menú.
 *
 * @param array $items Los ítems del menú a procesar, cada ítem puede contener subítems.
 * @return string El código HTML del menú generado.
 */
function generateMenu($items, $isFirstLevel = true)
{
    global $app_list_strings, $app_strings; // Accede a las listas de strings definidas en el ámbito global.

    $html = '<ul id="main-menu" class="sm sm-blue">'; // Inicia la construcción del menú principal.
    if ($isFirstLevel) {
        $html .= '<li><a href="index.php?module=Home&action=index"><i class="glyphicon glyphicon-home"></i></a></li>';
    }
    foreach ($items as $item) {
        // Itera sobre cada elemento del menú.

        // Intenta obtener el texto del ítem desde los strings de módulo o usa el id como último recurso.
        $text = ($app_list_strings['moduleList'][$item['id']] ?? ''); // Primer intento con moduleList.
        if (empty($text)) {
            $text = $app_strings[$item['id']]; // Segundo intento con app_strings.
        }
        if (empty($text)) {
            $text = str_replace('_', ' ', $item['id']); // Usa el ID del ítem, reemplazando guiones bajos por espacios.
        }

        // Determina si el ítem actual tiene submenús.
        $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
        $html .= '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>'; // Agrega la clase 'dropdown' si hay submenús.

        if ($hasChildren) {
            $html .= "<a href='#'>" . $text . '</a>'; // Crea un enlace para el ítem del menú.
            $html .= generateMenu($item['children'], false); // Recursivamente genera menús para los subítems.
        } else {

            $html .= "<a href='index.php?module={$item['id']}&action=index&return_module=Accounts&return_action=DetailView'>" . $text . '</a>'; // Crea un enlace para el ítem del menú.
        }
        $html .= '</li>'; // Cierra el ítem del menú.
    }
    $html .= '</ul>'; // Finaliza la lista del menú.

    return $html; // Retorna el HTML construido.
}

function addMenuProperties(&$array)
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
            addMenuProperties($value); // Recursivamente procesar sub-arrays
        }
    }
}
