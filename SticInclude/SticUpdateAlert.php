<?php
/**
 * Manages data for visibility of update alerts
 */

if (file_exists('SticVersion.php')) {
    // Include version file in order to get $sticVersion and $showUpdateAlert vars.
    include_once 'SticVersion.php';

    $sticVersionCookie = $_COOKIE['SticVersion'];

    if (isset($sticVersion) && !empty($sticVersionCookie) && $sticVersion > $sticVersionCookie) {
        $this->_tpl_vars['lastSticVersion'] = $sticVersion;
        $this->_tpl_vars['lastSticVersionDateTime'] = date("d/m/Y H:i", filemtime('SticVersion.php'));
        $this->_tpl_vars['showUpdateAlert'] = $showUpdateAlert;
    }
}
