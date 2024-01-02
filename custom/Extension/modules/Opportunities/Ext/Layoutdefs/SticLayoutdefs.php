<?php

$layout_defs['Opportunities']['subpanel_setup']['accounts']['override_subpanel_name'] = 'SticDefault';
$layout_defs['Opportunities']['subpanel_setup']['leads']['override_subpanel_name'] = 'SticDefault';

// Subpanels default sorting
$layout_defs['Opportunities']['subpanel_setup']['activities']['sort_order'] = 'asc';
$layout_defs['Opportunities']['subpanel_setup']['activities']['sort_by'] = 'date_due';
$layout_defs['Opportunities']['subpanel_setup']['history']['sort_order'] = 'desc';
$layout_defs['Opportunities']['subpanel_setup']['history']['sort_by'] = 'date_modified';
$layout_defs['Opportunities']['subpanel_setup']['contacts']['sort_order'] = 'asc';
$layout_defs['Opportunities']['subpanel_setup']['contacts']['sort_by'] = 'last_name, first_name';

// Hide SinergiaCRM unused subpanels
unset($layout_defs['Opportunities']['subpanel_setup']['project']);
unset($layout_defs['Opportunities']['subpanel_setup']['leads']);
