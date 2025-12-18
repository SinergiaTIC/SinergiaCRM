/**
 * DataBlock: {
 *   id, name, text, editable_text, module, required,
 *   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form, 
 *               show_in_form, value_type, value, value_text }],
 *    duplicate_detections: [{fields: [<field_name>], on_duplicate}],
 *  }
 */
class AWF_DataBlock {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      id: utils.newId("awfdb"), // Id del Bloque de datos
      name: "",                 // Nombre interno (identificador en UI) del Bloque de Datos
      text: "",                 // Texto a mostrar para el Bloque de Datos
      editable_text: true,      // Indica si el texto se puede modificar
      module: "",               // Nombre del módulo
      required: false,          // Indica si es obligado (interno, no se puede eliminar)
      fields: [],               // Campos del Bloque de Datos
      duplicate_detections: [], // Definición de detección de duplicados
      save_action_id: "",       // Id de la acción de guardado del bloque de datos
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.fields = (data.fields || this.fields).map(d => new AWF_Field(d));
    this.duplicate_detections = (data.duplicate_detections || this.duplicate_detections).map(d => new AWF_DuplicateDetection(d));

    if (this.duplicate_detections.length == 0) {
      this.duplicate_detections.push(new AWF_DuplicateDetection());
    }
  }

  isValid() {
    if ((this.name??"").trim()=='') return false;
    if ((this.text??"").trim()=='') return false;
    return this.fields.every(f => f.isValid());
  }

  /**
   * Gets the module information of the current DataBlock
   * @returns {object} ModuleInformation 
   * ModuleInformation: [name, text, textSingular, inStudio, icon, fields:[FieldInformation], relationships:[RelationshipInformation]]
   *   FieldInformation: { name, text, type, required, options, inViews }
   *   RelationshipInformation: { name, text, module_orig, field_orig, relationship, module_dest }
   */
  getModuleInformation() {
    return utils.getModuleInformation(this.module);
  }

  /**
   * Gets the text to show in the description of this DataBlock
   * @returns {string}
   */
  getTextDescription() {
    if (this.module) {
      return `${utils.translateForFieldLabel('LBL_MODULE')} ${this.getModuleInformation().text}`;
    }
    return utils.translate('LBL_RELATIONSHIP_NO_MODULE_RELATED');
  }

  getModuleText() {
    if (this.module) {
      return this.getModuleInformation().text;
    }
    return '';
  }

  /**
   * Gets all fields available to be setted in this DataBlock
   * @returns {array} FieldInformation
   * FieldInformation: { name, text, type, required, options, inViews }
   */
  getAvailableFieldsInformation() {
    let allFieldsInfo = this.getModuleInformation()?.fields;
    if (!allFieldsInfo) {
      return [];
    }
    // FieldInformation: { name, text, type, required, options, inViews }

    let availableFields = Object.values(allFieldsInfo).filter(fi => !this.fields.some(f => f.name == fi.name) );
    availableFields.push({name:'', text:'', type:'', required:false, options:[], inViews:true });
    return availableFields;
  }

  /**
   * Add a Field to the DataBlock, from a FieldInformation (the summarized field definition in vardefs)
   * @param {object} moduleField: FieldInformation
   * @returns {AWF_Field} the field added to DataBlock
   * FieldInformation: { name, text, type, required, default, options, inViews }
   */
  addFieldFromModuleField(moduleField) {
    // FieldInformation: { name, text, type, required, default, options, inViews }

    let field = this.fields.find((f) => f.name === moduleField.name);
    if (!field) {
      field = new AWF_Field();
      let type_field = 'form';
      if (moduleField.required && moduleField.default != null && moduleField.default != '') {
        type_field = 'hidden';
      }
      field.updateWithFieldInformation(moduleField, type_field);
      field.setValueOptions(utils.getFieldOptions(moduleField));

      field = this.addField(field);
    }
    // Update field info
    field.required = moduleField.required;

    return field;
  }

  /**
   * Add a Field with DuplicateDetection to the DataBlock, from a FieldInformation (the summarized field definition in vardefs)
   * @param {object} moduleField FieldInformation
   * @returns {AWF_Field} the field added to DataBlock
   * FieldInformation: { name, text, type, required, options, inViews }
   */
  addDuplicateDetectionFromModuleField(moduleField) {
    let field = this.addFieldFromModuleField(moduleField);
    field.required_in_form = true;
    
    if (!this.duplicate_detections[0].fields.find(f => f === field.name)) {
      this.duplicate_detections[0].fields.push(field.name);
    }

    return field;
  }

  addField(field) {
    let newField = new AWF_Field(field);
    if (newField.type_field == 'hidden') {
      this.fields.unshift(newField);
    }
    else {
      this.fields.push(newField);
    }

    return newField;
  }

  deleteField(fieldName) {
    const index = this.fields.findIndex(f => f.name == fieldName);
    if (index !== -1) {
      this.fields.splice(index, 1);
    }
  }

  updateField(oldName, newField) {
    const index = this.fields.findIndex(f => f.name === oldName);
    
    if (index == -1) {
      return this.addField(newField);
    } else {
      this.fields[index] = newField;
      return newField;
    }
  }

  /**
   * Checks current DataBlock
   */
  checkDataBlock(){
    this.checkDuplicateDetectionFields();
  }

  /**
   * Checks current DataBlock with DuplicateDetection directives
   */
  checkDuplicateDetectionFields(){
    this.duplicate_detections[0].fields.forEach(d => {
      if (!this.fields.find(f => f.name === d)) {
        let field = this.addFieldFromModuleField(this.getModuleInformation().fields[d]);
        field.required_in_form = true;
      }
    });
  }

  /**
   * Gets a suggested name for a new Field for this DataBlock
   * @param {*} fieldName The fieldName 
   * @returns {string} The suggested text for a new DataBlock
   */
  suggestFieldName(fieldName) {
    let index = 0;
    let name = fieldName;
    while(this.fields.some((f) => f.name === fieldName)) {
      index++;
      name = `${fieldName}_${index}`;
    }
    return name;
  }

  fixFieldName(field) {
    let index = 0;
    let name = field.name;
    while(this.fields.filter((f) => f.name === field.name).length==2) {
      index++;
      name = `${fieldName}_${index}`;
    }
    field.name = name;
    return name;
  }

  /**
   * Gets a suggested text for a new DataBlock for a module
   * @param {string} moduleName The module
   * @returns {string} The suggested text for a new DataBlock
   */
  suggestDataBlockText(moduleName) {
    let module = utils.getModuleInformation(moduleName);
    if (!module || !module.textSingular) {
      return "";
    }

    let text = module.textSingular;
    let index = 0;
    while(this.data_blocks.some((b) => b.text === text || b.name === name)) {
      index++;
      text = `${module.textSingular} ${index}`;
    }
    return text;
  }
}

/**
 * Field: { 
 *   name, label, required, required_in_form, type, in_form, 
 *   type_in_form, value_type, value_options: [{value, text}], value, value_text 
 * }
 */
class AWF_Field {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      name: '',                // Nombre del campo
      text_original: '',       // Texto original del campo
      label: '',               // Etiqueta que aparecerá con el campo
      description: '',         // Descripción del campo
      required: false,         // Indica si el campo es obligado en el bloque de datos (no se puede eliminar)
      type_field: 'form',      // Tipo de campo: unlinked, form, hidden
      required_in_form: false, // Indica si el campo será obligado en el formulario
      in_form: true,           // Indica si el campo estará en el formulario
      type_in_form: 'text',    // Tipo de editor en el formulario: text, textarea, number, date, select
      subtype_in_form: 'text', // SubTipo de editor en el formulario: text, text_email, text_tel, text_password, textarea, number, data, date_time, date_datetime...
      type: '',                // Tipo de datos del campo
      value_type: 'editable',  // Tipo de valor: editable, selectable, fixed, dataBlock
      value_options: [],       // Las opciones para el valor del campo
      placeholder: '',         // El placeholder o texto de fondo en el editor
      value: '',               // El valor del campo
      value_text: '',          // El texto a mostrar para el valor del campo
      related_module: ''       // Módulo relacionado (si aplica)
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.value_options = (data.value_options || this.value_options).map(d => new AWF_ValueOption(d));

    if (!data.in_form) {
      this.in_form = this.type_field != 'hidden';
    }
  }

  /**
   * Updates current Field with FieldInformation
   * @param {object} fieldInfo FieldInformation
   * FieldInformation: { name, text, type, required, default, options, inViews }
   */
  updateWithFieldInformation(fieldInfo, typeField) {
    // FieldInformation: { name, text, type, required, default, options, inViews }
    if (!fieldInfo){
      return;
    }
    typeField = typeField || this.type_field;

    this.name = fieldInfo.name;
    this.text_original = fieldInfo.text;
    this.label = utils.toFieldLabelText(fieldInfo.text);
    this.type_field = typeField;
    this.required = fieldInfo.required;
    this.required_in_form = typeField == 'form' && fieldInfo.required;
    this.type = fieldInfo.type;

    this.value = "";
    this.value_text = "";

    this.type_in_form = this.getAvailableTypesInForm()[0]?.id;
    this.subtype_in_form = this.getAvailableSubtypesInForm()[0]?.id;
    this.value_type = this.getAvailableValueTypes()[0]?.id;
    if (this.value_type != 'selectable' && this.value_type != 'fixed') {
      // Reset value_options
      this.value_options = [];
    }
    if (fieldInfo.module) {
        this.related_module = fieldInfo.module;
    } else {
        this.related_module = '';
    }
    if (!this.isFieldInForm()) {
      this.label = '';
      this.required_in_form = false;
      this.type_in_form = '';
      this.subtype_in_form = '';
    }

    this.in_form = this.type_field != 'hidden';
    if (this.value_type == 'fixed') {
      if (fieldInfo.default != null && fieldInfo.default != '') {
        this.value = fieldInfo.default;
        this.value_text = fieldInfo.default;
      }
    }

    return this;
  }

  mustInForm() {
    return this.isFieldInForm();
  }

  isFieldInForm() {
    return this.type_field != 'hidden';
  }

  isValid() {
    if ((this.name??"").trim()=='') return false;

    if (this.isFieldInForm()) {
      if (this.label.trim() == '') return false;
      if (this.type_in_form == '') return false;
      if (this.value_type == 'fixed' || this.value_type == 'dataBlock') return false;
      if (this.value_type == 'selectable' && this.value_options.length == 0) return false;
      if (this.value_type == 'selectable' && this.value_options.every(o => !o.is_visible)) return false;
      return true;
    } else {
      if (this.value_type == 'editable' || this.value_type == 'selectable') return false;
      if (this.value == '') return false;
      return true;
    }
  }

  getAvailableValueTypes() {
    if (this.name == "") {
      return [];
    }
    if (this.type_field == 'hidden') {
      // if (this.type == 'relate') {
      //   return AWF_Field.value_typeList().filter(t => t.id == 'fixed' || t.id == 'dataBlock');
      // }
      return AWF_Field.value_typeList().filter(t => t.id == 'fixed');
    }

    // Form or unlinked
    if (this.type == 'relate' || this.type == 'enum' || this.type == 'multienum') {
      return AWF_Field.value_typeList().filter(t => t.id == 'selectable');
    }

    return AWF_Field.value_typeList().filter(t => t.id == 'editable');
  }

  getAvailableTypesInForm() {
    if (this.name == "") {
      return [];
    }
    if (!this.isFieldInForm()) {
      return [];
    }
    if (this.type_field == 'unlinked') {
      return AWF_Field.type_in_formList();
    }
    
    // text, textarea, number, date, select
    if (this.type == "enum" || this.type == "radioenum" || this.type == "multienum" || this.type == "bool" || this.type == "checkbox") {
      return AWF_Field.type_in_formList().filter(t => t.id == "select");
    }
    if (this.type == "relate") {
      return AWF_Field.type_in_formList().filter(t => t.id == "select");
    }
    if (this.type == "date" || this.type == "time" || this.type == "datetime" || this.type == "datetimecombo") {
      return AWF_Field.type_in_formList().filter(t => t.id == "date");
    }
    if (this.type == "int" || this.type == "float" || this.type == "double" || this.type == "decimal") {
      return AWF_Field.type_in_formList().filter(t => t.id == "number");
    }
    if (this.type == "json") {
      return AWF_Field.type_in_formList().filter(t => t.id == "textarea");
    }
    if (this.type == "name" || this.type == "phone" || this.type == "email" || this.type == "url" || 
        this.type == "password" || this.type == "encrypt") {
      return AWF_Field.type_in_formList().filter(t => t.id == "text");
    }
    return AWF_Field.type_in_formList().filter(t => t.id == "text" || t.id == "textarea" || t.id == "number");
  }

  getTypeInActions() {
    switch (this.type) {
      case "textarea":
      case "json":
        return "textarea";
      case "int":
        return "integer";
      case "float":
      case "double":
      case "decimal":
        return "float";
      case "bool":
        return "boolean";
      case "date":
        return "date";
      case "time":
        return "time";
      case "datetime":
      case "datetimecombo":
        return "datetime-local";
      case "email":
        return "email";
      case "phone":
        return "tel";
      case "url":
        return "url";
      case "id":
      case "link":
      case "relate":
        return "relate";
      default:
        return "text";
    }
  }

  getAvailableSubtypesInForm() {
    if (this.name == "") {
      return [];
    }
    if (!this.isFieldInForm()) {
      return [];
    }
    if (this.type_in_form == "") {
      return [];
    }

    let base_subtypes = AWF_Field.subtype_in_formList().filter(s => s.id == this.type_in_form || s.id.startsWith(this.type_in_form + '_'));

    if (base_subtypes.length <= 1) {
      return base_subtypes;
    }

    let list = [];
    if (this.type == "phone") {
      list.push(base_subtypes.find(s => s.id == "text_tel"));
      list.push(base_subtypes.find(s => s.id == "text"));
      return list;
    }
    if (this.type == "email") {
      list.push(base_subtypes.find(s => s.id == "text_email"));
      list.push(base_subtypes.find(s => s.id == "text"));
      return list;
    }
    if (this.type == "password" || this.type == "encrypt") {
      list.push(base_subtypes.find(s => s.id == "text_password"));
      list.push(base_subtypes.find(s => s.id == "text"));
      return list;
    }

    if (this.type == "date") {
      list.push(base_subtypes.find(s => s.id == "date"));
      return list;
    }
    if (this.type == "time") {
      list.push(base_subtypes.find(s => s.id == "date_time"));
      return list;
    }
    if (this.type == "datetime" || this.type == "datetimecombo") {
      list.push(base_subtypes.find(s => s.id == "date_datetime"));
      list.push(base_subtypes.find(s => s.id == "date"));
      return list;
    }

    if (this.type == "enum" || this.type == "radioenum" || this.type == "relate") {
      list.push(base_subtypes.find(s => s.id == "select"));
      list.push(base_subtypes.find(s => s.id == "select_radio"));
      return list
    }
    if (this.type == "bool" || this.type == "check") {
      list.push(base_subtypes.find(s => s.id == "select_checkbox"));
      list.push(base_subtypes.find(s => s.id == "select_switch"));
      list.push(base_subtypes.find(s => s.id == "select"));
      list.push(base_subtypes.find(s => s.id == "select_radio"));
      return list
    }
    if (this.type == "multienum") {
      list.push(base_subtypes.find(s => s.id == "select_multiple"));
      list.push(base_subtypes.find(s => s.id == "select_checkbox_list"));
      list.push(base_subtypes.find(s => s.id == "select"));
      list.push(base_subtypes.find(s => s.id == "select_radio"));
      return list
    }

    return base_subtypes;
  }

  acceptPlaceholder() {
    return this.type_in_form == "text" || this.type_in_form == "textarea" || this.type_in_form == "number";
  }

  acceptValueOptions() {
    return this.type_in_form == "select" && this.subtype_in_form != "select_checkbox" && this.subtype_in_form != "select_switch";
  }

  setValueOptions(originalOptions) {
    if (this.type_field == 'unlinked' && this.acceptValueOptions()) {
      if ((this.value_options?.length ?? 0) == 0) {
        this.value_options = [new AWF_ValueOption()];
      }
      return this.value_options;
    }
    if (this.type_field == 'form' && this.type == 'relate' && (originalOptions?.length ?? 0) == 0 ) {
      return this.value_options;
    }

    this.value_options = [];
    if (originalOptions) {
      originalOptions.forEach(o => {
        this.value_options.push(new AWF_ValueOption({
          value: o.id,
          is_visible: true,
          text_original: o.text,
          text: o.text,
        }));
      });
    }
    return this.value_options;
  }
  
  isOptionValueModified() {
    return this.value_options.some(o => !o.is_visible || o.text_original !== o.text);
  }

  static type_fieldList(asString = false) {
    return utils.getList("stic_advanced_web_forms_field_type_list", asString);
  }
  get type_fieldText(){
    return AWF_Field.type_fieldList().find(i => i.id == this.type_field)?.text;  
  }

  static type_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_type_list", asString);
  }
  get type_in_formText(){
    return AWF_Field.type_in_formList().find(i => i.id == this.type_in_form)?.text;  
  }

  static subtype_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_subtype_list", asString);
  }
  get subtype_in_formText(){
    return AWF_Field.subtype_in_formList().find(i => i.id == this.subtype_in_form)?.text;  
  }

  static value_typeList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_value_type_list", asString);
  }
  get value_typeText(){
    return AWF_Field.value_typeList().find(i => i.id == this.value_type)?.text;  
  }
}

/**
 * ValueOption: { 
 *    value, is_visible, text_original, text
 *  }
 */
class AWF_ValueOption{
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      value: '',          // Valor de la opción
      is_visible: true,   // Indica si se mostrará
      text_original: '',  // Texto original de la opción
      text: '',           // Texto de la opción
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
  }
}

/**
 * DuplicateDetection: {
 *   fields: [<field_name>], on_duplicate
 * }
 */
class AWF_DuplicateDetection {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      fields: [],              // Array con el nombre de los campos para la detección de duplicados
      on_duplicate: "enrich"   // Acción a realizar con los duplicados: update, enrich, skip, error
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
  }

  static on_duplicateList(asString = false){
    return utils.getList("stic_advanced_web_forms_datablocks_duplicate_action_list", asString);
  }
  get on_duplicateText(){
    return AWF_DuplicateDetection.on_duplicateList().find(i => i.id == this.on_duplicate)?.text;  
  }
}

/**
 * Flow: {
 *   name,
 *   actions: [{ order, action_name, params: [{name, source, value}],
 * } 
 */
class AWF_Flow {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      id: utils.newId("awffa"), // Id del Flujo de acciones
      name: "",                 // Nombre del Flujo de acciones
      label: "",                // La etiqueta a traducir para el nombre
      text: "",                 // El texto a mostrar 
      actions: [],              // Las acciones del Flujo
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.actions = (data.actions || this.actions).map(f => new AWF_Action(f));
  }

  getText() {
    return this.label != "" ? utils.translate(this.label) : this.text;
  }

  hasTerminalAction() {
    return this.actions.some(a => a.is_terminal);
  }
}

class AWF_Action {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      id: utils.newId("awfa"),  // Id de la acción
      name: "",                 // Nombre interno de la acción
      title: "",                // Título de la acción (nombre genérico)
      text: "",                 // Texto a mostrar para la acción
      description: "",          // Descripción de la acción 
      requisite_actions: [],    // Array con los identificadores de las acciones previas a la actual
      category: 'data',         // Categoría de la acción
      parameters: [],           // Los parámetros de la acción
      is_user_selectable: true, // Indica si la acción es seleccionable por el usuario
      is_terminal: false,       // Indica si la acción es terminal
      order: 0,                 // El orden de ejecución de la acción
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.parameters = (data.parameters || this.parameters).map(a => new AWF_ActionParameter(a));
  }

  get is_fixed_order() {
    return this.order != 0;
  }

  isValid() {
    return this.parameters.every(param => !param.required || (param.value !== null && param.value !== ''));
  }

  static category_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_action_category_list", asString);
  }
  get category_in_formText(){
    return AWF_Action.category_in_formList().find(i => i.id == this.category)?.text;  
  }
}

class AWF_ActionParameter {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      name: '',                // Nombre del parámetro
      text: '',                // Texto del parámetro
      type: '',                // Tipo del parámetro: value, field, dataBlock, crmRecord, optionSelector
      required: false,         // Indica si el parámetro es obligatorio
      value: '',               // Valor del parámetro
      value_text: '',          // Texto a mostrar para el valor del parámetro
      selectedOption: '',      // Opción seleccionada (si aplica)
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
  }
}

class AWF_Layout {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      theme: new AWF_Theme(),      // Variables visuales del formulario

      header_html: '',             // Html con la cabecera del formulario
      footer_html: '',             // Html con el pie del formulario

      // Texto del botón de enviar
      submit_button_text: utils.translate('LBL_THEME_SUBMIT_BUTTON_TEXT_VALUE'),

      // Texto en caso de formulario cerrado
      closed_form_title: utils.translate('LBL_THEME_CLOSED_FORM_TITLE_VALUE'),
      closed_form_text: utils.translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE'),

      custom_css: '',              // CSS personalizado
      custom_js: '',               // JS personalizado

      structure: [],               // Array de Secciones
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects
    this.theme = new AWF_Theme(data.theme ?? {});
    this.structure = (data.structure || this.structure).map(s => new AWF_LayoutSection(s));

    // Decode: If it comes from the DB (JSON), it will come in Base64. If it is new, it will be empty.
    this.header_html = utils.fromBase64(this.header_html);
    this.footer_html = utils.fromBase64(this.footer_html);
    if (this.custom_css) this.custom_css = utils.fromBase64(this.custom_css);
    if (this.custom_js) this.custom_js = utils.fromBase64(this.custom_js);
  }

  /**
   * Sincroniza la estructura visual con los bloques de datos reales.
   *  - Borra referencias a bloques eliminados 
   *  - Elimina bloques duplicados (solo mantiene la primera aparición)
   *  - Elimina/Ignora los bloques que no tienen campos visibles
   *  - Añade los nuevos bloques de datos al final.
   * @param {AWF_DataBlock[]} dataBlocks Lista actual de bloques de datos
   */
  syncWithDataBlocks(dataBlocks) {
    const placedBlockIds = new Set(); // Conjunto de bloques colocados
    const cleanStructure = [];

    // Limpieza de los bloques de la estructura visual
    this.structure.forEach(section => {
      section.elements = section.elements.filter(el => {
        if (el.type != 'datablock') return true; // No es un bloque, lo mantenemos

        // Comprobamos que exista el bloque
        const block = dataBlocks.find(b => b.id === el.ref_id);
        
        if (!block) return false; // El bloque ya no existe
        if (placedBlockIds.has(el.ref_id)) return false; // Es un duplicado
        
        // Comprobamos visibilidad de campos
        // (si no tiene campos visibles, no tiene que estar en la maquetación)
        if (!block.fields.some(f => f.type_field !== 'hidden')) return false; // El bloque solo tiene campos ocultos
          
        // Marcamos el bloque como colocado
        placedBlockIds.add(el.ref_id); 
        return true;
      });
      cleanStructure.push(section);
    });

    this.structure = cleanStructure;

    // Añadimos los bloques que falten
    const orphanBlocks = dataBlocks.filter(b => {
      if (placedBlockIds.has(b.id)) return false; // El bloque está colocado
      if (!b.fields.some(f => f.type_field !== 'hidden')) return false; // Solo tiene campos ocultos

      return true;
    });

    if (orphanBlocks.length > 0) {
      // Creamos una sección para cada bloque
      orphanBlocks.forEach(block => {
        this._addSectionWithBlock(block);
      });
    }
  }

  _addSectionWithBlock(block) {
    const section = new AWF_LayoutSection({
      title: block.text, 
      containerType: 'panel'
    });
    
    const element = new AWF_LayoutElement({
      type: 'datablock',
      ref_id: block.id
    });

    section.elements.push(element);
    this.structure.push(section);
  }

  addSection(title) {
    this.structure.push(new AWF_LayoutSection({ title: title }));
  }
}

class AWF_Theme {
  constructor(data = {}) {
    Object.assign(this, {
      primary_color: STIC.mainThemeColor ?? '#0d6efd',  // Color corporativo por defecto 
      page_bg_color: '#f8f9fa',  // Fondo de la página (gris muy suave)
      form_bg_color: '#ffffff',  // Fondo del formulario (blanco)

      border_radius_container: 10, // Redondeo para los contenedores en px (10px). Range: [0..40]
      border_radius_controls: 4,   // Redondeo para los contenedores en px (4px). Range: [0..20]

      text_color: '#212529',     // Color del texto (gris oscuro)
      border_color: '#dee2e6',   // Color del borde (gris claro)
      border_width: 1,             // Ancho del borde en px

      floating_labels: true,       // Inica si se usaran etiquetas flotantes en los controles (true)
      
      // Tipografía
      font_family: "system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",

      font_size: 16,               // Tamaño de letra en px. 
      form_width: '800px',         // Ancho máximo del formulario. String para permitir %, px, rem
      shadow_intensity: 'normal',  // Intensidad de la sombra: 'none', 'light', 'normal', 'heavy'
      
      // Estructura (Grid)
      sections_per_row: 1,         // Secciones por fila (1, 2 o 3)
      fields_per_row: 2,           // Campos por fila (1, 2, 3 o 4)

      field_spacing: '1rem',       // Espaciado entre campos
      equal_height_sections: true, // Indica si las secciones tendrán el mismo alto
      label_weight_bold: false,    // Negrita en las etiquetas
      submit_full_width: false,    // Ancho total del botón de enviar
      input_style: 'standard',     // Estilo de los campos: 'standard', 'flat', 'filled'
    });

    // 2. Sobreescriure amb dades
    Object.assign(this, data);
  }

  static shadow_intensity_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_shadow_intensity_list", asString);
  }
  get shadow_intensity_in_formText(){
    return AWF_Theme.shadow_intensity_in_formList().find(i => i.id == this.shadow_intensity)?.text;  
  }

  static input_style_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_input_style_list", asString);
  }
  get input_style_in_formText(){
    return AWF_Theme.input_style_in_formList().find(i => i.id == this.input_style)?.text;  
  }

  static form_width_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_form_width_list", asString);
  }
  get form_width_in_formText(){
    return AWF_Theme.form_width_in_formList().find(i => i.id == this.form_width)?.text;  
  }

  static field_spacing_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_spacing_list", asString);
  }
  get field_spacing_in_formText(){
    return AWF_Theme.field_spacing_in_formList().find(i => i.id == this.field_spacing)?.text;  
  }
}

/**
 * Define un contenedor visual
 */
class AWF_LayoutSection {
  constructor(data = {}) {
    Object.assign(this, {
      id: utils.newId('sect'), // Id de la sección
      title: "",               // Título a mostrar
      showTitle: true,         // Indica si se mostrará el título
      isCollapsible: false,    // Indica si la sección se puede colapsar
      isCollapsed: false,      // Indica si la sección aparecerá inicialmente colapsada
      
      containerType: 'card',  // Tipo de contenedor visual: 'panel' (simple), 'card' (con borde), 'tabs', 'accordion'
      elements: [],
    });

    Object.assign(this, data);

    this.elements = (data.elements || this.elements).map(e => new AWF_LayoutElement(e));
  }

  static containerType_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_sections_type_list", asString);
  }
  get containerType_in_formText(){
    return AWF_LayoutSection.containerType_in_formList().find(i => i.id == this.containerType)?.text;  
  }
}

/**
 * Elemento dentro de un contenedor visual
 */
class AWF_LayoutElement {
  constructor(data = {}) {
    Object.assign(this, {
      id: utils.newId('el'),  // Id del elemento
      
      type: 'datablock',      // Tipo de elemento: 'datablock' (posibles ampliaciones: 'line', etc)
      ref_id: '',             // ID de referencia (el ID del AWF_DataBlock)
    });

    Object.assign(this, data);
  }
}

class AWF_Configuration {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      data_blocks: [],          // Los Bloques de Datos
      flows: [],                // Los Flujos de Acciones
      layout: new AWF_Layout(), // El Layout
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.data_blocks = (data.data_blocks || this.data_blocks).map(d => new AWF_DataBlock(d));
    this.flows = (data.flows || this.flows).map(d => new AWF_Flow(d));
    this.layout = new AWF_Layout(data.layout || {})

    // 4. Ensure default objects
    this._ensureDefaultDataBlocks();
    this._ensureDefaultFlows();
    this._ensureDefaultLayout();
  }
  static fromJSON(jsonString){
    return new AWF_Configuration(JSON.parse(jsonString));
  }
  toJSONString() {
    const clone = JSON.parse(JSON.stringify(this));
    if (clone.layout) {
        clone.layout.header_html = utils.toBase64(clone.layout.header_html);
        clone.layout.footer_html = utils.toBase64(clone.layout.footer_html);
        if (clone.layout.custom_css) clone.layout.custom_css = utils.toBase64(clone.layout.custom_css);
        if (clone.layout.custom_js) clone.layout.custom_js = utils.toBase64(clone.layout.custom_js);
    }
    return JSON.stringify(clone);
  }

  _ensureDefaultDataBlocks() {
    // TODO: Add Detached DataBlock
    // // Check exists Detached DataBlock
    // const detachedDataBlockExists = this.data_blocks.some((b) => b.name === "_Detached");
    // if (!detachedDataBlockExists) {
    //   this.data_blocks.push(new AWF_DataBlock({
    //     name: "_Detached",
    //     text: utils.translate("LBL_DATABLOCK_DETACHED"),
    //     module: "",
    //     editable_text: false,
    //     required: true,
    //   }));
    // }
  }
  _ensureDefaultFlows() {
    // Check exists Main Flow
    if (!this.flows.some(f => f.id == '0')) {
      this.flows.push(new AWF_Flow({ id: 0, name: "main", label: "LBL_FLOW_MAIN" }));
    }

    // Check exists OnError Flow
    if (!this.flows.some(f => f.id == '-1')) {
      this.flows.push(new AWF_Flow({ id: -1, name: "onError", label: "LBL_FLOW_ONERROR" }));
    }
    
    // Check exists Receipt Flow
    if (!this.flows.some(f => f.id == '1')) {
      this.flows.push(new AWF_Flow({ id: 1, name: "receipt", label: "LBL_FLOW_RECEIPT" }));
    }

    // Sort flows: Receipt, Main, Error
    this.flows.sort((a, b) => {
        const order = { '1': 0, '0': 1, '-1': 2 }; // Receipt -> Main -> Error
        return (order[a.id] ?? 99) - (order[b.id] ?? 99);
    });
  }
  _ensureDefaultLayout() {}

 /**
   * Gets a suggested text for a new DataBlock for a module
   * @param {string} moduleName The module
   * @returns {string} The suggested text for a new DataBlock
   */
  suggestDataBlockText(moduleName) {
    let module = utils.getModuleInformation(moduleName);
    if (!module || !module.textSingular) {
      return "";
    }

    let text = module.textSingular;
    let index = 0;
    while(this.data_blocks.some((b) => b.text === text || b.name === name)) {
      index++;
      text = `${module.textSingular} ${index}`;
    }
    return text;
  }

  prepareProcessingMode(mode) { 
    if (mode == 'async') {
      // Remove terminal actions from main flow
      const mainFlow = this.flows.find(f => f.id == '0');
      if (mainFlow) {
        mainFlow.actions = mainFlow.actions.filter(a => !a.is_terminal);
      }  
    }
  }

  /**
   * Gets a new string cleaned to be used as internal name of fields
   * @param {string} name 
   * @returns {string}
   */
  static cleanName(name){
    // Convert to lowercase and normalize accents
    name = name.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    // Replace any non valid char
    let nameClean = name.replace(/[^a-z0-9-]/g, "_");

    // Remove repeated _ or at the end
    nameClean = nameClean.replace(/_+/g, "_").replace(/_$/g, "");

    // If first char is a number add a preffix
    if (nameClean.match(/^[0-9]/)) {
      nameClean = "_" + nameClean;
    }

    return nameClean;
  }

  /**
   * Gets a safe name for a DataBlock using PascalCase of the moduleName.
   * Ex: "stic_Advanced_Web_Forms" -> "SticAdvancedWebForms"
   * Ex: "Contacts" -> "Contacts"
   */
  static getSafeNameFromModule(moduleName) {
    if (!moduleName) 
      return "Module";

    return moduleName
      .split(/[^a-zA-Z0-9]/)                                     // Split by "_" or non alphanumeric chars
      .filter(part => part.length > 0)                           // Remove empty parts
      .map(part => part.charAt(0).toUpperCase() + part.slice(1)) // Capitalize every part
      .join('');                                                 // Join without separators
  }

  updateDataBlockText(dataBlock, newText) {
    const oldName = dataBlock.name;
    let text = newText;
    let baseText = text.trim();
    let index = 0;
    while(this.data_blocks.some((b) => b.id != dataBlock.id && (b.text === text))) {
      index++;
      text = `${baseText} ${index}`;
    }
    dataBlock.text = text;

    // Update all actions and parameters pointing to this DataBlock
    this.flows.forEach(flow => {
      flow.actions.forEach(action => {
        action.parameters.forEach(param => {
          // Update value_text to this dataBlock
          if (param.value == dataBlock.id) {
            param.value_text = text;
          }

          //Update references to fields ("OldBlockName.Field" -> "NewBlockName.Field")
          if (typeof param.value === 'string') {
            const prefixOld = `${oldName}.`;
            const prefixNew = `${name}.`;
            
            const prefixDetachedOld = `_detached.${oldName}.`;
            const prefixDetachedNew = `_detached.${name}.`;

            if (param.value.startsWith(prefixOld)) {
              param.value = param.value.replace(prefixOld, prefixNew);
            } else if (param.value.startsWith(prefixDetachedOld)) {
              param.value = param.value.replace(prefixDetachedOld, prefixDetachedNew);
            }
          }

        });
      });
    });
  }

  /**
   * Gets a new DataBlock for specified module
   * @param {string} moduleName Module
   * @param {boolean} force 
   * @param {string} text 
   * @returns {AWF_DataBlock}
   */
  addDataBlockModule(moduleName, force = false, text = "") {
    let module = utils.getModuleInformation(moduleName);
    if (text == "") text = module.textSingular;

    // Find DataBlock for module
    let dataBlock = null;
    if (!force) {
      dataBlock = this.data_blocks.find((d) => d.module == moduleName);
      if (dataBlock) {
        return dataBlock;
      }
    }

    // Create DataBlock for module

    // Set unique text
    let baseText = text;
    let index = 0;
    while(this.data_blocks.some((b) => b.text === text)) {
      index++;
      text = `${baseText} ${index}`;
    }

    // Set unique name with Module name
    let baseName = AWF_Configuration.getSafeNameFromModule(moduleName);
    index = 0;
    let name = `${baseName}${index}`; // Ex: SticAdvancedWebForms0
    while(this.data_blocks.some((b) => b.name === name)) {
      index++;
      name = `${baseName}${index}`;
    }

    dataBlock = new AWF_DataBlock({
      name: name,
      text: text,
      module: moduleName,
    });

    // Set initial fields 
    let hasRequiredRelate = false;
    for (const field of Object.values(module.fields)) {
      if (field.required && field.type === 'relate') {
        hasRequiredRelate = true;
      }
      if (field.required) {
        dataBlock.addFieldFromModuleField(field);
      }
    }

    // Add Duplicate detection (only if there are not relate required)
    if (!hasRequiredRelate) {
      for (const field of Object.values(module.fields)) {
        if (field.required) {
          dataBlock.addDuplicateDetectionFromModuleField(field);
        }
      }
    }

    this.data_blocks.push(dataBlock);

    this._addSaveActionForDataBlock(dataBlock);
    this.syncLayoutWithDataBlocks();

    return dataBlock;
  }

  syncLayoutWithDataBlocks() {
    this.layout.syncWithDataBlocks(this.data_blocks);
  }

  _addSaveActionForDataBlock(dataBlock) {
    const saveActionDef = utils.getServerActions().find(a => a.name == 'SaveRecordAction');
    const params = {
      'data_block_id': {value: dataBlock.id, valueText: dataBlock.text, selectedOption: ''} 
    };
    const newAction = this.addAction(saveActionDef, params);
    newAction.text = `${newAction.title}: ${dataBlock.text}`;
    dataBlock.save_action_id = newAction.id;
  }
  
  /**
   * Add new action to flow
   *
   * @param {object} actionDef The Action definition (from ActionDefinitionDTO)
   * @param {object} params A map of parameters, ex: { 'param_name': { value: 'value', selectedOption: 'opt' } }
   * @param {string} flowId Id of the flow where action will be added (ex: '0' for main flow)
   * @returns {AWF_Action} The new action
   */
  addAction(actionDef, params = {}, flowId = '0') {
    const flow = this.flows.find(f => f.id == flowId);
    if (!flow) {
      console.error(`Flow with ID ${flowId} not found.`);
      return null;
    }

    // Si es una acción terminal, asignamos orden a 999
    const defaultOrder = actionDef.isTerminal ? 999 : (actionDef.order ?? 0);

    const newAction = new AWF_Action({
      name: actionDef.name,
      title: actionDef.title, 
      text: actionDef.title,
      description: actionDef.description,
      category: actionDef.category,
      is_user_selectable: actionDef.isUserSelectable,
      is_terminal: actionDef.isTerminal,
      order: defaultOrder,
    });

    const requisiteActions = new Set(); 
    (actionDef.parameters || []).forEach(paramDef => {      
      const paramConfig = params[paramDef.name];       
      const paramValue = paramConfig?.value ?? paramDef.defaultValue;
      const newParam = new AWF_ActionParameter({
        name: paramDef.name,
        text: paramDef.text,
        type: paramDef.type,
        required: paramDef.required,
        value: paramValue,
        value_text: paramConfig?.valueText ?? paramValue,
        selectedOption: paramConfig?.selectedOption ?? '',
      });

      newAction.parameters.push(newParam);

      // Requisites: If param is Datablock or resolvedType is DataBlock
      const paramIsDataBlock = (paramDef.type === 'dataBlock') || 
                               (paramDef.selectorOptions || []).find(o => o.name == newParam.selectedOption)?.resolvedType === 'dataBlock';

      if (paramIsDataBlock && newParam.value) {
        const requiredBlock = this.data_blocks.find(b => b.id == newParam.value);

        if (requiredBlock && requiredBlock.save_action_id) {
          requisiteActions.add(requiredBlock.save_action_id);
        }
      }
    });

    // Assign requisites
    newAction.requisite_actions = Array.from(requisiteActions);

    // Add Action to flow: Insertion based on order
    let insertIndex = flow.actions.length;
    for (let i = 0; i < flow.actions.length; i++) {
      if ((flow.actions[i].order ?? 0) > newAction.order) {
        insertIndex = i;
        break;
      }
    }
    flow.actions.splice(insertIndex, 0, newAction);

    return newAction;
  }

  /**
   * Deletes a DataBlock, removing all field references to the DataBlock
   * @param {AWF_DataBlock} dataBlock 
   */
  deleteDataBlock(dataBlock) {
    // Reset all fields pointing to this DataBlock
    this.data_blocks.forEach(d => {
      d.fields.filter(f => f.value_type == 'dataBlock' && f.value == dataBlock.id).forEach(f => {
        f.value = '';
        f.value_text = '';
      });
    });

    // Remove DataBlock
    this.data_blocks = this.data_blocks.filter(d => d.id != dataBlock.id);

    // Remove SaveAction and its dependants
    this._deleteSaveActionForDataBlock(dataBlock);

    // Sync Layout with DataBlocks
    this.syncLayoutWithDataBlocks();
  }

  _deleteSaveActionForDataBlock(dataBlock) {
    const targetActionId = dataBlock.save_action_id;
    
    if (!targetActionId) {
      return; // No save action to delete
    }

    // 1. Identify all actions to delete
    const idsToDelete = new Set([targetActionId]);
    let newDependentsFound = true;

    // To find multilevel dependences (A -> B -> C)
    while (newDependentsFound) {
      newDependentsFound = false;

      this.flows.forEach(flow => {
        flow.actions.forEach(action => {
          if (!idsToDelete.has(action.id)) {
            
            // Check if any requisite must be deleted
            const dependsOnDeleted = (action.requisite_actions || []).some(reqId => idsToDelete.has(reqId));
            if (dependsOnDeleted) {
              idsToDelete.add(action.id);
              newDependentsFound = true;
            }
          }
        });
      });
    }

    // 2. Remove actions in every flow
    this.flows.forEach(flow => {
      flow.actions = flow.actions.filter(action => !idsToDelete.has(action.id));
    });

    // 3. Clean save action in dataBlock
    dataBlock.save_action_id = "";
  }

  deleteDataBlockField(dataBlock, field) {
    dataBlock.deleteField(field.name);

    if (field.type == 'relate' && field.value_type == 'dataBlock') {
      // Remove Relationship Action
      const relateAction = this.flows.flatMap(f => f.actions).find(a => {
        if (a.name == 'RelateRecordsAction') {
          return a.parameters.find(p => p.name == 'data_block_id' && p.value == dataBlock.id) &&
                 a.parameters.find(p => p.name == 'target_data_block' && p.value == field.value) &&
                 a.parameters.find(p => p.name == 'field_to_update' && p.value == `${dataBlock.name}.${field.name}`);
        }
        return false;
      });
      if (relateAction) {
        this.flows.forEach(flow => {
          flow.actions = flow.actions.filter(a => a.id != relateAction.id);
        });
      }
    }

    // Invalidate paramter actions depending on this field
    let fieldRef = `${dataBlock.name}.${field.name}`;
    if (field.type_field === 'unlinked') { 
      fieldRef = `_detached.${fieldRef}`;
    }
    this.flows.forEach(flow => {
      flow.actions.forEach(action => {
        action.parameters.forEach(param => {
          if (param.value === fieldRef) {
            param.value = "";
            param.value_text = "";
          }
        });
      });
    });
  }

  /**
   * 
   * @param {string} datablockId 
   * @param {string} relationshipName 
   * @param {string} relatedDatablockId 
   * @param {string} newDataBlockText 
   * @returns 
   */
  addDataBlockRelationship(datablockId, relationshipName, relatedDatablockId, newDataBlockText) {
    // DataBlockRelationship: { name, text, module_orig, field_orig, relationship, module_dest, datablock, module, textExtended, datablock_orig, datablock_dest }

    // Find Relationship
    let rel = this.getAllDataBlockRelationships()[datablockId].find(r => r.name == relationshipName);
    if (!rel) {
      return null;
    }

    // Find Datablock
    let dataBlock = this.data_blocks.find(d => d.id == datablockId);
    if (!dataBlock) {
      return null;
    }

    // Find related Datablock
    let relDatablock = null;
    if (relatedDatablockId != -1) {
      // Use existant related Datablock
      relDatablock = this.data_blocks.find(d => d.id == relatedDatablockId);
    } else {
      // Create new related Datablock
      relDatablock = this.addDataBlockModule(rel.module, true, newDataBlockText);
    }
    if (!relDatablock) {
      return null;
    }

    // Set field value in origin
    let dataBlock_orig = dataBlock;
    let dataBlock_dest = relDatablock;
    if (dataBlock_orig.module != rel.module_orig) {
      dataBlock_orig = relDatablock;
      dataBlock_dest = dataBlock;
    }
    let module_orig = utils.getModuleInformation(rel.module_orig);

    /**
     * Field: { 
     *    name, label, required, required_in_form, type, in_form, 
     *    type_in_form, value_type, value_options: [{value, text}], value, value_text 
     *  }
     */
    let dataField_orig = dataBlock_orig.addFieldFromModuleField(module_orig.fields[rel.field_orig]);
    dataField_orig.type_field = 'hidden';
    dataField_orig.in_form = false;
    dataField_orig.value_type = "dataBlock";
    dataField_orig.value = dataBlock_dest.id;

    // Add Action to save Relationship
    const relateActionDef = utils.getServerActions().find(a => a.name == 'RelateRecordsAction');
    const params = {
      'data_block_id': {value: dataBlock_orig.id, valueText: dataBlock_orig.text, selectedOption: ''},
      'target_data_block': {value: dataBlock_dest.id, valueText: dataBlock_dest.text, selectedOption: ''},
      'relationship_name': {value: rel.name, valueText: rel.text, selectedOption: ''}
    };
    const newAction = this.addAction(relateActionDef, params);
    newAction.text = `${newAction.title}: ${dataBlock_orig.text} ⟶ ${dataBlock_dest.text}`;

    return dataBlock;
  }

  /**
   * Get all defined Relationships in all modules represented in data_blocks array
   * @returns {object} map with all DataBlock relationships, indexed by DataBlock id
   * DataBlockRelationship: { name, text, module_orig, field_orig, relationship, module_dest, datablock, module, textExtended, datablock_orig, datablock_dest }
   */
  getAllDataBlockRelationships() {
    // Relationship: {name, text, module_orig, field_orig, relationship, module_dest}
    // DataBlockRelationship: {name, text, module_orig, field_orig, relationship, module_dest, datablock, module, textExtended, datablock_orig, datablock_dest}
    let allRelationships = {};
    let relsToReview = [];

    this.data_blocks.forEach(d => {
      if (d.module) {
        allRelationships[d.id] = [];
        Object.values(utils.getModuleInformation(d.module).relationships).forEach(r => {
          let rel = {...r}; // Copy relationship object

          // Fill all available relationships for every DataBlock
          rel.datablock = d.id;
          rel.module = rel.module_orig == d.module ? rel.module_dest : rel.module_orig;
          rel.textExtended = `${rel.text} (${STIC.enabledModules[rel.module].text})`;
          rel.datablock_orig = "";
          rel.datablock_dest = "";

          allRelationships[d.id].push(rel);
        });
        // Find and fill defined relationships in datablock fields
        d.fields.filter(f => f.value_type == "dataBlock").forEach(f => {
          let rel = allRelationships[d.id].find(r => r.module_orig == d.module && r.field_orig == f.name);
          if (rel) {
            // Fill Orig -> Dest info
            rel.datablock_orig = d.id;
            rel.datablock_dest = f.value;

            // Mark to review to fill Dest <- Orig info
            relsToReview.push(rel);
          }
        });
      }
    });

    // Review and fill Dest <- Orig info
    relsToReview.forEach(r => {
      let rel = allRelationships[r.datablock_dest].find(v => v.name == r.name);
      if (rel) {
        // Fill Dest <- Orig info
        rel.datablock_orig = r.datablock_orig;
        rel.datablock_dest = r.datablock_dest;
      }
    });

    return allRelationships;
  }

  /**
   * Gets the module related with relationship in current DataBlock
   * @param {string} datablockId 
   * @param {string} relationshipName 
   * @returns {string} The module name
   */
  getRelationshipModule(datablockId, relationshipName) {
    return this.getAllDataBlockRelationships()[datablockId].find(r => r.name==relationshipName)?.module;
  }

  /**
   * Get all available DataBlocks that can be related with current DataBlock in the relationship
   * @param {string} datablockId 
   * @param {string} relationshipName 
   * @returns {array}
   * AvailableDataBlock: { id, text }
   */
  getAvailableDataBlocksForRelationship(datablockId, relationshipName) {
    let module = this.getRelationshipModule(datablockId, relationshipName);
    if (!module) {
      return [];
    }

    let dataBlocks = [];
    this.data_blocks.filter(d => d.module == module).forEach(db => {
      dataBlocks.push({id: db.id, text: db.text});
    });
    dataBlocks.push({ id: -1, text: utils.translate('LBL_DATABLOCK_NEW') });

    return dataBlocks;
  }
}
