{$title}
<div class="clear"></div>

<div id="stic_AWF_editor" x-data="wizardForm(null,null,{$readOnly})" x-init="init()">
    <div class="wizard-nav">
        <button type="button" class="button" @click="prevStep()" x-bind:disabled="!enablePrevStep()" x-text="modStrings.LBL_WIZARD_PREVIOUS"></button>
        <button type="button" class="button" @click="nextStep()" x-bind:disabled="!enableNextStep()" x-text="modStrings.LBL_WIZARD_NEXT"></button>
        <template x-if="!isReadOnly">
            <button type="button" class="button" @click="finish()" x-text="modStrings.LBL_WIZARD_FINISH"></button>
        </template>
    </div>

    <h2 id='wizard-section-title'></h2>
    <div class="card container-fluid wizard-container">
        <div class="card-body d-flex h-100" id="wizard-step-container"></div>
    </div>

    <div class="wizard-nav">
        <button type="button" class="button" @click="prevStep()" x-bind:disabled="!enablePrevStep()" x-text="modStrings.LBL_WIZARD_PREVIOUS"></button>
        <button type="button" class="button" @click="nextStep()" x-bind:disabled="!enableNextStep()" x-text="modStrings.LBL_WIZARD_NEXT"></button>
        <template x-if="!isReadOnly">
            <button type="button" class="button" @click="finish()" x-text="modStrings.LBL_WIZARD_FINISH"></button>
        </template>
    </div>
</div>
