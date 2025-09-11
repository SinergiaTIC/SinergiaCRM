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

/*
 * This file contains the JavaScript logic for the signature canvas
 * and the (simulated) management of audit records.
 */

document.addEventListener('DOMContentLoaded', () => {

    // General elements
    const auditRecordsDiv = document.getElementById('auditRecords');
    const documentContentDiv = document.getElementById('documentContent');
    const scrollInstructionMessage = document.getElementById('scrollInstructionMessage');

    // Canvas signature elements
    const signatureCanvas = document.getElementById('signatureCanvas');
    let clearSignatureBtn, saveSignatureBtn, textSignatureInput, fontSelector, renderTextSignatureBtn, imageSignatureInput;

    if (signatureCanvas) {
        clearSignatureBtn = document.getElementById('clearSignatureBtn');
        saveSignatureBtn = document.getElementById('saveSignatureBtn');
        textSignatureInput = document.getElementById('textSignatureInput');
        fontSelector = document.getElementById('fontSelector');
        renderTextSignatureBtn = document.getElementById('renderTextSignatureBtn');
        imageSignatureInput = document.getElementById('imageSignatureInput');
    }

    // Button acceptance elements
    const acceptDocumentBtn = document.getElementById('acceptDocumentBtn');

    // Variable para controlar si el área de firma/aceptación está activa
    let isAcceptanceAreaEnabled = false;

    /**
     * Adds an audit record to the audit trail area.
     * @param {string} message The message to record.
     */
    function addAuditRecord(message) {
        if (!auditRecordsDiv) return;
        const now = new Date();
        const timestamp = now.toLocaleString('en-US', {
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
        auditRecordsDiv.prepend(recordDiv);
    }

    /**
     * Enables the signature or acceptance area after the user scrolls to the bottom.
     */
    function enableAcceptanceArea() {
        if (!isAcceptanceAreaEnabled) {
            isAcceptanceAreaEnabled = true;

            if (scrollInstructionMessage) {
                scrollInstructionMessage.style.display = 'none';
            }

            if (signatureCanvas) {
                signatureCanvas.classList.remove('canvas-disabled');
                // Botones y campos de canvas
                if (clearSignatureBtn) clearSignatureBtn.disabled = false;
                if (saveSignatureBtn) saveSignatureBtn.disabled = false;
                if (textSignatureInput) textSignatureInput.disabled = false;
                if (fontSelector) fontSelector.disabled = false;
                if (renderTextSignatureBtn) renderTextSignatureBtn.disabled = false;
                if (imageSignatureInput) imageSignatureInput.disabled = false;
                addAuditRecord('Signature canvas and alternative options activated (document read).');
            } else if (acceptDocumentBtn) {
                acceptDocumentBtn.disabled = false;
                addAuditRecord('Acceptance button activated (document read).');
            }

            // Remove the scroll listener once the area is activated
            if (documentContentDiv) {
                documentContentDiv.removeEventListener('scroll', checkScrollPosition);
            }
        }
    }

    /**
     * Checks the scroll position of the document content to enable the signature area.
     */
    function checkScrollPosition() {
        if (!documentContentDiv) return;
        if (documentContentDiv.scrollTop + documentContentDiv.clientHeight >= documentContentDiv.scrollHeight - 1) {
            enableAcceptanceArea();
        }
    }

    // --- Lógica para el modo "handwritten" (canvas) ---
    if (signatureCanvas) {
        const ctx = signatureCanvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        let initialCanvasData = null;

        /**
         * Adjusts the canvas size for sharp rendering on high-density screens
         * and makes it responsive.
         */
        function resizeCanvas() {
            const parentElement = signatureCanvas.parentElement;
            const parentWidth = parentElement.offsetWidth;
            const maxHeightCSS = 250;

            let calculatedWidthCSS = parentWidth;
            let calculatedHeightCSS = calculatedWidthCSS * (9 / 16);

            if (calculatedHeightCSS > maxHeightCSS) {
                calculatedHeightCSS = maxHeightCSS;
                calculatedWidthCSS = maxHeightCSS * (16 / 9);
            }

            if (calculatedWidthCSS > parentWidth) {
                calculatedWidthCSS = parentWidth;
                calculatedHeightCSS = calculatedWidthCSS * (9 / 16);
            }

            signatureCanvas.style.width = `${calculatedWidthCSS}px`;
            signatureCanvas.style.height = `${calculatedHeightCSS}px`;

            signatureCanvas.width = calculatedWidthCSS * window.devicePixelRatio;
            signatureCanvas.height = calculatedHeightCSS * window.devicePixelRatio;

            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(window.devicePixelRatio, window.devicePixelRatio);

            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.lineWidth = 3;
            ctx.strokeStyle = '#333';

            if (initialCanvasData === null || initialCanvasData === 'reset') {
                const originalImageData = ctx.getImageData(0, 0, signatureCanvas.width, signatureCanvas.height);
                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
                initialCanvasData = signatureCanvas.toDataURL('image/png');
                ctx.putImageData(originalImageData, 0, 0);
            }
        }

        // Initialize canvas
        resizeCanvas();
        window.addEventListener('resize', () => {
            initialCanvasData = 'reset';
            resizeCanvas();
            checkScrollPosition();
        });

        // --- Drawing Logic for Mouse ---
        signatureCanvas.addEventListener('mousedown', (e) => {
            if (!isAcceptanceAreaEnabled) return;
            isDrawing = true;
            [lastX, lastY] = [e.offsetX, e.offsetY];
            addAuditRecord('Signature drawing started.');
        });

        signatureCanvas.addEventListener('mousemove', (e) => {
            if (!isDrawing || !isAcceptanceAreaEnabled) return;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            [lastX, lastY] = [e.offsetX, e.offsetY];
        });

        signatureCanvas.addEventListener('mouseup', () => {
            if (isDrawing) {
                isDrawing = false;
                addAuditRecord('Signature stroke finished.');
            }
        });

        signatureCanvas.addEventListener('mouseout', () => {
            if (isDrawing) {
                isDrawing = false;
                addAuditRecord('Signature stroke interrupted (cursor left canvas).');
            }
        });

        // --- Drawing Logic for Touch (Mobile) ---
        signatureCanvas.addEventListener('touchstart', (e) => {
            if (!isAcceptanceAreaEnabled) return;
            e.preventDefault();
            isDrawing = true;
            const touch = e.touches[0];
            const rect = signatureCanvas.getBoundingClientRect();
            lastX = touch.clientX - rect.left;
            lastY = touch.clientY - rect.top;
            addAuditRecord('Signature drawing started (touch).');
        }, { passive: false });

        signatureCanvas.addEventListener('touchmove', (e) => {
            if (!isDrawing || !isAcceptanceAreaEnabled) return;
            e.preventDefault();
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
                addAuditRecord('Signature stroke finished (touch).');
            }
        });

        // --- Button Functions for Canvas ---
        if (clearSignatureBtn) {
            clearSignatureBtn.addEventListener('click', () => {
                if (!isAcceptanceAreaEnabled) return;
                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
                initialCanvasData = signatureCanvas.toDataURL('image/png');
                addAuditRecord('Signature cleared from canvas.');
            });
        }
        if (saveSignatureBtn) {
            saveSignatureBtn.addEventListener('click', () => {
                if (!isAcceptanceAreaEnabled) return;
                saveSignature();
            });
        }

        // --- Text Signature Functionality ---
        if (renderTextSignatureBtn) {
            renderTextSignatureBtn.addEventListener('click', () => {
                if (!isAcceptanceAreaEnabled) return;
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
                    addAuditRecord('Attempted to generate text signature with empty field.');
                    return;
                }

                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.scale(window.devicePixelRatio, window.devicePixelRatio);

                document.fonts.ready.then(() => {
                    const canvasVisualWidth = signatureCanvas.offsetWidth;
                    const canvasVisualHeight = signatureCanvas.offsetHeight;
                    let fontSize = canvasVisualHeight * 0.8;
                    ctx.font = `${fontSize}px "${selectedFont}"`;
                    let textMetrics = ctx.measureText(signatureText);
                    const targetWidth = canvasVisualWidth * 0.9;
                    if (textMetrics.width > targetWidth) {
                        fontSize = (targetWidth / textMetrics.width) * fontSize;
                        ctx.font = `${fontSize}px "${selectedFont}"`;
                    }
                    const estimatedTextHeight = fontSize * 1.2;
                    const targetHeight = canvasVisualHeight * 0.9;
                    if (estimatedTextHeight > targetHeight) {
                        fontSize = (targetHeight / estimatedTextHeight) * fontSize;
                        ctx.font = `${fontSize}px "${selectedFont}"`;
                    }
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const x = canvasVisualWidth / 2;
                    const y = canvasVisualHeight / 2;
                    ctx.fillText(signatureText, x, y);
                    addAuditRecord(`Text signature "${signatureText}" generated with font "${selectedFont}".`);
                }).catch(error => {
                    console.error("Error loading font for text signature:", error);
                    addAuditRecord(`Error generating text signature: font "${selectedFont}" not loaded.`);
                    // Fallback to sans-serif
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
                    ctx.font = `${fontSize}px sans-serif`;
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const x = canvasVisualWidth / 2;
                    const y = canvasVisualHeight / 2;
                    ctx.fillText(signatureText, x, y);
                });
            });
        }

        // --- Upload Image Signature Functionality ---
        if (imageSignatureInput) {
            imageSignatureInput.addEventListener('change', (e) => {
                if (!isAcceptanceAreaEnabled) {
                    e.target.value = '';
                    return;
                }
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = new Image();
                    img.onload = () => {
                        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
                        ctx.setTransform(1, 0, 0, 1, 0, 0);
                        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);

                        const canvasVisualWidth = signatureCanvas.offsetWidth;
                        const canvasVisualHeight = signatureCanvas.offsetHeight;
                        const canvasAspectRatio = canvasVisualWidth / canvasVisualHeight;
                        const imgAspectRatio = img.width / img.height;
                        let drawWidth = canvasVisualWidth;
                        let drawHeight = canvasVisualHeight;
                        let offsetX = 0;
                        let offsetY = 0;

                        if (imgAspectRatio > canvasAspectRatio) {
                            drawHeight = drawWidth / imgAspectRatio;
                            offsetY = (canvasVisualHeight - drawHeight) / 2;
                        } else {
                            drawWidth = drawHeight * imgAspectRatio;
                            offsetX = (canvasVisualWidth - drawWidth) / 2;
                        }
                        ctx.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
                        addAuditRecord('Image signature loaded and rendered on the canvas.');
                    };
                    img.onerror = () => {
                        console.error('Error loading signature image.');
                        addAuditRecord('Error: Could not load signature image.');
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        errorMessage.innerHTML = `
                            <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                                <h3 class="text-xl font-bold text-red-600 mb-3">Error</h3>
                                <p class="text-gray-700 mb-4">No se pudo cargar la imagen. Por favor, asegúrese de que es un archivo PNG o JPG válido.</p>
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
        }

        // Save function for canvas
        function saveSignature() {
            const currentCanvasData = signatureCanvas.toDataURL('image/png');
            if (currentCanvasData !== initialCanvasData) {
                const urlParams = new URLSearchParams(window.location.search);
                const url = 'index.php';
                const signerId = urlParams.get('signerId');
                const data = {
                    module: "stic_Signatures",
                    action: "saveSignature",
                    signerId: signerId,
                    signatureData: currentCanvasData,
                };
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data),
                }).then(response => {
                    if (!response.ok) throw new Error('Network or server error');
                    console.log('Response status:', response);
                    return response.json();
                }).then(data => {
                    console.log('Signature data sent successfully:', data);
                    const successMessage = document.createElement('div');
                    successMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    successMessage.innerHTML = `
                        <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                            <h3 class="text-xl font-bold text-green-600 mb-3">Firma guardada</h3>
                            <p class="text-gray-700 mb-4">La firma se ha guardado correctamente.</p>
                            <button id="closeMessageBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                        </div>
                    `;
                    document.body.appendChild(successMessage);
                    document.getElementById('closeMessageBtn').addEventListener('click', () => {
                        successMessage.remove();
                    });
                }).catch(error => {
                    console.error('Error sending signature data:', error);
                    const warningMessage = document.createElement('div');
                    warningMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    warningMessage.innerHTML = `
                        <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                            <h3 class="text-xl font-bold text-yellow-600 mb-3">Atención</h3>
                            <p class="text-gray-700 mb-4">Por favor, dibuje su firma o use una opción alternativa antes de guardar.</p>
                            <button id="closeWarningBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                        </div>
                    `;
                    document.body.appendChild(warningMessage);
                    document.getElementById('closeWarningBtn').addEventListener('click', () => {
                        warningMessage.remove();
                    });
                    addAuditRecord('Attempted to save signature on empty canvas.');
                });
                addAuditRecord('Signature captured and ready to be sent to the server.');
            } else {
                addAuditRecord('Attempted to save an empty canvas.');
            }
        }
    }

    // --- Lógica para el modo "button" ---
    if (acceptDocumentBtn) {
        acceptDocumentBtn.addEventListener('click', () => {
            if (!isAcceptanceAreaEnabled) return;

            const urlParams = new URLSearchParams(window.location.search);
            const url = 'index.php';
            const signerId = urlParams.get('signerId');
            const data = {
                module: "stic_Signatures",
                action: "acceptDocument", // Nueva acción para aceptación por botón
                signerId: signerId,
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data),
            }).then(response => {
                if (!response.ok) throw new Error('Network or server error');
                return response.json();
            }).then(data => {
                console.log('Acceptance data sent successfully:', data);
                addAuditRecord('Document accepted via button click.');
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                successMessage.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                        <h3 class="text-xl font-bold text-green-600 mb-3">Aceptación registrada</h3>
                        <p class="text-gray-700 mb-4">Su aceptación del documento ha sido registrada correctamente.</p>
                        <button id="closeMessageBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                    </div>
                `;
                document.body.appendChild(successMessage);
                document.getElementById('closeMessageBtn').addEventListener('click', () => {
                    successMessage.remove();
                });
            }).catch(error => {
                console.error('Error sending acceptance data:', error);
                const errorMessage = document.createElement('div');
                errorMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                errorMessage.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                        <h3 class="text-xl font-bold text-red-600 mb-3">Error</h3>
                        <p class="text-gray-700 mb-4">No se pudo registrar la aceptación del documento. Por favor, inténtelo de nuevo.</p>
                        <button id="closeErrorBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Cerrar</button>
                    </div>
                `;
                document.body.appendChild(errorMessage);
                document.getElementById('closeErrorBtn').addEventListener('click', () => {
                    errorMessage.remove();
                });
            });
        });
    }

    // Initialize all common elements
    if (documentContentDiv) {
        documentContentDiv.addEventListener('scroll', checkScrollPosition);
        checkScrollPosition();
    }

    // Disable areas initially if they exist
    if (signatureCanvas) {
        signatureCanvas.classList.add('canvas-disabled');
        if (clearSignatureBtn) clearSignatureBtn.disabled = true;
        if (saveSignatureBtn) saveSignatureBtn.disabled = true;
        if (textSignatureInput) textSignatureInput.disabled = true;
        if (fontSelector) fontSelector.disabled = true;
        if (renderTextSignatureBtn) renderTextSignatureBtn.disabled = true;
        if (imageSignatureInput) imageSignatureInput.disabled = true;
    } else if (acceptDocumentBtn) {
        acceptDocumentBtn.disabled = true;
    }

    // Load initial audit records
    addAuditRecord('Document content loaded.');
    if (signatureCanvas) {
        addAuditRecord('Signature canvas and alternative options initially disabled.');
    } else if (acceptDocumentBtn) {
        addAuditRecord('Acceptance button initially disabled.');
    }
});