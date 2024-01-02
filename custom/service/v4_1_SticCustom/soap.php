<?php
chdir('../../..');
require_once('SugarWebServiceImplv4_1_SticCustom.php');
$webservice_class = 'SugarSoapService2';
$webservice_path = 'service/v2/SugarSoapService2.php';
$webservice_impl_class = 'SugarWebServiceImplv4_1_SticCustom';
$registry_class = 'registry_v4_1_SticCustom';
$registry_path = 'custom/service/v4_1_SticCustom/registry_v4_1_SticCustom.php';
$location = 'custom/service/v4_1_SticCustom/soap.php';
require_once('service/core/webservice.php');  