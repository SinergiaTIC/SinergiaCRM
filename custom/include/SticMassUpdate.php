<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MassUpdate.php';
require_once 'include/utils.php';

class CustomMassUpdate extends MassUpdate
{

    /**
     * Method override to allow MassUpdate for ColorPicker type fields
     * STIC#4
     * STIC#336
     * 
     * @param string $displayname field label
     * @param string $field field name
     * @param bool $even even or odd
     * @return string html field data
     */
    protected function addDefault($displayname, $field, &$even)
    {
        $even = !$even;
        $varname = $field["name"];
        $displayname = addslashes($displayname);

        if(in_array($field["type"], array('ColorPicker'))) {
            $scriptColorPicker = getVersionedScript("SticInclude/vendor/jqColorPicker/jqColorPicker.min.js");
            $html = <<<EOQ
                <td scope="row" width="20%">$displayname</td>
                <td class="dataField" width="30%"><input autocomplete="off" type="text" name="$varname" style="width: auto; " id="mass_{$varname}" value=""></td>
                $scriptColorPicker
                <script type="text/javascript">
                    $("#mass_$varname").colorPicker({
                        renderCallback: function(elm, toggled) { if (elm.val()) { var colors = this.color.colors; elm.val('#' + colors.HEX); } },
                        opacity: false});
                </script>
            EOQ;
            return $html;
        } else {
            return '';
        }
    }

}
