<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/SugarView.php';

class stic_RemittancesViewload_file extends SugarView
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {

        echo <<<SCRIPT
        <script type='text/javascript' src='cache/include/javascript/sugar_grp_yui_widgets.js'></script>
        <script>
        YAHOO.SUGAR.MessageBox.show({
            width: 'auto',
            msg: '<form id="miform" action="index.php?module=stic_Remittances&action=loadSEPAReturns" method="post" enctype="multipart/form-data"><input type="file" name="file" size="10"> <br><center><input type="button" onclick="this.form.submit();" value="' + SUGAR.language.get("stic_Remittances", "LBL_SEPA_RETURN_LOAD_FILE") + '"></center></form>',
            title: SUGAR.language.get('stic_Remittances', 'LBL_SEPA_RETURN_SELECT_FILE')
        });

        </script>
SCRIPT;

    }
}
