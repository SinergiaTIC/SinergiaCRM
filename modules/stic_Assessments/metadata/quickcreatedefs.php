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
$module_name = 'stic_Assessments';
$viewdefs [$module_name] = array (
  'QuickCreate' => array (
    'templateMeta' => array (
      'maxColumns' => '2',
      'widths' => array (
        0 => array (
          'label' => '10',
          'field' => '30',
        ),
        1 => array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => array (
        'LBL_DEFAULT_PANEL' => array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),           
      ),
    ),
    'panels' => array (
      'lbl_default_panel' => array (
        0 => array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => array (
          0 => array (
            'name' => 'stic_assessments_contacts_name',
            'label' => 'LBL_STIC_ASSESSMENTS_CONTACTS_FROM_CONTACTS_TITLE',
          ),
          1 => array (
            'name' => 'working_with',
            'studio' => 'visible',
            'label' => 'LBL_WORKING_WITH',
          ),
        ),
        2 => array (
          0 => array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => array (
            'name' => 'derivation',
            'studio' => 'visible',
            'label' => 'LBL_DERIVATION',
          ),
        ),
        3 => array (
          0 => array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => array (
            'name' => 'project',
            'studio' => 'visible',
            'label' => 'LBL_PROJECT',
          ),
        ),  
        4 => array (
          0 => array (
            'name' => 'assessment_date',
            'label' => 'LBL_ASSESSMENT_DATE',
          ),
          1 => array (
            'name' => 'next_date',
            'label' => 'LBL_NEXT_DATE',
          ),
        ),
        5 => array (
          0 => array (
            'name' => 'scope',
            'studio' => 'visible',
            'label' => 'LBL_SCOPE',
          ),
          1 => array (
            'name' => 'moment',
            'studio' => 'visible',
            'label' => 'LBL_MOMENT',
          ),
        ),
        6 => array (
          0 => array (
            'name' => 'areas',
            'studio' => 'visible',
            'label' => 'LBL_AREAS',
          ),
          1 => array (
            'name' => 'recommendations',
            'studio' => 'visible',
            'label' => 'LBL_RECOMMENDATIONS',
          ),
        ),
        7 => array (
          0 => array (
            'name' => 'conclusions',
            'studio' => 'visible',
            'label' => 'LBL_CONCLUSIONS',
          ),
          1 => array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'lbl_editview_panel1' => array (),      
      'lbl_editview_panel2' => array (
        0 => array (
          0 => array (
            'name' => 'training_completed',
            'studio' => 'visible',
            'label' => 'LBL_TRAINING_COMPLETED',
          ),
          1 => array (
            'name' => 'has_criminal_certificate',
            'studio' => 'visible',
            'label' => 'LBL_HAS_CRIMINAL_CERTIFICATE',
          ),
        ),
        1 => array (
          0 => array (
            'name' => 'needs_financial_aid',
            'studio' => 'visible',
            'label' => 'LBL_NEEDS_FINANCIAL_AID',
          ),
          1 => array (
            'name' => 'needs_parental_authorization',
            'studio' => 'visible',
            'label' => 'LBL_NEEDS_PARENTAL_AUTHORIZATION',
          ),
        ),
        2 => array (
          0 => array (
            'name' => 'available_days',
            'studio' => 'visible',
            'label' => 'LBL_AVAILABLE_DAYS',
          ),
          1 => array (
            'name' => 'available_time',
            'label' => 'LBL_AVAILABLE_TIME',
          ),
        ),
      ),
      'lbl_editview_panel3' => array (
        0 => array (
          0 => array (
            'name' => 'resignation_signed',
            'label' => 'LBL_RESIGNATION_SIGNED',
          ),
          1 => array (
            'name' => 'resignation_date',
            'label' => 'LBL_RESIGNATION_DATE',
          ),
        ),
        1 => array (
          0 => array (
            'name' => 'material_returned',
            'studio' => 'visible',
            'label' => 'LBL_MATERIAL_RETURNED',
          ),
          1 => array (
            'name' => 'insurance_canceled',
            'studio' => 'visible',
            'label' => 'LBL_INSURANCE_CANCELED',
          ),
        ),
      ),
    ),
  ),
);
;
?>
