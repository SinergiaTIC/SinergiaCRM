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
    return utils.translate('LBL_NO_MODULE_RELATED');
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
      value_text: ''           // El texto a mostrar para el valor del campo
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
    return this.type_in_form == "select" && this.subtype_in_form != "select_checkbox";
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
}

class AWF_Action {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      id: utils.newId("awfa"),  // Id de la acción
      name: "",                 // Nombre interno de la acción
      text: "",                 // Texto a mostrar para la acción
      description: "",          // Descripción de la acción 
      data_block_id: '',        // Id del Bloque de datos al que pertenece
      requisite_actions: [],    // Array con los identificadores de las acciones previas a la actual
      parameters: [],           // Los parámetros de la acción
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.parameters = (data.parameters || this.parameters).map(a => new AWF_ActionParameter(a));
  }
}

class AWF_ActionParameter {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      name: '',                // Nombre del parámetro
      text: '',                // Texto del parámetro
      value: '',               // Valor del parámetro
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
  }
}

/**
 * Layout: {
 *   type, name, ...
 * }
 */
class AWF_Layout {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      name: "",              // Nombre del layout
      // TODO: Definir campos
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
  }  
}

class AWF_Configuration {
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      data_blocks: [],  // Los Bloques de Datos
      flows: [],        // Los Flujos de Acciones
      layout: [],       // Los Layouts
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.data_blocks = (data.data_blocks || this.data_blocks).map(d => new AWF_DataBlock(d));
    this.flows = (data.flows || this.flows).map(d => new AWF_Flow(d));
    this.layout = (data.layout || this.layout).map(d => new AWF_Layout(d));

    // 4. Ensure default objects
    this._ensureDefaultDataBlocks();
    this._ensureDefaultFlows();
    this._ensureDefaultLayout();
  }
  static fromJSON(jsonString){
    return new AWF_Configuration(JSON.parse(jsonString));
  }
  toJSONString() {
    return JSON.stringify(this);
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
    const mainFlowExists = this.flows.some(f => f.id == '0');
    if (!mainFlowExists) {
      this.flows.push(new AWF_Flow({
        id: 0,
        name: "main",
        label: "LBL_FLOW_MAIN",
      }));
    }

    // Check exists OnError Flow
    const onErrorFlowExists = this.flows.some(f => f.id == '-1');
    if (!onErrorFlowExists) {
      this.flows.push(new AWF_Flow({
        id: -1,
        name: "onError",
        label: "LBL_FLOW_ONERROR",
      }));
    }
    
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

  /**
   * Gets a new string cleaned to be used as internal name of any element
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
   * Gets a new DataBlock for specified module
   * @param {string} moduleName Module
   * @param {boolean} force 
   * @param {string} text 
   * @returns {AWF_DataBlock}
   */
  addDataBlockModule(moduleName, force = false, text = "") {
    let module = utils.getModuleInformation(moduleName);

    // Find DataBlock for module
    let dataBlock = null;
    if (!force) {
      dataBlock = this.data_blocks.find((d) => d.module == moduleName);
      if (dataBlock) {
        return dataBlock;
      }
    }

    // Create DataBlock for module
    if (text =="") {
      text = module.textSingular;
    }
    let name = AWF_Configuration.cleanName(text);
    let index = 0;
    while(this.data_blocks.some((b) => b.text === text || b.name === name)) {
      index++;
      text = `${module.textSingular} ${index}`;
      name = AWF_Configuration.cleanName(text);
    }

    dataBlock = new AWF_DataBlock({
      name: name,
      text: text,
      module: moduleName,
    });

    // Set initial fields
    for (const [key, value] of Object.entries(module.fields)) {
      if (value.required) {
        dataBlock.addFieldFromModuleField(value);
        dataBlock.addDuplicateDetectionFromModuleField(value);
      }
    }

    this.data_blocks.push(dataBlock);

    return dataBlock;
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
    dataBlocks.push({ id: -1, text: utils.translate('LBL_NEW_DATABLOCK') });

    return dataBlocks;
  }
}
