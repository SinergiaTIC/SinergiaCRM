<div class="moduleTitle" style="margin-bottom: 20px;">
  <h2>{$MOD.LBL_VERIFACTU_QUERY_TITLE}</h2>
</div>

<div id="verifactuPeriodError" class="alert alert-danger" style="display: none; clear: both; margin-bottom: 12px; padding: 12px; border-left: 4px solid #a94442; background-color: #f2dede;">
  <strong>{$MOD.LBL_VERIFACTU_QUERY_ERROR_PREFIX}</strong> {$MOD.LBL_VERIFACTU_QUERY_PERIOD_REQUIRED}
</div>

<div class="panel panel-default" id="verifactuFilterPanel" style="margin-bottom: 20px; clear: both;">
  <div class="panel-heading" data-toggle="collapse" data-target="#filterPanel" role="button" tabindex="0">
    <div style="display: flex; align-items: center; gap: 6px; padding: 0 12px; height: 32px; line-height: 32px;">
      <span id="filterToggleIcon" class="suitepicon suitepicon-action-caret" style="transform: rotate({if $FORM_PANEL_STATE == 'expanded'}0{else}-90{/if}deg); transition: transform 0.2s; font-size: 16px;"></span>
      <span style="font-size: 13px; font-weight: 600; letter-spacing: 0.5px; white-space: nowrap; color: #fff;">{$MOD.LBL_VERIFACTU_QUERY_FILTERS_LABEL}</span>
      {if $FORM_ACTIVE_COUNT > 0}
      <span style="flex-shrink: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
        {foreach from=$FORM_ACTIVE_FILTERS item=filter}
        <span style="display: inline-block; font-size: 11px; font-weight: 500; padding: 1px 7px; margin: 0 3px; background: rgba(255,255,255,0.2); color: #fff; border-radius: 3px; line-height: 20px; vertical-align: middle;">{$filter}</span>
        {/foreach}
      </span>
      <span id="clearVerifactuFilters" onclick="VerifactuQuery.clearFilters()" style="display: inline-block; width: 20px; height: 20px; line-height: 20px; text-align: center; background: #D32F2F; color: #fff; border-radius: 3px; font-size: 14px; font-weight: 700; cursor: pointer; vertical-align: middle; font-family: Arial, sans-serif;" title="{$MOD.LBL_VERIFACTU_QUERY_CLEAR}">X</span>
      {/if}
      {if $HAS_RESULT && $RESULT_SUCCESS}
      <span style="margin-left: auto; font-size: 12px; font-weight: 500; color: #fff; white-space: nowrap;">{$RESULT_COUNT} {$MOD.LBL_VERIFACTU_QUERY_REGISTROS}</span>
      {/if}
    </div>
  </div>

  <div id="filterPanel" class="panel-collapse collapse{if $FORM_PANEL_STATE == 'expanded'} in{/if}">
    <div class="panel-body" style="padding: 0;">
      <form name="VerifactuQueryForm" method="POST" action="index.php" style="margin: 0; padding: 20px 24px;">
        <input type="hidden" name="module" value="AOS_Invoices">
        <input type="hidden" name="action" value="QueryAeatInvoices">
        <input type="hidden" name="query" value="1">
        <input type="hidden" name="panel_state" id="panel_state" value="{$FORM_PANEL_STATE}">

        <div class="row" style="margin-bottom: 4px;">
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_YEAR}</label>
            <input type="number" name="year" value="{$FORM_YEAR}" min="2024" max="2099" style="width: 100%;" required>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_PERIOD}</label>
            <div id="verifactuPeriodChips" class="period-chips">
              {foreach from=$FORM_PERIOD_OPTIONS item=opt}
              <button type="button" class="period-chip{if $opt.SELECTED} selected{/if}" data-value="{$opt.VALUE}" onclick="VerifactuQuery.togglePeriod(this)">{$opt.LABEL}</button>
              {/foreach}
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_DATE_FROM}</label>
            <input type="date" name="date_from" value="{$FORM_DATE_FROM}" style="width: 100%;">
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_DATE_TO}</label>
            <input type="date" name="date_to" value="{$FORM_DATE_TO}" style="width: 100%;">
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER}</label>
            <input type="text" name="serie_number" value="{$FORM_SERIE_NUMBER}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_SERIE_NUMBER_PLACEHOLDER}" style="width: 100%;">
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF}</label>
            <input type="text" name="counterparty_nif" value="{$FORM_COUNTERPARTY_NIF}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF_PLACEHOLDER}" style="width: 100%;">
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px;">
            <label style="display: block; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #534D64; letter-spacing: 0.5px;">{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME}</label>
            <input type="text" name="counterparty_name" value="{$FORM_COUNTERPARTY_NAME}" placeholder="{$MOD.LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME_PLACEHOLDER}" style="width: 100%;">
          </div>
          <input type="hidden" name="filter_by_sif" value="1">
          <div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom: 12px; padding-top: 22px;">
            <label style="font-weight: 400; cursor: pointer; font-size: 13px; color: #534D64;">
              <input type="checkbox" name="nest_rectified" value="1"{if $FORM_NEST_CHECKED} checked{/if} style="margin-right: 4px; vertical-align: text-top;"> {$MOD.LBL_VERIFACTU_QUERY_NEST_RECTIFIED}
            </label>
          </div>
        </div>

        <div style="text-align: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid #E6E6E6;">
          <button type="submit" class="button"><span class="glyphicon glyphicon-search text-success"></span>
				{$MOD.LBL_VERIFACTU_QUERY_BUTTON}</button>
        </div>
      </form>
    </div>
  </div>
</div>

{if $HAS_RESULT}
  {if $RESULT_SUCCESS}
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
{/if}

{literal}
<style>
#verifactuFilterPanel {
  border: 1px solid #353535;
  border-radius: 4px;
}
#verifactuFilterPanel > .panel-heading {
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  background: #353535;
  border-bottom: 1px solid #353535;
  color: #fff;
  border-radius: 3px 3px 0 0;
}
#verifactuFilterPanel > .panel-heading:hover {
  background: #2A2A2A;
}
#verifactuFilterPanel > .panel-body {
  background: #F5F5F5;
}
#verifactuFilterPanel > .panel-heading .suitepicon-action-caret {
  font-size: 16px;
  line-height: 32px;
}
#verifactuFilterPanel .panel-body input:not([type="checkbox"]):not([type="hidden"]) {
  height: 32px;
  box-sizing: border-box;
}
.period-chips {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 2px;
  height: 32px;
  padding: 0;
  border: 1px solid #b5bc31;
  border-radius: 4px;
  background: #fff;
  box-sizing: border-box;
  width: 100%;
}
.period-chips.error {
  border-color: #a94442;
}
.period-chip {
  flex-shrink: 0;
  border: 1px solid #ccc;
  border-radius: 3px;
  background: #f5f5f5;
  color: #534D64;
  padding: 4px 0;
  min-width: 22px;
  font-size: 11px;
  font-weight: 600;
  line-height: 1;
  box-sizing: border-box;
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  text-align: center;
  transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.period-chip:hover {
  border-color: #7f7f7f;
}
.period-chip.selected {
  background: #353535;
  border-color: #353535;
  color: #fff;
}

</style>
<script>
(function () {
  var STORAGE_KEY = 'verifactu_query_filters';

  var $toggle = jQuery('#filterToggleIcon');
  var $panel = jQuery('#filterPanel');

  if ($panel.length) {
    $panel.on('show.bs.collapse', function () {
      $toggle.css('transform', 'rotate(0deg)');
      jQuery('#panel_state').val('expanded');
    });
    $panel.on('hide.bs.collapse', function () {
      $toggle.css('transform', 'rotate(-90deg)');
      jQuery('#panel_state').val('collapsed');
    });
  }

  window.VerifactuQuery = window.VerifactuQuery || {};

  function getPeriodInputs(form) {
    var els = form && form.elements['period[]'];
    if (!els) {
      return [];
    }
    return els.length !== undefined ? Array.prototype.slice.call(els) : [els];
  }

  function getSelectedPeriods(form) {
    return getPeriodInputs(form).map(function (input) {
      return input.value;
    }).filter(function (value) {
      return value !== '';
    });
  }

  function syncPeriodInputs(form) {
    var chips = document.querySelectorAll('#verifactuPeriodChips .period-chip.selected');
    var months = Array.prototype.map.call(chips, function (chip) {
      return chip.getAttribute('data-value');
    });
    getPeriodInputs(form).forEach(function (input) {
      input.parentNode.removeChild(input);
    });
    months.forEach(function (month) {
      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'period[]';
      hidden.value = month;
      form.appendChild(hidden);
    });
  }

  VerifactuQuery.togglePeriod = function (chip) {
    chip.classList.toggle('selected');
    var form = document.forms['VerifactuQueryForm'];
    if (form) {
      syncPeriodInputs(form);
      saveFilterState();
    }
    hideValidationMessage();
  };

  function hasDateRange(form) {
    return !!((form.elements['date_from'] && form.elements['date_from'].value)
      || (form.elements['date_to'] && form.elements['date_to'].value));
  }

  function isValidQuery(form) {
    return getSelectedPeriods(form).length > 0 || hasDateRange(form);
  }

  VerifactuQuery.clearFilters = function () {
    var form = document.forms['VerifactuQueryForm'];
    if (!form) {
      return;
    }
    if (form.elements['year']) {
      form.elements['year'].value = String(new Date().getFullYear());
    }
    var chips = document.querySelectorAll('#verifactuPeriodChips .period-chip.selected');
    Array.prototype.forEach.call(chips, function (chip) {
      chip.classList.remove('selected');
    });
    syncPeriodInputs(form);
    ['serie_number', 'date_from', 'date_to', 'counterparty_nif', 'counterparty_name'].forEach(function (name) {
      if (form.elements[name]) {
        form.elements[name].value = '';
      }
    });
    if (form.elements['nest_rectified']) {
      form.elements['nest_rectified'].checked = true;
    }
    saveFilterState();
    window.location.href = 'index.php?module=AOS_Invoices&action=QueryAeatInvoices';
  };

  function setPeriodControlBorder(color) {
    var chips = document.getElementById('verifactuPeriodChips');
    if (chips) {
      chips.classList.toggle('error', color === '#a94442');
    }
  }

  function showValidationMessage() {
    var el = document.getElementById('verifactuPeriodError');
    if (el) {
      el.style.display = 'block';
    }
    setPeriodControlBorder('#a94442');
  }

  function hideValidationMessage() {
    var el = document.getElementById('verifactuPeriodError');
    if (el) {
      el.style.display = 'none';
    }
    setPeriodControlBorder('');
  }

  function readFilterState() {
    var form = document.forms['VerifactuQueryForm'];
    if (!form) {
      return null;
    }
    return {
      year: form.elements['year'] ? form.elements['year'].value : '',
      period: getSelectedPeriods(form),
      serie_number: form.elements['serie_number'] ? form.elements['serie_number'].value : '',
      date_from: form.elements['date_from'] ? form.elements['date_from'].value : '',
      date_to: form.elements['date_to'] ? form.elements['date_to'].value : '',
      counterparty_nif: form.elements['counterparty_nif'] ? form.elements['counterparty_nif'].value : '',
      counterparty_name: form.elements['counterparty_name'] ? form.elements['counterparty_name'].value : '',
      nest_rectified: form.elements['nest_rectified'] ? form.elements['nest_rectified'].checked : false,
      panel_state: form.elements['panel_state'] ? form.elements['panel_state'].value : '',
    };
  }

  function saveFilterState() {
    var state = readFilterState();
    if (state) {
      try {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(state));
      } catch (e) {
      }
    }
  }

  function restoreFilterState() {
    var form = document.forms['VerifactuQueryForm'];
    var state = null;
    try {
      state = sessionStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return;
    }
    if (!state || !form) {
      return;
    }
    try {
      state = JSON.parse(state);
    } catch (e) {
      return;
    }
    var months = Array.isArray(state['period']) ? state['period'] : (state['period'] ? [state['period']] : []);
    var chips = document.querySelectorAll('#verifactuPeriodChips .period-chip');
    Array.prototype.forEach.call(chips, function (chip) {
      chip.classList.toggle('selected', months.indexOf(chip.getAttribute('data-value')) !== -1);
    });
    syncPeriodInputs(form);
    ['year', 'serie_number', 'date_from', 'date_to', 'counterparty_nif', 'counterparty_name'].forEach(function (name) {
      if (form.elements[name] && typeof state[name] !== 'undefined') {
        form.elements[name].value = state[name];
      }
    });
    if (form.elements['nest_rectified'] && typeof state['nest_rectified'] !== 'undefined') {
      form.elements['nest_rectified'].checked = !!state['nest_rectified'];
    }
    if (form.elements['panel_state'] && typeof state['panel_state'] !== 'undefined' && state['panel_state']) {
      form.elements['panel_state'].value = state['panel_state'];
    }
  }

  var form = document.forms['VerifactuQueryForm'];
  if (form) {
    form.addEventListener('submit', function (e) {
      saveFilterState();
      if (!isValidQuery(form)) {
        e.preventDefault();
        showValidationMessage();
      } else {
        hideValidationMessage();
      }
    });

    var HAS_POSTBACK = {/literal}{$FORM_HAS_POSTBACK|default:0}{literal};
    if (HAS_POSTBACK !== 1) {
      restoreFilterState();
      if (!isValidQuery(form)) {
        showValidationMessage();
      } else {
        saveFilterState();
        form.submit();
      }
    }
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
