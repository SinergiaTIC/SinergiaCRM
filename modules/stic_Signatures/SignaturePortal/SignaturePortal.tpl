<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180"
        href="modules/stic_Signatures/SignaturePortal/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="modules/stic_Signatures/SignaturePortal/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
        href="modules/stic_Signatures/SignaturePortal/favicon/favicon-16x16.png">
    <link rel="manifest" href="modules/stic_Signatures/SignaturePortal/favicon/site.webmanifest">
    <script type="text/javascript">
        // load module language strings
        var MODS = {$MODS|@json_encode};
    </script>
    <title>{$MODS.LBL_PORTAL_TITLE_PAGE}</title>
    {$BOOTSTRAP_CSS}
    {$BOOTSTRAP_JS}
    {$BOOTSTRAP_ICONS}
    {$STYLESHEETS}
</head>

<body class="bg-light font-sans antialiased text-dark">

    <header class="p-4 p-sm-6 shadow-sm d-flex flex-column" style="background-color: {$HEADER_COLOR};">
        <div class="container-fluid d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <div class="w-100 w-sm-100 text-center mb-2 mb-sm-0" style="max-height: 50%;">
                { if isset($LOGO_URL) && !empty($LOGO_URL) }
                <img src="{$LOGO_URL}" alt="{$MODS.LBL_PORTAL_ORG_LOGO_ALT}" class="w-auto" style="max-height: 50px;">
                {/if}
            </div>
            <div class="w-100 w-sm-50 d-flex flex-column  align-items-sm-start text-center">
                <h2 class="text-white fs-5 text-base font-weight-bold tracking-tight mb-0">{$MODS.LBL_PORTAL_SIGNATURES}
                </h2>
                { if isset($ORGANIZATION_NAME) && !empty($ORGANIZATION_NAME) }
                <h3 class="text-white fs-6 font-weight-bold tracking-tight mt-1 mb-0">
                    {$ORGANIZATION_NAME}
                </h3>
                {/if}
            </div>
        </div>
    </header>
    <main class="flex-grow">
        { if isset($ERROR_MSG) && !empty($ERROR_MSG) }
        <div class="stic-container container mt-4 mx-auto">
            <section class="error-area">
                <h1 class="text-3xl font-weight-bold mb-4 text-center text-danger">{$MODS.LBL_PORTAL_ERROR}</h1>
                <div class="alert alert-danger" role="alert">
                    <strong>{$MODS.LBL_PORTAL_ERROR}:</strong> {$ERROR_MSG}
                </div>
                <p class="text-center">
                    {$MODS.LBL_PORTAL_ERROR_MSG}
                </p>
                <div class="text-center mt-3">
                    <a href="javascript:history.back()" class="btn btn-secondary">{$MODS.LBL_PORTAL_BTN_BACK}</a>
                </div>
            </section>
            {else}
            <div class="stic-container container mt-4 max-w-md mx-auto">
                { if $SHOW_PORTAL === true }
                { if $STATUS === 'pending' }
                <section class="document-area">
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-primary">{$MODS.LBL_PORTAL_DOC_SIGNATURE}
                    </h1>
                    <div id="documentContent" class="document-scroll-content">
                        {$DOCUMENT_HTML_CONTENT}
                    </div>
                    <div id="scrollInstructionMessage"
                        class="scroll-instruction-message alert alert-info mt-3 text-center">
                        <span class="d-block d-sm-inline">{$MODS.LBL_PORTAL_SCROLL_INFO}</span>
                    </div>
                </section>

                { if $SIGNATURE_MODE === 'handwritten' }
                <section class="signature-area mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">{$MODS.LBL_PORTAL_SIGNATURE_AREA}
                    </h2>
                    <p class="text-sm text-secondary mb-3 text-center">
                        {$MODS.LBL_PORTAL_HANDWRITTEN_INSTRUCTION}
                    </p>

                    <div class="d-flex justify-content-center">
                        <canvas id="signatureCanvas"></canvas>
                    </div>

                    <div class="action-buttons d-flex justify-content-center gap-2 mt-3">
                        <button id="clearSignatureBtn" class="btn btn-secondary">{$MODS.LBL_PORTAL_BTN_CLEAR}</button>
                        <button id="saveSignatureBtn"
                            class="btn btn-primary">{$MODS.LBL_PORTAL_BTN_SAVE_SIGNATURE}</button>
                    </div>

                    <div class="signature-alternatives w-100 mt-4 pt-3 border-top border-secondary">
                        <h3 class="text-xl font-weight-bold mb-4 text-center text-dark">
                            {$MODS.LBL_PORTAL_ALTERNATIVE_OPTIONS}
                        </h3>

                        <div class="text-signature-option mb-3 p-3 border border-secondary rounded bg-light">
                            <h4 class="text-lg font-weight-semibold mb-3 text-dark">
                                {$MODS.LBL_PORTAL_TEXT_SIGNATURE_TITLE}</h4>
                            <div class="mb-3">
                                <label for="textSignatureInput"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">{$MODS.LBL_PORTAL_FULL_NAME}:</label>
                                <input type="text" id="textSignatureInput" placeholder="{$MODS.LBL_PORTAL_NAME_EXAMPLE}"
                                    class="form-control text-dark" value="{$SIGNER_NAME}">
                            </div>
                            <div class="mb-3">
                                <label for="fontSelector"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">{$MODS.LBL_PORTAL_FONT_STYLE}:</label>
                                <select id="fontSelector" class="form-select text-dark">
                                    <option value="Dancing Script">Dancing Script</option>
                                    <option value="Pacifico">Pacifico</option>
                                    <option value="Great Vibes">Great Vibes</option>
                                    <option value="Caveat">Caveat</option>
                                    <option value="Indie Flower">Indie Flower</option>
                                </select>
                            </div>
                            <button id="renderTextSignatureBtn"
                                class="btn btn-secondary w-100">{$MODS.LBL_PORTAL_RENDER_TEXT_SIGNATURE}</button>
                        </div>

                        <div class="image-signature-option p-3 border border-secondary rounded bg-light">
                            <h4 class="text-lg font-weight-semibold mb-3 text-dark">
                                {$MODS.LBL_PORTAL_UPLOAD_IMAGE_TITLE}</h4>
                            <div class="mb-3">
                                <label for="imageSignatureInput"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">{$MODS.LBL_PORTAL_IMAGE_FILE_SELECTION}:</label>
                                <input type="file" id="imageSignatureInput" accept="image/png, image/jpeg"
                                    class="form-control text-dark">
                            </div>
                            <button id="uploadImageSignatureBtn"
                                class="btn btn-secondary w-100">{$MODS.LBL_PORTAL_UPLOAD_IMAGE_SIGNATURE}</button>
                        </div>
                    </div>
                </section>
                { /if }

                { if $SIGNATURE_MODE === 'button' }
                <section id="buttonAcceptationArea" class="mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">{$MODS.LBL_PORTAL_ACCEPTANCE_AREA}
                    </h2>
                    <p class="text-sm text-secondary mb-3 text-center">
                        {$MODS.LBL_PORTAL_ACCEPTANCE_INSTRUCTION}
                    </p>

                    <div class="action-buttons d-flex justify-content-center mt-3">
                        <button id="acceptDocumentBtn" class="btn btn-primary w-100 max-w-sm"
                            disabled>{$MODS.LBL_PORTAL_ACCEPT_AND_SIGN_BTN}</button>
                    </div>
                </section>
                { /if }
                { /if }


                { if $STATUS === 'signed' }
                <section class="signed-area text-center mt-4">
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-success">{$MO.LBL_PORTAL_DOCUMENT_SIGNED}
                    </h1>
                    <div class="text-center mb-4"><i class="bi bi-check-circle-fill text-success text-center"
                            style="font-size: 4rem;"></i></div>
                    <a class="btn btn-primary mt-3" href="{$DOWNLOAD_URL}" target="_blank" rel="noopener noreferrer"><i
                            class="bi bi-cloud-download-fill"></i> {$MODS.LBL_PORTAL_DOWNLOAD_SIGNED_DOC}</a>
                    <button id="send-signed-pdf-by-email" class="btn btn-primary mt-3"><i
                            class="bi bi-envelope-at-fill"></i> {$MODS.LBL_PORTAL_SEND_COPY_EMAIL}</button>
                    <p class="text-sm text-secondary mt-3">
                        {$MODS.LBL_PORTAL_VERIFICATION_CODE_INFO}
                    </p>
                    <div class="verification-code bg-light p-2 rounded mt-2 d-inline-block">
                        <code class="text-break font-monospace">{$SIGNER_VERIFICATION_CODE}</code> <i
                            class="bi bi-clipboard"
                            onclick="navigator.clipboard.writeText('{$SIGNER_VERIFICATION_CODE}');alert('{$MODS.LBL_PORTAL_TEXT_COPIED}');"
                            style="cursor: pointer;" title="{$MODS.LBL_PORTAL_COPY_TO_CLIPBOARD}"></i>
                    </div>


                </section>
                { /if }

                { if $STATUS === 'unnecessary' }
                <section class="unnecessary-area text-center mt-4">
                    <div class="text-center mb-4"><i class="bi bi-info-circle-fill text-info text-center"
                            style="font-size: 4rem;"></i></div>
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-info">{$MODS.LBL_PORTAL_UNNECESSARY_TEXT}
                    </h1>
                </section>

                { /if }




                { if $SHOW_LOGS === true }
                <section class="logs-area mt-5">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">{$MODS.LBL_PORTAL_ACTIONS_LOG}</h2>

                    { if isset($SIGNER_LOG) && !empty($SIGNER_LOG) }
                    <div class="mb-5">
                        <h3 class="text-xl font-weight-semibold mb-3 text-dark">{$MODS.LBL_PORTAL_SIGNER_ACTIONS}</h3>
                        <ul class="list-group">
                            { foreach from=$SIGNER_LOG item=logEntry }
                            <li class="list-group-item">
                                <strong>{$logEntry.date}</strong> - {$logEntry.action}
                                { if !empty($logEntry.description) }

                                <div class="text-secondary small ">
                                    {$logEntry.description}
                                </div>
                                {/if}
                            </li>
                            { /foreach }
                        </ul>
                    </div>
                    { /if }
                </section>
                { /if }


                {$JAVASCRIPT}
                {/if}

                { if $OTP_REQUIRED === true && $SHOW_PORTAL !== true }
                <section class="otp-area max-w-md mx-auto mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">
                        {$MODS.LBL_PORTAL_VERIFICATION_CODE}</h2>
                    { if isset($OTP_ERROR_MSG) && !empty($OTP_ERROR_MSG) }
                    <p class="alert alert-danger mb-4">
                        <strong>{$MODS.LBL_PORTAL_ERROR}:</strong> {$OTP_ERROR_MSG}
                    </p>
                    {/if}
                    {if !$OTP_SENT}
                    <div class="alert alert-info ">
                        {$MODS.LBL_PORTAL_OTP_INSTRUCTION}
                    </div>
                    <div class="d-flex align-items-center btn-group justify-content-center mb-4" role="group"
                        aria-label="Resend OTP Options">

                        {if $AUTH_MODE == 'otp_email' || $AUTH_MODE == 'otp' }
                        <button id="resend-otp-btn-email" class="btn btn-light">
                            <i class="bi bi-envelope-at"></i> {$MODS.LBL_PORTAL_OTP_SEND_CODE_BY_EMAIL}
                            {$OTP_MASKED_EMAIL}
                        </button>
                        {/if}
                        {if $AUTH_MODE == 'otp_phone_message' || $AUTH_MODE == 'otp' }
                        <button id="resend-otp-btn-phone-message" class="btn btn-light">
                            <i class="bi bi-telephone"></i> {$MODS.LBL_PORTAL_OTP_SEND_CODE_BY_PHONE_MESSAGE}
                            {$OTP_MASKED_PHONE}
                        </button>
                        {/if}
                    </div>
                    {/if}

                    <form id="otpForm" method="post" action="">
                        <div class="mb-3">
                            <div class="d-flex justify-content-center space-x-2">
                                <input type="text" id="otp-1" name="otp_code_1" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="text" id="otp-2" name="otp_code_2" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="text" id="otp-3" name="otp_code_3" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="text" id="otp-4" name="otp_code_4" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="text" id="otp-5" name="otp_code_5" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="text" id="otp-6" name="otp_code_6" maxlength="1" required
                                    class="otp-input form-control text-center text-xl font-weight-bold">
                                <input type="hidden" id="otp-code" name="otp-code" maxlength="6">
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary py-2 px-3 mt-3 mt-lg-0 ml-lg-3">
                                {$MODS.LBL_PORTAL_OTP_CHECK_BTN}
                            </button>
                        </div>
                    </form>
                    {if $OTP_SENT}
                    <div class="alert alert-info mt-4">{$MODS.LBL_PORTAL_OTP_DONT_RECEIVED}</div>
                    <div class="d-flex align-items-center btn-group justify-content-center m-4" role="group"
                        aria-label="Resend OTP Options">

                        {if $AUTH_MODE == 'otp_email' || $AUTH_MODE == 'otp' }
                        <button id="resend-otp-btn-email" class="btn btn-light">
                            <i class="bi bi-envelope-at"></i> {$MODS.LBL_PORTAL_OTP_SEND_CODE_BY_EMAIL}
                            {$OTP_MASKED_EMAIL}
                        </button>
                        {/if}
                        {if $AUTH_MODE == 'otp_phone_message' || $AUTH_MODE == 'otp' }
                        <button id="resend-otp-btn-phone-message" class="btn btn-light">
                            <i class="bi bi-telephone"></i> {$MODS.LBL_PORTAL_OTP_SEND_CODE_BY_PHONE_MESSAGE}
                            {$OTP_MASKED_PHONE}
                        </button>
                        {/if}
                    </div>
                    {/if}
                </section>
                {$JAVASCRIPT_VALIDATION}
                {/if}

                { if $FIELD_VALIDATION_REQUIRED === true && $SHOW_PORTAL !== true }
                <section class="field-validation-area max-w-md mx-auto mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">
                        {$MODS.LBL_PORTAL_FIELD_VALIDATION}</h2>
                    { if isset($FIELD_VALIDATION_ERROR_MSG) && !empty($FIELD_VALIDATION_ERROR_MSG) }
                    <p class="alert alert-danger mb-4">
                        <strong>{$MODS.LBL_PORTAL_ERROR}:</strong> {$FIELD_VALIDATION_ERROR_MSG}
                    </p>
                    {/if}
                    <form id="fieldValidationForm" method="POST" action="">
                        <div class="mb-3">
                            <label for="validationFieldInput"
                                class="form-label text-sm font-weight-medium text-dark mb-1">{$MODS.LBL_PORTAL_FIELD_VALIDATION_INSTRUCTION}<strong>
                                    {$FIELD_VALIDATION_LABEL}</strong><span class="text-muted">
                                    ({$FIELD_VALIDATION_LABEL_FORMAT}):</span></label>
                            <input type="text" id="validationFieldInput" name="validation_field_value" required
                                pattern="{$FIELD_VALIDATION_REGEXP}"
                                class="form-control text-center text-xl font-weight-bold"
                                value="{$PREVIOUS_FIELD_VALUE}">
                            <span class="text-danger">{$FIELD_ERROR_MSG}</span>
                        </div>
                    </form>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" form="fieldValidationForm"
                            class="btn btn-primary py-2 px-3 mt-3 mt-lg-0 ml-lg-3">
                            {$MODS.LBL_PORTAL_VALIDATE_FIELD_BTN}
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </section>
                {$JAVASCRIPT_VALIDATION}
                {/if}
            </div>
            {/if}
    </main>
    <footer class="p-4 mt-4 text-center text-sm text-white" style="background-color: {$HEADER_COLOR};">
        {$MODS.LBL_PORTAL_FOOTER}
        <a href="https://sinergiacrm.org" target="_blank"
            class="font-weight-bold text-white text-decoration-none">SinergiaCRM</a>
    </footer>
</body>

</html>