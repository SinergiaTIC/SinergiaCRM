<?php
// Scheduled task that rebuilds the views and tables used as data sources in SinergiaDA, if  $sugar_config['stic_sinergiada']['enabled'] is true

$job_strings[] = 'rebuildSDASources';

/**
 * Rebuilds SinergiaDA data sources if $sugar_config['stic_sinergiada']['enabled'] is 1.
 *
 * This function is part of a scheduled task in SuiteCRM.
 *
 * @return bool Returns true if the task is successful, false otherwise.
 */
function rebuildSDASources()
{
    global $sugar_config;

    $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Running the task rebuildSDASources');

    // Get the value of $sugar_config['stic_sinergiada']['enabled'].
    $sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;

    if ($sdaEnabled) {
        // If SinergiaDA is enabled, rebuild SinergiaDA data sources.
        require_once 'SticInclude/SinergiaDARebuild.php';
        $res = SinergiaDARebuild::rebuild(true, 'all');
        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Rebuilding SinergiaDA return [{$res}]");

        // Return true if the rebuild was successful, false otherwise.
        return $res == 'ok' ? true : false;
    } else {
        // If SinergiaDA is disabled, skip the task.
        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ':  The rebuildSDASources task has been skipped because  SinergiaDA is disabled.');
        return true;
    }
}
