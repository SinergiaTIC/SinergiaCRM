/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

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

    // Fuentes manuscritas de Google Fonts (no es necesario cargarlas aquí si ya están en CSS)
    // const handwrittenFonts = [
    //     'Dancing Script',
    //     'Pacifico',
    //     'Great Vibes',
    //     'Caveat',
    //     'Indie Flower'
    // ];

    // La función loadGoogleFonts() se elimina ya que las fuentes se cargan vía CSS.
    // function loadGoogleFonts() {
    //     handwrittenFonts.forEach(fontName => {
    //         const font = new FontFace(fontName, `url(https://fonts.gstatic.com/s/${fontName.toLowerCase().replace(' ', '')}/vXX/${fontName.replace(' ', '')}.woff2)`);
    //         font.load().then(() => {
    //             document.fonts.add(font);
    //             console.log(`${fontName} font loaded.`);
    //         }).catch(error => console.error(`Failed to load font ${fontName}:`, error));
    //     });
    // }

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
        // Se elimina la limpieza automática del canvas aquí para permitir múltiples trazos.
        // El canvas solo se limpiará con el botón "Limpiar" o al usar las opciones de texto/imagen.
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
            addAuditRecord('Fin de trazo de firma.'); // Mensaje cambiado para reflejar fin de trazo, no de firma completa
        }
    });

    signatureCanvas.addEventListener('mouseout', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Trazo de firma interrumpido (cursor fuera del lienzo).'); // Mensaje cambiado
        }
    });

    // --- Lógica de Dibujo para Táctil (Móviles) ---
    signatureCanvas.addEventListener('touchstart', (e) => {
        if (!canvasEnabled) return; // No permitir dibujar si el canvas está deshabilitado
        e.preventDefault(); // Prevenir el desplazamiento de la página
        isDrawing = true;
        // Se elimina la limpieza automática del canvas aquí para permitir múltiples trazos.
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
            addAuditRecord('Fin de trazo de firma (táctil).'); // Mensaje cambiado
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
            const canvasVisualWidth = signatureCanvas.offsetWidth;
            const canvasVisualHeight = signatureCanvas.offsetHeight;

            // Iniciar con un tamaño de fuente grande y reducirlo si es necesario
            let fontSize = canvasVisualHeight * 0.8; // Intentar ocupar la mayor parte de la altura
            ctx.font = `${fontSize}px "${selectedFont}"`;
            let textMetrics = ctx.measureText(signatureText);

            // Calcular el factor de escala necesario para que el texto quepa en el ancho deseado
            const targetWidth = canvasVisualWidth * 0.9; // 90% del ancho del canvas para padding
            if (textMetrics.width > targetWidth) {
                fontSize = (targetWidth / textMetrics.width) * fontSize;
                ctx.font = `${fontSize}px "${selectedFont}"`; // Actualizar la fuente con el nuevo tamaño
                textMetrics = ctx.measureText(signatureText); // Volver a medir con el nuevo tamaño
            }

            // Calcular la altura real del texto (aproximada, ya que textMetrics no da la altura exacta de la fuente)
            // Una forma más precisa sería usar textMetrics.actualBoundingBoxAscent + textMetrics.actualBoundingBoxDescent
            // pero esto puede variar entre navegadores y fuentes. Usaremos una estimación basada en fontSize.
            const estimatedTextHeight = fontSize * 1.2; // Factor de ajuste para la altura real de la fuente

            // Si la altura estimada es mayor que la altura máxima deseada
            const targetHeight = canvasVisualHeight * 0.9; // 90% de la altura del canvas para padding
            if (estimatedTextHeight > targetHeight) {
                fontSize = (targetHeight / estimatedTextHeight) * fontSize;
                ctx.font = `${fontSize}px "${selectedFont}"`; // Actualizar la fuente con el nuevo tamaño
            }

            ctx.fillStyle = '#333'; // Color del texto
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Calcular posición para centrar el texto
            const x = canvasVisualWidth / 2;
            const y = canvasVisualHeight / 2;

            ctx.fillText(signatureText, x, y);
            addAuditRecord(`Firma de texto "${signatureText}" generada con fuente "${selectedFont}".`);
        }).catch(error => {
            console.error("Error al cargar la fuente para la firma de texto:", error);
            addAuditRecord(`Error al generar firma de texto: fuente "${selectedFont}" no cargada.`);
            // Intentar dibujar con una fuente de respaldo
            const canvasVisualWidth = signatureCanvas.offsetWidth;
            const canvasVisualHeight = signatureCanvas.offsetHeight;
            let fontSize = canvasVisualHeight * 0.8;
            ctx.font = `${fontSize}px sans-serif`;
            let textMetrics = ctx.measureText(signatureText);
            const maxWidth = canvasVisualWidth * 0.9;
            if (textMetrics.width > maxWidth) {
                fontSize = (maxWidth / textMetrics.width) * fontSize;
            }
            const estimatedTextHeight = fontSize * 1.2;
            const targetHeight = canvasVisualHeight * 0.9;
            if (estimatedTextHeight > targetHeight) {
                fontSize = (targetHeight / estimatedTextHeight) * fontSize;
            }
            ctx.font = `${fontSize}px sans-serif`; // Aplicar el tamaño ajustado a la fuente de respaldo
            ctx.fillStyle = '#333';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const x = canvasVisualWidth / 2;
            const y = canvasVisualHeight / 2;
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
                const canvasVisualWidth = signatureCanvas.offsetWidth;
                const canvasVisualHeight = signatureCanvas.offsetHeight;
                const canvasAspectRatio = canvasVisualWidth / canvasVisualHeight;
                const imgAspectRatio = img.width / img.height;

                let drawWidth = canvasVisualWidth;
                let drawHeight = canvasVisualHeight;
                let offsetX = 0;
                let offsetY = 0;

                if (imgAspectRatio > canvasAspectRatio) {
                    // La imagen es más ancha que el canvas, ajustar al ancho del canvas
                    drawHeight = drawWidth / imgAspectRatio;
                    offsetY = (canvasVisualHeight - drawHeight) / 2;
                } else {
                    // La imagen es más alta que el canvas, ajustar a la altura del canvas
                    drawWidth = drawHeight * imgAspectRatio;
                    offsetX = (canvasVisualWidth - drawWidth) / 2;
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
    // La función loadGoogleFonts() se elimina ya que las fuentes se cargan vía CSS.
    // loadGoogleFonts(); // Cargar las fuentes al inicio

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
