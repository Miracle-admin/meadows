<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
$user 			= JFactory::getUser();
$db 			= JFactory::getDBO();
$doc 			= JFactory::getDocument();
$app			= JFactory::getApplication();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$vendor_store_desc 			= $this->vendor_data[0];
$vendor_terms_of_service	= $this->vendor_data[1];
$vendor_legal_info			= $this->vendor_data[2];	
$vendor_store_name			= ucfirst($this->vendor_data[3]);
$vendor_phone				= $this->vendor_data[4];
$vendor_url					= $this->vendor_data[5];
$vendor_id 					= $this->vendor_data[6];
$vendor_thumb 				= $this->vendor_thumb;
$cparams 				= JComponentHelper::getParams('com_vmvendor');
$profileman 			= $cparams->get('profileman');

$paypalemail_field      = $cparams->get('paypalemail_field',1);
$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
$profiletypes_ids		= $cparams->get('profiletypes_ids');
if( $cparams->get('load_bootstrap_css',1) )
	$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/bootstrap.min.css');

	
if (!class_exists( 'VmConfig' ))
	require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php');	
$use_as_catalog 	=  VmConfig::get('use_as_catalog');


//if($allowed){
	?>
	<h1><?php echo JText::_('COM_VMVENDOR_EDITPRO_EDITYOURPROF');
	
	// 
	 ?></h1>
	
	
    <form  method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate form-horizontal"  action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=editprofile&Itemid='.$app->input->get('Itemid') ) ?>" >
    <div class="form-actions " style="text-align:right">
	<button type="submit"  class="btn btn-primary validate" onclick="Joomla.submitbutton('editprofile.save')" >
	 	<i class="vmv-icon-ok"></i> <?php  echo JText::_('COM_VMVENDOR_EDITPRO_SUBMIT');   ?>
    </button>
    
	 <button type="button" class="btn" onclick="Joomla.submitbutton('editprofile.cancel')">
                    <i class="vmv-icon-cancel"></i>&#160;<?php echo JText::_('JCANCEL') ?>
                </button>
                
             
	
    </div>
    	 <fieldset name="mainfields">
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_title') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_title') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_image') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_image');
					
					if(!$vendor_thumb)
					$vendor_thumb = 'components/com_vmvendor/assets/img/noimage.gif'; 
					?>
   				 <img src="<?php echo $juri.$vendor_thumb;   ?>" height="25" style="vertical-align:middle;"/>
                </div>
            </div>
            <?php 

if(!$use_as_catalog){   ?>
    		<div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_telephone') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_telephone') ?>
                </div>
            </div>
            
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_url') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_url') ?>
                </div>
            </div>
            <?php }   ?>
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_store_desc') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_store_desc') ?>
                </div>
            </div>
            <?php 

if(!$use_as_catalog){   ?>
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_terms_of_service') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_terms_of_service') ?>
                </div>
            </div>
            
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_legal_info') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_legal_info') ?>
                </div>
            </div>
   <?php   } ?>
       <?php
       if($paypalemail_field)
       {  ?>
        <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('paypal_email') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('paypal_email') ?>
                </div>
            </div>
        <?php  } ?>
        
         </fieldset>
    
    
   
    
    
    
    
    
    
    
    
    
	<?php
	if($profileman=='js' || $profileman=='es'){
		?>
	<div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('activity_stream') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('activity_stream') ?>
                </div>
            </div>
            
	<?php } ?>
    
    
    <div class="form-actions">
	<?php echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability ?>
	 <input type="hidden" name="option" value="com_vmvendor" />
     <input type="hidden" name="controller" value="editprofile" />
	<input type="hidden" name="task" value="editprofile.save" />
    
	<button type="submit"  class="btn btn-primary validate" onclick="Joomla.submitbutton('editprofile.save')" >
	 	<i class="vmv-icon-ok"></i> <?php  echo JText::_('COM_VMVENDOR_EDITPRO_SUBMIT');   ?>
    </button>
    
	 <button type="button" class="btn" onclick="Joomla.submitbutton('editprofile.cancel')">
                    <i class="vmv-icon-cancel"></i>&#160;<?php echo JText::_('JCANCEL') ?>
                </button>
    </div>
	</form>