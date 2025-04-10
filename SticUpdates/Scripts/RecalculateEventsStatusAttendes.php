<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');




// Este script recalcula los estados de asistencia de los eventos donde se haya creado una inscripción 
// a partir de la fecha del primer despliegue de SinergiaCRM 2.0 (2025-02-20)

// Mapear la columna de la base de datos con los campos del CRM
$bbddColumnToCRMFieldMap = [
    'uninvited'        => 'status_not_invited',
    'invited'          => 'status_invited',
    'confirmed'        => 'status_confirmed',
    'rejected'         => 'status_rejected',
    'maybe'            => 'status_maybe',
    'not_participate'  => 'status_didnt_take_part',
    'participates'     => 'status_took_part',
    'dropped'          => 'status_drop_out',
];

$db = DBManagerFactory::getInstance();
if ($db instanceof DBManager) 
{
    // Seleccionar el ID de los eventos donde se haya creado una inscripción a partir de la fecha
    $fecha = '2025-02-20';
    $query = "
                SELECT DISTINCT(srse.stic_registrations_stic_eventsstic_events_ida) as id 
                FROM stic_registrations as sr 
                JOIN stic_registrations_stic_events_c srse 
                    ON sr.id = srse.stic_registrations_stic_eventsstic_registrations_idb 
                    AND srse.deleted = 0 
                WHERE DATE(sr.date_entered) >= '". $fecha ."';
    ";

    echo "<br /><br />";
    echo "Consulta para obtener los IDs de los eventos donde se haya creado una inscripción a partir de la fecha:" . $fecha;
    echo "<br /><br />";
    echo "<span style='color:blue'>" . $query . "</span>";
    echo "<br /><br />";

    $res = $db->query($query);
    $eventIds = '';
    while ($row = $db->fetchByAssoc($res)) 
    {
        $eventIds .= "'" . $row['id'] . "',";
    }
    // Eliminar última coma
    $eventIds = substr($eventIds, 0, -1);

    
    $query = "SELECT id, name FROM stic_events WHERE deleted = 0 AND id IN (" . $eventIds. ");";
    $res = $db->query($query);
    
    echo "<br /><br />";
    echo "<b>EVENTOS:</b>";

    while ($row = $db->fetchByAssoc($res)) 
    {
        echo "<br /><br />";
        echo "--- " .$row['name'] . " ---";

        $query = '
                SELECT
                    sr.status,
                    sum(attendees) as total 
                FROM
                    stic_registrations sr
                INNER JOIN stic_registrations_stic_events_c srse ON
                    sr.id = srse.stic_registrations_stic_eventsstic_registrations_idb
                    AND srse.deleted = 0
                WHERE
                    srse.stic_registrations_stic_eventsstic_events_ida = "'. $row['id'] . '"
                    AND sr.deleted = 0
                    AND srse.deleted=0
                GROUP BY
                    sr.status
        ';

        echo "<br /><br />";
        echo "Consulta para obtener el recuento de los estados de asistencia a través de las inscripciones:";
        echo "<br /><br />";
        echo "<span style='color:blue'>" . $query . "</span>";

        $query2 = "UPDATE stic_events SET ";
        $res2 = $db->query($query);
        while ($row2 = $db->fetchByAssoc($res2)) 
        {         
            $query2 .= $bbddColumnToCRMFieldMap[$row2["status"]] . ' = "' . $row2["total"] . '",';
        }

        // Eliminar última coma
        $query2 = substr($query2, 0, -1);

        // Añadir condición para que modifique el evento actual
        $query2 .= ' WHERE id = "' . $row['id'] .'"';
    
        echo "<br /><br />";
        echo "Consulta para actualizar los estados de asistencia del evento:";
        echo "<br /><br />";
        echo "<span style='color:blue'>" . $query2 . "</span>";

        // Actualizar los campo de estado de asistentes en el evento
        $res2 = $db->query($query2);
    }
}