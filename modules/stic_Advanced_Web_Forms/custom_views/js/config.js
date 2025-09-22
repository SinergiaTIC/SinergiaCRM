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

  getModule() {
    return utils.getModuleInformation(this.name);
  }

  addFieldFromModuleField(moduleField) {
    // Field: { name, text, type, required, options, inViews}

    let field = this.fields.find((f) => f.name === moduleField.name);
    if (!field) {
      field = new AWF_Field({
        name: moduleField.name,
        label: moduleField.text,
        required: moduleField.required,
        required_in_form: moduleField.required,
        // value_options: moduleField.options, // IEPA!!
      })
      this.fields.push(field);
    }
    // Update field info
    field.required = moduleField.required;

    return field;
  }

  addDuplicateDetectionFromModuleField(moduleField) {
    let field = this.addFieldFromModuleField(moduleField);
    field.required_in_form = true;
    
    if (!this.duplicate_detection.fields.find(f => f === field.name)) {
      this.duplicate_detection.fields.push(field.name);
    }

    return field;
  }

  checkDataBlock(){
    this.checkDuplicateDetectionFields();
  }

  checkDuplicateDetectionFields(){
    this.duplicate_detection.fields.forEach(d => {
      if (!this.fields.find(f => f.name === d)) {
        let field = this.addFieldFromModuleField(this.getModule().fields[d]);
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
      name: "",                // Nombre de la columna
      label: "",               // Etiqueta que aparecerá con la columna
      required: false,         // Indica si el campo es obligado en el bloque de datos (no se puede eliminar)
      required_in_form: false, // Indica si el campo será obligado en el formulario
      in_form: true,           // Indica si el campo estará en el formulario
      type_in_form: 'text',    // Tipo de editor en el formulario: text, dropdown, check, date
      value_type: 'editable',  // Tipo de valor: fixed, editable, selectable, dataBlock
      value_options: [],       // Las opciones para el valor del campo
      value: "",               // El valor del campo
      value_text: ""           // El texto a mostrar para el valor del campo
    });

    // 2. Overwrite with provided data
    Object.assign(this, data);

    // 3. Map sub-objects and arrays to their classes
    this.value_options = (data.value_options || this.value_options).map(d => new AWF_ValueOption(d));
  }

  static type_in_formList(asString = false){
    return utils.getList("stic_advanced_web_forms_field_in_form_type_list", asString);
  }
  type_in_formText(){
    return AWF_Field.type_in_formList()[this.type_in_form];  
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

  suggestDataBlockName(moduleName) {
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

  static cleanName(name){
    // Convertim to lowercase and normalize accents
    name = name.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    // Replace any non valid char
    let nameClean = name.replace(/[^a-z0-9-]/g, "_");

    // Remove repeated _ or at the end
    nameClean = nameClean.replace(/_+/g, "_").replace(/_$/g, "");

    // If first char is a numbrem add a preffix
    if (nameClean.match(/^[0-9]/)) {
      nameClean = "_" + nameClean;
    }

    return nameClean;
  }

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
  addDataBlockRelationship(relationship) {
    // Relationship: {name, text, module_orig, field_orig, relationship, module_dest}

    // Find or create DataBlock for related modules
    let dataBlock_orig = this.addDataBlockModule(relationship.module_orig);
    let dataBlock_dest = this.addDataBlockModule(relationship.module_dest);

    // Set field value in orig
    let module_orig = utils.getModuleInformation(relationship.module_orig);
    let dataField_orig = dataBlock_orig.addFieldFromModuleField(module_orig.fields[relationship.field_orig]);
    dataField_orig.in_form = false;
    dataField_orig.value_type = "dataBlock";
    dataField_orig.value = dataBlock_dest.name;
    dataField_orig.value_text = dataBlock_dest.text;

    return dataBlock_dest;
  }

  getAllDataBlockRelationships() {
    // Relationship: {name, text, module_orig, field_orig, relationship, module_dest}
    // DataBlockRelationship: {name, text, module_orig, field_orig, relationship, module_dest, datablock, module, textExtended, datablock_orig, datablock_dest}
    let relationships = [];
    let relsToReview = [];
    this.data_blocks.forEach(d => {
      if (d.module) {
        Object.values(utils.getModuleInformation(d.module).relationships).forEach(r => {
          // All available relationships for every DataBlock
          r.datablock = d.id;
          r.module = r.module_orig == d.module ? r.module_dest : r.module_orig;
          r.textExtended = `${r.text} (${STIC.enabledModules[r.module].text})`;
          r.datablock_orig = "";
          r.datablock_dest = "";
          if (r.module_orig == d.module) {
            // Find field orig if is set as DataBlock
            let field = d.fields.find(f => f.value_type == "datablock");
            if (field) {
              // Fill Orig -> Dest info
              r.datablock_orig = d.id;
              r.datablock_dest = field.value;
              relsToReview.push({
                datablock: r.dataBlock,
                relationship: r.relationship,
              })
            }
          }
          relationships.push(r);
        });

        relsToReview.forEach(v => {
          let rOrig = relationships.find(r => r.datablock == v.datablock && r.relationship == v.relationship && r.datablock_orig != "");
          if (rOrig) {
            // There is Orig -> Dest info: Find Dest and Fill info in  Dest <- Orig
            let rDest = relationships.find(r => r.datablock == rOrig.datablock_dest && r.relationship == rOrig.relationship);
            if (rDest) {
              rDest.datablock_orig = rOrig.datablock_orig;
              rDest.datablock_dest = rOrig.datablock_dest;
            }
          }
        })
      }
    });
    return relationships;
  }

  getAvailableRelationships(datablockId) {
    return this.getAllDataBlockRelationships()
      .filter(r => r.datablock == datablockId && r.datablock_orig == "" && r.datablock_dest == "")
      .sort((a, b) => { return String(a.text).localeCompare(String(b.text)); });
  }

  getRelationshipModule(datablockId, relationshipName) {
    return this.getAllDataBlockRelationships().find(r => r.datablock == datablockId && r.name==relationshipName)?.module;
  }

  getAvailableDataBlocksForRelationship(datablockId, relationshipName) {
    let rel = this.getAllDataBlockRelationships().find(r => r.datablock == datablockId && r.name==relationshipName);
    if (!rel) {
      return [];
    }

    let dataBlocks = [{ id: -1, text: utils.translate("[< Nuevo Bloque de Datos >]") }];
    this.data_blocks.filter(d => d.module == rel.module).forEach(db => {
      dataBlocks.push({id: db.id, text: db.text});
    });    
  }
}
