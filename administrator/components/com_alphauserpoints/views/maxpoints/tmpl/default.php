<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		//if (task == 'cpanel' || task == 'cancelrule' || document.formvalidator.isValid(document.id('maxpoints-form'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		//}
		//else {
			//alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		//}
	}
</script>
<form action="index.php?option=com_alphauserpoints" method="post" name="adminForm" id="adminForm" class="form-validate">
<div class="width-100 fltrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'AUP_MAXPOINTSPERUSER' ); ?></legend>
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>::<?php echo JText::_('AUP_MAXPOINTS'); ?>">
					<?php echo JText::_( 'AUP_MAXPOINTS' ); ?>:
				</span>
			</td>
			<td>
				<input class="inputbox" type="text" name="setpointsperuser" id="setpointsperuser" size="20" maxlength="30" value="<?php echo $this->setpoints; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">&nbsp;
			</td>
			<td>
				<?php echo JText::_('AUP_ZEROORBLANKFORUNLIMITED'); ?>
			</td>
		</tr>		
		</tbody>
		</table>
	</fieldset>
</div>
	<input type="hidden" name="option" value="com_alphauserpoints" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="redirect" value="rules" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<div class="clr"></div>