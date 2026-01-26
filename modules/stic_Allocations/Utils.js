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
var module = "stic_Allocations";

/* INCLUDES */


/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    setAutofill(["name"]);

    checkBlockedAllocation();
    // add observer for blocked changes
    $("form").on("change", "#blocked", function () {
      checkBlockedAllocation();
    });

    break;

  case "detail":
    checkBlockedAllocationInDetailView();
    // add observer for [field='blocked'] changes
    const targetNode = document.querySelector('[field="blocked"]');
    var observer = new MutationObserver(function(mutations) {
      checkBlockedAllocationInDetailView();
    });
    observer.observe(targetNode, { childList: true, subtree: true});


    const validatedNode = document.querySelector('[field="validated"]');
    var observerValidated = new MutationObserver(function(mutations) {
      showSubPanel('stic_allocations_stic_justifications', null, true);
    });
    observerValidated.observe(validatedNode, { childList: true, subtree: true});

    break;

  case "list":
    break;

  default:
    break;
}

/* AUX FUNCTIONS */
function checkBlockedAllocation() {
  var blocked = $("#blocked").is(":checked");
  if (blocked) {
    $(".edit-view-row-item input").prop('disabled', true); // text, decimals, checks, etc.
    $(".edit-view-row-item select").prop('disabled', true); // desplegables
    $("button[type='button']:not(.saveAndContinue)").prop('disabled', true); // buttons except "Save and Continue Edit"
    $("#blocked").prop('disabled', false); // keep blocked enabled
  }
  else {
    $(".edit-view-row-item input").prop('disabled', false);
    $(".edit-view-row-item select").prop('disabled', false);
    $("button[type='button']").prop('disabled', false);
  }
}

function checkBlockedAllocationInDetailView() {
  // To block the inline edit on double click, we add/remove event listeners to the parent element of inlineEdit fields
  var blocked = $("[field='blocked'] input").is(":checked");

  $("#delete_button").hide(); // hide delete button

  if (blocked) {
    $(".inlineEdit").each(function() {
      if ($(this).attr('field') !== 'blocked') {
        $(this).parent()[0].addEventListener('dblclick', blockDblClick, true);
      }
    });
  }
  else {
    $("#delete_button").show(); // show delete button
    $(".inlineEdit").each(function() {
      if ($(this).attr('field') !== 'blocked') {
        $(this).parent()[0].removeEventListener('dblclick', blockDblClick, true);
      }
    });
  }
}

function blockDblClick(event) {
  event.stopPropagation();
  event.preventDefault(); 
}
