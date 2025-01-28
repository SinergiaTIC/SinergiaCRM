<?php
	global $current_user, $sugar_config;

	LogicHook::initialize()->call_custom_logic('Users', 'before_login');
	$userObj = BeanFactory::newBean('Users');

	$userObj->retrieve_by_email_address($_POST['user_email']);

	$result = array('code' => 0);
	
	if(empty($userObj->id)) {
		$result['message'] = "Sorry, This '".$_POST['user_email']."' user is not exist in CRM. Kindly contact the administrator.";
		echo json_encode($result);
		exit();
	}

	if (!defined('SUITE_PHPUNIT_RUNNER')) {
	    session_regenerate_id(false);
	}
	$login_vars = $GLOBALS['app']->getLoginVars(false);

	$authenticateClass = 'SugarAuthenticate';
	$authenticationDir = 'SugarAuthenticate';

	if (file_exists('custom/modules/Users/authentication/'.$authenticationDir.'/' . $authenticateClass . '.php')) {
        require_once('custom/modules/Users/authentication/'.$authenticationDir.'/' . $authenticateClass . '.php');
    }
    elseif (file_exists('modules/Users/authentication/'.$authenticationDir.'/' . $authenticateClass . '.php')) {
        require_once('modules/Users/authentication/'.$authenticationDir.'/' . $authenticateClass . '.php');
    }

    $sugarAuthenticate = new SugarAuthenticate();

	$sugarAuthenticate->userAuthenticate->loadUserOnSession($userObj->id);

	$sugarAuthenticate->postLoginAuthenticate();

	if(isset($GLOBALS['current_user']))
		$GLOBALS['current_user']->call_custom_logic('after_login');



	if (!empty($GLOBALS['sugar_config']['default_module']) && !empty($GLOBALS['sugar_config']['default_action'])) {
		$url = "index.php?module={$GLOBALS['sugar_config']['default_module']}&action={$GLOBALS['sugar_config']['default_action']}";
	} else {
		$modListHeader = query_module_access_list($current_user);
		//try to get the user's tabs
		$tempList = $modListHeader;
		$idx = array_shift($tempList);
		if (!empty($modListHeader[$idx])) {
			$url = "index.php?module={$modListHeader[$idx]}&action=index";
		}
	}
    $result['code'] = 1;
    $result['message'] = $url;
    echo json_encode($result);