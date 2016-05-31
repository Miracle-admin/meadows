<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=emailtemplate');?>" method="post" name="adminForm" id="item-form">

	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EMAIL_DETAILS'); ?></legend>
		<center>
		<TABLE class="admintable" align=center border=0>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_NAME'); ?> :</TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "email_name"
						id			= "email_name"
						value		= '<?php echo $this->item->email_name?>'
						size		= 32
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_TYPE'); ?> :</TD>
				<TD nowrap colspan=2 align=left>
					<select id = "email_type" name = "email_type">
						<option <?php echo $this->item->email_type=='New Company Notification Email'? "selected" : ""?> value='New Company Notification Email'><?php echo JText::_('LNG_NEW_COMPANY_NOTIFICATION_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Listing Creation Notification'? "selected" : ""?> value='Listing Creation Notification'><?php echo JText::_('LNG_LISTING_CREATION_NOTIFICATION_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Approve Email'? "selected" : ""?> value='Approve Email'><?php echo JText::_('LNG_APPROVE_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Claim Response Email'? "selected" : ""?> value='Claim Response Email'><?php echo JText::_('LNG_CLAIM_RESPONSE_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Claim Negative Response Email'? "selected" : ""?> value='Claim Negative Response Email'><?php echo JText::_('LNG_CLAIM_NEGATIVE_RESPONSE_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Contact Email'? "selected" : ""?> value='Contact Email'><?php echo JText::_('LNG_CONTACT_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Request Quote Email'? "selected" : ""?> value='Request Quote Email'><?php echo JText::_('LNG_REQUEST_QUOTE'); ?></option>
						<option <?php echo $this->item->email_type=='Order Email'? "selected" : ""?> value='Order Email'><?php echo JText::_('LNG_ORDER_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Payment Details Email'? "selected" : ""?> value='Payment Details Email'><?php echo JText::_('LNG_PAYMENT_DETAILS_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Expiration Notification Email'? "selected" : ""?> value='Expiration Notification Email'><?php echo JText::_('LNG_EXPIRATION_NOTIFICATION_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Review Email'? "selected" : ""?> value='Review Email'><?php echo JText::_('LNG_REVIEW_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Review Response Email'? "selected" : ""?> value='Review Response Email'><?php echo JText::_('LNG_REVIEW_RESPONSE_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Report Abuse Email'? "selected" : ""?> value='Report Abuse Email'><?php echo JText::_('LNG_REPORT_ABUSE_EMAIL'); ?></option>
						<option <?php echo $this->item->email_type=='Offer Creation Notification'? "selected" : ""?> value='Offer Creation Notification'><?php echo JText::_('LNG_OFFER_CREATION_NOTIFICATION'); ?></option>
						<option <?php echo $this->item->email_type=='Offer Approval Notification'? "selected" : ""?> value='Offer Approval Notification'><?php echo JText::_('LNG_OFFER_APPROVAL_NOTIFICATION'); ?></option>
						<option <?php echo $this->item->email_type=='Event Creation Notification'? "selected" : ""?> value='Event Creation Notification'><?php echo JText::_('LNG_EVENT_CREATION_NOTIFICATION'); ?></option>
						<option <?php echo $this->item->email_type=='Event Approval Notification'? "selected" : ""?> value='Event Approval Notification'><?php echo JText::_('LNG_EVENT_APPROVAL_NOTIFICATION'); ?></option>
						</select>
				</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_SUBJECT'); ?> :</TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "email_subject"
						id			= "email_subject"
						value		= '<?php echo $this->item->email_subject?>'
						size		= 32
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD width=10% nowrap class="key"> <?php echo JText::_('LNG_CONTENT'); ?> :</TD>
				<TD nowrap colspan=2 ALIGN=LEFT>
					
					<!-- textarea style="clear:both;width:700px"  id='email_content' name='email_content' rows=20 cols=140><?php echo $this->item->email_content?></textarea-->
					<?php
						$editor = JFactory::getEditor();
						echo $editor->display('email_content', $this->item->email_content, '750', '400', '60', '20', false);
					?>
					
				</TD>
			</TR>
		</TABLE>
	</fieldset>
	
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="email_id" value="<?php echo $this->item->email_id ?>" />
	<input type="hidden" name="is_default" value="<?php echo $this->item->is_default?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>

<script>
	jQuery(document).ready(function()
	{

	})
</script>