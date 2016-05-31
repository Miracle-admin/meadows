<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user 			= JFactory::getUser();
$app			= JFactory::getApplication();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$doc 			= JFactory::getDocument();
$tmpl			= $app->input->get('tmpl');
$cparams 		= JComponentHelper::getParams('com_vmvendor');
$cat_suggest 	= $cparams->get('cat_suggest',1);
$profileman		= $cparams->get('profileman','0');
$virtuemart_vendor_id = $this->virtuemart_vendor_id;
if ($cat_suggest<=2)
	echo '<h4 class="modal-title">'.JText::_('COM_VMVENDOR_CATSUGGEST_SUGGESTFORM').'</h4>';
else
	echo '<h4 class="modal-title">'.JText::_('COM_VMVENDOR_CATSUGGEST_ADDITIONFORM').'</h4>';
$forbidcatids 	= $cparams->get('forbidcatids');	
$vmitemid 		= $cparams->get('vmitemid', '103');
$to 			= $cparams->get('to');			
	
echo '<div class="well well-small">';
echo '<i class="vmv-icon-info-sign"></i> '.JText::_('COM_VMVENDOR_CATTSUGGEST_DESC_'.$cat_suggest);
echo '</div>';		
?> 
<form method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=catsuggest&Itemid='.$app->input->get('Itemid') ); ?>">
<fieldset name="mainfields">
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('cat_name') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('cat_name') ?>
                </div>
            </div>
            
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('cat_descr') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('cat_descr') ?>
                </div>
            </div>
            
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('suggestcatselectlist') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('suggestcatselectlist') ?>
                </div>
            </div>
       </fieldset>
<?php
echo '<div class="form-actions">';
if($user->id >0)
{	
	echo '<button type="submit" class="btn btn-primary validate" onclick="Joomla.submitbutton(\'catsuggest.save\')">
	<i class="vmv-icon-plus"></i> ';	
	if ($cat_suggest==1)
	{
			echo JText::_('COM_VMVENDOR_CATSUGGEST_CATSUGGESTBUTTON').'</button>';
	}
	else
	{
		echo JText::_('COM_VMVENDOR_CATSUGGEST_CATADDBUTTON').'</button>';
		if($tmpl!='component')
		{
			echo ' <a class="btn" onclick="history.go(-1)" > <i class="vmv-icon-cancel"></i> '. JText::_('JCANCEL').'</a>';
		}
			
		if ($cat_suggest==2)
				echo '<input type="hidden" name="cat_published" value="0" />';
		if ($cat_suggest==3)
				echo '<input type="hidden" name="cat_published" value="1" />';
	}
}
else
	echo '<div class="alert alert-warning">'. JText::_('COM_VMVENDOR_VMVENADD_ONLYLOGGEDIN') .'</div>';
echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability 
echo '<input type="hidden" name="option" value="com_vmvendor" />';
echo '<input type="hidden" name="tmpl" value="'.$tmpl.'" />';
echo '<input type="hidden" name="controller" value="catsuggest" />';
echo '<input type="hidden" name="task" value="catsuggest.save" />';
echo '</div>';
echo  '</form>';
?>