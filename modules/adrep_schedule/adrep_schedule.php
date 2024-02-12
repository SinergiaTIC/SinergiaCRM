<?php
class adrep_schedule extends Basic
{
    public $new_schema = true;
    public $module_dir = 'adrep_schedule';
    public $object_name = 'adrep_schedule';
    public $table_name = 'adrep_schedule';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $query;
    public $email_column;
    public $adrep_report_id_c;
    public $report_name;
    public $format;
    public $dow;
    public $tod;
    public $active_flag;

	function __construct()
	{
		global $current_user;

		parent::__construct();

		// Make sure we have current_user if running from cron
		if (empty($current_user->fetched_row['id']))
			$current_user->retrieve('1');
	}

    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

	private function time24()
	{
		global $timedate;

		// Get timestamp
		$now_ts = time();

		// Round down last 5 minute interval (300 seconds)
		$now_ts -= ($now_ts % 300);

		// Convert to 24hour time
		$time_db = date('H:i',$now_ts);

		return $time_db;
	}

	function run_reports()
	{
		global $current_user, $timedate, $db;

		$lastday=date('d',strtotime('last day of this month'));
		$daytoday=date('d');

		$domsql='( dom like "%^'.$daytoday.'^%" or dom like "^All^" ';
		if($lastday==$daytoday)
			$domsql.=' or dom like "%^Last^%" )';

		// Fetch reprost that need to be run
		$cnt = 0;
		$time24 = $this->time24();
		$dow = date('w');
		$query = "SELECT id FROM adrep_schedule WHERE active_flag=1 AND (dow=$dow OR dow=9) AND $domsql AND tod='$time24' ORDER BY date_entered";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			$retval = $this->run_report($row['id']);
			$cnt += $retval;
		}

		return $cnt;
	}

	function run_report($id)
	{
		require_once "modules/adrep_report/adrep_report.php";
		require_once "modules/Users/User.php";
		require_once "include/SugarPHPMailer.php";

		global $current_user, $db;

		// Retrieve the schedule
		$cnt = 0;
		$focus = new adrep_schedule();
		$focus->retrieve($id);
		$focus->query = html_entity_decode($focus->query,ENT_QUOTES);

		// Retrieve the From user
		$from = new User();
		$from->retrieve($focus->assigned_user_id);

		// And now run the report for every row
		$res = $db->query($focus->query);
		while ($row = $db->fetchByAssoc($res))
		{
			// Retrieve the report
			$report = new adrep_report();
			$report->retrieve($focus->report_id);
			$report->retrieve_parameters();

			// And run it
			$report->run_report($row);
			$method = "generate_$focus->format";
			if (method_exists($report,$method))
				$file = $report->$method();
			else
				$file = $report->generate_html();

			// Email body
			$body = html_entity_decode($focus->description,ENT_QUOTES);
			foreach ($row as $key => $value)
				$body = str_replace("{\$$key}",$value,$body);

			// Set up mailer
			$mail = new SugarPHPMailer();
			$mail->setMailer();
			$mail->From = $from->email1;
			$mail->FromName = $from->full_name;
			$mail->isHTML(true);
			$mail->Subject = $focus->name;
			if ($focus->format == 'embed')
				$mail->Body = file_get_contents($file);
			else
				$mail->Body = "<html><body><div>\n" . nl2br($body) . "\n</div><body></html>\n";
			$mail->AltBody = "$body";

			// Attach the report
			if ($focus->format != 'embed')
				$mail->addAttachment($file,"$report->dl_name.$focus->format");

			// Recipients
			$to = explode(",",$row[$focus->email_column]);
			foreach ($to as $address)
				$mail->addAddress(trim($address));

			// And send it
			if ($mail->send())
				$cnt++;

			// Clean up
			unlink($file);
		}

		return $cnt;
	}
}
