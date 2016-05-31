<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) {
   define('DS', DIRECTORY_SEPARATOR);
}

	function getCopyrightNotice () {
	
		$copyStart = 2008; 
		$copyNow = date('Y');  
		if ($copyStart == $copyNow) 
		{ 
			$copySite = $copyStart;
		} 
		else 
		{
			$copySite = $copyStart."-".$copyNow ;
		}
	
		/** 
		*  IMPORTANT !
		*  Provide copyright on frontend
		*  If you remove or hide this lines below,
		*  please make a donation if you find AlphaUserPoints useful
		*  and want to support its continued development.
		*  Your donations help by hardware, hosting services and other expenses that come up as we develop,
		*  protect and promote AlphaUserPoints and other free components.
		*  You can donate on http://www.alphaplug.com
		*
		*/	
		
		echo "<p>&nbsp;</p><div style=\"clear:both;text-align:center;\">"
		. "<span>Powered by <a href=\"http://www.alphaplug.com\" target=\"_blank\">AlphaUserPoints</a> &copy; $copySite</span>"
		. "</div><p>&nbsp;</p>"
		;
	
	}
	
	function getLinkToInvite( $referrerid, $systemRegistration ) 
	{
	
			$uri        = JURI::getInstance();
			$base    	= $uri->toString( array('scheme', 'host', 'port'));
			
			$referrer_link = '';
	
			// prepare link for register
			$referrer_exist  = ( $referrerid ) ? "&referrer=$referrerid" : "";
			
			switch ( $systemRegistration ) {
			
				case 'cb' :
					$referrer_link    	= $base.JRoute::_( "index.php?option=com_comprofiler&task=registers$referrer_exist" );
					break;
					
				case 'cbe' :
					$referrer_link    	= $base.JRoute::_( "index.php?option=com_cbe&task=registers$referrer_exist" );
					break;
					
				case 'VM' : 
					$referrer_link 		= $base.JRoute::_( "index.php?option=com_virtuemart&page=shop.registration$referrer_exist" ); 
					break;		
					
				case 'js':
					$referrer_link 		= $base.JRoute::_( "index.php?option=com_community&view=register$referrer_exist" ); 
					break;

				case 'ose':
					$referrer_link 		= $base.JRoute::_( "index.php?option=com_osemsc&view=register&layout=onestep$referrer_exist" ); 
					break;
					
				case 'ext': // extendedreg
					$referrer_link    	= $base.JRoute::_( "index.php?option=com_extendedreg&task=register$referrer_exist" );
					break;
					
				case 'J!' :
				case 'joomunity' :			
				default   :			
					$referrer_link    	= $base.JRoute::_( "index.php?option=com_users&view=registration$referrer_exist" );
					
			}			
			return $referrer_link;
	}
	
	function getAvatar( $useAvatarFrom, $userinfo, $height='', $width='', $class='' ) 
	{		
		$db	    = JFactory::getDBO();
		
		$avatar = '';
		
		$setheight = ( $height!='' )? 'height="'.$height.'"' : '';
		$setwidth  = ( $width!=''  )? 'width="'.$width.'"'   : 'width="'.$height.'"';
		
		if ( $width=='' ) $width = $height;		
		
		$defaultAvatarAUP = JURI::root() . 'components/com_alphauserpoints/assets/images/avatars/generic_gravatar_grey.png';
		
		switch ( $useAvatarFrom ) 
		{	
			case 'gravatar':
				$email   = $userinfo->email ;
				$gravatar_url = 'http://www.gravatar.com/avatar/';
				$gravatar_url .= md5( strtolower(trim($email)) );
				$gravatar_url .= '?d=' . urlencode($defaultAvatarAUP);	
				if ( $height ) {
					$gravatar_url .= '&amp;s=$height';		
				} else $gravatar_url .= '&amp;s=80';		
				$avatar = '<img src="'.$gravatar_url.'" alt=""/>';
				break;			
			case 'kunena':
				if(!defined("_AUP_KUNENA_PATH")) {
					define('_AUP_KUNENA_PATH', JPATH_ROOT . '/media/kunena');
				}
				if(!defined("_AUP_KUNENA_LIVE_PATH")) {
					define('_AUP_KUNENA_LIVE_PATH', JURI::base(true) . '/media/kunena');		
				}				
				$Avatarname = $userinfo->avatar;
				$query = "SELECT a.*, b.* FROM #__kunena_users as a"
                        . "\n LEFT JOIN #__users as b on b.id=a.userid"
                        . "\n where a.userid=".$userinfo->id;
						;
				$db->setQuery( $query );
				$userProfilKunena = $db->loadObject();
				$fb_avatar = @$userProfilKunena->avatar;		
				if ($fb_avatar != '')
				{
					if(!file_exists( _AUP_KUNENA_PATH . '/avatars/' . $fb_avatar))
					{
						$avatar = _AUP_KUNENA_LIVE_PATH . '/avatars/' . $fb_avatar;
					}
					else
					{
						$avatar = _AUP_KUNENA_LIVE_PATH . '/avatars/' . $fb_avatar;
					}
				}
        		else $avatar = _AUP_KUNENA_LIVE_PATH . '/avatars/nophoto.jpg';
				break;
			case 'cb':
				$query = "SELECT avatar FROM #__comprofiler WHERE user_id = '".$userinfo->id."'";
				$db->setQuery($query);
				$result = $db->loadResult();
				if(!empty($result)) {
					$avatar = JURI::base(true)."/images/comprofiler/".$result;
				} else {
					$avatar = JURI::base(true)."/components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png";
				}						
				break;
			case 'cbe':
				global $mosConfig_lang;
				$query = "SELECT avatar, avatarapproved FROM #__cbe WHERE user_id ='".$userinfo->id."'";
				$db->setQuery($query);
				$result = $db->loadObject();
				$avatar = $result->avatar;
				if(file_exists(JPATH_ROOT . DS. "components".DS."com_cbe".DS."images".DS.$mosConfig_lang)) {
					$uimagepath = JURI::base(true)."/components/com_cbe/images/".$mosConfig_lang."/";
				} else $uimagepath = JURI::base(true)."/components/com_cbe/images/english/";
				if($result->avatarapproved==0) {
					$avatar = $uimagepath . "pendphoto.jpg";
				} elseif($result->avatar=='' || $result->avatar==null) {
					$avatar = $uimagepath . "nophoto.jpg";
				} else $avatar = JURI::base(true)."/images/cbe/".$avatar;						
				break;
			
			case 'jomsocial':
				$query = "SELECT avatar FROM #__community_users WHERE userid = '".$userinfo->id."'";	
				$db->setQuery($query);	
				$result = $db->loadResult();	
				if(!empty($result)) {		
					$avatar = JURI::base(false). $result;
				} else {
					$avatar = JURI::base(true)."/components/com_community/assets/default_thumb.jpg"; 	
				}
				break;
			case 'clexus':
				$query = "SELECT picture FROM #__mypms_profiles WHERE `name`='".$userinfo->username."'";
				$db->setQuery($query);
				$result = $db->loadResult();
				if(!empty($result)) {		
					$avatar = $result;
				} else {
					$avatar='';	
				}				
				break;
			case 'K2':
				$query = "SELECT image FROM #__k2_users WHERE userID='".$userinfo->id."'";
				$db->setQuery($query);
				$result = $db->loadResult();
				if(!empty($result)) {
					$avatar = JURI::base(true)."/media/k2/users/".$result;
				} else {
					$avatar='';	
				}
				break;
			case 'alphauserpoints':
				
				if(!defined("_AUP_AVATAR_LIVE_PATH")) {
					define('_AUP_AVATAR_LIVE_PATH', JURI::base(true) . '/components/com_alphauserpoints/assets/images/avatars/');
				}
				
				//$usr_avatar = ( $userinfo->avatar!='' ) ? JPATH_COMPONENT . DS . 'assets/images/avatars/' . $userinfo->avatar : JPATH_COMPONENT . DS . 'assets/images/avatars/generic_gravatar_grey.gif' ;
				$usr_avatar = ( $userinfo->avatar!='' ) ? JPATH_ROOT . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'assets'.DS.'images'.DS.'avatars'. DS . $userinfo->avatar : JPATH_ROOT . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'assets'.DS.'images'.DS.'avatars'. DS . 'generic_gravatar_grey.gif' ;
				if(file_exists($usr_avatar)){
					$image = new JImage( $usr_avatar );
					$avatar = $image->createThumbs( array( $width .'x'.$height ), JImage::CROP_RESIZE, JPATH_ROOT . DS . 'components' . DS . 'com_alphauserpoints' .DS. 'assets'.DS.'images'.DS.'avatars'.DS.'thumbs' );
					$avatar = myImage::getLivePathImage($avatar); 		
				}else {				
					$avatar = $defaultAvatarAUP;
				}
				
				break;
				
			case 'jomWALL': 
				// for version 2.5
				$config 					= JComponentHelper::getParams('com_awdwall');
				$template 					= $config->get('temp', 'blue');
				$avatarintergration 		= $config->get('avatarintergration', '0');
				
				$query 	= "SELECT facebook_id FROM #__jconnector_ids WHERE user_id = "  . (int)$userId;
				$db->setQuery($query);
				$facebook_id = $db->loadResult();
				if($facebook_id)
				{
					$avatar='https://graph.facebook.com/'.$facebook_id.'/picture?type=large';
				}
				else
				{
					
					$query 	= 'SELECT avatar FROM #__awd_wall_users WHERE user_id = ' . (int)$userId;
					$db 	=  JFactory::getDBO();
					$db->setQuery($query);
					$img = $db->loadResult();		
					
					if($img == NULL){
						$avatar = JURI::root() . "components/com_awdwall/images/".$template."/".$template."51.png";
					}else{
						$avatar = JURI::root() . "images/wallavatar/" . $userId . "/thumb/tn51" . $img;
					}
				}
			
				if($avatarintergration==1) // k2
				{
						if(file_exists(JPATH_SITE . '/components/com_k2/k2.php'))
						{
							require_once (JPATH_SITE . '/components/com_k2/helpers/utilities.php');
						
						$avatar=K2HelperUtilities::getAvatar($userId);
						}
					
				}
				else if($avatarintergration==2) // easyblog
				{
						if(file_exists(JPATH_SITE . '/components/com_easyblog/easyblog.php'))
						{
							require_once (JPATH_SITE . '/components/com_easyblog/helpers/helper.php');
						
						$blogger	= EasyBlogHelper::getTable( 'Profile', 'Table');
						$blogger->load( $userId );
						$avatar=$blogger->getAvatar();
						}
				}
				else if($avatarintergration==3) // alphauserpoint
				{
						if(file_exists(JPATH_SITE . '/components/com_alphauserpoints/alphauserpoints.php'))
						{
							require_once (JPATH_SITE . '/components/com_alphauserpoints/helper.php');
							require_once (JPATH_SITE . '/components/com_alphauserpoints/helpers/helpers.php');
						
							$_user_info = AlphaUserPointsHelper::getUserInfo ( $referrerid='', $userId  );
							$com_params = JComponentHelper::getParams( 'com_alphauserpoints' );
							$useAvatarFrom = $com_params->get('useAvatarFrom');
							$height = 50;
							$width=50;
							$avatar = getAvatar( $useAvatarFrom, $_user_info, $height,$width);	
							$doc = new DOMDocument();
							$doc->loadHTML($avatar);
							$imageTags = $doc->getElementsByTagName('img');
							
							foreach($imageTags as $tag) {
								$avatar=$tag->getAttribute('src');
							}
						}
				}
				break;
			default:
				$avatar = '';
		}		
		
		if ( $avatar && $useAvatarFrom!='gravatar' && $useAvatarFrom!='jomsocial') {
			
			$avatar = '<img src="' . $avatar . '" border="0" alt="" ' . $setheight . $setwidth . ' ' . $class . ' />';
			
		} elseif ( $useAvatarFrom=='jomsocial' ){ 
			$avatar = '<img src="' . $avatar . '" border="0" alt="" ' . $setheight . $setwidth . ' />';
		}
		
		return $avatar;	
	}
	
	function getProfileLink( $profilechoice, $userinfo, $xhtml=true )
	{		
		$db	   = JFactory::getDBO();

		switch ( $profilechoice ) {
		
			case 'ku' :
				$query = "SELECT id FROM #__menu WHERE `link`='index.php?option=com_kunena' AND `type`='component' AND `published`='1'";
				$db->setQuery( $query );
				$profileitemid  = '&amp;Itemid=' . $db->loadResult();			
				$profilLink = JRoute::_( 'index.php?option=com_kunena&amp;func=fbprofile&amp;userid=' . $userinfo->userid . $profileitemid, $xhtml);
				break;
			
			case 'cb' :
				$profilLink = JRoute::_( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . $userinfo->userid, $xhtml);
				break;
				
			case 'cbe' :
				$profilLink = JRoute::_( 'index.php?option=com_cbe&amp;task=userProfile&amp;user=' . $userinfo->userid, $xhtml);
				break;
			case 'js' :
				$profilLink = JRoute::_( 'index.php?option=com_community&amp;view=profile&amp;userid=' . $userinfo->userid, $xhtml);
				break; 
				
			case 'j!' :
				$profilLink = JRoute::_( 'index.php?option=com_users&view=profile', $xhtml);
				break;
				
			case 'jw' :
				if (is_file ( JPATH_ROOT . '/components/com_awdwall/helpers/user.php' ))
				{
					include_once (JPATH_ROOT . '/components/com_awdwall/helpers/user.php');
					$itemId = AwdwallHelperUser::getComItemId();
					$profilLink = JRoute::_('index.php?option=com_awdwall&view=awdwall&layout=mywall&wuid=' .  $userinfo->userid . '&Itemid=' . $itemId, false);
				} else $profilLink = '';				
				break;					
				
			default :
				// AUP Link Profile
				$query = "SELECT id FROM #__menu WHERE `link`='index.php?option=com_alphauserpoints&view=account' AND `type`='component' AND `published`='1'";
				$db->setQuery( $query );
				$profileitemid  = '&amp;Itemid=' . $db->loadResult();
				$profilLink = JRoute::_( 'index.php?option=com_alphauserpoints&amp;view=account&amp;userid=' . $userinfo->referreid . $profileitemid, $xhtml);
				break;	
		}
		
		return $profilLink;
	
	}
	
	function nicetime($date, $showTense=1)
	{		
		$config = JFactory::getConfig();
		$tzoffset = $config->get('config.offset');
		
		if(empty($date)) {
			return "No date provided";
		}
		
		$datetimestamp = strtotime($date);
		$date = date('Y-m-d H:i:s', $datetimestamp + ($tzoffset * 60 * 60));
	   
		$period          = array(JText::_( 'AUP_SECOND' ), JText::_( 'AUP_MINUTE' ), JText::_( 'AUP_HOUR' ), JText::_( 'AUP_DAY' ), JText::_( 'AUP_WEEK' ), JText::_( 'AUP_MONTH' ), JText::_( 'AUP_YEAR' ), JText::_( 'AUP_DECADE' ));
		$periods         = array(JText::_( 'AUP_SECONDS' ), JText::_( 'AUP_MINUTES' ), JText::_( 'AUP_HOURS' ), JText::_( 'AUP_DAYS' ), JText::_( 'AUP_WEEKS' ), JText::_( 'AUP_MONTHS' ), JText::_( 'AUP_YEARS' ), JText::_( 'AUP_DECADES' ));
		
		$lengths         = array("60","60","24","7","4.35","12","10");
	   
		//$now             = time();
		$now = strtotime(gmdate('Y-m-d H:i:s')) + ($tzoffset * 60 * 60);
		$unix_date       = strtotime($date);
	   
		   // check validity of date
		if(empty($unix_date)) {   
			return "Bad date";
		}
	
		// is it future date or past date
		if($now > $unix_date) {   
			$difference     = $now - $unix_date;
			$tense         = JText::_( 'AUP_AGO' );
		   
		} else {
			$difference     = $unix_date - $now;
			$tense         = JText::_( 'AUP_FROM_NOW' );
		}
	   
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
	   
		$difference = round($difference);
	   
		if($difference != 1) {
			$nicetime = $difference . " " . $periods[$j];
			if ( $showTense ) {
				return sprintf($tense, $nicetime);
			} else return $nicetime;
		} else {		
			$nicetime = $difference . " " . $period[$j];
			if ( $showTense ) {
				return sprintf($tense, $nicetime);
			} else return $nicetime;
		}

	}

	function determine_age( $birth_date )
	{
		$birth_date_time = strtotime($birth_date);
		$to_date = date('Y-m-d', $birth_date_time);
		
		list($birth_year, $birth_month, $birth_day ) = explode('-', $to_date);
		
		$now = time();
		
		$current_year = date('Y');
		
		$this_year_birth_date = $current_year.'-'.$birth_month.'-'.$birth_day;
		$this_year_birth_date_timestamp = strtotime($this_year_birth_date);
		
		$years_old = $current_year - $birth_year;
		
		if($now < $this_year_birth_date_timestamp)
		{
			/* his/her birthday hasn't yet arrived this year */
			$years_old = $years_old - 1;
		}
		
		( $years_old > 1) ? $years_old .= ' ' . JText::_( 'AUP_YEARS' ) : $years_old .= ' ' .JText::_( 'AUP_YEAR' );
		
		return $years_old;
	}
	
	function _getIconCategoryRule( $category )
	{
		$icon = ( $category!='' ) ? '<img src="'.JURI::root() . 'components/com_alphauserpoints/assets/images/categories/'.$category.'.gif" alt="" align="absmiddle" />' : '';
		return $icon;
	}
	
	function _getProgressProfile( $userinfo, $type )
	{
		// $type = bar or message
		$message = '';
		$progressprofile = '';
		$total = 10; // 10% by default on registration with name, username, password, email etc...		
		// check User infos
		if ( $userinfo->job!='' && $userinfo->education!='' && $userinfo->graduationyear ){
			$total = $total + 10;
		} else $message = 'education';		
		if ( $userinfo->city!='' ){
			$total = $total + 10;
		} else $message = 'location';
		if ( $userinfo->aboutme!='' ){
			$total = $total + 10; 
		} else $message = 'aboutme';
		if ( $userinfo->gender>0 ){
			$total = $total + 10;
		} else $message = 'gender';
		if ( $userinfo->birthdate!='0000-00-00' ){
			$total = $total + 10;
		} else $message = 'birthdate';
		if ( $userinfo->avatar!='' ) {
			$total = $total + 40;
		} else $message = 'avatar';
		
		//$barprogressprofile = ( $total<100 ) ? '<img src="'.JURI::base(true).'/components/com_alphauserpoints/assets/images/percent_'.$total.'.png" alt="" />' : '';		
		$barprogressprofile = ( $total<100 ) ? '<div class="progress progress-striped active"><div class="bar" style="width: '. $total.'%;"></div></div>' : '';
		
		switch ( $message ) {
			case 'avatar':
				$messageprogessbar = sprintf( JText::_( 'AUP_ADD_A_PHOTO_OR_AVATAR' ), ($total + 40).'%' ) ;
				break;
			case 'birthdate':
				$messageprogessbar = sprintf( JText::_( 'AUP_FILL_IN_YOUR_BIRTHDATE' ), ($total + 10).'%' ) ;
				break;
			case 'gender':
				$messageprogessbar = sprintf( JText::_( 'AUP_FILL_IN_YOUR_GENDER' ), ($total + 10).'%' ) ;
				break;
			case 'aboutme':
				$messageprogessbar = sprintf( JText::_( 'AUP_FILL_IN_THE_DESCRIPTION_ABOUT_YOU' ), ($total + 10).'%' ) ;
				break;
			case 'location':
				$messageprogessbar = sprintf( JText::_( 'AUP_FILL_IN_YOUR_LOCATION' ), ($total + 10).'%' ) ;
				break;
			case 'education':
				$messageprogessbar = sprintf( JText::_( 'AUP_FILL_OUT_YOUR_EDUCATION_AND_JOB' ), ($total + 10).'%' ) ;
				break;				
			default:		
		}
		
		if ( $type == 'bar' && $barprogressprofile ) {
			$progressprofile = $barprogressprofile . '<span class="small">' . sprintf( JText::_( 'AUP_PROFILE_IS_X_COMPLETE' ), '<b>' . $total .'%</b>' ) . '</span>';			
		} elseif ( $type == 'message' && $barprogressprofile )  {
			$progressprofile = sprintf( JText::_( 'AUP_YOUR_PROFILE_IS_X_COMPLETE' ), $total .'%' ) . $messageprogessbar ;
		}
		
		return $progressprofile;
	}
	
	function _updateProfileViews ( $referreid ) 
	{		
		$db	   = JFactory::getDBO();
		$query = "UPDATE #__alpha_userpoints SET profileviews = profileviews + 1 WHERE `referreid`='" . $referreid . "'";
		$db->setQuery( $query );
		$db->query();
	}
	
	/*
	function _getCountryList($selected='')
	{
	$countrylist='';
	$country_list = array(
			"Afghanistan",
			"Albania",
			"Algeria",
			"Andorra",
			"Angola",
			"Antigua and Barbuda",
			"Argentina",
			"Armenia",
			"Australia",
			"Austria",
			"Azerbaijan",
			"Bahamas",
			"Bahrain",
			"Bangladesh",
			"Barbados",
			"Belarus",
			"Belgium",
			"Belize",
			"Benin",
			"Bhutan",
			"Bolivia",
			"Bosnia and Herzegovina",
			"Botswana",
			"Brazil",
			"Brunei",
			"Bulgaria",
			"Burkina Faso",
			"Burundi",
			"Cambodia",
			"Cameroon",
			"Canada",
			"Cape Verde",
			"Central African Republic",
			"Chad",
			"Chile",
			"China",
			"Colombi",
			"Comoros",
			"Congo (Brazzaville)",
			"Congo",
			"Costa Rica",
			"Cote d'Ivoire",
			"Croatia",
			"Cuba",
			"Cyprus",
			"Czech Republic",
			"Denmark",
			"Djibouti",
			"Dominica",
			"Dominican Republic",
			"East Timor (Timor Timur)",
			"Ecuador",
			"Egypt",
			"El Salvador",
			"Equatorial Guinea",
			"Eritrea",
			"Estonia",
			"Ethiopia",
			"Fiji",
			"Finland",
			"France",
			"Gabon",
			"Gambia, The",
			"Georgia",
			"Germany",
			"Ghana",
			"Greece",
			"Grenada",
			"Guatemala",
			"Guinea",
			"Guinea-Bissau",
			"Guyana",
			"Haiti",
			"Honduras",
			"Hungary",
			"Iceland",
			"India",
			"Indonesia",
			"Iran",
			"Iraq",
			"Ireland",
			"Israel",
			"Italy",
			"Jamaica",
			"Japan",
			"Jordan",
			"Kazakhstan",
			"Kenya",
			"Kiribati",
			"Korea, North",
			"Korea, South",
			"Kuwait",
			"Kyrgyzstan",
			"Laos",
			"Latvia",
			"Lebanon",
			"Lesotho",
			"Liberia",
			"Libya",
			"Liechtenstein",
			"Lithuania",
			"Luxembourg",
			"Macedonia",
			"Madagascar",
			"Malawi",
			"Malaysia",
			"Maldives",
			"Mali",
			"Malta",
			"Marshall Islands",
			"Mauritania",
			"Mauritius",
			"Mexico",
			"Micronesia",
			"Moldova",
			"Monaco",
			"Mongolia",
			"Morocco",
			"Mozambique",
			"Myanmar",
			"Namibia",
			"Nauru",
			"Nepa",
			"Netherlands",
			"New Zealand",
			"Nicaragua",
			"Niger",
			"Nigeria",
			"Norway",
			"Oman",
			"Pakistan",
			"Palau",
			"Panama",
			"Papua New Guinea",
			"Paraguay",
			"Peru",
			"Philippines",
			"Poland",
			"Portugal",
			"Qatar",
			"Romania",
			"Russia",
			"Rwanda",
			"Saint Kitts and Nevis",
			"Saint Lucia",
			"Saint Vincent",
			"Samoa",
			"San Marino",
			"Sao Tome and Principe",
			"Saudi Arabia",
			"Senegal",
			"Serbia and Montenegro",
			"Seychelles",
			"Sierra Leone",
			"Singapore",
			"Slovakia",
			"Slovenia",
			"Solomon Islands",
			"Somalia",
			"South Africa",
			"Spain",
			"Sri Lanka",
			"Sudan",
			"Suriname",
			"Swaziland",
			"Sweden",
			"Switzerland",
			"Syria",
			"Taiwan",
			"Tajikistan",
			"Tanzania",
			"Thailand",
			"Togo",
			"Tonga",
			"Trinidad and Tobago",
			"Tunisia",
			"Turkey",
			"Turkmenistan",
			"Tuvalu",
			"Uganda",
			"Ukraine",
			"United Arab Emirates",
			"United Kingdom",
			"United States Of America",
			"Uruguay",
			"Uzbekistan",
			"Vanuatu",
			"Vatican City",
			"Venezuela",
			"Vietnam",
			"Virgin Islands (British)",
			"Virgin Islands (US)",
			"Wallis and Futuna Islands",
			"Western Sahara",
			"Yemen",
			"Zaire",
			"Zambia",
			"Zimbabwe"
		);
		
		$n = count($country_list)-1;
		
		$options[] = JHTML::_( 'select.option', '', JText::_( 'AUP_SELECTCOUNTRY' ) );
		
		for ($i=0, $n=(count( $country_list )-1); $i < $n; $i++) {
			$options[] = JHTML::_( 'select.option', $country_list[$i], $country_list[$i] );		
		}
		$countrylist = JHTML::_('select.genericlist', $options, 'country', 'class="inputbox" size="1"', 'value', 'text', $selected );
			
		return $countrylist;
	}
	*/
	
	function _getCountryList($selected='')
	{
	$countrylist='';
	$country_list = explode('|', JText::_( 'AUP_COUNTRIES' ));
		
		$n = count($country_list)-1;
		
		$options[] = JHTML::_( 'select.option', '', JText::_( 'AUP_SELECTCOUNTRY' ) );
		
		for ($i=0, $n=(count( $country_list )-1); $i < $n; $i++) {
			$options[] = JHTML::_( 'select.option', $country_list[$i], $country_list[$i] );		
		}
		$countrylist = JHTML::_('select.genericlist', $options, 'country', 'class="inputbox" size="1"', 'value', 'text', $selected );
			
		return $countrylist;
	}

	
	
	function _get_average_age_community() {
	
		$db = JFactory::getDBO();	
		
		$avarage_age	= 0;
		
		$query = "SELECT AVG((FLOOR(( TO_DAYS(NOW()) - TO_DAYS(birthdate))/365))) FROM #__alpha_userpoints WHERE birthdate!='0000-00-00' AND blocked='0'";
		$db->setQuery( $query );
		$avarage_age = round($db->loadResult());
		
		return $avarage_age;

	}
	
	function isIE () {
		$document = JFactory::getDocument();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/MSIE/i', $user_agent)) {
			$juribase = str_replace('/administrator', '', JURI::base());
			$document->addScript($juribase.'components/com_alphauserpoints/assets/js/html5.js');
		}
	}
	
	 function countryCityFromIP($ipAddr)
	 {
		 //function to find country and city from IP address
		 //Developed by Roshan Bhattarai http://roshanbh.com.np
		 
		 // USAGE :
		 /*
		 	$IPDetail=countryCityFromIP('12.215.42.19');
 			echo $IPDetail['country']; //country of that IP address
		 	echo $IPDetail['city']; //outputs the IP detail of the city 
		 */
		
		 //verify the IP address for the
		 ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ?  trigger_error("Invalid IP", E_USER_ERROR) : "";
		 $ipDetail=array(); //initialize a blank array
		
		 //get the XML result from hostip.info
		 $xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
		
		 //get the city name inside the node <gml:name> and </gml:name>
		 preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
		
		 //assing the city name to the array
		 $ipDetail['city']=$match[2]; 
		
		 //get the country name inside the node <countryName> and </countryName>
		 preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
		
		 //assign the country name to the $ipDetail array
		 $ipDetail['country']=$matches[1];
		
		 //get the country name inside the node <countryName> and </countryName>
		 preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
		 $ipDetail['country_code']=$cc_match[1]; //assing the country code to array
		
		 //return the array containing city, country and country code
		 return $ipDetail;
	
	 } 
	 
	
	 function getFormattedPoints( $points ){
	 
		// get params definitions
		$params = JComponentHelper::getParams( 'com_alphauserpoints' );		
		$formatPoints = $params->get( 'formatPoints', 0 );
		
		switch( $formatPoints ){
			case "1":
				$fpoints = number_format($points, 2, '.', ',');
				break;
			case "2":
				$fpoints = number_format($points, 2, ',', '');
				break;
			case "3":
				$fpoints = number_format($points, 2, ',', ' ');
				break;
			case "4":
				$fpoints = number_format($points, 0);
				break;
			case "5":
				$fpoints = number_format($points, 0, '', ' ');
				break;
			case "6":
				$fpoints = number_format($points, 0, '', ',');
				break;
			case "7":				
				$fpoints = number_format(floor($points), 0);
				break;	
			case "8":
				$fpoints = number_format(floor($points), 0, '', ' ');
				break;
			case "9":
				$fpoints = number_format(floor($points), 0, '', ',');
				break;		
			case "0":
			default:
				$fpoints = $points; 
		}		
	
	 	return $fpoints;
		
	 }
	 
	 
class myImage extends JImage
{
    function __construct() { }

    public static function getPathImage($avatar)
    {
        return $avatar[0]->path;
    }
	
	public static function getLivePathImage($avatar)
	{
		$path = $avatar[0]->path;
		$livePathAvatar = str_replace('\\', '/',$path);
		$pos = strpos($livePathAvatar, 'components');	
		$livePathAvatar = substr_replace($livePathAvatar, JURI::base(), 0, $pos);
		
		return $livePathAvatar;
	}
}

?>