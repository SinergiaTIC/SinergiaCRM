<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

function post_install()
{
    global $db;

    echo "<br><span style='color: #0000FF; font-weight: bold;'>[TTS] Iniciando instalaci&oacute;n de Text-to-Speech...</span><br>";

    $sqlFile = 'custom/Extension/application/Ext/Sql/tts_deepgram_usage.sql';
    if (file_exists($sqlFile)) {
        $sqlContent = file_get_contents($sqlFile);
        $sqlContent = preg_replace('/-- .*$/m', '', $sqlContent);
        $statements = explode(';', $sqlContent);
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (!empty($stmt)) {
                $db->query($stmt);
            }
        }
        echo "<span style='color: green;'>✓ SQL ejecutado: tabla tts_usage creada y settings insertados.</span><br>";
    } else {
        echo "<span style='color: orange;'>⚠ No se encontr&oacute; $sqlFile. Los settings deber&aacute;n configurarse manualmente.</span><br>";
    }

    $configFile = 'config_override.php';
    if (file_exists($configFile)) {
        $configContent = file_get_contents($configFile);
        if (strpos($configContent, 'tts_provider') === false) {
            $configContent = rtrim($configContent);
            if (substr($configContent, -2) === '?>') {
                $configContent = substr($configContent, 0, -2);
            }
            $configContent .= "\n\n// TTS (Text-to-Speech) - Deepgram technical configuration\n";
            $configContent .= "\$sugar_config['tts_provider'] = 'deepgram';\n";
            $configContent .= "\$sugar_config['deepgram_tts_endpoint'] = 'https://api.eu.deepgram.com/v1/speak';\n";
            $configContent .= "\$sugar_config['tts_max_chars_per_request'] = 2000;\n";
            $configContent .= "\$sugar_config['tts_encoding'] = 'mp3';\n";
            $configContent .= "\$sugar_config['tts_curl_timeout'] = 30;\n";
            file_put_contents($configFile, $configContent);
            echo "<span style='color: green;'>✓ Configuraci&oacute;n de Deepgram a&ntilde;adida a config_override.php.</span><br>";
        } else {
            echo "<span style='color: orange;'>⚠ La configuraci&oacute;n de Deepgram ya existe en config_override.php. Omitiendo.</span><br>";
        }
    } else {
        echo "<span style='color: orange;'>⚠ No se encontr&oacute; config_override.php. A&ntilde;ade la configuraci&oacute;n de Deepgram manualmente.</span><br>";
    }

    echo "<br><span style='color: #0000FF; font-weight: bold;'>[TTS] Reconstruyendo extensiones y limpiando cach&eacute;...</span><br>";

    require_once 'ModuleInstall/ModuleInstaller.php';
    $mi = new ModuleInstaller();
    $mi->rebuild_extensions();

    if (file_exists('cache/jsLanguage')) {
        rmdir_recursive('cache/jsLanguage');
    }

    require_once 'modules/Administration/QuickRepairAndRebuild.php';
    $repair = new RepairAndClear();
    $repair->repairAndClearAll(
        ['clearVardefs', 'clearLanguageCache', 'clearTpls'],
        ['Accounts', 'Contacts', 'Notes'],
        true,
        false
    );

    echo "<br><span style='color: #0000FF; font-weight: bold;'>[TTS] Instalaci&oacute;n completada.</span><br>";
    echo "<p><b>Pasos restantes:</b></p>";
    echo "<ol>";
    echo "<li>Configura la API key en <b>Admin &gt; stic_Settings</b> (tipo TTS_DEEPGRAM: TTS_DEEPGRAM_API_KEY)</li>";
    echo "<li>Configura los m&oacute;dulos y campos en <b>Admin &gt; stic_Settings</b> (tipo TTS: TTS_TEXTAREAS, TTS_HIGHLIGHT_FIELDS, etc.)</li>";
    echo "<li>Cierra sesi&oacute;n y vuelve a entrar para que los cambios tengan efecto</li>";
    echo "</ol>";
    echo "<p><b>M&oacute;dulo Instalado Con &eacute;xito</b></p>";
}
