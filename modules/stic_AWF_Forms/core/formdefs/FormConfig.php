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

/**
 * Class representing the configuration of a form, including its data blocks, action flows, and layout.
 */
class FormConfig {
    /** @var FormDataBlock[] */
    public array $data_blocks;         // The form's data blocks
    /** @var FormFlow[] */
    public array $flows;               // The form's action flows
    public ?FormLayout $layout = null; // The form layout

    /** 
     * Creates a FormConfig instance from a JSON array. 
     * @param array $data The data in array format 
     * @return FormConfig The created instance 
     */
    public static function fromJsonArray(array $data): self {
        $dto = new self();

        $dto->data_blocks = [];
        if (isset($data['data_blocks'])) {
            foreach ($data['data_blocks'] as $dataBlockData) {
                $formDataBlock = FormDataBlock::fromJsonArray($dto, $dataBlockData);
                $dto->data_blocks[$formDataBlock->id] = $formDataBlock;
            }
        }

        $dto->flows = [];
        if (isset($data['flows'])) {
            foreach ($data['flows'] as $flowData) {
                $formFlow = FormFlow::fromJsonArray($dto, $flowData);
                $dto->flows[$formFlow->id] = $formFlow;
            }
        }

        if (isset($data['layout'])) {
            $dto->layout = FormLayout::fromJsonArray($dto, $data['layout']);
        } else {
            $dto->layout = new FormLayout();     // Default layout
            $dto->layout->theme = new FormTheme();
        }
        return $dto;
    }



    /**
     * Returns the direct children of the block passed as a parameter.
     * @param FormDataBlock $block The block whose children are to be retrieved
     * @return FormDataBlock[] An array of FormDataBlock instances that are direct children of the given block
     */
    public function getGroupChildren(FormDataBlock $block): array {
        return array_values(array_filter($this->data_blocks, fn($b) => $b->group_root === $block->id));
    }


    /**
     * Returns all descendants of the block passed as a parameter, including children, grandchildren, etc.
     * @param FormDataBlock $rootBlock The root block whose descendants are to be retrieved
     * @return FormDataBlock[] An array of FormDataBlock instances that are descendants of the given root block
     */
    public function getGroupDescendants(FormDataBlock $rootBlock): array {
        $descendants = [];
        $queue = [$rootBlock->id];
        $visited = [$rootBlock->id => true];

        while (!empty($queue)) {
            $parentId = array_shift($queue);
            foreach ($this->data_blocks as $candidate) {
                if ($candidate->group_root === $parentId && !isset($visited[$candidate->id])) {
                    $visited[$candidate->id] = true;
                    $descendants[] = $candidate;
                    $queue[] = $candidate->id;
                }
            }
        }

        return $descendants;
    }


    /**
     * Gets the root block of a group for a given block. If the block is not part of a group, it returns the block itself.
     * @param FormDataBlock $block The block whose group root is to be retrieved
     * @return FormDataBlock The root block of the group or the block itself if it is not part of a group
     */
    public function getGroupRootBlock(FormDataBlock $block): ?FormDataBlock {
        $current = $block;
        $visited = [$current->id => true];

        while (!empty($current->group_root)) {
            $parent = $this->data_blocks[$current->group_root] ?? null;
            if ($parent === null || isset($visited[$parent->id])) break; // Missing parent or cycle guard
            $visited[$parent->id] = true;
            $current = $parent;
        }

        return $current;
    }
}