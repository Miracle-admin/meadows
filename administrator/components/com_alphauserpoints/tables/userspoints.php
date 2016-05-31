<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableuserspoints extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var int */
	var $userid = '';
	/** @var string */
	var $referreid = '';
	/** @var string */
	var $upnid = '';
	/** @var string */
	var $points = '';
	/** @var string */
	var $max_points = '';
	/** @var datetime */
	var $last_update = '';
	/** @var string */
	var $referraluser = '';
	/** @var int */	
	var $referrees = '';
	/** @var int */
	var $blocked = '';	
	/** @var date */
	var $birthdate = '';
	/** @var string */
	var $avatar = '';	
	/** @var int */
	var $levelrank = '';
	/** @var date */
	var $leveldate = '';
	/** @var int */
	var $gender = '0';
	/** @var string */
	var $aboutme = '';
	var $website = '';
	var $phonehome = '';
	var $phonemobile = '';
	var $address = '';
	var $zipcode = '';
	var $city = '';
	var $country = '';
	var $education = '';
	var $graduationyear = '';
	var $job = '';
	var $facebook = '';
	var $twitter = '';
	var $icq = '';
	var $aim = '';
	var $yim = '';
	var $msn = '';
	var $skype = '';
	var $gtalk = '';
	var $xfire = '';
	/** @var int */
	var $profileviews = '';
	var $published = 1;
	var $shareinfos = 1;	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__alpha_userpoints', 'id', $db);
	}	

}
?>
