<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$cparams 	= JComponentHelper::getParams('com_vmvendor');
$profileman = $cparams->get('profileman');
$daystolog 		= $cparams->get('daystolog', '30');
$date_format 	= $cparams->get('date_display' );
$juri = JURI::Base();			
$doc 	= JFactory::getDocument();
$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/profilevisits.css');
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';


echo '<button id="vmv-backbutton" type="button" class="btn btn-default" onclick="history.go(-1)" aria-invalid="false">
                    <i class="vmv-icon-leftarrow"></i> '.JText::_('COM_VMVENDOR_BACK').'</button>';
			
$days_array = array();
for($i = 0; $i < $daystolog ; $i++)
{
	$days_array[] = date("Y-m-d", strtotime('-'. $i .' days'));
}
$data = array();
$max_value = 0;
$total_visits = 0;
for($i=0;$i<count($days_array);$i++)
{
	$count_value = @$this->visits[$days_array[$i]];
	if(!$count_value)
		$count_value ='0';
	if($count_value>$max_value)
		$max_value = $count_value ;
	array_push ( $data , "['". JHtml::date( $days_array[$i] , 'd M ' , false) ."' , ".$count_value." ] , ");
	$total_visits = $total_visits + $count_value;
}
$max_value = $max_value + 1 ;
$data = array_reverse($data);
$data = implode('',$data);

echo  '<div style="float:left"><h3><i class="vmv-icon-calendar"></i> '.JText::_('COM_VMVENDOR_PROFILEVISITS').'</h3></div>';
echo '<div style="clear:both"></div>';
if(count($this->visits)>0)
{	
	echo  '<div id="visits_chart">';
	$doc->addScript('https://www.google.com/jsapi');
	$chart_script = " google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['DAYS', '".JText::_('COM_VMVENDOR_PROFILEVISITS')."'],";
	 $chart_script .= $data;
	 $chart_script .= "]);
			var options = {
				chartArea: {width: '95%'},
			  vAxis: {format:'#', gridlines: {count:'". $max_value  ."'}}, 
			  hAxis: {title: '".JText::_('COM_VMVENDOR_PROFILEVISITS_TOTAL')." : ".$total_visits." '  },
			  legend: {position: 'none'}
			};
			var chart = new google.visualization.ComboChart(document.getElementById('visits_chart'));
			chart.draw(data, options);
		  }";
	$doc->addScriptDeclaration($chart_script);
	echo  '</div>';
		
}
else
	echo  JText::_('COM_VMVENDOR_PROFILEVISITS_NOSTATS');
echo  '<hr />
	<h3><i class="icon-users"></i> '.JText::_('COM_VMVENDOR_PROFILEVISITS_LATESTVISITORS').'</h3>';
if($profileman=='js')
{
	require_once JPATH_BASE .  '/components/com_community/libraries/core.php';
	$config		= CFactory::getConfig();
	$js_naming 	= $config->get('displayname','username');
}
if(count($this->visitors)>0)
{
	echo '<div id="visitors_thumbs">';
	foreach($this->visitors as $visitor)
	{
		//$visitor_naming = $visitor->$naming;
		if($profileman=='cb')
		{
			if(!$visitor->thumb  )
				$thumb = $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
			elseif($visitor->thumb && $visitor->avatarapproved!='1' )
				$thumb = $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/pending_n.png';
			else
				$thumb =  $juri.'images/comprofiler/'.$visitor->thumb;
		$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$visitor->visitor_userid.'&Itemid='.$app->input->get('Itemid','','int')) ;
		}
		elseif($profileman=='js')
		{
			$visitor_naming = $visitor->$js_naming;
			if(!$visitor->thumb  )
				$thumb = $juri.'components/com_community/assets/user-Male-thumb.png';
			else
			{
				$thumb = $juri_storage.$visitor->thumb;
			}
			$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$visitor->visitor_userid.'&Itemid='.$this->profileItemid) ;
		}
		elseif($profileman=='es')
		{
			$his = Foundry::user( $visitor->visitor_userid );
			$thumb = $his->getAvatar( SOCIAL_AVATAR_MEDIUM  );
			$visitor_naming = $his->getName();
			$profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$visitor->visitor_userid.':'.$visitor->username.'&Itemid='.$this->profileItemid) ;
			
		}
		echo  '<div class="wvmp_visitor"  data-popbox="module://easysocial/profile/popbox" data-user-id="'.$visitor->visitor_userid.'">';	
		if($profileman!='0')
		{
			echo  '<div class="wvmp_thumb">
			<a href="'.$profile_url.'" ><img src="'.$thumb.'" width="64" heigh="64" alt="thumb" /></a></div>';
		}
		echo '<div><a href="'.$profile_url.'" ><i class="vmv-icon-user"></i> '.$visitor_naming.'</a></div>
		<div  title="'.JText::_('COM_VMVENDOR_PROFILEVISITS_LATESTVISIT').'"><i class="vmv-icon-calendar"></i> '.
		JHtml::date( $visitor->date , $date_format, $tz=false)
		.'</div>
		</div>';
	}
	echo  '</div>';
}
else
	echo  JText::_('COM_VMVENDOR_PROFILEVISITS_NOVISITORS');
echo  '<div style="clear:both"></div>';

?>