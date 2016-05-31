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
$app 			= JFactory::getApplication();
//$template_name  = $app->getTemplate('template')->template;
$juri 			= JURI::base();
$doc 					= JFactory::getDocument();
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/fontello.css');
//$doc->addStylesheet($juri.'templates/'.$template_name.'/css/theme.css');
$cparams 					= JComponentHelper::getParams('com_vm2wishlists');
$naming = $cparams->get('naming','username');
$userid = $app->input->getInt('userid');
$listid = $app->input->getInt('listid');
$sent 	= $app->input->get('sent');

if($sent>0 )
{
	if($sent==1 )
		echo '<div class="alert alert-success">'.JText::_('COM_VM2WISHLISTS_RECOMMEND_SENT');	
	else
		echo '<div class="alert alert-danger">'.JText::_('COM_VM2WISHLISTS_RECOMMEND_EMAILFAILED');	
	echo '</div>';	
	echo '<div style="text-align:center;padding-top:100px;">
	<input type="button" value="'.JText::_('COM_VM2WISHLISTS_RECOMMEND_CLOSE').'"   class="btn btn-lg btn-primary"  onclick="window.parent.SqueezeBox.close();" />
	</div>';
}	
else
{
	echo '<h3 class="modal-title">'.JText::_('COM_VM2WISHLISTS_RECOMMEND_TITLE').'</h3>';
	
	echo '<div><i class="'.$this->list_data->icon_class.'"></i> '.JText::_($this->list_data->list_name).'
	<br /><i class="vm2w-icon-user"></i> '.JText::_('COM_VM2WISHLISTS_BY').' '.ucwords(JFactory::getUser($userid)->$naming).'<br /><br /></div>';
	
	

/*	
	$Itemid = $app->input->getId('Itemid');	
	echo  substr(JURI::base(),0,-1).'/index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$userid.'&Itemid='.$Itemid;
	echo  '<br />'.$list_url = substr(JURI::base(),0,-1).JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$userid.'&Itemid='.$Itemid);

	*/
	
	
	echo '<form  method="post" name="adminForm" id="adminForm"  class="form-validate form-horizontal" action="'.JRoute::_('index.php?option=com_vm2wishlists&view=recommend&listid='.$listid.'&userid='.$userid).'" >';
	 ?>
      <fieldset name="mainfields">
      <div class="control-group">
     	<div class="control-label">
         	 <?php  echo $this->form->getLabel('myname') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('myname')  	?>
        	</div>
            </div>
     
       <div class="control-group">
       <div class="control-label">
         	 <?php  echo $this->form->getLabel('email') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('email')  	?>
        	</div>
            </div>
    
        <div class="control-group">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('subject') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('subject')  	?>
        	</div>
            </div>
        <div class="control-group">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('message') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('message')  	?>
        	</div>
            </div>
		 <div class="control-group">
        <div class="control-label">
         	 <?php  //echo $this->form->getLabel('copy') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('copy')  	?>
        	</div>
            </div>
            </fieldset>
        <?php
	
		echo '<i class="vm2w-icon-info"></i> '.JText::_('COM_VM2WISHLISTS_RECOMMEND_URLWILLBEADDED');
	
	?>
    <div class="form-actions">
	
	<button type="submit" class="btn btn-large btn-primary btn-block validate" onclick="Joomla.submitbutton('recommend.send')">
                    <i class="vm2w-icon-ok"></i> <?php echo JText::_('COM_VM2WISHLISTS_RECOMMEND_SENDBUTTON') ?>
                </button>
				
				
    <?php
	 echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability ?>
	<input type="hidden" name="listid" value="<?php echo $app->input->getInt('listid'); ?>">
    <input type="hidden" name="userid" value="<?php echo $app->input->getInt('userid'); ?>">
    <input type="hidden" name="Itemid" value="<?php echo $app->input->getInt('Itemid'); ?>">
	<input type="hidden" name="option" value="com_vm2wishlists" />
    <input type="hidden" name="controller" value="recommend" />
    <input type="hidden" name="task" value="recommend.send" /> 
		</div>
		</form>
		<?php
}