<?php if((!isset($this->company->userId) || $this->company->userId == 0) && $appSettings->claim_business){ ?>
<div id="company-claim" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_CLAIM_COMPANY') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
					<form id="claimCompanyFrm" name ="claimCompanyFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" onSubmit="return validateClaimForm()">
						<p>
							<?php echo JText::_('LNG_COMPANY_CLAIM_TEXT') ?>
						</p>
						<div class="review-repsonse">
						<fieldset>
		
							<div class="form-item">
								<label for="firstName"><?php echo JText::_('LNG_FIRST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="firstName" id="firstName"><br>
									<span class="error_msg" id="frmFirstName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="lastName" id="lastName"><br>
									<span class="error_msg" id="frmLastName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_FUNCTION') ?></label>
								<div class="outer_input">
									<input type="text" name="function" id="function"><br>
									<span class="error_msg" id="frmFunction_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_PHONE') ?></label>
								<div class="outer_input">
									<input type="text" name="phone" id="phone"><br>
									<span class="error_msg" id="frmPhone_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email" ><br>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>

							<div class="form-item">
								<input type="checkbox"  name="claim-company-agreament" id="claim-company-agreament"> <?php echo JText::_('LNG_COMPANY_CLAIM_DECLARATION')?>
							</div>

							<div class="form-item">
								<input type="checkbox"  name="claim-terms-conditions" id="claim-terms-conditions"> <?php echo JText::_('LNG_TERMS_AGREAMENT')?>
							</div>
							
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
									$namespace="jbusinessdirectory.contact";
									$class=" required";
									
									$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																		
									if(!empty($captcha)){	
										$captcha->display("captcha", "captcha-div-claim", $class);
									}
									
									?>
									<div id="captcha-div-claim"></div>
								</div>
							<?php } ?>
		
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="submit" class="ui-dir-button">
											<span class="ui-button-text"><?php echo JText::_("LNG_CLAIM_COMPANY")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
						</div>
						
						<input type='hidden' name='task' value='companies.claimCompany'/>
						<input type='hidden' name='userId' value='<?php echo $user->id?>'/>
						<input type='hidden' name='controller' value='companies' />
						<input type='hidden' name='view' value='companies' />
						<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
					</form>
				</div>
		</div>
	</div>
</div>
<?php } ?>	
	
<?php if((isset($this->package->features) && in_array(CONTACT_FORM,$this->package->features) || !$appSettings->enable_packages) && $showData && !empty($company->email)){ ?>
						
<div id="company-contact" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_CONTACT_COMPANY') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
					<form id="contactCompanyFrm" name="contactCompanyFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" onSubmit="return validateContactForm()">
						<p>
							<?php echo JText::_('LNG_COMPANY_CONTACT_TEXT') ?>
						</p>
						<div class="review-repsonse">
							<fieldset>
			
								<div class="form-item">
									<label for="firstName"><?php echo JText::_('LNG_FIRST_NAME') ?></label>
									<div class="outer_input">
										<input type="text" name="firstName" id="firstName" ><br>
										<span class="error_msg" id="frmFirstNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
									</div>
								</div>
			
								<div class="form-item">
									<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
									<div class="outer_input">
										<input type="text" name="lastName" id="lastName" ><br>
										<span class="error_msg" id="frmLastNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
									</div>
								</div>
			
								<div class="form-item">
									<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
									<div class="outer_input">
										<input type="text" name="email" id="email" ><br>
										<span class="error_msg" id="frmEmailC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
									</div>
								</div>
	
								<div class="form-item">
									<label for="description" ><?php echo JText::_('LNG_CONTACT_TEXT')?>:</label>
									<div class="outer_input">
										<textarea rows="5" name="description" id="description"></textarea><br>
										<span class="error_msg" id="frmDescriptionC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
									</div>
								</div>
						
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
									$namespace="jbusinessdirectory.contact";
									$class=" required";
									
									$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																		
									if(!empty($captcha)){	
										$captcha->display("captcha", "captcha-div-contact", $class);
									}
									
									?>
									<div id="captcha-div-contact"></div>
								</div>
							<?php } ?>
						
								<div class="clearfix clear-left">
									<div class="button-row ">
										<button type="submit" class="ui-dir-button">
												<span class="ui-button-text"><?php echo JText::_("LNG_CONTACT_COMPANY")?></span>
										</button>
										<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
												<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
										</button>
									</div>
								</div>
							</fieldset>
						</div>
						
						<?php echo JHTML::_( 'form.token' ); ?>
						<input type='hidden' name='task' value='companies.contactCompany'/>
						<input type='hidden' name='userId' value='<?php echo $user->id?>'/>
						<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
					</form>
				</div>
		</div>
	</div>
</div>	
<?php } ?>	

<?php if($user->id>0){?>
<div id="add-bookmark" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_ADD_BOOKMARK') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">				
					<form id="bookmarkFrm" name="bookmarkFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post">
						<div class="review-repsonse">
						<fieldset>
							<div class="form-item">
								<label for="description" ><?php echo JText::_('LNG_NOTE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="note" id="note" cols="50" ></textarea><br>
								</div>
							</div>
					
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="submit" class="ui-dir-button">
											<span class="ui-button-text"><?php echo JText::_("LNG_ADD")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
						</div>
						
						<?php echo JHTML::_( 'form.token' ); ?>
						<input type='hidden' name='task' value='companies.addBookmark'/>
						<input type='hidden' name='user_id' value='<?php echo $user->id?>'/>
						<input type="hidden" name="company_id" value="<?php echo $this->company->id?>" />
					</form>
				</div>
		</div>
	</div>
</div>	
<?php } ?>


<?php if($user->id>0){?>
<div id="update-bookmark" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_UPDATE_BOOKMARK') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">				
					<form id="updateBookmarkFrm" name="bookmarkFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post">
						<div class="review-repsonse">
						<fieldset>
							<div class="form-item">
								<a href="javascript:removeBookmark()" class="red"> <?php echo JText::_("LNG_REMOVE_BOOKMARK")?></a>	
							</div>
							<div class="form-item">
								<label for="description" ><?php echo JText::_('LNG_NOTE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="note" id="note" cols="50" ><?php echo isset($this->company->bookmark)?$this->company->bookmark->note:"" ?></textarea>
								</div>
							</div>
					
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="submit" class="ui-dir-button">
											<span class="ui-button-text"><?php echo JText::_("LNG_UPDATE")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
						</div>
						
						<?php echo JHTML::_( 'form.token' ); ?>
						<input type='hidden' id="task" name='task' value='companies.updateBookmark'/>
						<input type='hidden' name='id' value='<?php echo $this->company->bookmark->id ?>'/>
						<input type='hidden' name='user_id' value='<?php echo $user->id?>'/>
						<input type="hidden" name="company_id" value="<?php echo $this->company->id?>" />
					</form>
				</div>
		</div>
	</div>
</div>	
<?php } ?>

<div id="login-notice" style="display:none;">
<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_INFO') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">				
					<p>
						<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
					</p>
					<p>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)); ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
					</p>
				</div>
		</div>
	</div>
</div>


<script>
jQuery(document).ready(function(){
	var averageRaty = jQuery('#rating-average').raty({
		  half:       true,
		  precision:  false,
		  size:       24,
		  starHalf:   'star-half.png',
		  starOff:    'star-off.png',
		  starOn:     'star-on.png',
		  readOnly:   true,
		  start:	  <?php echo $this->company->averageRating ?>, 	
		  path:		  '<?php echo COMPONENT_IMAGE_PATH?>'	
		});
	
	var userRating = jQuery('#rating-user').raty({
		  half:       true,
		  precision:  false,
		  size:       24,
		  starHalf:   'star-half.png',
		  starOff:    'star-off.png',
		  starOn:     'star-on.png',
		  start:	  <?php echo isset($this->rating->rating)?$this->rating->rating:'0' ?>,	
		  path:		  '<?php echo COMPONENT_IMAGE_PATH?>',	 
		  click: function(score, evt) {
			  <?php $user = JFactory::getUser(); 
			  	if($appSettings->enable_reviews_users && $user->id ==0){
			  	?>
			  	jQuery(this).raty('start',jQuery(this).attr('title'));
			  	jQuery(this).parent().parent().parent().parent().find(".company-info-review").find(".login-awareness").show();
			  <?php }else{  ?>
			  updateCompanyRate('<?php echo $this->company->id ?>',score);
			 <?php } ?>
		  }	
		});

	jQuery('.rating-review').raty({
		  half:       true,
		  size:       24,
		  starHalf:   'star-half.png',
		  starOff:    'star-off.png',
		  starOn:     'star-on.png',
		  start:   	  function() { return jQuery(this).attr('title')},
		  path:		  '<?php echo COMPONENT_IMAGE_PATH?>',
		  readOnly:   true
		});
});  

function showTab(tabId){
	jQuery("#tabId").val(tabId);
	jQuery("#tabsForm").submit();
}

function showClaimDialog(){
	jQuery.blockUI({ message: jQuery('#company-claim'), css: {width: 'auto', top: '5%', left:"0", position:"absolute", cursor:'default'} });
	jQuery('.blockUI.blockMsg').center();
	jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI);
}

function showLoginNotice(){
	jQuery.blockUI({ message: jQuery('#login-notice'), css: {width: 'auto', top: '5%', left:"0", position:"absolute"} });
	jQuery('.blockUI.blockMsg').center();
	jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
}

function showAddBookmarkDialog(){

	<?php if($user->id ==0){?>
		showLoginNotice();
	<?php }else{?>
		jQuery.blockUI({ message: jQuery('#add-bookmark'), css: {width: 'auto',top: '5%', left:"0", position:"absolute",cursor:'default'} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
	<?php }?>
	
}

function showUpdateBookmarkDialog(){
	console.debug("show update");
	<?php if($user->id ==0){?>
		showLoginNotice();
	<?php }else{?>
		jQuery.blockUI({ message: jQuery('#update-bookmark'), css: {width: 'auto',top: '5%', left:"0", position:"absolute",cursor:'default'} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
	<?php }?>
	
}

function removeBookmark(){
	console.debug("remove bookmark");
	jQuery("#updateBookmarkFrm > #task").val("companies.removeBookmark");
	jQuery("#updateBookmarkFrm").submit();
}


function claimCompany(){
	<?php if($user->id==0){	?>
  		jQuery("#claim-login-awarness").show();
  	 <?php //}else if($this->appSettings->direct_processing){  ?>
  	//	window.location = "<?php //echo JRoute::_("index.php?option=com_jbusinessdirectory&view=packages&claimCompanyId=".$this->company->id)?>";
   <?php }else{  ?>
	  	jQuery(".error_msg").each(function(){
			jQuery(this).hide();
		});
  		showClaimDialog();
  // updateCompanyOwner(<?php echo $this->company->id ?>, <?php echo $user->id ?>);
 	<?php } ?>
}

function showContactCompany(){
	jQuery.blockUI({ message: jQuery('#company-contact'), css: {width: 'auto',top: '10%', left:"0",position:"absolute",cursor:'default'} });
	jQuery('.blockUI.blockMsg').center();
	jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI);
}

function showDirTab(tab){
	jQuery(".dir-tab").each(function(){
		jQuery(this).hide();
		
	});

	jQuery(tab).show();

	jQuery(".track-business-details").each(function(){
		jQuery(this).parent().removeClass("active");
	});
	
	var number = tab.slice(-1);
	jQuery("#dir-tab-"+number).parent().addClass("active");
}

function validateClaimForm(){
	var form = document.claimCompanyFrm;
	var isError = false;
	
	jQuery(".error_msg").each(function(){
		jQuery(this).hide();
	});
	
	if( !validateField( form.elements['firstName'], 'string', false, null ) ){
		jQuery("#frmFirstName_error_msg").show();
		if(!isError)
			jQuery("#firstName").focus();
		isError = true;
	}

	if( !validateField( form.elements['lastName'], 'string', false, null ) ){
		jQuery("#frmLastName_error_msg").show();
		if(!isError)
			jQuery("#lastName").focus();
		isError = true;
	}

	if( !validateField( form.elements['function'], 'string', false, null ) ){
		jQuery("#frmFunction_error_msg").show();
		if(!isError)
			jQuery("#function").focus();
		isError = true;
	}
	
	if( !validateField( form.elements['phone'], 'string', false, null ) ){
		jQuery("#frmPhone_error_msg").show();
		if(!isError)
			jQuery("#phone").focus();
		isError = true;
	}
	if( !validateField( form.elements['email'], 'email', false, null ) ){
		jQuery("#frmEmail_error_msg").show();
		if(!isError)
			jQuery("#email").focus();
		isError = true;
	}
	
	if(!isError && jQuery("#claim-company-agreament").is(':checked')==false){
		alert("<?php echo JText::_("LNG_CLAIM_DECLARATION_ERROR")?>");
		isError = true;
	} else if(!isError && jQuery("#claim-terms-conditions").is(':checked')==false){
		alert("<?php echo JText::_("LNG_TERMS_CONDITIONS_ERROR")?>");
		isError = true;
	}
	
	return !isError;
}

function validateContactForm(){
	//console.debug("validate");
	var form = document.contactCompanyFrm;
	var isError = false;
	
	jQuery(".error_msg").each(function(){
		jQuery(this).hide();
	});
	
	if( !validateField( form.elements['firstName'], 'string', false, null ) ){
		//console.debug("firstName");
		jQuery("#frmFirstNameC_error_msg").show();
		if(!isError)
			jQuery("#firstName").focus();
		isError = true;
	}

	if( !validateField( form.elements['lastName'], 'string', false, null ) ){
		jQuery("#frmLastNameC_error_msg").show();
		if(!isError)
			jQuery("#lastName").focus();
		isError = true;
	}

	if( !validateField( form.elements['email'], 'email', false, null ) ){
		jQuery("#frmEmailC_error_msg").show();
		if(!isError)
			jQuery("#email").focus();
		isError = true;
	}
	
	if( !validateField( form.elements['description'], 'string', false, null ) ){
		jQuery("#frmDescriptionC_error_msg").show();
		if(!isError)
			jQuery("#description").focus();
		isError = true;
	}
	
	//console.debug(isError);
	return !isError;
}

function updateCompanyOwner(companyId, userId){
        jQuery.blockUI({ 
        	message: '<span class="loading-message"> Please wait...</span>',
            css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .6, 
            color: '#fff' 
        } }); 
 
	var form = document.reportAbuse;
	var postParameters='';
	postParameters +="&companyId=" + companyId;
	postParameters +="&userId=" + userId;
	var postData='&controller=companies&task=companies.updateCompanyOwner'+postParameters;
	jQuery.post(baseUrl, postData, processUpdateCompanyOwner);
}

function processUpdateCompanyOwner(responce){
	var xml = responce;
	jQuery(xml).find('answer').each(function()
	{
		var message ='';
		if(jQuery(this).attr('result')==true){
			message = "<?php echo JText::_('LNG_CLAIM_SUCCESSFULLY')?>"
			jQuery("#claim-container").hide();	
		}else{
			message = "<?php echo JText::_('LNG_ERROR_CLAIMING_COMPANY')?>"
			//alert('notsaved');
		}
		jQuery.blockUI({ 
        	message: '<span class="loading-message">'+message+'</span>',
			css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .6, 
            color: '#fff' 
        } }); 
		setTimeout(jQuery.unblockUI, 1500);
	});
}

</script>
