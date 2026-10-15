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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="actionsContainer">
<tr><td>
    <input type="button" class="button" onclick="document.location.href='index.php?module=Administration&action=sticportalconfig'" value="{$MOD.LBL_STIC_PORTAL_BACK_CONFIG|default:'Back to Portal Configuration'|escape}">
</td></tr>
</table>
<h2>{$MOD.LBL_STIC_PORTAL_LOGIN_AUDIT|default:'Portal Login Audit Log'|escape}</h2>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list view">
<thead>
<tr>
    <th width="15%">{$MOD.LBL_STIC_PORTAL_AUDIT_DATE|default:'Date'|escape}</th>
    <th width="20%">{$MOD.LBL_STIC_PORTAL_AUDIT_USERNAME|default:'Username'|escape}</th>
    <th width="8%">{$MOD.LBL_STIC_PORTAL_AUDIT_TYPE|default:'Type'|escape}</th>
    <th width="12%">{$MOD.LBL_STIC_PORTAL_AUDIT_IP|default:'IP Address'|escape}</th>
    <th width="10%">{$MOD.LBL_STIC_PORTAL_AUDIT_RESULT|default:'Result'|escape}</th>
    <th width="15%">{$MOD.LBL_STIC_PORTAL_AUDIT_REASON_METHOD|default:'Reason / Method'|escape}</th>
    <th width="20%">{$MOD.LBL_STIC_PORTAL_AUDIT_USER_AGENT|default:'User Agent'|escape}</th>
</tr>
</thead>
<tbody>
{foreach from=$AUDIT item=r}
<tr>
    <td>{$r.date_entered|escape}</td>
    <td>{$r.username|escape}</td>
    <td>{$r.type_label|escape}</td>
    <td>{$r.ip_address|escape}</td>
    <td>{$r.result_label|escape}</td>
    <td>{if $r.reason_label}{$r.reason_label|escape} / {/if}{$r.method_label|escape}</td>
    <td style="font-size:10px;word-break:break-all">{$r.user_agent|escape|truncate:80}</td>
</tr>
{foreachelse}
<tr><td colspan="7" align="center">{$MOD.LBL_STIC_PORTAL_AUDIT_NO_RECORDS|default:'No records found'|escape}</td></tr>
{/foreach}
</tbody>
</table>
