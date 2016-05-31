<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
$user 			= JFactory::getUser();
$db 			= JFactory::getDBO();
$doc 			= JFactory::getDocument();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$app 			= JFactory::getApplication();
if (!class_exists( 'VmConfig' ))
	require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
VmConfig::loadConfig();
$cparams 			= JComponentHelper::getParams('com_vmvendor');
$tax_mode 			= $cparams->get('tax_mode',0);
$taxid = $app->input->getInt('taxid');
$tax_id 			= $this->taxdata[0];
$tax_name 			= $this->taxdata[1];
$tax_descr 			= $this->taxdata[2];
$tax_kind 			= $this->taxdata[3];
$tax_mathop 		= $this->taxdata[4];
$tax_value 			= $this->taxdata[5];
$tax_currency 		= $this->taxdata[6];
$tax_publish_up 	= $this->taxdata[7];
$tax_publish_down 	= $this->taxdata[8];
$tax_cats			= $this->tax_cats;
$vendor_shoppergroups = $this->vendor_shoppergroups;
$virtuemart_vendor_id = $this->virtuemart_vendor_id;
	if ($taxid !='' && $tax_name !='')
		echo '<h1>'.JText::_('COM_VMVENDOR_VMVENEDITTAX_FORM_TITLE_EDITION').'</h1>';
	else
		echo '<h1>'.JText::_('COM_VMVENDOR_VMVENEDITTAX_FORM_TITLE_CREATION').'</h1>';
	
	if (!class_exists( 'VmConfig' ))
		require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
	
	echo '<div class="well"><i class="vmv-icon-info-sign"></i> '.JText::_('COM_VMVENDOR_EDITTAX_FORM_TAXEDITION_NOTICE').'</div>';
?>
    <form  method="post" name="adminForm" id="adminForm"  class="form-validate form-horizontal"  action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=edittax&Itemid='.$app->input->get('Itemid') ) ?>" >
    	 <fieldset name="mainfields">
         <?php  if ($taxid !='' && $tax_name !=''){  ?>
         <div class="control-group">
                <div class="control-label">
			<?php	//echo  '<label>'.JText::_('COM_VMVENDOR_EDITTAX_FORM_TAXID').'</label>' ?>
				</div>
                 <div class="controls">
			<?php 	if 	($tax_id!=''){ 
					//echo $tax_id;
					echo '<input type="hidden" name="calc_id" value="'.$tax_id.'" />';
				}  
				 ?>
                 </div>
                 </div>
                 
		<?php } ?>	
	
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('calc_name') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('calc_name') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('calc_descr') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('calc_descr') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                <label><?php  echo $this->form->getLabel('calc_kind') ?> </label>
                    
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('calc_kind'); ?>      
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('taxcatselect') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('taxcatselect') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('calc_mathop') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('calc_mathop') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('calc_value') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('calc_value') ?>
                </div>
            </div>
	
    <?php
				$tax_shoppergroups = array();
				for($i=0; $i < count($vendor_shoppergroups) ; $i++ ){
					array_push($tax_shoppergroups , $vendor_shoppergroups[$i] ); 
				}
				
				echo '<div class="form-actions">';
				echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability 
				echo ' <input type="hidden" id="calc_shoppergroups"  name="calc_shoppergroups" value="'.implode(',',$tax_shoppergroups).'" />
				<input type="hidden" id="calc_currency" name="calc_currency" value="'.$tax_currency.'" />
                <input type="hidden" id="calc_vendor_id" name="calc_vendor_id" value="'.$virtuemart_vendor_id.'" />';
				if ($taxid !='' && $tax_name !=''){
				?>
		
					<button type="submit" class="btn btn-primary btn-large validate" onclick="Joomla.submitbutton(\'edittax.save\')">
                    <i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_EDITTAX_FORM_TAXEDITBUTTON') ?></button>
					
				<?php 	
				}
			
				else{  ?>
	
					<button type="submit" class="btn btn-primary btn-large  validate" onclick="Joomla.submitbutton(\'edittax.save\')">
                    <i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_EDITTAX_FORM_TAXADDBUTTON') ?></button>
				<?php 	
				}
				?>
			<button type="button" class="btn btn-large" onclick="history.go(-1)" >
                    <i class="vmv-icon-cancel"></i> <?php echo JText::_('JCANCEL') ?></button>
			</div>
				
	<input type="hidden" name="option" value="com_vmvendor" />
	<input type="hidden" name="controller" value="edittax" />
    <input type="hidden" name="task" value="edittax.save" />	
    </fieldset>	
</form>