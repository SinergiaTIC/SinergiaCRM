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
var module = "stic_Organizational_Environment";

const NETWORK_ENTITY_FIELDS = ["network_organization", "network_person"];
const NETWORK_TYPES = {
  network_organization: "organization",
  network_person: "person",
};

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    initNetworkTypeFields();
    break;
  case "detail":
    applyNetworkEntityDetail();
    break;
  default:
    break;
}

function initNetworkTypeFields() {
  const selector = document.getElementById("network_type_c");
  if (!selector) return;

  const orgBlock = document.querySelector(
    '.edit-view-row-item[data-field="network_organization"]',
  );
  const personBlock = document.querySelector(
    '.edit-view-row-item[data-field="network_person"]',
  );
  if (
    orgBlock &&
    personBlock &&
    orgBlock.parentElement === personBlock.parentElement
  ) {
    const wrapper = document.createElement("div");
    orgBlock.parentElement.insertBefore(wrapper, orgBlock);
    wrapper.appendChild(orgBlock);
    wrapper.appendChild(personBlock);
  }

  const apply = () => {
    clear_all_errors();
    for (const field of NETWORK_ENTITY_FIELDS) {
      setRelateBlockVisibility(field, selector.value === NETWORK_TYPES[field]);
    }
  };
  selector.addEventListener("change", apply);
  apply();
}

function setRelateBlockVisibility(field, visible) {
  const block = document.querySelector(
    `.edit-view-row-item[data-field="${field}"]`,
  );
  if (!block) return;
  block.style.display = visible ? "" : "none";
  if (visible) {
    setRequiredMark(block, true);
  } else {
    setRequiredMark(block, false);
    const text = document.getElementById(field);
    const fk = document.getElementById(`${field}_id_c`);
    if (text) text.value = "";
    if (fk) fk.value = "";
  }
}

function setRequiredMark(block, required) {
  const label = block.querySelector(".label");
  if (!label) return;
  let star = label.querySelector(".required");
  if (required && !star) {
    star = document.createElement("span");
    star.className = "required";
    star.textContent = "*";
    label.appendChild(star);
  } else if (!required && star) {
    star.remove();
  }
}

function applyNetworkEntityDetail() {
  for (const field of NETWORK_ENTITY_FIELDS) {
    const block = document.querySelector(
      `.detail-view-row-item[data-field="${field}"]`,
    );
    if (!block) continue;
    const value = block.querySelector(
      ".detail-view-field[type=relate], .detail-view-field",
    );
    if (value && !value.textContent.trim()) {
      block.style.display = "none";
    }
  }
}
