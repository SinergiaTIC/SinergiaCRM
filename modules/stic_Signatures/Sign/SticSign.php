<?php
// SticSign.php
// Este archivo contendrá la estructura HTML principal del portal de firmas.
// Se asume que este archivo se incluye dentro de un módulo de SinergiaCRM.

// --- Contenido del Documento HTML a Firmar ---
// Esta variable PHP contendría el HTML del documento que se desea firmar.
// En un escenario real, este contenido provendría de una base de datos o de un archivo.
// Por ejemplo: $documentHtmlContent = obtenerContenidoDocumentoDesdeDB($documentId);
$documentHtmlContent = '
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Acuerdo de Confidencialidad</h2>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Este Acuerdo de Confidencialidad ("Acuerdo") se celebra entre <strong>[Nombre de la Parte 1]</strong>
        y <strong>[Nombre de la Parte 2]</strong>, en adelante denominadas individualmente como "Parte" y
        conjuntamente como "Partes".
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Las Partes reconocen que, en el curso de su relación, una Parte puede divulgar a la otra
        información confidencial y propietaria. La "Información Confidencial" incluye, entre otros,
        secretos comerciales, información financiera, planes de negocio, listas de clientes,
        información técnica y cualquier otra información designada como confidencial.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Las Partes acuerdan mantener la Información Confidencial en estricta confidencialidad
        y no divulgarla a terceros sin el consentimiento previo por escrito de la Parte divulgadora.
        Asimismo, las Partes se comprometen a utilizar la Información Confidencial únicamente
        para los fines para los que fue divulgada.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Este Acuerdo será vinculante para las Partes y sus respectivos sucesores y cesionarios.
        La obligación de confidencialidad sobrevivirá a la terminación de la relación entre las Partes.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        En fe de lo cual, las Partes firman este Acuerdo en la fecha indicada a continuación.
    </p>
    <div class="mt-8 text-sm text-gray-600">
        <p>Fecha: ' . date('d/m/Y') . '</p>
        <p>Lugar: Getafe, España</p>
    </div>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus auctor iaculis. Aenean placerat. Ut dictum felis eu erat. Nam vitae elit. Sed non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augu.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci, ac placerat dolor lectus quis orci. Phasellus dictum. Fusce a quam. In eleifend arcu quis nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augu.
    </p>
    <p class="mb-3 text-gray-700 leading-relaxed">
        Maecenas non leo laoreet, condimentum lorem nec, sagittis orci. Etiam in fermentum dolor. Sed non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augu. Sed non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augu.
    </p>
';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SticSign - Portal de Firmas Electrónicas</title>
    <!-- Enlace al CDN de Tailwind CSS para estilos de utilidad -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configuración de Tailwind CSS para usar la fuente Inter y colores personalizados -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Enlace al archivo de estilos CSS externo -->
    <link rel="stylesheet" href="modules/stic_Signatures/Sign/SticSign.css">
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="stic-container">
        <!-- Sección del Documento HTML -->
        <section class="document-area">
            <h1 class="text-3xl font-extrabold mb-6 text-center text-blue-700">Documento para Firma</h1>
            <div id="documentContent" class="prose max-w-none document-scroll-content">
                <?php echo $documentHtmlContent; ?>
            </div>
        </section>

        <!-- Sección del Área de Firma Manuscrita -->
        <section class="signature-area">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Área de Firma</h2>
            <p class="text-sm text-gray-600 mb-3 text-center">
                Por favor, dibuje su firma en el recuadro de abajo.
            </p>
            <canvas id="signatureCanvas" class="w-full"></canvas>
            <div class="action-buttons flex justify-center gap-4 mt-4">
                <button id="clearSignatureBtn" class="btn-secondary">Limpiar</button>
                <button id="saveSignatureBtn" class="btn-primary">Guardar Firma</button>
            </div>
        </section>

        <!-- Sección de Registros de Auditoría -->
        <section class="audit-trail-area">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Registros de Auditoría</h2>
            <div id="auditRecords" class="space-y-2">
                <!-- Los registros de auditoría se cargarán aquí dinámicamente con JavaScript -->
                <div class="audit-record bg-gray-50 rounded-md">
                    <strong>[<?php echo date('d/m/Y H:i:s'); ?>]</strong> Documento cargado.
                </div>
                <div class="audit-record bg-gray-50 rounded-md">
                    <strong>[<?php echo date('d/m/Y H:i:s', strtotime('-5 minutes')); ?>]</strong> Usuario inició sesión.
                </div>
            </div>
        </section>
    </div>

    <!-- Enlace al archivo JavaScript -->
    <script src="modules/stic_Signatures/Sign/SticSign.js"></script>
</body>
</html>
