function wizardForm(readOnly) {
  return {
    navigation: {
      step: 1,
      totalSteps: 4,
    },

    bean: STIC.record || {},
    formConfig: {},

    async initWizard() {
      // Set Context accessible
      window.alpineComponent = this;

      // Set config object
      let jsonString = "{}";
      if (this.bean?.configuration) {
        jsonString = utils.decodeHTMLString(this.bean.configuration);
      }
      this.formConfig = AWF_Configuration.fromJSON(jsonString);

      // Load current Step
      WizardNavigation.loadStep();
    },
  };
}
class WizardNavigation {
  static cacheSteps = [];

  static async loadStep() {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (step <= 0 || step > totalSteps) {
      return;
    }

    if (!(step in WizardNavigation.cacheSteps)) {
      WizardNavigation.cacheSteps[step] = await (
        await fetch(`modules/stic_Advanced_Web_Forms/custom_views/wizard/steps/step${step}.html`)
      ).text();
    }

    $("#wizard-section-title").text(utils.translate(`LBL_WIZARD_TITLE_STEP${step}`) + ` (${step}/${totalSteps})`);

    let $el = document.getElementById("wizard-step-container");
    $el.innerHTML = WizardNavigation.cacheSteps[step];

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
    if (!this.isReadOnly) {
      fetch("index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          bean: window.alpineComponent.bean,
          config: window.alpineComponent.formConfig.toJSONString(),
          step: window.alpineComponent.navigation.step,
        }),
      }).then(() => location.reload());
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
              let str;
              if (dataBlock_orig.id == datablockId) {
                str = `${dataBlock_orig.text} ⟶ ${dataBlock_dest.text}`;
              } else {
                str = `${dataBlock_dest.text} ⟵ ${dataBlock_orig.text}`;
              }
              str += ` (${rel.text})`;
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

            /**
             * Retorna el título del modal
             */
            get title() {
              if (!this.field) return '';

              if (this.isNewField) {
                switch (this.field.type_field) {
                  case 'form':
                    return utils.translate('LBL_NEW_FIELD_FORM');
                  case 'unlinked':
                    return utils.translate('LBL_NEW_FIELD_UNLINKED');
                  case 'hidden':
                    return utils.translate('LBL_NEW_FIELD_HIDDEN');
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
             * @param {AWF_Field} fieldData El campo
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
                this.dataBlock.addField(this.field);
              } else {
                if (this.needDeleteOld) {
                  this.dataBlock.deleteField(this.field.name);
                  this.dataBlock.addField(this.field);
                } else {
                  this.dataBlock.updateField(this.original_name, this.field);
                }
              }
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
              return utils.translate('LBL_NEW_RELATIONSHIP');
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
              this.availableRels = this.relationships.unusedDatablockRelationships(dataBlock.id);
              this.selectedRelName = this.availableRels[0]?.name ?? '';
              this.relationships.resetDataBlockRelationships(); // Forzar recargar relaciones
              this.isOpen = true;
            },

            /**
             * Cierra el modal de creación de relaciones
             */
            close() {
              this.isOpen = false;
              this.dataBlock = null;
              this.formConfig = null;
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
        if (this.field.type_field == 'unlinked') return null;
        return this.availableFields.find(f => f.name == this.field?.name); 
      },
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
      optionValuesRelated: '',

      get relatedModule() {
        if (this.field.type == 'relate') {
          return this.formConfig.getRelationshipModule(this.dataBlock.id, this.selectedFieldInfo?.options);
        }
        return '';
      },

      get availableValueTypes() { return this.field?.getAvailableValueTypes() ?? [{id:'',text:''}]; },
      get availableTypesInForm() { return this.field?.getAvailableTypesInForm() ?? [{id:'',text:''}]; },
      get availableSubtypesInForm() { return this.field?.getAvailableSubtypesInForm() ?? [{id:'',text:''}]; },

      get isDate() {return this.field?.type == 'date' || this.field?.type == 'datetime' || this.field?.type == 'datetimecombo'; },

      get isInFormSelectableValues() { return this.field?.type_field != 'hidden' && this.field.acceptValueOptions() && this.field.type != "relate"},
      get isInFormSelectableRelation() { return this.field?.type_field != 'hidden' && this.field.acceptValueOptions() && this.field.type == "relate" },

      get isFixedValue() { return this.field && this.field.type_field == 'hidden' && this.field.value_type == 'fixed'; },
      get isFixedValueOfEnum() { return this.isFixedValue && this.field.value_options.length > 0; },
      get isFixedValueOfRelated() { return this.isFixedValue && this.field.type == 'relate'},
      get isFixedValueOfDate() { return this.isFixedValue && this.isDate; },
      get isFixedValueOfDefault() { return this.isFixedValue && !this.isFixedValueOfEnum && !this.isFixedValueOfRelated && !this.isFixedValueOfDate },
      valueToday: false,

      get isValid() { return this.field?.isValid() == true; },

      init() {
        this.$watch('field.name', (newName, oldName) => {
          this.configValueOptions = false;

          if (!this.field) return;
          if (this.isEdit) return;
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
          if (this.isEdit) return;
          this.field.setValueOptions(utils.getFieldOptions(this.selectedFieldInfo));
          if (this.field.type_field == 'unlinked') {
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
              optionValuesRelated = this.field.value_options.map(o => o.value).join("|");
            }
          } else {
            if (this.field.type_field == 'hidden' && newArray.length > 0) {
              this.field.value = newArray[0]?.value ?? '';
            }
          }
        });
        this.$watch('field.value', (newValue, oldValue) => {
          if (!this.field) return;
          if (this.isFixedValueOfEnum) {
            this.field.value_text = this.field.value_options.find(v => v.value == newValue)?.text;
          } else if (this.isFixedValueOfDate) {
            if (newValue == 'today') {
              this.field.value_text = utils.translate('LBL_FIELD_VALUE_TODAY');
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
        this.$watch('valueToday', (newValue, oldValue) => {
          if (!this.field) return;
          this.field.value = newValue ? 'today' : '';
        });
      },

      convertFieldToType(type) {
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
      }
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
               : utils.translate('LBL_ACTION_SEL_FIELDS');
      },

    }
  }

}
