var sticControls = class sticControls {
  static _insertComponent($el, htmlString) {
    if (!$el || !htmlString) {
      console.error("Container or html not defined");
      return;
    }

    $el.innerHTML = "";
    $el.innerHTML = htmlString;

    // Initialize Alpine.js over new content
    Alpine.initTree($el);
  }
  static _addSectionsToComponent($el) {
    let id = this._getId($el);

    // Sections: [{ headerText, selected, contentId }]
    let sections = JSON.parse($el.dataset.sections ?? "[]");
    for (let i = 0; i < sections.length; i++) {
      let contentId = sections[i].contentId;
      let containerId = `${id}_Body_${i}`;

      document.getElementById(containerId).appendChild(document.getElementById(contentId));
    }

    // Initialize Alpine.js over new content
    Alpine.initTree($el);
  }
  static _getId($el, suffix = "_ctrl") {
    return `${$el.id}${suffix}`;
  }

  static _label($el, addColon = true) {
    let id = this._getId($el);
    let label = $el.dataset.label ?? "";
    let labelText = label != "" ? `translate('${label}')` : `'${$el.dataset.labelText}'` ?? "";
    if (labelText != "") {
      if (addColon) {
        labelText += "+':'";
      }
      return `<label class="form-label" for="${id}" x-text="${labelText}"></label>`;
    }
    return "";
  }

  static _required($el) {
    let isRequired = $el.hasAttribute("required");
    if (isRequired) {
      return `<span class="required">*</span>`;
    }
    return "";
  }

  static _text($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let value = $el.dataset.value ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let attribute = $el.dataset.attribute ?? "";

    return `
    <input class="form-control" type="text" id="${id}" ${required} x-model="${model}" value="${value}" ${attribute}/>`;
  }

  static _textarea($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let value = $el.dataset.value ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let attribute = $el.dataset.attribute ?? "";

    return `
    <textarea class="form-control" id="${id}" ${required} x-model="${model}" value="${value}" ${attribute}/>`;
  }

  static _checkbox($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let value = $el.dataset.value ?? "";
    let attribute = $el.dataset.attribute ?? "";

    return `
    <input class="form-check-input" type="checkbox" role="switch" id="${id}" x-model="${model}" value="${value}" ${attribute}/>`;
  }

  static _radio($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let value = $el.dataset.value ?? "";
    let attribute = $el.dataset.attribute ?? "";

    return `
    <input class="form-check-input" type="radio" id="${id}" x-model="${model}" value="${value}" ${attribute}/>`;
  }

  static _select($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let value = $el.dataset.value ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let map = $el.dataset.map ?? "";
    let mapProperty = $el.dataset.mapProperty ?? "";
    let prop = mapProperty != "" ? `.${mapProperty}` : "";
    let multiselect = $el.dataset.multiselect ?? "";
    let attribute = $el.dataset.attribute ?? "";

    return `
    <select class="form-select" id="${id}" ${required} x-model="${model}" value="${value}" ${attribute}
      x-init="$nextTick(() => {
        let select = $('#${id}').selectize({ placeholder: '', onChange: (value) => { ${model} = value }})[0].selectize;
        select.setValue(${model});
      });">
      <template x-for="[elKey, el] in Object.entries(${map})" :key="elKey">
        <option :value="elKey" x-text="el${prop}"></option>
      </template>
    </select>`;
  }

  static _popup($el) {
    let idName = this._getId($el);
    let idId = this._getId($el, "_id");
    let modelName = $el.dataset.modelName ?? "";
    let modelId = $el.dataset.modelId ?? "";
    let module = $el.dataset.module ?? "";
    let callBackFunction = $el.dataset.callBackFunction ?? "";
    let callBackFunctionStr = callBackFunction != "" ? `'call_back_function':'${callBackFunction}'` : "";
    let fieldToNameArray = $el.dataset.fieldToNameArray ?? "";
    let fieldToNameArrayStr = fieldToNameArray != "" ? `'field_to_name_array':${fieldToNameArray}` : "";
    let mode = $el.dataset.mode ?? "single"; // single / MultiSelect

    let html = `
    <div class="input-group">
      <input class="form-control" type="text" id="${idName}" x-model="${modelName}" autocomplete="off" />
      <input type="hidden" id="${idId}" x-model="${modelId}" />
      <button class="btn" type="button" 
        @click="open_popup('${module}', 600, 400, '', true, false, {${callBackFunctionStr},${fieldToNameArrayStr}},'${mode}', true);">
        <span class="suitepicon suitepicon-action-select"></span>
      </button>
      <button class="btn" type="button" @click="${modelId}='';${modelName}='';">
        <span class="suitepicon suitepicon-action-clear"></span>
      </button>
    </div>
    `;
    return html;
  }

  static _tableArray($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let attribute = $el.dataset.attribute ?? "";

    let html = `
    <table id="${id}" class="table table-bordered table-striped" ${attribute}>
      <tbody>
      <template x-for="(element, index) in ${model}">
        <tr>
          <td x-text="index + ':'"></td>
          <td>
            <template x-if="Array.isArray(element) && element.length > 0">
              <div id="${id}_" data-model="${model}[index]" x-init="sticControls.elemTableArray($el);"></div>
            </template>
            <template x-if="typeof element === 'object' && element !== null && !Array.isArray(element)">
              <div id="${id}_" data-model="${model}[index]" x-init="sticControls.elemTableObject($el);"></div>
            </template>
            <template x-if="!Array.isArray(element) && typeof element !== 'object' && element !== null">
              <span x-text="element"></span>
            </template>
          </td>
        </tr>
      </template>
      </tbody>
    </table>
    `;
    return html;
  }

  static _listObject($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let attribute = $el.dataset.attribute ?? "";

    let html = `
    <dl id="${id}" ${attribute}>
      <template x-for="(value, key) in ${model}">
        <div class="row">
          <dt class="col-sm-3" x-text="key+':'"></dt>
          <dd class="col-sm-9">
            <template x-if="Array.isArray(value) && value.length > 0">
              <div id="${id}_" data-model="${model}[key]" x-init="sticControls.elemTableArray($el);"></div>
            </template>
            <template x-if="typeof value === 'object' && value !== null && !Array.isArray(value)">
              <div id="${id}_" data-model="${model}[key]" x-init="sticControls.elemListObject($el);"></div>
            </template>
            <template x-if="!Array.isArray(value) && typeof value !== 'object' && value !== null">
              <span x-text="value"></span>
            </template>          
          </dd>
        </div>
      </template>
    </dl>
    `;
    return html;
  }

  static _tableObjects($el) {
    let id = this._getId($el);
    let objects = $el.dataset.objects ?? "";
    let showRow = $el.dataset.showRow ?? "";
    let xShowRow = showRow != "" ? `x-show="${showRow}"` : "";
    let attribute = $el.dataset.attribute ?? "";

    let html = `
    <table id="${id}" class="table table-bordered table-striped" ${attribute}>
      <thead>
        <tr>
          <template x-for="header in Object.keys(${objects} && ${objects}[Object.keys(${objects})[0]] ? ${objects}[Object.keys(${objects})[0]] : {})">
            <th x-text="header"></th>
          </template>
        </tr>
      </thead>
      <tbody>
        <template x-for="(value, key) in ${objects}">
          <tr ${xShowRow}>
            <template x-for="(elem_value, elem_key) in ${objects}[key]">
              <td>
                <template x-if="Array.isArray(elem_value) && elem_value.length > 0">
                <div id="${id}_" data-model="${objects}[key][elem_key]" x-init="sticControls.elemTableArray($el);"></div>
                </template>
                <template x-if="typeof elem_value === 'object' && elem_value !== null && !Array.isArray(elem_value)">
                  <div id="${id}_" data-model="${objects}[key][elem_key]" x-init="sticControls.elemTableObject($el);"></div>
                </template>
                <template x-if="!Array.isArray(elem_value) && typeof elem_value !== 'object' && elem_value !== null">
                  <span x-text="elem_value"></span>
                </template>
              </td>
            </template>
          </tr>
        </template>
      </tbody>
    </table>
    `;
    return html;
  }

  static _spanOrEdit($el) {
    let id = this._getId($el);
    let model = $el.dataset.model ?? "";
    let condition = $el.dataset.condition ?? "true";
    let attribute = $el.dataset.attribute ?? "";

    let html = `
    <div x-data="{editing:false}">
      <div x-show="!editing">
        <span class="me-2" x-text="${model}" @dblclick="editing=${condition};" ${attribute}></span>
        <template x-if="${condition}">
          <span class="btn suitepicon suitepicon-action-edit" @click="editing=true;"></span>
        </template>
      </div>
      <div x-show="editing" x-effect="if(editing){ $nextTick(() => $refs['${id}'].focus()) }">
        <input id="${id}" x-ref="${id}" type="text" x-model="${model}" @blur="editing=false;" @keydown.enter="editing=false;" @keydown.esc="editing=false;"/>
      </div>
    </div>
    `;
    return html;
  }

  static _accordion($el) {
    let id = this._getId($el);

    // Sections: [{ headerText, selected, contentId }]
    let sections = JSON.parse($el.dataset.sections ?? "[]");
    let attribute = $el.dataset.attribute ?? "";
    let open = `${id}open`;

    let html = `
    <div class="accordion" id="${id}" ${attribute}>`;
    for (let i = 0; i < sections.length; i++) {
      let headerText = sections[i].headerText;
      let opened = sections[i].selected ? "true" : "false";
      html += `
      <div class="accordion-item" x-data="{ ${open}: ${opened} }">
        <div
          id="${id}_Header_${i}"
          class="btn accordion-header accordion-button collapsed"
          @click="${open} = !${open};"
          :aria-expanded="${open}"
          aria-controls="${id}_Content_${i}"
          data-bs-toggle="collapse"
          data-bs-target="#${id}_Content_${i}"
          x-text="${headerText}"
        ></div>
        <div
          id="${id}_Content_${i}"
          class="accordion-collapse collapse"
          aria-labelledby="${id}_Header_${i}"
        >
          <div id="${id}_Body_${i}" class="accordion-body"> </div>
        </div>
      </div>
      `;
    }

    html += `
    </div>`;
    return html;
  }

  static _tabs($el) {
    let id = this._getId($el);

    // Sections: [{ headerText, selected, contentId }]
    let sections = JSON.parse($el.dataset.sections ?? "[]");
    let attribute = $el.dataset.attribute ?? "";
    let selected = `${id}selected`;

    let html = `
    <div x-data="{ ${selected}: '0' }">
      <div class="stic-tabs" id="${id}"  ${attribute}>`;
    for (let i = 0; i < sections.length; i++) {
      let headerText = sections[i].headerText;
      html += `
        <div id="${id}_HeaderTab_${i}" class="stic-tab">
          <input class="form-check-input" type="radio" id="${id}_Header_${i}" x-model="${selected}" value="${i}"/>
          <label class="form-label" for="${id}_Header_${i}" x-text="${headerText}"></label>
        </div>`;
    }
    html += `
      </div>`;
    for (let i = 0; i < sections.length; i++) {
      html+= `
      <div id="${id}_Body_${i}" class="stic-tabcontent overflow-auto" x-show="${selected} == '${i}'"> </div>`;
    }
    html += `
    </div>`;
    return html;
  }

  static fieldText($el) {
    this._insertComponent($el, this._label($el) + this._required($el) + this._text($el));
  }

  static fieldTextarea($el) {
    this._insertComponent($el, this._label($el) + this._required($el) + this._textarea($el));
  }

  static fieldSelect($el) {
    this._insertComponent($el, this._label($el) + this._required($el) + this._select($el));
  }

  static fieldCheckbox($el) {
    this._insertComponent($el, this._checkbox($el) + this._label($el, false));
  }

  static fieldRadio($el) {
    this._insertComponent($el, this._radio($el) + this._label($el, false));
  }

  static fieldPopup($el) {
    this._insertComponent($el, this._label($el, false) + this._popup($el));
  }

  static elemTableObjects($el) {
    this._insertComponent($el, this._tableObjects($el));
  }

  static elemListObject($el) {
    this._insertComponent($el, this._listObject($el));
  }

  static elemTableArray($el) {
    this._insertComponent($el, this._tableArray($el));
  }

  static elemTableObject($el) {
    this.elemTableArray($el);
  }

  static editableText($el) {
    this._insertComponent($el, this._spanOrEdit($el));
  }

  static accordion($el) {
    this._insertComponent($el, this._accordion($el));
    this._addSectionsToComponent($el);
  }

  static tabs($el) {
    this._insertComponent($el, this._tabs($el));
    this._addSectionsToComponent($el);
  }
};
