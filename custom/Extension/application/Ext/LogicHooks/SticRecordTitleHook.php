<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$hook_array['after_ui_frame'][] = array(
    1,
    'Render configured header title in DetailView',
    'SticInclude/SticRecordTitleRenderer.php',
    'SticRecordTitleRenderer',
    'renderHeaderTitleJs',
);
