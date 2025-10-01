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
      async loadDatablockRelationships() {
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
    })
  }
}
