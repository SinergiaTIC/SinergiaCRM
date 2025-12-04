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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class FormTheme {
    public FormLayout $layout;           // El Layout al que pertenece

    public string $primary_color = '#0d6efd';
    public string $page_bg_color = '#f8f9fa';
    public string $form_bg_color = '#ffffff';
    public string $text_color = '#212529';
    public string $border_color = '#dee2e6';
    public int $border_width = 1;
    public int $border_radius_container = 10;
    public int $border_radius_controls = 4;
    public bool $floating_labels = true;
    public string $font_family = 'system-ui';
    public int $font_size = 16;
    public string $form_width = '800px';
    public string $shadow_intensity = 'normal';
    public int $sections_per_row = 1;
    public int $fields_per_row = 2;

    public static function fromJsonArray(FormLayout $layout, array $data): self {
        $dto = new self();
        $dto->layout = $layout;
    
        foreach ($data as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }
        return $dto;
    }
}