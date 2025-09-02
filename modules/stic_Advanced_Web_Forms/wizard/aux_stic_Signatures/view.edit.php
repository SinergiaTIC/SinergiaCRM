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
        // $this->setFields(); // Standard SugarCRM call.
        parent::display(); // Standard SugarCRM call.
        SticViews::display($this); // Custom SinergiaCRM call.
        $this->displayTMCE(); // Custom method to display TinyMCE.
    }

    /**
     * Displays the TinyMCE editor instance with custom configurations.
     * Includes jstree CSS and JS, and sets up custom TinyMCE buttons.
     */
    public function displayTMCE()
    {
        global $app_strings, $mod_strings; // Global SugarCRM language strings.
        include 'include/SuiteEditor/SuiteEditorConnector.php'; // Include SuiteEditor connector.

        require_once "include/SugarTinyMCE.php"; // SugarCRM TinyMCE helper class.
        global $locale; // Global locale object for user language.

        $userLang = explode('_', $_SESSION['authenticated_user_language'])[0]; // Get user's base language.
        $tiny = new SugarTinyMCE(); // Instantiate TinyMCE helper.
        $tinyMCE = $tiny->getConfig(); // Get base TinyMCE configuration.

        $js = <<<JS
		<script language="javascript" type="text/javascript">
		var df = '{$locale->getPrecedentPreference('default_date_format')}'; // Default date format.

        // These variables are now effectively managed by Utils.js for direct UI elements.
        var dynamicModuleItems = []; 

        // window.tinyMceInitCallbacks array is used to queue functions to run after TinyMCE is initialized.
        window.tinyMceInitCallbacks = window.tinyMceInitCallbacks || [];

 		tinyMCE.init({
			language: '{$userLang}', // Set editor language.
            language_url: 'SticInclude/vendor/tinymce/langs/{$userLang}.js', // Path to language file.
    		theme : "silver", // TinyMCE theme.
    		theme_advanced_toolbar_align : "left", // Toolbar alignment.
    		mode: "exact", // Exact mode for TinyMCE.
			elements : "body", // Target the textarea with id 'body'.
			theme_advanced_toolbar_location : "top", // Toolbar position.
			// Toolbar configuration: removed module and fields dropdowns as they are now external.
			toolbar1: 'code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            toolbar2: 'print preview media | forecolor backcolor | image | emoticons | table | link | fontselect fontsizeselect',
			theme_advanced_fonts:"Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Helvetica Neu=helveticaneue,sans-serif;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
			resize: 'both', // Allow resizing.
			code_dialog_height: 600, // Height for code dialog.
			code_dialog_width: 650, // Width for code dialog.
            // ***** MODIFICACIÓN CLAVE: EXTENDER ELEMENTOS VÁLIDOS PARA SPAN *****
            extended_valid_elements: 'style,html[xmlns],style[dir|lang|media|title|type],hr[class|width|size|noshade],@[class|style],span[class|path]', 
            // span[class|path] le dice a TinyMCE que la etiqueta span es válida y que puede tener los atributos 'class' y 'path'.
            // *******************************************************************
            custom_elements: 'style,link,~link', // Custom elements.
            content_style: "@import url('https://fonts.googleapis.com/css2?family=Open+Sans');", // Custom CSS for editor content.
			plugins : "fullpage advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools", // Enabled plugins.
			height:"500", // Editor height.
			width: "100%", // Editor width.
			inline_styles : true, // Enable inline styles.
			directionality : "ltr", // Text direction.
			remove_redundant_brs : true, // Remove redundant <br> tags.
			entity_encoding: 'raw', // Entity encoding.
			cleanup_on_startup : true, // Clean up on startup.
			strict_loading_mode : true, // Strict loading.
			convert_urls : false, // Do not convert URLs.
			plugin_insertdate_dateFormat : '{DATE '+df+'}', // Date format for insert date plugin.
			pagebreak_separator : "<div style=\"page-break-before: always;\">&nbsp;</div>", // Page break separator.
			extended_valid_elements : "textblock,barcode[*]", // Extended valid elements.
			custom_elements: "textblock", // Custom elements.
            setup: function (editor) {
                // Event listener for TinyMCE initialization.
                editor.on('init', function() {
                    window.tinyMceInitCallbacks.forEach(function(callback) {
                        if (typeof callback === 'function') {
                            callback(editor);
                        }
                    });
                    window.tinyMceInitCallbacks = []; // Clear callbacks after execution.
                });
            }
		});

		</script>

JS;
        // Echo the TinyMCE initialization JavaScript.
        echo $js;
        // Include jstree CSS.
        echo '<link rel="stylesheet" href="SticInclude/vendor/jstree/themes/default/style.min.css" />';
        // Include jstree JavaScript BEFORE Utils.js.
        echo getVersionedScript("SticInclude/vendor/jstree/jstree.min.js");
        // Include custom Utils.js script.
        echo '<link rel="stylesheet" href="modules/stic_Signatures/style.css" />';
        echo getVersionedScript("modules/stic_Signatures/Utils.js");
    }
}