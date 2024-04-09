<form id='seven_sms_form'>
    <input name='bean_id' type='hidden' value='{$SEVEN_BEAN_ID}'/>
    <input name='module' type='hidden' value='{$SEVEN_MODULE}'/>

    <div class='form-group'>
        <label for='seven_from'>{$MOD.LBL_SEVEN_FROM}</label>
        <input
                class='form-control'
                id='seven_from'
                maxlength='16'
                name='seven_from'
                value='{$SEVEN_FROM}'
        />
    </div>

    <div class='form-group'>
        <label for='seven_to'>
            {$MOD.LBL_SEVEN_TO}
            <p class='help-block'>
                <small>{$MOD.LBL_SEVEN_TO_HELP}</small>
            </p>
        </label>
        <input class='form-control' id='seven_to' name='seven_to' required value='{$SEVEN_TO}'/>
    </div>

    {* STIC-Custom 20240404 EPS *}
    <div class='form-group'>
        <label for='seven_template'>
            {$MOD.LBL_SEVEN_TEMPLATE}
        </label>
        <div>
            <input type='text' class='sqsEnabled' name='seven_template_name'
                id='seven_template_name' autocomplete='off'
                value='{$seven_template_name}' title='' tabindex='3'>
            <input type='hidden' name='seven_template_id'
                id='seven_template_id'
                value='{$seven_template_id}'>
            <span class='id-ff multiple'>
                <button title='{$MOD.LBL_SELECT_BUTTON_TITLE}' type='button'
                    class='button' name='btn_1'
                    onclick='openSelectPopup("EmailTemplates", "seven_template")'>
                    <span class='suitepicon suitepicon-action-select'></span>
                </button>
                <button type='button' name='btn_1' class='button lastChild'
                    onclick='clearRow(this.form, "seven_template")'>
                
                    <span class='suitepicon suitepicon-action-clear'></span>
                </button>
            </span>
        </div>
    </div>
    {* END STIC-Custom *}



    <div class='form-group'>
        <label for='seven_text'>{$MOD.LBL_SEVEN_TEXT}</label>
        <textarea
                {* STIC-Custom 202404040 EPS *}
                {* class='form-control' id='seven_text' name='seven_text' required rows='8' *}
                class='form-control' id='seven_text' name='seven_text' rows='8'
                {* END STIC-Custom *}
        ></textarea>
    </div>

    <button class='btn btn-primary btn-block button-padding' type='submit'>
        {$MOD.LBL_SEVEN_SEND_SMS}
    </button>
</form>

<p id='seven_notification'></p>
