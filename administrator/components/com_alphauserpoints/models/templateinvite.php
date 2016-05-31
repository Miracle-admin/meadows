<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelTemplateinvite extends JmodelLegacy {

	function __construct(){
		parent::__construct();
	}
	
	function _load_templateinvite() {
	
		$app = JFactory::getApplication();
		
		$db			    = JFactory::getDBO();
		
		$total 			= 0;
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);
	
		
		$query = "SELECT * FROM #__alpha_userpoints_template_invite";
		$total = @$this->_getListCount($query);
		$result = $this->_getList($query, $limitstart, $limit);
		
		return array($result, $total, $limit, $limitstart);
	
	}	
	
	function _edit_templateinvite() {
	
		$db     = JFactory::getDBO();

		$cid 	= JFactory::getApplication()->input->get('cid', array(0), 'array');
		$option = JFactory::getApplication()->input->get('option', '', 'cmd');
		
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$lists = array();

		$row = JTable::getInstance('template_invite');
		$row->load( $cid[0] );
		
		
		
		return $row;
	
	}	
	
	function _delete_templateinvite() {
	
		$app = JFactory::getApplication();

		// initialize variables
		$db			= JFactory::getDBO();
		$cid		= JFactory::getApplication()->input->get('cid', array(), 'array');
		$msgType	= '';
		
		JArrayHelper::toInteger($cid);
		
		if (count($cid)) {		
			
			// are there one or more rows to delete?
			/*
			if (count($cid) == 1) {
				$row = JTable::getInstance('template_invite');
				$row->load($cid[0]);
			} else {
				$msg = JText::sprintf('AUP_MSGSUCCESSFULLYDELETED', JText::_('AUP_RULES'), '');
			}
			*/
		
			$query = "DELETE FROM #__alpha_userpoints_template_invite"
					. "\n WHERE (`id` = " . implode(' OR `id` = ', $cid) . ")"
					;
			$db->setQuery($query);
			
			if (!$db->query()) {
				$msg = $db->getErrorMsg();
				$msgType = 'error';
			}

		}

		JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&task=templateinvite', $msg, $msgType);
		JControllerLegacy::redirect();					
	}
	
	function _save_templateinvite($apply=0) {
		$app = JFactory::getApplication();

		// initialize variables
		$db = JFactory::getDBO();
		//$post = $_POST;
		$post = JFactory::getApplication()->input->getArray(array());	

		$row = JTable::getInstance('template_invite');
		
		$id= JFactory::getApplication()->input->get( 'id', 0, 'int' );
		
		if (!$row->bind( $post )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = JText::_( 'AUP_TEMPLATESAVED' );
		if (!$apply){
			JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&task=templateinvite', $msg);
		} else {
			JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&task=edittemplateinvite&cid[]='.$id, $msg);
		}
		JControllerLegacy::redirect();		
	}
	

}
?>