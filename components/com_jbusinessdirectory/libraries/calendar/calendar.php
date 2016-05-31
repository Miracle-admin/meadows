<?php
$document = JFactory::getDocument();

$document->addStyleSheet(JUri::root() . 'components/com_jbusinessdirectory/libraries/calendar/fullcalendar.min.css');
$document->addScript(JUri::root() . 'components/com_jbusinessdirectory/libraries/calendar/moment.min.js');
$document->addScript(JUri::root() . 'components/com_jbusinessdirectory/libraries/calendar/fullcalendar.min.js');

/* $calendarSource needs to be provided when the file is included. */

$dayNames = array();
$dayNamesShort =array();
$dayNamesMin = array();
$monthNames = array();
$monthNamesShort = array();
for ($i = 0; $i < 7; $i++){
	$dayNames[] = JBusinessUtil::dayToString($i, false);
	$dayNamesShort[] = JBusinessUtil::dayToString($i, true) ;
	$dayNamesMin[] = mb_substr(JBusinessUtil::dayToString($i, true), 0, 2) ;
}

for ($i = 1; $i <= 12; $i++){
	$monthNames[] = JBusinessUtil::monthToString($i, false) ;
	$monthNamesShort[] = JBusinessUtil::monthToString($i, true) ;
}

$calendarOptions = "	var calendarOptions = {\n";
$calendarOptions .= "		eventSources: " . json_encode($calendarSource) . ",\n";
$calendarOptions .= "		monthNames: " . json_encode($monthNames). ",\n";
$calendarOptions .= "		monthNamesShort: " . json_encode($monthNamesShort). ",\n";
$calendarOptions .= "		dayNames: " . json_encode($dayNames). ",\n";
$calendarOptions .= "		dayNamesShort: " . json_encode($dayNamesShort) . ",\n";
$calendarOptions .= "		dayNamesMin: " . json_encode($dayNamesMin) . ",\n";
$calendarOptions .= "	};\n";
$document->addScriptDeclaration($calendarOptions);
?>

<div id="events-calendar-container">
	<div class="clear"></div>
	<div id="events-calendar"></div>
</div>

<script>
	jQuery(document).ready(function() {
		// Some default options
		calendarOptions['header'] = {
			left : 'prev,next today',
			center : 'title',
			right : 'month,basicWeek,basicDay'
		};
		calendarOptions['height'] = "auto";
		calendarOptions['eventLimit'] = "true";
		calendarOptions['views'] = {
				month: {
		            eventLimit: 10
		        },
		        basicWeek: {
		            eventLimit: 50
		        },
		        basicDay: {
		            eventLimit: 50
		        }
			};
		// Translations
		calendarOptions['allDayText'] =  "<?php echo JText::_('LNG_ALL_DAY', true); ?>"
		calendarOptions['buttonText'] = {
			today : "<?php echo JText::_('LNG_TODAY', true)?>",
			month : "<?php echo JText::_('LNG_MONTH', true)?>",
			week : "<?php echo JText::_('LNG_WEEK', true)?>",
			day : "<?php echo JText::_('LNG_DAY', true)?>",
			list : "<?php echo JText::_('LNG_LIST', true)?>"
		};

		calendarOptions['listTexts'] = {
			until : "<?php echo JText::_('LNG_UNTIL', true)?>",
			past : "<?php echo JText::_('LNG_PAST', true)?>",
			today : "<?php echo JText::_('LNG_TODAY', true)?>",
			tomorrow : "<?php echo JText::_('LNG_TOMORROW', true)?>",
			thisWeek : "<?php echo JText::_('LNG_THIS_WEEK', true)?>",
			nextWeek : "<?php echo JText::_('LNG_NEXT_WEEK', true)?>",
			thisMonth : "<?php echo JText::_('LNG_THIS_MONTH', true)?>",
			nextMonth : "<?php echo JText::_('LNG_NEXT_MONTH', true)?>",
			future : "<?php echo JText::_('LNG_FUTURE', true)?>",
			week : "<?php echo JText::_('LNG_WEEK', true)?>"
		};


		calendarOptions['dayClick'] = function(date, jsEvent, view) {
			jQuery('#events-calendar').fullCalendar('gotoDate', date);
			jQuery('#events-calendar').fullCalendar('changeView', 'basicDay');	
		};

		calendarOptions['eventClick'] = function(event, jsEvent, view) {
			if (event.url) {
	            window.open(event.url,"_blank");
	            return false;
	        }	
		};
		
		
	});
</script>
