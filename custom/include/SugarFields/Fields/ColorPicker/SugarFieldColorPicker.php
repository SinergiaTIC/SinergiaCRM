<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldColorPicker extends SugarFieldBase
{
    public function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch('custom/include/SugarFields/Fields/ColorPicker/SearchView.tpl');
    }
}
