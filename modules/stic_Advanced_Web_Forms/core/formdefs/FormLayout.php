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

class FormLayout {
    public FormConfig $form_config;    // La configuración del formulario al que pertenece

    public FormTheme $theme;
    public string $header_html = '';
    public string $footer_html = '';

    public string $submit_button_text = 'Send';

    public string $custom_css = '';
    public string $custom_js = '';

    /** @var FormLayoutSection[] */
    public array $structure = [];      // Las secciones

    public static function fromJsonArray(FormConfig $form, array $data): self {
        $dto = new self();
        $dto->form_config = $form;

        $dto->theme = FormTheme::fromJsonArray($dto, $data['theme'] ?? []);

        $dto->header_html = $data['header_html'] ?? '';
        $dto->footer_html = $data['footer_html'] ?? '';
        $dto->custom_css = $data['custom_css'] ?? '';
        $dto->custom_js = $data['custom_js'] ?? '';

        if (isset($data['structure']) && is_array($data['structure'])) {
            foreach ($data['structure'] as $secData) {
                $dto->structure[] = FormLayoutSection::fromJsonArray($dto, $secData);
            }
        }

        return $dto;
    }
}