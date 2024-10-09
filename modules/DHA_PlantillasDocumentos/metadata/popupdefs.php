<?php
/**
 * This file is part of Mail Merge Reports by Izertis.
 * Copyright (C) 2015 Izertis. 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * You can contact Izertis at email address info@izertis.com.
 */
// STIC-Custom 20241003 ART - Does not show available fields in Popup View
// https://github.com/SinergiaTIC/SinergiaCRM/pull/

// if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

// $module_name = 'DHA_PlantillasDocumentos';
// $object_name = 'DHA_PlantillasDocumentos';
// $_module_name = 'dha_plantillasdocumentos';
// $popupMeta = array(
//    'moduleMain' => $module_name,
//    'varName' => $object_name,
//    'orderBy' => $_module_name.'.name',
//    'whereClauses' => array(
//       'name' => $_module_name . '.name', 
//    ),
//    'searchInputs'=> array(
//       'name', 
//    ),
// );
$popupMeta = array(
    'moduleMain' => 'DHA_PlantillasDocumentos',
    'varName' => 'DHA_PlantillasDocumentos',
    'orderBy' => 'dha_plantillasdocumentos.name',
    'whereClauses' => array(
        'name' => 'dha_plantillasdocumentos.name',
    ),
    'searchInputs' => array(
        0 => 'name',
    ),
    'listviewdefs' => array(
        'FILE_URL' => array(
            'width' => '2%',
            'label' => '&nbsp;',
            'link' => true,
            'default' => true,
            'related_fields' => array(
                0 => 'file_ext',
            ),
            'sortable' => false,
            'studio' => false,
            'name' => 'file_url',
        ),
        'MODULO_URL' => array(
            'width' => '2%',
            'label' => '&nbsp;',
            'link' => false,
            'default' => true,
            'sortable' => false,
            'studio' => false,
            'name' => 'modulo_url',
        ),
        'DOCUMENT_NAME' => array(
            'width' => '25%',
            'label' => 'LBL_NAME',
            'link' => true,
            'default' => true,
            'name' => 'document_name',
        ),
        'MODULO' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_MODULO',
            'width' => '10%',
            'name' => 'modulo',
        ),
        'IDIOMA' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_IDIOMA_PLANTILLA',
            'width' => '10%',
            'name' => 'idioma',
        ),
        'STATUS_ID' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_DOC_STATUS',
            'width' => '10%',
            'name' => 'status_id',
        ),
        'ASSIGNED_USER_NAME' => array(
            'link' => 'assigned_user_link',
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'width' => '10%',
            'default' => true,
            'module' => 'Employees',
            'name' => 'assigned_user_name',
        ),
    ),
);
// END STIC-Custom
?>