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
    // --- Signature Canvas Configuration ---
    const signatureCanvas = document.getElementById('signatureCanvas');
    const ctx = signatureCanvas.getContext('2d');
    const clearSignatureBtn = document.getElementById('clearSignatureBtn');
    const saveSignatureBtn = document.getElementById('saveSignatureBtn');
    const auditRecordsDiv = document.getElementById('auditRecords');
    const documentContentDiv = document.getElementById('documentContent');
    const scrollInstructionMessage = document.getElementById('scrollInstructionMessage');

    // Elements for text signature
    const textSignatureInput = document.getElementById('textSignatureInput');
    const fontSelector = document.getElementById('fontSelector');
    const renderTextSignatureBtn = document.getElementById('renderTextSignatureBtn');

    // Elements for image signature
    const imageSignatureInput = document.getElementById('imageSignatureInput');
    const uploadImageSignatureBtn = document.getElementById('uploadImageSignatureBtn');

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let initialCanvasData = null; // Stores the state of the empty canvas
    let canvasEnabled = false; // Controls if the canvas is active

    /**
     * Enables the signature canvas and related buttons.
     */
    function enableSignatureArea() {
        if (!canvasEnabled) {
            canvasEnabled = true;
            signatureCanvas.classList.remove('canvas-disabled');
            clearSignatureBtn.disabled = false;
            saveSignatureBtn.disabled = false;
            textSignatureInput.disabled = false;
            fontSelector.disabled = false;
            renderTextSignatureBtn.disabled = false;
            imageSignatureInput.disabled = false;
            uploadImageSignatureBtn.disabled = false;

            // Hide the scroll instruction message
            scrollInstructionMessage.style.display = 'none';

            addAuditRecord('Signature canvas and alternative options activated (document read).');
            // Optionally: Remove the scroll listener once the canvas is activated
            documentContentDiv.removeEventListener('scroll', checkScrollPosition);
        }
    }

    /**
     * Checks the scroll position of the document content to enable the signature area.
     */
    function checkScrollPosition() {
        // If the user has scrolled to the end of the document
        // A small margin of 1px is added to avoid rounding issues
        if (documentContentDiv.scrollTop + documentContentDiv.clientHeight >= documentContentDiv.scrollHeight - 1) {
            enableSignatureArea();
        }
    }

    /**
     * Adjusts the canvas size for sharp rendering on high-density screens
     * and makes it responsive, maintaining a 16:9 aspect ratio and a maximum height of 250px.
     */
    function resizeCanvas() {
        // Create a temporary canvas to save the current drawing before resizing
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        // Ensure the temporary canvas has the same intrinsic dimensions
        // as the main canvas before resizing for proper capture.
        tempCanvas.width = signatureCanvas.width;
        tempCanvas.height = signatureCanvas.height;
        // Draw the current content of the main canvas onto the temporary one
        tempCtx.drawImage(signatureCanvas, 0, 0);

        const parentElement = signatureCanvas.parentElement;
        const parentWidth = parentElement.offsetWidth; // Available width of the container
        const maxHeightCSS = 250; // Desired maximum height in CSS pixels

        let calculatedWidthCSS = parentWidth;
        let calculatedHeightCSS = calculatedWidthCSS * (9 / 16); // Initial height based on 16:9 of full width

        // If the calculated height exceeds the maximum height, adjust
        if (calculatedHeightCSS > maxHeightCSS) {
            calculatedHeightCSS = maxHeightCSS;
            calculatedWidthCSS = maxHeightCSS * (16 / 9); // Recalculate width to maintain 16:9 with max height
        }

        // Ensure the calculated width does not exceed the parent's width
        if (calculatedWidthCSS > parentWidth) {
            calculatedWidthCSS = parentWidth;
            calculatedHeightCSS = calculatedWidthCSS * (9 / 16);
        }

        // Apply the calculated dimensions to the canvas's CSS style
        signatureCanvas.style.width = `${calculatedWidthCSS}px`;
        signatureCanvas.style.height = `${calculatedHeightCSS}px`;

        // Set the intrinsic dimensions (drawing resolution) of the canvas
        // Multiply by devicePixelRatio for sharpness on high-density (Retina) screens
        signatureCanvas.width = calculatedWidthCSS * window.devicePixelRatio;
        signatureCanvas.height = calculatedHeightCSS * window.devicePixelRatio;

        // Clear the context and reset the transformation before applying the new scale
        // This is crucial to avoid cumulative scaling issues or blurry drawings.
        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        ctx.setTransform(1, 0, 0, 1, 0, 0); // Reset the transformation matrix to identity
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Apply the new scaling

        // Restore the drawing from the temporary canvas to the resized main canvas
        // It's important to draw using the *display* dimensions (calculatedWidthCSS, calculatedHeightCSS)
        // so that the drawing scales correctly to the canvas's new size.
        ctx.drawImage(tempCanvas, 0, 0, calculatedWidthCSS, calculatedHeightCSS);

        // Reset context style properties after scaling
        ctx.lineCap = 'round';     // Rounded line ends
        ctx.lineJoin = 'round';    // Rounded line joins
        ctx.lineWidth = 3;         // Line width for the signature
        ctx.strokeStyle = '#333';  // Signature color

        // Capture the Data URL of an empty canvas only the first time it's initialized
        // or when explicitly cleared. This is used to compare if the canvas is truly empty.
        if (initialCanvasData === null || initialCanvasData === 'reset') { // 'reset' to force recapture on resize
            // To get a Data URL of a *truly* empty canvas,
            // we temporarily clear it, get the URL, and then restore the content.
            const originalImageData = ctx.getImageData(0, 0, signatureCanvas.width, signatureCanvas.height);
            ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            initialCanvasData = signatureCanvas.toDataURL('image/png');
            ctx.putImageData(originalImageData, 0, 0); // Restore original content
        }
    }

    // Initialize canvas size on page load and capture initial empty state
    resizeCanvas();

    // Adjust canvas size if the window is resized
    window.addEventListener('resize', () => {
        initialCanvasData = 'reset'; // Mark to recapture empty state after resize
        resizeCanvas();
        // After resizing, scroll position might change, so re-check
        checkScrollPosition();
    });

    /**
     * Adds an audit record to the audit trail area.
     * @param {string} message The message to record.
     */
    function addAuditRecord(message) {
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
        auditRecordsDiv.prepend(recordDiv); // Add to the beginning to see the most recent
    }

    // --- Drawing Logic for Mouse ---
    signatureCanvas.addEventListener('mousedown', (e) => {
        if (!canvasEnabled) return; // Do not allow drawing if canvas is disabled
        isDrawing = true;
        // Automatic canvas clearing is removed here to allow multiple strokes.
        // The canvas will only be cleared with the "Clear" button or when using text/image options.
        [lastX, lastY] = [e.offsetX, e.offsetY];
        addAuditRecord('Signature drawing started.');
    });

    signatureCanvas.addEventListener('mousemove', (e) => {
        if (!isDrawing || !canvasEnabled) return; // Do not allow drawing if canvas is disabled
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    signatureCanvas.addEventListener('mouseup', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Signature stroke finished.'); // Message changed to reflect end of stroke, not full signature
        }
    });

    signatureCanvas.addEventListener('mouseout', () => {
        if (isDrawing) {
            isDrawing = false;
            addAuditRecord('Signature stroke interrupted (cursor left canvas).'); // Message changed
        }
    });

    // --- Drawing Logic for Touch (Mobile) ---
    signatureCanvas.addEventListener('touchstart', (e) => {
        if (!canvasEnabled) return; // Do not allow drawing if canvas is disabled
        e.preventDefault(); // Prevent page scrolling
        isDrawing = true;
        // Automatic canvas clearing is removed here to allow multiple strokes.
        const touch = e.touches[0];
        const rect = signatureCanvas.getBoundingClientRect();
        lastX = touch.clientX - rect.left;
        lastY = touch.clientY - rect.top;
        addAuditRecord('Signature drawing started (touch).');
    }, { passive: false });

    signatureCanvas.addEventListener('touchmove', (e) => {
        if (!isDrawing || !canvasEnabled) return; // Do not allow drawing if canvas is disabled
        e.preventDefault(); // Prevent page scrolling
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
            addAuditRecord('Signature stroke finished (touch).'); // Message changed
        }
    });

    // --- Button Functions ---
    clearSignatureBtn.addEventListener('click', () => {
        if (!canvasEnabled) return; // Do not allow action if canvas is disabled
        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        initialCanvasData = signatureCanvas.toDataURL('image/png'); // Recapture empty state
        addAuditRecord('Signature cleared from canvas.');
    });

    saveSignatureBtn.addEventListener('click', () => {

        saveSignature();

    });

    // --- Text Signature Functionality ---
    renderTextSignatureBtn.addEventListener('click', () => {
        if (!canvasEnabled) return;
        const signatureText = textSignatureInput.value.trim();
        const selectedFont = fontSelector.value;

        if (signatureText === '') {
            const warningMessage = document.createElement('div');
            warningMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            warningMessage.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                    <h3 class="text-xl font-bold text-yellow-600 mb-3">Attention</h3>
                    <p class="text-gray-700 mb-4">Please enter your name to generate the text signature.</p>
                    <button id="closeWarningBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Close</button>
                </div>
            `;
            document.body.appendChild(warningMessage);

            document.getElementById('closeWarningBtn').addEventListener('click', () => {
                warningMessage.remove();
            });
            addAuditRecord('Attempted to generate text signature with empty field.');
            return;
        }

        ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height); // Clear canvas
        ctx.setTransform(1, 0, 0, 1, 0, 0); // Reset transformation before drawing text
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Apply scale for sharpness

        // Try to load the font if not already loaded (though CSS @import should handle it)
        document.fonts.ready.then(() => {
            const canvasVisualWidth = signatureCanvas.offsetWidth;
            const canvasVisualHeight = signatureCanvas.offsetHeight;

            // Start with a large font size and reduce if necessary
            let fontSize = canvasVisualHeight * 0.8; // Try to occupy most of the height
            ctx.font = `${fontSize}px "${selectedFont}"`;
            let textMetrics = ctx.measureText(signatureText);

            // Calculate the necessary scaling factor for the text to fit the desired width
            const targetWidth = canvasVisualWidth * 0.9; // 90% of canvas width for padding
            if (textMetrics.width > targetWidth) {
                fontSize = (targetWidth / textMetrics.width) * fontSize;
                ctx.font = `${fontSize}px "${selectedFont}"`; // Update font with new size
                textMetrics = ctx.measureText(signatureText); // Remeasure with new size
            }

            // Calculate the actual text height (approximate, as textMetrics doesn't give exact font height)
            // A more precise way would be to use textMetrics.actualBoundingBoxAscent + textMetrics.actualBoundingBoxDescent
            // but this can vary between browsers and fonts. We will use an estimation based on fontSize.
            const estimatedTextHeight = fontSize * 1.2; // Adjustment factor for actual font height

            // If the estimated height is greater than the desired maximum height
            const targetHeight = canvasVisualHeight * 0.9; // 90% of canvas height for padding
            if (estimatedTextHeight > targetHeight) {
                fontSize = (targetHeight / estimatedTextHeight) * fontSize;
                ctx.font = `${fontSize}px "${selectedFont}"`; // Update font with new size
            }

            ctx.fillStyle = '#333'; // Text color
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Calculate position to center the text
            const x = canvasVisualWidth / 2;
            const y = canvasVisualHeight / 2;

            ctx.fillText(signatureText, x, y);
            addAuditRecord(`Text signature "${signatureText}" generated with font "${selectedFont}".`);
        }).catch(error => {
            console.error("Error loading font for text signature:", error);
            addAuditRecord(`Error generating text signature: font "${selectedFont}" not loaded.`);
            // Try to draw with a fallback font
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
            ctx.font = `${fontSize}px sans-serif`; // Apply adjusted size to fallback font
            ctx.fillStyle = '#333';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const x = canvasVisualWidth / 2;
            const y = canvasVisualHeight / 2;
            ctx.fillText(signatureText, x, y);
        });
    });

    // --- Upload Image Signature Functionality ---
    imageSignatureInput.addEventListener('change', (e) => {
        if (!canvasEnabled) {
            imageSignatureInput.value = ''; // Clear input if not enabled
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
                ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height); // Clear canvas
                ctx.setTransform(1, 0, 0, 1, 0, 0); // Reset transformation
                ctx.scale(window.devicePixelRatio, window.devicePixelRatio); // Apply scale

                // Calculate dimensions to fit the image to the canvas while maintaining aspect ratio
                const canvasVisualWidth = signatureCanvas.offsetWidth;
                const canvasVisualHeight = signatureCanvas.offsetHeight;
                const canvasAspectRatio = canvasVisualWidth / canvasVisualHeight;
                const imgAspectRatio = img.width / img.height;

                let drawWidth = canvasVisualWidth;
                let drawHeight = canvasVisualHeight;
                let offsetX = 0;
                let offsetY = 0;

                if (imgAspectRatio > canvasAspectRatio) {
                    // Image is wider than canvas, adjust to canvas width
                    drawHeight = drawWidth / imgAspectRatio;
                    offsetY = (canvasVisualHeight - drawHeight) / 2;
                } else {
                    // Image is taller than canvas, adjust to canvas height
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
                        <p class="text-gray-700 mb-4">Could not load the image. Please ensure it is a valid PNG or JPG file.</p>
                        <button id="closeErrorBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Close</button>
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

    // --- Load Audit Records (Simulated) ---
    function loadAuditRecords() {
        setTimeout(() => {
            addAuditRecord('Document ready for signature.');
            addAuditRecord('Welcome to the signature portal!');
        }, 500);
    }

    // Load audit records on initialization
    loadAuditRecords();

    // Disable canvas and buttons initially
    signatureCanvas.classList.add('canvas-disabled');
    clearSignatureBtn.disabled = true;
    saveSignatureBtn.disabled = true;
    textSignatureInput.disabled = true;
    fontSelector.disabled = true;
    renderTextSignatureBtn.disabled = true;
    imageSignatureInput.disabled = true;
    uploadImageSignatureBtn.disabled = true;

    addAuditRecord('Signature canvas and alternative options initially disabled.');

    // Add scroll listener to document content
    documentContentDiv.addEventListener('scroll', checkScrollPosition);

    // Check scroll position on page load in case content is short
    // and scroll is already at the bottom.
    checkScrollPosition();


    function saveSignature() {
        if (!canvasEnabled) return; // Do not allow action if canvas is disabled
        const currentCanvasData = signatureCanvas.toDataURL('image/png');
        console.log('Initial Canvas Data:', currentCanvasData);


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

                // Manage the response
                if (!response.ok) {
                    throw new Error('Error de red o del servidor');
                }
                return response.json();
                // Parsea la respuesta como JSON
            }
            ).then(data => {
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





            }
            ).catch(error => {
                console.error('Error sending signature data:', error);
                const warningMessage = document.createElement('div');
                warningMessage.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                warningMessage.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm mx-4">
                    <h3 class="text-xl font-bold text-yellow-600 mb-3">Attention</h3>
                    <p class="text-gray-700 mb-4">Please draw your signature or use one of the alternative options before saving.</p>
                    <button id="closeWarningBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Close</button>
                </div>
            `;
                document.body.appendChild(warningMessage);

                document.getElementById('closeWarningBtn').addEventListener('click', () => {
                    warningMessage.remove();
                });
                addAuditRecord('Attempted to save signature on empty canvas.');
            }
            );



            addAuditRecord('Signature captured and ready to be sent to the server.');



        }
    }

});