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

// Recover global CRM configuration
global $current_language, $mod_strings, $sugar_config;

// Check if the channel is enabled in the Administration panel
if (isset($sugar_config['whistleblowing_enabled']) && $sugar_config['whistleblowing_enabled'] === '0') {
    $_mod_strings = return_module_language($current_language, 'stic_Whistleblowing');
    $disabledMessage = $_mod_strings['LBL_WHISTLEBLOWING_DISABLED'] ?? 'Whistleblowing channel disabled';
    echo "<div style='font-family:sans-serif; max-width:600px; margin:100px auto; border:1px solid #ddd; border-radius:10px; text-align:center; padding:30px; background:white;'>"
        . '<h2 style="color:#8ca33e;">' . htmlspecialchars($disabledMessage) . '</h2></div>';
    exit;
}

// Get the language for the form
$lang = isset($_GET['lang']) ? $_GET['lang'] : $current_language;
$mod_strings = return_module_language($lang, 'stic_Whistleblowing');

/**
 * Translation helper with vsprintf support. Module scoped to avoid global namespace pollution.
 *
 * @param string $key Mod string key
 * @param array  $args Optional vsprintf args
 * @return string
 */
function whistleblowingTranslate($key, $args = array())
{
    global $mod_strings;
    $label = isset($mod_strings[$key]) ? $mod_strings[$key] : $key;
    if (!empty($args)) {
        return vsprintf($label, $args);
    }
    return $label;
}

// Recover the administrative texts of the tabs (About, Confidentiality and Security)
$textAbout = isset($sugar_config['whistleblowing_text_about']) ? $sugar_config['whistleblowing_text_about'] : '';
$textConfid = isset($sugar_config['whistleblowing_text_confidentiality']) ? $sugar_config['whistleblowing_text_confidentiality'] : '';
$textSecurity = isset($sugar_config['whistleblowing_text_security']) ? $sugar_config['whistleblowing_text_security'] : '';

$db = DBManagerFactory::getInstance();

// Allowed extensions and max size (in bytes) for complaint attachments
$allowedUploadExtensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt', 'msg', 'eml', 'zip');
$maxUploadSize = (int) ($sugar_config['upload_maxsize'] ?? 3000000);

// 1. Process the creation of a new complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepta_condicions_c'])) {

    // reCAPTCHA verification (if enabled in Administration and a valid configuration
    // exists in the Study Labels constants). Reuses the reCaptcha infrastructure of stic_Web_Forms
    if (!empty($sugar_config['whistleblowing_recaptcha_enabled']) && $sugar_config['whistleblowing_recaptcha_enabled'] === '1') {
        require_once 'modules/stic_Web_Forms/sticUtils.php';
        $recaptchaConfigs = SticWebFormsUtils::getRecaptchaConfigurations();
        // Use the "(General)" reCaptcha configuration or, in its absence, the first valid one
        $recaptchaConfig = (is_array($recaptchaConfigs) && isset($recaptchaConfigs['(General)'])) ? $recaptchaConfigs['(General)'] : (is_array($recaptchaConfigs) ? reset($recaptchaConfigs) : null);

        $recaptchaOk = false;
        if (!empty($recaptchaConfig)) {
            $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';
            if (!empty($recaptchaToken)) {
                // Verify the received token against Google, as done in stic_Web_Forms/Entrypoints/SaveRecaptcha.php
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptchaConfig['KEY'], 'response' => $recaptchaToken)));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $recaptchaResponse = json_decode((string) curl_exec($ch), true);
                curl_close($ch);
                $recaptchaOk = !empty($recaptchaResponse['success'])
                    && (!isset($recaptchaResponse['score']) || $recaptchaResponse['score'] >= 0.5); // OK for reCaptcha v2 and v3
            }
        }
        if (!$recaptchaOk) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': reCAPTCHA not validated. The form has not been sent.');
            echo "<div style='font-family:sans-serif; max-width:600px; margin:50px auto; border:1px solid #f5c6cb; border-radius:10px; text-align:center; padding:30px; background:#f8d7da;'>"
                . '<h2 style="color:#721c24;">' . htmlspecialchars(whistleblowingTranslate('LBL_RECAPTCHA_ERROR')) . '</h2>'
                . "<a href='?entryPoint=sticWhistleblowing' style='display:inline-block; margin-top:15px; background:#8ca33e; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>" . htmlspecialchars(whistleblowingTranslate('LBL_BOTO_ENVIAR')) . '</a></div>';
            exit;
        }
        if (empty($recaptchaConfig)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': reCAPTCHA enabled in Administration but no valid configuration in Study Labels. The form has been accepted without captcha.');
        }
    }

    require_once 'modules/stic_Settings/Utils.php';
    $allowedUsersStr = stic_SettingsUtils::getSetting('WHISTLEBLOWING_ALLOWED_USERS');
    $allowedUsers = array_map('trim', explode(',', $allowedUsersStr));

    $bean = BeanFactory::newBean('stic_Whistleblowing');
    $bean->name = whistleblowingTranslate('LBL_CANAL_TITOL') . ': ' . $_POST['type'];
    $bean->description = $_POST['description'];
    $bean->stic_status = 'new';
    $bean->assigned_user_id = !empty($allowedUsers[0]) ? $allowedUsers[0] : '';
    $bean->stic_category = $_POST['category'];

    // Validate the start date received from the public form (Y-m-d format)
    $startDate = DateTime::createFromFormat('Y-m-d', $_POST['stic_start_date'] ?? '');
    $bean->stic_start_date = $startDate ? $startDate->format('Y-m-d') : TimeDate::getInstance()->nowDbDate();

    // Non anonymous complaints: search for an existing Contact by email/surname or create a new one
    if (isset($_POST['persona']) && $_POST['persona'] !== 'anonima') {
        $lastName = $_POST['contact_last_name'] ?? '';
        $email = $_POST['contact_email1'] ?? '';
        $contactSeed = BeanFactory::newBean('Contacts');
        // Duplicate check filtered by surname and email
        $safeLastName = $db->quote($lastName);
        $safeEmail = $db->quote($email);
        $where = "contacts.last_name = '{$safeLastName}' AND contacts.id IN (SELECT eear.bean_id FROM email_addr_bean_rel eear JOIN email_addresses ea ON ea.id = eear.email_address_id WHERE ea.email_address = '{$safeEmail}' AND eear.bean_module = 'Contacts' AND eear.deleted = 0)";
        $existingContacts = $contactSeed->get_full_list('', $where);
        if (!empty($existingContacts)) {
            $contactId = $existingContacts[0]->id;
        } else {
            $newContact = BeanFactory::newBean('Contacts');
            $newContact->first_name = $_POST['contact_first_name'] ?? '';
            $newContact->last_name = $lastName;
            $newContact->email1 = $email;
            $newContact->assigned_user_id = $bean->assigned_user_id;
            $newContact->save();
            $contactId = $newContact->id;
        }
        // Relate the Contact (Persona) with the complaint. The relate field in vardefs
        // ('stic_whistleblowing_contactscontacts_ida', save => 1) creates the join record on save
        $bean->stic_whistleblowing_contactscontacts_ida = $contactId;
    }

    $bean->save();
    $bean->retrieve($bean->id);
    $numeroSeguiment = str_pad($bean->stic_code, 5, '0', STR_PAD_LEFT);
    $codigoVerificacion = strtoupper(substr($bean->id, -12));

    // Attachments
    for ($i = 1; $i <= 10; $i++) {
        if (!isset($_FILES['filename' . $i]) || $_FILES['filename' . $i]['error'] != 0) {
            continue;
        }
        $fileExt = strtolower(pathinfo($_FILES['filename' . $i]['name'], PATHINFO_EXTENSION));
        $fileSize = (int) $_FILES['filename' . $i]['size'];
        if (!in_array($fileExt, $allowedUploadExtensions, true)) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Rejected file due to invalid extension: ' . $_FILES['filename' . $i]['name']);
            continue;
        }
        if ($fileSize > $maxUploadSize) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Rejected file due to max size: ' . $_FILES['filename' . $i]['name']);
            continue;
        }

        // Create a new Document linked to the current complaint
        $doc = BeanFactory::newBean('Documents');
        $doc->document_name = $_FILES['filename' . $i]['name'];
        $doc->active_date = TimeDate::getInstance()->nowDbDate();
        $doc->status_id = 'Active';
        $doc->save();
        $revision = BeanFactory::newBean('DocumentRevisions');
        $revision->document_id = $doc->id;
        $revision->filename = $_FILES['filename' . $i]['name'];
        $revision->revision = '1';
        $revision->save();
        $doc->document_revision_id = $revision->id;
        $doc->save();
        if ($bean->load_relationship('stic_whistleblowing_documents')) {
            $bean->stic_whistleblowing_documents->add($doc->id);
        }
    }

    echo "<div style='font-family:sans-serif; max-width:600px; margin:50px auto; border:1px solid #ddd; border-radius:10px; text-align:center; padding:30px; background:white;'><h2 style='color:#8ca33e;'>" . whistleblowingTranslate('LBL_HEADER_CREACIO') . "</h2><div style='background:#f9faf5; border:1px dashed #8ca33e; padding:20px; margin:20px 0;'><p style='margin:0;'>" . whistleblowingTranslate('LBL_NUM_SEGUIMENT') . ': <strong style="font-size:1.2em;">#' . htmlspecialchars($numeroSeguiment) . "</strong></p><p style='margin:10px 0 0 0;'>" . whistleblowingTranslate('LBL_CODI_VERIFICACIO') . ': <strong style="font-size:1.2em; color:#5d6d29;">' . htmlspecialchars($codigoVerificacion) . '</strong></p></div><p style="font-size:0.9em; color:#666;">' . htmlspecialchars(whistleblowingTranslate('LBL_INFO_GUARDAR_CODES')) . '</p><a href="?entryPoint=sticWhistleblowing" style="display:inline-block; margin-top:15px; background:#8ca33e; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">' . htmlspecialchars(whistleblowingTranslate('LBL_BOTO_NOVA_COMUNICACIO')) . '</a></div>';
    exit;
}

// 2. Process the follow up state query
$resultadoConsulta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'consulta') {
    $num = (int) ($_POST['id_bustia_c'] ?? 0);
    $code = (string) $_POST['codi_seguiment_c'];
    $seed = BeanFactory::newBean('stic_Whistleblowing');
    $tableName = $seed->table_name;
    $safeCode = $db->quote($code);
    $where = "{$tableName}.stic_code = {$num} AND RIGHT({$tableName}.id, 12) = '{$safeCode}' AND {$tableName}.deleted = 0";
    $list = $seed->get_full_list('', $where);
    if (!empty($list)) {
        $denuncia = $list[0];
        $statusLabels = array(
            'new' => whistleblowingTranslate('LBL_WHISTLEBLOWING_STATUS_NEW'),
            'working' => whistleblowingTranslate('LBL_WHISTLEBLOWING_STATUS_WORKING'),
            'solved' => whistleblowingTranslate('LBL_WHISTLEBLOWING_STATUS_SOLVED'),
            'archived' => whistleblowingTranslate('LBL_WHISTLEBLOWING_STATUS_ARCHIVED'),
        );
        $estadoActual = isset($statusLabels[$denuncia->stic_status]) ? $statusLabels[$denuncia->stic_status] : $denuncia->stic_status;
        $numFormatejat = str_pad($denuncia->stic_code, 5, '0', STR_PAD_LEFT);
        $missatge = whistleblowingTranslate('LBL_ESTAT_OK', array('#' . htmlspecialchars($numFormatejat), htmlspecialchars($estadoActual)));
        if ($denuncia->stic_status === 'solved' && !empty($denuncia->stic_status_detail)) {
            $missatge .= '<br><br><div style="border-top: 1px solid #c3e6cb; padding-top: 10px; margin-top: 10px;"><strong>' . whistleblowingTranslate('LBL_RESOLUCIO') . '</strong><br>' . nl2br(htmlspecialchars($denuncia->stic_status_detail)) . '</div>';
        }
        $resultadoConsulta = array('status' => 'success', 'msg' => $missatge);
    } else {
        $resultadoConsulta = array('status' => 'error', 'msg' => whistleblowingTranslate('LBL_ESTAT_ERROR'));
    }
}
// Check reCaptcha availability for the public form (enabled in Administration and valid configuration)
$whistleblowingRecaptcha = null;
if (!empty($sugar_config['whistleblowing_recaptcha_enabled']) && $sugar_config['whistleblowing_recaptcha_enabled'] === '1') {
    require_once 'modules/stic_Web_Forms/sticUtils.php';
    $recaptchaConfigsAll = SticWebFormsUtils::getRecaptchaConfigurations();
    // Use the "(General)" reCaptcha configuration or, in its absence, the first valid one
    $whistleblowingRecaptcha = (is_array($recaptchaConfigsAll) && isset($recaptchaConfigsAll['(General)'])) ? $recaptchaConfigsAll['(General)'] : (is_array($recaptchaConfigsAll) ? reset($recaptchaConfigsAll) : null);
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(whistleblowingTranslate('LBL_CANAL_TITOL')); ?></title>
    <style>
        :root { --primary-color: #8ca33e; --primary-dark: #5d6d29; --bg-light: #f4f5f0; --text-main: #333; }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg-light); color: var(--text-main); margin: 0; padding: 20px; line-height: 1.6; }
        .main-container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        .nav-tabs { display: flex; background: #e9ebdf; border-bottom: 3px solid var(--primary-color); flex-wrap: wrap; }
        .nav-tab { flex: 1; min-width: 120px; padding: 15px 10px; text-align: center; cursor: pointer; font-weight: bold; color: #555; transition: 0.3s; border-right: 1px solid #dcdfd0; font-size: 0.9em; }
        .nav-tab.active { background: var(--primary-color); color: white; }
        .content-section { padding: 40px; display: none; min-height: 400px; }
        .content-section.active { display: block; }
        h2 { color: var(--primary-dark); margin-top: 0; border-bottom: 2px solid var(--bg-light); padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; }
        input[type="text"], input[type="email"], select, textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-primary { background: var(--primary-color); color: white; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #eef2e1; color: var(--primary-dark); border: 1px solid var(--primary-color); }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info-card { background: #f9f9f9; padding: 20px; border-radius: 6px; border-left: 5px solid var(--primary-color); margin-bottom: 20px; }
        .hidden { display: none; }
        /* Styles for admin texts coming from the administrator config */
        .admin-content-render { white-space: pre-wrap; word-wrap: break-word; }
        @media (max-width: 600px) { .nav-tab { flex: 1 1 50%; } }
    </style>
</head>
<body>
<div style="text-align: right; padding: 10px;">
    <a href="?entryPoint=sticWhistleblowing&lang=ca_ES">Català</a> |
    <a href="?entryPoint=sticWhistleblowing&lang=es_ES">Castellano</a> |
    <a href="?entryPoint=sticWhistleblowing&lang=eu_ES">Euskera</a> |
    <a href="?entryPoint=sticWhistleblowing&lang=gl_ES">Gallego</a> |
    <a href="?entryPoint=sticWhistleblowing&lang=en_us">English</a>
</div>
<div class="main-container">
    <div class="nav-tabs">
        <div class="nav-tab active" onclick="showTab('tab-denuncia')"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_DENUNCIA')); ?></div>
        <div class="nav-tab" onclick="showTab('tab-seguiment')"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_SEGUIMENT')); ?></div>
        <div class="nav-tab" onclick="showTab('tab-seguretat')"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_SEGURETAT')); ?></div>
        <div class="nav-tab" onclick="showTab('tab-confidencialitat')"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_CONFIDENCIALITAT')); ?></div>
        <div class="nav-tab" onclick="showTab('tab-acerca')"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_ACERCA')); ?></div>
    </div>

    <div id="tab-denuncia" class="content-section active">
        <h2><?php echo htmlspecialchars(whistleblowingTranslate('LBL_HEADER_CREACIO')); ?></h2>
        <div class="info-card"><?php echo nl2br(htmlspecialchars(whistleblowingTranslate('LBL_INFO_CREACIO'))); ?></div>
        <form id="denuncia_form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_type" value="creacion">
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_IDENTIFICACIO_PREGUNTA')); ?></label>
                <select name="persona" id="persona_select">
                    <option value="anonima"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_ANONIM')); ?></option>
                    <option value="registre"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_IDENTIFICAT')); ?></option>
                </select>
            </div>
            <div id="fields_identificacio" class="hidden">
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_NOM')); ?></label>
                        <input type="text" name="contact_first_name">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_COGNOMS')); ?></label>
                        <input type="text" name="contact_last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_EMAIL')); ?></label>
                    <input type="email" name="contact_email1">
                </div>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_CATEGORY_PREGUNTA')); ?></label>
                <select name="category" id="category_select">
                    <option value="fraud"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_FRAUD')); ?></option>
                    <option value="harassment"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_HARASSMENT')); ?></option>
                    <option value="data_privacy"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_DATA_PRIVACY')); ?></option>
                    <option value="others"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_OTHERS')); ?></option>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_MOTIU')); ?></label>
                <input type="text" name="type" required>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_START_DATE')); ?></label>
                <input type="date" id="stic_start_date" name="stic_start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_DESCRIPCIO')); ?></label>
                <textarea name="description" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_ADJUNTS')); ?></label>
                <input type="file" name="filename1">
            </div>
            <div class="form-group" style="background: var(--bg-light); padding: 15px; border-radius: 4px;">
                <label style="font-weight: normal;"><input type="checkbox" name="accepta_condicions_c" required> <?php echo htmlspecialchars(whistleblowingTranslate('LBL_ACCEPTA_POLITICA')); ?></label>
            </div>
            <?php if (!empty($whistleblowingRecaptcha) && $whistleblowingRecaptcha['VERSION'] === '2'): ?>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($whistleblowingRecaptcha['WEBKEY']); ?>"></div>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_BOTO_ENVIAR')); ?></button>
        </form>
    </div>

    <div id="tab-seguiment" class="content-section">
        <h2><?php echo htmlspecialchars(whistleblowingTranslate('LBL_HEADER_SEGUIMENT')); ?></h2>
        <?php if ($resultadoConsulta): ?>
            <div class="alert alert-<?php echo $resultadoConsulta['status'] === 'success' ? 'success' : 'error'; ?>"><?php echo $resultadoConsulta['msg']; ?></div>
        <?php endif; ?>
        <div class="info-card"><?php echo nl2br(htmlspecialchars(whistleblowingTranslate('LBL_INFO_SEGUIMENT'))); ?></div>
        <form method="POST">
            <input type="hidden" name="action_type" value="consulta">
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_NUM_SEGUIMENT')); ?></label>
                <input type="text" name="id_bustia_c" required>
            </div>
            <div class="form-group">
                <label><?php echo htmlspecialchars(whistleblowingTranslate('LBL_CODI_VERIFICACIO')); ?></label>
                <input type="text" name="codi_seguiment_c" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars(whistleblowingTranslate('LBL_BOTO_CONSULTAR')); ?></button>
        </form>
    </div>

    <div id="tab-seguretat" class="content-section">
        <h2><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_SEGURETAT')); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textSecurity); ?></div>
    </div>

    <div id="tab-confidencialitat" class="content-section">
        <h2><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_CONFIDENCIALITAT')); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textConfid); ?></div>
    </div>

    <div id="tab-acerca" class="content-section">
        <h2><?php echo htmlspecialchars(whistleblowingTranslate('LBL_TAB_ACERCA')); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textAbout); ?></div>
    </div>
</div>

<script>
    function showTab(tabId) {
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(t => { if (t.getAttribute('onclick').includes(tabId)) t.classList.add('active'); });
    }
    document.getElementById('persona_select').addEventListener('change', function() {
        document.getElementById('fields_identificacio').classList.toggle('hidden', this.value === 'anonima');
    });
    <?php if (isset($_POST['action_type']) && $_POST['action_type'] === 'consulta'): ?>
        showTab('tab-seguiment');
    <?php endif; ?>
</script>
<?php if (!empty($whistleblowingRecaptcha) && $whistleblowingRecaptcha['VERSION'] === '2'): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php elseif (!empty($whistleblowingRecaptcha) && $whistleblowingRecaptcha['VERSION'] === '3'): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($whistleblowingRecaptcha['WEBKEY']); ?>"></script>
    <script>
        grecaptcha.ready(function () {
            // Interception of filing. The v3 token included in the form is verified on the
            // server side before creating the complaint (as done in stic_Web_Forms)
            document.getElementById('denuncia_form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.execute("<?php echo htmlspecialchars($whistleblowingRecaptcha['WEBKEY']); ?>", {action: 'whistleblowing'}).then(function(token) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    document.getElementById('denuncia_form').appendChild(input);
                    document.getElementById('denuncia_form').submit();
                });
            });
        });
    </script>
<?php endif; ?>
</body>
</html>
