<?php
/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

use SuiteCRM\PDF\Exceptions\PDFException;
use SuiteCRM\PDF\PDFWrapper;

/**
 * Handles the generation and finalization of a signed PDF document
 * by retrieving template data, inserting signature/acceptance images,
 * adding an audit page (if configured), and saving the resulting PDF file.
 */
class sticGenerateSignedPdf
{
    /**
     * Generates a signed PDF for a given signer ID and saves it to the file system.
     * The generated PDF file name is stored in the 'pdf_document' field of the signer record.
     *
     * @param string $signedMode The mode of signing, either 'handwritten' (for drawn signature) or 'button' (for acceptance).
     * Defaults to 'handwritten'.
     * @return string The file path of the generated PDF document.
     */
    public static function generateSignedPdf($signedMode = 'handwritten')
    {
        global $sugar_config, $app_list_strings, $app_strings;

        // Required utility and function files
        require_once 'custom/modules/AOS_PDF_Templates/SticGeneratePdfFunctions.php';
        require_once 'modules/stic_Signatures/Utils.php';
        require_once 'modules/stic_Signers/Utils.php';

        // Check required parameters
        if (!isset($_REQUEST['signerId']) || empty($_REQUEST['signerId'])) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " No signerId received");
            sugar_die('No signerId received');
        }

        // Retrieve the signer bean
        $signerBean = BeanFactory::getBean('stic_Signers', $_REQUEST['signerId']);
        if (!$signerBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Signer ID: {$_REQUEST['signerId']}");
            sugar_die("Invalid Signer");
        }

        // Retrieve the signature bean
        $signatureBean = BeanFactory::getBean('stic_Signatures', $signerBean->stic_signatures_stic_signersstic_signatures_ida);
        if (!$signatureBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Signature ID: {$signerBean->signature_id} for Signer ID: {$signerBean->id}");
            sugar_die("Invalid Signature");
        }

        $sourceModule = $signatureBean->main_module;
        $sourceId = $signerBean->record_id;
        // Check that the signature has an associated record
        if (empty($sourceModule) || empty($sourceId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signature ID: {$signatureBean->id} has no associated record");
            sugar_die("Signature has no associated record");
        }

        // Retrieve the PDF template bean
        $templateBean = BeanFactory::getBean('AOS_PDF_Templates', $signatureBean->pdftemplate_id_c);

        if (!$templateBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Template ID: {$signatureBean->pdftemplate_id_c} for Signature ID: {$signatureBean->id}");
            sugar_die("Invalid Template");
        }

        $file_name = str_replace(" ", "_", (string) $templateBean->name) . ".pdf";

        // PDF Configuration based on the template bean
        $pdfConfig = [
            'mode' => 'en',
            'page_size' => $templateBean->page_size,
            'font' => 'DejaVuSansCondensed',
            'margin_left' => $templateBean->margin_left,
            'margin_right' => $templateBean->margin_right,
            'margin_top' => $templateBean->margin_top,
            'margin_bottom' => $templateBean->margin_bottom,
            'margin_header' => $templateBean->margin_header,
            'margin_footer' => $templateBean->margin_footer,
            'orientation' => $templateBean->orientation,
        ];

        try {
            $pdf = PDFWrapper::getPDFEngine();
            $pdf->configurePDF($pdfConfig);
        } catch (PDFException $e) {
            LoggerManager::getLogger()->warn('PDFException: ' . $e->getMessage());
        }

        // Retrieve the record bean (source of data for the PDF)
        $sourceBean = BeanFactory::getBean($sourceModule, $sourceId);
        if (!$sourceBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Record ID: {$sourceId} for Module: {$sourceModule}");
            sugar_die("Invalid Record");
        }

        // PDF History (unused here, but configured)
        try {
            $pdfHistory = PDFWrapper::getPDFEngine();
            $pdfHistory->configurePDF($pdfConfig);
        } catch (PDFException $e) {
            LoggerManager::getLogger()->warn('PDFException: ' . $e->getMessage());
        }

        // Array for template parsing
        $object_arr = [$sourceBean->module_dir => $sourceBean->id];

        // Add related Accounts ID if the source is Contacts for backward compatibility
        if ($sourceBean->module_dir === 'Contacts' && isset($sourceBean->account_id)) {
            $object_arr['Accounts'] = $sourceBean->account_id;
        }

        // Replace the signature placeholder with the actual signature image/acceptance image.
        // The placeholder string is HTML-encoded.
        $stringToreplace = '&lt;img class=&quot;signature&quot; src=&quot;themes/SuiteP/images/SignaturePlaceholder.png&quot; alt=&quot;&quot; width=&quot;200&quot; /&gt;';

        // Set time in user format and UTC for use later in audit/acceptance
        $userTime = (new DateTime())->format('Y-m-d H:i:s (\U\T\C P)');
        $utcTime = (new DateTime('UTC'))->format('Y-m-d H:i:s P');

        $replaceWith = '';
        // Prepare the replacement HTML based on the signed mode
        switch ($signedMode) {
            case 'handwritten':
                // Use the drawn signature image URL from the signer bean
                $replaceWith = htmlspecialchars('<img class="signature" src="' . $signerBean->signature_image . '" width="200"></div>');
                break;
            case 'button':
                // Generate an acceptance image with signer details and timestamp
                $textArray = [
                    'Document accepted by:',
                    $signerBean->parent_name,
                    $signerBean->email_address,
                    $userTime,
                    // $utcTime // UTC time is commented out but available
                ];

                $acceptImage = stic_SignaturesUtils::generateAcceptImage($textArray);
                $replaceWith = htmlspecialchars('<img class="signature" src="' . $acceptImage . '" width="200"></div>');
                break;
            default:
                // Default case, no replacement
                break;
        }

        // Perform the replacement in the template description
        $templateBean->description = str_replace($stringToreplace, $replaceWith, (string) $templateBean->description);

        // If 'pdf_audit_page' is enabled, append an audit page to the PDF content
        if (!empty($signatureBean->pdf_audit_page) && $signatureBean->pdf_audit_page) {

            // Get logs related to the signer
            require_once 'modules/stic_Signature_Log/Utils.php';
            $signerLog = stic_SignatureLogUtils::getSignatureLogActions($signerBean->id, 'SIGNER', ['OPEN_PORTAL_BEFORE_SIGN']);

            $sugar_smarty = new Sugar_Smarty();

            // Assign variables for the audit page template
            $sugar_smarty->assign('DOCUMENT_NAME', $templateBean->name);
            $sugar_smarty->assign('SIGNER_NAME', $signerBean->parent_name);
            $sugar_smarty->assign('SIGNER_EMAIL', $signerBean->email_address);
            $sugar_smarty->assign('SIGNER_PHONE', $signerBean->phone);
            $sugar_smarty->assign('SIGNER_USER_TIME', $userTime);
            $sugar_smarty->assign('SIGNER_MODE', $app_list_strings['stic_signatures_modes_list'][$signedMode]);
            $sugar_smarty->assign('SIGNER_STATUS', $app_list_strings['stic_signers_status_list'][$signerBean->status]);
            
            // If signing on behalf of someone else, include that information
            if($signerBean->parent_id != $signerBean->contact_id_c){
                $behalfName = BeanFactory::getBean('Contacts', $signerBean->contact_id_c)->full_name;
                $sugar_smarty->assign('SIGNER_ON_BEHALF_OF', $behalfName);
            } 

            $sugar_smarty->assign('MOD_STRINGS', return_module_language($GLOBALS['current_language'], 'stic_Signatures'));
            $sugar_smarty->assign('APP_STRINGS', $app_strings);

            $sugar_smarty->assign('SIGNER_LOG', $signerLog);

            // Construct the audit HTML content
            $auditHtml = '<p style="page-break-before: always;">&nbsp;</p>';
            $auditHtml .= $sugar_smarty->fetch('modules/stic_Signatures/AuditPageTemplate.tpl');

            // Append the audit page HTML (encoded) to the template description
            $templateBean->description .= htmlspecialchars($auditHtml);
        }

        // HTML Cleaning and Replacement preparation
        $search = [
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@([\r\n])[\s]+@', // Strip out white space
            '@&(quot|#34);@i', // Replace HTML entities
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@<address[^>]*?>@si',
        ];

        $replace = [
            '',
            '',
            '\1',
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            '<br>',
        ];

        // Apply initial cleaning to the description
        $text = preg_replace($search, $replace, (string) $templateBean->description);

        // Replace {DATE <format>} placeholders with the current date
        $text = preg_replace_callback(
            '/{DATE\s+(.*?)}/',
            function ($matches) {
                return date($matches[1]);
            },
            $text
        );

        // STIC-Custom 20240125 JBL - Product line items in pdf
        // Handle AOS (Advanced OpenSales) specific modules for line items
        if (str_starts_with($sourceModule, "AOS_")) {
            $variableName = strtolower($sourceBean->module_dir);
            $lineItemsGroups = [];
            $lineItems = [];

            // Query to fetch line items and groups
            $sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $sourceBean->object_name . "' AND pg->parent_id = '" . $sourceBean->id . "' AND pg->deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
            $res = $sourceBean->db->query($sql);
            while ($row = $sourceBean->db->fetchByAssoc($res)) {
                $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
                $lineItems[$row['id']] = $row['product_id'];
            }

            // Backward compatibility for related beans in AOS modules
            if (isset($sourceBean->billing_account_id)) {
                $object_arr['Accounts'] = $sourceBean->billing_account_id;
            }
            if (isset($sourceBean->billing_contact_id)) {
                $object_arr['Contacts'] = $sourceBean->billing_contact_id;
            }
            if (isset($sourceBean->assigned_user_id)) {
                $object_arr['Users'] = $sourceBean->assigned_user_id;
            }
            if (isset($sourceBean->currency_id)) {
                $object_arr['Currencies'] = $sourceBean->currency_id;
            }

            // Replace specific AOS variables with dynamic ones
            $text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
            $text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
            $text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
            $text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
            $text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
            $text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
            $text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
            $text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

            // Populate group lines (products/services) in the template
            $text = populate_group_lines($text, $lineItemsGroups, $lineItems);
        }
        // END STIC-Custom 20240125

        // Apply cleaning to header and footer
        $header = preg_replace($search, $replace, (string) $templateBean->pdfheader);
        $footer = preg_replace($search, $replace, (string) $templateBean->pdffooter);

        // Final template parsing using the utility function, which handles advanced placeholders
        $parsedText = stic_SignaturesUtils::getParsedTemplate($signerBean->id);
        $converted = $parsedText['converted'];
        $header = $parsedText['header'];
        $footer = $parsedText['footer'];

        // Replace the signature src with the actual signature image/acceptance image.
        $stringToreplace = 'src="themes/SuiteP/images/SignaturePlaceholder.png"';

        // Set time in user format and UTC for use later in audit/acceptance
        $userTime = (new DateTime())->format('Y-m-d H:i:s (\U\T\C P)');

        $replaceWith = '';
        // Prepare the replacement HTML based on the signed mode
        switch ($signedMode) {
            case 'handwritten':
                // Use the drawn signature image URL from the signer bean
                $replaceWith = 'src="' . $signerBean->signature_image . '";';
                break;
            case 'button':
                // Generate an acceptance image with signer details and timestamp
                $textArray = [
                    'Document accepted by:',
                    $signerBean->parent_name,
                    $signerBean->email_address,
                    $userTime,
                ];

                $acceptImage = stic_SignaturesUtils::generateAcceptImage($textArray);
                $replaceWith = 'src="' . $acceptImage . '";';
                break;
            default:
                // Default case, no replacement
                break;
        }

        // Perform the replacement in the $text
        $converted = str_replace($stringToreplace, $replaceWith, (string) $converted);

        // Replace newlines with HTML line breaks for PDF generation
        $printable = str_replace("\n", "<br />", (string) $converted);

        try {
            // Generate a unique file name using the signer's GUID
            $fileName = "{$signerBean->id}_signed.pdf";
            $filePath = $sugar_config['upload_dir'] . $fileName;

            // Generate the PDF and save it to the file system
            $pdf->writeHeader($header);
            $pdf->writeFooter($footer);
            ob_clean(); // avoid debug messages breaking the PDF
            $pdf->writeHTML($printable);
            $pdf->outputPDF($filePath, 'F'); // 'F' parameter saves to a local file

            // Store the file reference in the signer bean's 'pdf_document' field
            $signerBean->pdf_document = $fileName;
            $signerBean->save();

            return $filePath;

        } catch (PDFException $e) {
            
            $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.': '. " PDF generation failed for Signer ID: {$signerBean->id} - " . $e->getMessage());
            sugar_die('PDF generation failed. Please contact the system administrator.');
        }
    }
}
