function wizardForm(readOnly) {
  return {
    navigation: {
      step: 1,
      totalSteps: 4,
    },

    bean: STIC.record || {},
    formConfig: {},

    // [name, text, textSingular, inStudio, icon]
    step1: {},
    step2: {
      allDatablockRelationships: {},
      loadDatablockRelationships() {
        this.allDatablockRelationships = window.alpineComponent.formConfig.getAllDataBlockRelationships();
      },
      usedDatablockRelationships(datablockId) {
        return this.allDatablockRelationships[datablockId].filter(r => r.datablock_orig != '' && r.datablock_dest != '');
      },
      unusedDatablockRelationships(datablockId) {
        return this.allDatablockRelationships[datablockId].filter(r => r.datablock_orig == '' && r.datablock_dest == '');
      },
    },
    step3: {},
    step4: {},

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
  static generalDatablocksxData() {
    return {
      init() {
        // Store for the Field Editor management
        Alpine.store('fieldEditor', {
          isOpen: false,           // Indica si está abierto el editor de campos
          field: new AWF_Field(),  // Copia de los datos del campo
          original_name: '',       // Nombre original del campo
          dataBlock: null,         // El bloque de datos del campo

          /**
           * Retorna si es un campo nuevo
           * @returns {boolean}
           */
          get isNewField() { return this.original_name === ''; },

          /**
           * Retorna el título del editor
           */
          get title() {
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
              return $store.fieldEditor.original_name
            }
          },

          /**
           * Abre el Modal para Crear un campo
           * @param {AWF_DataBlock} dataBlock El Bloque de datos
           * @param {string} type Tipo de campo: unlinked, form, hidden
           */
          openCreate(dataBlock, type) {
            this._open(dataBlock, null, type);
          },

          /**
           * Abre el Modal para Editar un campo
           * @param {AWF_DataBlock} dataBlock El Bloque de datos
           * @param {AWF_Field} fieldData El campo
           */
          openEdit(dataBlock, field) {
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
            this.field = fieldData ? JSON.parse(JSON.stringify(fieldData)) : new AWF_Field ({type_field: type});
            this.original_name = this.field.name;
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
          },

          /**
           * Guarda los cambios de la edición (o creación) de un campo
           */
          saveChanges() {
            if(this.isNewField) {
              this.dataBlock.addField(this.field);
            } else {
              this.dataBlock.updateField(this.original_name, this.field);
            }
            this.close();
          },
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

  static addDataBlockModulexData(initial_formConfig, initial_step2) {
    return {
      formConfig: initial_formConfig,
      step2: initial_step2,

      creatingDataBlock: false,

      newDataBlock: {module:'', text:''},
      get isValid() { 
        return this.newDataBlock.module.trim() != '' && this.newDataBlock.text.trim() != '';
      },

      handleAddDatablockModule() {
        this.formConfig.addDataBlockModule(this.newDataBlock.module, true, this.newDataBlock.text);
        this.creatingDataBlock = false;
        this.step2.loadDatablockRelationships();
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

      configValueOptions: false,

      showAllFields: false,
      get availableFields() {
        return this.showAllFields ? this.dataBlock?.getAvailableFieldsInformation() : this.dataBlock?.getAvailableFieldsInformation().filter(f => f.inViews) ?? [];
      },

      get selectedFieldInfo() { return this.availableFields.find(f => f.name == this.field?.name); },
      get optionValues() { return utils.getFieldOptions(this.selectedFieldInfo); },
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
        if (listName == '' && this.optionValues.length > 0) {
          listName = this.optionValues.filter(v => v.is_visible).map(v => v.text).join(', ');
        }
        return listName;
      },
      optionValuesRelated: '',

      get relatedModule() {
        if (this.field.type == 'relate') {
          return this.formConfig.getRelationshipModule(this.dataBlock.id, this.selectedFieldInfo.options);
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
      get isFixedValueOfEnum() { return this.isFixedValue && this.optionValues.length > 0; },
      get isFixedValueOfRelated() { return this.isFixedValue && this.field.type == 'relate'},
      get isFixedValueOfDate() { return this.isFixedValue && this.isDate; },
      get isFixedValueOfDefault() { return this.isFixedValue && !this.isFixedValueOfEnum && !this.isFixedValueOfRelated && !this.isFixedValueOfDate },
      valueToday: false,

      get isValid() { return this.field?.isValid() == true; },

      init() {
        this.$watch('field.name', (newName, oldName) => {
          if (newName != oldName) {
            this.field?.setValueOptions();
            this.field?.updateWithFieldInformation(this.selectedFieldInfo);
            this.configValueOptions = false;
            this.optionValuesRelated = '';
          }
        });
        this.$watch('field.text_original', (newText, oldText) => {
          if (this.field.type_field == 'unlinked') {
            this.field.name = this.dataBlock.suggestFieldName(AWF_Configuration.cleanName(newText));
            this.field.label = utils.toFieldLabelText(newText);
          }
        });
        this.$watch('availableFields', (newArray) => {
          this.field && (this.field.name = newArray[0]?.name ?? '');
        });
        this.$watch('availableValueTypes', (newArray) => {
          this.field && (this.field.value_type = newArray[0]?.id ?? '');
        });
        this.$watch('availableTypesInForm', (newArray) => {
          this.field && (this.field.type_in_form = newArray[0]?.id ?? '');
        });
        this.$watch('availableSubtypesInForm', (newArray) => {
          this.field && (this.field.subtype_in_form = newArray[0]?.id ?? '');
        });
        this.$watch('field.value_type', (newType, oldType) => {
          if (newType != 'fixed' && this.field) {
            this.field.value = '';
          }
        });
        this.$watch('field.subtype_in_form', (newType, oldType) => {
          this.field?.setValueOptions(this.optionValues);
        });
        this.$watch('optionValues', (newArray) => {
          if (this.field && this.field.value_type == 'fixed') {
            this.field.value = newArray[0]?.id ?? '';
          }
        });
        this.$watch('optionValuesRelated', (newRelateds, oldRelateds) => {
          let arrIds = newRelateds.split('|');
          let destModule = this.relatedModule;
          this.field?.setValueOptions(utils.getRecordsTextById(destModule, arrIds));
        }); 
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


  static addRelationshipxData(dataBlock, initial_formConfig, initial_step2) {
    return {
      formConfig: initial_formConfig,
      step2: initial_step2,

      creatingRelDataBlock: false,
      availableRels: [],
      selectedRelName:'',
      availableDataBlocks: [],
      selectedDataBlockId:'',
      newDataBlockText:'',
      relNewDataBlock:false,

      async loadRelations() {
        this.availableRels = this.step2.unusedDatablockRelationships(dataBlock.id);
        this.selectedRelName = this.availableRels.length > 0 ? this.availableRels[0].name : '';
        this.availableDataBlocks = [];
        this.selectedDataBlockId = '';
        this.creatingRelDataBlock = !this.creatingRelDataBlock;
        this.handleRelationChange();
      },
      async handleRelationChange() {
        this.availableDataBlocks = this.formConfig.getAvailableDataBlocksForRelationship(dataBlock.id, this.selectedRelName);
        this.selectedDataBlockId = this.availableDataBlocks[0]?.id ?? -1;
        this.handleDataBlockChange();
      },
      async handleDataBlockChange() {
        this.newDataBlockText = '';
        this.relNewDataBlock = this.selectedDataBlockId == -1;
        if (this.relNewDataBlock) {
          this.newDataBlockText = this.formConfig.suggestDataBlockText(this.formConfig.getRelationshipModule(dataBlock.id, this.selectedRelName));
        }
      },
      async handleCreateRelationship(){
        this.formConfig.addDataBlockRelationship(dataBlock.id, this.selectedRelName, this.selectedDataBlockId, this.newDataBlockText);
        this.step2.loadDatablockRelationships();
        this.creatingRelDataBlock = false;
      },
    };
  }
}
