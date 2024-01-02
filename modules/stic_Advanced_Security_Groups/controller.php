<?php
class stic_Advanced_Security_GroupsController extends SugarController
{
    public function action_checkIfRecordExist()
    {
        $moduleName = $_GET["moduleName"];
        //    global $mod_strings;
        $db = DBManagerFactory::getInstance();
        $id = $db->getOne("SELECT id FROM stic_advanced_security_groups WHERE name='{$moduleName}' AND deleted=0");

        if (empty($id)) {
            // Resultado OK --> se guardará el registro
            // $resp['ret'] = 1;
            echo json_encode('1');
        } else {
            // Resultado KO --> no se guardará el registro
            echo json_encode('0');
            // $resp['error'] = 1;
            // $resp['msg'] = "¡Error!";
            // $resp['ret'] = 0;
        }
        // echo json_encode($resp);
        die();
    }
}
