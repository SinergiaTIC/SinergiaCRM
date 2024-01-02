{*
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
 *}
<input type='hidden' name='whereconditions' id='whereconditions' value='{$fields.whereconditions.value}'>
<input type='hidden' name='wheregroups' id='wheregroups' value='{$fields.wheregroups.value}'>
<input type='hidden' name='listfields' id='listfields' value='{$fields.listfields.value}'>
<input type='hidden' name='unionlistfields' id='unionlistfields' value='{$fields.unionlistfields.value}'>
<input type='hidden' name='listtype' id='listtype' value='{$fields.listtype.value}'>
<input type='hidden' name='listtypeproperties' id='listtypeproperties' value='{$fields.listtypeproperties.value}'>
<input type='hidden' name='jsonlanguage' id='jsonlanguage' value='{$jsonlanguage}'>
<input type='hidden' name='selectionlimit' id='selectionlimit' value='{$fields.selectionlimit.value}'>
<input type='hidden' name='presentation_params' id='presentation_params' value='{$fields.presentation_params.value}'>
<input type='hidden' name='visualization_params' id='visualization_params' value='{$fields.visualization_params.value}'>
<input type='hidden' name='integration_params' id='integration_params' value='{$fields.integration_params.value}'>
<input type='hidden' name='report_module' id='report_module' value='{$fields.report_module.value}'>
<input type='hidden' name='reportoptions' id='reportoptions' value='{$fields.reportoptions.value}'>
<input type='hidden' name='union_modules' id='union_modules' value='{$fields.union_modules.value}'>
<input type='hidden' name='name' id='name' value='{$fields.name.value}'>
<input type='hidden' name='description' id='description' value='{$fields.description.value}'>
<input type='hidden' name='report_status' id='report_status' value='{$fields.report_status.value}'>
<input type='hidden' name='assigned_user_name' id='assigned_user_name' value='{$fields.assigned_user_name.value}'>
<input type='hidden' name='assigned_user_id' id='assigned_user_id' value='{$fields.assigned_user_id.value}'>
<input type='hidden' name='team_name' id='team_name' value='{$team_name}'>
<input type='hidden' name='team_id' id='team_id' value='{$fields.team_id.value}'>
<input type='hidden' name='authaccess_id' id='authaccess_id' value='{$authaccess_id}'>
<input type='hidden' name='authaccess_name' id='authaccess_name' value='{$authaccess_name}'>
<input type='hidden' name='korgobject_name' id='korgobject_name' value='{$fields.korgobject_name.value}'>
<input type='hidden' name='korgobject_id' id='korgobject_id' value='{$fields.korgobject_id.value}'>
{* STIC-Custom 20221027 MHP - Set is_admin hidden input *}
{* STIC#897 *}
<input type='hidden' name='is_admin' id='is_admin' value='{$is_admin}'>
{* END STIC-Custom*}

<link rel="stylesheet" type="text/css" href="custom/k/extjs4/resources/css/ext-all-gray.css" />
<link rel="stylesheet" type="text/css" href="custom/k/css/ext_override.css" />
<script type="text/javascript" src="custom/k/extjs4/ext-all{if $kreportDebug}-debug{/if}.js"></script>

{* any further variables we need *}
<script type='text/javascript'>{$jsVariables}</script>

<div id='toolbarArea' style='margin-bottom: 5px;'></div>
<div id="layoutregion"></div>

{$editViewAddJs}

<script type='text/javascript' src='modules/KReports/js/kreportsbase1{if $kreportDebug}_debug{/if}.js'></script>
<script type="text/javascript" src="modules/KReports/js/kreportsbase2{if $kreportDebug}_debug{/if}.js"></script>
<script type="text/javascript" src="modules/KReports/js/kreportsbase3{if $kreportDebug}_debug{/if}.js"></script>

{$pluginJS}{$pluginData}

<script type="text/javascript" src="modules/KReports/js/kreportsbase5{if $kreportDebug}_debug{/if}.js"></script>



