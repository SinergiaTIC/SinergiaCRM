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
    <title>SinergiaCRM - Portal de Firmas Electrónicas</title>
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
                <img src="{$LOGO_URL}" alt="Logo de la organización" class="w-auto" style="max-height: 50px;">
                {/if}
            </div>
            <div class="w-100 w-sm-50 d-flex flex-column  align-items-sm-start text-center">
                <h2 class="text-white fs-5 text-base font-weight-bold tracking-tight mb-0">Portal de firmas</h2>
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
            <div class="stic-container container mt-4 max-w-md mx-auto">
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
                    <h1 class="text-3xl font-weight-bold mb-4 text-center text-success">Documento firmado</h1>
                    <div class="text-center mb-4"><i class="bi bi-check-circle-fill text-success text-center"
                            style="font-size: 4rem;"></i></div>
                    <!-- <iframe src="{$SIGNED_PDF_URL}#toolbar=0&navpanes=0&statusbar=0&messages=0&view=FitH"
                        class="w-100 h-600px border border-secondary rounded mb-3" title="Documento Firmado"></iframe> -->
                    <a class="btn btn-primary mt-3" href="{$DOWNLOAD_URL}"  target="_blank" rel="noopener noreferrer"><i
                            class="bi bi-cloud-download-fill"></i> Descargar
                        documento firmado</a>
                    <button id="send-signed-pdf-by-email" class="btn btn-primary mt-3"><i
                            class="bi bi-envelope-at-fill"></i> Enviame una copia por correo</button>
                    <p class="text-sm text-secondary mt-3">
                        El documento ha sido firmado electrónicamente. Puede copiar el siguiente código de verificación
                        para
                        comprobar en el futuro la validez de la firma:
                    </p>
                    <div class="verification-code bg-light p-2 rounded mt-2 d-inline-block">
                        <code class="text-break font-monospace">{$SIGNER_VERIFICATION_CODE}</code> <i class="bi bi-clipboard"
                            onclick="navigator.clipboard.writeText('{$SIGNER_VERIFICATION_CODE}');alert('Texto copiado');"
                            style="cursor: pointer;" title="Copiar al portapapeles"></i>
                    </div>


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