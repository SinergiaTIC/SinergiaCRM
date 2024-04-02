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

    <div class='form-group'>
        <label for='seven_text'>{$MOD.LBL_SEVEN_TEXT}</label>
        <textarea
                class='form-control' id='seven_text' name='seven_text' required rows='8'
        ></textarea>
    </div>

    <button class='btn btn-primary btn-block button-padding' type='submit'>
        {$MOD.LBL_SEVEN_SEND_SMS}
    </button>
</form>

<p id='seven_notification'></p>
