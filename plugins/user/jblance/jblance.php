<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  User.joomla
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Joomla User plugin
 *
 * @since  1.5
 */
class PlgUserJblance extends JPlugin {

    function onUserAfterLogin($options) {

        $user = $options['user'];
        $app = JFactory::getApplication();
        $groups = $user->groups;

        $jinput = & JFactory::getApplication()->input;
        $planID = $jinput->get('plan_id');


//you are developer
        if (in_array(13, $groups)) {

            if ($this->lastUpdate($user->id)) {
                $this->updateDateSlots($user->id);
            }

//add the user plan info.
            $api_jb = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jblance' . DS . 'jblance.php';

            include_once($api_jb);

            $customer = JblanceHelper::getBtCustomer($user->id);
            
			
			
		    $key = $user->id . "." . $user->name . ".bt.customer.";
          
            $app->setUserState($key, $customer);
			
			$data_value = $app->input->post->getArray();
            if (isset($data_value['temp_var'])) {
                return;
            }

            $url= ($options['return'] && ($options['return']!="index.php?option=com_users&view=profile")) ? $options['return'] : JUri::root() . "index.php?option=com_jblance&view=user&layout=dashboarddeveloper&Itemid=368";
            $app->redirect(JRoute::_($url));
            $app->exit();
        }
//you are a company
        if (in_array(11, $groups)) {


//$app->redirect(JRoute::_(JUri::root()."index.php?option=com_jblance&view=user&layout=dashboard&Itemid=148"));
            $url= ($options['return'] && ($options['return']!="index.php?option=com_users&view=profile")) ?JRoute::_($options['return']): htmlspecialchars_decode(JRoute::_("index.php?option=com_jblance&view=user&layout=dashboard&Itemid=394")); 
            $app->redirect($url);
            $app->exit();
        }
//you are shopper
        if (in_array(12, $groups)) {
            $url= ($options['return']  && ($options['return']!="index.php?option=com_users&view=profile")) ? $options['return'] :"index.php?option=com_virtuemart&view=virtuemart&productsublayout=products_horizon&Itemid=199";
            $app->redirect(htmlspecialchars_decode(JRoute::_($url)), "Welcome " . $user->name . " to AppMeadows market place.");
            $app->exit();
        }
    }

    function lastUpdate($uId) {

        $user = JFactory::getUser();
        $db = JFactory::getDbo();

        $query = "SELECT last_update FROM #__jblance_visitcounts WHERE uid = '" . $user->id . "'";
        $db->setQuery($query);
        $lastUpdate = $db->loadObject();
        $lastUpdate = strtotime($lastUpdate->last_update);

        $i_12_hours = strtotime('now -12 hours');
        if ($lastUpdate < $i_12_hours) {
            return true;
        } else {
            return false;
        }
    }

    function CalculateDateSlots($uid) {

        $db = JFactory::getDbo();

        $return = array();

//calculate date slots
        $six_daysago = date('Y-m-d H:i:s', strtotime('-6 days', strtotime(date("Y-m-d H:i:s"))));

        $twelve_daysago = date('Y-m-d H:i:s', strtotime('-12 days', strtotime(date("Y-m-d H:i:s"))));

        $eighteen_daysago = date('Y-m-d H:i:s', strtotime('-18 days', strtotime(date("Y-m-d H:i:s"))));

        $twentyfour_daysago = date('Y-m-d H:i:s', strtotime('-24 days', strtotime(date("Y-m-d H:i:s"))));

        $one_monthago = date('Y-m-d H:i:s', strtotime('-1 month', strtotime(date("Y-m-d H:i:s"))));


//0count 6 days hit to me
        $query = "SELECT count(*) AS sixdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $six_daysago . "' AND NOW( ) AND visit_to='" . $uid . "'";

        $db->setQuery($query);
        $return['to_me_6d'] = $db->loadObject()->sixdays;

//1count 12 days hit to me	
        $query = "SELECT count(*) AS twelvedays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $twelve_daysago . "' AND '" . $six_daysago . "' AND visit_to='" . $uid . "'";
        $db->setQuery($query);
        $return['tome_12d'] = $db->loadObject()->twelvedays;


//2count 18 days hits to me
        $query = "SELECT count(*) AS eighteendays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $eighteen_daysago . "' AND '" . $twelve_daysago . "' AND visit_to='" . $uid . "'";
        $db->setQuery($query);
        $return['tome_18d'] = $db->loadObject()->eighteendays;


//3count 24 days hits to me
        $query = "SELECT count(*) AS twentyfourdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $twentyfour_daysago . "' AND '" . $eighteen_daysago . "' AND visit_to='" . $uid . "'";
        $db->setQuery($query);
        $return['tome_24d'] = $db->loadObject()->twentyfourdays;

//4count monthly hits to me
        $query = "SELECT count(*) AS onemonth
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $one_monthago . "' AND '" . $twentyfour_daysago . "' AND visit_to='" . $uid . "'";
        $db->setQuery($query);
        $return['tome_1m'] = $db->loadObject()->onemonth;

######################################## hits by me below ###########################################
//5count 6 days hit by me
        $query = "SELECT count(*) AS sixdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $six_daysago . "' AND NOW( ) AND visit_from='" . $uid . "'";

        $db->setQuery($query);
        $return['byme_6d'] = $db->loadObject()->sixdays;

//6count 12 days hit by me	
        $query = "SELECT count(*) AS twelvedays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $twelve_daysago . "' AND '" . $six_daysago . "' AND visit_from='" . $uid . "'";
        $db->setQuery($query);
        $return['byme_12d'] = $db->loadObject()->twelvedays;


//7count 18 days hits by me
        $query = "SELECT count(*) AS eighteendays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $eighteen_daysago . "' AND '" . $twelve_daysago . "' AND visit_from='" . $uid . "'";
        $db->setQuery($query);
        $return['byme_18d'] = $db->loadObject()->eighteendays;


//8count 24 days hits by me
        $query = "SELECT count(*) AS twentyfourdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $twentyfour_daysago . "' AND '" . $eighteen_daysago . "' AND visit_from='" . $uid . "'";
        $db->setQuery($query);
        $return['byme_24d'] = $db->loadObject()->twentyfourdays;

//9count monthly hits by me
        $query = "SELECT count(*) AS onemonth
FROM  `#__jblance_profvisits` 
WHERE visit_date between '" . $one_monthago . "' AND '" . $twentyfour_daysago . "' AND visit_from='" . $uid . "'";
        $db->setQuery($query);
        $return['byme_1m'] = $db->loadObject()->onemonth;




        return $return;
    }

//update date slots
    function updateDateSlots($id) {
        $db = JFactory::getDbo();
        $dateSlots = $this->CalculateDateSlots($id);
        $dateSlotsK = array_keys($dateSlots);

        $query = $db->getQuery(true);

// Fields to update.
        $fields = array(
            $db->quoteName($dateSlotsK[0]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[0]]),
            $db->quoteName($dateSlotsK[1]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[1]]),
            $db->quoteName($dateSlotsK[2]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[2]]),
            $db->quoteName($dateSlotsK[3]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[3]]),
            $db->quoteName($dateSlotsK[4]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[4]]),
            $db->quoteName($dateSlotsK[5]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[5]]),
            $db->quoteName($dateSlotsK[6]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[6]]),
            $db->quoteName($dateSlotsK[7]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[7]]),
            $db->quoteName($dateSlotsK[8]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[8]]),
            $db->quoteName($dateSlotsK[9]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[9]])
        );

// Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('uid') . ' = ' . $id,
        );

        $query->update($db->quoteName('#__jblance_visitcounts'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $result = $db->execute();
    }

}
