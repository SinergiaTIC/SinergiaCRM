<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class FollowUps_Incremental {
    function incremental($bean, $event, $arguments) {

        $is_new = empty($bean->fetched_row['id']);

        $GLOBALS['log']->fatal("BÚSTIA ÈTICA: Hook disparat. Tipus: " . $bean->type . " | Nou: " . ($is_new ? 'SÍ' : 'NO'));

        if ($is_new && $bean->type == 'complaint') {
            $db = DBManagerFactory::getInstance();
            if (empty($bean->num_seguiment_c)) {
                $query = "SELECT MAX(CAST(c.num_seguiment_c AS UNSIGNED)) as max_val 
                          FROM stic_followups_cstm c
                          INNER JOIN stic_followups m ON c.id_c = m.id
                          WHERE m.type = 'complaint' AND m.deleted = 0";
                $result = $db->query($query);
                $row = $db->fetchByAssoc($result);
                $bean->num_seguiment_c = ($row['max_val']) ? intval($row['max_val']) + 1 : 1;
                $GLOBALS['log']->fatal("BÚSTIA ÈTICA: S'ha assignat el número secuencial: " . $nextNum);
            }
        } else {
            // Log en cas que no entri a la condició per saber per què
            $GLOBALS['log']->fatal("BÚSTIA ÈTICA: No s'ha aplicat numeració. Tipus: " . $bean->type . " | New: " . (empty($bean->id) ? 'Sí' : 'No'));
        }
    }
}
