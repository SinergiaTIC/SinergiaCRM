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
$mod_strings = array(
    'LBL_ASSIGNED_TO_ID' => 'Assigned to (ID)',
    'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
    'LBL_ASSIGNED_TO' => 'Assigned to',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigned to',
    'LBL_LIST_ASSIGNED_USER' => 'Assigned to',
    'LBL_CREATED' => 'Created By',
    'LBL_CREATED_USER' => 'Created By',
    'LBL_CREATED_ID' => 'Created By (ID)',
    'LBL_MODIFIED' => 'Modified By',
    'LBL_MODIFIED_NAME' => 'Modified By',
    'LBL_MODIFIED_USER' => 'Modified By',
    'LBL_MODIFIED_ID' => 'Modified By (ID)',
    'LBL_SECURITYGROUPS' => 'Security Groups',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Security Groups',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Date Created',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_DELETED' => 'Deleted',
    'LBL_NAME' => 'Name',
    'LBL_LIST_NAME' => 'Name',
    'LBL_EDIT_BUTTON' => 'Edit',
    'LBL_QUICKEDIT_BUTTON' => '↙ Edit',
    'LBL_REMOVE' => 'Remove',
    'LBL_ASCENDING' => 'Ascending',
    'LBL_DESCENDING' => 'Descending',
    'LBL_DEFAULT_PANEL' => 'Overview',
    'LBL_PANEL_RECORD_DETAILS' => 'Record details',
    'LBL_LIST_FORM_TITLE' => 'Signers List',
    'LBL_MODULE_NAME' => 'Signers',
    'LBL_MODULE_TITLE' => 'Signers',
    'LBL_HOMEPAGE_TITLE' => 'My Signers',
    'LNK_NEW_RECORD' => 'Create a Signer',
    'LNK_LIST' => 'View Signers',
    'LNK_IMPORT_STIC_SIGNERS' => 'Import Signers',
    'LBL_SEARCH_FORM_TITLE' => 'Search Signers',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'View History',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
    'LBL_STIC_SIGNERS_SUBPANEL_TITLE' => 'Signers',
    'LBL_NEW_FORM_TITLE' => 'New Signer',
    'LBL_STIC_SIGNATURES_STIC_SIGNERS_FROM_STIC_SIGNATURES_TITLE' => 'Signature',
    'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURE_LOG_TITLE' => 'Signer log',
    'LBL_SIGNATURE_ID' => 'Signature',
    'LBL_FLEX_RELATE' => 'Signer',
    'LBL_RECORD_ID' => 'Related record ID',
    'LBL_RECORD_TYPE' => 'Related record type',
    'LBL_RECORD_NAME' => 'Related record name',
    'LBL_EMAIL_ADDRESS' => 'Email address',
    'LBL_PHONE' => 'Phone',
    'LBL_STATUS' => 'Status',
    'LBL_PDF_DOCUMENT' => 'PDF document',
    'LBL_SIGNATURE_DATE' => 'Signature date',
    'LBL_VERIFICATION_CODE' => 'Verification code',
    'LBL_OTP' => 'One time password',
    'LBL_OTP_EXPIRATION' => 'One time password expiration',
    'LBL_IP_ADDRESS' => 'IP address',
    'LBL_SIGNATURE_IMAGE' => 'Signature image',
    'LBL_REJECTION_REASON' => 'Rejection reason',
    'LBL_ON_BEHALF_OF_ID' => 'Represented person',
    'LBL_ON_BEHALF_OF_ID_CONTACT_ID' => 'Authorized to sign on behalf of',
    'LBL_STIC_SIGNATURES_CONTACTS_FROM_STIC_SIGNERS_TITLE' => 'Signatures',
    'LBL_STIC_SIGNATURES_USERS_FROM_STIC_SIGNERS_TITLE' => 'Signatures',

    'LBL_SIGNER_SEND_TO_SIGN_BY_EMAIL' => 'Request signature by email',
    'LBL_SIGNER_SEND_TO_SIGN_MASSIVE_LIMIT_ALERT' => 'Please select a maximum of 20 signers.',
    'LBL_SIGNER_REDIRECT_TO_PORTAL' => 'Go to signing portal',
    'LBL_SIGNER_COPY_PORTAL_URL' => 'Copy portal URL',
    'LBL_SIGNER_PORTAL_URL_COPIED' => 'Portal URL copied to clipboard.',
    'LBL_SIGNER_EMAIL_SUCCESS' => 'Email successfully sent to the signer.',
    'LBL_DOWNLOAD_PDF_SIGNATURE' => 'Download signed document',
    'LBL_NO_PDF_SIGNATURE' => 'Document not available',

    'LBL_EMAIL_ADDRESS_HELP' => "Signer's email address to which the corresponding messages will be sent during the signing process.",
    'LBL_PHONE_HELP' => "Signer's phone number to which the corresponding messages will be sent during the signing process.",
    'LBL_STATUS_HELP' => 'Signing process status:<ul><li><strong>Pending:</strong> The signer has not yet signed the document.</li> <li><strong>Signed:</strong> The signer has signed the document.</li> <li><strong>Expired:</strong> The process has ended without the signer having signed.</li><li><strong>Unnecessary:</strong> The signer does not need to sign because another authorized person has already done so.</li></ul>',
    'LBL_PDF_DOCUMENT_HELP' => 'Allows you to download the document once signed.',
    'LBL_SIGNATURE_DATE_HELP' => 'Date on which the document was signed.',
    'LBL_VERIFICATION_CODE_HELP' => 'Verification code generated after the document was signed.',
    'LBL_RECORD_NAME_HELP' => 'Name of the record related to the signature, from which the signer record was generated.',
);
