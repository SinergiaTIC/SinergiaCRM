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

    // STIC-Custom OC - 20250803 - Returns the child blocks that belong to a repeatable root
    public function getGroupChildren(FormDataBlock $rootBlock): array {
        return array_filter($this->data_blocks, function (FormDataBlock $b) use ($rootBlock) {
            return $b->group_root === $rootBlock->id;
        });
    }
    // END STIC-Custom OC

    // STIC-Custom OC - 20260807 - Returns ALL descendant blocks of a group root (BFS, transitive).
    // Needed because adoptRelatedOrphans (JS) adopts descendants transitively; rendering/execution
    // must include the whole branch (e.g. Adult -> Entorn Familiar -> Menor -> Inscripcio).
    public function getGroupDescendants(FormDataBlock $rootBlock): array {
        $descendants = [];
        $queue = [$rootBlock->id];
        $visited = [$rootBlock->id => true];

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            foreach ($this->data_blocks as $b) {
                if ($b->group_root !== $currentId) continue;
                if (isset($visited[$b->id])) continue; // Cycle guard for malformed data
                $visited[$b->id] = true;
                $descendants[] = $b;
                $queue[] = $b->id;
            }
        }

        return $descendants;
    }

    // STIC-Custom OC - 20260807 - Walks up the group_root chain to the top-level root of the branch
    // (the block whose own group_root is empty). Needed for multi-level hierarchies where a
    // level-2+ child's group_root points to its immediate (non-root) parent.
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
    // END STIC-Custom OC
}