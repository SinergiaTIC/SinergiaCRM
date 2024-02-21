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

// Add js file for some customizations in Popup views
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticPopupCustomizations.js' => 'include/javascript/sugar_grp1.js',
);

// Adding selectize library to JSGroupings.
// JSGroupings are file packages containing one or more minified JavaScript libraries.
// The groupings enhance system performance by reducing the number of downloaded files for a given page.
// The resulting files will be created inside the /cache/ folder.
$js_groupings[] = $newGrouping = array(
    'SticInclude/vendor/selectize/js/selectize.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticSelectize.js' => 'include/javascript/sugar_grp1.js',
);

// Overrides isValidEmail function and fix STIC#301
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticCustomIsValidEmailFunction.js' => 'include/javascript/sugar_grp1.js',
);

// Add autosize library & runit
$js_groupings[] = $newGrouping = array(
    'SticInclude/vendor/autosize/dist/autosize.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticAutosize.js' => 'include/javascript/sugar_grp1.js',
);

// Add qtip library in all pages and custom qtip calls
$js_groupings[] = $newGrouping = array(
    'include/javascript/qtip/jquery.qtip.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticQtip.js' => 'include/javascript/sugar_grp1.js',
    
);
// Add qtip library in all pages and custom qtip calls
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticGetAdditionalDetails.js' => 'include/javascript/sugar_grp1.js',
);
// Add Custom View functionality
$js_groupings[] = $newGrouping = array(
    'modules/stic_Custom_Views/processor/js/sticCustomViews_Utils.js' => 'include/javascript/sugar_grp1.js',

    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivLabelBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewItemBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivInputBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivPanelContentBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivPanelHeaderBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivRowBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivTabContentBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewDivTabHeaderBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewItemFieldBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewItemPanelBase.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/base/sticCustomViewItemTabBase.js' => 'include/javascript/sugar_grp1.js',

    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivInputDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivPanelContentDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivPanelHeaderDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivRowDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivTabContentDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewDivTabHeaderDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewItemFieldDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewItemPanelDetail.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/detailview/sticCustomViewItemTabDetail.js' => 'include/javascript/sugar_grp1.js',

    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivInputEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivPanelContentEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivPanelHeaderEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivRowEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivTabContentEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewDivTabHeaderEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewItemFieldEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewItemPanelEdit.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/editview/sticCustomViewItemTabEdit.js' => 'include/javascript/sugar_grp1.js',

    'modules/stic_Custom_Views/processor/js/sticCustomView.js' => 'include/javascript/sugar_grp1.js',
    'modules/stic_Custom_Views/processor/js/sticCustomizeView.js' => 'include/javascript/sugar_grp1.js',
);
