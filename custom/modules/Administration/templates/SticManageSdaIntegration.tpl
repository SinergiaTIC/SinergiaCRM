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
	<h2 class="module-title-text">{$MOD.LBL_STIC_SINERGIADA_LINK_TITLE}</h2>
	<div class="clear"></div>
</div>

<link rel="stylesheet" type="text/css" href="SticInclude/vendor/selectize/css/selectize.bootstrap3.css" />
<link rel="stylesheet" type="text/css" href="SticInclude/SinergiaDA.css" />
<script src="SticInclude/vendor/selectize/js/selectize.min.js"></script>

{$SDA_THEME_STYLE}

<div class="sda-page-wrapper"><div class="row sda-actions-row">
		<div class="col-md-6">
			<div class="sda-action-card sda-action-card-left">
				<h3><span class="glyphicon glyphicon-flash"></span> {$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}</h3>
				<div style="margin-bottom: 12px;">
					<a id="rebuild-link" href="index.php?module=Administration&action=createReportingMySQLViews&debug=1&print_debug=1" class="sda-rebuild-link">
						<span class="glyphicon glyphicon-flash"></span> {$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}
					</a>
					{if $CURRENT_USER_ID eq '2'}
						<label class="sda-debug-toggle">
							<input type="checkbox" id="debug-check" checked="checked"> {$MOD.LBL_STIC_RUN_SDA_DEBUG_CHECK_LABEL}
						</label>
					{/if}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="sda-action-card sda-action-card-right">
				<h3><span class="glyphicon glyphicon-link"></span> {$MOD.LBL_STIC_GO_TO_SDA_LINK_TITLE}</h3>
				<div style="margin-bottom: 8px;">
					<a id="sda-link" target="_blank" class="sda-external-link">
						<span class="glyphicon glyphicon-link"></span> {$MOD.LBL_STIC_GO_TO_SDA_LINK_TITLE}
					</a>
				</div>
				<div id="sda-url" class="sda-url-display"></div>
			</div>
		</div>
	</div>

	<div class="row sda-feedback-row">
		<div class="col-md-12">
			<div id="rebuild-feedback"></div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="sda-config-card">
				<div class="sda-config-header">
					<h3>{$MOD.LBL_STIC_SINERGIADA_CONFIG_SECTION}</h3>
				</div>
				<form name="SdaConfigForm" method="POST" action="index.php?module=Administration&action=sticSaveSdaConfig">
					<input type="hidden" name="doaction" value="save">

					<div class="sda-config-body">

						<div class="sda-sub-card">
							<div class="sda-sub-card-header">
								<span class="glyphicon glyphicon-cog"></span>
								<h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_GENERAL}</h4>
							</div>
							<div class="sda-sub-card-body">
								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_ENABLED_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_ENABLED_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="hidden" name="enabled" value="0"><input type="checkbox" name="enabled" value="1" {if $SDA_CONFIG.enabled}checked{/if}>
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_AUTO_REBUILD_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="hidden" name="auto_rebuild_on_studio_events" value="0"><input type="checkbox" name="auto_rebuild_on_studio_events" value="1" {if $SDA_CONFIG.auto_rebuild_on_studio_events|default:true}checked{/if}>
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_URL_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_URL_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="text" name="public_url" value="{$SDA_PUBLIC_URL}" class="form-control">
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_SEED_STRING_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_SEED_STRING_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="text" name="seed_string" value="{$SDA_CONFIG.seed_string}" class="form-control">
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_MAX_USERS_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="number" name="max_users_processed" value="{$SDA_CONFIG.max_users_processed}" class="form-control" min="0">
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_PUBLISH_AS_TABLE_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<select id="publish_as_table" name="publish_as_table[]" multiple placeholder="..." class="form-control">
											{foreach from=$SDA_MODULES item=mod}
												<option value="{$mod.name}" {if is_array($SDA_CONFIG.publish_as_table) && in_array($mod.name, $SDA_CONFIG.publish_as_table)}selected{/if}>{$mod.label}</option>
											{/foreach}
										</select>
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_GROUP_PERMISSIONS_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="hidden" name="group_permissions_enabled" value="0"><input type="checkbox" name="group_permissions_enabled" value="1" {if $SDA_CONFIG.group_permissions_enabled}checked{/if}>
									</div>
								</div>
							</div>
						</div>

						<div class="sda-sub-card">
							<div class="sda-sub-card-header">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<h4>{$MOD.LBL_STIC_SINERGIADA_CONFIG_CACHE}</h4>
							</div>
							<div class="sda-sub-card-body">
								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_CACHE_ENABLED_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_CACHE_ENABLED_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="hidden" name="cache_enabled" value="0"><input type="checkbox" name="cache_enabled" value="1" {if $SDA_CONFIG.config.cache_enabled}checked{/if}>
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_CACHE_UNITS_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<select name="cache_units" class="form-control">
											<option value="days" {if $SDA_CONFIG.config.cache_units eq 'days'}selected{/if}>{'days'|capitalize}</option>
											<option value="hours" {if $SDA_CONFIG.config.cache_units eq 'hours'}selected{/if}>{'hours'|capitalize}</option>
										</select>
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_CACHE_QUANTITY_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_CACHE_QUANTITY_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="number" name="cache_quantity" value="{$SDA_CONFIG.config.cache_quantity}" class="form-control" min="0">
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_CACHE_HOURS_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_CACHE_HOURS_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="text" name="cache_hours" value="{$SDA_CONFIG.config.cache_hours}" class="form-control" maxlength="2">
									</div>
								</div>

								<div class="sda-config-row">
									<div class="sda-row-label">
										<span>{$MOD.LBL_STIC_SINERGIADA_CACHE_MINUTES_LABEL}</span>
										<span class="glyphicon glyphicon-info-sign" title="{$MOD.LBL_STIC_SINERGIADA_CACHE_MINUTES_HELP}"></span>
									</div>
									<div class="sda-row-value">
										<input type="text" name="cache_minutes" value="{$SDA_CONFIG.config.cache_minutes}" class="form-control" maxlength="2">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="sda-config-footer">
				<button type="submit" class="sda-btn-save" form="SdaConfigForm">
					<span class="glyphicon glyphicon-floppy-disk"></span> {$MOD.LBL_STIC_SINERGIADA_CONFIG_SAVE}
				</button>
				<span class="sda-footer-note">{$MOD.LBL_STIC_SINERGIADA_CONFIG_SAVE_HELP}</span>
			</div>
		</div>
	</div>
</div>

{literal}
<script type="text/javascript">
(function() {
	var currentDomain = window.location.hostname;
	var lang = SUGAR.language.languages.app_list_strings.language_pack_name.split(" ").pop().split("_")[0];
	var sdaUrl = SUGAR?.config?.stic_sinergiada_public?.url || ("https://" + currentDomain.replace("sinergiacrm", "sinergiada") + "/" + lang + "/#");
	document.getElementById("sda-link").href = sdaUrl;
	document.getElementById("sda-url").textContent = sdaUrl;

	var debugCheck = document.getElementById("debug-check");
	if (debugCheck) {
		function toggleDebug() {
			var link = document.getElementById("rebuild-link");
			if (debugCheck.checked) {
				link.href = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&print_debug=1";
			} else {
				link.href = "index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1";
			}
		}
		debugCheck.addEventListener("change", toggleDebug);
		toggleDebug();

		document.getElementById("rebuild-link").addEventListener("click", function(e) {
			if (debugCheck.checked) {
				e.preventDefault();
				var href = this.href;
				var box = '<div id="debug-output" style="margin-top:16px;border:2px solid var(--sda-primary,#b5bc31);border-radius:8px;overflow:hidden;background:#fff;">'
					+ '<div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--sda-primary-light,#f6f7d9);border-bottom:1px solid var(--sda-primary,#b5bc31);">'
					+ '<strong style="font-size:14px;color:#333;"><span class="glyphicon glyphicon-console" style="margin-right:6px;"></span>Resultado de depuraci&oacute;n</strong>'
					+ '<button type="button" class="btn btn-default btn-xs" id="debug-toggle">Ocultar</button>'
					+ '</div>'
					+ '<div id="debug-content" style="max-height:500px;overflow:auto;"></div>'
					+ '</div>';
				document.getElementById("rebuild-feedback").innerHTML = box;
				document.getElementById("debug-content").innerHTML = '<div class="alert alert-info" style="margin:16px;"><span class="glyphicon glyphicon-hourglass" style="margin-right:8px;"></span>Reconstruyendo con depuraci&oacute;n...</div>';
				jQuery.get(href, function(data) {
					jQuery("#debug-content").html(data);
				});
			}
		});

		document.addEventListener("click", function(e) {
			if (e.target.id === "debug-toggle") {
				jQuery("#debug-content").slideToggle();
				e.target.textContent = jQuery("#debug-content").is(":visible") ? "Ocultar" : "Mostrar";
			}
		});
	}

	jQuery(document).ready(function() {
		jQuery("#publish_as_table").selectize({
			plugins: ["remove_button"],
			persist: false,
			create: false
		});
	});
})();
</script>
{/literal}