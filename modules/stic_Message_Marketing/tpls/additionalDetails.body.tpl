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
{if !empty($FIELD.NAME)}
    <div>
        <a href="index.php?action=DetailView&module={$MODULE_NAME}&record={$FIELD.ID}">{$FIELD.NAME}</a>
    </div>
{/if}
<br>
{if !empty($FIELD.START_DATE_TIME)}
    <div>
        <strong>{$PARAM.LBL_START_DATE_TIME}:</strong>
        {$FIELD.START_DATE_TIME}
    </div>
{if !empty($FIELD.STATUS)}
    <div>
        <strong>{$PARAM.LBL_STATUS}:</strong>
        {$FIELD.STATUS}
    </div>
{/if}
{/if}
{if !empty($FIELD.TEMPLATE)}
    <div>
        <strong>{$PARAM.LBL_TEMPLATE}:</strong>
        {$FIELD.TEMPLATE}
    </div>
{/if}
{if !empty($FIELD.SELECT_ALL)}
    <div>
        <strong>{$PARAM.LBL_SELECT_ALL}:</strong>
        {$FIELD.SELECT_ALL}
    </div>
{/if}
{if !empty($FIELD.PROSPECT_LISTS)}
    <div>
        <strong>{$PARAM.LBL_PROSPECT_LISTS}:</strong>
        {$FIELD.PROSPECT_LISTS}
    </div>
{/if}
{if !empty($FIELD.DATE_ENTERED)}
    <div>
        <strong>{$PARAM.LBL_DATE_ENTERED}:</strong>
        {$FIELD.DATE_ENTERED}
    </div>
{/if}
{if !empty($FIELD.DATE_MODIFIED)}
    <div>
        <strong>{$PARAM.LBL_DATE_MODIFIED}:</strong>
        {$FIELD.DATE_MODIFIED}
    </div>
{/if}
