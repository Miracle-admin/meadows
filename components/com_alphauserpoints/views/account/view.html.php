<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pane');
jimport( 'joomla.html.pagination' );

class alphauserpointsViewAccount extends JViewLegacy
{
	function _display($tpl = null) 
	{		
		$document	=  JFactory::getDocument();		

		//JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'includes'.DS.'pane.php');
		
		$pane   = JPane::getInstance('tabs');
		$slider = JPane::getInstance('sliders');
		
		$document->addStyleSheet(JURI::base(true).'/components/com_alphauserpoints/assets/css/alphauserpoints.css');
		$document->addStyleSheet(JURI::base(true).'/components/com_alphauserpoints/assets/css/bar.css');
		$document->addScript(JURI::base(true).'/components/com_alphauserpoints/assets/ajax/maxlength.js');
		
		
		// CROP AVATAR
		/*
		$document->addStyleSheet(JURI::base(true).'/components/com_alphauserpoints/assets/crop/css/uvumi-crop.css', 'text/css', 'screen');
		$document->addScript(JURI::base(true).'/components/com_alphauserpoints/assets/crop/js/UvumiCrop-compressed.js');
		$scriptCrop = "cropperAvatar = new uvumiCropper('cropAvatar',{
				coordinates:true,
				preview:true,
				downloadButton:false,
				saveButton:true
			});";
		$document->addScriptDeclaration($scriptCrop, '');
		*/
		
		isIE ();
		
		// get params definitions
		$params = JComponentHelper::getParams( 'com_alphauserpoints' );		
		$enabledUDDEIM = $params->get( 'showUddeimTab', '0' );		
	
		$this->assignRef( 'params', $this->params );
		$this->assignRef( 'cparams', $this->cparams );
		$this->assignRef( 'referreid',	$this->referreid );
		$this->assignRef( 'currenttotalpoints', $this->currenttotalpoints );
		$this->assignRef( 'lastupdate', $this->lastupdate );		
		$this->assignRef( 'rowslastpoints', $this->rowslastpoints );			
		$this->assignRef( 'referraluser', $this->referraluser );
		$this->assignRef( 'referralname', $this->referralname );
		$this->assignRef( 'rowsreferrees', $this->rowsreferrees );
		$this->assignRef( 'userid', $this->userid );
		$this->assignRef( 'userrankinfo', $this->userrankinfo );
		$this->assignRef( 'medalslistuser', $this->medalslistuser );		
		$this->assignRef( 'pane', $pane );
		$this->assignRef( 'slider', $slider );
		$this->assignRef( 'pointsearned', $this->pointsearned );		
		$this->assignRef( 'totalpoints', $this->totalpoints );
		$this->assignRef( 'mypointsearned', $this->mypointsearned );
		$this->assignRef( 'mypointsspent', $this->mypointsspent );		
		$this->assignRef( 'mypointsearnedthismonth', $this->mypointsearnedthismonth);
		$this->assignRef( 'mypointsspentthismonth', $this->mypointsspentthismonth);
		$this->assignRef( 'mypointsearnedthisday', $this->mypointsearnedthisday);
		$this->assignRef( 'mypointsspentthisday', $this->mypointsspentthisday);			
		$this->assignRef( 'myname', $this->myname);
		$this->assignRef( 'myusername', $this->myusername);
		$this->assignRef( 'avatar', $this->avatar);
		$this->assignRef( 'birthday', $this->birthday);
		$this->assignRef( 'user_info', $this->user_info);
		$this->assignRef( 'useAvatarFrom', $this->useAvatarFrom);
		$this->assignRef( 'mycouponscode', $this->mycouponscode);
		$this->assignRef( 'userinfo', $this->userinfo);
		$this->assignRef( 'average_age', $this->average_age);
		$this->assignRef( 'enabledUDDEIM', $enabledUDDEIM);
		
		$lists = array();
		if ( $this->referreid==@$_SESSION['referrerid'] ) {
			$options = array();
			$options[] = JHTML::_('select.option', '1', JText::_('AUP_MALE') );
			$options[] = JHTML::_('select.option', '2', JText::_('AUP_FEMALE') );
			$lists['gender'] = JHTML::_('select.radiolist', $options, 'gender', 'class="inputbox"' ,'value', 'text', $this->userinfo->gender );			
		}
		
		if ( $this->referreid==@$_SESSION['referrerid'] ) {
			$lists['shareinfos'] = JHTML::_('select.booleanlist', 'shareinfos', '', $this->userinfo->shareinfos );
		}
		
		$this->assignRef('lists', $lists );		
		
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef('pagination', $pagination );
		
		$document->setTitle( $this->myusername . ' - ' . getFormattedPoints( $this->currenttotalpoints ) . ' ' . JText::_('AUP_POINTS') );

		parent::display($tpl);
	}
	
}
?>