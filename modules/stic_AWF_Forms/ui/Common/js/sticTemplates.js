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


// ---------- SearchSelect <stic-select> ----------
/**
 * Visual definition (Template)
 */
const searchSelectTemplate = (optionsVar, modelVar, placeholder) => `
  <div x-data="searchableSelect({ optionsVarName: '${optionsVar}', modelVarName: '${modelVar}', placeholder: '${placeholder}' || utils.translate('LBL_SELECT_PLACEHOLDER') })" 
       x-effect="refreshData()" 
       class="stic-select-container" 
       @click.outside="close()" 
       @resize.window="close()" 
       @scroll.window="close()"
       @stic-select-open.window="if ($event.target !== $el) close()"
       style="position: relative; font-family: Arial, sans-serif;" >

    <div class="form-control form-select" 
         x-ref="trigger" 
         tabindex="0" 
         @click="toggle()" 
         @keydown.enter.prevent="isDisabled ? null : (open ? selectFocused() : toggle())"
         @keydown.space.prevent="isDisabled ? null : (open ? selectFocused() : toggle())"
         @keydown.arrow-down.prevent="isDisabled ? null : (open ? navigateOptions('next') : toggle())"
         @keydown.arrow-up.prevent="isDisabled ? null : (open ? navigateOptions('prev') : null)"
         @keydown.escape.prevent="close(true)"
         :style="getTriggerStyle()">
      
      <span x-show="!open" x-html="selectedLabel ? selectedLabel : placeholder" 
            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-right: 20px;" 
            :class="{'text-muted': !selectedLabel}"></span>
      
      <input x-show="open" x-ref="searchInput" x-model="search" type="text" :placeholder="utils.translate('LBL_SELECT_WRITE_TO_SEARCH')"
             @keydown.tab="selectFocused(false); close()"
             @keydown.enter.prevent.stop="selectFocused()"
             @keydown.arrow-down.prevent.stop="navigateOptions('next')"
             @keydown.arrow-up.prevent.stop="navigateOptions('prev')"
             @keydown.escape.prevent="close(true)"
             style="border: none; outline: none; width: 100%; height: 100%; padding: 0; margin: 0; box-shadow: none; background: transparent; font-size: 15px; color: #333;">
    </div>

    <template x-teleport="body">
      <div x-show="open" x-transition :style="dropdownStyle" x-ref="dropdownList">
        <ul style="list-style: none; padding: 0; margin: 0;">
          <template x-for="(option, index) in filteredOptions" :key="option.id">
            <li @click.stop="selectOption(option)" :class="{ 'selected': option.id == selected, 'focused': index === focusedIndex }"
                @mouseenter="focusedIndex = index" :style="getItemStyle(option.id == selected, index === focusedIndex)"> 
              <span x-html="option.label ? option.label : '&nbsp;'" :style="getTextStyle(option.id == selected)"></span>
            </li>
          </template>
          
          <li x-show="filteredOptions.length === 0" x-text="utils.translate('LBL_SELECT_NO_RESULTS')"
              style="padding: 15px; display: flex; align-items: center; justify-content: center; color: #999; font-style: italic; font-size: 14px; font-family: Arial, sans-serif;">
          </li>
        </ul>
      </div>
    </template>
  </div>
`;

/**
 * Component Logic (Alpine Data)
 */
const registerSearchableSelect = () => {
  const removeAccents = (str) => {
    if (str === null || str === undefined) return '';
    return String(str)
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  };

  Alpine.data('searchableSelect', (config) => ({
    options: [], 
    selected: null,
    selectedLabel: '',
    search: '',
    open: false,
    focusedIndex: -1, 
    isDisabled: false,

    dropdownStyle: {
      position: 'fixed',
      top: '-9999px',
      left: '-9999px',
      width: 'auto',
      maxHeight: '350px', 
      overflowY: 'auto',
      zIndex: 999999,
      background: 'white',
      border: '1px solid #bbb',
      borderRadius: '4px',
      boxShadow: '0 8px 20px rgba(0,0,0,0.2)',
      fontFamily: 'Arial, sans-serif',
    },
    
    placeholder: '',
    optionsVarName: '',
    modelVarName: '',
    isUserSelect: false,
    
    init() {
      this.placeholder = config.placeholder;
      this.optionsVarName = config.optionsVarName;
      this.modelVarName = config.modelVarName;
      
      // Initialize disabled looking into parent
      const parent = this.$el.closest('stic-select');
      if (parent) {
          this.isDisabled = parent.hasAttribute('disabled');
          
          // x-bind:disabled
          const observer = new MutationObserver(() => {
              this.isDisabled = parent.hasAttribute('disabled');
              if (this.isDisabled && this.open) this.close();
          });
          observer.observe(parent, { attributes: true, attributeFilter: ['disabled'] });
      }

      this.$watch('selected', (val) => { 
        this.updateLabel();
        if (this.modelVarName) {
          try {
            // Update internal model
            this.$dispatch('input', val); 
            let setExpr = `${this.modelVarName} = '${val}'`;
            Alpine.evaluate(this.$el, setExpr);

            // Firing event Change (for the external @change)
            this.$el.dispatchEvent(new CustomEvent('change', { 
                detail: { value: val },
                bubbles: true 
            }));

          } catch(e) { }
        }
      });
      
      this.$watch('search', () => { this.focusedIndex = 0; });
    },

    getTriggerStyle() {
        let style = 'position: relative; display: flex; align-items: center; justify-content: space-between; padding: 6px 12px; height: 38px; border: 1px solid #ccc; border-radius: 4px; ';
        if (this.isDisabled) {
            style += 'background-color: #e9ecef; cursor: not-allowed; opacity: 0.7; color: #6c757d;';
        } else {
            style += 'background-color: #fff; cursor: pointer; color: #333;';
        }
        return style;
    },

    getItemStyle(isSelected, isFocused) {
      let style = 'display: flex; align-items: center; min-height: 30px; padding: 6px 10px; transition: background-color 0.05s; cursor: pointer; ';
      if (isFocused) {
        style += 'background-color: #e2e6ea; color: #000; '; 
      } else if (isSelected) {
        style += 'background-color: #f0f7ff; color: #333; '; 
      } else {
        style += 'background-color: white; color: #444; ';
      }
      return style;
    },

    getTextStyle(isSelected) {
      let style = 'display: block; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: inherit;';
      if (isSelected) {
        style += 'font-weight: 600;';
      } else {
        style += 'font-weight: normal;';
      }
      return style;
    },

    refreshData() {
      try {
        let data = [];
        if (this.optionsVarName) {
          data = Alpine.evaluate(this.$el, this.optionsVarName);
        }
        if (Array.isArray(data)) {
          this.options = data;
        } else if (typeof window[this.optionsVarName] !== 'undefined') {
          this.options = window[this.optionsVarName];
        }

        if (this.modelVarName && !this.isUserSelect) {
          let val = Alpine.evaluate(this.$el, this.modelVarName);
          if (val !== this.selected) {
            this.selected = val;
          }
        }
        this.updateLabel();
      } catch (e) {}
    },

    updateLabel() {
      if (!this.options || this.options.length === 0) {
        this.selectedLabel = '';
        return;
      }
      const found = this.options.find(o => o.id == this.selected);
      this.selectedLabel = found ? found.label : '';
    },

    get filteredOptions() {
      if (!this.options) return [];
      if (this.search === '') return this.options;
      
      const cleanSearch = removeAccents(this.search).toLowerCase();
      return this.options.filter(o => removeAccents(o.label).toLowerCase().includes(cleanSearch));
    },

    toggle() {
      if (this.isDisabled) return; // Lock if disabled

      if (this.open) {
        this.close();
      } else {
        this.$dispatch('stic-select-open'); 

        this.calculatePosition();
        this.open = true;
        this.search = ''; 
        
        if (this.selected) {
          const idx = this.filteredOptions.findIndex(o => o.id == this.selected);
          this.focusedIndex = idx >= 0 ? idx : 0;
        } else {
          this.focusedIndex = 0;
        }
        
        this.$nextTick(() => { 
          if(this.$refs.searchInput) this.$refs.searchInput.focus();
          this.scrollToFocused(); 
        });
      }
    },

    navigateOptions(direction) {
      const max = this.filteredOptions.length - 1;
      if (direction === 'next') {
        this.focusedIndex = this.focusedIndex < max ? this.focusedIndex + 1 : 0;
      } else {
        this.focusedIndex = this.focusedIndex > 0 ? this.focusedIndex - 1 : max;
      }
      this.scrollToFocused();
    },

    scrollToFocused() {
      this.$nextTick(() => {
        if (!this.$refs.dropdownList) return;
        const list = this.$refs.dropdownList;
        const items = list.querySelectorAll('li'); 
        if (items[this.focusedIndex]) {
          items[this.focusedIndex].scrollIntoView({ block: 'nearest' });
        }
      });
    },

    selectFocused(refocus = true) {
      const options = this.filteredOptions;
      if (options.length > 0 && this.focusedIndex >= 0 && options[this.focusedIndex]) {
        this.selectOption(options[this.focusedIndex], refocus);
      }
    },

    selectOption(option, refocus = true) {
      this.isUserSelect = true;
      this.selected = option.id;
      this.open = false;
      this.search = '';
      this.$nextTick(() => { 
        this.isUserSelect = false; 
        if(refocus && this.$refs.trigger) this.$refs.trigger.focus();
      });
    },

    close(returnFocus = false) { 
      this.open = false; 
      this.search = ''; 
      if(returnFocus && this.$refs.trigger) this.$refs.trigger.focus();
    },

    calculatePosition() {
      if (!this.$refs.trigger) return;
      const rect = this.$refs.trigger.getBoundingClientRect();
      this.dropdownStyle = {
        ...this.dropdownStyle,
        top: rect.bottom + 'px',
        left: rect.left + 'px',
        width: rect.width + 'px',
        display: 'block'
      };
    },
  }));
};

if (typeof Alpine !== 'undefined') {
    registerSearchableSelect();
} else {
    document.addEventListener('alpine:init', registerSearchableSelect);
}

class SticSelect extends HTMLElement {
  connectedCallback() {
    if (this.querySelector('.stic-select-container')) return;

    const optionsVar = this.getAttribute('options') || '';
    const modelVar = this.getAttribute('model') || '';
    const placeholder = this.getAttribute('placeholder') || '';

    const elementId = this.getAttribute('id') || '';

    this.innerHTML = searchSelectTemplate(optionsVar, modelVar, placeholder);

    if (elementId) {
      const trigger = this.querySelector('[x-ref="trigger"]');
      if (trigger) {
        trigger.id = elementId + "_internal"; 
      }
    }
  }
}
if (!customElements.get('stic-select')) {
  customElements.define('stic-select', SticSelect);
}