<div class="moduleTitle" style="margin-bottom: 20px;">
  <h2>{$MOD.LBL_VERIFACTU_QUERY_TITLE}</h2>
</div>

<div class="panel panel-default" id="verifactuFilterPanel" style="margin-bottom: 20px; clear: both;">
  <div class="panel-heading" data-toggle="collapse" data-target="#filterPanel" role="button" tabindex="0">
    <div style="display: flex; align-items: center; gap: 6px; padding: 0 12px; height: 32px; line-height: 32px;">
      <span id="filterToggleIcon" class="suitepicon suitepicon-action-caret" style="transform: rotate(-90deg); transition: transform 0.2s; font-size: 16px;"></span>
      <span style="font-size: 13px; font-weight: 600; letter-spacing: 0.5px; white-space: nowrap;">{$MOD.LBL_VERIFACTU_QUERY_FILTERS_LABEL}</span>
      {if $FORM_ACTIVE_COUNT > 0}
      <span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
        {foreach from=$FORM_ACTIVE_FILTERS item=filter}
        <span style="display: inline-block; font-size: 11px; font-weight: 500; padding: 1px 7px; margin: 0 3px; background: rgba(255,255,255,0.2); color: #fff; border-radius: 3px; line-height: 20px; vertical-align: middle;">{$filter}</span>
        {/foreach}
      </span>
      <a href="index.php?module=AOS_Invoices&action=QueryAeatInvoices" style="font-size: 11px; font-weight: 500; padding: 1px 7px; background: rgba(255,255,255,0.15); color: #fff; border-radius: 3px; line-height: 20px; vertical-align: middle; text-decoration: none; white-space: nowrap; border: 1px solid rgba(255,255,255,0.25);">✕ {$MOD.LBL_VERIFACTU_QUERY_CLEAR}</a>
      <span class="label label-primary" style="font-size: 11px; font-weight: 600; padding: 2px 7px; letter-spacing: 0; line-height: 18px; border-radius: 3px; background: rgba(255,255,255,0.25); color: #fff; border: 1px solid rgba(255,255,255,0.3);">{$FORM_ACTIVE_COUNT}</span>
      {/if}
    </div>
  </div>

  <div id="filterPanel" class="panel-collapse collapse">
    <div class="panel-body" style="padding: 0;">
      <form name="VerifactuQueryForm" method="POST" action="index.php" style="margin: 0; padding: 20px 24px;">
        <input type="hidden" name="module" value="AOS_Invoices">
        <input type="hidden" name="action" value="QueryAeatInvoices">
        <input type="hidden" name="query" value="1">

        <div class="row" style="margin-bottom: 4px;">
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_YEAR}</label>
            <input type="number" name="year" value="{$FORM_YEAR}" min="2024" max="2099" style="width: 100%; max-width: 120px;" required>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_PERIOD}</label>
            <select name="period" style="width: 100%; max-width: 120px;">
              {foreach from=$FORM_PERIOD_OPTIONS item=opt}
              <option value="{$opt.VALUE}"{if $opt.SELECTED} selected{/if}>{$opt.LABEL}</option>
              {/foreach}
            </select>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER}</label>
            <input type="text" name="serie_number" value="{$FORM_SERIE_NUMBER}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER_PLACEHOLDER}" style="width: 100%; max-width: 220px;">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_DATE_FROM}</label>
            <input type="date" name="date_from" value="{$FORM_DATE_FROM}" style="width: 100%; max-width: 180px;">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_DATE_TO}</label>
            <input type="date" name="date_to" value="{$FORM_DATE_TO}" style="width: 100%; max-width: 180px;">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF}</label>
            <input type="text" name="counterparty_nif" value="{$FORM_COUNTERPARTY_NIF}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF_PLACEHOLDER}" style="width: 100%; max-width: 150px;">
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px;">
            <label style="font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME}</label>
            <input type="text" name="counterparty_name" value="{$FORM_COUNTERPARTY_NAME}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME_PLACEHOLDER}" style="width: 100%; max-width: 300px;">
          </div>
          <input type="hidden" name="filter_by_sif" value="1">
          <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 12px; padding-top: 18px;">
            <label style="font-weight: 400; cursor: pointer; font-size: 13px; color: #534D64;">
              <input type="checkbox" name="nest_rectified" value="1"{if $FORM_NEST_CHECKED} checked{/if} style="margin-right: 4px; vertical-align: text-top;"> {$MOD.LBL_VERIFACTU_QUERY_NEST_RECTIFIED}
            </label>
          </div>
        </div>

        <div style="text-align: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid #E6E6E6;">
          <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; min-width: 150px; background: #f5f5f5; color: #333; border: 1px solid #ccc; border-radius: 3px; padding: 6px 16px; font-size: 13px; font-weight: 500; cursor: pointer;">
            <span class="suitepicon suitepicon-action-search" style="font-size: 14px; line-height: 1;"></span> {$MOD.LBL_VERIFACTU_QUERY_BUTTON}
          </button>
          <button type="button" class="button" onclick="window.location.href='index.php?module=AOS_Invoices&action=index'" style="min-width: 100px;">
            {$MOD.LBL_VERIFACTU_QUERY_CANCEL}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

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
    <div class="table-responsive">
    <table class="list view table table-bordered" style="width: 100%;"{literal} data-breakpoints='{"xs": 480, "sm": 768, "md": 992}'{/literal}>
      <thead>
        <tr class="footable-header">
          <th data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_SERIE}</th>
          <th data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_DATE}</th>
          <th data-breakpoints="xs" data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_TYPE}</th>
          <th data-type="html" style="text-align: right;">{$MOD.LBL_VERIFACTU_QUERY_HEADER_AMOUNT}</th>
          <th data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_CLIENT}</th>
          <th data-breakpoints="xs" data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_CLIENT_NIF}</th>
          <th data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_STATUS}</th>
          <th data-breakpoints="xs sm" data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_PRESENTATION}</th>
          <th data-breakpoints="xs sm md" data-type="html">{$MOD.LBL_VERIFACTU_QUERY_HEADER_ERROR}</th>
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
    </div>
    {/if}
  {else}
    <div class="alert alert-danger" style="margin: 10px 0; padding: 12px; border-left: 4px solid #a94442; background-color: #f2dede;">
      <strong>{$MOD.LBL_VERIFACTU_QUERY_ERROR_PREFIX}</strong> {$RESULT_ERROR_MSG}
    </div>
  {/if}
</div>
{/if}

{literal}
<style>
#verifactuFilterPanel > .panel-heading {
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
}
#verifactuFilterPanel > .panel-heading:hover {
  background: #5C6772;
}
#verifactuFilterPanel > .panel-heading .suitepicon-action-caret {
  font-size: 16px;
  line-height: 32px;
}
</style>
<script>
(function () {
  var toggle = document.getElementById('filterToggleIcon');
  var panel = document.getElementById('filterPanel');

  if (panel) {
    panel.addEventListener('show.bs.collapse', function () {
      if (toggle) toggle.style.transform = 'rotate(0deg)';
    });
    panel.addEventListener('hide.bs.collapse', function () {
      if (toggle) toggle.style.transform = 'rotate(-90deg)';
    });
  }

  setTimeout(function () {
    var tbl = document.querySelector('table.list.view.table-bordered');
    if (tbl && typeof jQuery !== 'undefined' && jQuery.fn.footable) {
      jQuery(tbl).footable();
    }
  }, 100);
})();
</script>
{/literal}
