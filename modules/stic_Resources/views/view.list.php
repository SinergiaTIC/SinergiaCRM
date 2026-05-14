<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_ResourcesViewList extends ViewList
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {
        $return_action = $_REQUEST['action'] ?? '';

        parent::display();

        SticViews::display($this);
        echo getVersionedScript("modules/stic_Resources/Utils.js");

        // Sets the custom where for mass update to discrimate between places and resources. This is needed because mass update does not process the custom where added in listview and updates all the records instead of only places or non-places.
        echo "<script>
            $(document).ready(function(){
                var massUpdateForm = $('#MassUpdate');
                $(\"#MassUpdate [name='return_action']\").val('{$return_action}');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'custom_where',
                    value: ' AND (stic_resources.type <> \"place\" OR stic_resources.type IS NULL)'
                }).appendTo(massUpdateForm);

            });
        </script>";

        // Write here you custom code
    }
    function listViewProcess() {
        $this->processSearchForm();
        $this->params['custom_where'] = ' AND (stic_resources.type <> "place" OR stic_resources.type IS NULL)';
        if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
            $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
            echo $this->lv->display();
        }
    }

}
