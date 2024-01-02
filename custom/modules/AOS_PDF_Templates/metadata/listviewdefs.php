<?php
$listViewDefs['AOS_PDF_Templates'] =
array(
    'NAME' => array(
        'width' => '15%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'TYPE' => array(
        'width' => '10%',
        'label' => 'LBL_TYPE',
        'default' => true,
    ),
    'PAGE_SIZE' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PAGE_SIZE',
        'width' => '10%',
    ),
    'ORIENTATION' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ORIENTATION',
        'width' => '10%',
    ),
    'ASSIGNED_USER_NAME' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_CREATED',
        'default' => false,
        'module' => 'Users',
        'link' => true,
        'id' => 'CREATED_BY',
    ),
    'MODIFIED_BY_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_MODIFIED_NAME',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false,
    ),
);

?>
