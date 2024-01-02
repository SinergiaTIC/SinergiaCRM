<?php
chdir('../../..');

require_once 'SugarWebServiceImplv4_1_SticCustom.php';

$webservice_path = 'service/core/SugarRestService.php';
$webservice_class = 'SugarRestService';
$webservice_impl_class = 'SugarWebServiceImplv4_1_SticCustom';
$registry_path = 'custom/service/v4_1_SticCustom/registry_v4_1_SticCustom.php';
$registry_class = 'registry_v4_1_SticCustom';
$location = 'custom/service/v4_1_SticCustom/rest.php';

require_once 'service/core/webservice.php';   