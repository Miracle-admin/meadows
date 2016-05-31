<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	05 March 2015
 * @file name	:	views/project/tmpl/projectprogress.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Project progress page (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.formvalidation');
 JHtml::_('bootstrap.tooltip');
 
 $doc 	 = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/btngroup.js");
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 $doc->addScript("components/com_jblance/js/bootstrap-slider.js");
 $doc->addStyleSheet("components/com_jblance/css/slider.css");
 
 $project = $this->row;
 
 $user 		= JFactory::getUser();
 $config 	= JblanceHelper::getConfig();
 $now		= JFactory::getDate();
 $select 	= JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper

 $showUsername	= $config->showUsername;
 $dformat 		= $config->dateFormat;
 
 //get the freelancer and buyer details
 $buyerInfo 	 = JFactory::getUser($project->buyer_id);
 $freelancerInfo = JFactory::getUser($project->freelancer_id);
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 //find the current user is buyer or freelancer
 $isBuyer 		 = ($project->buyer_id == $user->id) ? true : false;
 $isfreelancer   = ($project->freelancer_id == $user->id) ? true : false;
 
 JText::script('COM_JBLANCE_INITIATED');
 JText::script('COM_JBLANCE_IN_PROGRESS');
 JText::script('COM_JBLANCE_COMPLETED');
 
 JblanceHelper::setJoomBriToken();

?>
<script type="text/javascript">
<!--
function validateFormProgress(f){
	$('submitbtn').set('disabled', true);
	$('submitbtn').set('value', '<?php echo JText::_('COM_JBLANCE_SAVING', true); ?>');
	return true;
}
function validateFormMessage(f){
	var message	 = $('message').get('value'); 
	//check if the message is not entered
	if(!message.length === 0 || !message.trim()){
		alert('<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>');
		$('message').focus();
		return false;
	}
	else {
		$('btnsend').set('disabled', true);
		$('btnsend').set('value', '<?php echo JText::_('COM_JBLANCE_SENDING', true); ?>');
		return true;
	}
}
jQuery(document).ready(function() {
	jQuery('#p_percent').slider({
		min: 0,
		max: 100,
		step: 10,
		value: 0,
		tooltip: 'hide',
		formater: function(value) {
		return value + ' %';
		}
	});
	jQuery('#p_percent').on('slide', function(slideEvt) {
		jQuery('#p_percent_text').text(slideEvt.value);
		if(slideEvt.value == 0){
			$('p_status').set('value', 'COM_JBLANCE_INITIATED');
			$('span_p_status').set('html', Joomla.JText._('COM_JBLANCE_INITIATED'));
		}
		if(slideEvt.value > 0 && slideEvt.value < 100){
			$('p_status').set('value', 'COM_JBLANCE_IN_PROGRESS');
			$('span_p_status').set('html', Joomla.JText._('COM_JBLANCE_IN_PROGRESS'));
		}
		if(slideEvt.value == 100){
			$('p_status').set('value', 'COM_JBLANCE_COMPLETED');
			$('span_p_status').set('html', Joomla.JText._('COM_JBLANCE_COMPLETED'));
		}
	});
});

	window.addEvent('domready', function(){
		attachFile('uploadmessage', 'message.attachfile');
	});
	
	window.addEvent('domready',function() {
		//Scrolls the window to the bottom
		if($('messageList'))
			var myFx = new Fx.Scroll('messageList').toBottom();
	});
/* window.addEvent('domready', function(){
	$('p_status').addEvent('change', function(el){
		if(this.value != ''){
			$('div_p_percent').setStyle('display', 'inline-block');
		}
		
		if(this.value == 'COM_JBLANCE_INITIATED'){
			//$('div_p_percent').setStyle('display', 'inline-block');
		}
		else if(this.value == 'COM_JBLANCE_IN_PROGRESS'){
			//$('div_p_percent').setStyle('display', 'inline-block');
		}
		else if(this.value == 'COM_JBLANCE_COMPLETED'){
			//$('div_p_percent').setStyle('display', 'inline-block');
			//jQuery('#p_percent').slider('setValue', 100);
		}
	//alert(this.value);
	});
	$('p_status').fireEvent('change');
}); */


//-->
</script>

	<div class="jbl_h3title"><?php echo $project->project_title; ?></div>
	<div class="row-fluid">

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="progress-message" id="progress-message" class="form-validate" onsubmit="return validateFormMessage(this);">	
	<?php 
	//initialise message varibles
	if($user->id == $project->freelancer_id){
		$idFrom = $project->freelancer_id;
		$idTo = $project->buyer_id;
	}
	else {
		$idFrom = $project->buyer_id;
		$idTo = $project->freelancer_id;
	}
	?>
	<?php 
	$count =  count($this->messages); 
	if($count > 0){
	?>
	<div  id="messageList" class="jb-chat-panel">
	<ul class="jb-chat">
		<?php
		$parent = (isset($this->messages[0])) ? $this->messages[0]->id : 0;	//parent is the 0th element.
		
		for($i=0, $x=count($this->messages); $i < $x; $i++){
			$message = $this->messages[$i];
			$userDtl = JFactory::getUser($message->idFrom); ?>
			<li class="left clearfix">
				<span class="jb-chat-img pull-left">
					<?php
					$attrib = 'width=56 height=56 class="img-polaroid"';
					$avatar = JblanceHelper::getThumbnail($message->idFrom, $attrib);
					echo !empty($avatar) ? LinkHelper::GetProfileLink($message->idFrom, $avatar) : '&nbsp;' ?>
				</span>
				<div class="jb-chat-body clearfix">
					<div class="header">
						<strong class="primary-font"><?php echo LinkHelper::GetProfileLink($message->idFrom, $userDtl->$nameOrUsername); ?></strong> 
						<small class="pull-right text-muted">
							<span class="icon-time"></span> <?php echo JHtml::_('date', $message->date_sent, $dformat, true); ?>
						</small>
					</div>
					<p>
					<?php 
					if($message->approved == 1)
						echo $message->message; 
					else
						echo '<small>'.JText::_('COM_JBLANCE_PRIVATE_MESSAGE_WAITING_FOR_MODERATION').'</small>';
					?>
					</p>
				<?php
				if(!empty($message->attachment) && $message->approved == 1) : ?>
					<span>
						<i class="icon-download"></i>
				<?php echo LinkHelper::getDownloadLink('message', $message->id, 'message.download'); ?>
					</span>
				<?php	
				endif;
				?>
				</div>
			</li>
		<?php 
		} ?>
	</ul>
	</div>
	<?php } ?>
	<div class="control-group">
		<div class="controls well">
			<textarea name="message" id="message" rows="3" class="input-block-level required" placeholder="<?php echo JText::_('COM_JBLANCE_ENTER_MESSAGE'); ?>"></textarea>
			<div class="sp10">&nbsp;</div>
			<div id="ajax-container-uploadmessage"></div>
			<div id="file-attached-uploadmessage"></div>
			<div class="pull-left">
				<?php 
				$tipmsg = JHtml::tooltipText(JText::_('COM_JBLANCE_ATTACH_FILE'), JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB');
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTooltip" title="<?php echo $tipmsg; ?>"/>
				<input type="button" id="uploadmessage" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="btn btn-primary">
			</div>
			<div style="text-align: right;">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEND'); ?>" class="btn btn-primary"  id="btnsend"/>
			</div>
		</div>
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="message.sendmessage" />	
	<input type="hidden" name="idFrom" value="<?php echo $idFrom; ?>" />
	<input type="hidden" name="idTo" value="<?php echo $idTo; ?>" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="subject" value="<?php echo $project->project_title; ?>" />
	<input type="hidden" name="project_id" value="<?php echo $project->project_id; ?>" />
	<input type="hidden" name="parent" value="<?php echo $parent; ?>" />
	<input type="hidden" name="type" value="COM_JBLANCE_PROJECT" />
	<input type="hidden" name="return" value="<?php echo base64_encode(JFactory::getURI()->toString())?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>