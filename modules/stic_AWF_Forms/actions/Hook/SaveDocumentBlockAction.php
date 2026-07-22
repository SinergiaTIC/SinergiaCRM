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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";

/**
 * SaveDocumentBlockAction
 *
 * Action that processes a Document Data Block: creates a Document record,
 * updates the auto-generated DocumentRevision with actual file data,
 * moves the uploaded file to upload://{revisionId}, and binds the
 * Document to the Response via the stic_awf_responses_documents relationship.
 */
class SaveDocumentBlockAction extends HookDataBlockActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = false;
        $this->isAutomatic = true;
        $this->category = 'data';
        $this->baseLabel = 'LBL_SAVE_DOCUMENT_BLOCK_ACTION';
    }

    public function executeWithBlock(ExecutionContext $context, FormAction $actionConfig, DataBlockResolved $block): ActionResult
    {
        if (!$block->dataBlock->is_document_block) {
            return new ActionResult(ResultStatus::SKIPPED, $actionConfig, "Not a document block.");
        }

        $fileField = $block->dataBlock->fields['file'] ?? null;
        if (!$fileField) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Document block has no file field.");
        }

        $phpKey = $fileField->getPhpKey();
        $fileInfo = $context->uploadedFiles[$phpKey] ?? null;

        if (!$fileInfo || $fileInfo['error'] !== UPLOAD_ERR_OK) {
            if ($fileField->required_in_form) {
                return new ActionResult(ResultStatus::ERROR, $actionConfig, "Required file not uploaded.");
            }
            return new ActionResult(ResultStatus::SKIPPED, $actionConfig, "No file uploaded.");
        }

        $document = BeanFactory::newBean('Documents');
        $document->active_date = TimeDate::getInstance()->nowDbDate();

        $modifications = stic_AWF_FormsUtils::populateBeanFromBlock($document, $block);
        
        // Guarantee mandatory fallbacks if not mapped in the form
        if (empty($document->document_name)) {
            $document->document_name = 'AWF_Upload_' . time();
            $modifications['document_name'] = new FieldModification('document_name', FieldModificationStatus::APPLIED, $document->document_name);
        }
        if (empty($document->status_id)) {
            $document->status_id = 'Active';
            $modifications['status_id'] = new FieldModification('status_id', FieldModificationStatus::APPLIED, $document->status_id);
        }
        // Assign user if a default one is set
        if (!empty($context->defaultAssignedUserId)) {
            $document->assigned_user_id = $context->defaultAssignedUserId;
        }
        // Save without logic hooks execution to prevent duplicate triggers
        $document->save(false);

        // Create a DocumentRevision manually (the Documents module auto-creates
        // one only when $_FILES is populated, which isn't the case here)
        $revision = BeanFactory::newBean('DocumentRevisions');
        $revision->id = create_guid();
        $revision->new_with_id = true;
        $revision->document_id = $document->id;
        $revision->filename = $fileInfo['name'];
        $revision->file_mime_type = mime_content_type($fileInfo['tmp_name']);
        $revision->file_ext = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        $revision->revision = 1;
        $revision->save(false);

        // Link the revision back to the document
        $document->document_revision_id = $revision->id;
        $document->save(false);

        // Move the temporary binary payload to SuiteCRM permanent storage
        if (!UploadStream::move_uploaded_file($fileInfo['tmp_name'], "upload://{$revision->id}")) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Failed moving binary payload to target path.");
        }

        // Build relationship links with the AWF Response log
        if ($context->responseBean && $context->responseBean->load_relationship('stic_awf_responses_documents')) {
            $context->responseBean->stic_awf_responses_documents->add($document->id);
        }

        // Register modifications and revision metadata in ActionResult
        $result = new ActionResult(ResultStatus::OK, $actionConfig, "Document '{$document->document_name}' created.");
        $result->registerBeanModificationFromBlock($document, $block, BeanModificationType::CREATED, $modifications);
        $result->registerActionMetadata($document, [
            ['key' => 'revision_filename', 'label' => $this->translate('FILENAME_TEXT'), 'value' => $revision->filename],
        ]);
        return $result;
    }
}
