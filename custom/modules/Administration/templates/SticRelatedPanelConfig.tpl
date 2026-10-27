{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * STIC-Custom 20261023 DEV - Related records panel
 * Administration configuration screen (tree: modules -> subpanels).
 * See stic-ai-handbook: output/features/related-records-panel
*}

{$title}

{if $SAVED}
    <div class="alert alert-success">{$MOD.LBL_RRP_SAVED}</div>
{/if}
{if $SAVE_ERROR}
    <div class="alert alert-danger">{$MOD.LBL_RRP_SAVE_ERROR}</div>
{/if}

<form method="post" action="index.php?module=Administration&amp;action=sticrelatedpanel" name="rrpConfig" id="rrp-config" class="stic-rrp-admin" onsubmit="return false;">
    {* The form is never submitted as-is: SticRelatedPanelAdmin.js builds a single
       JSON from the DOM and posts it (the full form would exceed PHP max_input_vars). *}

    {* ---------------------------------------------------- global options *}
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{$MOD.LBL_RRP_ADMIN_GLOBAL}</h3></div>
        <div class="panel-body">
            <div class="stic-rrp-admin-option">
                <label class="stic-rrp-admin-check">
                    <input type="checkbox" name="enabled" value="1" {if $CONFIG.enabled}checked{/if}>
                    <strong>{$MOD.LBL_RRP_ENABLED}</strong>
                </label>
                <div class="stic-rrp-admin-desc">{$MOD.LBL_RRP_ENABLED_DESC}</div>
            </div>
            <div class="stic-rrp-admin-option">
                <label class="stic-rrp-admin-check">
                    <input type="checkbox" name="pinned" value="1" {if $CONFIG.pinned}checked{/if}>
                    <strong>{$MOD.LBL_RRP_PINNED}</strong>
                </label>
                <div class="stic-rrp-admin-desc">{$MOD.LBL_RRP_PINNED_DESC}</div>
            </div>
            <div class="stic-rrp-admin-option">
                <label class="stic-rrp-admin-check">
                    <input type="checkbox" name="hide_empty" value="1" {if $CONFIG.hide_empty}checked{/if}>
                    <strong>{$MOD.LBL_RRP_HIDE_EMPTY}</strong>
                </label>
                <div class="stic-rrp-admin-desc">{$MOD.LBL_RRP_HIDE_EMPTY_DESC}</div>
            </div>
            <div class="stic-rrp-admin-option stic-rrp-admin-inline">
                <label for="rrp-max"><strong>{$MOD.LBL_RRP_MAX_PER_SECTION}</strong></label>
                <input type="number" id="rrp-max" name="max_per_section" min="1" max="30" value="{$CONFIG.max_per_section}" class="input-sm">
            </div>
        </div>
    </div>

    {* ------------------------------------------------- modules/subpanels *}
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{$MOD.LBL_RRP_MODULES_TREE}</h3></div>
        <div class="panel-body">
            <div class="stic-rrp-admin-desc stic-rrp-admin-tree-desc">
                {$MOD.LBL_RRP_MODULES_TREE_DESC}
            </div>

            {foreach from=$TREE item=M}
            <div class="stic-rrp-admin-module{if !$M.enabled} is-disabled{/if}" data-module="{$M.module}">
                <div class="stic-rrp-admin-module-head">
                    <input type="hidden" name="modules[{$M.module}][present]" value="1">
                    <label class="stic-rrp-admin-check">
                        <input type="checkbox" name="modules[{$M.module}][enabled]" value="1" {if $M.enabled}checked{/if}>
                    </label>
                    <span class="suitepicon {$M.icon}"></span>
                    <span class="stic-rrp-admin-module-label">{$M.label}</span>
                    <span class="stic-rrp-admin-count">{$M.subpanels|count}</span>
                    <span class="suitepicon suitepicon-action-caret stic-rrp-admin-caret"></span>
                </div>
                <div class="stic-rrp-admin-module-body">

                    {foreach from=$M.subpanels item=S}
                    <div class="stic-rrp-admin-row" draggable="true" data-key="{$S.key}">
                        <input type="hidden" name="modules[{$M.module}][subpanels][{$S.key}][present]" value="1">
                        <span class="suitepicon suitepicon-action-sorting-none stic-rrp-admin-handle" title="{$MOD.LBL_RRP_ORDER_HINT}"></span>
                        <label class="stic-rrp-admin-check">
                            <input type="checkbox" name="modules[{$M.module}][subpanels][{$S.key}][enabled]" value="1" {if $S.config.enabled}checked{/if}>
                        </label>
                        <span class="suitepicon {$S.icon}"></span>
                        <span class="stic-rrp-admin-row-title">{$S.title}</span>
                        <span class="stic-rrp-admin-row-key">{$S.key}</span>
                        <button type="button" class="stic-rrp-admin-expand">
                            <span class="suitepicon suitepicon-action-edit"></span>
                        </button>

                        <div class="stic-rrp-admin-config">
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_TITLE_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][title_field]">
                                    <option value="" {if !$S.config.title_field}selected{/if}>{$MOD.LBL_RRP_AUTO}</option>
                                    {foreach from=$S.fields item=F}
                                        <option value="{$F.name}" {if $S.config.title_field == $F.name}selected{/if}>{$F.label}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_SUBTITLE_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][subtitle_field]">
                                    <option value="" {if !$S.config.subtitle_field}selected{/if}>{$MOD.LBL_RRP_AUTO}</option>
                                    {foreach from=$S.fields item=F}
                                        <option value="{$F.name}" {if $S.config.subtitle_field == $F.name}selected{/if}>{$F.label}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_RIGHT_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][right_field]">
                                    <option value="" {if !$S.config.right_field}selected{/if}>{$MOD.LBL_RRP_AUTO_DATE}</option>
                                    {foreach from=$S.fields item=F}
                                        <option value="{$F.name}" {if $S.config.right_field == $F.name}selected{/if}>{$F.label}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_SORT_BY}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][sort_field]">
                                    {foreach from=$SORT_OPTIONS item=O}
                                        <option value="{$O.value}" {if $S.config.sort_field == $O.value}selected{/if}>{$O.label}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_SORT_DIR}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][sort_dir]">
                                    {foreach from=$DIR_OPTIONS item=O}
                                        <option value="{$O.value}" {if $S.config.sort_dir == $O.value}selected{/if}>{$O.label}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    {/foreach}

                </div>
            </div>
            {/foreach}
        </div>
    </div>

    <div class="stic-rrp-admin-actions">
        <input type="button" class="button primary" id="rrp-save" value="{$MOD.LBL_RRP_SAVE}" data-error-text="{$MOD.LBL_RRP_SAVE_ERROR}">
        <span class="stic-rrp-admin-saving" id="rrp-saving" style="display:none;">{$MOD.LBL_RRP_SAVING}</span>
        <input type="button" class="button" onclick="window.location='index.php?module=Administration&amp;action=index';" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
    </div>
</form>

{sugar_getscript file='custom/modules/Administration/js/SticRelatedPanelAdmin.js'}
