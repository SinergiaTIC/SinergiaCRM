<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $sugar_config;

$action = $_REQUEST["action"];

switch ($action) {
    case 'eventRegistration':
        $url = $sugar_config['site_url'] .'/index.php?action=DetailView&module=stic_Registrations&record=' . $_REQUEST["id"];
        break;
    
    default:
        # code...
        break;
}

// Generate the QR code using the internal library
$qrCode = new TCPDF2DBarcode($url, 'QRCODE,H');

// Save the QR Code image as a PNG file
$qrImage = $qrCode->getBarcodePngData(6, 6, array(0, 0, 0));

header('Content-Type: image/png');
echo $qrImage;
exit();