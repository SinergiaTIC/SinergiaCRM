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
	<div class="row">
		<div class="col-md-4 text-center">
			<a href="index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1"><button
					type='button' class='button' id='rebuild'><span
						class='glyphicon glyphicon-flash text-success'></span>
					{$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}</button></a>
			{if $CURRENT_USER_ID eq '2'}
				<label class="checkbox-inline" style="margin-left: 10px;">
					<input type="checkbox" id="debug-check" checked="checked"> {$MOD.LBL_STIC_RUN_SDA_DEBUG_CHECK_LABEL}
				</label>
			{/if}
			<p>{$MOD.LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION}

		</div>
		<div class="col-md-4 text-center">
			<a id="sda-link" target="_blank"><button type='button' class='button' id='link'><span
						class='glyphicon glyphicon-link'></span>
					{$MOD.LBL_STIC_GO_TO_SDA_LINK_TITLE}</button></a>
			<p id="sda-url"></p>
			<p id="link-feedback">
		</div>
	</div>
	<div class="col-md-12" id='rebuild-feedback'></div>
</div>
</div>

{literal}
	<script type="text/javascript">
		const currentDomain = window.location.hostname;
		var lang = SUGAR.language.languages.app_list_strings.language_pack_name.split(" ").pop().split("_")[0];
		const sdaUrl = SUGAR?.config?.stic_sinergiada_public?.url || "https://" + currentDomain.replace("sinergiacrm", "sinergiada") + "/" + lang + "/#";
		$("#sda-link").attr('href', sdaUrl);
		$("#sda-url").text(sdaUrl);

		if ($("#debug-check").length) {
			function toggleDebug() {
				var link = $("a[href*='createReportingMySQLViews']").first();
				if ($("#debug-check").is(':checked')) {
					link.attr('href', 'index.php?module=Administration&action=createReportingMySQLViews&debug=1&print_debug=1');
				} else {
					link.attr('href', 'index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1');
				}
			}
			$("#debug-check").change(toggleDebug);
			toggleDebug();

			$("a[href*='createReportingMySQLViews']").first().click(function(e) {
				if ($("#debug-check").is(':checked')) {
					e.preventDefault();
					var href = $(this).attr('href');
					var box = '<div id="debug-output" style="border:2px solid #ffc107;border-radius:4px;margin-top:15px;margin-bottom:15px;background:#fff;">'
						+ '<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;background:#fff8e1;border-bottom:1px solid #ffc107;">'
						+ '<strong style="font-size:14px;">Resultado de depuraci&oacute;n</strong>'
						+ '<button type="button" class="button" id="debug-toggle">Ocultar</button>'
						+ '</div>'
						+ '<div id="debug-content" style="max-height:600px;overflow:auto;"></div>'
						+ '</div>';
					$('#rebuild-feedback').html(box);
					$('#debug-content').html('<div class="alert alert-info" style="margin:12px;"><strong>Reconstruyendo con depuraci&oacute;n...</strong></div>');
					$.get(href, function(data) {
						$('#debug-content').html(data);
					});
				}
			});
			$(document).on('click', '#debug-toggle', function() {
				$('#debug-content').slideToggle();
				$(this).text($('#debug-content').is(':visible') ? 'Ocultar' : 'Mostrar');
			});
		}
	</script>
{/literal}