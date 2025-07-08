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
/* HEADER */
// Set module name
var module = "stic_Signatures";

/* INCLUDES */


/* VALIDATION DEPENDENCIES */
var validationDependencies = {

};

/* VALIDATION CALLBACKS */

/* VIEWS CUSTOM CODE */


/* AUX. FUNCTIONS */

switch (viewType()) {
    case "edit":
    case "quickcreate":
    case "popup":
        setAutofill(["name"]);


        break;
    case "detail":

        break;
    case "list":
        break;
    default:
        break;
}


if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
    // Si TinyMCE ya está inicializado, ejecuta las acciones inmediatamente
    editorReadyFunctions(tinymce.activeEditor);
} else {
    // Si no, suscribe la función al array de callbacks para que se ejecute cuando esté listo.
    // Asegúrate de que 'window.tinyMceInitCallbacks' es accesible (definido en view.edit.php)
    window.tinyMceInitCallbacks = window.tinyMceInitCallbacks || []; // Asegura que el array existe
    window.tinyMceInitCallbacks.push(editorReadyFunctions);
}


// TinyMCE editor initialization 
function editorReadyFunctions(editor) {
    renameModulesDropdownButton($('#main_module').find("option:selected").text());
}


// Event listener for the main module dropdown change
$('#main_module').on('change', function () {
    renameModulesDropdownButton($(this).find("option:selected").text());
})


var currentModuleList, currentFieldList;


/* * Retrieves a list of modules or fields related to the specified module.
 *
 * @param {string} moduleName - The name of the module to retrieve relationships for.
 * @param {string} [modulesOrFields='modules'] - Specifies whether to retrieve 'modules' or 'fields'.
 * @returns {Array} An array of module names or field options.
 */
function getModuleOrFieldList(moduleName, modulesOrFields = 'modules') {
    var moduleList = [];

    $.ajax({
        url: location.href.slice(0, location.href.indexOf(location.search)),
        type: "POST",
        async: false, // Se mantiene síncrono según la solicitud de no cambiar la funcionalidad
        dataType: "json", // jQuery parseará automáticamente la respuesta como JSON
        data: {
            module: "stic_Signatures",
            action: "getRelationships",
            getmodule: moduleName,
            format: "json",
        },
        success: function (response) {
            // 'response' ya es un objeto JavaScript debido a 'dataType: "json"'
            if (response && response.module) {
                if (modulesOrFields === 'fields') {
                    moduleList = response.option;
                } else {
                    moduleList = response.module;
                }
            } else {
                console.error("No se pudo recuperar la lista de módulos. Respuesta:", response);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al recuperar la lista de módulos:", status, error);
        }
    });

    return moduleList;
}





/**
 * Cambia el texto visible del botón desplegable "Módulos" en la barra de herramientas de TinyMCE.
 *
 * @param {string} newName El nuevo nombre para el botón.
 */
function renameModulesDropdownButton(newName) {
    var editor = tinymce.get('body');

    if (newName === 'Home' || newName === null || newName.trim() === '') {
        return;
    }

    newName = SUGAR.language.languages.app_strings['LBL_MODULE'] + ': ' + newName;

    if (!editor) {
        console.error("Editor de TinyMCE no encontrado. Asegúrate de que el ID del textarea es 'body' y el editor está inicializado.");
        return;
    }

    // Los botones de TinyMCE 5+ generalmente tienen la clase 'tox-tbtn'.
    // El texto visible está dentro de un <span> con la clase 'tox-tbtn__select-label'.
    // El título (tooltip) está en el atributo 'title' del botón.
    // La etiqueta de accesibilidad está en el atributo 'aria-label'.

    // Intentamos encontrar el botón por su título o aria-label actuales.
    // Nota: El valor 'Módulos' aquí debe coincidir con el texto actual del botón.
    // Si tu TinyMCE está en otro idioma o el botón ya ha sido renombrado,
    // deberías ajustar esta parte para que coincida con el valor actual.
    var $button = $('.tox-tbtn[title="' + currentModulesButtonName + '"], .tox-tbtn[aria-label="' + currentModulesButtonName + '"]');

    if ($button.length === 0) {
        // Fallback: Si no se encuentra por título/aria-label, intenta buscar por el texto visible
        // (menos fiable si hay múltiples botones con el mismo texto visible)
        $button = $('.tox-tbtn:has(.tox-tbtn__select-label:contains("' + currentModulesButtonName + '"))');
    }


    if ($button.length > 0) {
        // Actualiza el texto visible dentro del botón
        $button.find('.tox-tbtn__select-label').text(newName);
        // Actualiza el atributo title (tooltip)
        $button.attr('title', newName);
        // Actualiza el atributo aria-label (accesibilidad)
        $button.attr('aria-label', newName);
        currentModulesButtonName = newName; // Actualiza la variable global para reflejar el nuevo nombre

        console.log(`Nombre del botón 'Módulos' cambiado a '${newName}'.`);
    } else {
        console.warn("No se encontró el botón 'Módulos' en la barra de herramientas de TinyMCE.");
        console.warn("Asegúrate de que el botón existe y su texto/título actual es 'Módulos' para que esta función pueda encontrarlo.");
    }
}