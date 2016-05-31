<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableCoupons extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var string */
	var $description = '';
	/** @var string */
	var $couponcode = '';
	/** @var int */
	var $points = '';
	/** @var datetime */
	var $expires = '';
	/** @var int */
	var $public = '1';
    var $category = '1';
    var $printable = '0';
  
  	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_coupons', 'id', $db);
	}
}
?>
