<?php
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

/**
 * Visualizador de Suitepicons
 * Lee los glifos del archivo SCSS y utiliza las fuentes de la ruta confirmada.
 */

// --- CONFIGURACIÓN ---
// Ruta al archivo donde están definidos los nombres y códigos
$scssFile = 'SuiteP/css/suitep-base/suitepicon-glyphs.scss';

// Ruta donde están los archivos de fuente (.woff, .ttf) relativa a la raíz del CRM
$fontDir = 'SuiteP/css/suitep-base/';

// --- LÓGICA DE EXTRACCIÓN ---
$icons = [];
if (file_exists($scssFile)) {
    $content = file_get_contents($scssFile);
    
    // Buscamos definiciones tipo: .suitepicon-action-edit:before { content: "\e904"; }
    // Esta Regex captura: Grupo 1 (Nombre clase), Grupo 2 (Código Unicode)
    preg_match_all('/\.((?:suitepicon|glyphicon)-[\w-]+)::?before\s*\{\s*content:\s*["\']\\\([a-fA-F0-9]+)["\']/', $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $icons[$match[1]] = $match[2];
    }
    ksort($icons); // Ordenar alfabéticamente
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Suitepicons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 1. DEFINICIÓN DE LA FUENTE */
        @font-face {
            font-family: 'SuiteP_Internal';
            src: url('<?php echo $fontDir; ?>suitepicon.woff') format('woff'),
                 url('<?php echo $fontDir; ?>suitepicon.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* 2. CLASE BASE */
        .my-icon {
            font-family: 'SuiteP_Internal', sans-serif !important;
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
        }

        /* 3. GENERACIÓN DE LOS GLIFOS (CSS Dinámico) */
        <?php foreach($icons as $class => $code): ?>
        .<?php echo $class; ?>:before { content: "\<?php echo $code; ?>"; }
        <?php endforeach; ?>

        /* 4. ESTILOS DE LA UI */
        body { 
            background-color: #f4f6f9; 
            padding: 40px 20px; 
            font-family: system-ui, -apple-system, sans-serif; 
        }
        
        .header-area { margin-bottom: 40px; text-align: center; }
        .search-sticky { 
            position: sticky; top: 20px; z-index: 100; 
            max-width: 600px; margin: 0 auto 30px auto; 
        }
        
        .icon-card {
            background: white;
            border: 1px solid #e1e4e8;
            border-radius: 12px; /* Más redondeado */
            padding: 25px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .icon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: #0d6efd;
        }
        
        .icon-glyph {
            font-size: 36px; /* Icono grande */
            margin-bottom: 15px;
            color: #555;
            transition: transform 0.2s;
        }
        
        .icon-card:hover .icon-glyph {
            color: #0d6efd;
            transform: scale(1.2);
        }
        
        .icon-name {
            font-size: 11px;
            color: #6c757d;
            font-family: 'Consolas', monospace;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            word-break: break-all;
        }

        /* TOAST PERSONALIZADO */
        #toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 50px;
            padding: 12px 24px;
            position: fixed;
            z-index: 1000;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s, bottom 0.3s;
        }
        #toast.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <h1 class="fw-bold">🎨 Galería Suitepicons</h1>
        <p class="text-muted">Total: <strong><?php echo count($icons); ?></strong> iconos disponibles</p>
    </div>

    <div class="search-sticky">
        <input type="text" id="search" class="form-control form-control-lg shadow-sm rounded-pill" 
               placeholder="🔍 Buscar (ej: edit, user, action)..." autocomplete="off" autofocus>
    </div>

    <?php if(empty($icons)): ?>
        <div class="alert alert-warning text-center">
            No se han encontrado iconos. Verifica que el archivo <code><?php echo $scssFile; ?></code> contiene las clases.
        </div>
    <?php else: ?>
        <div class="row g-3" id="icon-grid">
            <?php foreach($icons as $class => $code): 
                // Limpiamos el nombre para la búsqueda (sin el prefijo)
                $cleanName = str_replace(['suitepicon-', 'glyphicon-'], '', $class);
            ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 icon-item" data-search="<?php echo $class . ' ' . $cleanName; ?>">
                <div class="icon-card" onclick="copyToClipboard('<?php echo $class; ?>')">
                    <div class="icon-glyph">
                        <i class="my-icon <?php echo $class; ?>"></i>
                    </div>
                    <div class="icon-name"><?php echo $cleanName; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="toast">¡Copiado al portapapeles!</div>

<script>
    // 1. Filtrado en tiempo real
    const searchInput = document.getElementById('search');
    const items = document.querySelectorAll('.icon-item');

    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        items.forEach(item => {
            const text = item.getAttribute('data-search').toLowerCase();
            item.style.display = text.includes(term) ? '' : 'none';
        });
    });

    // 2. Copiar al portapapeles y mostrar Toast
    function copyToClipboard(cls) {
        // Generamos el código HTML standard para SuiteCRM
        const html = `<span class="suitepicon ${cls}"></span>`;
        
        navigator.clipboard.writeText(html).then(() => {
            showToast(`Copiado: <span class="fw-bold">${cls}</span>`);
        }).catch(err => {
            console.error('Error al copiar', err);
        });
    }

    // 3. Gestión del Toast
    let toastTimeout;
    function showToast(htmlMsg) {
        const t = document.getElementById("toast");
        t.innerHTML = htmlMsg; // Permite HTML dentro del toast
        t.classList.add("show");
        
        // Reiniciar el temporizador si hacemos muchos clics rápidos
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => { 
            t.classList.remove("show"); 
        }, 2000);
    }
</script>

</body>
</html>