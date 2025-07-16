// SticSign.js
// Este archivo contiene la lógica JavaScript para el lienzo de firma
// y la gestión (simulada) de registros de auditoría.

document.addEventListener('DOMContentLoaded', () => {
    // --- Configuración del Lienzo de Firma ---
    const signatureCanvas = document.getElementById('signatureCanvas');
    const ctx = signatureCanvas.getContext('2d');
    const clearSignatureBtn = document.getElementById('clearSignatureBtn');
    const saveSignatureBtn = document.getElementById('saveSignatureBtn');
    const auditRecordsDiv = document.getElementById('auditRecords');
    const documentContentDiv = document.getElementById('documentContent'); // Referencia al div del contenido del documento
    const scrollInstructionMessage = document.getElementById('scrollInstructionMessage'); // Nuevo: Mensaje de instrucción de scroll

    // Nuevos elementos para la firma de texto
    const textSignatureInput = document.getElementById('textSignatureInput');
    const fontSelector = document.getElementById('fontSelector');
    const renderTextSignatureBtn = document.getElementById('renderTextSignatureBtn');

    // Nuevos elementos para la firma por imagen
    const imageSignatureInput = document.getElementById('imageSignatureInput');
    const uploadImageSignatureBtn = document.getElementById('uploadImageSignatureBtn');

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let initialCanvasData = null; // Para almacenar el estado del canvas vacío
    let canvasEnabled = false; // Nuevo estado para controlar si el canvas está activo

    // Fuentes manuscritas de Google Fonts
    const handwrittenFonts = [
        'Dancing Script',
        'Pacifico',
        'Great Vibes',
        'Caveat',
        'Indie Flower'
    ];

    // Cargar las fuentes de Google Fonts dinámicamente
    function loadGoogleFonts() {
        handwrittenFonts.forEach(fontName => {
            // Se asume que las fuentes ya están importadas en SticSign.css
            // document.fonts.load() es útil si necesitas asegurarte de que están cargadas
            // antes de dibujar, pero CSS @import ya las maneja para el navegador.
            // Aquí solo aseguramos que el navegador las conozca para el canvas.
            const font = new FontFace(fontName, `url(https://fonts.gstatic.com/s/${fontName.toLowerCase().replace(' ', '')}/vXX/${fontName.replace(' ', '')}.woff2)`);
            font.load().then(() => {
                document.fonts.add(font);
                console.log(`${fontName} font loaded.`);
            }).catch(error => console.error(`Failed to load font ${fontName}:`, error));
        });
    }

    // Función para habilitar el lienzo y los botones
    function enableSignatureArea() {
        if (!canvasEnabled) {
            canvasEnabled = true;
            signatureCanvas.classList.remove('canvas-disabled');
            clearSignatureBtn.disabled = false;
            saveSignatureBtn.disabled = false;
            // Habilitar también los nuevos elementos
            textSignatureInput.disabled = false;
            fontSelector.disabled = false;
            renderTextSignatureBtn.disabled = false;
            imageSignatureInput.disabled = false;
            uploadImageSignatureBtn.disabled = false;

            // Ocultar el mensaje de instrucción de scroll
            scrollInstructionMessage.style.display = 'none';

            addAuditRecord('Lienzo de firma y opciones alternativas activadas (documento leído).');
            // Opcional: Remover el listener de scroll una vez que el canvas está activado
            documentContentDiv.removeEventListener('scroll', checkScrollPosition);
        }
    }

    // Función para verificar la posición del scroll
    function checkScrollPosition() {
        // Si el usuario ha llegado al final del scroll del documento
        // Se añade un pequeño margen de 1px para evitar problemas de redondeo
        if (documentContentDiv.scrollTop + documentContentDiv.clientHeight >= documentContentDiv.scrollHeight - 1) {
            enableSignatureArea();
        }
    }

    // Ajustar el tamaño del lienzo para que sea nítido en pantallas de alta densidad
    // y sea responsivo, manteniendo la proporción 16:9 y una altura máxima de 250px.
    function resizeCanvas() {
        // Crear un canvas temporal para guardar el dibujo actual antes de redimensionar
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        // Asegurarse de que el canvas temporal tenga las mismas dimensiones intrínsecas
        // que el canvas principal antes de redimensionar para capturar correctamente.
        tempCanvas.width = signatureCanvas.width;
        tempCanvas.height = signatureCanvas.height;
        // Dibujar el contenido actual del canvas principal en el temporal
        tempCtx.drawImage(signatureCanvas, 0, 0);

        const parentElement = signatureCanvas.parentElement;
        const parentWidth = parentElement.offsetWidth; // Ancho disponible del contenedor
        const maxHeightCSS = 250; // Altura máxima deseada en CSS pixels

        let calculatedWidthCSS = parentWidth;
        let calculatedHeightCSS = calculatedWidthCSS * (9 / 16); // Altura inicial basada en 16:9 del ancho completo

        // Si la altura calculada excede la altura máxima, ajustamos
        if (calculatedHeightCSS > maxHeightCSS) {
            calculatedHeightCSS = maxHeightCSS;
            calculatedWidthCSS = maxHeightCSS * (16 / 9); // Recalcular ancho para mantener 16:9 con la altura máxima
        }

        // Asegurarse de que el ancho calculado no exceda el ancho del padre
        if (calculatedWidthCSS > parentWidth) {
            calculatedWidthCSS = parentWidth;
            calculatedHeightCSS = calculatedWidthCSS * (9 / 16);
        }

        // Aplicar las dimensiones calculadas al estilo CSS del canvas
        signatureCanvas.style.width = `${calculatedWidthCSS}px`;
        signatureCanvas.style.height = `${calculatedHeightCSS}px`;

        // Establecer las dimensiones intrínsecas (resolución de dibujo) del canvas
        // Multiplicar por devicePixelRatio para asegurar nitidez en pantallas de alta densidad (Retina)
        signatureCanvas.width = calculatedWidthCSS * window.devicePixelRatio;
        signatureCanvas.height = calculatedHeightCSS * window.devicePixelRatio;

        // Limpiar el contexto y restablecer la transformación antes de aplicar la nueva escala
        // Esto es crucial para evitar problemas de escalado acumulativos o dibujos borrosos.
        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        ctx.setTransform(1, 0, 0, 1, 0, 0); // Restablecer la matriz de transformación a la identidad
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Aplicar el nuevo escalado

        // Restaurar el dibujo desde el canvas temporal al canvas principal redimensionado
        // Es importante dibujar usando las dimensiones *de visualización* (calculatedWidthCSS, calculatedHeightCSS)
        // para que el dibujo se escale correctamente al nuevo tamaño del canvas.
        ctx.drawImage(tempCanvas, 0, 0, calculatedWidthCSS, calculatedHeightCSS);

        // Restablecer las propiedades de estilo del contexto después de escalar
        ctx.lineCap = 'round';     // Extremos de línea redondeados
        ctx.lineJoin = 'round';    // Uniones de línea redondeadas
        ctx.lineWidth = 3;         // Ancho de línea para la firma
        ctx.strokeStyle = '#333';  // Color de la firma

        // Capturar la Data URL de un lienzo vacío solo la primera vez que se inicializa
        // o cuando se limpia explícitamente. Esto se usa para comparar si el lienzo está realmente vacío.
        if (initialCanvasData === null || initialCanvasData === 'reset') { // 'reset' para forzar recaptura en resize
            // Para obtener una Data URL de un canvas *realmente* vacío,
            // lo limpiamos temporalmente, obtenemos la URL, y luego restauramos el contenido.
            const originalImageData = ctx.getImageData(0, 0, signatureCanvas.width, signatureCanvas.height);
            ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            initialCanvasData = signatureCanvas.toDataURL('image/png');
            ctx.putImageData(originalImageData, 0, 0); // Restaurar el contenido original
        }
    }

    // Inicializar el tamaño del lienzo al cargar la página y capturar el estado vacío inicial
    resizeCanvas();

    // Reajustar el tamaño del lienzo si la ventana cambia de tamaño
    window.addEventListener('resize', () => {
        initialCanvasData = 'reset'; // Marcar para recapturar el estado vacío después del resize
        resizeCanvas();
        // Después de redimensionar, el scroll puede cambiar, así que volvemos a verificar
        checkScrollPosition();
    });


    // Función para añadir un registro al área de auditoría
    function addAuditRecord(message) {
        const now = new Date();
        const timestamp = now.toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const recordDiv = document.createElement('div');
        recordDiv.className = 'audit-record bg-gray-50 rounded-md';
        recordDiv.innerHTML = `<strong>[${timestamp}]</strong> ${message}`;
        auditRecordsDiv.prepend(recordDiv); // Añadir al principio para ver los más recientes
    }

    // --- Lógica de Dibujo para Ratón ---
    signatureCanvas.addEventListener('mousedown', (e) => {
        if (!canvasEnabled) return; // No permitir dibujar si el canvas está deshabilitado
        isDrawing = true;
        // Limpiar el canvas si el usuario empieza a dibujar manualmente después de una firma de texto/imagen
        if (signatureCanvas.toDataURL('image/png') !== initialCanvasData) {
            ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            addAuditRecord('Lienzo limpiado para nueva firma manual.');
        }
        [lastX, lastY] = [e.offsetX, e.offsetY];
        addAuditRecord('Inicio de dibujo de firma.');
    });

    signatureCanvas.addEventListener('mousemove', (e) => {
        if (!isDrawing || !canvasEnabled) return; // No permitir dibujar si el canvas está deshabilitado
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    signatureCanvas.addEventListener('mouseup', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Fin de dibujo de firma.');
        }
    });

    signatureCanvas.addEventListener('mouseout', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Dibujo de firma interrumpido (cursor fuera del lienzo).');
        }
    });

    // --- Lógica de Dibujo para Táctil (Móviles) ---
    signatureCanvas.addEventListener('touchstart', (e) => {
        if (!canvasEnabled) return; // No permitir dibujar si el canvas está deshabilitado
        e.preventDefault(); // Prevenir el desplazamiento de la página
        isDrawing = true;
        // Limpiar el canvas si el usuario empieza a dibujar manualmente después de una firma de texto/imagen
        if (signatureCanvas.toDataURL('image/png') !== initialCanvasData) {
            ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            addAuditRecord('Lienzo limpiado para nueva firma manual (táctil).');
        }
        const touch = e.touches[0];
        const rect = signatureCanvas.getBoundingClientRect();
        lastX = touch.clientX - rect.left;
        lastY = touch.clientY - rect.top;
        addAuditRecord('Inicio de dibujo de firma (táctil).');
    }, { passive: false });

    signatureCanvas.addEventListener('touchmove', (e) => {
        if (!isDrawing || !canvasEnabled) return; // No permitir dibujar si el canvas está deshabilitado
        e.preventDefault(); // Prevenir el desplazamiento de la página
        const touch = e.touches[0];
        const rect = signatureCanvas.getBoundingClientRect();
        const currentX = touch.clientX - rect.left;
        const currentY = touch.clientY - rect.top;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();
        [lastX, lastY] = [currentX, currentY];
    }, { passive: false });

    signatureCanvas.addEventListener('touchend', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Fin de dibujo de firma (táctil).');
        }
    });

    // --- Funciones de Botones ---
    clearSignatureBtn.addEventListener('click', () => {
        if (!canvasEnabled) return; // No permitir acción si el canvas está deshabilitado
        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        initialCanvasData = signatureCanvas.toDataURL('image/png'); // Recapturar el estado vacío
        addAuditRecord('Firma limpiada del lienzo.');
    });

    saveSignatureBtn.addEventListener('click', () => {
        if (!canvasEnabled) return; // No permitir acción si el canvas está deshabilitado
        const currentCanvasData = signatureCanvas.toDataURL('image/png');

        if (currentCanvasData !== initialCanvasData) {
            console.log('Firma guardada (simulado):', currentCanvasData);
            addAuditRecord('Firma capturada y lista para ser enviada al servidor.');

            const successMessage = document.createElement('div');
            successMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            successMessage.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                    <h3 class="text-xl font-bold text-green-600 mb-3">¡Firma Guardada!</h3>
                    <p class="text-gray-700 mb-4">La firma ha sido capturada exitosamente.</p>
                    <button id="closeMessageBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                </div>
            `;
            document.body.appendChild(successMessage);

            document.getElementById('closeMessageBtn').addEventListener('click', () => {
                successMessage.remove();
            });

        } else {
            const warningMessage = document.createElement('div');
            warningMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            warningMessage.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                    <h3 class="text-xl font-bold text-yellow-600 mb-3">Atención</h3>
                    <p class="text-gray-700 mb-4">Por favor, dibuje su firma o use una de las opciones alternativas antes de guardarla.</p>
                    <button id="closeWarningBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                </div>
            `;
            document.body.appendChild(warningMessage);

            document.getElementById('closeWarningBtn').addEventListener('click', () => {
                warningMessage.remove();
            });
            addAuditRecord('Intento de guardar firma en lienzo vacío.');
        }
    });

    // --- Funcionalidad de Firma por Texto ---
    renderTextSignatureBtn.addEventListener('click', () => {
        if (!canvasEnabled) return;
        const signatureText = textSignatureInput.value.trim();
        const selectedFont = fontSelector.value;

        if (signatureText === '') {
            const warningMessage = document.createElement('div');
            warningMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            warningMessage.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                    <h3 class="text-xl font-bold text-yellow-600 mb-3">Atención</h3>
                    <p class="text-gray-700 mb-4">Por favor, escriba su nombre para generar la firma de texto.</p>
                    <button id="closeWarningBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                </div>
            `;
            document.body.appendChild(warningMessage);

            document.getElementById('closeWarningBtn').addEventListener('click', () => {
                warningMessage.remove();
            });
            addAuditRecord('Intento de generar firma de texto con campo vacío.');
            return;
        }

        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height); // Limpiar canvas
        ctx.setTransform(1, 0, 0, 1, 0, 0); // Resetear transformación antes de dibujar texto
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Aplicar escala para nitidez

        // Intentar cargar la fuente si no está ya cargada (aunque CSS @import debería manejarlo)
        document.fonts.ready.then(() => {
            const fontSize = Math.min(signatureCanvas.width / (signatureText.length * 0.8), signatureCanvas.height * 0.6); // Ajustar tamaño de fuente
            ctx.font = `${fontSize}px "${selectedFont}"`;
            ctx.fillStyle = '#333'; // Color del texto
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Calcular posición para centrar el texto
            const x = signatureCanvas.width / (2 * window.devicePixelRatio);
            const y = signatureCanvas.height / (2 * window.devicePixelRatio);

            ctx.fillText(signatureText, x, y);
            addAuditRecord(`Firma de texto "${signatureText}" generada con fuente "${selectedFont}".`);
        }).catch(error => {
            console.error("Error al cargar la fuente para la firma de texto:", error);
            addAuditRecord(`Error al generar firma de texto: fuente "${selectedFont}" no cargada.`);
            // Intentar dibujar con una fuente de respaldo
            ctx.font = `${Math.min(signatureCanvas.width / (signatureText.length * 0.8), signatureCanvas.height * 0.6)}px sans-serif`;
            ctx.fillStyle = '#333';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const x = signatureCanvas.width / (2 * window.devicePixelRatio);
            const y = signatureCanvas.height / (2 * window.devicePixelRatio);
            ctx.fillText(signatureText, x, y);
        });
    });

    // --- Funcionalidad de Subir Firma como Imagen ---
    imageSignatureInput.addEventListener('change', (e) => {
        if (!canvasEnabled) {
            imageSignatureInput.value = ''; // Limpiar input si no está habilitado
            return;
        }
        const file = e.target.files[0];
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height); // Limpiar canvas
                ctx.setTransform(1, 0, 0, 1, 0, 0); // Resetear transformación
                ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Aplicar escala

                // Calcular dimensiones para ajustar la imagen al canvas manteniendo la proporción
                const canvasAspectRatio = signatureCanvas.width / signatureCanvas.height;
                const imgAspectRatio = img.width / img.height;

                let drawWidth = signatureCanvas.width / window.devicePixelRatio;
                let drawHeight = signatureCanvas.height / window.devicePixelRatio;
                let offsetX = 0;
                let offsetY = 0;

                if (imgAspectRatio > canvasAspectRatio) {
                    // La imagen es más ancha que el canvas, ajustar al ancho del canvas
                    drawHeight = drawWidth / imgAspectRatio;
                    offsetY = (signatureCanvas.height / window.devicePixelRatio - drawHeight) / 2;
                } else {
                    // La imagen es más alta que el canvas, ajustar a la altura del canvas
                    drawWidth = drawHeight * imgAspectRatio;
                    offsetX = (signatureCanvas.width / window.devicePixelRatio - drawWidth) / 2;
                }

                ctx.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
                addAuditRecord('Firma de imagen cargada y renderizada en el lienzo.');
            };
            img.onerror = () => {
                console.error('Error al cargar la imagen de firma.');
                addAuditRecord('Error: No se pudo cargar la imagen de firma.');
                const errorMessage = document.createElement('div');
                errorMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                errorMessage.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                        <h3 class="text-xl font-bold text-red-600 mb-3">Error</h3>
                        <p class="text-gray-700 mb-4">No se pudo cargar la imagen. Asegúrese de que sea un archivo PNG o JPG válido.</p>
                        <button id="closeErrorBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                    </div>
                `;
                document.body.appendChild(errorMessage);

                document.getElementById('closeErrorBtn').addEventListener('click', () => {
                    errorMessage.remove();
                });
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    // --- Carga de Registros de Auditoría (Simulado) ---
    function loadAuditRecords() {
        setTimeout(() => {
            addAuditRecord('Documento listo para firma.');
            addAuditRecord('¡Bienvenido al portal de firmas!');
        }, 500);
    }

    // Cargar registros de auditoría al iniciar
    loadAuditRecords();
    loadGoogleFonts(); // Cargar las fuentes al inicio

    // Desactivar el canvas y los botones al inicio
    signatureCanvas.classList.add('canvas-disabled');
    clearSignatureBtn.disabled = true;
    saveSignatureBtn.disabled = true;
    // Desactivar también los nuevos elementos
    textSignatureInput.disabled = true;
    fontSelector.disabled = true;
    renderTextSignatureBtn.disabled = true;
    imageSignatureInput.disabled = true;
    uploadImageSignatureBtn.disabled = true;

    addAuditRecord('Lienzo de firma y opciones alternativas inicialmente desactivadas.');

    // Añadir el listener de scroll al contenido del documento
    documentContentDiv.addEventListener('scroll', checkScrollPosition);

    // Verificar la posición del scroll al cargar la página por si el contenido es corto
    // y el scroll ya está al final.
    checkScrollPosition();
});
