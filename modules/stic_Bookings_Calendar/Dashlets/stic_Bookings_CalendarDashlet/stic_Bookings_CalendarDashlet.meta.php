<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['stic_Bookings_CalendarDashlet'] = array(
    'module' => 'stic_Bookings_Calendar',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'stic_Bookings_Calendar'),
    'description' => 'LBL_DESCRIPTION', // array index in language pack
    'category' => 'Module Views',
);
