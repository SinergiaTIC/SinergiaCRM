<div id='grid_Div'>
	<table width="100%">
        <tr>
            <td><h2>{$MOD.LBL_RESULTS_TITLE}</h2></td>
        </tr>
    </table>
    <form id="results" name="results" action="index.php" enctype="multipart/form-data" method="post">
	    <input type="hidden" name="module" id="module" value="{$MAP.MODULE}">
  		<input type="hidden" name="action" id="action" value="{$MAP.ACTION}">
  		<input type="hidden" name="return_module" id="return_module" value="{$MAP.RETURN_MODULE}">
  		<input type="hidden" name="return_id" id="return_id" value="{$MAP.RETURN_ID}">
  		<input type="hidden" name="return_action" id="return_action" value="{$MAP.RETURN_ACTION}">
		<input type="hidden" name="fileId" id="fileId" value="{$MAP.FILEID}">
		<h3>{$MOD.LBL_SUMMARY_TITLE}:</h3>
		<table class="edit view">
			<tr><td width="25%"></td></tr>
			{foreach from=$MAP.SUMMARY key=KEY item=INFO}
				<input type="hidden" name="summary[{$KEY}]" id="summary" size=50 value="{$INFO}">
  				<tr>
  					{assign var="lbl_summary_field" value="LBL_SUMMARY_"|cat:$KEY|upper}
  					<td style="text-align: left; border-bottom: 0.5px inset silver; line-height: 1.7;">
  						{$MOD[$lbl_summary_field]}:
					</td>
					<td>
						{$INFO}
  					</td>
  				</tr>
  			{/foreach}
			<tr>
		</table>
		{if count($MAP.LOG.aut) > 0}
			<h3>{$MOD.LBL_RESULTS_INCORPORA_CONNECTION_TITLE}:</h3>
			<table class="edit view">
				<tr><td width="3%"></td></tr>
				<tr>
					<td style="color:red; font-size:15px;">&#10008;</td>
					<td style="border-bottom: 0.5px inset silver;">
						{$MAP.LOG.aut.msg}
					</td>
				</tr>
			</table>
		{/if}
		{if count($MAP.LOG.logs) > 0}
			<h3>{$MOD.LBL_RESULTS_LOG_TITLE}:</h3>
			<table class="edit view">
				<tr><td></td></tr>
				<tr>
					<th width="2%"></th>
					<th width="20%" style="text-align: left">{$MOD.LBL_LIST_NAME}</th>
					<th style="text-align: left">{$MOD.LBL_RESULTS_ERRORS_LOG}</th>
				</tr>
				{foreach from=$MAP.LOG.logs item=ITEM}
					<tr>
						{if $ITEM.error eq true}
							<td style="color:red; font-size:15px;">{counter}&#10008;</td>
						{else}
							<td style="color:green; font-size:15px;">{counter}&#9989;</td>
						{/if}
						<td style="border-bottom: 0.5px inset silver;">
							{$ITEM.url}
						</td>
						<td style="border-bottom: 0.5px inset silver;">
							{$ITEM.msg}
						</td>
					</tr>
				{/foreach}
  			</table>
		  {/if}
		  <br>
		  <table width="100%">
		  
  			<tr>
  		    	<td>
  			    	<input type="submit" id="cancel_button" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" class="button" name="close_button" value="{$APP.LBL_CLOSE_BUTTON_TITLE}" onclick="this.form.action.value='{$MAP.RETURN_ACTION}'; this.form.module.value='{$MAP.RETURN_MODULE}'; this.form.record.value='{$MAP.RETURN_ID}';"> 
  				</td>
  		    </tr>
		</table>
	</form>
</div>
