<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTabletemplate_invite extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null; 
    var $template_name = ''; 
	var $category = '';
    var $emailsubject = ''; 
    var $emailbody = ''; 
    var $emailformat = 1; 
    var $bcc2admin = 0; 
	var $published = 1;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_template_invite', 'id', $db);
	}	

}
?>
