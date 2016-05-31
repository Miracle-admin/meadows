<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableqrcodetrack extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var int */
	var $couponid = '';
	/** @var string */
	var $trackid = '';
	/** @var date */
	var $trackdate = '';
	/** @var string */
	var $country = '';
	var $city = '';
	var $device = '';
	var $ip = '';
	/** @var int */
	var $confirmed = '';
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_qrcodetrack', 'id', $db);
	}	

}
?>
