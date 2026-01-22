function wizardForm() {
  return {
    navigation: {
      step: 1,
      totalSteps: 5,
    },

    bean: STIC.record || {},
    formConfig: {},
    init() {
      this.$watch('bean.processing_mode', (newMode, oldMode) => {
        this.formConfig.prepareProcessingMode(newMode);
      });

      // Quill Component Editor
      Alpine.data('quillEditor', (initialContent, onUpdate) => ({
        editor: null,
        content: initialContent,
        
        init() {
          if (typeof Quill === 'undefined') {
            console.error("Quill JS not loaded");
            return;
          }

          // Initialize Quill to use inline styles
          const Align = Quill.import('attributors/style/align');
          const Background = Quill.import('attributors/style/background');
          const Color = Quill.import('attributors/style/color');
          const Font = Quill.import('attributors/style/font');
          const Size = Quill.import('attributors/style/size');
          
          Quill.register(Align, true);
          Quill.register(Background, true);
          Quill.register(Color, true);
          Quill.register(Font, true);
          Quill.register(Size, true);

          const toolbarOptions = [
            [{'header': [1, 2, 3, false] }], 
                
            ['bold', 'italic', 'underline', 'strike'],
            [{'list': 'ordered'}, { 'list': 'bullet' }],
            [{'align': [] }],
            [{'color': [] }, { 'background': [] }],
            ['link', 'image', 'clean'],
          ];

          this.editor = new Quill(this.$refs.editor, {
            theme: 'snow',
            modules: {
              toolbar: {
                container: toolbarOptions,
              }
            }
          });

          // Load initial content
          if (this.content) {
            try {
                this.editor.clipboard.dangerouslyPasteHTML(0, this.content);
            } catch (e) {
                console.warn("Error loading HTML in Quill:", e);
                // Fallback: Plain text
                this.editor.setText(this.content);
            }
          }

          // Save to Model
          this.editor.on('text-change', () => {
            let html = this.editor.root.innerHTML;
            if (html === '<p><br></p>') html = '';
            this.content = html;
            
            if (typeof onUpdate === 'function') {
              onUpdate(html);
            }
          });
        }
      }));
    },

    async initWizard() {
      // Set Context accessible
      window.alpineComponent = this;

      // Set config object
      let jsonString = "{}";
      if (this.bean?.configuration) {
        jsonString = utils.decodeHTMLString(this.bean.configuration);
      }
      try {
        this.formConfig = AWF_Configuration.fromJSON(jsonString);
      } catch (e) {
        console.error("Error parsing JSON:", e);
        console.log("Bad JSON String:", jsonString);
        // Fallback a config vacía si falla
        this.formConfig = new AWF_Configuration();
      }

      // Load current Step
      WizardNavigation.loadStep();
    },
  };
}
class WizardNavigation {
  static cacheSteps = [];
  static cacheDebug = null;

  static async loadStep() {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (step <= 0 || step > totalSteps) {
      return;
    }

    // Step title
    $("#wizard-section-title").text(utils.translate(`LBL_WIZARD_TITLE_STEP${step}`) + ` (${step}/${totalSteps})`);
    // Step description
    $("#wizard-section-desc").text(utils.translate(`LBL_WIZARD_DESC_STEP${step}`));

    // Step content
    if (!(step in WizardNavigation.cacheSteps)) {
      WizardNavigation.cacheSteps[step] = await (
        await fetch(`modules/stic_Advanced_Web_Forms/custom_views/wizard/steps/step${step}.html`)
      ).text();
    }
    let $el = document.getElementById("wizard-step-container");
    $el.innerHTML = WizardNavigation.cacheSteps[step];

    // Debug options
    if (!(WizardNavigation.cacheDebug)) {
      WizardNavigation.cacheDebug = await (
        await fetch(`modules/stic_Advanced_Web_Forms/custom_views/wizard/steps/debug.html`)
      ).text();
    }
    let $elDebug = document.getElementById("debug-container");
    $elDebug.innerHTML = WizardNavigation.cacheDebug;

    // Initialize Alpine.js over new content
    Alpine.initTree($el);
  }

  static enabled(action) {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (action == "prev") {
      return step > 1;
    }
    if (action == "next") {
      return step < totalSteps;
    }
  }

  static prev() {
    if (WizardNavigation.enabled("prev")) {
      window.alpineComponent.navigation.step--;
      WizardNavigation.loadStep();
    }
  }

  static next() {
    if (WizardNavigation.enabled("next")) {
      let allOk = true;
      document.querySelectorAll("#wizard-step-container form.needs-validation").forEach(function (f) {
        allOk &= f.reportValidity();
      });
      if (allOk) {
        window.alpineComponent.navigation.step++;
        WizardNavigation.autoSave();
        WizardNavigation.loadStep();
      }
    }
  }

  static async autoSave() {
    const response = await fetch("index.php?module=stic_Advanced_Web_Forms&action=saveDraft", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        bean: window.alpineComponent.bean,
        config: window.alpineComponent.formConfig.toJSONString(),
        step: window.alpineComponent.navigation.step,
      }),
    });

    // Check for http errors
    if (response.ok) {
      // Read response body as text (SuiteCRM add html code to every action response)
      const responseText = await response.text();
      const lines = responseText.split("\n").filter((line) => line.trim() !== "");

      // Get the last line of the array: The json with the data from server
      const lastLine = lines[lines.length - 1];
      const data = JSON.parse(lastLine);

      if (data.success) {
        // Update local data
        window.alpineComponent.bean.id = data.id;
      }
    }
  }

  static finish() {
    window.alpineComponent.formConfig.syncLayoutWithDataBlocks();
    if (!this.isReadOnly) {
      fetch("index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          bean: window.alpineComponent.bean,
          config: window.alpineComponent.formConfig.toJSONString(),
          step: window.alpineComponent.navigation.step,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = data.redirectUrl; 
        } else {
          console.error('Error saving form:', data.message);
        }
      });
    }
  }
}

// Access configuration examples:
// window.alpineComponent.formConfig
// window.alpineComponent.bean.base_module

function set_wizard_assigned_user(popup_reply_data) {
  window.alpineComponent.bean.assigned_user_id = popup_reply_data.name_to_value_array.assigned_user_id;
  window.alpineComponent.bean.assigned_user_name = popup_reply_data.name_to_value_array.assigned_user_name;
}


function handle_open_popup(popup_reply_data) {
  if (popup_reply_data.name_to_value_array) {
    Object.entries(popup_reply_data.name_to_value_array).forEach(el => {
      $(`#${el[0]}`).val(el[1]);
      $(`#${el[0]}`)[0].dispatchEvent(new Event('input', { bubbles: true }));
    })
  }
  if (popup_reply_data.selection_list) {
    const idField = popup_reply_data.passthru_data.id;
    $(`#${idField}`).val(Object.values(popup_reply_data.selection_list).join("|"));
    $(`#${idField}`)[0].dispatchEvent(new Event('input', { bubbles: true }));
  }
}

class WizardStep2 {
  static mainStep2xData() {
    return {
      init() {
        // Store for the Datablock relationship management
        if (!Alpine.store('dataBlockRelationships')) {
          Alpine.store('dataBlockRelationships', {
            get formConfig() { return window.alpineComponent.formConfig; },

            _dataBlockRelationships: null,
            get dataBlockRelationships() { 
              if (this._dataBlockRelationships == null) {
                this._dataBlockRelationships = this.formConfig.getAllDataBlockRelationships(); 
              }
              return this._dataBlockRelationships;
            },
            resetDataBlockRelationships() {
              this._dataBlockRelationships = null;
            },
            usedDatablockRelationships(datablockId) {
              return this.dataBlockRelationships[datablockId].filter(r => r.datablock_orig != '' && r.datablock_dest != '');
            },
            unusedDatablockRelationships(datablockId) {
              return this.dataBlockRelationships[datablockId].filter(r => r.datablock_orig == '' && r.datablock_dest == '');
            },
            addDataBlockRelationship(datablockId, relName, relatedDatablockId, newDatablockText) {
              let dataBlock = this.formConfig.addDataBlockRelationship(datablockId, relName, relatedDatablockId, newDatablockText);
              if (dataBlock) {
                this.resetDataBlockRelationships();
              }
              return dataBlock;
            },
            getAvailableDataBlocksForRelationship(datablockId, relName) { 
              return this.formConfig.getAvailableDataBlocksForRelationship(datablockId, relName);
            },
            suggestNewDestDataBlockText(origDatablockId, relName) {
              return this.formConfig.suggestDataBlockText(this.formConfig.getRelationshipModule(origDatablockId, relName));
            },
            getRelText(datablockId, relName) {
              let rel = this.dataBlockRelationships[datablockId].find(r => r.name == relName);
              let dataBlock_orig = this.formConfig.data_blocks.find(d => d.id == rel.datablock_orig);
              let dataBlock_dest = this.formConfig.data_blocks.find(d => d.id == rel.datablock_dest);
              let str = "";
              if (dataBlock_orig && dataBlock_dest) {
                if (dataBlock_orig.id == datablockId) {
                  str = `${dataBlock_orig.text} ⟶ ${dataBlock_dest.text}`;
                } else {
                  str = `${dataBlock_dest.text} ⟵ ${dataBlock_orig.text}`;
                }
                str += ` (${rel.text})`;
              }
              return str;
            },
            
          });
        }

        // Store for the Field Editor management
        if (!Alpine.store('fieldEditor')) {
          Alpine.store('fieldEditor', {
            isOpen: false,           // Indica si está abierto el editor de campos
            isEdit: false,           // Indica si es modo edición (false: modo creación)
            field: new AWF_Field(),  // Copia de los datos del campo
            dataBlock: null,         // El bloque de datos del campo
            needDeleteOld: false,    // Indica si es necesario eliminar el campo anterior antes de guardar
            original_name: '',       // Nombre original del campo

            /**
             * Retorna si es un campo nuevo
             * @returns {boolean}
             */
            get isNewField() { return this.original_name === ''; },

            get formConfig() { return window.alpineComponent.formConfig; },

            /**
             * Retorna el título del modal
             */
            get title() {
              if (!this.field) return '';

              if (this.isNewField) {
                switch (this.field.type_field) {
                  case 'form':
                    return utils.translate('LBL_FIELD_FORM_NEW');
                  case 'unlinked':
                    return utils.translate('LBL_FIELD_UNLINKED_NEW');
                  case 'hidden':
                    return utils.translate('LBL_FIELD_HIDDEN_NEW');
                }
              } else {
                let title = "";
                switch (this.field.type_field) {
                  case 'form':
                    title += utils.translate('LBL_FIELD_FORM') + ' » ';
                    break;
                  case 'unlinked':
                    title += utils.translate('LBL_FIELD_UNLINKED') + ' » ';
                    break;
                  case 'hidden':
                    title += utils.translate('LBL_FIELD_HIDDEN') + ' » ';
                }
                title += this.field.text_original;
                return title;
              }
            },
            /**
             * Retorna el tubtítulo del modal
             */
            get subtitle() {
              return this.dataBlock?.text + ' - ' + this.dataBlock?.getTextDescription();
            },

            /**
             * Retorna el título traducido de un validador
             */
            getValidatorTitle(validatorName) {
                const def = utils.getDefinedActions().find(a => a.name === validatorName);
                return def ? def.title : validatorName;
            },

            /**
             * Retorna la descripción de la condición de una validación
             * @param {AWF_FieldValidation} validation 
             * @returns {string}
             */
            getConditionLabel(validation) {
              if (!validation.condition_field) return '';
              
              // Obtenemos la etiqueta del campo
              const fieldDef = this.formConfig.getFieldDefinitionByHtmlName(validation.condition_field);
              const label = fieldDef ? utils.fromFieldLabelText(fieldDef.label || fieldDef.text_original) : validation.condition_field;

              // Obtenemos el valor formateado
              let val = validation.condition_value;
              if (fieldDef && (fieldDef.type == 'bool' || fieldDef.type == 'checkbox' || fieldDef.subtype_in_form == 'select_checkbox')) {
                if (val == '1' || val == 'true') {
                  val = utils.translate('LBL_YES');
                } else if (val == '0' || val == 'false') {
                  val = utils.translate('LBL_NO');
                }
              }
              
              return `${label} = ${val}`;
            },

            /**
             * Retorna un array de objetos {label, value} para mostrar en la tabla de parámetros
             */
            getValidationParamsForTable(validation) {
              if (!validation || !validation.validator || !validation.params) return [];
              
              const def = utils.getDefinedActions().find(a => a.name === validation.validator);
              if (!def) return [];
              
              return Object.entries(validation.params).map(([key, value]) => {
                const paramDef = def.parameters.find(p => p.name === key);
                
                // Si és un checkbox (boolean)
                let displayValue = value;
                if (paramDef && utils.getParameterInputType(paramDef.dataType) === 'checkbox') {
                  displayValue = value ? '☑' : '☐';
                }
                
                return {
                  label: paramDef ? utils.fromFieldLabelText(paramDef.text) : key, 
                  value: displayValue
                };
              });
            },

            /**
             * Elimina una validación
             */
            deleteValidation(index) {
                if (this.field && this.field.validations) {
                    this.field.validations.splice(index, 1);
                }
            },

            /**
             * Abre el Modal para Crear un campo
             * @param {AWF_DataBlock} dataBlock El Bloque de datos
             * @param {string} type Tipo de campo: unlinked, form, hidden
             */
            openCreate(dataBlock, type) {
              this.isEdit = false;
              this._open(dataBlock, null, type);
            },

            /**
             * Abre el Modal para Editar un campo
             * @param {AWF_DataBlock} dataBlock El Bloque de datos
             * @param {AWF_Field} field El campo
             */
            openEdit(dataBlock, field) {
              this.isEdit = true;
              this._open(dataBlock, field, '');
            },

            /**
             * Abre el Modal para editar o crear un campo
             * @param {AWF_DataBlock} dataBlock El Bloque de datos
             * @param {AWF_Field} fieldData El campo
             * @param {string} type Tipo de campo: unlinked, form, hidden
             */
            _open(dataBlock, fieldData, type) {
              this.dataBlock = dataBlock;
              this.field = new AWF_Field(fieldData || {type_field: type});
              this.original_name = this.field.name;
              this.needDeleteOld = false;
              this.isOpen = true;
            },

            /**
             * Cierra el modal de edición de un campo
             */
            close() {
              this.isOpen = false;
              this.dataBlock = null;
              this.field = null;
              this.original_name = '';
              this.needDeleteOld = false;
            },

            /**
             * Guarda los cambios de la edición (o creación) de un campo
             */
            saveChanges() {
              if (this.isNewField) {
                this.formConfig.addDataBlockField(this.dataBlock, this.field);
              } else {
                if (this.needDeleteOld) {
                  this.dataBlock.deleteField(this.field.name);
                  this.formConfig.addDataBlockField(this.dataBlock, this.field);
                } else {
                  this.dataBlock.updateField(this.original_name, this.field);
                }
              }
              this.close();
            },
          });
        }

        // Store for the Field Validation Editor management
        if (!Alpine.store('fieldValidationEditor')) {
          Alpine.store('fieldValidationEditor', {
            isOpen: false,                          // Indica si está abierto el editor de validaciones
            isEdit: false,                          // Indica si es modo edición (false: modo creación)
            validation: new AWF_FieldValidation(),  // Copia de los datos de la validación
            field: null,                            // El campo al que pertenece la validación
            dataBlock: null,                        // El bloque de datos del campo

            applyCondition: false,                  // Indica si se aplica condición
            _activeConditionFieldDef: null,         // Definición del campo de condición activo

            get formConfig() { return window.alpineComponent.formConfig; },

            /**
             * Retorna el título del modal
             */
            get title() {
              if (!this.validation) return '';

              if (this.isEdit) {
                return utils.translate('LBL_FIELD_VALIDATION_EDIT');
              } else {
                return utils.translate('LBL_FIELD_VALIDATION_NEW');
              }
            },
            get fieldLabel() { return utils.fromFieldLabelText((this.field?.label ?? "") || (this.field?.text_original ?? "")); },
            get subtitle() { return `${this.dataBlock?.text} » ${this.fieldLabel}`; },

            get availableFieldsInForm() { 
              const currentFieldName = this.dataBlock?.getFieldInputName(this.field);
              return this.formConfig?.getAllFieldsInForm(currentFieldName) ?? []; 
            },
            get activeConditionFieldDef() { return this._activeConditionFieldDef; },
            
            get isBooleanCondition() {
              const def = this._activeConditionFieldDef;
              if (!def) return false;
              return def.type === 'bool' || def.type === 'checkbox' || 
                     def.subtype_in_form === 'select_checkbox' || def.subtype_in_form === 'select_switch';
            },

            get conditionInputType() {
              const def = this._activeConditionFieldDef;
              if (!def) return 'text';
              if (def.type_in_form === 'number') return 'number';
              if (def.type_in_form === 'date') {
                if (def.subtype_in_form === 'date_time') return 'time';
                if (def.subtype_in_form === 'date_datetime') return 'datetime-local';
                return 'date';
              }
              return 'text';
            },

            init() {
              Alpine.effect(() => {
                const fieldName = this.validation?.condition_field;
                this.updateActiveConditionFieldDef(fieldName);
              });
              
              Alpine.effect(() => {
                if (this.applyCondition === false && this.validation) {
                  if (this.validation.condition_field !== '' || this.validation.condition_value !== '') {
                    this.validation.condition_field = '';
                    this.validation.condition_value = '';
                    this._activeConditionFieldDef = null;
                  }
                }
              });

              Alpine.effect(() => {
                const validatorName = this.validation?.validator;
                if (validatorName && (!this.validation.message || this.validation.message === '')) {
                  const def = utils.getDefinedActions().find(a => a.name === validatorName);
                  if (def) {
                    this.validation.message = def.defaultErrorMessage ?? '';
                  }
                }
              });
            },

            updateActiveConditionFieldDef(fieldName) {
              if (!fieldName) {
                this._activeConditionFieldDef = null;
              } else {
                const newDef = this.formConfig.getFieldDefinitionByHtmlName(fieldName);
                if (this._activeConditionFieldDef !== newDef) {
                  this._activeConditionFieldDef = newDef;
                  // Reactivar el valor si es un select para que refresque las opciones
                  if (this._activeConditionFieldDef && this._activeConditionFieldDef.type_in_form === 'select') {
                    const currentValue = this.validation.condition_value;
                    if (currentValue) {
                      setTimeout(() => { 
                        if(this.validation) this.validation.condition_value = currentValue; 
                      }, 50);
                    }
                  }
                }
              }
            },

            syncConditionState() {
              if (this.validation && this.validation.condition_field) {
                this.applyCondition = true;
                this.updateActiveConditionFieldDef(this.validation.condition_field);
              } else {
                this.applyCondition = false;
                this._activeConditionFieldDef = null;
              }
            },

            /**
             * Abre el Modal para Crear una validación
             * @param {AWF_Field} field El campo al que pertenece la validación
             */
            openCreate(dataBlock, field) {
              this.isEdit = false;
              this._open(dataBlock, field, null);
            },

            /**
             * Abre el Modal para Editar una validación
             * @param {AWF_Field} field El campo al que pertenece la validación
             * @param {AWF_FieldValidation} validation La validación
             */
            openEdit(dataBlock, field, validation) {
              this.isEdit = true;
              this._open(dataBlock, field, validation);
            },

            /**
             * Abre el Modal para editar o crear una validación de campo
             * @param {AWF_Field} fiel El campo
             * @param {AWF_FieldValidation} validation La validación
             */
            _open(dataBlock, field, validation) {
              this.dataBlock = dataBlock;
              this.field = field;
              this.validation = new AWF_FieldValidation(validation || {name:`${utils.newId('validation_')}` });
              this.isOpen = true;
            },

            /**
             * Cierra el modal de edición de una validación de campo
             */
            close() {
              this.isOpen = false;
              this.field = null;
              this.validation = null;
            },

            /**
             * Guarda los cambios de la edición (o creación) de una validación de campo
             */
            saveChanges() {
              this.formConfig.addOrUpdateFieldValidation(this.field, this.validation);
              this.close();
            },
          });
        }

        // Store for the Relationship Creator management
        if (!Alpine.store('relCreator')) {
          Alpine.store('relCreator', {
            isOpen: false,           // Indica si está abierto el creador de relaciones
            dataBlock: null,         // El bloque de datos del campo
            relationships: Alpine.store('dataBlockRelationships'), // Store con las operaciones generales de Relaciones

            availableRels: [],
            selectedRelName: '',

            availableDataBlocks: [],
            relatedDataBlockId: null,

            newDataBlockText: '',
            get isNewDataBlock() { return this.relatedDataBlockId == -1; },

            get isValid() { return this.relatedDataBlockId != null && (!this.isNewDataBlock || this.newDataBlockText != ''); },

            /**
             * Retorna el título del modal
             */
            get title() {
              return utils.translate('LBL_RELATIONSHIP_NEW');
            },
            /**
             * Retorna el tubtítulo del modal
             */
            get subtitle() {
              return this.dataBlock?.text + ' - ' + this.dataBlock?.getTextDescription();
            },

            /**
             * Abre el modal de creación de relaciones
             * @param {AWF_DataBlock} dataBlock 
             */
            openCreate(dataBlock) {
              this.dataBlock = dataBlock;
              this.relationships.resetDataBlockRelationships(); // Forzar recargar relaciones
              this.availableRels = this.relationships.unusedDatablockRelationships(dataBlock.id);
              this.selectedRelName = this.availableRels[0]?.name ?? '';
              this.isOpen = true;
            },

            /**
             * Cierra el modal de creación de relaciones
             */
            close() {
              this.isOpen = false;
              this.dataBlock = null;
              this.availableRels = [];
              this.selectedRelName = '';
            },

            /**
             * Guarda los cambios de la creación de una relación
             */
            saveChanges() {
              this.relationships.addDataBlockRelationship(this.dataBlock.id, this.selectedRelName, this.relatedDataBlockId, this.newDataBlockText);
              this.close();
            },

            init() {
              let selectedRelName_old = this.selectedRelName;
              let relatedDataBlockId_old = this.relatedDataBlockId;

              // En Stores, la observación de cambios la hacemos por effect (bajo nivel, no vinculado a elemento DOM)
              Alpine.effect(() => {
                // Changes in selectedRelName
                if (this.selectedRelName != selectedRelName_old && this.dataBlock) {
                  relatedDataBlockId_old = null; // To force changes in relatedDataBlockId
                  this.availableDataBlocks = this.relationships.getAvailableDataBlocksForRelationship(this.dataBlock.id, this.selectedRelName);
                  this.relatedDataBlockId = this.availableDataBlocks[0]?.id ?? -1;
                }

                // Changes in relatedDataBlockId
                if (this.relatedDataBlockId != relatedDataBlockId_old) {
                  if (this.relatedDataBlockId == -1 && this.dataBlock) {
                    this.newDataBlockText = this.relationships.suggestNewDestDataBlockText(this.dataBlock.id, this.selectedRelName);
                  } else {
                    this.newDataBlockText = '';
                  }
                }

                // Update watched properties
                selectedRelName_old = this.selectedRelName;
                relatedDataBlockId_old = this.relatedDataBlockId;
              });
            }
          });
        };
      },
    };
  }

  static generalDatablocksxData(initial_formConfig) {
    return {
      formConfig: initial_formConfig,

      deleteDataBlock(dataBlock) {
        this.formConfig.deleteDataBlock(dataBlock);
        Alpine.store('dataBlockRelationships').resetDataBlockRelationships();
      },

      getDataBlockText(dataBlockId) {
        let dataBlock = this.formConfig.data_blocks.find(d => d.id == dataBlockId);
        if (!dataBlock) return '';
        return `${dataBlock.text} (${dataBlock.getModuleText()})`;
      }
    };
  }

  static datablockxData(initial_formConfig, dataBlock) {
    return {
      formConfig: initial_formConfig,
      dataBlock: dataBlock,

      init() {
        this.$watch('dataBlock.text', (newText, oldText) => {
          if (!this.dataBlock) return;
          if (newText == oldText) return;
          this.formConfig.updateDataBlockText(this.dataBlock, newText);
        });
      },
    };
  }

  static sortableListxData(initial_items) {
    return {
      items: initial_items,

      moveUp(index) {
        if (index <= 0) return;

        const itemToMove = this.items[index];
        this.items.splice(index, 1);
        this.items.splice(index - 1, 0, itemToMove);
      },

      moveDown(index) {
        if (index >= this.items.length - 1) return;

        const itemToMove = this.items[index];
        this.items.splice(index, 1);
        this.items.splice(index + 1, 0, itemToMove);
      },
    };
  }

  static addDataBlockModulexData(initial_formConfig) {
    return {
      formConfig: initial_formConfig,

      creatingDataBlock: false,

      newDataBlock: {module:'', text:''},
      get isValid() { 
        return this.newDataBlock.module.trim() != '' && this.newDataBlock.text.trim() != '';
      },

      handleAddDatablockModule() {
        this.formConfig.addDataBlockModule(this.newDataBlock.module, true, this.newDataBlock.text);
        this.creatingDataBlock = false;
        Alpine.store('dataBlockRelationships').resetDataBlockRelationships();
      },

      init() {
        if (this.formConfig && !this.formConfig.data_blocks.some(b => b.module!='')) {
          this.creatingDataBlock = true;
        }
      },
    };
  }
 
  static editionFieldxData(fieldStore, config) {
    return {
      formConfig: config,
      store: fieldStore,

      get dataBlock() { return this.store?.dataBlock; },
      get field() { return this.store?.field; },
      get isEdit() { return this.store?.isEdit; },

      configValueOptions: false,

      showAllFields: false,
      get availableFields() {
        if (this.isEdit) {
          return [this.dataBlock?.getModuleInformation()?.fields[this.field.name]];
        }
        return this.showAllFields ? this.dataBlock?.getAvailableFieldsInformation() : this.dataBlock?.getAvailableFieldsInformation().filter(f => f.inViews) ?? [];
      },

      get selectedFieldInfo() { 
        if (!this.field || this.field.type_field  == 'unlinked') return null;
        return this.availableFields.find(f => f.name == this.field?.name); 
      },

      optionValuesRelated: '',
      get optionValuesListName() {
        let listName = utils.getFieldOptions(this.selectedFieldInfo, true);
        if (listName != '') {
          let lastDot = listName.lastIndexOf('.');
          if (lastDot != -1) {
            return listName.substring(lastDot + 1);
          }
        };
        if (this.optionValuesRelated != ''){
          listName = `${utils.getModuleInformation(this.relatedModule)?.text} (${this.optionValuesRelated.split('|').length})`;
        }
        if (listName == '' && this.field && this.field.value_options.length > 0) {
          listName = this.field.value_options.filter(v => v.is_visible).map(v => v.text).join(', ');
        }
        return listName;
      },

      get relatedModule() {
        if (this.field && this.field.type == 'relate') {
          return this.formConfig.getRelationshipModule(this.dataBlock.id, this.selectedFieldInfo?.options);
        }
        return '';
      },

      get availableValueTypes() { return this.field?.getAvailableValueTypes() ?? [{id:'',text:''}]; },
      get availableTypesInForm() { return this.field?.getAvailableTypesInForm() ?? [{id:'',text:''}]; },
      get availableSubtypesInForm() { return this.field?.getAvailableSubtypesInForm() ?? [{id:'',text:''}]; },

      get isDate() {return this.field?.type == 'date' || this.field?.type == 'datetime' || this.field?.type == 'datetimecombo'; },

      get isInFormSelectableValues() { return this.field && this.field.type_field != 'hidden' && this.field.acceptValueOptions() && this.field.type != "relate"},
      get isInFormSelectableRelation() { return this.field && this.field.type_field != 'hidden' && this.field.acceptValueOptions() && this.field.type == "relate" },

      get isFixedValue() { return this.field && this.field.type_field == 'hidden' && this.field.value_type == 'fixed'; },
      get isFixedValueOfEnum() { return this.isFixedValue && this.field.value_options.length > 0; },
      get isFixedValueOfRelated() { return this.isFixedValue && this.field.type == 'relate'},
      get isFixedValueOfDate() { return this.isFixedValue && this.isDate; },
      get isFixedValueOfDefault() { return this.isFixedValue && !this.isFixedValueOfEnum && !this.isFixedValueOfRelated && !this.isFixedValueOfDate },
      showRelativeDateSelector: false,
      relativeDateSelected: 'custom',
      get availableRelativeDates() { return utils.getList("stic_advanced_web_forms_date_relative_list"); },
        

      get isValid() { return this.field?.isValid() == true; },

      init() {
        this.$watch('field.name', (newName, oldName) => {
          this.configValueOptions = false;
          this.showRelativeDateSelector = false;

          if (!this.field) return;
          if (this.isEdit) {
            if (this.isFixedValueOfDate) {
              if (this.availableRelativeDates.find(v => v.id == this.field.value)) {
                this.showRelativeDateSelector = true;
              }
            }
            return;
          }
          if (newName != oldName) {
            this.field.updateWithFieldInformation(this.selectedFieldInfo);
            if (this.field.type_field == 'hidden' || this.isInFormSelectableValues) {
              this.field.setValueOptions(utils.getFieldOptions(this.selectedFieldInfo));
            } else {
              this.field.setValueOptions();
            }
            if (this.field.type_field == 'unlinked') {
              this.dataBlock.fixFieldName(this.field);
            }
            this.configValueOptions = false;
            this.optionValuesRelated = '';
          }
        });
        this.$watch('field.text_original', (newText, oldText) => {
          if (!this.field) return;
          if (this.field.type_field == 'unlinked') {
            this.field.name = AWF_Configuration.cleanName(newText);
            this.field.label = utils.toFieldLabelText(newText);
          }
        });
        this.$watch('availableFields', (newArray) => {
          if (!this.field) return;
          if (this.isEdit) return;
          this.field.name = newArray[0]?.name ?? '';
        });
        this.$watch('availableValueTypes', (newArray) => {
          if (!this.field) return;
          if (this.isEdit) {
            // Force reactive
            const currentValue = this.field.value_type; 
            setTimeout(() => {
              this.field.value_type = ''; 
              this.field.value_type = currentValue;
            }, 50);            
          } else {
            this.field.value_type = newArray[0]?.id ?? '';
          }
        });
        this.$watch('availableTypesInForm', (newArray) => {
          if (!this.field) return;
          if (this.isEdit) {
            // Force reactive
            const currentValue = this.field.type_in_form; 
            setTimeout(() => {
              this.field.type_in_form = ''; 
              this.field.type_in_form = currentValue;
            }, 50);
          } else {
            this.field.type_in_form = newArray[0]?.id ?? '';
          }
        });
        this.$watch('availableSubtypesInForm', (newArray) => {
          if (!this.field) return;
          if (this.isEdit) { 
            // Force reactive
            const currentValue = this.field.subtype_in_form; 
            setTimeout(() => {
              this.field.subtype_in_form = ''; 
              this.field.subtype_in_form = currentValue;
            }, 50);
          } else {
            this.field.subtype_in_form = newArray[0]?.id ?? '';
          }
        });
        this.$watch('field.value_type', (newType, oldType) => {
          if (!this.field) return;
          if (this.isEdit) return;
          if (this.isFixedValue) {
            this.field.value = '';
            this.field.value_text = '';
          }
        });
        this.$watch('field.subtype_in_form', (newType, oldType) => {
          if (!this.field) return;
          if (!this.isEdit) {
            this.field.setValueOptions(utils.getFieldOptions(this.selectedFieldInfo));
          }
          if (this.field.type_field == 'unlinked' || this.field.isSelectCustomOptions()) {
            this.configValueOptions = this.isInFormSelectableValues;
          }
        });
        this.$watch('field.value_options', (newArray) => {
          if (!this.field) return;
          if (this.isEdit) { 
            // Force reactive
            const currentValue = this.field.value; 
            setTimeout(() => {
              this.field.value = ''; 
              this.field.value = currentValue;
            }, 50);
            // Set aux OptionValuesRelated var
            if (this.field.type == 'relate') {
              this.optionValuesRelated = this.field.value_options.map(o => o.value).join("|");
            }
          } else {
            if (this.field.type_field == 'hidden' && newArray.length > 0) {
              this.field.value = newArray[0]?.value ?? '';
            }
          }
        });
        this.$watch('showRelativeDateSelector', (newValue, oldValue) => {
          if (!this.field) return;
          if (newValue == true) {
            if (!this.availableRelativeDates.find(v => v.id == this.field.value)) {
              this.field.value = 'today';
            }
            this.relativeDateSelected = this.field.value;
          }
          if (newValue !== true) {
            this.field.value = '';
          }
        });
        this.$watch('relativeDateSelected', (newValue, oldValue) => {
          if (!this.field) return;
          if (!this.isFixedValueOfDate) return;
          if (newValue == 'custom') {
            this.field.value = '';
          } else {
            this.field.value = newValue.replaceAll('_', ' ');
          }
        });
        this.$watch('field.value', (newValue, oldValue) => {
          if (!this.field) return;
          if (this.isFixedValueOfEnum) {
            this.field.value_text = this.field.value_options.find(v => v.value == newValue)?.text;
          } else if (this.isFixedValueOfDate) {
            if (this.showRelativeDateSelector) {
              this.field.value_text = this.availableRelativeDates.find(v => v.id == newValue.replaceAll(' ', '_'))?.text ?? '';
              if (this.field.value_text == '') {
                this.relativeDateSelected = 'custom';
                this.field.value_text = newValue;
              }

            } else {
              this.field.value_text = new Date(newValue).toLocaleDateString();
            }
          } else if (this.isFixedValueOfDefault) {
            this.field.value_text = this.field.value;
          }
        });
        this.$watch('optionValuesRelated', (newRelateds, oldRelateds) => {
          if (!this.field) return;
          let arrIds = newRelateds.split('|');
          let destModule = this.relatedModule;
          if (this.field.type == 'relate') {
            this.field.setValueOptions(utils.getRecordsTextById(destModule, arrIds));
          }
        });
      },

      convertFieldToType(type) {
        if (!this.field) return;
        if (type == this.field.type_field) return;
        if (type == 'form' || type == 'hidden') {
          this.field.updateWithFieldInformation(this.selectedFieldInfo, type);
          this.store.needDeleteOld = true;
          this.configValueOptions = false;
        }
      },

      openPopUp(idBase, single=true) {
        let mode = 'single';
        if (single!=true) {
          mode = 'MultiSelect';
        }
        let destModule = this.relatedModule;
        let objMap = {'id':`${idBase}_id`, 'name':`${idBase}_name`};
        open_popup(destModule, 600, 400, '', true, false,
                    {
                      'passthru_data': objMap,
                      'field_to_name_array': objMap,
                      'call_back_function': 'handle_open_popup'
                    }, mode, true);
      },
    };
  }

  static editionValidationFieldxData(validationStore, config) {
    return {
      formConfig: config,
      store: validationStore,

      applyCondition: false,
      _activeDef: null,

      get validation() { return this.store?.validation; },
      get field() { return this.store?.field; },
      get dataBlock() { return this.store?.dataBlock; },
      get fieldName() { return this.dataBlock?.getFieldInputName(this.field); },
      get isEdit() { return this.store?.isEdit; },
      get isValid() { return this.validation?.isValid() == true; },

      get availableFieldsInForm() { return this.formConfig?.getAllFieldsInForm(this.fieldName) ?? []; },

      get availableValidators() {
        if (!this.field) return [];

        // Obtenemos el tipo de acción del campo (ej: un campo 'int' del CRM retorna 'integer')
        const fieldType = this.field.getTypeInActions(); 

        // Filtramos las acciones del servidor
        return utils.getDefinedActions().filter(a => 
            a.type === 'Validator' &&
            (
                a.supportedDataTypes.length === 0 || // Si está vacío, es que soporta todos los tipos
                a.supportedDataTypes.includes(fieldType) // O si incluye el tipo del campo
            )
        );
      },
      get selectedValidatorDefinition() {
        if (!this.validation.validator) return null;
        return utils.getDefinedActions().find(a => a.name === this.validation.validator);
      },
    };
  }

  static fieldsSummaryxData(dataBlock, config) {
    return {
      formConfig: config,
      dataBlock: dataBlock,

      fieldTabSelected: 'form',

      get firstFieldInFormIndex() {
        return this.dataBlock.fields.filter(f => !f.isFieldInForm()).length;
      },

      get summaryDescription() {
        switch(this.fieldTabSelected) {
          case 'form':
            return utils.translate('LBL_FIELDS_FORM_DESC');
          case 'hidden':
            return utils.translate('LBL_FIELDS_HIDDEN_DESC');
        }
        return '';
      },

      canShowField(field) {
        switch(this.fieldTabSelected) {
          case 'form':
            return field.isFieldInForm();
          case 'hidden':
            return !field.isFieldInForm();
        }
        return true;
      },

      canShowFieldColumn(column) {
        if (column == 'label' || column == 'type_in_form' || column == 'subtype_in_form' || 
            column == 'value_options' || column == 'required_in_form') {
          return this.fieldTabSelected != 'hidden';
        } 
        if (column == 'type_field') {
          return this.fieldTabSelected != 'form' && this.dataBlock.module;
        } 
        if (column == 'value' || column == 'value_text') {
          return this.fieldTabSelected != 'form';
        } 
        if (column == 'value_type') {
          return this.fieldTabSelected == 'hidden';
        }
        return true;
      },

      getfieldText(field) {
        if (!field) return '';
        if (!field.isFieldInForm()) return '';

        if (field.type_field == 'unlinked') {
          return `<i>[${field.text_original}]</i>`;
        }
        return field.text_original;
      },

      getfieldValueText(field) {
        if (!field) return '';
        if (field.value_type == 'dataBlock') {
          return this.formConfig.data_blocks.find(d => d.id == field.value)?.text;
        }
        return field.value_text;
      },

      getValidationsTooltip(field) {
        if (!field || !field.validations || field.validations.length == 0) return '';

        let tooltip = utils.translateForFieldLabel('LBL_FIELD_ACTIVE_VALIDATIONS') + "\n";
        const lines = field.validations.map(val => {
            const def = utils.getDefinedActions().find(a => a.name === val.validator);
            const name = def ? def.title : val.validator;
            return `- ${name}`;
        });

        return tooltip + lines.join('\n');
      },
    }
  }

  static duplicateFieldsxData(dataBlock, index) {
    return {
      dataBlock: dataBlock,
      duplicateDef: dataBlock.duplicate_detections[index],

      opened: false, 

      get selectedFieldsText() {
        return this.duplicateDef.fields.length > 0 
               ? this.duplicateDef.fields.map(d => this.dataBlock.fields.find(f => f.name == d)?.text_original).join(', ')
               : utils.translate('LBL_DUPLICATE_FIELDS_SEL_FIELDS');
      },

      get fieldsToDuplicate() {
        return this.dataBlock.fields.filter(f => f.type_field!='unlinked' && f.type!='relate');
      },

    }
  }
}

class WizardStep3 {
  static mainStep3xData() {
    return {
      get formConfig() { return window.alpineComponent.formConfig; },
      get bean() { return window.alpineComponent.bean; },

      flowTabSelected: 0,
      get flow() { return this.formConfig.flows.find(f => f.id == this.flowTabSelected); },
      get actions() { return this.flow?.actions ?? []; },
      showAllActions: false,

      selectedCategory: '', 
      selectedActionDefName: '', // Per al v-model del select d'accions

      init() {
        this.flowTabSelected = this.bean.processing_mode == 'async' ? 1 : 0;

        // Store for the Action Editor management
        if (!Alpine.store('actionEditor')) {
          Alpine.store('actionEditor', {
            isOpen: false,             // Indica si está abierto el editor de acciones
            isEdit: false,             // Indica si es modo edición (false: modo creación)
            flow: null,                // El flujo de la acción
            original_id: '',           // Id original de la acción

            // Objetos de trabajo
            action: null,              // Copia de los datos de la acción
            definition: null,          // Definición de la acción

            // Estado de la UI
            currentStep: 1,            // 1: Selección, 2: Configuración

            // Listados y filtros
            allDefinitions: [],        // Todas las definiciones de acciones disponibles
            isTerminalFilter: false,   // Filtro para acciones terminales al crear
            selectedCategory: '',      // Para guardar la categoría seleccionada 
            selectedActionDefName: '', // Para guardar la acción seleccionada en modo creación

            // Condición
            applyCondition: false,
            _activeConditionFieldDef: null,

            get formConfig() { return window.alpineComponent.formConfig; },

            get availableFieldsInForm() { return this.formConfig?.getAllFieldsInForm() ?? []; },
            
            get activeConditionFieldDef() { return this._activeConditionFieldDef; },
            get isBooleanCondition() {
              debugger;
              const def = this.activeConditionFieldDef;
              if (!def) return false;
              return def.type === 'bool' || def.type === 'checkbox' || 
                     def.subtype_in_form === 'select_checkbox' || def.subtype_in_form === 'select_switch';
            },
            get conditionInputType() {
                const def = this._activeConditionFieldDef;
                if (!def) return 'text';
                if (def.type_in_form === 'number') return 'number';
                if (def.type_in_form === 'date') {
                  if (def.subtype_in_form === 'date_time') return 'time';
                  if (def.subtype_in_form === 'date_datetime') return 'datetime-local';
                  return 'date';
                }
                return 'text';
            },

            init() {
              // Cargar todas las definiciones de acciones seleccionables por el usuario
              this.allDefinitions = utils.getDefinedActions().filter(a => a.isUserSelectable && a.isActive && 
                                                                     (a.type == 'Hook' || a.type == 'Deferred'));
              
              Alpine.effect(() => {
                const fieldName = this.action?.condition_field;
                this.updateActiveConditionFieldDef(fieldName);
              });
              Alpine.effect(() => {
                if (this.applyCondition === false && this.action) {
                  if (this.action.condition_field !== '' || this.action.condition_value !== '') {
                    this.action.condition_field = '';
                    this.action.condition_value = '';
                    this._activeConditionFieldDef = null;
                  }
                }
              });
            },

            /**
             * Retorna si es una acción nueva
             * @returns {boolean}
             */
            get isNewAction() { return this.original_id === ''; },

            /**
             * Retorna el título del modal
             * @returns {string}
             */
            get title() {
              if (!this.action) return '';

              if (this.isNewAction) {
                if (this.action.is_terminal) {
                  return utils.translate('LBL_ACTION_TERMINAL_NEW');
                }
                return utils.translate('LBL_ACTION_NEW');
              } else {
                return utils.translate('LBL_ACTION_TERMINAL') + ' » ' + this.action.text;
              }
            },

            /**
             * Retorna las categorías disponibles según las acciones válidas
             * @returns {Array} Lista de categorías disponibles
             */
            get availableCategories() {
              const validActions = this.allDefinitions.filter(d => d.isTerminal == this.isTerminalFilter);
              const uniqueCatIds = [...new Set(validActions.map(a => a.category))];
              return AWF_Action.category_in_formList().filter(c => uniqueCatIds.includes(c.id));
            },

            /**
             * Retorna las acciones filtradas según la categoría y si son terminales
             * @returns {Array} Lista de definiciones de acciones filtradas
             */
            get filteredActions() {
                if (!this.selectedCategory) return [];
                return this.allDefinitions.filter(d => d.isTerminal == this.isTerminalFilter && d.category == this.selectedCategory);
            },

            get isValid() {
              if (!this.action) return false;
              return this.action.isValid();
            },

            /**
             * Abre el Modal para Crear una acción
             * @param {AWF_Flow} flow El Flujo de acciones
             * @param {boolean} isTerminal Indica si es una acción terminal
             * @returns {void}
             */
            openCreate(flow, isTerminal) {
              this.isEdit = false;
              this.flow = flow;
              this.original_id = '';

              this.currentStep = 1;

              // Para crear, empezamos sin acción ni definición: Se deberá seleccionar una definición
              this.action = null;
              this.definition = null;
              this.isTerminalFilter = isTerminal;
              this.selectedActionDefName = '';

              // Preseleccionamos la primera categoría disponible
              const cats = this.availableCategories;
              if (cats.length > 0) this.selectedCategory = cats[0].id;

              this.isOpen = true;
              this.syncConditionState();
            },

            /**
             * Abre el Modal para Editar una acción
             * @param {AWF_Flow} flow El Flujo de acciones
             * @param {AWF_Action} action La acción a editar
             * @returns {void}
             */
            openEdit(flow, action) {
              this.isEdit = true;
              this.flow = flow;
              this.original_id = action.id;

              this.currentStep = 2;

              // Recuperamos definición y estado visual
              this.definition = this.allDefinitions.find(d => d.name == action.name);
              if (!this.definition) { console.error("Definition not found for action: " + action.name); return; }

              this.selectedCategory = this.definition.category;
              this.selectedActionDefName = this.definition.name;

              // Clonamos la acción para editarla
              this.action = new AWF_Action(action);

              // Adaptamos los valores de los parámetros tipo textarea para mostrar saltos de línea correctamente
              if (this.action.parameters && this.definition.parameters) {
                this.action.parameters.forEach(param => {
                  const paramDef = this.definition.parameters.find(p => p.name === param.name);
                  if (paramDef && paramDef.dataType === 'textarea' && typeof param.value === 'string') {
                    // Reemplazamos los '\n' guardados por saltos de línea reales
                    param.value = param.value.replace(/\\+n/g, '\n');
                  }
                });
              }

              this.isOpen = true;
              this.syncConditionState();
            },

            goToStep2() {
                if (!this.selectedActionDefName) return;
                
                this.onActionChange(); 
                this.currentStep = 2;
            },

            goBackToStep1() {
                this.currentStep = 1;
            },

            /**
             * Gestiona el cambio de acción seleccionada en el selector (modo creación)
             * @returns {void}
             */
            onActionChange() {
                if (!this.selectedActionDefName) return;

                const def = this.allDefinitions.find(d => d.name == this.selectedActionDefName);
                if (!def) return;

                this.definition = def;
                
                // Si es una acción terminal, asignamos orden a 999
                const defaultOrder = def.isTerminal ? 999 : (def.order ?? 0);

                // Creamos una instancia vacía basada en la definición
                this.action = new AWF_Action({
                    name: def.name,
                    title: def.title,
                    text: def.title, // Por defecto el título
                    description: def.description,
                    category: def.category,
                    is_terminal: def.isTerminal,
                    order: defaultOrder,
                    is_user_selectable: true
                });

                // Inicializamos los parámetros vacíos según la definición
                this.action.parameters = (def.parameters || []).map(pDef => new AWF_ActionParameter({
                    name: pDef.name,
                    text: pDef.text,
                    type: pDef.type,
                    required: pDef.required,
                    value: pDef.defaultValue,
                    selectedOption: ''
                }));

                this.syncConditionState();
            },

            /**
             * Gestiona el cambio de opción en el selector de opciones de un parámetro
             * Si la opción es de tipo 'empty', asigna un valor automático para pasar la validación.
             * Si no, limpia el valor.
             * * @param {AWF_ActionParameter} param El parámetro que se está editando
             * @param {object} paramDef La definición del parámetro (DTO)
             */
            onParamOptionChange(param, paramDef) {
              const selectedOptDef = paramDef.selectorOptions.find(o => o.name == param.selectedOption);
              
              if (selectedOptDef && selectedOptDef.resolvedType === 'empty') {
                  param.value = selectedOptDef.name;
                  param.value_text = selectedOptDef.text;
              } else {
                  param.value = '';
                  param.value_text = '';
              }
            },

            /**
             * Retorna la lista de Bloques de Datos disponibles para asignar en los parámetros
             * @param {Array} supportedModules Lista de los módulos de los Bloques de datos a mostrar
             * @returns {Array} Lista de {id, text, module} de bloques de datos
             */
            getSupportedDataBlocksList(supportedModules = []) {
              let blocks = [];
              if (!supportedModules) supportedModules = [];

              this.formConfig.data_blocks.forEach(b => {
                if (supportedModules.length == 0 || supportedModules.includes(b.module)) {
                  blocks.push({
                    id: b.id, 
                    text: `${b.text} (${b.getModuleText()})`,
                    module: b.module,
                  })
                }
              });
              return blocks;
            }, 

            /**
             * Retorna la lista de los campos disponibles en el formulario
             * @param {Array} supportedDataTypes Lista de los tipos de datos de los campos a mostrar
             * @returns Lista de {id, text, typeInActions} de campos
             */
            getSupportedFieldsList(supportedDataTypes = []) {
              // Format value: "BlockName.FieldName" / "_detached.BlockName.FieldName"
              let fields = [];
              if (!supportedDataTypes) supportedDataTypes = [];

              this.formConfig.data_blocks.forEach(block => {
                  block.fields.forEach(field => {
                      const typeInActions = field.getTypeInActions();
                      if (supportedDataTypes.length == 0 || supportedDataTypes.includes(typeInActions)) {
                        const prefix = field.type_field === 'unlinked' ? `_detached.${block.name}.` : `${block.name}.`;
                        fields.push({
                            id: prefix + field.name,
                            text: `${block.text} » ${field.label || field.text_original}`,
                            typeInActions: typeInActions
                        });
                      }
                  });
              });
              return fields;
            },

            getParameterValueInputType(paramDataType) {
              const dataType  = (!paramDataType || paramDataType == '') ? 'text' : paramDataType;
              if (['text', 'date', 'datetime-local', 'time', 'email', 'tel', 'url'].includes(dataType)) return dataType;
              if (['integer', 'float'].includes(dataType)) return 'number';
              if (dataType == 'boolean') return 'checkbox';
              return '';
            },

            /**
             * Cierra el modal de edición de una acción
             */
            close() {
              this.isOpen = false;
              this.flow = null;
              this.original_id = '';
              this.selectedCategory = '';
              this.action = null;
              this.definition = null;
              this.isTerminalFilter = false;
            },

            /**
             * Guarda los cambios de la edición (o creación) de una acción
             * @returns {void}
             */
            saveChanges() {
              const form = document.getElementById('ModalActionConfigForm');
              if (form) {
                  // Ejecutamos la función nativa para comprobar la validez de los imputs y mostrar errores
                  if (!form.reportValidity()) {
                      return; // Si no es valido, abortamos el proceso
                  }
              }
              if (!this.isValid) {
                alert(utils.translate('LBL_ACTION_PARAM_MISSING_MESSAGE'));
                return;
              }

              // Recalculate action before saving
              this._recalculateAction(this.action, this.definition);

              if (this.isNewAction) {
                // Add Action to flow: Insertion based on order
                let insertIndex = this.flow.actions.length;
                for (let i = 0; i < this.flow.actions.length; i++) {
                  if ((this.flow.actions[i].order ?? 0) > (this.action.order ?? 0)) {
                    insertIndex = i;
                    break;
                  }
                }
                this.flow.actions.splice(insertIndex, 0, this.action);
              } else {
                // Update existing Action in flow
                const index = this.flow.actions.findIndex(a => a.id == this.original_id);
                if (index !== -1) {
                  this.flow.actions[index] = this.action;
                }
              }
              this.close();
            },

            /**
             * Reconstruye los parámetros y recalcula los requisitos de una acción basándose en su definición y los valores actuales.
             * @param {AWF_Action} action La acción a procesar (se modifica in-place)
             * @param {object} definition La definición de la acción (ActionDefinitionDTO)
             * @returns {void}
             */
            _recalculateAction(action, definition) {
              const newParams = [];
              const requisiteActions = new Set();

              // Iteramos sobre la DEFINICIÓN (la fuente de verdad)
              (definition.parameters || []).forEach(paramDef => {
                // Buscamos el valor actual que tenía la acción (si existía)
                const currentParam = action.parameters.find(p => p.name == paramDef.name);

                // Construimos el parámetro asegurando estructura actualizada
                const newParam = new AWF_ActionParameter({
                  name: paramDef.name,
                  text: paramDef.text,
                  type: paramDef.type,
                  required: paramDef.required,
                        
                  // Preservamos el valor editado o usamos el default
                  value: currentParam ? currentParam.value : paramDef.defaultValue,
                  value_text: currentParam ? currentParam.value_text : '', 
                  selectedOption: currentParam ? currentParam.selectedOption : ''
                });
                    
                newParams.push(newParam);

                // 2. Recálculo de Requisitos (Requisite Actions)
                // Miramos si este parámetro apunta a un DataBlock
                const paramIsDataBlock = (paramDef.type === 'dataBlock') || 
                                         (paramDef.selectorOptions || []).find(o => o.name == newParam.selectedOption)?.resolvedType === 'dataBlock';

                if (paramIsDataBlock && newParam.value) {
                  const requiredBlock = this.formConfig.data_blocks.find(b => b.id == newParam.value);
                  if (requiredBlock && requiredBlock.save_action_id) {
                    requisiteActions.add(requiredBlock.save_action_id);
                  }
                }
              });

              // 3. Actualizamos la acción
              action.parameters = newParams;
              action.requisite_actions = Array.from(requisiteActions); //
            },

            /**
             * Actualiza la definición del campo activo para la condición
             * @param {string} fieldName Nombre HTML del campo
             */
            updateActiveConditionFieldDef(fieldName) {
              if (!fieldName) {
                this._activeConditionFieldDef = null;
              } else {
                // Solo actualizamos si ha cambiado
                const newDef = this.formConfig.getFieldDefinitionByHtmlName(fieldName);
                if (this._activeConditionFieldDef !== newDef) {
                  this._activeConditionFieldDef = newDef;
                  // Si es un select, forzamos reactividad para que coja el valor correcto
                  if (this._activeConditionFieldDef && this._activeConditionFieldDef.type_in_form === 'select') {
                    const currentValue = this.action.condition_value;
                    if (currentValue) {
                      setTimeout(() => { 
                        if(this.action) this.action.condition_value = currentValue; 
                      }, 50);
                    }
                  }
                }
              }
            },

            /**
             * Sincroniza el estado de la condición según los datos de la acción
             */
            syncConditionState() {
              if (this.action && this.action.condition_field) {
                this.applyCondition = true;
                this.updateActiveConditionFieldDef(this.action.condition_field);
              } else {
                this.applyCondition = false;
                this._activeConditionFieldDef = null;
              }
            },
          });
        }
      },

      /**
       * Indica si se puede editar una acción
       * @param {AWF_Action} action La acción
       * @returns {boolean}
       */
      canEditAction(action) {
        return action.is_user_selectable;
      },

      /**
       * Indica si se puede eliminar una acción
       * @param {AWF_Action} action La acción
       * @returns {boolean}
       */
      canRemoveAction(action) {
        return action.is_user_selectable;
      },

      /**
       * Elimina una acción del flujo
       * @param {AWF_Action} action La acción a eliminar
       * @return {void}
       */
      removeAction(action) {
        this.flow.actions = this.flow.actions.filter(a => a.id != action.id);
      },

      /**
       * Indica si se puede mover hacia arriba una acción
       * @param {AWF_Action} action La acción
       * @returns {boolean}
       */
      canMoveUpAction(action) {
        // No podemos mover una acción fija
        if (action.is_fixed_order) return false;

        // No podemos mover la primera acción
        const index = this.actions.findIndex(a => a.id == action.id);
        if (index <= 0) return false;

        // No podemos saltar una acción fija      
        const prevAction = this.actions[index - 1];
        if (prevAction.is_fixed_order) return false;

        // No podemos mover antes de una acción requisito
        if ((action.requisite_actions || []).includes(prevAction.id)) return false;

        return true;
      },

      /**
       * Mueve hacia arriba una acción
       * @param {AWF_Action} action La acción
       * @returns {void}
       */
      moveUpAction(action) {
        if (!this.canMoveUpAction(action)) return;

        const index = this.actions.findIndex(a => a.id == action.id);
        const actionToMove = this.actions[index];
        this.actions.splice(index, 1);
        this.actions.splice(index - 1, 0, actionToMove);
      },

      /**
       * Indica si se puede mover hacia abajo una acción
       * @param {AWF_Action} action La acción
       * @returns {boolean}
       */
      canMoveDownAction(action) {
        // No podemos mover una acción fija
        if (action.is_fixed_order) return false;

        // No podemos mover la última acción
        const index = this.actions.findIndex(a => a.id == action.id);
        if (index >= this.actions.length - 1) return false;

        // No podemos saltar una acción fija      
        const nextAction = this.actions[index + 1];
        if (nextAction.is_fixed_order) return false;

        // No podemos mover si somos requisito de la siguiente acción
        if ((nextAction.requisite_actions || []).includes(action.id)) return false;

        return true;  
      },

      /**
       * Mueve hacia abajo una acción
       * @param {AWF_Action} action La acción
       * @returns {void}
       */
      moveDownAction(action) {
        if (!this.canMoveDownAction(action)) return;

        const index = this.actions.findIndex(a => a.id == action.id);
        const actionToMove = this.actions[index];
        this.actions.splice(index, 1);
        this.actions.splice(index + 1, 0, actionToMove);
      },

      /**
       * Genera la etiqueta descriptiva de la condición de una acción
       * @param {AWF_Action} action La acción
       * @returns {string}
       */
      getActionConditionLabel(action) {
        if (!action.condition_field) return '';
        
        // Obtenemos la etiqueta del campo
        const fieldDef = this.formConfig.getFieldDefinitionByHtmlName(action.condition_field);
        const label = fieldDef ? utils.fromFieldLabelText(fieldDef.label || fieldDef.text_original) : action.condition_field;
        
        // Obtenemos el valor formateado
        let val = action.condition_value;
        if (fieldDef && (fieldDef.type == 'bool' || fieldDef.type == 'checkbox' || fieldDef.subtype_in_form == 'select_checkbox')) {
          if (val == '1' || val == 'true') {
            val = utils.translate('LBL_YES');
          } else if (val == '0' || val == 'false') {
            val = utils.translate('LBL_NO');
          }
        }

        return `${label} = ${val}`;
      }
    };
  }

  static editCrmRecordParamxData(paramName, initial_supportedModules) {
    return {
      supportedModules: initial_supportedModules,
      
      get param() {
        return Alpine.store('actionEditor').action.parameters.find(p => p.name == paramName);
      },
      
      selectedModule: '',
      rawId: '',
      
      get allowedModules() {
        const all = Object.values(STIC.enabledModules);
        if (!this.supportedModules || this.supportedModules.length === 0) return all;
        return all.filter(m => this.supportedModules.includes(m.name));
      },

      init() {
        this.selectedModule = this.allowedModules[0].name;

        if (this.param.value && this.param.value.includes('|')) {
          const parts = this.param.value.split('|');
          this.selectedModule = parts[0];
          this.rawId = parts[1];
        } 
        this.$watch('selectedModule', (val) => this.updateParamValue());
        this.$watch('rawId', (val) => this.updateParamValue());
      },

      updateParamValue() {
        let newValue = '';
        if (this.selectedModule && this.rawId) {
          newValue = `${this.selectedModule}|${this.rawId}`;
        }
        this.param.value = newValue;
      },

      onModuleChange() {
        this.rawId = '';
        this.param.value_text = ''; 
        this.updateParamValue();
      },

      openPopUp(idBase, destModule, single=true) {
        let mode = 'single';
        if (single!=true) {
          mode = 'MultiSelect';
        }
        let objMap = {'id':`${idBase}_id`, 'name':`${idBase}_name`};
        open_popup(destModule, 600, 400, '', true, false,
          {
            'passthru_data': objMap,
            'field_to_name_array': objMap,
            'call_back_function': 'handle_open_popup'
          }, mode, true);
      },
      
    };
  }
}

class WizardStep4 {
  static mainStep4xData() {
    return {
      layoutTabSelected: 'design',
      isLoadingPreview: false,
      generatedHtml: '',
      previewTimeout: null,

      get formConfig() { return window.alpineComponent.formConfig; },
      get bean() { return window.alpineComponent.bean; },
      get sections() { return this.formConfig.layout.structure; },

      get availableContainerTypes() {
        const validCategories = ['panel', 'card'];
        return AWF_LayoutSection.containerType_in_formList().filter(c => validCategories.includes(c.id));
      },

      init() {
        this.formConfig.syncLayoutWithDataBlocks();
        if (this.layoutTabSelected === 'preview') {
            this.refreshPreview();
        }

        this.$watch('layoutTabSelected', (val) => {
            if (val === 'preview') {
                this.refreshPreview();
            }
        });
        this.$watch('formConfig.layout', (val) => {
            if (this.layoutTabSelected === 'preview') {
                this.debouncedPreview();
            }
        });
      },
      debouncedPreview() {
        if (this.previewTimeout) clearTimeout(this.previewTimeout);
        
        // Set next refresh in 1000ms
        this.previewTimeout = setTimeout(() => {
            this.refreshPreview();
        }, 1000);
      },

      async refreshPreview() {
        if (this.previewTimeout) clearTimeout(this.previewTimeout);
        if (this.isLoadingPreview) return;

        this.isLoadingPreview = true;
        this.generatedHtml = utils.translate('LBL_PREVIEW_LOADING');

        await this.$nextTick();
        const iframe = this.$refs.previewFrame;
          
        if (!iframe) {
          console.error("Iframe not found");
          this.isLoadingPreview = false;
          return;
        }

        try {
          const response = await fetch("index.php?module=stic_Advanced_Web_Forms&action=renderPreview", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              id: this.bean.id || 'preview_id',
              config: this.formConfig.toJSONString()
            })
          });

          if (response.ok) {
            this.generatedHtml = await response.text();
            this.$nextTick(() => {
              iframe.onload = function() {
                  const height = iframe.contentWindow.document.body.scrollHeight;
                  iframe.style.height = height + 'px';
              };
                          
              // Inject HTML
              const doc = iframe.contentWindow.document;
              doc.open();
              doc.write(this.generatedHtml);
              doc.close();
            });
          } else {
            console.error("Error generating preview");
          }
        } catch (e) {
          this.generatedHtml = utils.translate('LBL_PREVIEW_LOAD_ERROR');
          console.error("Connection error", e);
        } finally {
          this.isLoadingPreview = false;
        }
      },

      createSection() {
        this.sections.push(new AWF_LayoutSection({
          title: utils.translate('LBL_SECTION_NEW'),
        }));
      },

      canDeleteSection(section) {
        return section.elements.length==0;
      },

      deleteSection(section) {
        if (!this.canDeleteSection(section)) return;

        this.formConfig.layout.structure = this.formConfig.layout.structure.filter(s => s.id != section.id);
      },

      canMoveUpSection(section) {
        const index = this.sections.findIndex(s => s.id == section.id);
        if (index <= 0) return false;

        return true;
      },
      
      moveUpSection(section) {
        if (!this.canMoveUpSection(section)) return;

        const index = this.sections.findIndex(s => s.id == section.id);
        const sectionToMove = this.sections[index];
        this.sections.splice(index, 1);
        this.sections.splice(index - 1, 0, sectionToMove);
      },

      canMoveDownSection(section) {
        const index = this.sections.findIndex(s => s.id == section.id);
        if (index >= this.sections.length - 1) return false;

        return true;
      },

      moveDownSection(section) {
        if (!this.canMoveDownSection(section)) return;

        const index = this.sections.findIndex(s => s.id == section.id);
        const sectionToMove = this.sections[index];
        this.sections.splice(index, 1);
        this.sections.splice(index + 1, 0, sectionToMove);
      },

      getDataBlock(element) {
        if (element.type == 'datablock') {
          return this.formConfig.data_blocks.find(d => d.id == element.ref_id);
        }
        return null;
      },

      getFields(element) {
        return this.getDataBlock(element)?.fields.filter(f => f.type_field != 'hidden');
      },

      getElementHeader(element) {
        let header = element.type;
        if (element.type == 'datablock') {
          const dataBlock  = this.getDataBlock(element);
          header = `${utils.translate('LBL_DATABLOCK')}: ${dataBlock.text}`;
        }
        return header;
      },

      moveElementUp(section, index) {
        if (index <= 0) return;
        const item = section.elements[index];
        section.elements.splice(index, 1);
        section.elements.splice(index - 1, 0, item);
      },      

      moveElementDown(section, index) {
        if (index >= section.elements.length - 1) return;
        const item = section.elements[index];
        section.elements.splice(index, 1);
        section.elements.splice(index + 1, 0, item);
      },

      moveElementToSection(element, fromSectionId, toSectionId) {
        if (!toSectionId || fromSectionId === toSectionId) return;

        const fromSection = this.formConfig.layout.structure.find(s => s.id == fromSectionId);
        const toSection = this.formConfig.layout.structure.find(s => s.id == toSectionId);

        if (fromSection && toSection) {
          fromSection.elements = fromSection.elements.filter(el => el.id !== element.id);
          toSection.elements.push(element);
        }
      },

      resetTheme() {
        this.formConfig.layout.theme = new AWF_Theme();
        this.formConfig.layout.submit_button_text = utils.translate('LBL_THEME_SUBMIT_BUTTON_TEXT_VALUE');
        this.formConfig.layout.closed_form_title = utils.translate('LBL_THEME_CLOSED_FORM_TITLE_VALUE');
        this.formConfig.layout.closed_form_text = utils.translate('LBL_THEME_CLOSED_FORM_TEXT_VALUE');
        this.formConfig.layout.custom_css = '';
        this.formConfig.layout.custom_js = '';
      }
    }
  }
}

class WizardStep5 {
  static mainStep5xData() {
    return {
      get bean() { return window.alpineComponent.bean; },
      
      tab: 'link', // Active tab
      generatedHtml: utils.translate('LBL_CODE_GENERATING'),
      
      get publicUrl() {
        const baseUrl = window.location.origin + window.location.pathname;
        return `${baseUrl}?entryPoint=stic_AWF_renderForm&id=${this.bean.id}`;
      },

      get previewUrl() {
        return `index.php?module=stic_Advanced_Web_Forms&action=renderPreviewForm&record=${this.bean.id}`;
      },

      get iframeCode() {
        return `<iframe src="${this.publicUrl}" width="100%" height="800" frameborder="0" style="border:0; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></iframe>`;
      },

      init() {
        this.loadGeneratedHtml();
      },

      async loadGeneratedHtml() {
        this.generatedHtml = utils.translate('LBL_CODE_LOADING');
          
        try {
          const response = await fetch("index.php?module=stic_Advanced_Web_Forms&action=renderForm&record=" + this.bean.id);
          if (response.ok) {
            this.generatedHtml = await response.text();
          } else {
            this.generatedHtml = utils.translate('LBL_CODE_GENERATING_ERROR');
          }
        } catch (e) {
          console.error(e);
          this.generatedHtml = utils.translate('LBL_CODE_LOADING_ERROR');
        }
      },

      copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
          alert(utils.translate('LBL_COPY_TO_CLIPBOARD_DONE')); 
        });
      },

      downloadHtml() {
        const element = document.createElement('a');
        const file = new Blob([this.generatedHtml], {type: 'text/html'});
        element.href = URL.createObjectURL(file);
        element.download = `form-${this.bean.id}.html`;
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
      }
    };
  }
}