<?php

class stic_SettingsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        // Force always settings name uppercase and without blank spaces
        $bean->name = strtoupper(str_replace(' ', '_', trim($bean->name)));
    }

    public function after_save(&$bean, $event, $arguments)
    {
        // If color changes, compile subtheme css
        if ($bean->name == 'GENERAL_CUSTOM_THEME_COLOR' && $bean->fetched_row['value'] != $bean->value) {
            include_once 'SticInclude/SticCustomScss.php';
        }
    }

}
