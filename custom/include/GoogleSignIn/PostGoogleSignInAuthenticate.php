<?php

	$userObj = new User();
	$userObj->retrieve_by_email_address($_POST['user_email']);

	$result = array('code' => 0);
	
	$GLOBALS['log']->fatal(__METHOD__." USER ID: ".print_r($userObj->id, true));

	if(empty($userObj->id)) {
		$result['message'] = "Sorry, This '".$_POST['user_email']."' user is not exist in CRM. Kindly contact the administrator.";
		echo json_encode($result);
		exit();
	}

	if (!defined('SUITE_PHPUNIT_RUNNER')) {
	    session_regenerate_id(false);
	}
	global $mod_strings, $sugar_config;
	$login_vars = $GLOBALS['app']->getLoginVars(false);

	$userAuthenticateClass = 'SugarAuthenticateUser';
	$authenticationDir = 'SugarAuthenticate';

	if (file_exists('custom/modules/Users/authentication/'.$authenticationDir.'/' . $userAuthenticateClass . '.php')) {
        require_once('custom/modules/Users/authentication/'.$authenticationDir.'/' . $userAuthenticateClass . '.php');
    }
    elseif (file_exists('modules/Users/authentication/'.$authenticationDir.'/' . $userAuthenticateClass . '.php')) {
        require_once('modules/Users/authentication/'.$authenticationDir.'/' . $userAuthenticateClass . '.php');
    }

    $user_id = '1';
    
    $userAuthenticate = new SugarAuthenticateUser();

    $userAuthenticate->loadUserOnSession($userObj->id);

    if (isset ($sugar_config['unique_key']))$_SESSION['unique_key'] = $sugar_config['unique_key'];

	//set user language
	if (isset ($reset_language_on_default_user) && $reset_language_on_default_user && $GLOBALS['current_user']->user_name == $sugar_config['default_user_name']) {
		$authenticated_user_language = $sugar_config['default_language'];
	} else {
		$authenticated_user_language = isset($_REQUEST['login_language']) ? $_REQUEST['login_language'] : (isset ($_REQUEST['ck_login_language_20']) ? $_REQUEST['ck_login_language_20'] : $sugar_config['default_language']);
	}

	$_SESSION['authenticated_user_language'] = $authenticated_user_language;

	$GLOBALS['log']->debug("authenticated_user_language is $authenticated_user_language");

	// Clear all uploaded import files for this user if it exists
    require_once('modules/Import/ImportCacheFiles.php');
    $tmp_file_name = ImportCacheFiles::getImportDir()."/IMPORT_" . $GLOBALS['current_user']->id;

	if (file_exists($tmp_file_name)) {
		unlink($tmp_file_name);
	}

	if(isset($GLOBALS['current_user']))
		$GLOBALS['current_user']->call_custom_logic('after_login');

	global $record;
    global $current_user;
    global $sugar_config;

    if(!empty($GLOBALS['sugar_config']['default_module']) && !empty($GLOBALS['sugar_config']['default_action'])) {
        $url = "index.php?module={$GLOBALS['sugar_config']['default_module']}&action={$GLOBALS['sugar_config']['default_action']}";
    } else {
	    $modListHeader = query_module_access_list($current_user);
	    //try to get the user's tabs
	    $tempList = $modListHeader;
	    $idx = array_shift($tempList);
	    if(!empty($modListHeader[$idx])){
	    	$url = "index.php?module={$modListHeader[$idx]}&action=index";
	    }
    }
    $result['code'] = 1;
    $result['message'] = $url;
    echo json_encode($result);
?>