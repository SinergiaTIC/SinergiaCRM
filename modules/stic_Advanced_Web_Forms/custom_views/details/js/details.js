function DetailsForm() {
  return {
    navigation: {
      step: 1,
      totalSteps: 1,
    },

    bean: STIC.record || {},
    formConfig: {},

    async initDetails() {
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
      DetailsNavigation.loadStep();
    },
  };
}
class DetailsNavigation {
  static cacheSteps = [];

  static async loadStep() {
    const step = window.alpineComponent.navigation.step;
    const totalSteps = window.alpineComponent.navigation.totalSteps;

    if (step <= 0 || step > totalSteps) {
      return;
    }

    // Step content
    if (!(step in DetailsNavigation.cacheSteps)) {
      DetailsNavigation.cacheSteps[step] = await (
        await fetch(`modules/stic_Advanced_Web_Forms/custom_views/details/steps/step${step}.html`)
      ).text();
    }
    let $el = document.getElementById("wizard-step-container");
    $el.innerHTML = DetailsNavigation.cacheSteps[step];

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
    if (DetailsNavigation.enabled("prev")) {
      window.alpineComponent.navigation.step--;
      DetailsNavigation.loadStep();
    }
  }

  static next() {
    if (DetailsNavigation.enabled("next")) {
      let allOk = true;
      document.querySelectorAll("#wizard-step-container form.needs-validation").forEach(function (f) {
        allOk &= f.reportValidity();
      });
      if (allOk) {
        window.alpineComponent.navigation.step++;
        DetailsNavigation.autoSave();
        DetailsNavigation.loadStep();
      }
    }
  }
}

class DetailsStep1 {
  static mainStep1xData() {
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