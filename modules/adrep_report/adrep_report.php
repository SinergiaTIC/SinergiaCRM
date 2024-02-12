<?php
require_once('modules/adrep_chart/adrep_chart.php');
require_once('modules/adrep_report/adrep_report_sugar.php');
require_once('modules/adrep_report/vendor/autoload.php');

 use HeadlessChromium\BrowserFactory;

class adrep_report extends adrep_report_sugar
{
	function __construct()
	{
		global $timedate;

		parent::__construct();

		// Some defaults - to make things faster later
		$this->dtz = new DateTimeZone('UTC');
		$this->date_format = $timedate->get_date_format();
		$this->time_format = $timedate->get_time_format();
		$this->date_time_format = $timedate->get_date_time_format();
		if ($_REQUEST['action'] == 'EditView' && $_REQUEST['module'] == 'adrep_report')
			$this->_BuildTemplateList();
	}

	function retrieve($id = -1, $encode = true, $deleted = true)
	{
		global $current_user, $timedate;

		$retvar = parent::retrieve($id, $encode, $deleted);
		if ($_REQUEST['action'] == 'EditView' && $_REQUEST['module'] == 'adrep_report')
			$this->_BuildTemplateList();
		// Decode query
		$this->query = htmlspecialchars_decode($this->query,ENT_QUOTES);
		$this->custom_css = htmlspecialchars_decode($this->custom_css,ENT_QUOTES);
		// Some defaults - to make things faster later
		$this->module_names = array();
		$this->dl_name = preg_replace('/[^a-zA-Z0-9]/','_',$this->name) . date('_Ymd_Hi');

		// Temp filename - Must be outside web root for security reasons
		$this->base_name = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($this->id . $current_user->id);

		return $retvar;
	}

	public function _BuildTemplateList()
	{
		global $app_list_strings;
		$files_to_find = array('header','row','footer','css');
		foreach ($files_to_find as $type)
		{
			$ext = ($type == 'css') ? 'css' : 'html';

			foreach(glob("custom/modules/adrep_report/tpls/$type/*.$ext") as $filename){
				$file=end(explode(DIRECTORY_SEPARATOR,str_replace(".$ext","",$filename)));
				if(!array_key_exists($file,$app_list_strings['adrep_css_file_list']))
					$app_list_strings['adrep_css_file_list'][$file]=$file;
			}
			foreach(glob("modules/adrep_report/tpls/$type/*.$ext") as $filename){
				$file=end(explode(DIRECTORY_SEPARATOR,str_replace(".$ext","",$filename)));
				if(!array_key_exists($file,$app_list_strings['adrep_css_file_list']))
					$app_list_strings['adrep_css_file_list'][$file]=$file;
			}

		}

		ksort($app_list_strings['adrep_css_file_list']);
	}

    function create_new_list_query($order_by, $where, $filter = array(), $params = array(), $show_deleted = 0, $join_type = '', $return_array = false, $parentbean = null, $singleSelect = false, $ifListForExport = false)
	{
        global $db, $current_user;

        $ret_array = parent::create_new_list_query($order_by, $where, $filter, $params, $show_deleted, $join_type, $return_array, $parentbean, $singleSelect);

		if (is_admin($current_user))	// Admin users can see all reports
			return $ret_array;

		// Get user's roles
		$roles = array();
		$query = "SELECT role_id FROM acl_roles_users WHERE user_id='$current_user->id' AND deleted=0";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
			$roles[] = $row['role_id'];
		$roles_joined = join("','",$roles);

		// Add filtering for permission
		$ret_array['from'] .= " LEFT JOIN adrep_role ON adrep_report.id=adrep_role.report_id AND adrep_role.deleted=0";
		$ret_array['where'] .= " AND ( adrep_report.permission='Public' OR
									( adrep_report.permission='Private' AND adrep_report.assigned_user_id='$current_user->id') OR
									( adrep_report.permission='Roles' AND adrep_role.role_id IN ('$roles_joined') AND adrep_role.deleted=0 AND adrep_role.allowed_flag=1) )";

		$ret_array['order_by'] = "GROUP BY adrep_report.id " . $ret_array['order_by'];

        return $ret_array;
    }

	function get_parameter_names()
	{
		$this->parameter_names = array();

		$parts = explode('{$',$this->query);
		unset($parts[0]);	// First part never cantains a parameter

		foreach($parts as $part)
		{
			$end = strpos($part,'}');
			if ($end>0)
				$this->parameter_names[] = substr($part,0,$end);
		}

		return $this->parameter_names;
	}

	function get_parameter_ids($joined=false)
	{
		$this->parameter_ids = array();

		$parameters = $this->get_parameter_names();

		foreach($parameters as $name)
			$this->parameter_ids[] = md5($this->id . $name);

		if ($joined)
			return "'" . join("','",$this->parameter_ids) . "'";
		else
			return $this->parameter_ids;
	}

	function retrieve_parameters()
	{
		require_once "modules/adrep_parameter/adrep_parameter.php";

		global $db;

		$this->parameters = array();

		$query = "SELECT id,name FROM adrep_parameter WHERE id IN (" .
					$this->get_parameter_ids(true) . ") AND deleted=0";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			$tmp = new adrep_parameter();
			$tmp->retrieve($row['id']);
			$this->parameters[$row['name']] = $tmp;
		}

		return (count($this->parameters)>0);
	}

	function get_columns_from_query()	// only works in MySQL!!
	{
		global $db;

		$fields = array();

		if ($db->variant != 'mysqli')	// Make sure we are using MySQL
			return $fields;

		$types = array(
						1=>'Number',
						5=>'Currency',
						10=>'Date',
						12=>'DateTime',
						252=>'Text',
						253=>'Text',
						254=>'ModuleLink',
					);

		$i = 0;
		$res = $db->query($this->query);
		while ($i < mysqli_num_fields($res))
		{
			$meta = mysqli_fetch_field_direct($res, $i);
			if (!$meta)
				continue;

			$fields[$meta->name] = array(
									'id' => md5($this->id.$meta->name),
									'name' => $meta->name,
									'column_label' => $meta->name,
									'type' => $types[$meta->type],
									//'type_num' => $meta->type,
									'decimals' => ($meta->type < 10) ? 2 : 0,
									'priority' => $i+1,
								);
			$i++;
		}

		return $fields;
	}

	function retrieve_columns()
	{
		require_once "modules/adrep_column/adrep_column.php";

		global $db;

		$this->column_names = array();
		$this->columns = array();

		$query = "SELECT id,name FROM adrep_column WHERE report_id='$this->id' AND deleted=0 ORDER BY priority, column_label";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			$tmp = new adrep_column();
			$tmp->retrieve($row['id']);
			$this->columns[$row['id']] = $tmp;
			$this->column_names[$row['id']] = $tmp->name;
		}

		return (count($this->columns)>0);
	}

	function fill_query($vars)
	{
		global $timedate, $current_user;

		$query = $this->query;
		$keys = $this->get_parameter_names();

		foreach ($keys as $key)
			if (isset($vars[$key]))
			{
				// Convert dates to correct format and timezone
				if ($this->parameters[$key]->type == 'DateTime')
				{
					$vars[$key] = $timedate->to_db($vars[$key]);

					// Add hours and minutes if defined
					if (isset($vars["hour_$key"]))
						$vars[$key] = date('Y-m-d H:i:s',strtotime($vars[$key]) +
												($vars["hour_$key"]%12) * 3600 + // % is for 12am
												$vars["min_$key"] * 60);

					// Add 12 hours if PM
					if (isset($vars["ampm_$key"]) && strtolower($vars["ampm_$key"]) == 'pm')
						$vars[$key] = date('Y-m-d H:i:s',strtotime($vars[$key]) +
												12 * 3600);
				}
				elseif ($this->parameters[$key]->type == 'Date')
					$vars[$key] = $timedate->to_db_date($vars[$key],false);
				elseif ($this->parameters[$key]->type == 'CurrentUserID')
					$vars[$key] = $current_user->id;

				$query = str_replace("{\$$key}",$vars[$key],$query);
			}

		return $query;
	}

	function get_record_name($id,$module)
	{
		/* TODO
		 * Retrieving the beans is an easy and convenient method,
		 * but quering the DB directly will require much fewer queries and be much faster!
		 */

		global $beanList, $beanFiles;

		if (isset($this->record_names[$module][$id]))
			return $this->record_names[$module][$id];

		$class_name = $beanList[$module];
		$beanFile = $beanFiles[$class_name];

		if (!empty($class_name) && file_exists($beanFile))
		{
			require_once $beanFiles[$class_name];
			$bean = new $class_name();
			$bean->retrieve($id);

			if (isset($bean->full_name))
				$this->record_names[$module][$id] = $bean->full_name;
			elseif (isset($bean->name))
				$this->record_names[$module][$id] = $bean->name;
			else
				$this->record_names[$module][$id] = $id;
		}
		else
			$this->record_names[$module][$id] = $id;

		return $this->record_names[$module][$id];
	}

	function format_value($value,$type,$extra)
	{
		global $timedate, $current_user;

		if ($type == 'Number')
			$value = number_format($value,$extra);
		elseif ($type == 'Currency')
			$value = number_format($value,$extra);	// TODO - Improve this!
		elseif ($type == 'DateTime' && !empty($value) && $value != '&nbsp;')
		{
			$dt = new DateTime($value,$this->dtz);
			$value = $timedate->asUser($dt);
		}
		elseif ($type == 'Date' && !empty($value) && $value != '&nbsp;')
			$value = date($this->date_format,strtotime($value));
		elseif ($type == 'ModuleLink')
			$value = $this->get_record_name($value,$extra);
		elseif ($type == 'CurrentUserID')
			$value = $this->get_record_name($current_user->id,"Users");

		return $value;
	}

	function clear_cache()
	{
		global $current_user, $db;

		$query = "DELETE FROM adrep_cache WHERE report_id='$this->id' AND assigned_user_id='$current_user->id'";
		$db->query($query);
	}

	function save_to_cache($row,$priority,$row_type='detail')
	{
		global $current_user, $db, $timedate;

		// Format values
		$new_row = array();
		if ($row_type == 'detail')
		{
			foreach ($this->columns as $column)
			{
				if ($column->priority > 0) //isset($row[$column->name]))
					if ($column->type == 'ModuleLink')
						$new_row[$column->name] = $this->format_value($row[$column->name],
													$column->type, $column->linked_module_name);
					else
						$new_row[$column->name] = $this->format_value($row[$column->name],
													$column->type, $column->decimals);
			}
		}
		elseif ($row_type == 'parameters')
		{
			foreach ($this->parameters as $parameter)
			if (isset($row[$parameter->name]))
			{
				if ($parameter->type == 'ModuleLink')
					$new_row[] = array(
								'label' => $parameter->parameter_label,
								'value' => $row["name_$parameter->name"],
							);
				elseif ($parameter->type == 'CurrentUserID')
					$new_row[] = array(
								'label' => $parameter->parameter_label,
								'value' => $this->get_record_name($current_user->id,"Users"),
							);
				else
					$new_row[] = array(
								'label' => $parameter->parameter_label,
								'value' => $row[$parameter->name],
							);
			}
		}
		else	// (sub)total row
		{
			foreach ($this->columns as $column)
				if ($column->priority > 0) //isset($row[$column->name]))
				{
					if ($column->total_type == 'blank')
						$new_row[$column->name] = '&nbsp;';
					elseif ($column->total_type == 'sum')
						$new_row[$column->name] = $row[$column->name]['sum'];
					elseif ($column->total_type == 'cnt')
						$new_row[$column->name] = $row[$column->name]['cnt'];
					elseif ($column->total_type == 'avg')
						$new_row[$column->name] = $row[$column->name]['avg'];
					elseif ($column->total_type == 'value')
						$new_row[$column->name] = $row[$column->name]['value'];
					elseif ($column->total_type == 'subtotal1')
						$new_row[$column->name] = $row[$column->name]['value'];
					elseif ($column->total_type == 'subtotal2')
						$new_row[$column->name] = $row[$column->name]['value'];
					else
						$new_row[$column->name] = '&bsp;';

					if ($column->type == 'ModuleLink')
						$new_row[$column->name] = $this->format_value($new_row[$column->name],
													$column->type, $column->linked_module_name);
					else
						$new_row[$column->name] = $this->format_value($new_row[$column->name],
													$column->type, $column->decimals);
				}
		}

		$id = md5($this->id . $current_user->id . $priority);
		$description = base64_encode(var_export($new_row,true));
		$date_entered = gmdate('Y-m-d H:i:s');

		$query = "INSERT IGNORE INTO adrep_cache
						(id,report_id,assigned_user_id,priority,description,row_type,date_entered)
				  VALUES ('$id','$this->id','$current_user->id','$priority',\"$description\",
							 '$row_type','$date_entered')";
		$db->query($query);
	}

	function get_subtotal_column($level=1)
	{
		$column_name = '';

		foreach ($this->columns as $id => $column)
			if ($column->total_type == "subtotal$level")
				$column_name = $column->name;

		return ($column_name);
	}

	function run_query($vars)
	{
		global $current_user, $db;

		// Clear the cache
		$this->clear_cache();

		// Get subtotal columns (if any)
		$subt1column = $this->get_subtotal_column(1);
		$subt2column = $this->get_subtotal_column(2);

		$totals = array();
		$subtotals1 = array();
		$subtotals2 = array();
		$previous = array();

		// Now run the new query
		$cnt = 0;
		$query = $this->fill_query($vars);

		// Save parameters to cache
		$this->save_to_cache($vars,0,'parameters');

		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			$subt2saved = false;
			$cnt++;

			// Inner group changed
			if (!empty($previous) && !empty($subt2column) &&
					$previous[$subt2column] != $row[$subt2column])
			{
				// Save subtotals
				$this->save_to_cache($subtotals2,$cnt,'subtotal2');
				$cnt++;

				// Clear subtotals
				$subtotals2 = array();

				$subt2saved = true;
			}

			// Outer group changed
			if (!empty($previous) && !empty($subt1column) &&
					$previous[$subt1column] != $row[$subt1column])
			{
				// Save subtotals
				if (!$subt2saved && !empty($subt2column))
				{
					$this->save_to_cache($subtotals2,$cnt,'subtotal2');
					$cnt++;
				}
				$this->save_to_cache($subtotals1,$cnt,'subtotal1');
				$cnt++;

				// Clear subtotals
				$subtotals2 = array();
				$subtotals1 = array();
			}

			// Add to totals
			foreach($row as $key => $value)
			{
				if ($key == $subt2column || $key == $subt1column)
					$totals[$key]['value'] = '';
				else
					$totals[$key]['value'] = $value;
				$totals[$key]['sum'] += $value;
				$totals[$key]['cnt']++;
				$totals[$key]['avg'] = ($totals[$key]['sum'] / $totals[$key]['cnt']);

				if ($key == $subt2column)
					$subtotals1[$key]['value'] = '';
				else
					$subtotals1[$key]['value'] = $value;
				$subtotals1[$key]['sum'] += $value;
				$subtotals1[$key]['cnt']++;
				$subtotals1[$key]['avg'] = ($subtotals1[$key]['sum'] / $subtotals1[$key]['cnt']);

				$subtotals2[$key]['value'] = $value;
				$subtotals2[$key]['sum'] += $value;
				$subtotals2[$key]['cnt']++;
				$subtotals2[$key]['avg'] = ($subtotals2[$key]['sum'] / $subtotals2[$key]['cnt']);
			}

			// Save as previous row
			$previous = $row;

			// Save the detail row to the cache
			$this->save_to_cache($row,$cnt,'detail');
		}

		// Save subtotals (if any)
		if (!empty($subt2column))
		{
			$cnt++;
			$this->save_to_cache($subtotals2,$cnt,'subtotal2');
		}
		if (!empty($subt1column))
		{
			$cnt++;
			$this->save_to_cache($subtotals1,$cnt,'subtotal1');
		}

		// Save grand totals
		$cnt++;
		$this->save_to_cache($totals,$cnt,'totals');

		return $cnt;
	}

	function run_report($vars,$format='html')
	{
		global $current_user;

		$this->retrieve_parameters();
		$this->retrieve_columns();

		// Insert current_user and missing defaults in vars
		foreach ($this->parameters as $param)
		{
			if ($param->type == 'CurrentUserID')
				//$vars[$param->name] = $current_user->id;
				$vars[$param->name] = $_SESSION['authenticated_id'];	// Better for Portal
			elseif (!isset($vars[$param->name]))
				$vars[$param->name] = $param->default_value;
		}

		$cnt = $this->run_query($vars);

		return $cnt;
	}

	function generate_html($page=0, $rpp=50,$outputmode='html',$overridetemplate=null)	// Current page and rows per page
	{
		global $db, $current_user, $sugar_config,$app_list_strings;
		$this->_BuildTemplateList();
		// Count total number of rows
		$query = "SELECT count(*) cnt FROM adrep_cache WHERE report_id='$this->id' and assigned_user_id='$current_user->id'";
		$res = $db->query($query);
		$row = $db->fetchByAssoc($res);
		$num_rows = $row['cnt'];

		//Allows a local override of the template for ie. runing the same report for a dashlet but with differing output style.
		if(array_key_exists($overridetemplate,$app_list_strings['adrep_css_file_list']))
		{
				$passtotemplate=true;
				$cssfile=$overridetemplate;
		}else {
			$passtotemplate=false;
			$cssfile=$this->css_file;
		}

		// Calculate start and end values for query
		if (empty($page))	// zero or null means all rows
		{
			$start = 1;
			$end = $num_rows;
			$rpp = $num_rows;
		}
		else
		{
			$end = $page*$rpp;
			$start = $end - $rpp + 1;
			if (empty($rpp))
				$rpp = 50;
		}


		// Find Templates
		$tpls = array();
		$files_to_find = array('header','row','footer','css');
		foreach ($files_to_find as $type)
		{
			$ext = ($type == 'css') ? 'css' : 'html';

			if (file_exists("custom/modules/adrep_report/tpls/$type/$cssfile.$ext"))
				$tpls[$type] = "custom/modules/adrep_report/tpls/$type/$cssfile.$ext";
			elseif (file_exists("modules/adrep_report/tpls/$type/$cssfile.$ext"))
				$tpls[$type] = "modules/adrep_report/tpls/$type/$cssfile.$ext";
			else
				$tpls[$type] = "modules/adrep_report/tpls/$type/Basic.$ext";
		}

		// Basic variables for Smarty
		$smarty = new Sugar_Smarty();
		$smarty->assign('report_id',$this->id);
		$smarty->assign('title',$this->name);
		$smarty->assign('stylesheet',file_get_contents($tpls['css']));
		$smarty->assign('custom_css',$this->custom_css);
		$smarty->assign('created_by',$current_user->full_name);
		$smarty->assign('date_created',date($this->date_time_format));
		$smarty->assign('action',$_REQUEST['action']);
		if (file_exists("custom/themes/default/images/company_logo.png"))
			$smarty->assign('logo_url',$sugar_config['site_url'] .
						"/custom/themes/default/images/company_logo.png");

		// Values for pagination
		$smarty->assign('min2',$page-2);
		$smarty->assign('min1',$page-1);
		$smarty->assign('page',$page);
		$smarty->assign('plus1',$page+1);
		$smarty->assign('plus2',$page+2);
		$smarty->assign('page',$page);
		$smarty->assign('rpp',$rpp);
		$smarty->assign('num_rows',$num_rows);
		$smarty->assign('num_pages',ceil($num_rows/$rpp));
		$smarty->assign('record',$this->id);
		$smarty->assign('sugar_body_only',$_REQUEST['sugar_body_only']);
		$smarty->assign('ts',time());

		if($passtotemplate)
			$smarty->assign('template','&template='.$cssfile);
		// Render the parameters for display
		$query = "SELECT id,row_type,description FROM adrep_cache WHERE report_id='$this->id' and assigned_user_id='$current_user->id' AND priority=0 AND row_type = 'parameters'";
		$res = $db->query($query);
		$row = $db->fetchByAssoc($res);
		$description = base64_decode($row['description']);
		eval ("\$parameters = $description;");
		$smarty->assign('parameters',$parameters);

		// Charts
		$charts = array();
		$chart_cnt = 0;
		$query = "SELECT id FROM adrep_chart WHERE report_id='$this->id' AND deleted=0 ORDER by date_entered";
		$res = $db->query($query);


		while ($row = $db->fetchByAssoc($res))
		{
			$chart = new adrep_chart();
			$chart->retrieve($row['id']);

			if($this->chart_type=='google')
			{

					$contents = $chart->draw_google_chart(null,"$this->base_name.chart_$chart_cnt.png");
					$charts[] = $contents;

			}else{
				$contents = file_get_contents($chart->draw_chart(null,"$this->base_name.chart_$chart_cnt.png"));
				$base64 = base64_encode($contents);
				unlink("$this->base_name.chart_$chart_cnt.png");
				$charts[] = "data:image/png;base64, $base64";
		 }
			$chart_cnt++;
		}
		$smarty->assign('charttype',$this->chart_type);
		$smarty->assign('chartcount',$chart_cnt);
		$smarty->assign('charts',$charts);

		// Render the header
		file_put_contents("$this->base_name.html",$smarty->fetch($tpls['header']));


		// Query for fetching the detail rows and fetch first row to validate columns
		// 20181128
		$query = "SELECT id,row_type,description FROM adrep_cache WHERE report_id='$this->id' and assigned_user_id='$current_user->id' AND priority>=$start AND priority<=$end ORDER BY priority";
		$res = $db->query($query);
		$row = $db->fetchByAssoc($res);
		$description = base64_decode($row['description']);
		eval ("\$old_row = $description;");
		// 20181128

		// Render the column headings
		$smarty->assign('row_class',"header");
		// TMP hack
		$new_row = array();
		$col_cnt = 0;
		foreach ($this->columns as $column_key => $column)
			if($column->priority > 0 && isset($old_row[$column->name]))	// 20181128
			{
				$col_cnt++;
				$new_row[] = array('class'=>$column->type,
									'value'=>$column->column_label,
									'width'=>$column->width,
									'name'=>$column->name);
			}
			else
				unset($this->columns[$column_key]);	// Column is no longer in the query // 20181128
		$smarty->assign('row',$new_row);
		file_put_contents("$this->base_name.html",$smarty->fetch($tpls['row']),FILE_APPEND);

		// Fetch and render the rows
		$cnt = 0;
		while (!empty($row))
		{
			$cnt++;
			$smarty->assign('rowcount',$cnt);

			if ($row['row_type'] == 'detail')
				$row_class = (($cnt%2)==0) ? 'even' : 'odd';
			else
				$row_class = $row['row_type'];
			$smarty->assign('row_class',$row_class);

			$description = base64_decode($row['description']);
			eval ("\$old_row = $description;");

			$new_row = array();
			foreach ($this->columns as $column)
				if($column->priority > 0 && isset($old_row[$column->name]))	// 20181128
					$new_row[] = array('class'=>$column->type, 'value'=>$old_row[$column->name], 'width'=>$column->width,'name'=>$column->name);
			$smarty->assign('row',$new_row);

			/* echo "<pre>\n";
			print_r($description);
			print_r($old_row);
			print_r($new_row);
			echo "</pre>\n"; */

			file_put_contents("$this->base_name.html",$smarty->fetch($tpls['row']),FILE_APPEND);

			$row = $db->fetchByAssoc($res);
		}

		// Render the footer
		file_put_contents("$this->base_name.html",$smarty->fetch($tpls['footer']),FILE_APPEND);

		return "$this->base_name.html";

	}

	function generate_pdf($dummy=0)
	{
		global $sugar_config;
		// Generate HTML output
		$html_file = $this->generate_html(0);
		$pdf_file = "$this->base_name.pdf";

		// Chrome Headless
		if(isset($sugar_config['adrep_pdf_driver']) &&
			$sugar_config['adrep_pdf_driver'] == 'googlechrome' &&
			isset($sugar_config['adrep_pdf_chrome_location']) &&
			file_exists($sugar_config['adrep_pdf_chrome_location']))
		{
			$browserFactory = new BrowserFactory($sugar_config['adrep_pdf_chrome_location']);

    		// starts headless chrome
    		$browser = $browserFactory->createBrowser(['noSandbox'=>true]);

    		// creates a new page and navigate to an url
    		$page = $browser->createPage();

        	$page->navigate('file://'.$html_file)->waitForNavigation();
			$options = [
					'printBackground' => true,   // default to false
					//  'displayHeaderFooter' => true, // default to false
					'preferCSSPageSize' => true, // default to false ( reads parameters directly from @page )
					//'marginTop' => 0.0, // defaults to ~0.4 (must be float, value in inches)
					//  'marginBottom' => 1.4, // defaults to ~0.4 (must be float, value in inches)
					//  'marginLeft' => 0.4, // defaults to ~0.4 (must be float, value in inches)
					//  'marginRight' => 0.4, // defaults to ~0.4 (must be float, value in inches)
					'paperWidth' => 8.3, // defaults to 8.5 (must be float, value in inches)
					'paperHeight' => 11.7, // defaults to 8.5 (must be float, value in inches)
					// 'headerTemplate' => "<div>foo</div>", // see details bellow
					//'footerTemplate' => "<div>foo</div>", // see details bellow
					//'scale' => 1.2, // defaults to 1
				];

    		// pdf
    		$page->pdf($options)->saveToFile($pdf_file);
    		// bye
    		$browser->close();
		}
		else	// mpdf
		{
			// Convert to PDF
			require_once "modules/AOS_PDF_Templates/PDF_Lib/mpdf.php";

			$mpdf = new mPDF();
			$mpdf->WriteHTML(file_get_contents($html_file));
			$mpdf->Output($pdf_file,"F");
		}

		unlink($html_file);

		return $pdf_file;
	}

	function generate_csv($dummy=0)
	{
		global $db, $current_user, $sugar_config;

		// Open temp file for writing
		$fp = fopen("$this->base_name.csv","w");

		// Render the column headings
		$headers = array();
		$col_cnt = 0;
		foreach ($this->columns as $column)
			if($column->priority > 0)
			{
				$col_cnt++;
				$headers[] = $column->column_label;
			}
		fputcsv($fp,$headers);

		// Fetch and render the rows
		$cnt = 0;
		$query = "SELECT id,row_type,description FROM adrep_cache WHERE report_id='$this->id' and assigned_user_id='$current_user->id' AND priority>=1 AND row_type NOT IN ('chart','subtotal1','subtotal2') ORDER BY priority";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
		{
			$cnt++;

			// Decode the original row
			$description = base64_decode($row['description']);
			eval ("\$old_row = $description;");

			// We have to unformat Number and Currency columns
			$new_row = array();
			foreach ($this->columns as $column)
				if($column->priority > 0)
					if ($column->type == 'Number' ||$column->type == 'Currency')
						$new_row[] = preg_replace('/[^0-9.-]/','',$old_row[$column->name]);
					else
						$new_row[] = $old_row[$column->name];

			fputcsv($fp,$new_row);
		}

		// Close the file
		fclose($fp);

		return "$this->base_name.csv";
	}

	function gen_dates()
	{
		$dom = date('d');
		$doy = date('z');
		$year = date('Y');
		$month = date('m');
		$last_year = $year-1;
		$next_year = $year+1;
		$dim = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		if ($month == 1)
			$dim_last = 31; //Always December
		else
			$dim_last = cal_days_in_month(CAL_GREGORIAN, $month-1, $year);
		if ($month == 12)
			$dim_next = 31; //Always January
		else
			$dim_next = cal_days_in_month(CAL_GREGORIAN, $month+1, $year);


		// Set current week start to 00:00:00 of monday THIS week.
		$dateinfo=getdate();
		if($dateinfo['wday']==1)
			$currentweekstart=strtotime('today');
		else
			$currentweekstart = strtotime('last monday');

		$dates = array(
			'Yesterday'			=>	date($this->date_format,time()-86400),
			'Today'				=>	date($this->date_format),
			'Tomorrow'			=>	date($this->date_format,time()+86400),
			'1st of last Month'	=>	date($this->date_format,time() - ($dom-1+$dim_last)*86400),
			'End of last Month'	=>	date($this->date_format,time() - ($dom)*86400),
			'1st of this Month'	=>	date($this->date_format,time() - ($dom-1)*86400),
			'End of this Month'	=>	date($this->date_format,time() + ($dim-$dom)*86400),
			'1st of next Month'	=>	date($this->date_format,time() + ($dim-$dom+1)*86400),
			'End of next Month'	=>	date($this->date_format,time() + ($dim-$dom+$dim_next)*86400),
			'1st of last Year'	=>	date($this->date_format,strtotime("$last_year/01/01")),
			'End of last Year'	=>	date($this->date_format,strtotime("$last_year/12/31")),
			'1st of this Year'	=>	date($this->date_format,strtotime("$year/01/01")),
			'End of this Year'	=>	date($this->date_format,strtotime("$year/12/31")),
			'1st of next Year'	=>	date($this->date_format,strtotime("$next_year/01/01")),
			'End of next Year'	=>	date($this->date_format,strtotime("$next_year/12/31")),
			'Last Monday' => date($this->date_format, strtotime('last monday', $currentweekstart)),
			'End of Prev Sunday' => date($this->date_format,strtotime('-1 second', $currentweekstart))
		);

		// Add your own custom dates into this file
		if (file_exists("custom/modules/adrep_report/gen_dates.php"))
			require "custom/modules/adrep_report/gen_dates.php";

		return $dates;
	}

	function gen_date_ranges()
	{
		// Last UTC midnight as base ts
		$ts = time();
		$mn = $ts - ($ts % 86400);

		// Number of days in the month
		$dims = array(30,31,31,28+date('L'),31,30,31,30,31,31,30,31,30,31,31); // Nov,Dec...Dec,Jan
		$mth  = date('n')*1+1;	// This month number
		$dim  = $dims[$mth];	// Days in this month
		$dimp = $dims[$mth-1];	// Days in last month
		$dimpp = $dims[$mth-2];	// Days in month before last month
		$dimn = $dims[$mth+1];	// Days in next month


		// Days to add for each date of range
		$days = array
			(
				'Yesterday'		=> array(-1,0),
				'Today'			=> array(0,1),
				'Tomorrow'		=> array(1,2),
				'Last Month'	=> array(1-date('j') - $dimp,1-date('j')),
				'This Month'	=> array(1-date('j'),$dim-date('j')+1),
				'Next Month'	=> array(date('j'),date('j')+$dimn+1),
				'26th to 25th'  => array(1-date('j')-$dimp-$dimpp+25,0-date('j')-$dimp+25),
				'Last Year'		=> array(0-date('z')-365-date('L'),0-date('z')),
				'This Year'		=> array(0-date('z'),365+date('L')-date('z')),
				'Next Year'		=> array(365-date('z')+date('L'),
											365*2+date('L')-date('z')), // FIXME may be 1 day out
				'Month to Date'	=> array(1-date('j'),1),
				'Year to Date'	=> array(0-date('z'),1)
			);

		// String representationd of date and time in user's timezone
		// Start dates are meant to be used with greater or equal than	eg. start >= {$dtart}
		// End dates are meant to be used with less than				eg.   end < {$end}
		$date_ranges = array();
		foreach ($days as $key => $values)
		{
			$date_ranges[$key] = array(
							'startDate'		=> date($this->date_format,$mn + $values[0]*86400),
							'endDate'		=> date($this->date_format,$mn + $values[1]*86400),
							'startDateTime' => date($this->date_format,$ts + $values[0]*86400) .
													" " . gmdate($this->time_format,0),
							'endDateTime'	=> date($this->date_format,$ts + $values[1]*86400) .
													" " . gmdate($this->time_format,0),
									);
		}

		// Add your own custom date ranges into this file
		if (file_exists("custom/modules/adrep_report/gen_date_ranges.php"))
			require "custom/modules/adrep_report/gen_date_ranges.php";

		return($date_ranges);
	}
}
