{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *}

<div class="moduleTitle">
    <h2 class="module-title-text">{$MOD.LBL_STIC_RECORD_TITLES_LINK_TITLE}</h2>
    <div class="clear"></div>
</div>

<p class="help-text">{$MOD.LBL_STIC_RECORD_TITLES_HELP}</p>

<form name="SticRecordTitlesForm" method="POST" action="index.php?module=Administration&action=sticSaveRecordTitles">
    <table class="edit view" style="margin-top:10px; width:100%;">
        <thead>
            <tr>
                <th scope="col" width="40%">{$MOD.LBL_STIC_RECORD_TITLES_MODULE}</th>
                <th scope="col" width="60%">{$MOD.LBL_STIC_RECORD_TITLES_TEMPLATE}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$MODULES key=moduleName item=moduleLabel}
            <tr>
                <td>{$moduleLabel} <span style="color:#888;">({$moduleName})</span></td>
                <td>
                    <select name="templates[{$moduleName}]">
                        <option value="">{$MOD.LBL_STIC_RECORD_TITLES_NONE}</option>
                        {foreach from=$TEMPLATES key=templateId item=templateName}
                        <option value="{$templateId}" {if $MAPPING[$moduleName] == $templateId}selected{/if}>{$templateName}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    <div style="margin-top:12px;">
        <input type="submit" class="button primary" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
    </div>
</form>
