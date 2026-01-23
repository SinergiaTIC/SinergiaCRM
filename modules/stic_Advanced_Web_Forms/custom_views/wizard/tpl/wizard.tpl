<script type="text/javascript" language="JavaScript">
    STIC.enabledModules = {$enabledModules};
    STIC.mainThemeColor = '{$mainThemeColor}';
    STIC.formMsgWarnings = {$msgWarnings|json_encode};
    STIC.isAdmin = {$isAdminUser|json_encode};
    STIC.record = {$beanJson};
</script>

{$title}
<div class="clear"></div>

<div id="stic_panel" class="stic_panel" x-data="wizardForm()" x-init="initWizard()">
    
    <div class="wizard-header flex-shrink-0">
        <div class="wizard-stepper mt-3 px-4 mb-3">
            <template x-for="step in navigation.stepsList" :key="step.id">
                <div class="wizard-step-item" 
                     :class="{literal}{ 'active': navigation.step === step.id, 'completed': navigation.step > step.id }{/literal}"
                     @click="WizardNavigation.goToStep(step.id)">                
                    <div class="wizard-step-icon">
                        <template x-if="navigation.step > step.id">
                            <span class="suitepicon suitepicon-action-confirm"></span>
                        </template>
                        <template x-if="navigation.step <= step.id">
                            <span :class="'suitepicon ' + step.icon"></span>
                        </template>
                    </div>
                    <div class="wizard-step-label" x-text="utils.translate(step.label)"></div>
                </div>
            </template>
        </div>

        <div x-show="STIC.formMsgWarnings!=''" class="alert alert-warning mx-4 p-2 shadow-sm small" x-text="STIC.formMsgWarnings" style="white-space: pre-wrap;"></div> 

        <div class="px-4 mb-2">
            <h2 class="d-flex mb-0">
                <span id="wizard-step-icon"></span>
                <span id='wizard-section-title'></span>
            </h2>
            <p id='wizard-section-desc' class="text-muted mb-0" style="font-size: 1em;"></p>
        </div>
    </div>

    <div class="wizard-card-wrapper mx-2 mb-2">
        <div class="wizard-container" id="wizard-step-container"></div>
        <div id="debug-container" class="p-2 bg-light border-top" style="display:none;" x-show="showDetailsData"></div>

        <div class="wizard-nav">
            <button type="button" class="button btn-lg" @click="WizardNavigation.prev()" x-bind:disabled="!WizardNavigation.enabled('prev')" x-text="utils.translate('LBL_WIZARD_PREVIOUS')"></button>
            <button type="button" class="button btn-lg btn-primary" @click="WizardNavigation.next()" x-show="navigation.step < navigation.totalSteps" x-bind:disabled="!WizardNavigation.enabled('next')" x-text="utils.translate('LBL_WIZARD_NEXT')"></button>
            <button type="button" class="button btn-lg btn-success" @click="WizardNavigation.finish()" x-show="navigation.step == navigation.totalSteps" x-text="utils.translate('LBL_WIZARD_FINISH')"></button>
        </div>
    </div>

</div>
