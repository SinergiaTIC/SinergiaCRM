<?php
// created: 2021-12-30 20:28:20
$layout_defs["Prospects"]["subpanel_setup"]['prospects_documents_1'] = array(
    'order' => 100,
    'module' => 'Documents',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_PROSPECTS_DOCUMENTS_1_FROM_DOCUMENTS_TITLE',
    'get_subpanel_data' => 'prospects_documents_1',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);
