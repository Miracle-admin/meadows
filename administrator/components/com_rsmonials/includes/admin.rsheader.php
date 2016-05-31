<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
isset($_REQUEST['view'])?$view=$_REQUEST['view']:$view='';
if($view == '') { $view='testi'; }
isset($_REQUEST['action'])?$action=$_REQUEST['action']:$action = '';
?>
<div id="j-sidebar-container" class="span2">
	<div id="sidebar">
    	<div class="sidebar-nav">
        	<ul id="submenu" class="nav nav-list">
				<li class="nav-header">RSMonials Menu</li>
                <li <?php if(($view=='testi')||($view=='')){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=testi">Testimonials</a></li>
				<li <?php if($view=='conf'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=conf">Settings</a></li>
                <li <?php if($view=='style'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=style">Display Style</a></li>
				<li <?php if($view=='css'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=css">CSS File</a></li>
				<li <?php if($view=='lang'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=lang">Language File</a></li>
                <li <?php if($view=='spam'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=spam">Spam Testimonials</a></li>
				<li <?php if($view=='doc'){?>class="active"<?php }?>><a href="index.php?option=com_rsmonials&view=doc">Documentation</a></li>
                <li class="divider"></li>
                <li><img src="components/com_rsmonials/images/rsmonials.png" alt="www.rswebsols.com"  /></li>
                <li>RSMonials Version <?php echo RSWEBSOLS_EXTENSION_VERSION; ?></li>
            </ul>
        </div>
    </div>
</div>
<div id="j-main-container" class="span10">
<?php
isset($_REQUEST['result'])?$rswsres=intval($_REQUEST['result']):$rswsres=0;
if($rswsres > 0) {
?>
<div class="alert"><?php echo returnMsg($rswsres); ?></div>
<?php
}
$rsws_h1 = 'RSMonials :: Dashboard';
if($view == 'testi' && ($action == 'add' || $action == 'edit')) { $rsws_h1 = 'RSMonials :: '.ucfirst(strtolower($action)).' Testimonial'; }
elseif($view == 'testi') { $rsws_h1 = 'RSMonials :: TESTIMONIALS'; }
elseif($view == 'conf' && $action == 'editall') { $rsws_h1 = 'RSMonials :: Edit All Settings'; }
elseif($view == 'conf' && $action == 'edit') { $rsws_h1 = 'RSMonials :: Edit Setting'; }
elseif($view == 'conf') { $rsws_h1 = 'RSMonials :: SETTINGS'; }
elseif($view == 'style' && $action == 'editall') { $rsws_h1 = 'RSMonials :: Edit All Display Style Settings'; }
elseif($view == 'style' && $action == 'edit') { $rsws_h1 = 'RSMonials :: Edit Display Style Setting'; }
elseif($view == 'style') { $rsws_h1 = 'RSMonials :: DISPLAY STYLE'; }
elseif($view == 'css' && $action == 'edit') { $rsws_h1 = 'RSMonials :: Edit CSS File'; }
elseif($view == 'css') { $rsws_h1 = 'RSMonials :: CSS FILE'; }
elseif($view == 'lang' && $action == 'edit') { $rsws_h1 = 'RSMonials :: Edit Language File'; }
elseif($view == 'lang') { $rsws_h1 = 'RSMonials :: LANGUAGE FILE'; }

elseif($view == 'spam' && $action == 'edit') { $rsws_h1 = 'RSMonials :: Edit Spam Testimonial'; }
elseif($view == 'spam') { $rsws_h1 = 'RSMonials :: SPAM TESTIMONIALS'; }

elseif($view == 'doc') { $rsws_h1 = 'RSMonials :: DOCUMENTATION'; }
?>
<h1><?php echo $rsws_h1; ?></h1>
<hr />