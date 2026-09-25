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

class FormLayoutSection extends FormLayoutNode {
    public FormLayout $layout;               // The layout it belongs to

    public string $title;
    public string $subtitle;
    public string $containerType;            // 'panel', 'card', 'tabs', 'accordion'
    public bool $showTitle;
    public bool $is_custom_title = false;    // Flag to track manual title overrides (sync no longer auto-renames the section)
    public bool $isCollapsible;
    public bool $isCollapsed;
    public string $toggle_label = '';        // Label for the "include instance data" toggle switch
    public string $add_button_label = '';    // Label for the "add instance" button
    public string $remove_button_label = ''; // Label for the "remove instance" button

    /** @var FormLayoutNode[] */
    public array $elements = [];

    public static function fromJsonArray(FormLayout $layout, array $data, bool $topLevel = true): self {
        $kind = isset($data['kind']) && is_string($data['kind']) ? $data['kind'] : null;
        $rawGroupRootBlockId = $data['groupRootBlockId'] ?? '';
        $groupRootBlockId = is_scalar($rawGroupRootBlockId) ? (string)$rawGroupRootBlockId : '';
        if ($kind === null && $topLevel && is_array($data['elements'] ?? null)) {
            $groupRootBlockId = self::findLegacyGroupRootBlockId($layout, $data['elements']);
        }
        $isGroupSection = $topLevel && ($kind === 'group' || ($kind === null && $groupRootBlockId !== ''));
        $dto = $isGroupSection ? new FormLayoutGroupSection() : new self();

        $dto->layout = $layout;

        $dto->id = $data['id'] ?? uniqid('sect');
        $dto->type = 'section';
        $dto->title = $data['title'] ?? '';
        $dto->subtitle = $data['subtitle'] ?? '';
        $dto->showTitle = (bool)($data['showTitle'] ?? false);
        $dto->is_custom_title = (bool)($data['is_custom_title'] ?? false);
        $dto->isCollapsible = (bool)($data['isCollapsible'] ?? false);
        $dto->isCollapsed = (bool)($data['isCollapsed'] ?? false);
        $dto->containerType = $data['containerType'] ?? 'panel';

        if ($dto instanceof FormLayoutGroupSection) {
            $dto->groupRootBlockId = (string)$groupRootBlockId;
        }

        $dto->toggle_label = $data['toggle_label'] ?? '';
        $dto->add_button_label = $data['add_button_label'] ?? '';
        $dto->remove_button_label = $data['remove_button_label'] ?? '';
        
        if (isset($data['elements']) && is_array($data['elements'])) {
            foreach ($data['elements'] as $elData) {
                if (($elData['type'] ?? '') === 'section' || isset($elData['elements'])) {
                    $dto->elements[] = self::fromJsonArray($layout, $elData, false);
                } else {
                    $dto->elements[] = FormLayoutElement::fromJsonArray($dto, $elData);
                }
            }
        }

        return $dto;
    }

    private static function findLegacyGroupRootBlockId(FormLayout $layout, array $elements): string {
        $firstBlock = self::findFirstReferencedBlock($layout, $elements);
        if (!$firstBlock) return '';
        $rootBlock = $layout->form_config->getGroupRootBlock($firstBlock);
        if (!$rootBlock || $rootBlock->group_root !== '') return '';
        $isGroupHead = $rootBlock->isRepeatable()
            || $rootBlock->isOptional()
            || !empty($layout->form_config->getGroupChildren($rootBlock));
        return $isGroupHead ? $rootBlock->id : '';
    }

    private static function findFirstReferencedBlock(FormLayout $layout, array $elements): ?FormDataBlock {
        foreach ($elements as $element) {
            if (!is_array($element)) continue;
            if (($element['type'] ?? '') === 'section' || isset($element['elements'])) {
                $nestedElements = $element['elements'] ?? [];
                if (!is_array($nestedElements)) continue;
                $nestedBlock = self::findFirstReferencedBlock($layout, $nestedElements);
                if ($nestedBlock) return $nestedBlock;
                continue;
            }
            $elementType = $element['type'] ?? 'datablock';
            if (!in_array($elementType, ['datablock', 'field'], true) || empty($element['ref_id'])) continue;
            $block = $layout->form_config->data_blocks[$element['ref_id']] ?? null;
            if ($block) return $block;
        }
        return null;
    }
}
