<?php
require_once('modules/adrep_chart/adrep_chart_sugar.php');

class adrep_chart extends adrep_chart_sugar
{
	function __construct()
	{
		parent::__construct();

		// Fix dropdown and defaults
		if ($_REQUEST['action'] == 'EditView' && $_REQUEST['module'] == 'adrep_chart' && empty($_REQUEST['record']))
		{
			$this->get_dropdown($_REQUEST['adrep_report_id']);

			$_REQUEST['report_id'] = $_REQUEST['adrep_report_id'];
			$_REQUEST['report_name'] = $_REQUEST['adrep_report_name'];
		}
	}

	private function get_dropdown($report_id)
	{
		global $db, $app_list_strings;

		$app_list_strings['adrep_column_list'] = array(''=>'');

		$query = "SELECT name,column_label FROM adrep_column WHERE report_id='$report_id' AND deleted=0 ORDER BY column_label";
		$res = $db->query($query);
		while ($row = $db->fetchByAssoc($res))
			$app_list_strings['adrep_column_list'][$row['name']] = $row['column_label'];

		return count($app_list_strings['adrep_column_list']);
	}

	function retrieve($id = -1, $encode = true, $deleted = true)
	{
		$retval = parent::retrieve($id, $encode, $deleted);

		if ($_REQUEST['action'] == 'EditView' && $_REQUEST['module'] == 'adrep_chart')
			$this->get_dropdown($this->report_id);

		return $retval;
	}

	function get_data()
	{
		global $db;

		$data = array(
			'series' => array(),
			'labels' => array(),
			'title' => $this->name,
			'x_label' => $this->x_label,
			'y_label' => $this->y_label,
			);

		$outer_totals = array();
		$inner_totals = array();

		$query = "SELECT description FROM adrep_cache WHERE report_id='$this->report_id' AND row_type='detail' AND deleted=0 ORDER BY priority";
		$res = $db->query($query);
		while($row = $db->fetchByAssoc($res))
		{
			$description = base64_decode($row['description']);
			eval ("\$old_row = $description;");

			// We have to unformat Number and Currency columns
			$value = preg_replace('/[^0-9.]/','',$old_row[$this->value_field]);

			$outer = $old_row[$this->group1_field];
			if (!empty($this->group2_field))
				$inner = $old_row[$this->group2_field];
			else
				$inner = 0;

			$outer_totals[$outer] += $value;
			$inner_totals[$outer][$inner] += $value;
		}

		if ($this->chart_type == 'bar' || $this->chart_type == 'stacked')
		{
			$inner_values = array();
			foreach($inner_totals as $outer_key => $series)
			{
				$data['series'][$outer_key] = $series;
				foreach($series as $inner_key => $value)
					$inner_values[$inner_key] += $value;
			}

			$data['labels'] = array_keys($inner_totals);

		}
		else
		{

			$data['series'][0] = array_values($outer_totals);
			$data['labels'] = array_keys($outer_totals);
		}

		return ($data);
	}

	function draw_chart($data=null,$fname)
	{
		if (empty($data))
			$data = $this->get_data();

		$function = "draw_" . strtolower($this->chart_type);

		if(isset($this->width) && is_numeric($this->width) && $this->width>0)
			$width=$this->width;
		else
			$width=1000;

			if(isset($this->height) && is_numeric($this->height) && $this->height>0)
				$height=$this->height;
			else
				$height=300;

		if (method_exists($this,$function))
			return $this->$function($data,$fname,$width,$height);
		else
			return $this->draw_pie($data,$fname,$width,$height);
	}

	function draw_stacked($data,$fname,$width=1000,$height=300)
	{
		return $this->draw_bar($data,$fname,$width,$height,true);
	}

	function draw_bar($data,$fname,$width=1000,$height=300,$stacked=false)
	{
		$pChartDir = "modules/adrep_chart/lib/pChart/pChart";
		require_once("$pChartDir/../examples/functions.inc.php");
		require_once("$pChartDir/pDraw.php");
		require_once("$pChartDir/pData.php");
		require_once("$pChartDir/pPie.php");
		require_once("$pChartDir/pCharts.php");
		require_once("$pChartDir/pColor.php");
		require_once("$pChartDir/pColorGradient.php");
		require_once("$pChartDir/pException.php");

		/* Create the pChart object */
		$myPicture = new pChart\pDraw($width,$height);

		/* Populate the pData object */
		foreach ($data['series'] as $key => $series)
			$myPicture->myData->addPoints($series,"$key");

		//$myPicture->myData->setSerieTicks("Probe 2",4);
		if (!empty($data['y_label']))
			$myPicture->myData->setAxisName(0,$data['y_label']);
		$myPicture->myData->addPoints($data['labels'],"Labels");
		if (!empty($data['x_label']))
			$myPicture->myData->setSerieDescription("Labels",$data['x_label']);
		$myPicture->myData->setAbscissa("Labels");

		/* Draw the background */
		//$myPicture->drawFilledRectangle(0,0,$width,$height,["Color"=> new pChart\pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pChart\pColor(190,203,107)]);

		/* Overlay with a gradient */
		//$myPicture->drawGradientArea(0,0,$width,$height,DIRECTION_VERTICAL,["StartColor"=>new pChart\pColor(219,231,139,50),"EndColor"=>new pChart\pColor(1,138,68,50)]);
		//$myPicture->drawGradientArea(0,$height-20,$width,$height, DIRECTION_VERTICAL,  ["StartColor"=>ColorBlack(),"EndColor"=>new pChart\pColor(50,50,50,100)]);

		/* Add a border to the picture */
		$myPicture->drawRectangle(0,0,$width-1,$height-1,["Color"=>new pChart\pColor(0,0,0)]);

		/* Write the chart title */
		$myPicture->setFontProperties(["FontName"=>"$pChartDir/fonts/Forgotte.ttf","FontSize"=>11]);
		$myPicture->drawText($width/2,25,$data['title'],["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

		/* Create the pCharts object */
		$pCharts = new pChart\pCharts($myPicture);

		/* Draw the scale and the 1st chart */
		$myPicture->setGraphArea(45,45,$width-20,$height-45);
		$myPicture->drawFilledRectangle(45,45,$width-20,$height-45,["Color"=>new pChart\pColor(255,255,255,10),"Surrounding"=>-200]);
		if ($stacked)
			$myPicture->drawScale(["DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_ADDALL]);
		else
			$myPicture->drawScale(["DrawSubTicks"=>TRUE]);
		$myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pChart\pColor(0,0,0,10)]);
		$myPicture->setFontProperties(["FontName"=>"$pChartDir/fonts/pf_arma_five.ttf","FontSize"=>6]);

		if ($stacked)
			$pCharts->drawStackedBarChart(["DisplayValues"=>TRUE,"DisplayType"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>60]);
		else
			$pCharts->drawBarChart(["DisplayValues"=>TRUE,"DisplayType"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30]);

		/* Write the chart legend */
		$myPicture->setShadow(FALSE);
		$myPicture->setFontProperties(["FontName"=>"$pChartDir/fonts/Silkscreen.ttf","FontSize"=>8,"Color"=>ColorBlack()]);
		$myPicture->drawLegend(20,$height-12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

		/* Render the picture (choose the best way) */
		$myPicture->Render($fname);

		return $fname;
	}


	function draw_pie($data,$fname,$width=1000,$height=300)
	{
		$pChartDir = "modules/adrep_chart/lib/pChart/pChart";
		require_once("$pChartDir/../examples/functions.inc.php");
		require_once("$pChartDir/pDraw.php");
		require_once("$pChartDir/pData.php");
		require_once("$pChartDir/pPie.php");
		require_once("$pChartDir/pColor.php");
		require_once("$pChartDir/pColorGradient.php");
		require_once("$pChartDir/pException.php");

		$radius = floor((min($width,$height)-100)/2);

		/* Create the pChart object */
		$myPicture = new pChart\pDraw($width,$height);

		/* Populate the pData object */
		$myPicture->myData->addPoints($data['series'][0],"Series0");
		$myPicture->myData->setSerieDescription("Series0","Data Series 0");

		/* Define the abscissa serie */
		$myPicture->myData->addPoints($data['labels'],"Labels");
		$myPicture->myData->setAbscissa("Labels");

		/* Draw the background */
		//$myPicture->drawFilledRectangle(0,0,$width,$height,["Color"=> new pChart\pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pChart\pColor(190,203,107)]);

		/* Overlay with a gradient */
		//$myPicture->drawGradientArea(0,0,$width,$height,DIRECTION_VERTICAL,["StartColor"=>new pChart\pColor(219,231,139,50),"EndColor"=>new pChart\pColor(1,138,68,50)]);
		//$myPicture->drawGradientArea(0,$height-20,$width,$height, DIRECTION_VERTICAL,  ["StartColor"=>ColorBlack(),"EndColor"=>new pChart\pColor(50,50,50,100)]);

		/* Add a border to the picture */
		$myPicture->drawRectangle(0,0,$width-1,$height-1,["Color"=>ColorBlack()]);

		/* Write the chart title */
		$myPicture->setFontProperties(["FontName"=>"$pChartDir/fonts/Forgotte.ttf","FontSize"=>11]);
		$myPicture->drawText($width/2,25,$data['title'],["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);

		/* Set the default font properties */
		$myPicture->setFontProperties(array("FontName"=>"$pChartDir/fonts/Forgotte.ttf","FontSize"=>10,"Color"=>new pChart\pColor(80,80,80)));

		/* Enable shadow computing */
		$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>ColorBlack($Alpha=50)]);

		/* Create the pPie object */
		$PieChart = new pChart\pPie($myPicture);

		/* Draw an AA pie chart */
		$method = "draw$this->chart_type";
		if (!method_exists($PieChart,$method))
			$method = "draw2DPie";
		$PieChart->$method($width/2,$height/2,["Radius"=>$radius,"DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE,"WriteValues"=>PIE_VALUE_NATURAL,"DataGapAngle"=>0,"ValuePosition"=>PIE_VALUE_INSIDE]);

		/* Write the legend */
		$myPicture->setShadow(FALSE);
		$myPicture->setFontProperties(["FontName"=>"$pChartDir/fonts/Silkscreen.ttf","FontSize"=>8,"Color"=>ColorBlack()]);
		$PieChart->drawPieLegend(20,$height-12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

		/* Render the picture */
		$myPicture->Render($fname);

		return $fname;
	}


private function _getjsdata($data)
{
	$jsdata="['{$data['x_label']}','{$data['y_label']}'],";
		if($this->chart_type == 'bar' || $this->chart_type =='stacked')
		{
			foreach($data['series'] as $series=>$entries)
			{
					if($counter>0)
						$jsdata.=", ";
					$innercounter=0;
					$jsdata.="[ '$series', ";
					foreach($entries as $entry)
					{
						if($innercounter>0)
							$jsdata.=', ';
						$jsdata.=$entry;
						$innercounter++;
					}
					$jsdata.=" ]";
					$counter++;
			}

		}else {
			$counter=0;

			foreach($data['series'][0] as $number=>$entry)
			{
				if($counter>0)
					$jsdata.=", \n";

					$series=$data['labels'][$number];
				$jsdata.="\n[ '$series', $entry] ";
				$counter++;
			}
		}

return $jsdata;
}
	function draw_google_chart($data=null,$fname)
	{
		if (empty($data))
			$data = $this->get_data();

			$id=base64_encode($this->id);
		$chartid="AdrepJSChart_".$id;

		$variableoptions='';

		$jsdata=$this->_getjsdata($data);

			switch($this->chart_type)
			{
					case 'stacked':

						$variableoptions.="\nisStacked: true, ";
					case 'bar':
					$variableoptions='vAxis: { title: \''.$data['x_label'].'\' }, hAxis:{ title: \''.$data['y_label'].'\'},';
						$function="BarChart";
						break;
					case '3DPie':
						$variableoptions.="\nis3D: true,";
					case 'pie':
						$function="PieChart";
						break;
					case '3DRing':
						$variableoptions.="\nis3D: true,";
					case '2DRing':
						$variableoptions.="\npieHole: 0.4,";
						$function="PieChart";
						break;
					case 'line':
						$function="LineChart";
						$variableoptions='vAxis: { title: \''.$data['y_label'].'\' }, hAxis:{ title: \''.$data['x_label'].'\'},';
						break;
					default:
						$function="PieChart";
						break;
			}
$title=$data['title'];

if(isset($this->width) && is_numeric($this->width) && $this->width>0)
	$width=$this->width;
else
	$width=1000;

	if(isset($this->height) && is_numeric($this->height) && $this->height>0)
		$height=$this->height;
	else
		$height=300;
		
$js=<<<EOF

google.charts.setOnLoadCallback(draw$chartid);

      function draw$chartid() {

				var data = google.visualization.arrayToDataTable([
						$jsdata
				]);

var options = {title:"$title",$variableoptions
                       width:$width,
                       height:$height};

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.$function(document.getElementById('{$chartid}_div'));
        chart.draw(data, options);
			}

EOF;

	$ret['Chartid']=$chartid;
	$ret['ChartJS']=$js;
	return $ret;
	}

}
