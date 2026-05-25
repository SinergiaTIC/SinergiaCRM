<?php
/**
 * STIC#1021 - Repair JJWG Maps Fields
 *
 * This script adds the jjwg_maps custom fields (lat, lng, address, geocode_status)
 * to the 8 modules that support maps functionality in instances that were created
 * before the GoogleMaps suite_install was implemented.
 *
 * Modules: Accounts, Cases, Contacts, Leads, Meetings, Opportunities, Project, Prospects
 */

global $current_user, $db;

// Load admin user
$current_user = new User();
$current_user->getSystemUser();

// Include the GoogleMaps installer
require_once 'install/suite_install/GoogleMaps.php';

// Execute the installation function
install_gmaps();

echo "JJWG Maps fields repair completed.\n";