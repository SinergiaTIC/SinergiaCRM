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
    <input type="button" class="button" onclick="document.location.href='index.php?module=Administration&action=sticportalconfig'" value="Back to Portal Configuration">
</td></tr>
</table>
<h2>Portal Login Audit Log</h2>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list view">
<thead>
<tr>
    <th width="15%">Date</th>
    <th width="20%">Username</th>
    <th width="8%">Type</th>
    <th width="12%">IP Address</th>
    <th width="10%">Result</th>
    <th width="15%">Reason / Method</th>
    <th width="20%">User Agent</th>
</tr>
</thead>
<tbody>
{foreach from=$AUDIT item=r}
<tr>
    <td>{$r.date_entered|escape}</td>
    <td>{$r.username|escape}</td>
    <td>{$r.parent_type|escape}</td>
    <td>{$r.ip_address|escape}</td>
    <td>{if $r.success}Success{else}Failure{/if}</td>
    <td>{$r.failure_reason|escape} / {$r.auth_method|escape}</td>
    <td style="font-size:10px;word-break:break-all">{$r.user_agent|escape|truncate:80}</td>
</tr>
{foreachelse}
<tr><td colspan="7" align="center">No records found</td></tr>
{/foreach}
</tbody>
</table>
