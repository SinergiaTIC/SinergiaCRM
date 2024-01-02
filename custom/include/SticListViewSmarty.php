<?php
// Extension of class ListViewSmarty to allow MassUpdate of field types not allowed by default by SugarCRM

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/ListView/ListViewSmarty.php';
require_once 'custom/include/SticMassUpdate.php';

class SticListViewSmarty extends ListViewSmarty
{

    /**
     * @return MassUpdate instance
     */
    protected function getMassUpdate()
    {
        return new CustomMassUpdate();
    }

}
