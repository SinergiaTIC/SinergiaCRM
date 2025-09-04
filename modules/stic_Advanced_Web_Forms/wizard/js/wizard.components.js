function insertComponent($container, htmlString) {
  if (!$container || !htmlString) {
    console.error("Container or html not defined");
    return;
  }

  $container.innerHTML = "";
  $container.innerHTML = htmlString;

  // Initialize Alpine.js over new content
  Alpine.initTree($container);
}

function insertComponent_formFieldText($el) {
  let id = `${$el.id}_field`;
  let model = $el.dataset.model ?? "";
  let label = $el.dataset.label ?? "";
  let isRequired = $el.hasAttribute("required");

  var htmlString = "";
  if (label != "") {
    htmlString += `
    <label class="form-label" for="${id}" x-text="translate('${label}') + ':'"></label>`;
  }
  if (isRequired) {
    htmlString += `
    <span class="required">*</span>`;
  }
  htmlString += `
    <input class="form-control" type="text" id="${id}" x-model="${model}" />
  `;
  insertComponent($el, htmlString);
}

function insertComponent_formFieldSelectize($el) {
  let id = `${$el.id}_field`;
  let model = $el.dataset.model ?? "";
  let label = $el.dataset.label ?? "";
  let map = $el.dataset.map ?? "";
  let mapProperty = $el.dataset.mapProperty ?? "";
  let multiselect = $el.dataset.multiselect ?? "";
  let isRequired = $el.hasAttribute("data-multiselect");

  var htmlString = "";
  if (label != "") {
    htmlString += `
    <label class="form-label" for="${id}" x-text="translate('${label}') + ':'"></label>`;
  }
  if (isRequired) {
    htmlString += `
    <span class="required">*</span>`;
  }
  let required = isRequired ? " required" : "";
  let prop = mapProperty != "" ? `.${mapProperty}` : "";
  htmlString += `
    <select class="form-select" id="${id}" ${required} x-model="${model}"
      x-init="$nextTick(() => {
        let select = $('#${id}').selectize({ placeholder: '', onChange: (value) => { ${model} = value }})[0].selectize;
        select.setValue(${model});
      });">
      <option value=""></option>
      <template x-for="[elKey, el] in Object.entries(${map})" :key="elKey">
        <option :value="elKey" x-text="el${prop}"></option>
      </template>
    </select>
  `;

  insertComponent($el, htmlString);
}
