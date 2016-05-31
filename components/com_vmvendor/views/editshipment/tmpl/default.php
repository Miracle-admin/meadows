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
{
require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
}
VmConfig::loadConfig();
$cparams 			= JComponentHelper::getParams('com_vmvendor');
$shipment_mode 			= $cparams->get('shipment_mode',0);
$shipmentid = $app->input->get('shipmentid','','int');

$shipment_id 			= $this->shipmentdata[0];
$shipment_name 			= $this->shipmentdata[1];
$shipment_descr 		= $this->shipmentdata[2];

$vendor_shoppergroups = $this->vendor_shoppergroups;
$virtuemart_vendor_id = $this->virtuemart_vendor_id;
	if ($shipmentid !='' && $shipment_name !='')
		echo '<h1>'.JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_TITLE_EDITION').'</h1>';
	else
		echo '<h1>'.JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_TITLE_CREATION').'</h1>';
	
	if (!class_exists( 'VmConfig' ))
		require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php');
	
	echo '<div class="well"><i class="vmv-icon-info-sign"></i> '.JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_TAXEDITION_NOTICE').'</div>';

    $virtuemart_shipmentmethod_id   = $app->input->get('virtuemart_shipmentmethod_id','','int');
    $jpluginid                    = $app->input->get('jpluginid','','int');

	//$form_taxid			= JRequest::getVar('form_taxname');
	
	?>
    <form  method="post" name="adminForm" id="adminForm"  class="form-validate form-horizontal"  action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=editshipment&Itemid='.$app->input->get('Itemid') ) ?>" >
    	 <fieldset name="mainfields">
         <?php  if ($virtuemart_shipmentmethod_id !='' && $shipment_name !=''){  ?>
         <div class="control-group">
                <div class="control-label">
			<?php	//echo  '<label>'.JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_TAXID').'</label>' ?>
				</div>
                 <div class="controls">
			<?php 	if 	($virtuemart_shipmentmethod_id!=''){ 
					//echo $tax_id;
					echo '<input type="hidden" name="virtuemart_shipmentmethod_id" value="'.$virtuemart_shipmentmethod_id.'" />';
				}  
				 ?>
                 </div>
                 </div>
                 
		<?php } ?>	
	
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('shipment_name') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('shipment_name') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('shipment_desc') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('shipment_desc') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                	<?php  echo $this->form->getLabel('shipment_published') ?>               
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('shipment_published'); ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('show_on_pdetails') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('show_on_pdetails') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('multicountries') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('multicountries') ?>
                </div>
            </div>
             <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('ziprange_start') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('ziprange_start') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('ziprange_end') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('ziprange_end') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('lowest_weight') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('lowest_weight') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('highest_weight') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('highest_weight') ?>
                </div>
            </div>
              <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('weightunit') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('weightunit') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('minimum_orderproducts') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('minimum_orderproducts') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('maximum_orderproducts') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('maximum_orderproducts') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('minimum_orderamount') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('minimum_orderamount') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('maximum_orderamount') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('maximum_orderamount') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('shipment_cost') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('shipment_cost') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('package_fee') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('package_fee') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('shipmenttaxrules') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('shipmenttaxrules') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('freeshipment_amount') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('freeshipment_amount') ?>
                </div>
            </div>
    <?php
				$shipment_shoppergroups = array();
				for($i=0; $i < count($vendor_shoppergroups) ; $i++ ){
					array_push($shipment_shoppergroups , $vendor_shoppergroups[$i] ); 
				}
				
				echo '<div class="form-actions">';
				 echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability 
				//??
                echo ' <input type="hidden" id="shipment_shoppergroups"  name="shipment_shoppergroups" value="'.implode(',',$shipment_shoppergroups).'" />
                <input type="hidden" id="jpluginid"  name="jpluginid" value="'.$jpluginid.'" />
                <input type="hidden" id="shipmentid"  name="shipmentid" value="'.$shipmentid.'" />
                <input type="hidden" id="ship_vendor_id"  name="ship_vendor_id" value="'.$virtuemart_vendor_id.'" />';
				if ($shipmentid !='' && $shipment_name !='')
                {?>
		
					<button type="submit" class="btn btn-large btn-primary validate" onclick="Joomla.submitbutton(\'edittax.save\')">
                    <i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_SHIPMENTEDITBUTTON') ?></button>
					
				<?php 	
				}
			
				else{  ?>
	
					<button type="submit" class="btn btn-large btn-primary validate" onclick="Joomla.submitbutton(\'edittax.save\')">
                    <i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_SHIPMENTADDBUTTON') ?></button>
				<?php 	
				}
				?>
			<button type="button" class="btn btn-large" onclick="history.go(-1)" >
                    <i class="vmv-icon-cancel"></i> <?php echo JText::_('JCANCEL') ?></button>
			</div>
				
	<input type="hidden" name="option"     value="com_vmvendor" />
	<input type="hidden" name="controller" value="editshipment" />
    <input type="hidden" name="task"       value="editshipment.save" />	
    </fieldset>	
</form>