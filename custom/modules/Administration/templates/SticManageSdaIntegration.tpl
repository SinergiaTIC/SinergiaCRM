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

	<tbody id="sda-enabled">
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_ENABLED_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_ENABLED_HELP}</div>
		</td>
		<td width="75%" colspan="3" valign="middle">
			<input type="hidden" name="enabled" value="0"><input type="checkbox" name="enabled" value="1" {if $SDA_CONFIG.enabled}checked{/if}>
		</td>
	</tr>
	</tbody>

	<tbody class="sda-toggleable" id="sda-ui-fields">
	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_ACTIONS_SECTION}</h4></th>
	</tr>
	<tr>
		<td colspan="4" class="sda-actions-row">
			<button id="rebuild-link" type="button" class="button">
				<span class="glyphicon glyphicon-flash text-success"></span> {$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}
			</button>
			<span style="display:inline-block"><i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION}</div></span>
			<label style="margin-left:16px"><input type="checkbox" id="debug-check" checked=""> {$MOD.LBL_STIC_RUN_SDA_DEBUG_CHECK_LABEL}</label>
			<button id="sda-link" type="button" class="button" style="margin-left:24px">
				<span class="glyphicon glyphicon-link text-success"></span> {$MOD.LBL_STIC_GO_TO_SDA_LINK_TITLE}
			</button>
			<span style="display:inline-block"><i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_GO_TO_SDA_DESCRIPTION|default:'Abre la instancia de SinergiaDA en una nueva pestaña.'}</div></span>
		</td>
	</tr>

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_GENERAL}</h4></th>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="hidden" name="auto_rebuild_on_studio_events" value="0"><input type="checkbox" name="auto_rebuild_on_studio_events" value="1" {if $SDA_CONFIG.auto_rebuild_on_studio_events|default:true}checked{/if}>
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_URL_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_URL_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="text" name="public_url" value="{$SDA_PUBLIC_URL}" size="30">
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="number" name="max_users_processed" value="{$SDA_CONFIG.max_users_processed}" min="0">
		</td>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_HELP}</div>
		</td>
		<td width="25%" valign="middle">
			<input type="hidden" name="group_permissions_enabled" value="0"><input type="checkbox" name="group_permissions_enabled" value="1" {if $SDA_CONFIG.group_permissions_enabled}checked{/if}>
		</td>
	</tr>
	<tr>
		<td width="25%" scope="row" valign="middle">
			{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_LABEL}
			<i class="inline-help glyphicon glyphicon-info-sign"></i>
			<div class="inline-help-content">{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_HELP}</div>
		</td>
		<td width="75%" colspan="3" valign="middle">
			<select id="publish_as_table" name="publish_as_table[]" multiple placeholder="..." class="form-control">
				{foreach from=$SDA_MODULES item=mod}
					<option value="{$mod.name}" {if is_array($SDA_CONFIG.publish_as_table) && in_array($mod.name, $SDA_CONFIG.publish_as_table)}selected{/if}>{$mod.label}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	<tr>
		<th colspan="4" scope="row"><h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_CACHE}</h4></th>
	</tr>
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
	<tr class="sda-cache-field">
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
	<tr class="sda-cache-field">
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
	</tbody>
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
var SDA_ERROR_MSG = '{$MOD.LBL_STIC_RUN_SDA_ERROR_MSG|escape:'javascript'}';
var SDA_SUCCESS_MSG = '{$MOD.LBL_STIC_RUN_SDA_SUCCESS_MSG|default:'Rebuild completed successfully.'|escape:'javascript'}';
var SDA_BUSY_MSG = '{$MOD.LBL_STIC_RUN_SDA_BUSY_MSG|default:'A rebuild is already running.'|escape:'javascript'}';
var SDA_STALLED_MSG = '{$MOD.LBL_STIC_RUN_SDA_STALLED_MSG|default:'The rebuild was interrupted before finishing.'|escape:'javascript'}';
var SDA_RUNNING_MSG = '{$MOD.LBL_STIC_RUN_SDA_RUNNING_MSG|default:'The rebuild is running in background...'|escape:'javascript'}';
{literal}
(function() {
	var currentDomain = window.location.hostname;
	var lang = (typeof SUGAR !== 'undefined' && SUGAR.language && SUGAR.language.languages && SUGAR.language.languages.app_list_strings && SUGAR.language.languages.app_list_strings.language_pack_name) ? SUGAR.language.languages.app_list_strings.language_pack_name.split(" ").pop().split("_")[0] : "es";
	var sdaUrl = (typeof SUGAR !== 'undefined' && SUGAR.config && SUGAR.config.stic_sinergiada_public && SUGAR.config.stic_sinergiada_public.url) ? SUGAR.config.stic_sinergiada_public.url : ("https://" + currentDomain.replace("sinergiacrm", "sinergiada") + "/" + lang + "/#");
	var sdaLink = document.getElementById("sda-link");
	if (sdaLink) {
		sdaLink.addEventListener("click", function() {
			window.open(sdaUrl, '_blank');
		});
	}

	var debugCheck = document.getElementById("debug-check");
	var rebuildLink = document.getElementById("rebuild-link");
	var pollTimer = null;
	var debugMode = false;

	var STATUS_URL = "index.php?module=Administration&action=createReportingMySQLViews&check_status=1";
	var DEBUG_HTML_URL = "index.php?module=Administration&action=createReportingMySQLViews&fetch_debug=1";
	var LAUNCH_URL = "index.php?module=Administration&action=createReportingMySQLViews&async=1";

	function isDebugEnabled() {
		return !!debugCheck && debugCheck.checked;
	}

	function showResult(html) {
		var el = document.getElementById(debugMode ? "debug-content" : "rebuild-feedback");
		if (el) {
			// jQuery .html() (unlike raw innerHTML) executes the <script> tags the
			// report embeds, which bind the expand/collapse handlers of SinergiaDA.js
			jQuery(el).html(html);
		}
	}

	function alertHtml(cls, glyphicon, text) {
		return '<div class="alert ' + cls + '" style="margin:16px;"><span class="glyphicon ' + glyphicon + '" style="margin-right:8px;"></span>' + text + '</div>';
	}

	function alertBox(cls, glyphicon, text) {
		showResult(alertHtml(cls, glyphicon, text));
	}

	function stopPolling() {
		if (pollTimer) {
			clearInterval(pollTimer);
			pollTimer = null;
		}
	}

	function showFinished(s) {
		if (s.state === "busy") {
			alertBox("alert-warning", "glyphicon-time", SDA_BUSY_MSG);
		} else if (s.state === "finished" && s.result === "ok" && debugMode) {
			jQuery.get(DEBUG_HTML_URL)
				.done(function(html) {
					if (html && html.length) {
						showResult(html);
					} else {
						alertBox("alert-success", "glyphicon-check", SDA_SUCCESS_MSG);
					}
				})
				.fail(function() {
					alertBox("alert-success", "glyphicon-check", SDA_SUCCESS_MSG);
				});
		} else if (s.state === "finished" && s.result === "ok") {
			alertBox("alert-success", "glyphicon-check", SDA_SUCCESS_MSG);
		} else if (s.state === "finished" && debugMode) {
			// Finished with error: still show the debug report below the alert,
			// it contains the failure details (the run completed, the file is fresh)
			var failAlert = alertHtml("alert-danger", "glyphicon-minus-sign", SDA_ERROR_MSG + (s.message ? "<br>" + s.message : ""));
			jQuery.get(DEBUG_HTML_URL)
				.done(function(html) {
					showResult(failAlert + (html || ""));
				})
				.fail(function() {
					showResult(failAlert);
				});
		} else if (s.state === "finished") {
			alertBox("alert-danger", "glyphicon-minus-sign", SDA_ERROR_MSG + (s.message ? "<br>" + s.message : ""));
		} else {
			// stalled / idle: the runner died before finishing
			alertBox("alert-danger", "glyphicon-warning-sign", SDA_STALLED_MSG);
		}
		if (rebuildLink) {
			rebuildLink.disabled = false;
		}
	}

	function handleStatus(s) {
		if (!s || !s.state || s.state === "running") {
			return;
		}
		stopPolling();
		showFinished(s);
	}

	function startPolling() {
		stopPolling();
		pollTimer = setInterval(function() {
			jQuery.get(STATUS_URL)
				.done(function(s) {
					if (typeof s === "string") {
						try { s = JSON.parse(s); } catch (e) { s = null; }
					}
					if (s) {
						if (s.state === "running" && !debugMode) {
							var elapsed = s.started_at ? Math.max(0, Math.round(Date.now() / 1000 - s.started_at)) : 0;
							alertBox("alert-info", "glyphicon-hourglass", SDA_RUNNING_MSG + " (" + elapsed + "s)");
						} else {
							handleStatus(s);
						}
					}
				})
				.fail(function() { /* keep trying on transient errors */ });
		}, 2500);
	}

	if (rebuildLink) {
		rebuildLink.addEventListener("click", function() {
			debugMode = isDebugEnabled();
			try {
				var feedback = document.getElementById("rebuild-feedback");
				if (!feedback) {
					throw new Error("#rebuild-feedback not found");
				}
				if (debugMode) {
					var box = '<div id="debug-output" style="margin-top:16px;border:2px solid #b5bc31;border-radius:8px;overflow:hidden;background:#fff;">'
						+ '<div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#f6f7d9;border-bottom:1px solid #b5bc31;">'
						+ '<strong style="font-size:14px;color:#333;"><span class="glyphicon glyphicon-console" style="margin-right:6px;"></span>' + SDA_DEBUG_TITLE + '</strong>'
						+ '<button type="button" class="btn btn-default btn-xs" id="debug-toggle">' + SDA_DEBUG_HIDE + '</button>'
						+ '</div>'
						+ '<div id="debug-content" style="max-height:500px;overflow:auto;"></div>'
						+ '</div>';
					feedback.innerHTML = box;
					alertBox("alert-info", "glyphicon-hourglass", SDA_DEBUG_LOADING);
				} else {
					feedback.innerHTML = "";
					alertBox("alert-info", "glyphicon-hourglass", SDA_RUNNING_MSG);
				}
				rebuildLink.disabled = true;
				stopPolling();
				jQuery.get(LAUNCH_URL + (debugMode ? "&print_debug=1" : ""))
					.done(function(s) {
						if (typeof s === "string") {
							try { s = JSON.parse(s); } catch (e) { s = null; }
						}
						if (s && s.state === "running") {
							var elapsed = s.started_at ? Math.max(0, Math.round(Date.now() / 1000 - s.started_at)) : 0;
							if (!debugMode) {
								alertBox("alert-info", "glyphicon-hourglass", SDA_RUNNING_MSG + " (" + elapsed + "s)");
							}
							startPolling();
						} else if (s) {
							handleStatus(s);
						} else {
							// Unreadable response: rely on the polling
							startPolling();
						}
					})
					.fail(function(xhr, status) {
						var detail = (xhr && xhr.status) ? xhr.status : status;
						alertBox("alert-danger", "glyphicon-remove-sign", SDA_ERROR_MSG + " (" + detail + ")");
						if (rebuildLink) {
							rebuildLink.disabled = false;
						}
					});
			} catch (err) {
				window.location.href = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1";
			}
		});
	}

	document.addEventListener("click", function(e) {
		if (e.target.id === "debug-toggle") {
			jQuery("#debug-content").slideToggle();
			e.target.textContent = jQuery("#debug-content").is(":visible") ? SDA_DEBUG_HIDE : SDA_DEBUG_SHOW;
		}
	});

	function toggleDependents(checkbox) {
		if (checkbox.name === 'cache_enabled') {
			jQuery('.sda-cache-field').toggle(checkbox.checked);
		}
		if (checkbox.name === 'enabled') {
			jQuery('#sda-ui-fields').toggle(checkbox.checked);
			if (checkbox.checked) {
				var $ce = jQuery('#sda-ui-fields').find('input[type="checkbox"][name="cache_enabled"]');
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
