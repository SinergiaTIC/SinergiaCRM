<div class="moduleTitle">
	<h2 class="module-title-text">{$MOD.LBL_STIC_MANAGE_SDA_ACTIONS_LINK_TITLE}</h2>
	<div class="clear"></div>
	<div class="row">
		<div class="col-md-4 text-center">
			<a href="index.php?module=Administration&action=createReportingMySQLViews&debug=1&update_model=1"><button
					type='button' class='button' id='rebuild'><span
						class='glyphicon glyphicon-flash text-success'></span>
					{$MOD.LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE}</button></a>
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
	</script>
{/literal}