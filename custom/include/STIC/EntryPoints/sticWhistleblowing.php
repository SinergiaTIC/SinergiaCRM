<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

// Recuperem la configuració global del CRM
global $current_language, $mod_strings, $sugar_config;

// 1. Detectar idioma
$lang = isset($_GET['lang']) ? $_GET['lang'] : $current_language;
$mod_strings = return_module_language($lang, 'stic_Whistleblowing');

/**
 * Funció de traducció amb suport per a sprintf (vsprintf)
 */
function t($key, $args = array()) {
    global $mod_strings;
    $label = isset($mod_strings[$key]) ? $mod_strings[$key] : $key;
    if (!empty($args)) {
        return vsprintf($label, $args);
    }
    return $label;
}

// RECUPERACIÓ DELS TEXTOS DE CONFIGURACIÓ (ADMINISTRACIÓ)
$textAbout    = isset($sugar_config['whistleblowing_text_about']) ? $sugar_config['whistleblowing_text_about'] : '';
$textConfid   = isset($sugar_config['whistleblowing_text_confidentiality']) ? $sugar_config['whistleblowing_text_confidentiality'] : '';
$textSecurity = isset($sugar_config['whistleblowing_text_security']) ? $sugar_config['whistleblowing_text_security'] : '';

$db = DBManagerFactory::getInstance();

// 1. Processament de CREACIÓ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepta_condicions_c'])) {
    // ... (Mantinc tot el teu codi de creació intacte) ...
    $bean = BeanFactory::newBean('stic_Whistleblowing');
    $bean->name = t('LBL_CANAL_TITOL') . ": " . $_POST['type'];
    $bean->description = $_POST['description'];
    $bean->stic_status = 'pending';
    $bean->assigned_user_id = '000001ce-cfd0-62ad-8705-6a034c4ec656';
    $bean->stic_category = $_POST['category'];
    $bean->type = $_POST['type'];
    $bean->accepta_condicions_c = 1;

    if (isset($_POST['persona']) && $_POST['persona'] !== 'anonima') {
        $first_name = $_POST['contact_first_name'];
        $last_name = $_POST['contact_last_name'];
        $email = $_POST['contact_email1'];
        $contactSeed = BeanFactory::newBean('Contacts');
        $safeEmail = $db->quote($email);
        $safeLastName = $db->quote($last_name);
        $where = "contacts.last_name = '$safeLastName' AND contacts.id IN (SELECT eear.bean_id FROM email_addr_bean_rel eear JOIN email_addresses ea ON ea.id = eear.email_address_id WHERE ea.email_address = '$safeEmail' AND eear.bean_module = 'Contacts' AND eear.deleted = 0)";
        $existingContacts = $contactSeed->get_full_list('', $where);
        if (!empty($existingContacts)) { $contactId = $existingContacts[0]->id; } 
        else {
            $newContact = BeanFactory::newBean('Contacts');
            $newContact->first_name = $first_name; $newContact->last_name = $last_name; $newContact->email1 = $email;
            $newContact->assigned_user_id = $bean->assigned_user_id; $newContact->save(); $contactId = $newContact->id;
        }
        $bean->stic_whistleblowing_contactscontacts_ida = $contactId;
    } else { $bean->anonymous = true; }
    
    $bean->save();
    $bean->retrieve($bean->id);
    $numeroSeguiment = str_pad($bean->stic_code, 5, "0", STR_PAD_LEFT);
    $codigoVerificacion = strtoupper(substr($bean->id, -12));

    for ($i = 1; $i <= 10; $i++) {
        if (isset($_FILES['filename' . $i]) && $_FILES['filename' . $i]['error'] == 0) {
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
            $rel_name = 'stic_whistleblowing_documents'; 
            if ($bean->load_relationship($rel_name)) { $bean->$rel_name->add($doc->id); }
        }
    }
    echo "<div style='font-family:sans-serif; max-width:600px; margin:50px auto; border:1px solid #ddd; border-radius:10px; text-align:center; padding:30px; background:white;'><h2 style='color:#8ca33e;'>".t('LBL_HEADER_CREACIO')."</h2><div style='background:#f9faf5; border:1px dashed #8ca33e; padding:20px; margin:20px 0;'><p style='margin:0;'>".t('LBL_NUM_SEGUIMENT').": <strong style='font-size:1.2em;'>#{$numeroSeguiment}</strong></p><p style='margin:10px 0 0 0;'>".t('LBL_CODI_VERIFICACIO').": <strong style='font-size:1.2em; color:#5d6d29;'>{$codigoVerificacion}</strong></p></div><p style='font-size:0.9em; color:#666;'>".t('LBL_INFO_SEGUIMENT')."</p><a href='?entryPoint=sticWhistleblowing' style='display:inline-block; margin-top:15px; background:#8ca33e; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>".t('LBL_BOTO_ENVIAR')."</a></div>";
    exit;
}

// 2. Processament de CONSULTA
$resultadoConsulta = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'consulta') {
    $num = (int)$_POST['id_bustia_c'];
    $num = $db->quote($num);
    $code = $db->quote($_POST['codi_seguiment_c']);
    $seed = BeanFactory::newBean('stic_Whistleblowing');
    $tableName = $seed->table_name;    
    $where = "{$tableName}.stic_code = $num AND RIGHT({$tableName}.id, 12) = '$code' AND {$tableName}.deleted = 0";
    $list = $seed->get_full_list("", $where);
    if (!empty($list)) {
        $denuncia = $list[0];
        $labelEstados = ['pending' => 'Rebuda / Pendent', 'working' => 'En tràmit', 'solved' => 'Resolta'];
        $estadoActual = isset($labelEstados[$denuncia->stic_status]) ? $labelEstados[$denuncia->stic_status] : $denuncia->stic_status;
        $numFormatejat = str_pad($denuncia->stic_code, 5, "0", STR_PAD_LEFT);
        $missatge = t('LBL_ESTAT_OK', array("#".$numFormatejat, $estadoActual));
        if ($denuncia->stic_status === 'solved' && !empty($denuncia->stic_status_detail)) {
            $missatge .= "<br><br><div style='border-top: 1px solid #c3e6cb; padding-top: 10px; margin-top: 10px;'><strong>" . t('LBL_RESOLUCIO') . "</strong><br>" . nl2br($denuncia->stic_status_detail) . "</div>";
        }
        $resultadoConsulta = array('status' => 'success', 'msg' => $missatge);
    } else { $resultadoConsulta = array('status' => 'error', 'msg' => t('LBL_ESTAT_ERROR')); }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('LBL_CANAL_TITOL'); ?></title>
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
        /* Estil per als continguts que venen del textarea d'administració */
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
        <div class="nav-tab active" onclick="showTab('tab-denuncia')"><?php echo t('LBL_TAB_DENUNCIA'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-seguiment')"><?php echo t('LBL_TAB_SEGUIMENT'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-seguretat')"><?php echo t('LBL_TAB_SEGURETAT'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-confidencialitat')"><?php echo t('LBL_TAB_CONFIDENCIALITAT'); ?></div>
        <div class="nav-tab" onclick="showTab('tab-acerca')"><?php echo t('LBL_TAB_ACERCA'); ?></div>
    </div>

    <div id="tab-denuncia" class="content-section active">
        <h2><?php echo t('LBL_HEADER_CREACIO'); ?></h2>
        <div class="info-card"><?php echo t('LBL_INFO_CREACIO'); ?></div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action_type" value="creacion">
            <div class="form-group">
                <label><?php echo t('LBL_IDENTIFICACIO_PREGUNTA'); ?></label>
                <select name="persona" id="persona_select">
                    <option value="anonima"><?php echo t('LBL_ANONIM'); ?></option>
                    <option value="registre"><?php echo t('LBL_IDENTIFICAT'); ?></option>
                </select>
            </div>
            <div id="fields_identificacio" class="hidden">
                <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo t('LBL_NOM'); ?></label>
                        <input type="text" name="contact_first_name">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><?php echo t('LBL_COGNOMS'); ?></label>
                        <input type="text" name="contact_last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo t('LBL_EMAIL'); ?></label>
                    <input type="email" name="contact_email1">
                </div>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_CATEGORY_PREGUNTA'); ?></label>
                <select name="category" id="category_select">
                    <option value="fraud"><?php echo t('LBL_FRAUD'); ?></option>
                    <option value="harassment"><?php echo t('LBL_HARASSMENT'); ?></option>
                    <option value="data_privacy"><?php echo t('LBL_DATA_PRIVACY'); ?></option>
                    <option value="others"><?php echo t('LBL_OTHERS'); ?></option>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_MOTIU'); ?></label>
                <input type="text" name="type" required>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_DESCRIPCIO'); ?></label>
                <textarea name="description" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_ADJUNTS'); ?></label>
                <input type="file" name="filename1">
            </div>
            <div class="form-group" style="background: var(--bg-light); padding: 15px; border-radius: 4px;">
                <label style="font-weight: normal;"><input type="checkbox" name="accepta_condicions_c" required> <?php echo t('LBL_ACCEPTA_POLITICA'); ?></label>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo t('LBL_BOTO_ENVIAR'); ?></button>
        </form>
    </div>

    <div id="tab-seguiment" class="content-section">
        <h2><?php echo t('LBL_HEADER_SEGUIMENT'); ?></h2>
        <?php if ($resultadoConsulta): ?>
            <div class="alert alert-<?php echo $resultadoConsulta['status']; ?>"><?php echo $resultadoConsulta['msg']; ?></div>
        <?php endif; ?>
        <div class="info-card"><?php echo t('LBL_INFO_SEGUIMENT'); ?></div>
        <form method="POST">
            <input type="hidden" name="action_type" value="consulta">
            <div class="form-group">
                <label><?php echo t('LBL_NUM_SEGUIMENT'); ?></label>
                <input type="text" name="id_bustia_c" required>
            </div>
            <div class="form-group">
                <label><?php echo t('LBL_CODI_VERIFICACIO'); ?></label>
                <input type="text" name="codi_seguiment_c" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo t('LBL_BOTO_CONSULTAR'); ?></button>
        </form>
    </div>

    <div id="tab-seguretat" class="content-section">
        <h2><?php echo t('LBL_TAB_SEGURETAT'); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textSecurity); ?></div>
    </div>
    
    <div id="tab-confidencialitat" class="content-section">
        <h2><?php echo t('LBL_TAB_CONFIDENCIALITAT'); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textConfid); ?></div>
    </div>

    <div id="tab-acerca" class="content-section">
        <h2><?php echo t('LBL_TAB_ACERCA'); ?></h2>
        <div class="admin-content-render"><?php echo nl2br($textAbout); ?></div>
    </div>
</div>

<script>
    function showTab(tabId) {
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(t => { if(t.getAttribute('onclick').includes(tabId)) t.classList.add('active'); });
    }
    document.getElementById('persona_select').addEventListener('change', function() {
        document.getElementById('fields_identificacio').classList.toggle('hidden', this.value === 'anonima');
    });
    <?php if (isset($_POST['action_type']) && $_POST['action_type'] === 'consulta'): ?>
        showTab('tab-seguiment');
    <?php endif; ?>
</script>
</body>
</html>