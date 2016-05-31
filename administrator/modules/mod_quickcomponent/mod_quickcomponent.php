<?php
/*------------------------------------------------------------------------
# mod_quickcomponent - Component Listing
# ------------------------------------------------------------------------
# author    Sabuj Kundu
# copyright Copyright (C) 2010-2011 codeboxr.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://codeboxr.com
# Technical Support:  Forum - http://codeboxr.com/product/quick-component-listing-module-for-joomla-admin-panel
#Special thanks to Jeff Koertzen<jeff@koertzen.com>
-------------------------------------------------------------------------*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);



// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.framework');
JHtml::_('behavior.modal', 'a.modal');

require_once __DIR__ . '/helper.php';

//load language forr menu module
$language = JFactory::getLanguage();
$language->load('mod_menu', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('mod_menu', JPATH_ADMINISTRATOR, null, true);

//jimport( 'joomla.html.html.tabs' );

// Lets get some variables we will need to render the menu
$lang	= JFactory::getLanguage();
$doc	= JFactory::getDocument();
$user	= JFactory::getUser();

//echo "Hi this comlist module by manchumahara mu ha ha<br/>";
//jimport('joomla.html.pane');

//$pane =& JPane::getInstance('tabs', array('startOffset' => 0));


//echo $pane->startPane('adminlisting'.$module->id);
//echo $pane->startPanel( JText::_('Components'), 'panel'.'adminlisting'.$module->id );
echo JHtml::_('bootstrap.startTabSet', 'modqc_components', array('active' => 'modqccomponents'));

    echo JHtml::_('bootstrap.addTab', 'modqc_components', 'modqccomponents', JText::_('Components'), true);

        echo QuickcomponentHelper::getComList($params);
    //echo $pane->endPanel();
    echo JHtml::_('bootstrap.endTab');

    //echo $pane->startPanel( JText::_('Extensions').'-'.JText::_('Tools'), 'panel'.'adminlisting'.$module->id );
    echo JHtml::_('bootstrap.addTab', 'modqc_components', 'modqcextensions',JText::_('Extensions'), false);

        $extensionlist = '<ol class="manchucomlist">';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_installer').'">'.JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER').'</a></li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_modules').'">'.JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER').'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_modules&tmpl=component').'">Quick View</a> )</li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_plugins').'">'.JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER').'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_plugins&tmpl=component').'">Quick View</a> )</li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_templates').'">'.JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER').'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_templates&tmpl=component').'">Quick View</a> )</li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_languages').'">'.JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER').'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_languages&tmpl=component').'">Quick View</a> )</li>';
        //$extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_messages').'">'.JText::_('Read Messages').'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_messages&tmpl=component').'">Quick View</a> )</li>';
        //$extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_messages&task=add').'">'.JText::_('Write Message').'</a></li>';
        //$extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_massmail').'">'.JText::_('Mass Mail').'</a></li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_checkin').'">'.JText::_('MOD_MENU_GLOBAL_CHECKIN').'</a></li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_cache').'">'.JText::_('MOD_MENU_CLEAR_CACHE').'</a></li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_cache&view=purge').'">'.JText::_('MOD_MENU_PURGE_EXPIRED_CACHE').'</a></li>';
        $extensionlist .= '</ol>';
        echo $extensionlist;
    //echo $pane->endPanel();
    echo JHtml::_('bootstrap.endTab');

    //echo $pane->startPanel(JText::_('Menus'), 'panel'.'adminlisting'.$module->id );
    echo JHtml::_('bootstrap.addTab', 'modqc_components', 'modqcmenus',JText::_('Menus'), false);
        // Menu Types
        require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_menus'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'menus.php' );
        //$menuTypes 	= MenusHelper::getMenuTypes();
        $db         = JFactory::getDbo();
        $db->setQuery('SELECT * FROM #__menu_types');
        $menuTypes = $db->loadObjectList();
        //var_dump($menuTypes);
        //print_r($menuTypes);
        $extensionlist = '<ol class="manchucomlist">';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_menus').'">'.JText::_('Menu Manager').'</a></li>';
        $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_trash&task=viewMenu').'">'.JText::_('Menu Trash').'</a></li>';
        $extensionlist .= '</ol>';
        echo $extensionlist;
        echo '<hr/>';
        if (count($menuTypes)) {
            //$pane2 =& JPane::getInstance('tabs', array('startOffset' => 0));
            //echo $pane2->startPane('adminlistingmenu'.$module->id);
            foreach ($menuTypes as $menuType) {
                //var_dump($menuType); return;
                //echo $pane2->startPanel( $menuType->title . ($menuType->home ? ' *' : ''), 'panel'.'adminlistingmenu'.$module->id );
                $extensionlist = '<ol class="manchucomlist">';
                $extensionlist .= '<li><a href="'.JFilterOutput::ampReplace('index.php?option=com_menus&view=items&menutype='
                                . $menuType->menutype).'">'.$menuType->title.'</a> ( <a rel="{handler: \'iframe\', size: {x: 700, y: 600}}" class="modal" href="'.JFilterOutput::ampReplace('index.php?option=com_menus&view=items&menutype='
                                . $menuType->menutype).'&tmpl=component">Quick View</a> )</li>';
                $extensionlist .= '</ol>';
                echo $extensionlist;
                //echo $pane2->endPanel();
            }
            //echo $pane2->endPane();
        }
    //echo $pane->endPanel();
     echo JHtml::_('bootstrap.endTab');

    //echo $pane->endPane();
     echo JHtml::_('bootstrap.endTab');




