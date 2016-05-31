<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 - Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

if ( $this->displ=='view' ) {
	$document	=  JFactory::getDocument();
	$lang       = $document->getLanguage();	
	
	?>	
	<script type="text/javascript">
	<!--	
		function validateForm() {			
			if (document.inviteForm.sender.value=='') {
				alert( "<?php echo JText::_( 'AUP_YOURNAME', true ); ?>" );
			} else {
				document.inviteForm.submit();
			}
		}
	// -->
	</script>	
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	<div id="invite-form">
		<form action="<?php echo JRoute::_( 'index.php?Itemid='.JFactory::getApplication()->input->get('Itemid', '') );?>" method="post" name="inviteForm" id="inviteForm" class="form-validate form-horizontal">
		<fieldset>
		<legend><?php echo JText::_( 'AUP_INVITEYOURFRIENDSTOSIGNUP' ); ?></legend>
		<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php if ( $this->params->get( 'showinformations' ) ) { ?>
			<span style="text-decoration: underline;"><?php echo JText::_( 'AUP_INFORMATION' ); ?></span><br/>
			<span class="small"><?php echo JText::_( 'AUP_MAXEMAILPERINVITE' ); ?> <b><?php echo $this->params->get( 'maxemailperinvite' ); ?></b></span><br/>
			<span class="small"><?php echo JText::_( 'AUP_DELAYBETWEENINVITES' ); ?> <b><?php echo $this->params->get( 'delaybetweeninvites' ); ?> <?php echo JText::_( 'AUP_SECONDS' ); ?></b></span><br/>
			<span class="small"><?php echo JText::_( 'AUP_MAXINVITESPERDAY' ); ?> <b><?php echo $this->params->get( 'maxinvitesperday' ); ?></b></span><br/>
			<?php if ( $this->referreid && $this->points ) { ?>
			<span class="small"><?php echo JText::_( 'AUP_POINTSEARNEDPERSUCCESSFULLINVITE' ); ?> <b><?php echo $this->points ; ?></b></span>
			<?php } ?>
		<?php } ?>
		</div>
		<div class="control-group">
			<div class="control-label">			
			<label data-original-title="<strong><?php echo JText::_( 'JGLOBAL_EMAIL' ); ?></strong><br /><?php echo JText::_( 'AUP_SEPERATEMULTIPLEEMAIL' ); ?>" id="jform_other_recipients-lbl" for="other_recipients" class="hasTooltip required" title=""><?php echo JText::_( 'JGLOBAL_EMAIL' ); ?><span class="star">&nbsp;*</span></label>
			</div>
			<div class="controls">
				<input type="text" name="other_recipients" id="other_recipients" class="validate-email" required="" aria-required="true" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">			
			<label data-original-title="<strong><?php echo JText::_( 'AUP_YOURNAME' ); ?></strong><br /><?php echo JText::_( 'AUP_YOURNAME' ). ' ('.JText::_( 'AUP_REQUIRED' ).')'; ?>" id="jform_sender-lbl" for="sender" class="hasTooltip required" title=""><?php echo JText::_( 'AUP_YOURNAME' ); ?><span class="star">&nbsp;*</span></label>
			</div>
			<div class="controls">
				<input type="text" name="sender" id="sender" value="<?php echo $this->user_name; ?>"  class="validate-username" required="" aria-required="true" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">			
			<label data-original-title="<strong><?php echo JText::_( 'AUP_CUSTOMMESSAGEOPTIONAL' ); ?></strong><br /><?php echo JText::_( 'AUP_THISMESSAGEWILLBEAPPEARINTHEEMAILSENT' ); ?>" id="jform_custommessage-lbl" for="custommessage" class="hasTooltip" title=""><?php echo JText::_( 'AUP_CUSTOMMESSAGEOPTIONAL' ); ?></label>
			</div>
			<div class="controls">
				<textarea class="inputbox" rows="4" name="custommessage" id="custommessage"></textarea>
			</div>
		</div>
		<?php if ( $this->params->get( 'userecaptcha' )=='1' || ($this->params->get( 'userecaptcha' )=='2' && $this->user_name=='') ) {  
			if ( $this->params->get( 'recaptchaajax ', 0 ) ) {
				echo '<div id="recaptcha_div"></div>';
			} else {			
				// prevent recaptchalib already loaded
				if ( !function_exists('_recaptcha_qsencode') ) {
					require_once (JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'assets'.DS.'recaptcha'.DS.'recaptchalib.php');	
				}			
				// Get a key from http://recaptcha.net/api/getkey
				$publickey = $this->params->get( 'pubkey' );					
				// the response from reCAPTCHA
				$resp = null;
				// the error code from reCAPTCHA, if any
				$error = null;			
				echo recaptcha_get_html($publickey, $error);
			}
		 }
		 ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary validate"><?php echo JText::_('AUP_SEND');?></button>
			<a class="btn" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
		</div>		
		<?php if ( $this->referrer_link && $this->user_name!='' ) { ?>		 
		<input type="text" name="referrer_link" id="referrer_link" onfocus="select();" readonly="readonly" class="inputbox" value="<?php echo $this->referrer_link; ?>" />
		<p>&nbsp;</p>
		<div class="alert alert-info"><span aria-hidden="true" class="icon-info-circle"></span>&nbsp;&nbsp;<?php echo JText::_( 'AUP_INVITATION_LINK' ); ?></div>		 
		<?php } ?>
		<input type="hidden" name="option" value="com_alphauserpoints" />
		<input type="hidden" name="controller" value="invite" />
		<input type="hidden" name="referreid" value="<?php echo $this->referreid; ?>" />
		<input type="hidden" name="task" value="sendinvite" />
		<?php echo JHtml::_('form.token'); ?>
		</fieldset>	
	</form>		
	</div>
<?php } else { 

		 if ($this->params->get('show_page_heading')){ ?>
			<div class="page-header">
				<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
			</div>
			<?php }	?>
			
		<fieldset>
		<legend><?php echo JText::_( 'AUP_INVITEYOURFRIENDSTOSIGNUP' ); ?></legend>
		<?php	 
		echo "<p>".$this->message."</p>";
		?>
		</fieldset>	
<?php
	}

	/** 
	*
	*  Provide copyright on frontend
	*  If you remove or hide this line below,
	*  please make a donation if you find AlphaUserPoints useful
	*  and want to support its continued development.
	*  Your donations help by hardware, hosting services and other expenses that come up as we develop,
	*  protect and promote AlphaUserPoints and other free components.
	*  You can donate on http://www.alphaplug.com
	*
	*/	
	getCopyrightNotice ();
?>