<?php
/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2015. All rights rserved
 * @license     GNU General Public License version 3 ; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Pointsactivity controller class.
 */
class VmvendorControllerPointsactivity extends JControllerForm
{

    function __construct() {
        $this->view_list = 'pointsactivities';
        parent::__construct();
    }

}