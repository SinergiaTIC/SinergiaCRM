function wizardForm(readOnly) {
    return {
        step: 1,
        totalSteps: 4,
        steps: [],
    
        isReadOnly: readOnly,
    
        bean: STIC.record || {},
        config: JSON.parse(this.bean?.config_json || '{}'),
    
        modStrings: SUGAR.language.languages.stic_Advanced_Web_Forms,
        appStrings: SUGAR.language.languages.app_strings,
        appListStrings: SUGAR.language.languages.app_list_strings,
    
        async init() {
            window.alpineComponent = this;
            this.loadStep();
        },
    
        async loadStep() {
            if(this.step <= this.totalSteps && this.steps.length < this.step+1) {
                this.steps[this.step] = await (await fetch(`modules/stic_Advanced_Web_Forms/wizard/steps/step${this.step}.html`)).text();
            }
            document.getElementById('wizard-step-container').innerHTML = this.steps[this.step];
        },
    
        enablePrevStep() {
            return this.step > 1;
        },
        enableNextStep() {
            return this.step < this.totalSteps;
        },
        nextStep() { 
            if (this.enableNextStep()) {
                this.step++;
                this.autoSave(); 
                this.loadStep();
            };
        },
        prevStep() { 
            if (this.enablePrevStep()) {
                this.step--; 
                this.loadStep();
            };
        },
    
        autoSave() {
            if (!this.isReadOnly) {
                fetch('index.php?module=stic_Advanced_Web_Forms&action=saveDraft', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({bean: this.bean, config: this.config, step: this.step})
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
    };
}

function set_wizard_assigned_user(popup_reply_data) {
    window.alpineComponent.bean.assigned_user_id = popup_reply_data.name_to_value_array.assigned_user_id;
    window.alpineComponent.bean.assigned_user_name = popup_reply_data.name_to_value_array.assigned_user_name;
}