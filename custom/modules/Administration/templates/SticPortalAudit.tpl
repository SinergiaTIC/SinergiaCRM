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
