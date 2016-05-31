<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerAccount extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
		parent::__construct( );
	}
	
	public function display ($cachable = false, $urlparams = false) 
	{
		$app = JFactory::getApplication();
		
		require_once (JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
		
		$com_params = JComponentHelper::getParams( 'com_alphauserpoints' );		
	
		$model      = $this->getModel ( 'alphauserpoints' );
		$view       = $this->getView  ( 'account','html' );
		
		// current user
		$user =  JFactory::getUser();
		
		// profil request 
		$userid      = JFactory::getApplication()->input->get( 'userid', '', 'string' );
		
		if ( !$user->id && !$com_params->get( 'allowGuestUserViewProfil', 1 ) ) {		
			$msg = JText::_('ALERTNOTAUTH' );
			$this->setRedirect('index.php', $msg);
			$this->redirect();
		}			
		
		// check referre ID
		if ( ! $userid ) {
			$referrerid = $model->_checkUser();
		} else  {
			$referrerid = $userid;
		}
		
		// Rule Profile View
		if ( $referrerid != @$_SESSION['referrerid'] ) 
		{
			$keyreference  = AlphaUserPointsHelper::buildKeyreference( 'sysplgaup_profile_view', $user->id );
			$username = ( $user->username ) ? $user->username : JText::_('AUP_GUEST');
			$datareference = JText::_('AUP_PROFILE_VIEW_REFERENCE') . ' ' . $username;
			AlphaUserPointsHelper::userpoints ( 'sysplgaup_profile_view' , $referrerid, 0, $keyreference, $datareference );
		}
		// End rule Profile View
		
		// Get the parameters of the active menu item
		$params = $model->_getParamsAUP();
		
		$num_item_activities = $params->get( 'num_item_activities', 10 );
	
		$_get_last_points   	= $model->_get_last_points ( $referrerid, $num_item_activities );
		$_listing_last_points	= $_get_last_points[0];
		$_listing_total			= $_get_last_points[1];
		$_listing_limit			= $_get_last_points[2];
		$_listing_limitstart	= $_get_last_points[3];		
		
		$rowsreferrees			= $model->_get_referrees ( $referrerid );
		
		$pointsearned 			= $model->_pointsearned(); // users points earned TOP 10
		$totalpoints			= $model->_totalpoints(); // entire community
		$mypointsearned 		= $model->_mypointsearned($referrerid);
		$mypointsspent 			= $model->_mypointsspent($referrerid);		
		$mypointsearnedthismonth= $model->_mypointsearnedthismonth($referrerid);
		$mypointsspentthismonth	= $model->_mypointsspentthismonth($referrerid);
		$mypointsearnedthisday	= $model->_mypointsearnedthisday($referrerid);
		$mypointsspentthisday	= $model->_mypointsspentthisday($referrerid);
		$_average_age			= _get_average_age_community();
	
		$_user_info = AlphaUserPointsHelper::getUserInfo ( $referrerid );
		
		$currenttotalpoints    	= $_user_info->points;
		$lastupdate 			= $_user_info->last_update;
		$referraluser 			= $_user_info->referraluser;
		
		$myname = $_user_info->name;		
		$myusername = $_user_info->username;
		$mybirthday	= $_user_info->birthdate;
		
		$referralname = "";
		if ( $referraluser ) {
			$referralinfo = AlphaUserPointsHelper::getUserInfo ( $referraluser );
			$referralname = $referralinfo->username;
		}
		
		// get level/rank if exist
		$userrankinfo = AlphaUserPointsHelper::getUserRank ( $referrerid );
		// get medals if exist
		$medalslistuser = AlphaUserPointsHelper::getUserMedals ( $referrerid );		

		// load avatar		
		$useAvatarFrom = $com_params->get('useAvatarFrom');
		$height = 100;
		if ( $useAvatarFrom=='alphauserpoints' ) {
    
		  $lang = JFactory::getLanguage();
		  $lang->load( 'com_media', JPATH_ADMINISTRATOR);
		
		}	
		
		$avatar = getAvatar( $useAvatarFrom, $_user_info, $height, $height, 'class="thumbnail"' );		
		
		// Get coupons code
		$resultCoupons = $model->_getMyCouponCode( $referrerid );		
						
		$view->assign('params', $params );
		$view->assign('cparams', $com_params );
		$view->assign('referreid', $referrerid );
		$view->assign('currenttotalpoints', $currenttotalpoints );
		
		$view->assign('rowslastpoints', $_listing_last_points );
		$view->assign('total', $_listing_total );
		$view->assign('limit', $_listing_limit );
		$view->assign('limitstart', $_listing_limitstart );
			
		$view->assign('lastupdate', $lastupdate );		
		$view->assign('referraluser', $referraluser );
		$view->assign('referralname', $referralname );
		$view->assign('rowsreferrees', $rowsreferrees );	
		$view->assign('userid', $user->id);
		$view->assign('userrankinfo', $userrankinfo);
		$view->assign('medalslistuser', $medalslistuser);
		$view->assign('pointsearned', $pointsearned);
		$view->assign('totalpoints', $totalpoints);
		$view->assign('mypointsearned', $mypointsearned);
		$view->assign('mypointsspent', $mypointsspent);	
		$view->assign('mypointsearnedthismonth', $mypointsearnedthismonth);
		$view->assign('mypointsspentthismonth', $mypointsspentthismonth);
		$view->assign('mypointsearnedthisday', $mypointsearnedthisday);
		$view->assign('mypointsspentthisday', $mypointsspentthisday);
		$view->assign('myname', $myname);
		$view->assign('myusername', $myusername);
		$view->assign('avatar', $avatar);
		$view->assign('birthday', $mybirthday);
		$view->assign('user_info', $_user_info);
		$view->assign('useAvatarFrom', $useAvatarFrom);
		$view->assign('mycouponscode', $resultCoupons);
		$view->assign('userinfo', $_user_info);
		$view->assign('average_age', $_average_age);		

		// Display
		$view->_display();
	}
	
	public function saveprofile() {
	
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
	
		$referreid 		= JFactory::getApplication()->input->get('referreid', '', 'string');
		$newbirthdate	= JFactory::getApplication()->input->get('birthdate', '0000-00-00', 'string');
		//$curdate = date( "Y-m-d" );
		/*
		if ( $newbirthdate >= $curdate ) {			
			return $this->display();
		}
		*/
		$model      = $this->getModel ( 'alphauserpoints' );
		$model->_save_profile();
		JFactory::getApplication()->input->set( 'userid', $referreid );
		
		return $this->display();
	
	}
	
	public function downloadactivity() {
	
			//$referrerid      = JFactory::getApplication()->input->get( 'userid', '', 'string' );
			$userid = JFactory::getApplication()->input->get( 'userid', '', 'int' );
			
			require_once (JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');
			
			$referrerid = AlphaUserPointsHelper::getAnyUserReferreID( $userid );
			
			if ( !$referrerid || $referrerid!=@$_SESSION['referrerid'] ) return;			
			
			$db = JFactory::getDBO();
			$nullDate = $db->getNullDate();
			
			$model        = $this->getModel ( 'alphauserpoints' );
			$lastpoints   = $model->_get_last_points ( $referrerid, 'nolimit' );			

			$fileName     = "completeactivity_" . uniqid(rand(), true) . ".csv";	
			$filepath     = JPATH_SITE . DS . 'tmp' . DS . $fileName;
			
			$handler = fopen($filepath,"a");
			$header = JText::_('AUP_DATE') . ";" . JText::_('AUP_ACTION') . ";" . JText::_('AUP_POINTS') . ";" . JText::_('AUP_EXPIRE') . ";" .JText::_('AUP_DETAIL') . ";" . JText::_('AUP_APPROVED');
			fwrite($handler, $header ."\n");

			$total = count( $lastpoints );
			for ($i=0;$i< $total;$i++) {
			
				$date_insert = JHTML::_('date',  $lastpoints[$i]->insert_date,  JText::_('DATE_FORMAT_LC2') );
			
				if ( $lastpoints[$i]->expire_date == $nullDate ) {
					$date_expire =  '';
				} else {
					$date_expire = JHTML::_('date',  $lastpoints[$i]->expire_date,  JText::_('DATE_FORMAT_LC') );
				}	
				
				$approved = ( $lastpoints[$i]->approved )?  JText::_('AUP_APPROVED') :  JText::_('AUP_PENDINGAPPROVAL') ;	 					 

				fwrite( $handler, $date_insert . ";" . JText::_($lastpoints[$i]->rule_name) . ";" . $lastpoints[$i]->points . ";" . $date_expire . ";" . $lastpoints[$i]->datareference . ";" . $approved . "\n" );
			}
	
			header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
			header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			header("Content-Type: text/x-comma-separated-values");
			header("Content-Disposition: attachment; filename=$fileName");
			
			readfile($filepath);
			
			exit;
	}
	
	/* *
	 * upload avatar
	 */
	public function uploadavatar()
	{
		$app = JFactory::getApplication();
		
		$db   = JFactory::getDBO();
		$user =  JFactory::getUser();		

		// load language for component media

    	$lang = JFactory::getLanguage();
    	$lang->load( 'com_media', JPATH_SITE);	
    
    	$lang = JFactory::getLanguage();
    	$lang->load( 'com_media', JPATH_ADMINISTRATOR);		
		
		$params = JComponentHelper::getParams('com_media');
		
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php' );
		
		define('COM_AUP_MEDIA_BASE_IMAGE', JPATH_ROOT.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'images');		

		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$files 		= JFactory::getApplication()->input->files->get('filedata', '', 'array');
		$file 		= $files[0];
		$folder		= JFactory::getApplication()->input->get( 'folder', 'avatars', 'path' );
		$format		= JFactory::getApplication()->input->get( 'format', 'html', 'cmd');
		$return		= JFactory::getApplication()->input->get( 'return-url', null, 'base64' );
		$referrerid = JFactory::getApplication()->input->get( 'referrerid', '', 'string' );
		$err		= null;

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name'] = JFile::makeSafe($file['name']);

		if (isset($file['name']) && $referrerid!='') {
			$extention = JFile::getExt($file['name']);
			$newnameavatar = strtolower($referrerid.'.'.$extention);
			
			//chmod (COM_AUP_MEDIA_BASE_IMAGE.DS.$folder, 0755) ;
			$filepath = JPath::clean(COM_AUP_MEDIA_BASE_IMAGE.DS.$folder.DS.$newnameavatar);
			// erase old avatar
			if ( file_exists($filepath) ) @unlink( $filepath );
			 			
			if (!MediaHelper::canUpload( $file, $err )) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				} else {
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {  					
			     		$this->setRedirect(base64_decode($return));
			     		$this->redirect();	
					}
					return;
				}
			}

			if (JFile::exists($filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepath));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('UPLOAD FAILED. FILE ALREADY EXISTS'));
					// REDIRECT
					if ($return) {
			    		$this->setRedirect(base64_decode($return));
			     		$this->redirect();						
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath)) {
				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepath));
					header('HTTP/1.0 400 Bad Request');
					jexit('ERROR. UNABLE TO UPLOAD FILE');
				} else {
					JError::raiseWarning(100, JText::_('ERROR. UNABLE TO UPLOAD FILE'));
					// REDIRECT
					if ($return) {
			     		$this->setRedirect(base64_decode($return));
			     		$this->redirect();
					}
					return;
				}
			} else {
				// SAVE IN PROFIL USER ALPHAUSERPOINTS
				$query = "UPDATE #__alpha_userpoints" .
				"\n SET avatar='".$newnameavatar."'" .
				"\n WHERE referreid='".$referrerid."' AND userid='" . $user->id . "'"
				;
				$db->setQuery( $query );			
				if (!$db->query()) 
				{
					JError::raiseError( 500, $db->getErrorMsg() );			
					return false;
				}
				
				require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php');

				if ($format == 'json') {
					jimport('joomla.error.log');
					$log = JLog::getInstance();
					$log->addEntry(array('comment' => $folder));
					jexit('Upload complete');
					// apply rule for upload avatar
					AlphaUserPointsHelper::userpoints( 'sysplgaup_uploadavatar', '', 0, $referrerid );
					
				} else {
					$app->enqueueMessage(JText::_('UPLOAD COMPLETE'));
					
					// apply rule for upload avatar
					AlphaUserPointsHelper::userpoints( 'sysplgaup_uploadavatar', '', 0, $referrerid );
					
					// REDIRECT
					if ($return) {
			     	$this->setRedirect(base64_decode($return));
			     	$this->redirect();						
					}
					return;
				}
			}
		} else {
			     $this->setRedirect('index.php', 'Invalid Request', 'error');
			     $this->redirect();	
		}
	}
	
}
?>