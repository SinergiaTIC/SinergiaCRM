<?php

$beanList['seven_sms_inbound'] = 'seven_sms_inbound';
$beanList['seven_sms'] = 'seven_sms';
$beanList['seven_templates'] = 'seven_templates';

$beanFiles['seven_sms_inbound'] = 'modules/seven_sms_inbound/seven_sms_inbound.php';
$beanFiles['seven_sms'] = 'modules/seven_sms/seven_sms.php';
$beanFiles['seven_templates'] = 'modules/seven_templates/seven_templates.php';

$modInvisList[] = 'seven_sms_inbound';
$modInvisList[] = 'seven_sms';
$modInvisList[] = 'seven_templates';

$modules_exempt_from_availability_check['seven_sms_inbound'] = 'seven_sms_inbound';
$modules_exempt_from_availability_check['seven_sms'] = 'seven_sms';
$modules_exempt_from_availability_check['seven_templates'] = 'seven_templates';

// We don't want the modules to show on KReporter
// $report_include_modules['seven_sms_inbound'] = 'seven_sms_inbound';
// $report_include_modules['seven_sms'] = 'seven_sms';
// $report_include_modules['seven_templates'] = 'seven_templates';
