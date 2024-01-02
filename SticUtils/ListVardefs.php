<?php
// Este fichero genera una lista en formato csv (para copiar y pegar) con los detalles de todo los campos de módulos de sinergiatic y los campos custom de sinergiatic (con prefijo stic_) de módulos de suitecrm
// Para que funcione, incluir al principio de la función pre_Display() del fichero SticInclude/Views.php la linea
// include_once 'SticUtils/ListVardefs.php';
// y mostrar cualquier vista  de un modulo standar de stic


$module_list = array_intersect($GLOBALS['moduleList'], array_keys($GLOBALS['beanList']));

foreach ($module_list as $module_name) {
    $bean = BeanFactory::getBean($module_name);
    $field_defs[$module_name] = $bean->getFieldDefinitions();

}

$checkList = array(
    'audited',
    'comments',
    'custom_module',
    'dbType',
    'default',
    'display_default',
    'duplicate_merge',
    'duplicate_merge_dom_value',
    'enable_range_search',
    'full_text_search',
    'help',
    'importable',
    'inline_edit',
    'isnull',
    'len',
    'link',
    'massupdate',
    'merge_filter',
    'module',
    'name',
    'no_default',
    'options',
    'reportable',
    'required',
    'rname',
    'size',
    'source',
    'table',
    'type',
    'unified_search',
    'vname',
);

$exclude = array('id', 'name', 'date_entered', 'date_modified', 'description', 'modified_user_id', 'modified_by_name', 'created_by', 'created_by_name', 'deleted', 'created_by_link', 'modified_user_link', 'assigned_user_id', 'assigned_user_name', 'assigned_user_link', 'SecurityGroups');

$tx .= "Módulo;Campo;";
foreach ($checkList as $value3) {
    $tx .= $value3 . ';';
}
foreach ($exclude as $value) {
    unset($GLOBALS["dictionary"][$module]["fields"][$value]);
}

foreach ($field_defs as $key => $module) {
    if (substr($key, 0, 5) === 'stic_') {
        foreach ($module as $key2 => $campo) {
            $tx .= "<br>$key;";
            $tx .= "$key2;";

            foreach ($checkList as $value3) {
                $value3 = $field_defs[$key][$key2][$value3];
                $value3 = is_string($value3) ? trim($value3) : $value3;
                //     // $tx .= "$value3;";
                $tx .= $value3 . ";";
            }

        }
    } else {
        foreach ($module as $key2 => $campo) {
            if (substr($key2, 0, 5) === 'stic_') {
                $tx .= "<br>$key;";
                $tx .= "$key2;";

                foreach ($checkList as $value3) {
                    $value3 = $field_defs[$key][$key2][$value3];
                    $value3 = is_string($value3) ? trim($value3) : $value3;
                    $tx .= $value3 . ";";
                }
            }

        }
    }

}

echo ($tx);

