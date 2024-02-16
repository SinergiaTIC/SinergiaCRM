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

class stic_Custom_ViewsController extends SugarController {

    public function process()
    {
        //$this->action_remap = array();
        
        $GLOBALS [ 'log' ]->info(get_class($this).":") ;
        global $current_user;
        $this->hasAccess = ($current_user->isAdmin() || $current_user->isDeveloperForAnyModule());
        
        parent::process();
    }

    public function action_selectModule()
    {
        $this->view = 'selectmodule';
    }

    public function action_selectView()
    {
        $this->view = "selectview";
    }
    public function action_editview()
    {
        $this->view = 'edit';
        $GLOBALS['view'] = $this->view;
        if (isset($_REQUEST['view_module'])) {
            $this->bean->view_module = $_REQUEST['view_module'];
        }
        if (isset($_REQUEST['view_type'])) {
            $this->bean->view_type = $_REQUEST['view_type'];
        }
    }

    public function action_getModuleFieldEditor() {
        require_once("modules/AOW_WorkFlow/aow_utils.php");

        $view_module = $_REQUEST['view_module'];
        $field_name = $_REQUEST['field_name'];
        $editor_name = $_REQUEST['editor_name'];
        $form = $_REQUEST['form'];

        if (isset($_REQUEST['field_value'])) {
            $value = $_REQUEST['field_value'];
        } else {
            $value = '';
        }
        if ($_REQUEST['is_value_set'] === 'false'){
            $params['value_set'] = false;
        } else{
            $params['value_set'] = true;
        }
        $html = getModuleField($view_module, $field_name, $editor_name, 'EditView', $value, '', '', $params);
        $html = str_replace('EditView', $form, $html);
        echo $html;
        die;
    }

}