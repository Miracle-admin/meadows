<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

Jimport( 'joomla.application.component.view');

class alphauserpointsViewStats extends JViewLegacy {

	function _display($tpl = null) {
	
	
		$document	=  JFactory::getDocument();		
		$document->addScript( "https://www.google.com/jsapi" );	
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
	
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' . JText::_( 'AUP_STATISTICS' ), 'searchtext' );
		getCpanelToolbar();
		JToolBarHelper::back();
		getPrefHelpToolbar();	
		
		
		$topcountries = "";
		foreach($this->topcountryusers as $countryusers){											 
			$topcountries .= ",['".$countryusers->country."', '".$countryusers->numusers."']";									   
		}		
		
		$scriptCountry    =    "google.load('visualization', '1', {'packages': ['geochart']});
								 google.setOnLoadCallback(drawRegionsMap);
							
								  function drawRegionsMap() {
									var data = google.visualization.arrayToDataTable([['Country', 'Popularity']" .																	
									$topcountries
									. "
									]);
							
									var options = {};
							
									var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
									chart.draw(data, {width: 556, height: 347, minValue: 0,  colors: ['#FF0000', '#00FF00']});
								};
							";
		$document->addScriptDeclaration($scriptCountry);		
		
		foreach( $this->ratiomembers as $ratio ) {
			$sexe = $ratio->gender;
			$num = $ratio->nb;
			switch ( $sexe ) {
				case '0':
					$numU = $num;
					break;			
				case '1':
					$numM = $num ;
					break;
				case '2':
					$numF = $num ;	
					break;
			}	
		}
		
		
		$scriptGender = "      google.load('visualization', '1', {packages:['corechart']});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Gender', 'Num'],
				  ['".JText::_( 'AUP_MALES' ) ."',".$numM."],
				  ['".JText::_( 'AUP_FEMALES' ) ."',".$numF."],
				  ['".JText::_( 'AUP_UNKNOW' )."',".$numU."]
				]);
		
				var options = {title:'".JText::_( 'AUP_RATIO_MALE_FEMALE' )."',  chartArea:{left:10,top:20,width:'100%',height:'100%'},width:320,height:260};
		
				var chart = new google.visualization.PieChart(document.getElementById('chart_div_gender'));
				chart.draw(data, options);
			  }		
		";
		
		$document->addScriptDeclaration($scriptGender);		
		
		
		$this->assignRef( 'result', $this->result );
		$this->assignRef( 'result2', $this->result2 );
		$this->assignRef( 'date_start', $this->date_start );
		$this->assignRef( 'date_end', $this->date_end );
		$this->assignRef( 'listrules', $this->listrules );
		$this->assignRef( 'communitypoints', $this->communitypoints );
		$this->assignRef( 'average_age', $this->average_age );
		$this->assignRef( 'average_points_earned_by_day', $this->average_points_earned_by_day );
		$this->assignRef( 'average_points_spent_by_day', $this->average_points_spent_by_day );
		$this->assignRef( 'topcountryusers', $this->topcountryusers );
		$this->assignRef( 'numusers', $this->numusers );
		$this->assignRef( 'ratiomembers', $this->ratiomembers );
		$this->assignRef( 'inactiveusers', $this->inactiveusers );
		$this->assignRef( 'num_days_inactiveusers_rule', $this->num_days_inactiveusers_rule );
		
		parent::display( $tpl);
		
	}
}
?>
