<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

#[\AllowDynamicProperties]
class stic_SignaturesViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {
        // $this->setFields();
        parent::display();
        SticViews::display($this);
        $this->displayTMCE();
    }

    public function displayTMCE()
    {
        global $app_strings, $mod_strings;
        include 'include/SuiteEditor/SuiteEditorConnector.php';
        
        require_once "include/SugarTinyMCE.php";
        global $locale;

        $userLang = explode('_', $_SESSION['authenticated_user_language'])[0];
        $tiny = new SugarTinyMCE();
        $tinyMCE = $tiny->getConfig();
        
        
        

        $js = <<<JS
		<script language="javascript" type="text/javascript">
		var df = '{$locale->getPrecedentPreference('default_date_format')}';

        var dynamicModuleItems = [
            // {
            //     type: 'menuitem',
            //     text: 'Elemento Inicial de Prueba',
            //     onAction: function () {
            //         alert('¡Elemento Inicial de Prueba clickeado!');
            //     }
            // }
        ];

		var currentModulesButtonName = '{$app_strings['LBL_MODULE']}';


        // Define un array global para almacenar callbacks que se ejecutarán cuando TinyMCE esté listo.
        // Inicializa solo si no existe ya.
        window.tinyMceInitCallbacks = window.tinyMceInitCallbacks || [];

 		tinyMCE.init({
			language: '{$userLang}',
            language_url: 'SticInclude/vendor/tinymce/langs/{$userLang}.js',
    		theme : "silver",
    		theme_advanced_toolbar_align : "left",
    		mode: "exact",
			elements : "body",
			theme_advanced_toolbar_location : "top",
			toolbar1: 'code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | dynamicModulesButton',
            toolbar2: 'print preview media | forecolor backcolor | image | emoticons | table | link | fontselect fontsizeselect | modulesDropdownButton fieldsDropdownButton',
			theme_advanced_fonts:"Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Helvetica Neu=helveticaneue,sans-serif;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
			resize: 'both',
			code_dialog_height: 600,
			code_dialog_width: 650,
			extended_valid_elements: 'style,html[xmlns],style[dir|lang|media|title|type],hr[class|width|size|noshade],@[class|style]',
            custom_elements: 'style,link,~link',
            content_style: "@import url('https://fonts.googleapis.com/css2?family=Open+Sans');",
			plugins : "fullpage advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools",
			height:"500",
			width: "100%",
			inline_styles : true,
			directionality : "ltr",
			remove_redundant_brs : true,
			entity_encoding: 'raw',
			cleanup_on_startup : true,
			strict_loading_mode : true,
			convert_urls : false,
			plugin_insertdate_dateFormat : '{DATE '+df+'}',
			pagebreak_separator : "<div style=\"page-break-before: always;\">&nbsp;</div>",
			extended_valid_elements : "textblock,barcode[*]",
			custom_elements: "textblock",
            setup: function (editor) {
                // Este listener del evento 'init' es el punto clave.
                editor.on('init', function() {
                    // Ejecuta todos los callbacks que se hayan suscrito
                    window.tinyMceInitCallbacks.forEach(function(callback) {
                        if (typeof callback === 'function') {
                            callback(editor); // Pasa la instancia del editor a cada callback
                        }
                    });
                    // Opcional: Vacía el array si los callbacks son de un solo uso
                    window.tinyMceInitCallbacks = [];
                });

                editor.ui.registry.addMenuButton('modulesDropdownButton', {
                    text: '{$app_strings['LBL_MODULE']}',
                    fetch: function (callback) {
                        callback(dynamicModuleItems);
                    },

                });

                editor.ui.registry.addMenuButton('fieldsDropdownButton', {
                    text: '{$app_strings['LBL_AVAILABLE_FIELDS']}',
                    fetch: function (callback) {
                        callback(dynamicFieldsItems);
                    },

                });
            }
		});

		</script>

JS;
        


        echo $js;
        echo getVersionedScript("modules/stic_Signatures/Utils.js");
    }
}
