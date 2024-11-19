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
 * This script contains Executes Rector during the update of SinergiaCRM instances,
 * using the Rector configuration defined in SticRectorConfig.php on the root folder
 */
// declare (strict_types=1);
// require_once __DIR__ . '/../../SticInclude/vendor/rector-standalone/vendor/rector/rector/vendor/autoload.php';
// require_once __DIR__ . '/../../SticInclude/vendor/rector-standalone/vendor/rector/rector/vendor/scoper-autoload.php';
// require_once __DIR__ . '/../../SticInclude/vendor/rector-standalone/vendor/autoload.php';

// use RectorPrefix202411\Nette\Utils\Json;
// use Rector\Bootstrap\RectorConfigsResolver;
// use Rector\ChangesReporting\Output\JsonOutputFormatter;
// use Rector\Configuration\Option;
// use Rector\Console\Style\SymfonyStyleFactory;
// use Rector\DependencyInjection\LazyContainerFactory;
// use Rector\DependencyInjection\RectorContainerFactory;
// use Rector\Util\Reflection\PrivatesAccessor;
// use RectorPrefix202411\Symfony\Component\Console\Application;
// use RectorPrefix202411\Symfony\Component\Console\Command\Command;
// use RectorPrefix202411\Symfony\Component\Console\Input\ArgvInput;
// use Rector\ValueObject\Bootstrap\BootstrapConfigs;

echo "<h2>Rector execution</h2>";
$rectorFile = __DIR__.'/../../SticInclude/vendor/rector-standalone/vendor/rector/rector/bin/rector.php';
$rectorConfigFile = __DIR__.'/../../SticRectorConfig.php';
if (!file_exists($rectorFile)) {
    echo "<h3>Error</h3>";
    echo "Rector is not installed. Can not run script<br />";
    exit(1);
}

$rectorCommand = "php {$rectorFile} process --dry-run --config={$rectorConfigFile}";// --output-format json";

$descriptorspec = [
    1 => ['pipe', 'w'], // stdout
    2 => ['pipe', 'w'], // stderr
];

// Open process
$process = proc_open($rectorCommand, $descriptorspec, $pipes);
if (is_resource($process)) {
    // Read standard output
    $stdout = stream_get_contents($pipes[1]);
    $stdout = str_replace("\\n", "\n", $stdout);
    fclose($pipes[1]);

    // Read error output
    $stderr = stream_get_contents($pipes[2]);
    $stderr = str_replace("\\n", "\n", $stderr);
    fclose($pipes[2]);

    // Close process
    $returnCode = proc_close($process);
    echo "<h3>Return code</h3>";
    echo $returnCode;

    // Show captured output
    echo "<h3>Output</h3>";
    echo nl2br(htmlspecialchars($stdout));
    echo "<h3>Errors</h3>";
    echo nl2br(htmlspecialchars($stderr));
} else {
    echo "<h3>Error</h3>";
    echo "Can not start Rector process<br />";
}

// exit();





// // Prepare rector params
// $_SERVER['argv'] = [];
// $_SERVER['argv'][] = $rectorFile;
// $_SERVER['argv'][] = "process";
// $_SERVER['argv'][] = "--dry-run";
// $_SERVER['argv'][] = "--config={$rectorConfigFile}";
// $_SERVER['argv'][] = "--output-format";
// $_SERVER['argv'][] = "json";

// ob_start();
// try {
//     require_once $rectorFile;
// } catch (Throwable $e) {
//     $stderr = ob_get_clean();
//     echo "Error executing Rector: " . $e->getMessage() . "<br />";
//     echo "Output:<br />" . $stderr;
//     exit(1);
// }
// $stdout = ob_get_clean();
// echo "Output:<br />" . $stdout;

// exit();





// // @ intentionally: continue anyway
// @\ini_set('memory_limit', '-1');
// // Performance boost
// \error_reporting(\E_ALL);
// \ini_set('display_errors', 'stderr');
// \gc_disable();
// \define('__RECTOR_RUNNING__', \true);


// //$_SERVER['argv']

// $rectorConfigFile = __DIR__.'/../../SticRectorConfig.php';
// try {
//     $bootstrapConfigs = new BootstrapConfigs($rectorConfigFile, []);
//     $rectorContainerFactory = new RectorContainerFactory();
//     $container = $rectorContainerFactory->createFromBootstrapConfigs($bootstrapConfigs);
// } catch (\Throwable $throwable) {
//     // for json output
//     $argvInput = new ArgvInput();
//     $outputFormat = $argvInput->getParameterOption('--' . Option::OUTPUT_FORMAT);
//     // report fatal error in json format
//     if ($outputFormat === JsonOutputFormatter::NAME) {
//         echo Json::encode(['fatal_errors' => [$throwable->getMessage()]]);
//     } else {
//         // report fatal errors in console format
//         $symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
//         $symfonyStyle = $symfonyStyleFactory->create();
//         $symfonyStyle->error($throwable->getMessage());
//     }
//     exit(Command::FAILURE);
// }
// /** @var Application $application */
// $application = $container->get(Application::class);
// exit($application->run());






// $command = escapeshellcmd("php {$rectorFile} process --dry-run --config={$rectorConfigFile} --output-format json");
// // $command = escapeshellcmd("php {$rectorFile} list-rules --output-format json");
// // $command = escapeshellcmd("php $rectorFile process --config={$configFile}");

// exec($command, $output, $returnVar);

// if ($returnVar !== 0) {
//     echo "Error running Rector: {$returnVar}<br />";
//     if (is_array($output)) {
//         echo implode("<br />", $output);
//     }
//     exit($returnVar);
// }

// echo "Rector executed withot errors.<br />";