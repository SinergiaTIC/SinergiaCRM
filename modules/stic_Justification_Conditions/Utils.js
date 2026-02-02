/**
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
 */
/* HEADER */
// Set module name
var module = "stic_Justification_Conditions";

/* INCLUDES */


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);

    blockEditOpportunityinEditView();
    break;

  case "detail":
    checkBlockedJustificationInDetailView();
    checkAmountAndPercentage();
    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
function blockEditOpportunityinEditView() {
  debugger;
    var justificationConditionId = $('[name="record"]').val() ? $('[name="record"]').val() : $(".listview-checkbox", $(".inlineEditActive").closest("tr")).val();

    if (justificationConditionId) {
      // $(".edit-view-row-item input").prop('disabled', true); // text, decimals, checks, etc.
      $("#opportunities_stic_justification_conditions_name").prop('disabled', true); // text, decimals, checks, etc.
      // $(".edit-view-row-item select").prop('disabled', true); // desplegables
      // $("button[type='button']:not(.saveAndContinue)").prop('disabled', true); // buttons except "Save and Continue Edit"
      $("[name='btn_opportunities_stic_justification_conditions_name']").prop('disabled', true); // buttons except "Save and Continue Edit"
      $("[name='btn_clr_opportunities_stic_justification_conditions_name']").prop('disabled', true); // buttons except "Save and Continue Edit"
    }
}

function checkBlockedJustificationInDetailView() {
      // $(".inlineEdit").each(function() {
      $("[field='opportunities_stic_justification_conditions_name']").each(function() {
      if ($(this).attr('field') !== 'blocked') {
        $(this).parent()[0].addEventListener('dblclick', blockDblClick, true);
      }
    });
}

function blockDblClick(event) {
  event.stopPropagation();
  event.preventDefault(); 
}

function checkAmountAndPercentage() {
  debugger;
  var maxAmount = parseSmartDecimal($("[field='max_allocable_amount_grant']").text());
  var justifiedAmount = parseSmartDecimal($("[field='justified_amount']").text());
  var percentage = parseSmartDecimal($("[field='justified_percentage']").text());

  if (justifiedAmount > maxAmount) {
    $("[field='max_allocable_amount_grant']").css("color", "red");
    var iconUrl = "https://cdn-icons-png.flaticon.com/512/3253/3253156.png";

    // Create the image HTML with inline styles for size and spacing
    var iconHtml = '<img src="' + iconUrl + '" style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" />';

    // Append it to the span
    $('#max_allocable_amount_grant').prepend(iconHtml);

  }

  if (percentage > 100) {
    $("[field='justified_percentage']").css("color", "red");
    var iconUrl = "https://cdn-icons-png.flaticon.com/512/3253/3253156.png";

    // Create the image HTML with inline styles for size and spacing
    var iconHtml = '<img src="' + iconUrl + '" style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" />';

    // Append it to the span
    $('#justified_percentage').prepend(iconHtml);

  }
}

function parseSmartDecimal(text) {
    // Netegem espais i agafem el text
    let val = text.trim();
    
    if (!val) return 0;

    // Mirem quin és l'últim separador (sigui punt o coma)
    const lastComma = val.lastIndexOf(',');
    const lastDot = val.lastIndexOf('.');
    
    // CAS 1: Format Europeu (1.000,00) -> La coma està al final
    if (lastComma > lastDot) {
        // Eliminem el punt de milers i canviem coma per punt
        val = val.replace(/\./g, '').replace(',', '.');
    } 
    // CAS 2: Format Americà (1,000.00) -> El punt està al final
    else if (lastDot > lastComma) {
        // Mirem si el punt separa 3 dígits (seria milers en format EUR: 1.000)
        // o si separa 2 dígits (seria decimal en format USA: 1.00)
        const digitsAfterDot = val.length - lastDot - 1;
        
        if (digitsAfterDot === 2) {
            // Clarament americà amb 2 decimals: eliminem la coma de milers
            val = val.replace(/,/g, '');
        } else {
            // Sembla format europeu sense decimals (ex: 1.000)
            // L'interpretem com a miler
            val = val.replace(/\./g, '');
        }
    }
    // CAS 3: No hi ha separadors
    else {
        // Ja és un número net o no té decimals
    }

    return parseFloat(val) || 0;
}