<?php
$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);

$vars = array('from_date_time'=>'2015-01-01','to_date_time'=>'2018-12-31');

$cnt = $focus->run_report($vars);

echo "<script type='text/javascript'>
	window.open('index.php?module=adrep_report&action=ViewHTML&record=$focus->id&page=1&rpp=50&sugar_body_only=yes');
</script>\n";
