<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$db = DBManagerFactory::getInstance();
if ($db instanceof DBManager) 
{
    // Get all non-deleted records from stic_work_calendar where weekday is empty
    $query = "SELECT id, start_date FROM stic_work_calendar WHERE (weekday IS NULL OR weekday = '') AND deleted = 0";
    $db = DBManagerFactory::getInstance();
    $result = $db->query($query);
    
    while ($row = $db->fetchByAssoc($result)) 
    {
        $bean = BeanFactory::getBean('stic_Work_Calendar', $row['id']);
        if (!$bean) {
            $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.": No existe un registro de Calendario laboral con id = {$row['id']}");
            continue;
        }
    
        // Convert the date formatted as text to a DateTime
        $startDateTimeInUTC = $timedate->fromDbFormat($row['start_date'], TimeDate::DB_DATETIME_FORMAT);
        if ($startDateTimeInUTC) {
            // Calculate the day of the week (0 = Sunday, 6 = Saturday) and save in database
            $weekday = date('w', strtotime($startDateTimeInUTC));
            $query = "UPDATE stic_work_calendar SET weekday = '{$weekday}' WHERE id = '{$row['id']}'";
            $res = $db->query($query);
            if ($res){
                echo "Actualizado el registro {$bean->name} con weekday = {$weekday}<br />";
            } else {
                echo "No se ha podido actualizar el registro {$bean->name} con weekday = {$weekday}<br />";
            }
        }
    }
} else {
    $GLOBALS['log']->fatal('Line '.__LINE__.': '.__METHOD__.': DBManager is not set');
}