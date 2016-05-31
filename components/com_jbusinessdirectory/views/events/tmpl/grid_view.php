<?php 

$calendarSource = html_entity_decode(JRoute::_('index.php?option=com_jbusinessdirectory&task=events.getCalendarEvents'));
require_once JPATH_COMPONENT_SITE.'/libraries/calendar/calendar.php';

?>