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

// ---------- Helpers ----------
function defineTemplate(html) {
  const tpl = document.createElement("template");
  tpl.innerHTML = html.trim();
  return tpl;
}

// ---------- FieldText ----------
const tplSticFieldText = (() => {
  const tpl = document.createElement("template");
  tpl.innerHTML = `
    <div>
      <label class="form-label" ></label>
      <input type="text" class="form-control" >
    </div>
  `;
  return tpl;
})();
class SticFieldText extends HTMLElement {
  connectedCallback() {
    const idBase = this.getAttribute("id") || utils.newId("ft-");

    // Clone template
    const clone = tplSticFieldText.content.cloneNode(true);
    const wrapper = clone.firstElementChild;
    const label = wrapper.querySelector("label");
    const input = wrapper.querySelector("input");

    // Label
    label.setAttribute("id", `${idBase}_label`);
    label.setAttribute("for", `${idBase}_input`);
    if (this.getAttribute("label")) {
      label.setAttribute("x-text", `utils.translateForFieldLabel('${this.getAttribute("label")}')`);
    }
    if (this.hasAttribute("required")) {
      label.classList.add("field-required");
    }

    // Input
    input.setAttribute("id", `${idBase}_input`);
    input.setAttribute("x-model", this.getAttribute("x-model") || "");
    if (this.getAttribute("help")) {
      input.setAttribute(":placeholder", `utils.translate('${this.getAttribute("help")}')`);
    }
    if (this.hasAttribute("required")) {
      input.setAttribute("required", "");
    }
    if(this.hasAttribute("keydown.enter")) {
      input.addEventListener('keydown',(e) => {
        if (e.key==='Enter'){
          Alpine.evaluate(document.getElementById('stic_panel'), this.getAttribute("keydown.enter"));
        }
      });
    }
    if(this.hasAttribute("keydown.esc")) {
      input.addEventListener('keydown',(e) => {
        if (e.key==='Escape'){
          Alpine.evaluate(document.getElementById('stic_panel'), this.getAttribute("keydown.esc"));
        }
      });
    }

    // Other attributes
    const reserved = ["id", "label", "help", "x-model"];
    const wrapperAttrs = ["class", "style", "hidden", "title"];
    for (const { name, value } of this.attributes) {
      if (reserved.includes(name)) continue;
      if (wrapperAttrs.includes(name)) continue;
      input.setAttribute(name, value);
    }

    this.appendChild(clone);
    requestAnimationFrame(() => Alpine.initTree(this));
  }
}
customElements.define("stic-field-text", SticFieldText);

// ---------- FieldTextarea ----------
const tplSticFieldTextarea = (() => {
  const tpl = document.createElement("template");
  tpl.innerHTML = `
    <div>
    <label class="form-label"></label>
    <textarea class="form-control"></textarea>
    </div>
  `;
  return tpl;
})();
class SticFieldTextarea extends HTMLElement {
  connectedCallback() {
    const idBase = this.getAttribute("id") || utils.newId("fta-");

    // Clone template
    const clone = tplSticFieldTextarea.content.cloneNode(true);
    const wrapper = clone.firstElementChild;
    const label = wrapper.querySelector("label");
    const textarea = wrapper.querySelector("textarea");

    // Label
    label.setAttribute("id", `${idBase}_label`);
    label.setAttribute("for", `${idBase}_textarea`);
    if (this.getAttribute("label")) {
      label.setAttribute("x-text", `utils.translateForFieldLabel('${this.getAttribute("label")}')`);
    }
    if (this.hasAttribute("required")) {
      label.classList.add("field-required");
    }

    // Textarea
    textarea.setAttribute("id", `${idBase}_textarea`);
    textarea.setAttribute("x-model", this.getAttribute("x-model") || "");
    if (this.getAttribute("help")) {
      textarea.setAttribute(":placeholder", `utils.translate('${this.getAttribute("help")}')`);
    }
    if (this.hasAttribute("required")) {
      textarea.setAttribute("required", "");
    }

    // Other attributes
    const reserved = ["id", "label", "help", "x-model"];
    const wrapperAttrs = ["class", "style", "hidden", "title"];
    for (const { name, value } of this.attributes) {
      if (reserved.includes(name)) continue;
      if (wrapperAttrs.includes(name)) continue;
      textarea.setAttribute(name, value);
    }

    this.appendChild(clone);
    requestAnimationFrame(() => Alpine.initTree(this));
  }
}
customElements.define("stic-field-textarea", SticFieldTextarea);

// ---------- FieldCheckbox ----------
const tplSticFieldCheckbox = (() => {
  const tpl = document.createElement("template");
  tpl.innerHTML = `
    <div>
      <label class="form-label me-2" ></label>
      <input type="checkbox" class="form-check-input" role="switch"/>
    </div>
  `;
  return tpl;
})();
class SticFieldCheckbox extends HTMLElement {
  connectedCallback() {
    const idBase = this.getAttribute("id") || utils.newId("fc-");

    // Clone template
    const clone = tplSticFieldCheckbox.content.cloneNode(true);
    const wrapper = clone.firstElementChild;
    const label = wrapper.querySelector("label");
    const input = wrapper.querySelector("input");

    // Label
    label.setAttribute("id", `${idBase}_label`);
    label.setAttribute("for", `${idBase}_input`);
    if (this.getAttribute("label")) {
      label.setAttribute("x-text", `utils.translateForFieldLabel('${this.getAttribute("label")}')`);
    }
    if (this.hasAttribute("required")) {
      label.classList.add("field-required");
    }

    // Input
    input.setAttribute("id", `${idBase}_input`);
    input.setAttribute("x-model", this.getAttribute("x-model") || "");
    if (this.hasAttribute("required")) {
      input.setAttribute("required", "");
    }

    // Other attributes
    const reserved = ["id", "label", "help", "x-model"];
    const wrapperAttrs = ["class", "style", "hidden", "title"];
    for (const { name, value } of this.attributes) {
      if (reserved.includes(name)) continue;
      if (wrapperAttrs.includes(name)) continue;
      input.setAttribute(name, value);
    }

    this.appendChild(clone);
    requestAnimationFrame(() => Alpine.initTree(this));
  }
}
customElements.define("stic-field-checkbox", SticFieldCheckbox);

// ---------- FieldSelect ----------
const tplSticFieldSelect = (() => {
  const tpl = document.createElement("template");
  tpl.innerHTML = `
    <div>
      <label class="form-label"></label>
      <select class="form-select"></select>    
    </div>
  `;
  return tpl;
})();
class SticFieldSelect extends HTMLElement {
  connectedCallback() {
    const idBase = this.getAttribute("id") || utils.newId("fs-");

    // Clone template
    const clone = tplSticFieldSelect.content.cloneNode(true);
    const wrapper = clone.firstElementChild;
    const label = wrapper.querySelector("label");
    const select = wrapper.querySelector("select");

    // Label
    label.setAttribute("id", `${idBase}_label`);
    label.setAttribute("for", `${idBase}_select`);
    if (this.getAttribute("label")) {
      label.setAttribute("x-text", `utils.translateForFieldLabel('${this.getAttribute("label")}')`);
    }
    if (this.hasAttribute("required")) {
      label.classList.add("field-required");
    }

    const model = this.getAttribute("x-model") || "";
    const map = this.getAttribute("map") || "";
    const valueProp = this.getAttribute("map-value") ? "." + this.getAttribute("map-value") : "Key";
    const textProp = this.getAttribute("map-text") ? "." + this.getAttribute("map-text") : "";

    // Select
    select.setAttribute("id", `${idBase}_select`);
    select.setAttribute("x-model", model);
    select.setAttribute("value", this.getAttribute("value") || "");
    if (this.hasAttribute("required")) {
      select.setAttribute("required", "");
    }
    if (this.hasAttribute("multiple")) {
      select.setAttribute("multiple", "multiple");
    }
    select.setAttribute(
      "x-init",
      `$nextTick(() => {
        let select = $('#${idBase}_select').selectize({ placeholder: '', dropdownParent: 'body', onChange: (value) => { ${model} = value }})[0]?.selectize;
        select?.setValue(${model});
      });`
    );
  
    select.innerHTML = `
    <template x-for="[elKey, el] in Object.entries(${map})" :key="elKey">
      <option :value="el${valueProp}" x-text="el${textProp}"></option>
    </template>`;

    // Other attributes
    const reserved = ["id", "label", "help", "x-model"];
    const wrapperAttrs = ["class", "style", "hidden", "title"];
    for (const { name, value } of this.attributes) {
      if (reserved.includes(name)) continue;
      if (wrapperAttrs.includes(name)) continue;
      select.setAttribute(name, value);
    }

    this.appendChild(clone);
    requestAnimationFrame(() => Alpine.initTree(this));
  }
}
customElements.define("stic-field-select", SticFieldSelect);

// ---------- FieldSelectDynamic ----------
const tplSticFieldSelectDynamic = (() => {
  const tpl = document.createElement("template");
  tpl.innerHTML = `
  <div 
    x-data="{ 
      items: [],
      open: false,
      search: '',
      selectedItem: null,

      model: '',
      mapExpr: '',
      valueProp: 'id',
      textProp: 'text',

      loadItems() {
        try {
          const data = Alpine.evaluate(this.$root.closest('[x-data]'), this.mapExpr) || [];
          this.items = Array.isArray(data) ? data : Object.values(data);
        } catch (e) {
          console.error('Error loading items from map expression:', e);
          this.items = [];
        }
      },

      filteredItems() {
        // ... To fiter elements
      },
    
      select(item) {
        // ... To select elements
      },
    
      // Reactivity
      watchItems() {
        const parentData = Alpine.$data(this.$root.closest('[x-data]'));
        Alpine.effect(() => {
          const newItems = Alpine.evaluate(parentData, this.mapExpr);
          this.items = Array.isArray(newItems) ? newItems : Object.values(newItems);
        });
      },
    
      // Initialization
      init() {
        this.watchItems();
        const initialValue = Alpine.evaluate(this.$root.closest('[x-data]'), this.model);
        if (initialValue) {
          const selected = this.items.find(item => item[this.valueProp] == initialValue);
          if (selected) {
            this.selectedItem = selected;
            this.search = selected[this.textProp];
          }
        }
      }
    }"
    @click.away="open = false"
    class="relative"
    >
    <div class="input-group">
      <input type="text" x-model="search" @focus="open = true" class="form-control" placeholder="" />
      <button class="btn btn-outline-secondary" type="button" @click="open = !open">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
          <path d="M7.247 11.14L2.451 5.658C1.885 5.093 2.221 4 3.003 4h9.994c.782 0 1.118 1.093.553 1.658L8.753 11.14a1 1 0 0 1-1.506 0z"/>
        </svg>
      </button>
    </div>
    
    <ul class="absolute z-10 w-full bg-white border rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto" x-show="open">
      <template x-for="item in filteredItems()" :key="item[valueProp]">
        <li @mousedown.prevent="select(item)" x-text="item[textProp]" class="cursor-pointer hover:bg-gray-200 p-2"></li>
      </template>
      <li x-show="!filteredItems().length" class="p-2 text-gray-400">No hi ha coincidències.</li>
    </ul>
</div>
  `;
  return tpl;
})();
class SticFieldSelectDynamic extends HTMLElement {
  connectedCallback() {
    const idBase = this.getAttribute("id") || utils.newId("fsd-");

    const model = this.getAttribute("x-model") || "";
    const mapExpr = this.getAttribute("map") || "[]";
    const valueProp = this.getAttribute("map-value") || "id";
    const textProp = this.getAttribute("map-text") || "text";

    // Clone template
    const clone = tplSticFieldSelectDynamic.content.cloneNode(true);
    const wrapper = clone.firstElementChild;
    const label = wrapper.querySelector("label");
    const select = wrapper.querySelector("select");

    // Label
    label.setAttribute("id", `${idBase}_label`);
    label.setAttribute("for", `${idBase}_select`);
    if (this.getAttribute("label")) {
      label.setAttribute("x-text", `utils.translateForFieldLabel('${this.getAttribute("label")}')`);
    }

    // Select
    select.setAttribute("id", `${idBase}_select`);
    if (this.hasAttribute("required")) {
      select.setAttribute("required", "");
    }
    const multiple = this.hasAttribute("multiple");
    if (multiple) {
      select.setAttribute("multiple", "multiple");
    }

    // Other attributes
    const reserved = ["id", "label", "help", "x-model"];
    const wrapperAttrs = ["class", "style", "hidden", "title"];
    for (const { name, value } of this.attributes) {
      if (reserved.includes(name)) continue;
      if (wrapperAttrs.includes(name)) continue;
      select.setAttribute(name, value);
    }

    this.appendChild(clone);
    requestAnimationFrame(() => Alpine.initTree(this));
  }
}
customElements.define("stic-field-select-dynamic", SticFieldSelectDynamic);

