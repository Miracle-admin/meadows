<?php
/*------------------------------------------------------------------------
# helper.php - Component Listing
# ------------------------------------------------------------------------
# author    codeboxr
# copyright Copyright (C) 2010-2014 codeboxr.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://codeboxr.com
# Technical Support:  Forum - http://codeboxr.com/product/quick-component-listing-module-for-joomla-admin-panel
-------------------------------------------------------------------------*/

class QuickcomponentHelper{

    /**
     * @param $params
     *
     * @return string
     */
    public  static  function getComList(&$params){
        //global $mainframe;
        $app = JFactory::getApplication();
        //$show_subitem = $params->get('show_subitem') ;
        //$show_credit = $params->get('show_credit') ;
        require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_menu'.DIRECTORY_SEPARATOR.'helper.php' );
        // Get the authorised components and sub-menus.
        $components = ModMenuHelper::getComponents( true );
        //$list = JManchuComList::_getDBlist();

        if (!$components){
            return "There is no component or failed to load the list...";
        }

        $txt = '<ol class="manchucomlist">';

        foreach ($components as &$component) {
            $txt .= '<li><a class="manchucomlink" href="'.JFilterOutput::ampReplace($component->link).'">'.$component->text.'</a>';
            //var_dump($component);
            if (!empty($component->submenu)) {
                // This component has a db driven submenu.
                //$menu->addChild(new JMenuNode($component->text, $component->link, $component->img), true);
                foreach ($component->submenu as $sub) {
                    //$menu->addChild(new JMenuNode($sub->text, $sub->link, $sub->img));
                    if ($component->link != $sub->link) {
                        $txt .= '  [<a class="manchusublink" href="'.JFilterOutput::ampReplace($sub->link).'">'.$sub->text.'</a>]';
                    }
                }

            }

            $txt .= '</li>';
        }
        $txt .= '</ol>';

        return $txt;
    }


    /**
     * @return array
     */
    /*
    public  static function _getDBlist(){
        $db   =& JFactory::getDBO();
        $query = 'SELECT * FROM #__components WHERE parent = \'0\' AND admin_menu_img <> \'\' AND enabled = \'1\' ORDER BY name ASC';
        $db->setQuery($query);
        $items = $db->loadObjectList();

        // Process the items
        $comList = array();
        foreach($items as $item)
        {
            $key = $item->name;

            $subItem[0]	= (JText::_($key))?JText::_($key) : $item->name;
            $subItem[1]	= 'index.php?option='. $item->option;
            $subItem[2] = $item->id;
            $subItem[3] = $item->option;
            $comList[] = $subItem;
        }
        return $comList;
    }
    */

    /**
     * @param $parentid
     *
     * @return array
     */
    /*
    public  static function _getSublist($parentid){
        $db     = JFactory::getDBO();
        $query  = 'SELECT * FROM #__components WHERE parent = '.$parentid.' ORDER BY ordering ASC';
        $db->setQuery($query);
        $row    = $db->loadObjectList();

        // Process the items
        $subList = array();

        foreach($row as $item){
            $key = $item->name;

            $subItem[0]	= (JText::_($key))?JText::_($key) : $item->name;
            $subItem[1]	= 'index.php?'. $item->admin_menu_link;
            $subList[] = $subItem;
        }
        return $subList;
    }

    */
}

?>