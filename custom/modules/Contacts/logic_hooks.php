<?php

// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
$hook_version = 1;
$hook_array = array();

// position, file, function
$hook_array['before_save'] = array();
$hook_array['before_save'][] = array(1, 'Contacts push feed', 'modules/Contacts/SugarFeeds/ContactFeed.php', 'ContactFeed', 'pushFeed');
$hook_array['before_save'][] = array(77, 'updateGeocodeInfo', 'modules/Contacts/ContactsJjwg_MapsLogicHook.php', 'ContactsJjwg_MapsLogicHook', 'updateGeocodeInfo');

$hook_array['after_save'] = array();
$hook_array['after_save'][] = array(1, 'Update Portal', 'modules/Contacts/updatePortal.php', 'updatePortal', 'updateUser');
$hook_array['after_save'][] = array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Contacts/ContactsJjwg_MapsLogicHook.php', 'ContactsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo');

$hook_array['after_ui_frame'] = array();
$hook_array['after_ui_frame'][] = array(1002, 'Document Templates after_ui_frame Hook', 'custom/modules/Contacts/DHA_DocumentTemplatesHooks.php', 'DHA_DocumentTemplatesContactsHook_class', 'after_ui_frame_method');
