<?php

// STIC#486

// Remove SuiteCRM Forums link
unset($global_control_links['training']);

// Keep a copy of Admin element to put it in the desired place inside the whole array
$adminElement = array();
if (isset($global_control_links['admin'])) {
    $adminElement['admin'] = $global_control_links['admin'];
    unset($global_control_links['admin']);
}

// Prepare common Stic links (wiki, videos, forums)
$sticElements = array(
    'stic_wiki' => array(
        'linkinfo' => array($app_strings['LBL_STIC_WIKI_LINK'] => "javascript:void(window.open('https://wikisuite.sinergiacrm.org/'))"),
        'submenu' => '',
    ),
    'stic_videos' => array(
        'linkinfo' => array($app_strings['LBL_STIC_VIDEOS_LINK'] => "javascript:void(window.open('https://www.youtube.com/c/SinergiaCRM'))"),
        'submenu' => '',
    ),
    'stic_forums' => array(
        'linkinfo' => array($app_strings['LBL_STIC_FORUMS_LINK'] => "javascript:void(window.open('https://forums.sinergiacrm.org/'))"),
        'submenu' => '',
    ),
);

global $current_user;

// Prepare custom links if defined in stic_Settings
include_once 'modules/stic_Settings/Utils.php';
$settingLinks = stic_SettingsUtils::getSettingsByType('LINK');
ksort($settingLinks);
$customLinks = array();
foreach ($settingLinks as $key => $value) {
    $link = explode('|', $value);
    $linkText = trim($link[0]);
    $linkUrl = trim($link[1]);
    $customLinks['custom-' . strtolower(preg_replace('/([^a-zA-Z0-9])/m', '', $linkText))] = array(
        'linkinfo' => array($linkText => "javascript:void(window.open('{$linkUrl}'))", 'class' => 'custom_link'),
    );
}

// Set the final array: STIC elements + Custom links + Admin + Other core links. The Profile element is always added later at the top place.
$global_control_links = array_merge($sticElements, $customLinks, $adminElement, $global_control_links);
$sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;
if ($sdaEnabled && is_admin($current_user)) {

    // Generate sdaUrl
    $currentDomain = $_SERVER['HTTP_HOST'];
    $lang = explode('_', $sugar_config['default_language'])[0];
    $sdaUrl = $sugar_config['stic_sinergiada_public']['url'] ?? "https://" . str_replace("sinergiacrm", "sinergiada", $currentDomain);
    $sdaUrl .= "/{$lang}/#";

    $sinergiaDA = array(
        'sda_link' => array(
            'linkinfo' => array($app_strings['LBL_STIC_SINERGIADA'] => "javascript:void(window.open('{$sdaUrl}'))"),
            'submenu' => '',
        ),
    );
    $global_control_links = array_merge($global_control_links, $sinergiaDA);
}
