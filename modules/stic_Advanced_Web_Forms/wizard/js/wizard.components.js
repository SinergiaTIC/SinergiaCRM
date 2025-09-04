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

function insertComponent_formFieldText($el, id, modelStr, label, isRequired) {
  var htmlString = `
    <label class="form-label" for="${id}" x-text="translate('${label}') + ':'"></label>`;
  if (isRequired) {
    htmlString += `
    <span class="required">*</span>`;
  }
  htmlString += `
    <input class="form-control" type="text" id="${id}" x-model="${modelStr}" />
  `;
  insertComponent($el, htmlString);
}

/**
 * Inserts a Selectize combo box
 * @param {Element} $el Element where will insert the component
 * @param {string} id The id of the component
 * @param {string} model The model to map data
 * @param {string} label The name of the label
 * @param {string} mapList The name of the object with values to show as options
 * @param {string} propertyToOptions The property of the object to show values (empty if no property)
 * @param {bool} isRequired Set the field as required
 * @param {bool} multiSelect Set as multiselection
 */
function insertComponent_formFieldSelectize(
  $el,
  id,
  model,
  label,
  mapList,
  propertyToOptions,
  isRequired,
  multiSelect
) {
  var htmlString = `
    <label class="form-label" for="${id}" x-text="translate('${label}') + ':'"></label>`;
  if (isRequired) {
    htmlString += `
    <span class="required">*</span>`;
  }
  let required = isRequired ? " required" : "";
  let prop = propertyToOptions != "" ? `.${propertyToOptions}` : "";
  htmlString += `
    <select class="form-select" id="${id}" ${required} x-model="${model}"
      x-init="$nextTick(() => {
        let select = $('#${id}').selectize({ placeholder: '', onChange: (value) => { ${model} = value }})[0].selectize;
        select.setValue(${model});
      });">
      <option value=""></option>
      <template x-for="[elKey, el] in Object.entries(${mapList})" :key="elKey">
        <option :value="elKey" x-text="el${prop}"></option>
      </template>
    </select>
  `;

  insertComponent($el, htmlString);
}
