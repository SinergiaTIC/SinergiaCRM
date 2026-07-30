<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

function post_uninstall()
{
    global $db;

    echo "<br><span style='color: #0000FF; font-weight: bold;'>[TTS] Desinstalando Text-to-Speech...</span><br>";

    $db->query('DROP TABLE IF EXISTS tts_usage');
    echo "<span style='color: green;'>✓ Tabla tts_usage eliminada.</span><br>";

    $db->query("DELETE FROM stic_settings WHERE type IN ('TTS', 'TTS_DEEPGRAM')");
    echo "<span style='color: green;'>✓ Settings de TTS y TTS_DEEPGRAM eliminados de stic_settings.</span><br>";

    $configFile = 'config_override.php';
    if (file_exists($configFile)) {
        $lines = file($configFile, FILE_IGNORE_NEW_LINES);
        $newLines = [];
        foreach ($lines as $line) {
            if (strpos($line, '// TTS (Text-to-Speech) - Deepgram technical configuration') !== false) {
                continue;
            }
            if (strpos($line, "\$sugar_config['tts_") !== false) {
                continue;
            }
            if (strpos($line, "\$sugar_config['deepgram_tts_") !== false) {
                continue;
            }
            $newLines[] = $line;
        }
        file_put_contents($configFile, implode("\n", $newLines));
        echo "<span style='color: green;'>✓ Configuraci&oacute;n de Deepgram eliminada de config_override.php.</span><br>";
    }

    echo "<br><span style='color: #0000FF; font-weight: bold;'>[TTS] Desinstalaci&oacute;n completada.</span><br>";
}
