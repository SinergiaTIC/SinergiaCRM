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
 * This script executes Rector during the update of SinergiaCRM instances,
 * using the Rector configuration defined in SticRectorConfig.php on the root folder
 * 
 * Without arguments, saves suggested changes to file
 * With argument "adapt-code", modifies the code 
 * 
 */

$output = new RectorCheckResultHelper(__DIR__."/../../rectorOutput.html");

$rectorFile = __DIR__."/../../SticInclude/vendor/rector-standalone/vendor/rector/rector/bin/rector.php";
$rectorConfigFile = __DIR__."/../../SticRectorConfig.php";

$output->addInfo($rectorFile, "Rector");
$output->addInfo($rectorConfigFile, "Rector config");

if (!file_exists($rectorFile)) {
    $output->addFatalError("Rector is not installed. Can not run script");
    $output->closeAndExit(1);
}

// Get command arguments
$arguments = $_SERVER['argv'];
$scriptName = array_shift($arguments);
$adaptCode = count($arguments) > 0 && $arguments[0] == "adapt-code";

$rectorCommand = "php {$rectorFile} process --no-progress-bar --config={$rectorConfigFile}";// --output-format json";
if (!$adaptCode) {
    $rectorCommand .= " --dry-run";
}
$output->addInfo($rectorCommand, "Command");


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
    $output->addResults(htmlspecialchars($stdout));
    fclose($pipes[1]);

    // Read error output
    $stderr = stream_get_contents($pipes[2]);
    $stderr = str_replace("\\n", "\n", $stderr);
    $output->addErrorResults(htmlspecialchars($stderr));
    fclose($pipes[2]);

    // Close process
    $returnCode = proc_close($process);
    $output->closeAndExit($returnCode);
} else {
    $output->addFatalError("Can not start Rector process");
    $output->closeAndExit(1);
}

class RectorCheckResultHelper {

    private $startDate;
    private $fileName;
    private $fatalErrors = [];
    private $infos = [];
    private $results = [];
    private $errorResults = [];

    public function __construct($fileName)
    {
        $this->startDate = date('Y-m-d H:i:s');
        $this->fileName = $fileName;
    }

    private function getLabelText($text, $label = "", $newLine = false)
    {
        if(!empty($label)) {
            $text = "<strong>{$label}</strong>: {$text}";
        }
        return $text. ($newLine ? "\n" : "");
    }
    private function getTitle($text, $newLine = false) {
        return "<h3>{$text}</h3>" . ($newLine ? "\n" : "");
    }

    public function addInfo($text, $label = "")
    {
        $this->infos[] = $this->getLabelText($text, $label);
    }

    public function addFatalError($text)
    {
        $this->fatalErrors[] = $text;
    }

    public function addResults($text)
    {
        $this->results = [...$this->results, ...explode("\n", $text)];
    }

    public function addErrorResults($text)
    {
        $this->errorResults = [...$this->errorResults, ...explode("\n", $text)];
    }

    public function closeAndExit($resultCode)
    {
        $content = "Result: {$resultCode} <br />\n";

        $this->addInfo($resultCode, "Result");

        // Last Execution
        $content .= $this->getTitle("Execution", true);
        $content .= $this->getLabelText($this->startDate . "<br />", "Start", true);
        $content .= $this->getLabelText(date('Y-m-d H:i:s') . "<br />", "End", true);

        // Fatal Errors
        if (count($this->fatalErrors) > 0) {
            $content .= $this->getTitle("Error", true);
            $content .= implode("<br />\n", $this->fatalErrors);
            $content .= "<br />\n";
        }
        
        // Informations
        if (count($this->infos) > 0) {
            $content .= $this->getTitle("Information", true);
            $content .= implode("<br />\n", $this->infos);
            $content .= "<br />\n";
        }

        // Results
        if (count($this->results) > 0) {
            $content .= $this->getTitle("Result", true);
            $content .= implode("<br />\n", $this->results);
            $content .= "<br />\n";
        }

        // Error Results
        if (count($this->results) > 0) {
            $content .= $this->getTitle("Rector Errors", true);
            $content .= implode("<br />\n", $this->errorResults);
            $content .= "<br />\n";
        }

        file_put_contents($this->fileName, $content);
        exit($resultCode);
    }
}