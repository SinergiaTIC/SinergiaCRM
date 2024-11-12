<?php
// Execute Rector in custom folder to migrate to PHP 8.2

if (!file_exists('vendor/rector/rector/bin/rector')) {
    echo "Rector is not installed. Can not run script<br />";
    exit(1);
}

$configFile = 'SticRectorConfig.php'; 
// $command = escapeshellcmd("php vendor/rector/rector/bin/rector process --dry-run --config={$configFile}");
$command = escapeshellcmd("php vendor/rector/rector/bin/rector process --config={$configFile}");

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    echo "Error running Rector:<br />" . implode("<br />", $output);
    exit($returnVar);
}

echo "Rector executed withot errors in custom folder.<br />";