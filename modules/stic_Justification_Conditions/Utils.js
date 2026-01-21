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