var sticControls = class sticControls {
  static _addAttributes($el) {
    let attributes = $el.dataset.attribute ?? "";
    if (attributes == "") {
      return;
    }

    let id = this._getId($el);
    let element = document.getElementById(id);

    // All attributes with values between ', or ", or withot value (boolean)
    const regex = /(\w+)(?:='([^']*)'|="([^"]*)"|$)/g;
    let match;

    while ((match = regex.exec(attributes)) !== null) {
      const key = match[1];
      let value = match[2] || match[3];

      if (value === undefined) {
        // No value: is a boolean
        element.setAttribute(key, true);
      } else {
        element.setAttribute(key, value);
      }
    }
  }
  static _insertComponent($el, htmlString) {
    if (!$el || !htmlString) {
      console.error("Container or html not defined");
      return;
    }

    $el.innerHTML = "";
    $el.innerHTML = htmlString;

    // Initialize Alpine.js over new content
    Alpine.initTree($el);
    // this._addAttributes($el);
  }
  static _getId($el, suffix="_ctrl") {
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

    let html = 
    `
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

  static _tableObjects($el) {
    let id = this._getId($el);
    let objects = $el.dataset.objects ?? "";
    let showRow = $el.dataset.showRow ?? "";
    let xShowRow = showRow != "" ? `x-show="${showRow}"` : "";
    let attribute = $el.dataset.attribute ?? "";

    debugger;
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
              <td x-text="elem_value"></td>
            </template>
          </tr>
        </template>
      </tbody>
    </table>
    `;
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
    this._insertComponent($el, this._label($el, false) + this._checkbox($el));
  }

  static fieldRadio($el) {
    this._insertComponent($el, this._label($el, false) + this._radio($el));
  }

  static fieldPopup($el) {
    this._insertComponent($el, this._label($el, false) + this._popup($el));
  }

  static elemTableObjects($el) {
    this._insertComponent($el, this._tableObjects($el));
  }
};
