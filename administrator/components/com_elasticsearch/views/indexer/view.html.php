<?php
/*
   Copyright 2013 CRIM - Computer Research Institute of Montreal

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
?>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * ElasticSearch View
 */
class ElasticSearchViewIndexer extends JViewLegacy
{

        function display($tpl = null) 
        {
				
			$dispatcher	= JDispatcher::getInstance();
			$res = JPluginHelper::importPlugin('elasticsearch');

			$types = array();
			
			// Trigger the index event.
			$this->results = $dispatcher->trigger('onElasticSearchIndexAll', array($types));
			
		   // Display the template
			parent::display($tpl);
			
        }
        
}
