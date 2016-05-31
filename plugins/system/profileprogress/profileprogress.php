<?php

/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

//example
//$dispatcher = JDispatcher::getInstance();
//$results = $dispatcher->trigger( 'onProfileProgress', array(10,$user->id) );
//
//
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemProfileprogress extends JPlugin {

    /**
     * 
     * @return boolean
     */
    public function onProfileProgress($points, $userid, $degrade = false) {
       
        if (!$userid)
            return false;
        $db = JFactory::getDbo();
        $query = "SELECT completed FROM #__jblance_profilecompleted WHERE uid=" . $userid;
        $db->setQuery($query);
        $res = $db->loadObject();
        if ($res) {
            $object = new stdClass();
            if ($degrade && ($res->completed - $points) > 0) {
                $object->completed = $res->completed - $points;
                $object->uid = $userid;
                $object->id = $res->id;
            } else if ($res->completed + $points < 100) {
                $object->completed = $res->completed + $points;
                $object->uid = $userid;
                $object->id = $res->id;
            } else {
                return false;
            }
            $result = JFactory::getDbo()->updateObject('#__jblance_profilecompleted', $object, 'id');
            return true;
        } else {
            if ($points >= 10 && $points <= 100 && !$degrade) {
                $query = 'insert into #__jblance_profilecompleted (uid, completed) values("' . $userid . '", "' . $points . '")';
                $db->setQuery($query);
                $res = $db->query();
            } else {
                return false;
            }
        }
        return true;
    }

}

?>