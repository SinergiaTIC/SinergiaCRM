{include file='modules/seven/tpls/message_templates.tpl'}

<form action='index.php' enctype='multipart/form-data' method='POST'>
    <input type='hidden' name='module' value='seven'/>
    <input type='hidden' name='action' value='{block name=action}{/block}'/>

    <span class='error'>{$error.main}</span>

    <table>
        {block name=table}{/block}
        <tr>
            <td><label for='seven_text'>{$MOD.LBL_SEVEN_TEXT}</label></td>
            <td>
                {block name=text_var}{/block}

                <textarea
                        cols='80'
                        id='seven_text'
                        name='{$seven_text_name}'
                        rows='10'
                        tabindex='0'
                >{$seven_text_content}</textarea>
            </td>
        </tr>
    </table>

    <p>
        <span class='text-danger'>{$MOD.LBL_SEVEN_NOTICE}: </span>
        {$MOD.LBL_SEVEN_SMS_ON_CREATION}
    </p>

    <p>
        <span class='text-danger'>{$MOD.LBL_SEVEN_PROPERTY_PLACEHOLDERS}:</span>
        <br/>
        {$placeholders}
    </p>

    <div>
        <input
                class='button'
                name='save'
                title='{$APP.LBL_SAVE_BUTTON_TITLE}'
                type='submit'
                value='{$APP.LBL_SAVE_BUTTON_LABEL}'
        />

        <input
                class='button'
                name='cancel'
                onclick='document.location.href="index.php?module=Administration&action=index"'
                title='{$MOD.LBL_CANCEL_BUTTON_TITLE}'
                type='button'
                value='{$APP.LBL_CANCEL_BUTTON_LABEL}'
        />
    </div>
</form>
