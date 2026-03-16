<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Canal Ètic - Entry Point
 * Coding Standards: PSR-2, 4-space indent, Inline Brackets.
 */

global $current_language, $mod_strings, $sugar_config;

// Include settings utilities
include_once 'modules/stic_Settings/Utils.php';

// Fetch settings from stic_Settings
$urlTermsSetting = stic_SettingsUtils::getSetting('GENERAL_COMPLAINT_TERMS_URL');
$termsUrl = !empty($urlTermsSetting) ? $urlTermsSetting : '#';

// 1. Language detection (URL -> Session -> CRM Default)
$lang = isset($_GET['lang']) ? $_GET['lang'] : $current_language;
$mod_strings = return_module_language($lang, 'stic_FollowUps');

/**
 * Translation function with vsprintf support for placeholders
 * @param string $key Translation key
 * @param array $args Values to substitute (optional)
 * @return string
 */
function t($key, $args = array()) 
{
    global $mod_strings;
    $label = isset($mod_strings[$key]) ? $mod_strings[$key] : $key;
    
    if (!empty($args)) {
        return vsprintf($label, $args);
    }
    return $label;
}

$db = DBManagerFactory::getInstance();

// 2. CREATE PROCESS (New report)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepta_condicions_c'])) {
    $bean = BeanFactory::newBean('stic_FollowUps');
    
    // Sanitize inputs
    $motiuC = SugarCleaner::cleanHtml($_POST['motiu_c']);
    $description = SugarCleaner::cleanHtml($_POST['description']);
    
    $bean->name = t('LBL_CHANNEL_TITLE') . ": " . $motiuC;
    $bean->description = $description;
    $bean->status = 'pending';
    $bean->start_date = TimeDate::getInstance()->nowDb();
    $bean->type = 'complaint';
    $bean->assigned_user_id = stic_SettingsUtils::getSetting('GENERAL_COMPLAINT_ASSIGNED_USER');
    
    $bean->motiu_c = $motiuC;
    $bean->accepta_condicions_c = 1;

    // Contact identification logic
    if (isset($_POST['persona']) && $_POST['persona'] !== 'anonima') {
        $firstName = SugarCleaner::cleanHtml($_POST['contact_first_name']);
        $lastName = SugarCleaner::cleanHtml($_POST['contact_last_name']);
        $email = SugarCleaner::cleanHtml($_POST['contact_email1']);
    
        $contactSeed = BeanFactory::newBean('Contacts');
        $safeEmail = $db->quote($email);
        $safeLastName = $db->quote($lastName);
    
        // Search for existing contact by last name and email
        $where = "contacts.last_name = '$safeLastName' AND contacts.id IN (
                    SELECT eear.bean_id FROM email_addr_bean_rel eear 
                    JOIN email_addresses ea ON ea.id = eear.email_address_id 
                    WHERE ea.email_address = '$safeEmail' AND eear.bean_module = 'Contacts' AND eear.deleted = 0
                  )";
        
        $existingContacts = $contactSeed->get_full_list('', $where);
    
        if (!empty($existingContacts)) {
            $contactId = $existingContacts[0]->id;
        } else {
            // Create new contact if not found
            $newContact = BeanFactory::newBean('Contacts');
            $newContact->first_name = $firstName;
            $newContact->last_name = $lastName;
            $newContact->email1 = $email;
            $newContact->assigned_user_id = $bean->assigned_user_id;
            $newContact->save();
            $contactId = $newContact->id;
        }
        // Link report to contact using the relationship field
        $bean->stic_followups_contactscontacts_ida = $contactId;
    }
    
    $bean->save();
    $bean->retrieve($bean->id);
    
    $numeroSeguiment = str_pad($bean->num_seguiment_c, 5, "0", STR_PAD_LEFT);
    $codigoVerificacion = strtoupper(substr($bean->id, -8));

    // Attachment handling
    for ($i = 1; $i <= 10; $i++) {
        if (isset($_FILES['filename' . $i]) && $_FILES['filename' . $i]['error'] == 0) {
            $doc = BeanFactory::newBean('Documents');
            $doc->document_name = $_FILES['filename' . $i]['name'];
            $doc->active_date = TimeDate::getInstance()->nowDbDate();
            $doc->date_entered = TimeDate::getInstance()->nowDbDate(); 
            $doc->status_id = 'Active';
            $doc->save();
            
            $revision = BeanFactory::newBean('DocumentRevisions');
            $revision->document_id = $doc->id;
            $revision->filename = $_FILES['filename' . $i]['name'];
            $revision->revision = '1';
            $revision->save();
            
            $doc->document_revision_id = $revision->id;
            $doc->save();
            
            if (move_uploaded_file($_FILES['filename' . $i]['tmp_name'], "upload/{$revision->id}")) {
                $relName = 'stic_followups_documents_1'; 
                if ($bean->load_relationship($relName)) {
                    $bean->$relName->add($doc->id);
                }
            }
        }
    }

    // Success message display
    echo "
    <div style='font-family:sans-serif; max-width:600px; margin:50px auto; border:1px solid #ddd; border-radius:10px; text-align:center; padding:30px; background:white;'>
        <h2 style='color:#8ca33e;'>" . t('LBL_HEADER_SUCCESS') . "</h2>
        <div style='background:#f9faf5; border:1px dashed #8ca33e; padding:20px; margin:20px 0;'>
            <p style='margin:0;'>" . t('LBL_FIELD_TRACKING_NUM') . ": <strong style='font-size:1.2em;'>#{$numeroSeguiment}</strong></p>
            <p style='margin:10px 0 0 0;'>" . t('LBL_FIELD_VERIFICATION_CODE') . ": <strong style='font-size:1.2em; color:#5d6d29;'>{$codigoVerificacion}</strong></p>
        </div>
        <p style='font-size:0.9em; color:#666;'>" . t('LBL_INFO_SAVE_CODES') . "</p>
        <a href='?entryPoint=canalDenuncias' style='display:inline-block; margin-top:15px; background:#8ca33e; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>" . t('LBL_BTN_BACK') . "</a>
    </div>";
    exit;
}

// 3. CONSULTATION PROCESS (Follow-up)
$resultadoConsulta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'consulta') {
    $num = (int)$_POST['id_bustia_c'];
    $numSafe = $db->quote($num);
    $codeSafe = $db->quote($_POST['codi_seguiment_c']);
    
    $seed = BeanFactory::newBean('stic_FollowUps');
    $where = "stic_followups_cstm.num_seguiment_c = '$numSafe' AND RIGHT(stic_followups.id, 8) = '$codeSafe'";
    $list = $seed->get_full_list("", $where);

    if (!empty($list)) {
        $denuncia = $list[0];
        
        // Manual mapping for status labels
        $labelStatuses = array(
            'pending' => t('LBL_STATUS_PENDING'),
            'planned' => t('LBL_STATUS_IN_PROGRESS'),
            'done'    => t('LBL_STATUS_DONE')
        );

        $currentStatus = isset($labelStatuses[$denuncia->status]) ? $labelStatuses[$denuncia->status] : $denuncia->status;
        $formattedNum = str_pad($denuncia->num_seguiment_c, 5, "0", STR_PAD_LEFT);
        
        $message = t('LBL_STATUS_MSG_OK', array("#".$formattedNum, $currentStatus));
        
        if ($denuncia->status === 'done' && !empty($denuncia->pending_actions)) {
            $message .= "<br><br><div style='border-top: 1px solid #c3e6cb; padding-top: 10px; margin-top: 10px;'>";
            $message .= "<strong>" . t('LBL_FIELD_RESOLUTION') . "</strong><br>" . nl2br($denuncia->pending_actions);
            $message .= "</div>";
        }
        
        $resultadoConsulta = array('status' => 'success', 'msg' => $message);
    } else {
        $resultadoConsulta = array('status' => 'error', 'msg' => t('LBL_STATUS_MSG_ERROR'));
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('LBL_CHANNEL_TITLE'); ?></title>
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
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .feature-item { padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 8px; }
        .hidden { display: none; }
        @media (max-width: 600px) { .nav-tab { flex: 1 1 50%; } }
    </style>
</head>
<body>
<div style="text-align: right; padding: 10px;">
    <a href="?entryPoint=canalDenuncias&lang=ca_ES">Català</a> | 
    <a href="?entryPoint=canalDenuncias&lang=es_ES">Castellano</a> | 
    <a href="?entryPoint=canalDenuncias&lang=eu_ES">Euskera</a> | 
    <a href="?entryPoint=canalDenuncias&lang=gl_ES">Gallego</a> | 
    <a href="?entryPoint=canalDenuncias&lang=en_us">English</a>
</div>

<div class="main-container">
    <div class="nav-tabs">
        <div class="nav-tab active" onclick="showTab('tab-report')"><?php echo t('LBL_TAB_REPORT'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-followup')"><?php echo t('LBL_TAB_FOLLOWUP'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-security')"><?php echo t('LBL_TAB_SECURITY'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-confidentiality')"><?php echo t('LBL_TAB_CONFIDENTIALITY'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-about')"><?php echo t('LBL_TAB_ABOUT'); ?></div>
    </div>

    <div id="tab-report" class="content-section active">
        <h2><?php echo t('LBL_HEADER_REPORT'); ?></h2>
        <div class="info-card"><?php echo t('LBL_INFO_REPORT'); ?></div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_type" value="creacion">
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_IDENTITY_ASK'); ?></label>
                <select name="persona" id="persona_select">
                    <option value="anonima"><?php echo t('LBL_OPT_ANONYMOUS'); ?></option>
                    <option value="registre"><?php echo t('LBL_OPT_IDENTIFIED'); ?></option>
                </select>
            </div>
            <div id="fields_identificacio" class="hidden">
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo t('LBL_FIELD_FIRST_NAME'); ?></label>
                        <input type="text" name="contact_first_name">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo t('LBL_FIELD_LAST_NAME'); ?></label>
                        <input type="text" name="contact_last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo t('LBL_FIELD_EMAIL'); ?></label>
                    <input type="email" name="contact_email1">
                </div>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_SUBJECT'); ?></label>
                <input type="text" name="motiu_c" required>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_DESCRIPTION'); ?></label>
                <textarea name="description" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_ATTACHMENTS'); ?></label>
                <input type="file" name="filename1">
            </div>
            <div class="form-group" style="background: var(--bg-light); padding: 15px; border-radius: 4px;">
                <label style="font-weight: normal; cursor: pointer;">
                    <input type="checkbox" name="accepta_condicions_c" required>
                    <?php echo t('LBL_FIELD_ACCEPT'); ?>
                    <a href="<?php echo $termsUrl; ?>" target="_blank" style="color: var(--primary-color); font-weight: bold;">
                        <?php echo t('LBL_LINK_TERMS'); ?>
                    </a>
                </label>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo t('LBL_BTN_SEND'); ?></button>
        </form>
    </div>

    <div id="tab-followup" class="content-section">
        <h2><?php echo t('LBL_HEADER_FOLLOWUP'); ?></h2>
        <?php if ($resultadoConsulta): ?>
            <div class="alert alert-<?php echo $resultadoConsulta['status']; ?>"><?php echo $resultadoConsulta['msg']; ?></div>
        <?php endif; ?>
        <div class="info-card"><?php echo t('LBL_INFO_FOLLOWUP'); ?></div>
        <form method="POST">
            <input type="hidden" name="action_type" value="consulta">
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_TRACKING_NUM'); ?></label>
                <input type="text" name="id_bustia_c" required>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_FIELD_VERIFICATION_CODE'); ?></label>
                <input type="text" name="codi_seguiment_c" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo t('LBL_BTN_CONSULT'); ?></button>
        </form>
    </div>

    <div id="tab-security" class="content-section">
        <h2><?php echo t('LBL_SECURITY_TITLE'); ?></h2>
        <p><?php echo t('LBL_SECURITY_DESC'); ?></p>
        <div class="feature-grid">
            <div class="feature-item">
                <strong><?php echo t('LBL_SEC_ENCRYPTION_TITLE'); ?></strong>
                <?php echo t('LBL_SEC_ENCRYPTION_DESC'); ?>
            </div>
            <div class="feature-item">
                <strong><?php echo t('LBL_SEC_ANONYMITY_TITLE'); ?></strong>
                <?php echo t('LBL_SEC_ANONYMITY_DESC'); ?>
            </div>
            <div class="feature-item">
                <strong><?php echo t('LBL_SEC_INTEGRITY_TITLE'); ?></strong>
                <?php echo t('LBL_SEC_INTEGRITY_DESC'); ?>
            </div>
            <div class="feature-item">
                <strong><?php echo t('LBL_SEC_ACCESS_TITLE'); ?></strong>
                <?php echo t('LBL_SEC_ACCESS_DESC'); ?>
            </div>
        </div>
    </div>

    <div id="tab-confidentiality" class="content-section">
        <h2><?php echo t('LBL_CONFID_TITLE'); ?></h2>
        <p><?php echo t('LBL_CONFID_DESC'); ?></p>
        <div class="info-card">
            <ul>
                <li><?php echo t('LBL_CONFID_ITEM_1'); ?></li>
                <li><?php echo t('LBL_CONFID_ITEM_2'); ?></li>
                <li><?php echo t('LBL_CONFID_ITEM_3'); ?></li>
                <li><?php echo t('LBL_CONFID_ITEM_4'); ?></li>
            </ul>
        </div>
        <p><?php echo t('LBL_CONFID_FOOTER'); ?></p>
    </div>

    <div id="tab-about" class="content-section">
        <h2><?php echo t('LBL_ABOUT_TITLE'); ?></h2>
        <p><?php echo t('LBL_ABOUT_P1'); ?></p>
        <p><?php echo t('LBL_ABOUT_P2'); ?></p>
        <p><?php echo t('LBL_ABOUT_P3'); ?></p>
    </div>
</div>

<script>
    // Tab switching logic
    function showTab(tabId) {
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(t => { if(t.getAttribute('onclick').includes(tabId)) t.classList.add('active'); });
    }
    
    // Toggle identification fields
    document.getElementById('persona_select').addEventListener('change', function() {
        document.getElementById('fields_identificacio').classList.toggle('hidden', this.value === 'anonima');
    });

    // Keep active tab on follow-up consultation
    <?php if (isset($_POST['action_type']) && $_POST['action_type'] === 'consulta'): ?>
        showTab('tab-followup');
    <?php endif; ?>
</script>
</body>
</html>
