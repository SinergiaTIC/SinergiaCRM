<div class="moduleTitle" style="margin-bottom: 20px;">
  <h2>{$MOD.LBL_VERIFACTU_QUERY_TITLE}</h2>
</div>

<form name="VerifactuQueryForm" method="POST" action="index.php" style="background: #f8f8f8; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px;">
  <input type="hidden" name="module" value="AOS_Invoices">
  <input type="hidden" name="action" value="QueryAeatInvoices">
  <input type="hidden" name="query" value="1">

  <table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
      <td width="12%" style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_YEAR}</td>
      <td width="21%"><input type="number" name="year" value="{$FORM_YEAR}" min="2024" max="2099" style="width: 100px;" required></td>
      <td width="12%" style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_PERIOD}</td>
      <td width="21%">
        <select name="period" style="width: 100px;">
          {foreach from=$FORM_PERIOD_OPTIONS item=opt}
          <option value="{$opt.VALUE}"{if $opt.SELECTED} selected{/if}>{$opt.LABEL}</option>
          {/foreach}
        </select>
      </td>
      <td width="12%" style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER}</td>
      <td width="22%"><input type="text" name="serie_number" value="{$FORM_SERIE_NUMBER}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER_PLACEHOLDER}" style="width: 200px;"></td>
    </tr>
    <tr>
      <td style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_DATE_FROM}</td>
      <td><input type="date" name="date_from" value="{$FORM_DATE_FROM}" style="width: 180px;"></td>
      <td style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_DATE_TO}</td>
      <td><input type="date" name="date_to" value="{$FORM_DATE_TO}" style="width: 180px;"></td>
      <td style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF}</td>
      <td><input type="text" name="counterparty_nif" value="{$FORM_COUNTERPARTY_NIF}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF_PLACEHOLDER}" style="width: 150px;"></td>
    </tr>
    <tr>
      <td style="font-weight: bold; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME}</td>
      <td colspan="5"><input type="text" name="counterparty_name" value="{$FORM_COUNTERPARTY_NAME}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME_PLACEHOLDER}" style="width: 300px;"></td>
    </tr>
    <input type="hidden" name="filter_by_sif" value="1">
    <tr>
      <td colspan="6" style="padding-top: 10px;">
        <label style="font-weight: normal; cursor: pointer;">
          <input type="checkbox" name="nest_rectified" value="1"{if $FORM_NEST_CHECKED} checked{/if} style="margin-right: 6px;"> {$MOD.LBL_VERIFACTU_QUERY_NEST_RECTIFIED}
        </label>
      </td>
    </tr>
    <tr>
      <td colspan="6" style="padding-top: 15px; text-align: center;">
        <button type="submit" class="button primary" style="padding: 8px 30px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;">
          <span class="suitepicon suitepicon-action-search"></span> {$MOD.LBL_VERIFACTU_QUERY_BUTTON}
        </button>
        &nbsp;&nbsp;
        <button type="button" class="button" onclick="window.location.href='index.php?module=AOS_Invoices&action=index'" style="padding: 8px 20px; font-size: 14px; display: inline-flex; align-items: center;">
          {$MOD.LBL_VERIFACTU_QUERY_CANCEL}
        </button>
      </td>
    </tr>
  </table>
</form>

{if $HAS_RESULT}
<div style="margin-bottom: 20px;">
  {if $RESULT_SUCCESS}
    <div class="alert {$RESULT_STATUS_CLASS}" style="margin: 10px 0; padding: 12px; border-left: 4px solid {$RESULT_STATUS_COLOR};">
      <strong>{$MOD.LBL_VERIFACTU_QUERY_RESULT}</strong> {$RESULT_OUTCOME} ({$RESULT_COUNT} {$MOD.LBL_VERIFACTU_QUERY_REGISTROS})
      {if $RESULT_HAS_MORE}
       &mdash; <em>{$MOD.LBL_VERIFACTU_QUERY_PAGINATION}</em>
      {/if}
      {if $RESULT_LAST_KEY}
       <br><small>{$MOD.LBL_VERIFACTU_QUERY_LAST_KEY} {$RESULT_LAST_KEY}</small>
      {/if}
    </div>

    {if $HAS_ROWS}
    <table class="list view table table-bordered" style="width: 100%;">
      <thead>
        <tr>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_SERIE}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_DATE}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_TYPE}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_AMOUNT}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_CLIENT}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_CLIENT_NIF}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_STATUS}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_PRESENTATION}</th>
          <th style="white-space: nowrap; padding: 8px;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_ERROR}</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$TABLE_ROWS item=row}
        <tr class="{$row.ROW_CLASS}" style="{$row.ROW_STYLE}">
          <td style="{$row.TD_PAD}">{$row.SERIE_PREFIX}{$row.SERIE_LINK}</td>
          <td style="{$row.TD_PAD}white-space: nowrap;">{$row.ISSUE_DATE}</td>
          <td style="{$row.TD_PAD}">{$row.TYPE_LABEL}</td>
          <td style="{$row.TD_PAD}text-align: right;">{$row.TOTAL_AMOUNT}</td>
          <td style="{$row.TD_PAD}max-width: 180px; overflow: hidden; text-overflow: ellipsis;">{$row.CLIENT_NAME}</td>
          <td style="{$row.TD_PAD}white-space: nowrap;">{$row.CLIENT_NIF}</td>
          <td style="{$row.TD_PAD}">{$row.STATUS_BADGE}</td>
          <td style="{$row.TD_PAD}white-space: nowrap; font-size: 11px;">{$row.PRESENTATION_DATE}</td>
          <td style="{$row.TD_PAD}max-width: 200px; overflow: hidden; text-overflow: ellipsis; font-size: 11px;">{$row.ERROR}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
    {/if}
  {else}
    <div class="alert alert-danger" style="margin: 10px 0; padding: 12px; border-left: 4px solid #a94442; background-color: #f2dede;">
      <strong>{$MOD.LBL_VERIFACTU_QUERY_ERROR_PREFIX}</strong> {$RESULT_ERROR_MSG}
    </div>
  {/if}
</div>
{/if}
