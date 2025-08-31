function wizardForm(jsonBeanData, jsonConfig, readOnly) {
    return {
        step: 1,
        totalSteps: 3,
        beanData: jsonBeanData || {},
        config: jsonConfig || {},
        steps: [],
        isReadOnly:readOnly,

        async init() {
            this.loadStep();
        },

        async loadStep() {
            if(this.step <= this.totalSteps && this.steps.length < this.step+1) {
                this.steps[this.step] = await (await fetch(`modules/stic_Advanced_Web_Forms/wizard/steps/step${this.step}.html`)).text();
            }
            document.getElementById('wizard-step-container').innerHTML = this.steps[this.step];
        },

        is_firstStep() {
            return this.step == 1;
        },
        is_lastStep() {
            return this.step == this.totalSteps;
        },

        nextStep() { 
            if (!this.is_lastStep()) {
                this.step++;
                this.autoSave(); 
                this.loadStep();
            };
        },
        prevStep() { 
            if (!this.is_firstStep()) {
                this.step--; 
                this.loadStep();
            };
        },

        autoSave() {
            if (!this.isReadOnly) {
                fetch('index.php?module=stic_Advanced_Web_Forms&action=saveDraft', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({beanData: this.beanData, config: this.config, step: this.step})
                });
            }
        },

        finish() {
            if (!this.isReadOnly) {
                fetch('index.php?module=stic_Advanced_Web_Forms&action=finalizeConfig', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({beanData: this.beanData, config: this.config, step: this.step})
                }).then(() => location.reload());
            }
        }
    }
}


window.WizardData = {
    step: 1
};