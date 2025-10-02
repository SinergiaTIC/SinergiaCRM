/**
 * DataBlock: {
 *   id, name, text, editable_text, module, required,
 *   fields: [{ name, label, required, required_in_form, type, type_in_form, subtype_in_form, 
 *               show_in_form, value_type, value, value_text }],
 *    duplicate_detection: {fields: [<field_name>], on_duplicate},
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
      order: 0,                 // Orden del Bloque de datos
      module: "",               // Nombre del módulo
      required: false,          // Indica si es obligado (interno, no se puede eliminar)
      fields: [],               // Campos del Bloque de Datos
      duplicate_detection: {},  // Definición de detección de duplicados
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.fields = (data.fields || this.fields).map(d => new AWF_Field(d));
    this.duplicate_detection = new AWF_DuplicateDetection(data.duplicate_detection || {});
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
    return utils.translate('LBL_MODULE_RELATED');
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
   * FieldInformation: { name, text, type, required, options, inViews }
   */
  addFieldFromModuleField(moduleField) {
    // FieldInformation: { name, text, type, required, options, inViews }

    let field = this.fields.find((f) => f.name === moduleField.name);
    if (!field) {
      field = new AWF_Field();
      field.updateWithFieldInformation(moduleField);
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
    
    if (!this.duplicate_detection.fields.find(f => f === field.name)) {
      this.duplicate_detection.fields.push(field.name);
    }

    return field;
  }

  addField(field) {
    let order = 0;
    if (field.type_field == 'hidden') {
      order = -1;
    } else if (this.fields.length > 0) {
      order = this.fields.reduce((max, db) => { return Math.max(max, db.order); }, 0);
      order++;
    }
    field.order = order;
    this.fields.push(field);
    this.fields.sort((a, b) => a.order - b.order);

    return field;
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
    this.duplicate_detection.fields.forEach(d => {
      if (!this.fields.find(f => f.name === d)) {
        let field = this.addFieldFromModuleField(this.getModuleInformation().fields[d]);
        field.required_in_form = true;
      }
    });
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
      name: "",                // Nombre del campo
      label: "",               // Etiqueta que aparecerá con el campo
      order: 0,                // Orden del campo en el bloque de datos
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
      value: "",               // El valor del campo
      value_text: ""           // El texto a mostrar para el valor del campo
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.value_options = (data.value_options || this.value_options).map(d => new AWF_ValueOption(d));
  }

  /**
   * Updates current Field with FieldInformation
   * @param {object} fieldInfo FieldInformation
   * FieldInformation: { name, text, type, required, options, inViews }
   */
  updateWithFieldInformation(fieldInfo, typeField) {
    // FieldInformation: { name, text, type, required, options, inViews }
    if (!fieldInfo){
      return;
    }
    typeField = typeField || this.type_field;

    this.name = fieldInfo.name;
    this.label = utils.toFieldLabelText(fieldInfo.text);
    this.type_field = typeField;
    this.required = fieldInfo.required;
    this.required_in_form = typeField == 'form' && fieldInfo.required;
    this.type = fieldInfo.type;

    this.value = "";
    this.value_text = "";

    this.type_in_form = this.getAvailableTypesInForm()[0]?.id;
    this.value_type = this.getAvailableValueTypes()[0]?.id;
    if (this.value_type!='selectable') {
      this.value_options = [];
    }
    if (!this.isFieldInForm()) {
      this.label = '';
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
    if ((this.name??"").trim()=='')  return false;

    if (this.isFieldInForm()) {
      if (this.label.trim() == '') return false;
      // if (this.order < 0) return false;
      if (this.type_in_form == '') return false;
      if (this.value_type == 'fixed' || this.value_type == 'dataBlock') return false;
      if (this.value_type == 'selectable' && this.value_options.length == 0) return false;
      return true;
    } else {
      // if (this.order >= 0) return false;
      if (this.value_type == 'editable' || this.value_type == 'selectable') return false;
      if (this.value == '') return false;
      return true;
    }
  }

  getAvailableValueTypes() {
    if (this.type_field == 'hidden') {
      if (this.type == 'relate') {
        return AWF_Field.value_typeList().filter(t => t.id == 'fixed' || t.id == 'dataBlock');
      }
      return AWF_Field.value_typeList().filter(t => t.id == 'fixed');
    }

    // Form or unlinked
    if (this.type == 'relate' || this.type == 'enum' || this.type == 'multienum') {
      return AWF_Field.value_typeList().filter(t => t.id == 'selectable');
    }

    return AWF_Field.value_typeList().filter(t => t.id == 'editable');
  }

  getAvailableTypesInForm() {
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
    debugger;
    if (!this.isFieldInForm()) {
      return [];
    }
    if (this.type_in_form = "") {
      return [];
    }

    let base_subtypes = AWF_Field.subtype_in_formList().filter(s => s.id.startsWith(this.type_in_form));

    if (base_subtypes.length <= 1) {
      return base_subtypes;
    }
    if (this.type == "phone") {
      return base_subtypes.filter(s => s.id == "text" || s.id == "text_tel");
    }
    if (this.type == "email") {
      return base_subtypes.filter(s => s.id == "text" || s.id == "text_email");
    }
    if (this.type == "password" || this.type == "encrypt") {
      return base_subtypes.filter(s => s.id == "text" || s.id == "text_password");
    }

    if (this.type == "date") {
      return base_subtypes.filter(s => s.id == "date");
    }
    if (this.type == "time") {
      return base_subtypes.filter(s => s.id == "date_time");
    }
    if (this.type == "datetime" || this.type == "datetimecombo") {
      return base_subtypes.filter(s => s.id == "date" || s.id == "date_datetime");
    }

    if (this.type == "enum" || this.type == "radioenum" || this.type == "relate") {
      return base_subtypes.filter(s => s.id == "select" || s.id == "select_radio");
    }
    if (this.type == "bool" || this.type == "check") {
      return base_subtypes.filter(s => s.id == "select" || s.id == "select_radio" || s.id == "select_check");
    }
    if (this.type == "multienum") {
      return base_subtypes.filter(s => s.id == "select" || s.id == "select_multiple" || s.id == "select_radio");
    }

    return base_subtypes;
  }

  static type_fieldList(asString = false) {
    return utils.getList("stic_advanced_web_forms_field_type_list", asString);
  }
  type_fieldText(){
    return AWF_Field.type_fieldList()[this.type_field];  
  }

  static type_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_type_list", asString);
  }
  type_in_formText(){
    return AWF_Field.type_in_formList()[this.type_in_form];  
  }
  static subtype_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_subtype_list", asString);
  }
  subtype_in_formText(){
    return AWF_Field.subtype_in_formList()[this.subtype_in_form];  
  }

  static value_typeList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_value_type_list", asString);
  }
  value_typeText(){
    return AWF_Field.value_typeList()[this.value_type];  
  }
}

/**
 * ValueOption: { 
 *    value, text
 *  }
 */
class AWF_ValueOption{
  constructor(data = {}) {
    // 1. Set default values
    Object.assign(this, {
      value: "",      // Valor de la opción
      text: "",       // Texto de la opción
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
  on_duplicateText(){
    return AWF_DuplicateDetection.on_duplicateList()[this.on_duplicate];  
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
      name: "",              // Nombre del flujo
      // TODO: Definir campos
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
    // Check exists Detached DataBlock
    const detachedDataBlockExists = this.data_blocks.some((b) => b.name === "_Detached");
    if (!detachedDataBlockExists) {
      this.data_blocks.push(new AWF_DataBlock({
        name: "_Detached",
        text: utils.translate("LBL_DATABLOCK_DETACHED"),
        module: "",
        editable_text: false,
        required: true,
      }));
    }
  }
  _ensureDefaultFlows() {
    // Check exists Main Flow
    // Check exists OnError Flow
  }
  _ensureDefaultLayout() {}

  /**
   * Gets a suggested text for a new DataBlock for a module
   * @param {string} moduleName The module
   * @returns {string} The suggested text for a new DataBlock
   */
  suggestDataBlockText(moduleName) {
    let module = utils.getModuleInformation(moduleName);
    if (module == null) {
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

    let order = 0;
    if (this.data_blocks.length > 0) {
      order = this.data_blocks.reduce((max, db) => { return Math.max(max, db.order); }, 0);
      order++;
    }

    dataBlock = new AWF_DataBlock({
      name: name,
      text: text,
      order: order,
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
    this.data_blocks.sort((a, b) => a.order - b.order);

    return dataBlock;
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
    dataField_orig.in_form = false;
    dataField_orig.value_type = "dataBlock";
    dataField_orig.value = dataBlock_dest.id;
    dataField_orig.value_text = dataBlock_dest.text;

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
          // All available relationships for every DataBlock
          r.datablock = d.id;
          r.module = r.module_orig == d.module ? r.module_dest : r.module_orig;
          r.textExtended = `${r.text} (${STIC.enabledModules[r.module].text})`;
          r.datablock_orig = "";
          r.datablock_dest = "";
          if (r.module_orig == d.module) {
            // Find field orig if is set as DataBlock
            let field = d.fields.find(f => f.name == r.field_orig && f.value_type == "dataBlock");
            if (field) {
              // Fill Orig -> Dest info
              r.datablock_orig = d.id;
              r.datablock_dest = field.value;
              relsToReview.push({
                datablock: r.datablock,
                relationship: r.relationship,
              })
            }
          }
          allRelationships[d.id].push(r);
        });
      }
    });

    // Fill Orig->Dest in Dest 
    relsToReview.forEach(v => {
      let rOrig = allRelationships[v.datablock].find(r => r.relationship == v.relationship && r.datablock_orig != "");
      if (rOrig) {
        // There is Orig -> Dest info: Find Dest and Fill info in  Dest <- Orig
        let rDest = allRelationships[rOrig.datablock_dest].find(r => r.relationship == rOrig.relationship);
        if (rDest) {
          rDest.datablock_orig = rOrig.datablock_orig;
          rDest.datablock_dest = rOrig.datablock_dest;
        }
      }
    });

    // Sort relationships
    Object.keys(allRelationships).forEach(k => {
      allRelationships[k].sort((a, b) => { return String(a.text).localeCompare(String(b.text)); });
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
    dataBlocks.push({ id: -1, text: utils.translate("[< Nuevo Bloque de Datos >]") });

    return dataBlocks;
  }
}
