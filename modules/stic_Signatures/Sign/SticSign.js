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

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let initialCanvasData = null; // Para almacenar el estado del canvas vacío
    let canvasEnabled = false; // Nuevo estado para controlar si el canvas está activo

    // Función para habilitar el lienzo y los botones
    function enableSignatureArea() {
        if (!canvasEnabled) {
            canvasEnabled = true;
            signatureCanvas.classList.remove('canvas-disabled');
            clearSignatureBtn.disabled = false;
            saveSignatureBtn.disabled = false;
            addAuditRecord('Lienzo de firma activado (documento leído).');
            // Opcional: Remover el listener de scroll una vez que el canvas está activado
            documentContentDiv.removeEventListener('scroll', checkScrollPosition);
        }
    }

    // Función para verificar la posición del scroll
    function checkScrollPosition() {
        // Si el usuario ha llegado al final del scroll del documento
        if (documentContentDiv.scrollTop + documentContentDiv.clientHeight >= documentContentDiv.scrollHeight) {
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
        if (initialCanvasData === null) {
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
        initialCanvasData = signatureCanvas.toDataURL('image/png');
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
                    <p class="text-gray-700 mb-4">Por favor, dibuje su firma antes de guardarla.</p>
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

    // --- Carga de Registros de Auditoría (Simulado) ---
    function loadAuditRecords() {
        setTimeout(() => {
            addAuditRecord('Documento listo para firma.');
            addAuditRecord('¡Bienvenido al portal de firmas!');
        }, 500);
    }

    // Cargar registros de auditoría al iniciar
    loadAuditRecords();

    // Desactivar el canvas y los botones al inicio
    signatureCanvas.classList.add('canvas-disabled');
    clearSignatureBtn.disabled = true;
    saveSignatureBtn.disabled = true;
    addAuditRecord('Lienzo de firma inicialmente desactivado.');

    // Añadir el listener de scroll al contenido del documento
    documentContentDiv.addEventListener('scroll', checkScrollPosition);

    // Verificar la posición del scroll al cargar la página por si el contenido es corto
    // y el scroll ya está al final.
    checkScrollPosition();
});
