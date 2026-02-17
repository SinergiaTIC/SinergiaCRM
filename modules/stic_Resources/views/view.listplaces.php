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

class stic_ResourcesViewListPlaces extends ViewList
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
        global $mod_strings;

        $return_action = $_REQUEST['action'] ?? '';

        parent::display();

        echo getVersionedScript("modules/stic_Resources/Utils.js");

        SticViews::display($this);
        
        echo "<script>
            $(document).ready(function(){
                $('.moduleTitle').html('<h2>{$mod_strings['LBL_PLACES']}</h2>');

                // Clear filter in Places must mantain the user in the places list not returning to resources list
                var clearButtons = $('.clearSearchIcon');
                clearButtons.attr('onclick', '');
                clearButtons.on('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation(); // Evita interferències amb altres scripts
                    // Defineix aquí la teva URL de destí
                    // Per exemple, anar a un layout concret o passar paràmetres extres
                    window.location.href = 'index.php?module=stic_Resources&action=listplaces&query=true&clear_query=true';
                });
            
                // Sets the custom where for mass update to discrimate between places and resources. This is needed because mass update does not process the custom where added in listview and updates all the records instead of only places or non-places.
                var massUpdateForm = $('#MassUpdate');
                $(\"#MassUpdate [name='return_action']\").val('{$return_action}');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'custom_where',
                    value: ' AND (stic_resources.type = \"place\")'
                }).appendTo(massUpdateForm);
            });
        </script>";
    }
    
    function listViewProcess() {
        $this->processSearchForm();
            $this->params['custom_where'] = ' AND stic_resources.type = "place" ';
      
        if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
            $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
            echo $this->lv->display();
        }
    }
}
