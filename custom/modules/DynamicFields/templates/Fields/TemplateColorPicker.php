<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/DynamicFields/templates/Fields/TemplateField.php');
class TemplateColorPicker extends TemplateField{
    var $type='ColorPicker';

    function get_field_def(){
        $def = parent::get_field_def();
        $def['dbType'] = 'varchar';
        return $def;
    }
}