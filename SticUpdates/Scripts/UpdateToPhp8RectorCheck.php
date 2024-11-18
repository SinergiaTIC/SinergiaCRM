<?php
// Execute Rector in custom folder to migrate to PHP 8.2

$rectorDir = __DIR__.'/../../SticInclude/vendor/rector-standalone/vendor/rector/rector/bin/rector';

if (!file_exists($rectorDir)) {
    echo "Rector is not installed. Can not run script<br />";
    exit(1);
}

$configFile = __DIR__.'/../../SticRectorConfig.php'; 
// $command = escapeshellcmd("php $rectorDir process --dry-run --config={$configFile}");
$command = escapeshellcmd("php $rectorDir process --config={$configFile}");

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    echo "Error running Rector:<br />" . implode("<br />", $output);
    exit($returnVar);
}

echo "Rector executed withot errors in custom folder.<br />";