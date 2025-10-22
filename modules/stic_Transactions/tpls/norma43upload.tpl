{* 
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
 *}

<div class="moduleTitle">
    <h2 class="module-title-text" style="font-size: 18px; margin-bottom: 0px;">{$MOD['LBL_STEP_1']}</h2>
</div>
<br>
<div  class="import_instruction">
    <br>
    {$MOD['LBL_INSTRUCTION_1_STEP_1']}
    <br>
    {$MOD['LBL_INSTRUCTION_2_STEP_1']}
    <br>
    {$MOD['LBL_INSTRUCTION_3_STEP_1']}
    <br>
</div>

<div class="listViewBody">
    <form id="Norma43UploadForm" method="post" enctype="multipart/form-data"
        action="index.php?module=stic_Transactions&action=loadNorma43">
        <br>
        <table class="edit view" width="100%">
            <tr>
                <label for="file"><strong>{$MOD['LBL_SELECT_FILE']}</strong></label>
            </tr>
            <tr>
                    <input type="file" name="file" id="file" size="50" onchange="document.getElementById('btn_continue').disabled = !this.value;" />
            </tr>
            {if $ERROR}
            <tr>
                <td></td>
                <td><span style="color:red;font-weight:bold;">{$ERROR}</span></td>
            </tr>
            {/if}
        </table>

        <div style="text-align: left; padding: 15px 0;  margin-top: 20px;">
            <input type="submit" id="btn_continue" class="button primary" value="{$APP['LNK_RESUME']}"  />
            <input type="button" class="button" value="{$APP['LBL_CANCEL']}"
                onclick="location.href='index.php?module=stic_Transactions&action=index';" />
        </div>
    </form>
</div>