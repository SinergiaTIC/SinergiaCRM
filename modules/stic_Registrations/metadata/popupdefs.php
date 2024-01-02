<?php
$module_name = 'stic_Registrations';
$object_name = 'stic_Registrations';
$_module_name = 'stic_Registrations';
$popupMeta = array('moduleMain' => $module_name,
    'varName' => $object_name,
    'orderBy' => $_module_name . '.name',
    'whereClauses' => array('name' => $_module_name . '.name',
    ),
    'searchInputs' => array($_module_name . '_number', 'name', 'priority', 'status'),

);
