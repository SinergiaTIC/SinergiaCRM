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
{literal}
<style>
.portal-msg{margin:10px 0;padding:10px;border-radius:4px;font-size:13px}
.portal-msg-success{background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9}
.portal-msg-warning{background:#fff3e0;color:#e65100;border:1px solid #ffe0b2}
.inline-help{margin-left:4px;color:#5b83ad;cursor:pointer;font-size:13px}
.inline-help-content{display:none}
.sda-actions-row{text-align:center;padding:12px 0}
.sda-actions-row .button{margin:0 8px}
.sda-save-row{text-align:center;padding:16px 0 8px}
</style>
{/literal}
<div class="moduleTitle">
	<h2 class="module-title-text">{$MOD.LBL_STIC_SINERGIADA_LINK_TITLE}</h2>
	<div class="clear"></div>
</div>

<link rel="stylesheet" type="text/css" href="SticInclude/vendor/selectize/css/selectize.bootstrap3.css" />
<script src="SticInclude/vendor/selectize/js/selectize.min.js"></script>

<form name="SdaConfigForm" method="POST" action="index.php?module=Administration&action=sticSaveSdaConfig">
<input type="hidden" name="doaction" value="save">

<table class="edit view" style="margin-top:10px;">

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_ACTIONS_SECTION}</h4></th>
	</tr>
	<tr>
		<td colspan="4" class="sda-actions-row">
			<button id="rebuild-link" type="button" class="button">
				<span class="glyphicon glyphicon-flash text-success"></span> {$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}
			</button>
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION}</div>
			{if $CURRENT_USER_ID eq '2'}
				<label style="margin-left:16px"><input type="checkbox" id="debug-check" checked=""> {$MOD.LBL_STIC_RUN_SDA_DEBUG_CHECK_LABEL}</label>
			{/if}
			<button id="sda-link" type="button" class="button" style="margin-left:24px">
				<span class="glyphicon glyphicon-link text-success"></span> {$MOD.LBL_STIC_GO_TO_SDA_LINK_TITLE}
			</button>
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_GO_TO_SDA_DESCRIPTION|default:'Abre la instancia de SinergiaDA en una nueva pestaña.'}</div>
		</td>
	</tr>

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_GENERAL}</h4></th>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_ENABLED_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_ENABLED_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="hidden" name="enabled" value="0"><input type="checkbox" name="enabled" value="1" {if $SDA_CONFIG.enabled}checked{/if}>
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="hidden" name="auto_rebuild_on_studio_events" value="0"><input type="checkbox" name="auto_rebuild_on_studio_events" value="1" {if $SDA_CONFIG.auto_rebuild_on_studio_events|default:true}checked{/if}>
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_URL_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_URL_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="text" name="public_url" value="{$SDA_PUBLIC_URL}" size="30">
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="number" name="max_users_processed" value="{$SDA_CONFIG.max_users_processed}" min="0">
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_HELP}</div>
		</td>
		<td width="25%" colspan="3" valign="middle">
			<select id="publish_as_table" name="publish_as_table[]" multiple placeholder="..." class="form-control">
				{foreach from=$SDA_MODULES item=mod}
					<option value="{$mod.name}" {if is_array($SDA_CONFIG.publish_as_table) && in_array($mod.name, $SDA_CONFIG.publish_as_table)}selected{/if}>{$mod.label}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_HELP}</div>
		</td>
		<td width="25%" colspan="3" valign="middle">
			<input type="hidden" name="group_permissions_enabled" value="0"><input type="checkbox" name="group_permissions_enabled" value="1" {if $SDA_CONFIG.group_permissions_enabled}checked{/if}>
		</td>
	</tr>

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_CACHE}</h4></th>
	</tr>
	<tbody class="sda-toggleable">
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CACHE_ENABLED_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CACHE_ENABLED_HELP}</div>
		</td>
		<td width="25%" colspan="3" valign="middle">
			<input type="hidden" name="cache_enabled" value="0"><input type="checkbox" name="cache_enabled" value="1" {if $SDA_CONFIG.config.cache_enabled}checked{/if}>
		</td>
	</tr>
	</tbody>
	<tbody class="sda-toggleable" id="sda-cache-fields">
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<select name="cache_units">
				<option value="days" {if $SDA_CONFIG.config.cache_units eq 'days'}selected{/if}>{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_DAYS}</option>
				<option value="hours" {if $SDA_CONFIG.config.cache_units eq 'hours'}selected{/if}>{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_HOURS}</option>
			</select>
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CACHE_QUANTITY_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CACHE_QUANTITY_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="number" name="cache_quantity" id="cache_quantity" value="{$SDA_CONFIG.config.cache_quantity}" min="1" max="30">
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CACHE_HOURS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CACHE_HOURS_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="number" name="cache_hours" value="{$SDA_CONFIG.config.cache_hours}" min="0" max="23" size="4">
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CACHE_MINUTES_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CACHE_MINUTES_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="number" name="cache_minutes" value="{$SDA_CONFIG.config.cache_minutes}" min="0" max="59" size="4">
		</td>
	</tr>
	</tbody>

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_EXTRA}</h4></th>
	</tr>
	{foreach from=$SDA_EXTRA_CONFIG key=ekey item=eval}
	<tr>
		<td width="25%" scope="row" valign="middle">{$ekey}</td>
		<td width="75%" colspan="3" valign="middle">
			<input type="text" name="extra_config[{$ekey}]" value="{$eval}" size="50">
		</td>
	</tr>
	{/foreach}
	<tr id="sda-add-extra-row">
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_CONFIG_EXTRA_ADD}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CONFIG_EXTRA_HELP|default:'Configuraciones adicionales en formato clave-valor. Use con precaución.'}</div>
		</td>
		<td width="75%" colspan="3" valign="middle">
			<input type="text" id="sda-new-extra-key" placeholder="{$MOD.LBL_STIC_SINERGIADA_CONFIG_EXTRA_KEY}" style="display:none;">
			<input type="text" id="sda-new-extra-value" placeholder="{$MOD.LBL_STIC_SINERGIADA_CONFIG_EXTRA_VALUE}" style="display:none;">
			<button type="button" id="sda-add-extra-btn" class="button sda-btn-add">+</button>
		</td>
	</tr>
</table>

<div id="rebuild-feedback"></div>

<div class="sda-save-row">
	<button type="button" class="button primary" onclick="this.form.submit()">
		<span class="glyphicon glyphicon-floppy-disk text-success"></span> {$MOD.LBL_STIC_SINERGIADA_CONFIG_SAVE}
		<i class="inline-help glyphicon glyphicon-info-sign"></i>
		<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_CONFIG_SAVE_HELP}</div>
	</button>
</div>

</form>

<script type="text/javascript">
var SDA_DEBUG_TITLE = '{$MOD.LBL_STIC_DA_DEBUG_TITLE|escape:'javascript'}';
var SDA_DEBUG_HIDE = '{$MOD.LBL_STIC_DA_DEBUG_HIDE|escape:'javascript'}';
var SDA_DEBUG_SHOW = '{$MOD.LBL_STIC_DA_DEBUG_SHOW|escape:'javascript'}';
var SDA_DEBUG_LOADING = '{$MOD.LBL_STIC_DA_DEBUG_LOADING|escape:'javascript'}';
{literal}
(function() {
	var currentDomain = window.location.hostname;
	var lang = (SUGAR.language.languages && SUGAR.language.languages.app_list_strings && SUGAR.language.languages.app_list_strings.language_pack_name) ? SUGAR.language.languages.app_list_strings.language_pack_name.split(" ").pop().split("_")[0] : "es";
	var sdaUrl = (SUGAR && SUGAR.config && SUGAR.config.stic_sinergiada_public && SUGAR.config.stic_sinergiada_public.url) ? SUGAR.config.stic_sinergiada_public.url : ("https://" + currentDomain.replace("sinergiacrm", "sinergiada") + "/" + lang + "/#");
	document.getElementById("sda-link").addEventListener("click", function() {
		window.open(sdaUrl, '_blank');
	});

	var debugCheck = document.getElementById("debug-check");
	if (debugCheck) {
		var rebuildUrl = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&print_debug=1";
		function toggleDebug() {
			if (debugCheck.checked) {
				rebuildUrl = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&print_debug=1";
			} else {
				rebuildUrl = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1";
			}
		}
		debugCheck.addEventListener("change", toggleDebug);
		toggleDebug();

		document.getElementById("rebuild-link").addEventListener("click", function(e) {
			if (debugCheck.checked) {
				var box = '<div id="debug-output" style="margin-top:16px;border:2px solid #b5bc31;border-radius:8px;overflow:hidden;background:#fff;">'
					+ '<div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f6f7d9;border-bottom:1px solid #b5bc31;">'
					+ '<strong style="font-size:14px;color:#333;"><span class="glyphicon glyphicon-console" style="margin-right:6px;"></span>' + SDA_DEBUG_TITLE + '</strong>'
					+ '<button type="button" class="btn btn-default btn-xs" id="debug-toggle">' + SDA_DEBUG_HIDE + '</button>'
					+ '</div>'
					+ '<div id="debug-content" style="max-height:500px;overflow:auto;"></div>'
					+ '</div>';
				document.getElementById("rebuild-feedback").innerHTML = box;
				document.getElementById("debug-content").innerHTML = '<div class="alert alert-info" style="margin:16px;"><span class="glyphicon glyphicon-hourglass" style="margin-right:8px;"></span>' + SDA_DEBUG_LOADING + '</div>';
				jQuery.get(rebuildUrl, function(data) {
					jQuery("#debug-content").html(data);
				});
			} else {
				window.location.href = rebuildUrl;
			}
		});

		document.addEventListener("click", function(e) {
			if (e.target.id === "debug-toggle") {
				jQuery("#debug-content").slideToggle();
				e.target.textContent = jQuery("#debug-content").is(":visible") ? SDA_DEBUG_HIDE : SDA_DEBUG_SHOW;
			}
		});
	}

	function toggleDependents(checkbox) {
		if (checkbox.name === 'cache_enabled') {
			jQuery('#sda-cache-fields').toggle(checkbox.checked);
		}
		if (checkbox.name === 'enabled') {
			jQuery('#sda-ui-fields').toggle(checkbox.checked);
			if (checkbox.checked) {
				var $ce = jQuery('#sda-cache-table').find('input[type="checkbox"][name="cache_enabled"]');
				if ($ce.length) toggleDependents($ce[0]);
			}
		}
	}

	jQuery(document).ready(function() {
		setInlineHelpQtip();

		jQuery("#publish_as_table").selectize({
			plugins: ["remove_button"],
			persist: false,
			create: false
		});
		jQuery('input[type="checkbox"][name="enabled"], input[type="checkbox"][name="cache_enabled"]').each(function() {
			jQuery(this).on('change', function() { toggleDependents(this); });
			toggleDependents(this);
		});

		jQuery('select[name="cache_units"]').on('change', function() {
			var $qty = jQuery('#cache_quantity');
			if (this.value === 'hours') {
				$qty.attr('max', 24);
			} else {
				$qty.attr('max', 365);
			}
			if (parseInt($qty.val()) > parseInt($qty.attr('max'))) {
				$qty.val($qty.attr('max'));
			}
		});

		var $addBtn = jQuery("#sda-add-extra-btn");
		var $addText = jQuery("#sda-add-extra-text");
		var $extraKey = jQuery("#sda-new-extra-key");
		var $extraVal = jQuery("#sda-new-extra-value");

		$addBtn.on("click", function() {
			if ($addBtn.text() === "+") {
				$addText.hide();
				$extraKey.show().focus();
				$extraVal.show();
				$addBtn.text("\u2713");
			} else {
				var key = $extraKey.val().trim();
				var val = $extraVal.val().trim();
				if (!key) return;
				var safeKey = key.replace(/[^a-zA-Z0-9_]/g, "_");
				var row = '<tr>'
					+ '<td width="25%" scope="row">' + $('<div>').text(safeKey).html() + '</td>'
					+ '<td width="75%" colspan="3">'
					+ '<input type="text" name="extra_config[' + safeKey + ']" value="' + $('<div>').text(val).html() + '" size="50">'
					+ '</td></tr>';
				jQuery(row).insertBefore("#sda-add-extra-row");
				$extraKey.val("");
				$extraVal.val("");
				$extraKey.hide();
				$extraVal.hide();
				$addText.show();
				$addBtn.text("+");
			}
		});
	});
})();
</script>
{/literal}
