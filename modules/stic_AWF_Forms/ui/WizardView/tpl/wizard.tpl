{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 *}
 
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
    <!-- Warnings -->
    <div x-show="STIC.formMsgWarnings!=''" class="alert alert-warning mx-4 p-2 shadow-sm small" x-text="STIC.formMsgWarnings" style="white-space: pre-wrap;"></div> 

    <!-- Wizard Header -->
    <div class="wizard-header flex-shrink-0">
        <div class="d-flex flex-wrap align-items-center px-4 mt-3 mb-2 gap-4">
            <!-- Steps -->
            <div class="wizard-stepper flex-grow-1 mb-0" style="min-width: 300px;">
                <template x-for="step in navigation.stepsList" :key="step.id">
                    <div class="wizard-step-item" :class="{literal}{ 'active': navigation.step === step.id, 'completed': navigation.step > step.id }{/literal}"
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

            <!-- Save / Finish Buttons -->
            <div class="d-flex gap-2 ms-auto">
                <template x-if="navigation.step < navigation.totalSteps">
                    <button type="button" class="button btn-lg btn-success" 
                            @click="WizardNavigation.autoSave(); alert(utils.translate('LBL_WIZARD_SAVED_DRAFT'));" 
                            :disabled="bean.id == ''" x-text="utils.translate('LBL_WIZARD_SAVE')">
                    </button>
                </template>
                <template x-if="navigation.step == navigation.totalSteps">
                    <button type="button" class="button btn-lg btn-success" 
                            @click="WizardNavigation.finish()" x-text="utils.translate('LBL_WIZARD_FINISH')">
                    </button>
                </template>
            </div>
        </div>

        <div class="px-4 mb-1">
            <h2 class="d-flex mb-0">
                <span id="wizard-step-icon"></span>
                <span id='wizard-section-title'></span>
            </h2>
            <p id='wizard-section-desc' class="text-muted mb-0 small"></p>
        </div>
    </div>

    <div class="wizard-card-wrapper mx-2 mb-2">
        <div class="wizard-container wizard-main flex-fill d-flex flex-column" id="wizard-step-container"></div>
        {* <div id="debug-container" class="p-2 bg-light border-top" style="display:none;" x-show="showDetailsData"></div> *}

        <div class="wizard-nav">
            <button type="button" class="button btn-lg" @click="WizardNavigation.prev()" x-bind:disabled="!WizardNavigation.enabled('prev')" x-text="utils.translate('LBL_WIZARD_PREVIOUS')"></button>
            <button type="button" class="button btn-lg btn-primary" @click="WizardNavigation.next()" x-show="navigation.step < navigation.totalSteps" x-bind:disabled="!WizardNavigation.enabled('next')" x-text="utils.translate('LBL_WIZARD_NEXT')"></button>
            <button type="button" class="button btn-lg btn-success" @click="WizardNavigation.finish()" x-show="navigation.step == navigation.totalSteps" x-text="utils.translate('LBL_WIZARD_FINISH')"></button>
        </div>
    </div>

</div>
