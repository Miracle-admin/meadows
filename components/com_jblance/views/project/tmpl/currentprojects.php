<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/showmybid.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.tooltip');
 JHtml::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");
 
 $model 				= $this->getModel();
 $user					= JFactory::getUser();
 $config 				= JblanceHelper::getConfig();
 $projhelp 				= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $sealProjectBids	  	= $config->sealProjectBids;
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
JHtml::script(Juri::base() . '/media/system/js/bpopup.js');
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 
?>

<div class="mtb-40">
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title">Current Projects</div>
	
	<?php if(empty($this->rows)) : ?>
	<div class="alert alert-info">
  		<?php echo "You dont have any ongoing projects."; ?>
	</div>
	<?php else : ?>
	<?php
	
	for($i=0; $i < count($this->rows); $i++){
		$row 			  = $this->rows[$i];
		
		$publisherAvtar=JblanceHelper::getThumbnail($row->publisher_userid);
		$link_accept_bid  = JRoute::_('index.php?option=com_jblance&task=project.acceptbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_deny_bid	  = JRoute::_('index.php?option=com_jblance&task=project.denybid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_retract_bid = JRoute::_('index.php?option=com_jblance&task=project.retractbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_edit_bid    = JRoute::_('index.php?option=com_jblance&view=project&layout=placebid&projectId='.$row->proj_id);
		$link_invoice 	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->proj_id.'&tmpl=component&print=1&type=project&usertype=freelancer');
		$link_pay_comp	  = JRoute::_('index.php?option=com_jblance&task=project.paymentcomplete&id='.$row->proj_id.'&'.JSession::getFormToken().'=1');
		$link_progress 	  = JRoute::_('index.php?option=com_jblance&view=project&layout=projectprogress&id='.$row->id.'&pid='.$row->project_id);	// id is the bid id and NOT project id
	?>
	<div class="row-fluid">
		<div class="span5">
		<div class="pj_info">
			<h4>
				<?php echo LinkHelper::getProjectLink($row->proj_id, $row->project_title); ?>
				<ul class="promotions" style="margin-top: 5px;">
					<?php if($row->is_featured) : ?>
					<li data-promotion="featured"><?php echo JText::_('COM_JBLANCE_FEATURED'); ?></li>
					<?php endif; ?>
					<?php if($row->is_private) : ?>
		  			<li data-promotion="private"><?php echo JText::_('COM_JBLANCE_PRIVATE'); ?></li>
		  			<?php endif; ?>
					<?php if($row->is_urgent) : ?>
		  			<li data-promotion="urgent"><?php echo JText::_('COM_JBLANCE_URGENT'); ?></li>
		  			<?php endif; ?>
		  			<?php if($sealProjectBids || $row->is_sealed) : ?>
					<li data-promotion="sealed"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></li>
					<?php endif; ?>
					<?php if($row->is_nda) : ?>
					<li data-promotion="nda"><?php echo JText::_('COM_JBLANCE_NDA'); ?></li>
					<?php endif; ?>
				</ul>
			</h4>
			<div class="pj_desc">
			<span class="poavtar"><?php echo $publisherAvtar; ?></span>
			<?php $desc = strlen($row->description) > 50 ? substr($row->description,0,50)."..." : $row->description; 
			echo $desc;
			?>
			</div>
			<div class="categories"><?php echo JblanceHelper::getCategoryNames($row->id_category); ?></div>
			<div class="pj_budget">Budget: < $<?php echo $row->amount; ?></div>
			</div>
		
		</div>
	
		<div class="span4 text-center">
		<div class="talk_po"><a id="talk_to_po" href="<?php echo JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=talkpo&id='.$row->id.'&pid='.$row->proj_id);?>">Talk to Project Owner</a></div>
        <div class="send_update"><a href="<?php echo JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=projectprogress&id='.$row->id.'&pid='.$row->proj_id.'&Itemid=373'); ?>">Send Update</a></div>		
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	<?php endif; ?>
	
</form>
</div>
<div id="popup-container-talkpo" style="display:none;"></div>
<style>
.pj_info {
    border: 1px solid;
    margin: 0 0 0 22px;
    padding: 11px;
	background: #FFD;
}
 .talk_po a
{ 

    width: 180px;
    padding: 13px;
    background: #169BD5;
    color: #fff;
	}
	
.send_update a
{

    width: 180px;
    padding: 13px;
      background: #ccc;
    color: #fff;
}


 .talk_po ,.send_update
 {
 width: 221px;
 margin: 10px 0 43px 0;
 }
	 
</style>
<script type="text/javascript">
/* function sendMessage(pid,poid)
{
url='<?php echo JUri::base()."index.php?option=com_jblance&task=upgrades.talkpo&pid="?>'+pid+'&poid='+poid ;
jQuery("#popup-container-talkpo").bPopup(
{
content:'ajax', //'ajax', 'iframe' or 'image'
closeClass :'btn-close',
loadUrl: url,
//onOpen: function() { alert('onOpen fired'); }, 
//onClose: function() { alert('onClose fired'); }

}, 
 function()
 {
  //alert('Callback fired');
 })

}
 */
</script>
<style>
#popup-container-talkpo{
    background-color: #fff;
    border-radius: 10px;
   
    color: #111;
    display: none;
  
    padding: 25px;
}
</style>