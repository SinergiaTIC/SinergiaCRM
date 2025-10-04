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
}

class WizardStep2 {
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
      
      creatingDataBlock: !this.formConfig?.data_blocks.some(b => b.module!='') ?? false,

      newDataBlock: {module:'', text:''},

      handleAddDatablockModule() {
        this.formConfig.addDataBlockModule(this.newDataBlock.module, true, this.newDataBlock.text);
        this.creatingDataBlock = false;
        this.step2.loadDatablockRelationships();
      }
    };
  }

  static creationFieldxData(dataBlock, initial_formConfig) {
    return {
      formConfig: initial_formConfig,

      creatingFieldUnlinked: false,
      creatingFieldForm: false,
      creatingFieldHidden: false,
      typeField: 'form',

      showAllFields: false,
      get availableFields() { return this.showAllFields ? dataBlock.getAvailableFieldsInformation() : dataBlock.getAvailableFieldsInformation().filter(f => f.inViews); },
          
      selectedFieldInfo: {},
      newField: new AWF_Field(),
      optionValues: [],
      optionValuesListName: '',

      get availableValueTypes() { return this.newField?.getAvailableValueTypes() ?? []; },
      get availableTypesInForm() { return this.newField?.getAvailableTypesInForm() ?? []; },
      get availableSubtypesInForm() { return this.newField?.getAvailableSubtypesInForm() ?? []; },

      get isValid() { return this.newField?.isValid() == true; },

      init() {
        this.$watch('availableFields', (newArray) => {
          this.newField = new AWF_Field({ name: newArray[0]?.name ?? '', type_field: this.newField?.type_field});
        });
        this.$watch('typeField', (newType, oldType) => {
          this.newField = new AWF_Field({ name: this.newField?.name ?? '', type_field: newType});
          this.newField.in_form = this.newField.isFieldInForm();
          if (!this.newField.isFieldInForm()) {
            this.newField.label = '';
            this.required_in_form = false;
            this.newField.type_in_form = '';
            this.newField.value_options = [];
          }
        });
        this.$watch('newField.name', (newName, oldName) => {
          this.selectedFieldInfo = this.availableFields.find(f => f.name == newName);
        });
        this.$watch('selectedFieldInfo', (newFieldInfo, oldFieldInfo) => {
          this.newField.updateWithFieldInformation(newFieldInfo)
          this.optionValues = utils.getFieldOptions(newFieldInfo);
          this.optionValuesListName = utils.getFieldOptions(newFieldInfo, true);
          if (this.optionValuesListName != '') {
            let lastDot = this.optionValuesListName.lastIndexOf('.');
            if (lastDot != -1) {
              this.optionValuesListName = this.optionValuesListName.substring(lastDot + 1);
            }
          }
        });
        this.$watch('availableValueTypes', (newArray) => {
          this.newField.value_type = newArray[0]?.id ?? '';
        });
        this.$watch('availableTypesInForm', (newArray) => {
          this.newField.type_in_form = newArray[0]?.id ?? '';
        });
        this.$watch('availableSubtypesInForm', (newArray) => {
          this.newField.subtype_in_form = newArray[0]?.id ?? '';
        });
        this.$watch('newField.value_type', (newType, oldType) => {
          if (newType != 'fixed') {
            this.newField.value = '';
          }
        });
        this.$watch('newField.subtype_in_form', (newType, oldType) => {
          this.newField.setValueOptions(this.optionValues);
        });
        this.$watch('optionValues', (newArray) => {
          if (this.newField.value_type == 'fixed') {
            this.newField.value = newArray[0]?.id ?? '';
          }
        });
      },

      openPopUp(idBase, single=true) {
        let mode = 'single';
        if (single!=true) {
          mode = 'MultiSelect';
        }
        let destModule = this.formConfig.getRelationshipModule(dataBlock.id, this.selectedFieldInfo.options);
        open_popup(destModule, 600, 400, '', true, false, 
                    { 
                      'field_to_name_array': {'id':`${idBase}_id${dataBlock.id}`, 'name':`${idBase}_name${dataBlock.id}`},
                      'call_back_function': 'handle_open_popup' 
                    }, mode, true);
      },

      handleStartAddField(typeField) {
        this.newField = new AWF_Field({ type_field: typeField });
        this.creatingFieldUnlinked = typeField=='unlinked';
        this.creatingFieldForm = typeField=='form';
        this.creatingFieldHidden = typeField=='hidden';
      },

      handleCreateField() {
        dataBlock.addField(this.newField);
        this.creatingFieldUnlinked = false;
        this.creatingFieldForm = false;
        this.creatingFieldHidden = false;
      },

      handleCancel() {
        this.creatingFieldUnlinked = false;
        this.creatingFieldForm = false;
        this.creatingFieldHidden = false;
      },
    };
  }

  static creationFieldInFormxData(dataBlock) {
    return {
      configValueOptions: false,
                  
      get isOptionValueModified() { return this.newField?.isOptionValueModified() ?? false; },
      get optionValuesListNameModified() {
        return this.optionValuesListName + (this.isOptionValueModified ? ' ' + utils.translate('LBL_FIELD_VALUE_OPTIONS_CUSTOMIZED') : '');
      },

      init() {
        this.$watch('newField.name', (newName, oldName) => {
          this.configValueOptions = false;
        });
      },
    };
  }
  
  static creationFieldHiddenxData(dataBlock) {
    return {
      get hasOptions() { return this.optionValues.length > 0; },
      get isRelated() { return this.newField?.type == 'relate'; },
      get isDate() { return this.newField?.type == 'date' || this.newField?.type == 'datetime' || this.newField?.type == 'datetimecombo'; },

      valueToday: false,

      init() {
        this.typeField = 'hidden';

        this.$watch('newField.value_type', (newType, oldType) => {
          this.valueToday = false;
        });
        this.$watch('valueToday', (newValue, oldValue) => {
          this.newField.value = newValue ? 'today' : '';
        });
        this.$watch('newField.value', (newValue, oldValue) => {
          if (this.hasOptions) {
            this.newField.value_text = this.optionValues.find(o => o.id == newValue)?.text ?? '';
          } else if(this.isDate) {
            if (newValue == 'today') {
              this.newField.value_text = utils.translate('LBL_FIELD_VALUE_TODAY');
            } else {
              this.newField.value_text = '';
              if (newValue) {
                const dateObj = new Date(newValue);
                this.newField.value_text = new Intl.DateTimeFormat(undefined, {year:'numeric',month:'2-digit',day:'2-digit'}).format(dateObj);
              }
            }
          } else if(!this.isRelated) {
            this.newField.value_text = newValue;
          }
        });
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
