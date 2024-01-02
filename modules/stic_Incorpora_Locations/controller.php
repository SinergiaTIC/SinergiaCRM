<?php
require_once('include/MVC/Controller/SugarController.php');

/**
 * It retains the user from creating any other record in this module
 */
class stic_Incorpora_LocationsController extends SugarController
{
    function action_save(){
        if(empty($_REQUEST["record"])){
            $this->view = 'noaccess';
        }else{
            $this->view = 'edit';
        }
    }

}