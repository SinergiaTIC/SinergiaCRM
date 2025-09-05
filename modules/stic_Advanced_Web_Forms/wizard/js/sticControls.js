var sticControls = class sticControls {
  static _insertComponent($container, htmlString) {
    if (!$container || !htmlString) {
      console.error("Container or html not defined");
      return;
    }

    $container.innerHTML = "";
    $container.innerHTML = htmlString;

    // Initialize Alpine.js over new content
    Alpine.initTree($container);
  }
  static _getId($el) {
    return `${$el.id}_field`;
  }

  static _label($el) {
    let label = $el.dataset.label ?? "";
    let labelText = label != "" ? `translate('${label}')` : `'${$el.dataset.labelText}'` ?? "";
    if (labelText != "") {
      return `<label class="form-label" for="${this._getId($el)}" x-text="${labelText}+':'"></label>`;
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
    let model = $el.dataset.model ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let attribute = $el.dataset.attribute ?? "";

    return `<input class="form-control" type="text" id="${this._getId($el)}" ${required} x-model="${model}" ${attribute}/>`;
  }
  
  static _textarea($el) {
    let model = $el.dataset.model ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let attribute = $el.dataset.attribute ?? "";

    return `<textarea class="form-control" id="${this._getId($el)}" ${required} x-model="${model}" ${attribute}/>`;
  }

  static _checkbox($el) {
    let model = $el.dataset.model ?? "";
    let attribute = $el.dataset.attribute ?? "";
    
    return `<input class="form-check-input" type="checkbox" role="switch" id="${this._getId($el)}" x-model="${model}" ${attribute}/>`;
  }

  static _select($el) {
    let model = $el.dataset.model ?? "";
    let isRequired = $el.hasAttribute("required");
    let required = isRequired ? " required" : "";
    let map = $el.dataset.map ?? "";
    let mapProperty = $el.dataset.mapProperty ?? "";
    let prop = mapProperty != "" ? `.${mapProperty}` : "";
    let multiselect = $el.dataset.multiselect ?? "";
    let attribute = $el.dataset.attribute ?? "";
    let id = this._getId($el);

    return `
    <select class="form-select" id="${id}" ${required} x-model="${model}" ${attribute}
      x-init="$nextTick(() => {
        let select = $('#${id}').selectize({ placeholder: '', onChange: (value) => { ${model} = value }})[0].selectize;
        select.setValue(${model});
      });">
      <template x-for="[elKey, el] in Object.entries(${map})" :key="elKey">
        <option :value="elKey" x-text="el${prop}"></option>
      </template>
    </select>
  `;
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
    this._insertComponent($el, this._label($el) + this._checkbox($el));
  }
};
