{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * Administration configuration screen (tree: modules -> subpanels).
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

    {* ------------------------------------------- actions (top, always visible) *}
    <div class="stic-rrp-admin-actions">
        <input type="button" class="button primary js-rrp-save" value="{$MOD.LBL_RRP_SAVE}" data-error-text="{$MOD.LBL_RRP_SAVE_ERROR}">
        <span class="stic-rrp-admin-saving js-rrp-saving" style="display:none;">{$MOD.LBL_RRP_SAVING}</span>
        <input type="button" class="button js-rrp-cancel" data-cancel-url="index.php?module=Administration&amp;action=index" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
    </div>

    {* ---------------------------------------------------- global options *}
    <table class="edit view" style="margin-top:10px;">
        <tbody id="rrp-global">
        <tr>
            <th colspan="4" scope="row"><h4>{$MOD.LBL_RRP_ADMIN_GLOBAL}</h4></th>
        </tr>
        <tr>
            <td width="25%" scope="row" valign="middle">
                {$MOD.LBL_RRP_ENABLED}
            </td>
            <td width="75%" colspan="3" valign="middle">
                <input type="hidden" name="enabled" value="0"><input type="checkbox" name="enabled" value="1" {if $CONFIG.enabled}checked{/if}>
                <div class="stic-rrp-admin-desc">{$MOD.LBL_RRP_ENABLED_DESC}</div>
            </td>
        </tr>
        <tr>
            <td width="25%" scope="row" valign="middle">
                {$MOD.LBL_RRP_PINNED}
            </td>
            <td width="75%" colspan="3" valign="middle">
                <input type="hidden" name="pinned" value="0"><input type="checkbox" name="pinned" value="1" {if $CONFIG.pinned}checked{/if}>
                <div class="stic-rrp-admin-desc">{$MOD.LBL_RRP_PINNED_DESC}</div>
            </td>
        </tr>
        </tbody>
    </table>

    {* ------------------------------------------------- modules/subpanels *}
    <table class="edit view" style="margin-top:10px;">
        <tbody>
        <tr>
            <th colspan="4" scope="row"><h4>{$MOD.LBL_RRP_MODULES_TREE}</h4></th>
        </tr>
        <tr>
            <td colspan="4">
            <div class="stic-rrp-admin-desc stic-rrp-admin-tree-desc">
                {$MOD.LBL_RRP_MODULES_TREE_DESC}
            </div>

            <div class="stic-rrp-admin-toolbar">
                <a href="#" class="stic-rrp-admin-link js-rrp-select-all">{$MOD.LBL_RRP_SELECT_ALL}</a>
                <span class="stic-rrp-admin-link-sep">|</span>
                <a href="#" class="stic-rrp-admin-link js-rrp-select-none">{$MOD.LBL_RRP_SELECT_NONE}</a>
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
                    <div class="stic-rrp-admin-toolbar stic-rrp-admin-toolbar-module">
                        <a href="#" class="stic-rrp-admin-link js-rrp-select-all-module">{$MOD.LBL_RRP_SELECT_ALL}</a>
                        <span class="stic-rrp-admin-link-sep">|</span>
                        <a href="#" class="stic-rrp-admin-link js-rrp-select-none-module">{$MOD.LBL_RRP_SELECT_NONE}</a>
                    </div>

                    {foreach from=$M.subpanels item=S}
                    <div class="stic-rrp-admin-row" draggable="true" data-key="{$S.key}">
                        <input type="hidden" name="modules[{$M.module}][subpanels][{$S.key}][present]" value="1">
                        <span class="suitepicon suitepicon-action-sorting-none stic-rrp-admin-handle" title="{$MOD.LBL_RRP_ORDER_HINT}"></span>
                        <label class="stic-rrp-admin-check">
                            <input type="checkbox" name="modules[{$M.module}][subpanels][{$S.key}][enabled]" value="1" {if $S.config.enabled}checked{/if}>
                        </label>
                        <span class="suitepicon {$S.icon}"></span>
                        <span class="stic-rrp-admin-row-title">{$S.title}</span>
                        <button type="button" class="stic-rrp-admin-expand" title="{$MOD.LBL_RRP_CONFIGURE_RELATION}" aria-label="{$MOD.LBL_RRP_CONFIGURE_RELATION}" aria-expanded="false">
                            <span class="glyphicon glyphicon-cog"></span>
                        </button>
                        <span class="stic-rrp-admin-row-key">{$S.key}</span>

                        <div class="stic-rrp-admin-config">
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_DATE_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][date_field]">
                                    <option value="" {if !$S.config.date_field}selected{/if}>{$S.defaults.date_label} ({$MOD.LBL_RRP_DEFAULT_SUFFIX})</option>
                                    {foreach from=$S.date_fields item=F}
                                        {if $F.name != $S.defaults.date_field}
                                            <option value="{$F.name}" {if $S.config.date_field == $F.name}selected{/if}>{$F.label}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_TITLE_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][title_field]">
                                    <option value="" {if !$S.config.title_field}selected{/if}>{$S.defaults.title_label} ({$MOD.LBL_RRP_DEFAULT_SUFFIX})</option>
                                    {foreach from=$S.fields item=F}
                                        {if $F.name != $S.defaults.title_field}
                                            <option value="{$F.name}" {if $S.config.title_field == $F.name}selected{/if}>{$F.label}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                            <div class="stic-rrp-admin-field">
                                <label>{$MOD.LBL_RRP_SUBTITLE_FIELD}</label>
                                <select name="modules[{$M.module}][subpanels][{$S.key}][subtitle_field]">
                                    <option value="" {if !$S.config.subtitle_field}selected{/if}>{$S.defaults.subtitle_label} ({$MOD.LBL_RRP_DEFAULT_SUFFIX})</option>
                                    {foreach from=$S.fields item=F}
                                        {if $F.name != $S.defaults.subtitle_field}
                                            <option value="{$F.name}" {if $S.config.subtitle_field == $F.name}selected{/if}>{$F.label}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    {/foreach}

                </div>
            </div>
            {/foreach}
            </td>
        </tr>
        </tbody>
    </table>

    <div class="stic-rrp-admin-actions">
        <input type="button" class="button primary js-rrp-save" value="{$MOD.LBL_RRP_SAVE}" data-error-text="{$MOD.LBL_RRP_SAVE_ERROR}">
        <span class="stic-rrp-admin-saving js-rrp-saving" style="display:none;">{$MOD.LBL_RRP_SAVING}</span>
        <input type="button" class="button js-rrp-cancel" data-cancel-url="index.php?module=Administration&amp;action=index" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
    </div>
</form>

{sugar_getscript file='custom/modules/Administration/js/SticRelatedPanelAdmin.js'}
