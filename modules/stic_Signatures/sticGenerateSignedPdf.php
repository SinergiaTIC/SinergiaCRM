<?php
/**
 *
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

class sticGenerateSignedPdf
{

    /** Generates a signed PDF for a given signer ID and saves it to the file system.
     * The generated PDF file name is stored in the 'pdf_document' field of the signer record.
     *
     * @param string $signedMode The mode of signing, either 'handwritten' or 'button'.
     *                           Defaults to 'handwritten'.
     * @return void
     */
    public static function generateSignedPdf($signedMode = 'handwritten')
    {
        global $sugar_config, $app_list_strings, $app_strings;

        // require_once 'modules/AOS_PDF_Templates/templateParser.php';
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

// Check that the signature is completed
        $sourceModule = $signatureBean->main_module;
        $sourceId = $signerBean->record_id;
        if (empty($sourceModule) || empty($sourceId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signature ID: {$signatureBean->id} has no associated record");
            sugar_die("Signature has no associated record");
        }

        $templateBean = BeanFactory::getBean('AOS_PDF_Templates', $signatureBean->pdftemplate_id_c);

        if (!$templateBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Template ID: {$signatureBean->pdftemplate_id_c} for Signature ID: {$signatureBean->id}");
            sugar_die("Invalid Template");
        }

        $file_name = str_replace(" ", "_", (string) $templateBean->name) . ".pdf";

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

        // Retrieve the record bean
        $sourceBean = BeanFactory::getBean($sourceModule, $sourceId);
        if (!$sourceBean) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Invalid Record ID: {$sourceId} for Module: {$sourceModule}");
            sugar_die("Invalid Record");
        }

        try {
            $pdfHistory = PDFWrapper::getPDFEngine();
            $pdfHistory->configurePDF($pdfConfig);
        } catch (PDFException $e) {
            LoggerManager::getLogger()->warn('PDFException: ' . $e->getMessage());
        }

        $object_arr = array();
        $object_arr[$sourceBean->module_dir] = $sourceBean->id;

        if ($sourceBean->module_dir === 'Contacts') {
            $object_arr['Accounts'] = $sourceBean->account_id;
        }

        // Replace the signature placeholder with the actual signature image.
        // As the image image placeholder is recovery encoded from the database, the pattern must be encoded too.
        $stringToreplace = '&lt;img class=&quot;signature&quot; src=&quot;themes/SuiteP/images/SignaturePlaceholder.png&quot; alt=&quot;&quot; width=&quot;200&quot; /&gt;';

        // Set time in user format and UTC for use later
        $userTime = new Datetime()->format('Y-m-d H:i:s (\U\T\C P)');
        $utcTime = new Datetime('UTC')->format('Y-m-d H:i:s P');

        // Depending on the signed mode, prepare the replacement HTML
        $replaceWith = '';
        switch ($signedMode) {
            case 'handwritten':
                $replaceWith = htmlspecialchars('<img class="signature" src="' . $signerBean->signature_image . '" width="200"></div>');
                break;
            case 'button':

                $textArray = [
                    'Documento aceptado por:',
                    $signerBean->parent_name,
                    $signerBean->email_address,
                    $userTime,
                    // $utcTime
                ];

                $acceptImage = stic_SignaturesUtils::generateAcceptImage($textArray);
                $replaceWith = htmlspecialchars('<img class="signature" src="' . $acceptImage . '" width="200"></div>');
                break;
            default:
                # code...
                break;
        }

        $templateBean->description = str_replace($stringToreplace, $replaceWith, (string) $templateBean->description);

        // if $signature->pdf_audit_page_c is set, add a new page to the PDF with the audit information
        if (!empty($signatureBean->pdf_audit_page) && $signatureBean->pdf_audit_page) {
            // start with new page html mark

            $sugar_smarty = new Sugar_Smarty();

            $sugar_smarty->assign('DOCUMENT_NAME', $templateBean->name);
            $sugar_smarty->assign('SIGNER_NAME', $signerBean->parent_name);
            $sugar_smarty->assign('SIGNER_EMAIL', $signerBean->email_address);
            $sugar_smarty->assign('SIGNER_PHONE', $signerBean->phone);
            $sugar_smarty->assign('SIGNER_USER_TIME', $userTime);
            $sugar_smarty->assign('SIGNER_MODE', $app_list_strings['stic_signatures_modes_list'][$signedMode]);
            $sugar_smarty->assign('SIGNER_STATUS', $app_list_strings['stic_signers_status_list'][$signerBean->status]);

            $sugar_smarty->assign('BROWSER', $signerBean->browser || '');
            $sugar_smarty->assign('MOD_STRINGS', return_module_language($GLOBALS['current_language'], 'stic_Signatures'));
            $sugar_smarty->assign('APP_STRINGS', $app_strings);

            $auditHtml = '<p style="page-break-before: always;">&nbsp;</p>';
            $auditHtml .= $sugar_smarty->fetch('modules/stic_Signatures/AuditPageTemplate.tpl');

            $auditHtml .= file_get_contents('modules/stic_Signatures/AuditPageTemplate.html');

            $templateBean->description .= htmlspecialchars($auditHtml);
        }

        $search = array(
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
        );

        $replace = array(
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
        );

        $text = preg_replace($search, $replace, (string) $templateBean->description);
        $text = preg_replace_callback(
            '/{DATE\s+(.*?)}/',
            function ($matches) {
                return date($matches[1]);
            },
            $text
        );

// STIC-Custom 20240125 JBL - Product line items in pdf
// https://github.com/SinergiaTIC/SinergiaCRM/pull/76
        if (str_starts_with($sourceModule, "AOS_")) {
            $variableName = strtolower($sourceBean->module_dir);
            $lineItemsGroups = array();
            $lineItems = array();

            $sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $sourceBean->object_name . "' AND pg.parent_id = '" . $sourceBean->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
            $res = $sourceBean->db->query($sql);
            while ($row = $sourceBean->db->fetchByAssoc($res)) {
                $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
                $lineItems[$row['id']] = $row['product_id'];
            }

            //backward compatibility
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

            $text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
            $text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
            $text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
            $text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
            $text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
            $text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
            $text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
            $text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

            $text = populate_group_lines($text, $lineItemsGroups, $lineItems);
        }
        // END STIC-Custom 20240125

        $header = preg_replace($search, $replace, (string) $templateBean->pdfheader);
        $footer = preg_replace($search, $replace, (string) $templateBean->pdffooter);

        $parsedText = stic_SignaturesUtils::getParsedTemplate($signerBean->id);
        $converted = $parsedText['converted'];
        $header = $parsedText['header'];
        $footer = $parsedText['footer'];

        $printable = str_replace("\n", "<br />", (string) $converted);

        try {
            // Generar un nombre de archivo único utilizando el ID (GUID) del firmante
            $fileName = "{$signerBean->id}_signed.pdf";
            $filePath = $sugar_config['upload_dir'] . $fileName;

            // Generar el PDF y guardarlo en el sistema de archivos
            $pdf->writeHeader($header);
            $pdf->writeFooter($footer);
            $pdf->writeHTML($printable);
            $pdf->outputPDF($filePath, 'F'); // El parámetro 'F' indica que se guardará como un archivo

            // Almacenar la referencia del archivo en el base de datos
            $signerBean->pdf_document = $fileName;
            $signerBean->save();

        } catch (PDFException $e) {
            LoggerManager::getLogger()->warn('PDFException: ' . $e->getMessage());
            sugar_die("Error al generar o guardar el PDF: " . $e->getMessage());
        }
        return $filePath;
    }
}
