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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'stic_Transactions';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name,),
    ),

    'where' => '',

    'list_fields' => array(
        // STIC-Custom 20240715 MHP - https://github.com/SinergiaTIC/SinergiaCRM/pull/62
        // Add the file name and options to download and view the file in a new tab in the subpanel of a new module of type File    
        // 'object_image' => array(
        //     'widget_class' => 'SubPanelIcon',
        //     'width' => '2%',
        //     'image2' => 'attachment',
        //     'image2_url_field' => array(
        //         'id_field' => 'selected_revision_id',
        //         'filename_field' => 'selected_revision_filename'
        //     ),
        //     'attachment_image_only' => true,

        // ),
        // 'document_name' => array(
        //     'name' => 'document_name',
        //     'vname' => 'LBL_LIST_DOCUMENT_NAME',
        //     'widget_class' => 'SubPanelDetailViewLink',
        //     'width' => '45%',
        // ),
        // 'active_date' => array(
        //     'name' => 'active_date',
        //     'vname' => 'LBL_DOC_ACTIVE_DATE',
        //     'width' => '45%',
        // ),        
        'document_name' => array(
            'name' => 'document_name',
            'vname' => 'LBL_NAME',
            'width' => '45%',
            'widget_class' => 'SubPanelDetailViewLink',
        ),
        'object_image' => array(
            'vname' => 'LBL_OBJECT_IMAGE',            
            'widget_class' => 'SubPanelIcon',
            'width' => '2%',
            'image2' => 'attachment',
            'image2_url_field' => array(
                'id_field' => 'id',
                'filename_field' => 'filename'
            ),
            'attachment_image_only' => true,
        ),
        'filename' => array(
            'name' => 'filename',
            'vname' => 'LBL_FILE_UPLOAD',
            'widget_class' => 'SubPanelFileDownloadViewLink',
            'width' => '20%',
            'sortable'=> false,
        ),
        'transaction_date' => array(
            'name' => 'transaction_date',
            'vname' => 'LBL_TRANSACTION_DATE',
            'width' => '10%',
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
        ),
        'transaction_type' => array(
            'name' => 'transaction_type',
            'vname' => 'LBL_TRANSACTION_TYPE',
            'width' => '10%',
        ),
        'category' => array(
            'name' => 'category',
            'vname' => 'LBL_CATEGORY',
            'width' => '10%',
        ),
        'subcategory' => array(
            'name' => 'subcategory',
            'vname' => 'LBL_SUBCATEGORY',
            'width' => '10%',
        ),
        'amount' => array(
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'width' => '10%',
        ),
        'payment_method' => array(
            'name' => 'payment_method',
            'vname' => 'LBL_PAYMENT_METHOD',
            'width' => '10%',
        ),
        // END STIC-Custom
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        // STIC-Custom 20240214 JBL - QuickEdit view
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/93
        'quickedit_button' => array(
            'vname' => 'LBL_QUICKEDIT_BUTTON',
            'widget_class' => 'SubPanelQuickEditButton',
            'module' => $module_name,
            'width' => '4%',
        ),
        // END STIC-Custom
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
    ),
);
