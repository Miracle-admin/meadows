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
									<input type="text" name="firstName" id="firstName" size="50"><br>
									<span class="error_msg" id="frmFirstNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="lastName" id="lastName" size="50"><br>
									<span class="error_msg" id="frmLastNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email" size="50"><br>
									<span class="error_msg" id="frmEmailC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>

							<div class="form-item">
								<label for="description" ><?php echo JText::_('LNG_CONTACT_TEXT')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="description" id="description" cols="50" ></textarea><br>
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
									<button type="button" class="ui-dir-button" onclick="contactCompany()">
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
						<input type='hidden' name='userId' value=''/>
						<input type="hidden" id="companyId" name="companyId" value="" />
					</form>
				</div>
		</div>
	</div>
</div>	

<div id="company-quote" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_QUOTE_COMPANY') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
				
					<form id="contactCompanyFrm" name="contactCompanyFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" onSubmit="return validateContactForm()">
						<p>
							<?php echo JText::_('LNG_COMPANY_QUTE_TEXT') ?>
						</p>
						<div class="review-repsonse">
						<fieldset>
		
							<div class="form-item">
								<label for="firstName"><?php echo JText::_('LNG_FIRST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="firstName" id="firstName" size="50"><br>
									<span class="error_msg" id="frmFirstNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="lastName" id="lastName" size="50"><br>
									<span class="error_msg" id="frmLastNameC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email" size="50"><br>
									<span class="error_msg" id="frmEmailC_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>

							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_CATEGORY') ?></label>
								<div class="outer_input">
									<select name="category" id="category">
										<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
										<?php foreach($this->maincategories as $category){?>
											<option value="<?php echo $category->name?>"><?php echo $category->name ?></option>
											<?php foreach($this->subcategories as $subCat){?>
												<?php if($subCat->parent_id == $category->id){?>
													<option value="<?php echo $subCat->name?>">-- <?php echo $subCat->name?></option>
												<?php } ?>
											<?php }?>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="form-item">
								<label for="description" ><?php echo JText::_('LNG_CONTACT_TEXT')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="description" id="description" cols="50" ></textarea><br>
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
										$captcha->display("captcha", "captcha-div-quote", $class);
									}
									
									?>
									<div id="captcha-div-quote"></div>
								</div>
							<?php } ?>
							
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="button" class="ui-dir-button" onclick="requestQuoteCompany()">
											<span class="ui-button-text"><?php echo JText::_("LNG_REQUEST_QUOTE")?></span>
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
						<input type='hidden' name='userId' value=''/>
						<input type="hidden" id="companyId" name="companyId" value="" />
					</form>
				</div>
		</div>
	</div>
</div>	