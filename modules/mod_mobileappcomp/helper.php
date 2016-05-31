<?php

/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link docs.joomla.org/J2.5:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModMobileAppcompHelper {

    /**
     * Retrieves the featured mobile app companies
     *
     * @param array $params An object containing the module parameters
     * @access public
     */
    //get the list of users have app development category	 
    public static function getUserList($params) {
        $jinput = JFactory::getApplication()->input;

        JLoader::import('joomla.application.component.model');
        JLoader::import('admproject', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jblance' . DS . 'models');
        $admprojects_model = JModelLegacy::getInstance('admproject', 'JblanceModel');


        //to set the limit
        JRequest::setVar('limit', 4);

        //to set the group id
        JRequest::setVar('ug_id', 4);


        //to get only featured companies
        $jinput->set('featured', 1);

        //users list
        $users = $admprojects_model->getShowUser();

        return $users[0];
    }

    //method to get the description set by a particular user
    public static function getDescription($userid) {
        $db = JFactory::getDbo();
        $query = "select value from #__jblance_custom_field_value WHERE userid=$userid and fieldid='35'";
        $db->setQuery($query);
        $description = $db->loadrow();
        $fields = JblanceHelper::get('helper.fields');
        // echo '<pre>'; print_r($description); 
        return $description[0];
    }

    //method to get the link field set by a particular user
    public static function getLink($userid) {
        $db = JFactory::getDbo();
        $query = "select value from #__jblance_custom_field_value WHERE userid=$userid AND fieldid='48'";
        $db->setQuery($query);
        $link = $db->loadrow();
        return $link[0];
    }

}

?>