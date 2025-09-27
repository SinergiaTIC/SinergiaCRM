<script type="text/javascript" language="JavaScript">
    STIC.enabledModules = {$enabledModules};
</script>

{$title}
<div class="clear"></div>

<div id="stic_panel" x-data="wizardForm(null,null,{$readOnly})" x-init="initWizard()">
    <div class="wizard-nav">
        <button type="button" class="button" @click="WizardNavigation.prev()" x-bind:disabled="!WizardNavigation.enabled('prev')" x-text="utils.translate('LBL_WIZARD_PREVIOUS')"></button>
        <button type="button" class="button" @click="WizardNavigation.next()" x-bind:disabled="!WizardNavigation.enabled('next')" x-text="utils.translate('LBL_WIZARD_NEXT')"></button>
        <button type="button" class="button" @click="WizardNavigation.finish()" x-text="utils.translate('LBL_WIZARD_FINISH')"></button>
    </div>

    <h2 id='wizard-section-title'></h2>
    <div class="card container-fluid wizard-container">
        <div class="card-body d-flex h-100" id="wizard-step-container"></div>
    </div>

    <div class="wizard-nav">
        <button type="button" class="button" @click="WizardNavigation.prev()" x-bind:disabled="!WizardNavigation.enabled('prev')" x-text="utils.translate('LBL_WIZARD_PREVIOUS')"></button>
        <button type="button" class="button" @click="WizardNavigation.next()" x-bind:disabled="!WizardNavigation.enabled('next')" x-text="utils.translate('LBL_WIZARD_NEXT')"></button>
        <button type="button" class="button" @click="WizardNavigation.finish()" x-text="utils.translate('LBL_WIZARD_FINISH')"></button>
    </div>

</div>
