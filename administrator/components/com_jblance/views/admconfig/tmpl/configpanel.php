<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/configpanel.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $link_dashboard 	= JRoute::_('index.php?option=com_jblance');
 $link_compsetting	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=config');
 $link_usergroup	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showusergroup');
 $link_plan			= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showplan');
 $link_paymode		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showpaymode');
 $link_customfield	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcustomfield');
 $link_emailtemp	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor=subscr-pending');
 $link_category		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcategory');
 $link_budget		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showbudget');
 $link_duration		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showduration');
 $link_location		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showlocation');
 $link_planBenefits	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showplanbenefits');
 $link_optimise		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=optimise');
?>
<div id="j-sidebar-container" class="span2">
	<?php include_once(JPATH_COMPONENT.'/views/configmenu.php'); ?>
</div>
<div id="j-main-container" class="span10">
	<div class="alert alert-info">
		<h4><?php echo JText::_('COM_JBLANCE_CONFIG');?></h4>
		<p><?php echo JText::_('COM_JBLANCE_CONFIG_DESC');?></p>
	</div>
	<div class="well well-small" id="cpanel">
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_dashboard; ?>" title="<?php echo JText::_('COM_JBLANCE_JOOMBRI_DASHBOARD');?>"><img src="components/com_jblance/images/dashboard.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_JOOMBRI_DASHBOARD'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_compsetting; ?>" title="<?php echo JText::_('COM_JBLANCE_COMPONENT_SETTINGS');?>"><img src="components/com_jblance/images/component.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_COMPONENT_SETTINGS'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_usergroup; ?>" title="<?php echo JText::_('COM_JBLANCE_USER_GROUPS');?>"><img src="components/com_jblance/images/usergroup.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_USER_GROUPS'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_plan; ?>" title="<?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PLANS');?>"><img src="components/com_jblance/images/plan.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PLANS'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_paymode; ?>" title="<?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAYS');?>"><img src="components/com_jblance/images/paymode.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAYS'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_customfield; ?>" title="<?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS');?>"><img src="components/com_jblance/images/customfield.png" align="middle" border="0" width="48" alt="" /><span><?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_emailtemp; ?>" title="<?php echo JText::_('COM_JBLANCE_EMAIL_TEMPLATES');?>"><img src="components/com_jblance/images/emailtemp.png" align="middle" border="0" width="48" alt="" /><span><?php echo JText::_('COM_JBLANCE_EMAIL_TEMPLATES'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_category; ?>" title="<?php echo JText::_('COM_JBLANCE_CATEGORIES');?>"><img src="components/com_jblance/images/category.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_CATEGORIES'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_budget; ?>" title="<?php echo JText::_('COM_JBLANCE_BUDGET_RANGE');?>"><img src="components/com_jblance/images/budget.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_duration; ?>" title="<?php echo JText::_('COM_JBLANCE_TOOLBAR_PROJECT_DURATION');?>"><img src="components/com_jblance/images/duration.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TOOLBAR_PROJECT_DURATION'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_location; ?>" title="<?php echo JText::_('COM_JBLANCE_LOCATION');?>"><img src="components/com_jblance/images/location.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_LOCATION'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_planBenefits; ?>" title="<?php echo JText::_('Plan benefits');?>"><img style="width: 50px;" src="components/com_jblance/images/pb.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('Plan benefits'); ?></span></a></div>
		</div>
		<div class="jbicon-container">
			<div class="jbicon"> <a href="<?php echo $link_optimise; ?>" title="<?php echo JText::_('COM_JBLANCE_OPTIMISE_DATABASE');?>"><img src="components/com_jblance/images/optimise.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_OPTIMISE_DATABASE'); ?></span></a></div>
		</div>
	</div>
</div>