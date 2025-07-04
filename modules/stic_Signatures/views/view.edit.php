<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


#[\AllowDynamicProperties]
class stic_SignaturesViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        // $this->setFields();
        parent::display();
        $this->displayTMCE();
    }



    public function displayTMCE()
    {
		include 'include/SuiteEditor/SuiteEditorConnector.php';

        require_once("include/SugarTinyMCE.php");
        global $locale;
		$userLang = explode('_', $_SESSION['authenticated_user_language'])[0];
        $tiny = new SugarTinyMCE();
        $tinyMCE = $tiny->getConfig();

        $js =<<<JS
		<script language="javascript" type="text/javascript">
		var df = '{$locale->getPrecedentPreference('default_date_format')}';

 		tinyMCE.init({
			language: '{$userLang}',
            language_url: 'SticInclude/vendor/tinymce/langs/{$userLang}.js',
    		theme : "silver",
    		theme_advanced_toolbar_align : "left",
    		mode: "exact",
			elements : "main_html",
			theme_advanced_toolbar_location : "top",
			toolbar1: 'code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            toolbar2: 'print preview media | forecolor backcolor | image | emoticons | table | link | fontselect fontsizeselect',
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
		});

		

		</script>

JS;
        echo $js;
		echo getVersionedScript("modules/stic_Signatures/Utils.js");
    }
}
