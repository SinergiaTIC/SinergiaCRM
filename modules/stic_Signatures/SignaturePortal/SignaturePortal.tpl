<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SticSign - Portal de Firmas Electrónicas</title>
    {$BOOTSTRAP_CSS}
    {$BOOTSTRAP_JS}
    {$BOOTSTRAP_ICONS}
    {$STYLESHEETS}
</head>

<body class="bg-light font-sans antialiased text-dark">

    <header class="p-4 p-sm-6 shadow-sm" style="background-color: {$HEADER_COLOR};">
        <div class="container-fluid d-flex align-items-center justify-content-center justify-content-sm-between">
            <div class="d-flex align-items-center space-x-4">
                { if isset($LOGO_URL) && !empty($LOGO_URL) }
                <img src="{$LOGO_URL}" alt="Logo de la organización" class="h-10 w-auto">
                {/if}
                { if isset($ORGANIZATION_NAME) && !empty($ORGANIZATION_NAME) }
                <h1 class="text-white text-2xl font-weight-bold tracking-tight d-none d-sm-block">Portal de firmas -
                    {$ORGANIZATION_NAME}
                </h1>
                {/if}
            </div>
        </div>
    </header>
    <main class="flex-grow">
        { if isset($ERROR_MSG) && !empty($ERROR_MSG) }
        <div class="stic-container container mt-4">
            <section class="error-area">
                <h1 class="text-3xl font-weight-bold mb-4 text-center text-danger">Error</h1>
                <div class="alert alert-danger" role="alert">
                    <strong>Error:</strong> {$ERROR_MSG}
                </div>
                <p class="text-center">
                    Por favor, inténtelo de nuevo o contacte al soporte técnico si el problema persiste.
                </p>
                <div class="text-center mt-3">
                    <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
                </div>
            </section>
            {else}
            <div class="stic-container container mt-4">
                { if $SHOW_PORTAL === true }
                { if $STATUS === 'pending' }
                <section class="document-area">
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-primary">Documento para Firma</h1>
                    <div id="documentContent" class="document-scroll-content">
                        {$DOCUMENT_HTML_CONTENT}
                    </div>
                    <div id="scrollInstructionMessage"
                        class="scroll-instruction-message alert alert-info mt-3 text-center">
                        <span class="d-block d-sm-inline">Por favor, desplácese hasta el final del documento para
                            habilitar
                            el área de firma.</span>
                    </div>
                </section>

                { if $SIGNATURE_MODE === 'handwritten' }
                <section class="signature-area mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">Área de Firma</h2>
                    <p class="text-sm text-secondary mb-3 text-center">
                        Por favor, dibuje su firma en el recuadro de abajo o use las opciones alternativas.
                    </p>

                    <div class="d-flex justify-content-center">
                        <canvas id="signatureCanvas"></canvas>
                    </div>

                    <div class="action-buttons d-flex justify-content-center gap-2 mt-3">
                        <button id="clearSignatureBtn" class="btn btn-secondary">Limpiar</button>
                        <button id="saveSignatureBtn" class="btn btn-primary">Guardar Firma</button>
                    </div>

                    <div class="signature-alternatives w-100 mt-4 pt-3 border-top border-secondary">
                        <h3 class="text-xl font-weight-bold mb-4 text-center text-dark">Opciones de Firma Alternativas
                        </h3>

                        <div class="text-signature-option mb-3 p-3 border border-secondary rounded bg-light">
                            <h4 class="text-lg font-weight-semibold mb-3 text-dark">1. Firma por Texto</h4>
                            <div class="mb-3">
                                <label for="textSignatureInput"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">Escriba
                                    su
                                    nombre completo:</label>
                                <input type="text" id="textSignatureInput" placeholder="Ej: Juan Pérez"
                                    class="form-control text-dark" value="{$SIGNER_NAME}">
                            </div>
                            <div class="mb-3">
                                <label for="fontSelector"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">Seleccione un
                                    estilo de fuente:</label>
                                <select id="fontSelector" class="form-select text-dark">
                                    <option value="Dancing Script">Dancing Script</option>
                                    <option value="Pacifico">Pacifico</option>
                                    <option value="Great Vibes">Great Vibes</option>
                                    <option value="Caveat">Caveat</option>
                                    <option value="Indie Flower">Indie Flower</option>
                                </select>
                            </div>
                            <button id="renderTextSignatureBtn" class="btn btn-secondary w-100">Renderizar Firma de
                                Texto</button>
                        </div>

                        <div class="image-signature-option p-3 border border-secondary rounded bg-light">
                            <h4 class="text-lg font-weight-semibold mb-3 text-dark">2. Subir Firma como Imagen</h4>
                            <div class="mb-3">
                                <label for="imageSignatureInput"
                                    class="form-label text-sm font-weight-medium text-dark mb-1">Seleccione
                                    un archivo de imagen (PNG/JPG):</label>
                                <input type="file" id="imageSignatureInput" accept="image/png, image/jpeg"
                                    class="form-control text-dark">
                            </div>
                            <button id="uploadImageSignatureBtn" class="btn btn-secondary w-100">Subir Imagen de
                                Firma</button>
                        </div>
                    </div>
                </section>
                { /if }

                { if $SIGNATURE_MODE === 'button' }
                <section id="buttonAcceptationArea" class="mt-4">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">Área de Aceptación</h2>
                    <p class="text-sm text-secondary mb-3 text-center">
                        Para aceptar el documento, haga clic en el botón de abajo.
                    </p>

                    <div class="action-buttons d-flex justify-content-center mt-3">
                        <button id="acceptDocumentBtn" class="btn btn-primary w-100 max-w-sm" disabled>Aceptar y Firmar
                            Documento</button>
                    </div>
                </section>
                { /if }
                { /if }


                { if $STATUS === 'signed' }
                <section class="signed-area text-center mt-4">
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-success">Documento Firmado</h1>
                    <iframe src="{$SIGNED_PDF_URL}#toolbar=0&navpanes=0&statusbar=0&messages=0&view=Fit"
                        class="w-100 h-600px border border-secondary rounded mb-3" title="Documento Firmado"></iframe>
                    <a href="{$DOWNLOAD_URL}" class="btn btn-primary" target="_blank"
                        rel="noopener noreferrer">Descargar
                        Documento Firmado</a>
                </section>
                { /if }

                { if $SHOW_LOGS === true }
                <section class="logs-area mt-5">
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">Registro de acciones</h2>

                    { if isset($SIGNER_LOG) && !empty($SIGNER_LOG) }
                    <div class="mb-5">
                        <h3 class="text-xl font-weight-semibold mb-3 text-dark">Acciones del Firmante</h3>
                        <ul class="list-group">
                            { foreach from=$SIGNER_LOG item=logEntry }
                            <li class="list-group-item" {if !empty($logEntry.description)}title="{$logEntry.description}" { /if }>
                                <strong>{$logEntry.date}</strong> - {$logEntry.action}
                                { if !empty($logEntry.description) }
                                <i class="fw-bold text-primary bi bi-info-circle float-end"></i>
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
                    <h2 class="text-2xl font-weight-bold mb-4 text-center text-dark">Código de verificación</h2>
                    { if isset($OTP_ERROR_MSG) && !empty($OTP_ERROR_MSG) }
                    <p class="alert alert-danger mb-4">
                        <strong>Error:</strong> {$OTP_ERROR_MSG}
                    </p>
                    {/if}
                    <p class="text-sm text-secondary mb-3 text-center">
                        Por favor, para continuar indique el código de verificación de un solo uso de 6 dígitos ha sido
                        enviado a su correo <strong>{$OTP_MASKED_EMAIL}</strong>.
                    </p>
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
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary py-2 px-3 mt-3 mt-lg-0 ml-lg-3">
                                Comprobar código
                            </button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <button id="resend-otp-btn" class="btn btn-link">
                            ¿No ha recibido el código? Reenviar
                        </button>
                    </div>
                </section>
                {$JAVASCRIPT_OTP}
                {/if}
            </div>
            {/if}
    </main>
    <footer class="p-4 mt-4 text-center text-sm text-white" style="background-color: {$HEADER_COLOR};">
        Portal de firmas electrónicas de
        <a href="https://sinergiacrm.org" target="_blank"
            class="font-weight-bold text-white text-decoration-none">SinergiaCRM</a>
    </footer>
</body>

</html>