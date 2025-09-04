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

require_once 'SticInclude/Views.php';

class stic_TransactionsViewLoad_file extends SugarView
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
        echo '
        <form action="index.php?module=stic_Transactions&action=loadNorma43" method="post" enctype="multipart/form-data">
            <input type="file" name="file" size="30"><br><br>
            <input type="submit" value="Importar Norma 43">
        </form>';

//         echo <<<SCRIPT
// <script type='text/javascript' src='cache/include/javascript/sugar_grp_yui_widgets.js'></script>
// <script>
// YAHOO.SUGAR.MessageBox.show({
//     width: 'auto',
//     msg: '<form id="miform" action="index.php?module=stic_Transactions&action=loadNorma43" method="post" enctype="multipart/form-data">'
//         + '<input type="file" name="file" size="10"><br>'
//         + '<center><input type="submit" value="' + SUGAR.language.get("stic_Transactions", "LBL_NORMA_43_LOAD_FILE") + '"></center>'
//         + '</form>',
//     title: SUGAR.language.get('stic_Transactions', 'LBL_NORMA_43_SELECT_FILE')
// });
// </script>
// SCRIPT;

    }
}
