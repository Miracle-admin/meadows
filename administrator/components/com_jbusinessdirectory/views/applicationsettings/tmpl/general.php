<div class="row-fluid">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_COMPANY_DETAILS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NAME'); ?></strong><br />Enter the name of your business" id="company_name-lbl" for="company_name" class="hasTooltip required" title=""><?php echo JText::_('LNG_NAME'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="company_name" id="company_name" value="<?php echo $this->item->company_name?>" size="50" type="text"></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_EMAIL'); ?></strong><br />Enter your business email" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_EMAIL'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="company_email" id="company_email" value="<?php echo $this->item->company_email?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_FACEBOOK'); ?></strong><br />Enter your business facebook" id="facebook-lbl" for="facebook" class="hasTooltip required" title=""><?php echo JText::_('LNG_FACEBOOK'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="facebook" id="facebook" value="<?php echo $this->item->facebook?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_TWITTER'); ?></strong><br />Enter your twitter" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_TWITTER'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="twitter" id="twitter" value="<?php echo $this->item->twitter?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_GOOGLE_PLUS'); ?></strong><br />Enter your googlep" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_GOOGLE_PLUS'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="googlep" id="googlep" value="<?php echo $this->item->googlep?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_LINKEDIN'); ?></strong><br />Enter your linkedin" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_LINKEDIN'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="linkedin" id="linkedin" value="<?php echo $this->item->linkedin?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_YOUTUBE'); ?></strong><br />Enter your youtube" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_YOUTUBE'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="youtube" id="youtube" value="<?php echo $this->item->youtube?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_LOGO'); ?></strong><br />Enter your youtube" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_LOGO'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls">
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="logo" id="imageLocation" value="<?php echo $this->item->logo?>">
							<input type="file" id="imageUploader" name="uploadfile" size="50">		
							<div class="clear"></div>
							<a href="javascript:removeImage();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>					
					</div>
					<div class="picture-preview" id="picture-preview">
						<?php
							if(!empty($this->item->logo)) {
								echo "<img  id='categoryImg' src='".JURI::root().PICTURES_PATH.$this->item->logo."'/>";
							}
						?>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_CURRENCY'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="company_name-lbl" for="company_name" class="hasTooltip" title=""><?php echo JText::_('LNG_NAME'); ?></label></div>
				<div class="controls">
					<select	id="currency_id" name="currency_id" class="chzn-color">
						<?php
							for($i = 0; $i <  count( $this->item->currencies ); $i++){
								$currency = $this->item->currencies[$i]; 
						?>
							<option value = '<?php echo $currency->currency_id?>' <?php echo $currency->currency_id==$this->item->currency_id? "selected" : ""?>> <?php echo $currency->currency_name." - ". $currency->currency_description ?></option>
						<?php }	?>
					</select>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_CURRENCY_SYMBOL'); ?></strong><br />Enter your business email" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_CURRENCY_SYMBOL'); ?><span class="star">&nbsp;*</span></label></div>
				<div class="controls"><input name="currency_symbol" id="currency_symbol" value="<?php echo $this->item->currency_symbol?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="currency_display-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_CURRENCY_DISPLAY'); ?></label></div>
				<div class="controls">
					<fieldset id="currency_display_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="currency_display" id="currency_display1" value="1" <?php echo $this->item->currency_display==1? 'checked="checked"' :""?> />
						<label class="btn" for="currency_display1"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="currency_display" id="currency_display2" value="2" <?php echo $this->item->currency_display==2? 'checked="checked"' :""?> />
						<label class="btn" for="currency_display2"><?php echo JText::_('LNG_SYMBOL')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="currency_location-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_CURRENCY'); ?></label></div>
				<div class="controls">
					<fieldset id="currency_location_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="currency_location" id="currency_location1" value="1" <?php echo $this->item->currency_location==1? 'checked="checked"' :""?> />
						<label class="btn" for="currency_location1"><?php echo JText::_('LNG_BEFORE_PRICE')?></label> 
						<input type="radio" class="validate[required]" name="currency_location" id="currency_location2" value="2" <?php echo $this->item->currency_location==2? 'checked="checked"' :""?> />
						<label class="btn" for="currency_location2"><?php echo JText::_('LNG_AFTER_PRICE')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="amount_separator-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_AMOUNT_SEPARATOR'); ?></label></div>
				<div class="controls">
					<fieldset id="amount_separator_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="amount_separator" id="amount_separator1" value="1" <?php echo $this->item->amount_separator==1? 'checked="checked"' :""?> />
						<label class="btn" for="amount_separator1"><?php echo JText::_('LNG_DOT_SEPARATOR')?></label> 
						<input type="radio" class="validate[required]" name="amount_separator" id="amount_separator2" value="2" <?php echo $this->item->amount_separator==2? 'checked="checked"' :""?> />
						<label class="btn" for="amount_separator2"><?php echo JText::_('LNG_COMMA_SEPARATOR')?></label> 
					</fieldset>
				</div>
			</div>
			
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_GENERAL_SETTINGS'); ?></legend>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<div class="control-label"><label id="company_name-lbl" for="company_name" class="hasTooltip" title=""><?php echo JText::_('LNG_DATE_FORMAT'); ?></label></div>
						<div class="controls">
								<select id='date_format_id' name='date_format_id'>
									<?php foreach ($this->item->dateFormats as $dateFormat){?>
										<option value = '<?php echo $dateFormat->id?>' <?php echo $dateFormat->id==$this->item->date_format_id? "selected" : ""?>> <?php echo $dateFormat->name?></option>
									<?php }	?>
								</select>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="time_format-lbl" for="time_format" class="hasTooltip" title=""><?php echo JText::_('LNG_TIME_FORMAT'); ?></label></div>
						<div class="controls">
							<select id='time_format' name='time_format'>
								<option value = "h:i A" <?php echo $this->item->time_format=="h:i A"? "selected" : ""?>><?php echo "12"." ".JText::_("LNG_HOURS")?></option>
								<option value = "H:i" <?php echo $this->item->time_format=="H:i"? "selected" : ""?>><?php echo "24"." ".JText::_("LNG_HOURS")?></option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="enable_packages-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_PACKAGES'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_packages" id="enable_packages1" value="1" <?php echo $this->item->enable_packages==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_packages1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_packages" id="enable_packages0" value="0" <?php echo $this->item->enable_packages==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_packages0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
							<div id="assign-packages" style="display:none">
									<span> <?php echo JText::_("LNG_UPDATE_COMPANIES_TO_PACKAGE") ?></span>
									<select name="package" class="inputbox input-medium">
										<option value="0"><?php echo JText::_("LNG_SELECT_PACKAGE") ?></option>
										<?php echo JHtml::_('select.options', $this->packageOptions, 'value', 'text',0);?>
									</select>
							</div>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_geolocation-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_GEOLOCATION'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_geolocation_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_geolocation" id="enable_geolocation1" value="1" <?php echo $this->item->enable_geolocation==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_geolocation1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_geolocation" id="enable_geolocation0" value="0" <?php echo $this->item->enable_geolocation==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_geolocation0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>

					<div class="control-group">
						<div class="control-label"><label id="enable_google_map_clustering-lbl" for="enable_google_map_clustering" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_GOOGLE_MAP_CLUSTERING'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_google_map_clustering_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_google_map_clustering" id="enable_google_map_clustering1" value="1" <?php echo $this->item->enable_google_map_clustering==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_google_map_clustering1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_google_map_clustering" id="enable_google_map_clustering0" value="0" <?php echo $this->item->enable_google_map_clustering==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_google_map_clustering0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_offers-lbl" for="enable_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_OFFERS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_offers_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_offers" id="enable_offers1" value="1" <?php echo $this->item->enable_offers==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_offers1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_offers" id="enable_offers0" value="0" <?php echo $this->item->enable_offers==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_offers0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_events-lbl" for="enable_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_EVENTS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_events_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_events" id="enable_events1" value="1" <?php echo $this->item->enable_events==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_events1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_events" id="enable_events0" value="0" <?php echo $this->item->enable_events==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_events0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_seo-lbl" for="enable_seo" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_SEO'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_seo_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_seo" id="enable_seo1" value="1" <?php echo $this->item->enable_seo==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_seo1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_seo" id="enable_seo0" value="0" <?php echo $this->item->enable_seo==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_seo0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="listing_url_type-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_URL_TYPE'); ?></label></div>
						<div class="controls">
							<fieldset id="listing_url_type_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type1" value="1" <?php echo $this->item->listing_url_type==1? 'checked="checked"' :""?> />
								<label class="btn" for="listing_url_type1"><?php echo JText::_('LNG_SIMPLE')?></label> 
								<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type2" value="2" <?php echo $this->item->listing_url_type==2? 'checked="checked"' :""?> />
								<label class="btn" for="listing_url_type2"><?php echo JText::_('LNG_CATEGORY')?></label> 
								<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type3" value="3" <?php echo $this->item->listing_url_type==3? 'checked="checked"' :""?> />
								<label class="btn" for="listing_url_type3"><?php echo JText::_('LNG_REGION')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_ratings-lbl" for="enable_ratings" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_RATINGS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_ratings_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_ratings" id="enable_ratings1" value="1" <?php echo $this->item->enable_ratings==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_ratings1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_ratings" id="enable_ratings0" value="0" <?php echo $this->item->enable_ratings==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_ratings0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_reviews-lbl" for="enable_reviews" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_REVIEWS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_reviews_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_reviews" id="enable_reviews1" value="1" <?php echo $this->item->enable_reviews==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_reviews" id="enable_reviews0" value="0" <?php echo $this->item->enable_reviews==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_reviews_users-lbl" for="enable_reviews_users" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_REVIEWS_USERS_ONLY'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_reviews_users_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_reviews_users" id="enable_reviews_users1" value="1" <?php echo $this->item->enable_reviews_users==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews_users1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_reviews_users" id="enable_reviews_users0" value="0" <?php echo $this->item->enable_reviews_users==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews_users0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_attachments-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_ATTACHMENTS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_attachments_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_attachments" id="enable_attachments1" value="1" <?php echo $this->item->enable_attachments==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_attachments1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_attachments" id="enable_attachments0" value="0" <?php echo $this->item->enable_attachments==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_attachments0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>

					<div class="control-group">
						<div class="control-label"><label id="max_attachments-lbl" for="max_attachments" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_ATTACHMENTS'); ?></label></div>
						<div class="controls">
							<input type="text" size="40" maxlength="20"  id="max_attachments" name="max_attachments" value="<?php echo $this->item->max_attachments?>">
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<div class="control-label"><label id="show_pending_approval-lbl" for="show_pending_approval" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_PENDING_APPROVAL'); ?></label></div>
						<div class="controls">
							<fieldset id="show_pending_approval_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_pending_approval" id="show_pending_approval1" value="1" <?php echo $this->item->show_pending_approval==true? 'checked="checked"' :""?> />
								<label class="btn" for="show_pending_approval1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="show_pending_approval" id="show_pending_approval0" value="0" <?php echo $this->item->show_pending_approval==false? 'checked="checked"' :""?> />
								<label class="btn" for="show_pending_approval0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="limit_cities-lbl" for="limit_cities" class="hasTooltip" title=""><?php echo JText::_('LNG_LIMIT_CITIES'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="limit_cities" id="limit_cities1" value="1" <?php echo $this->item->limit_cities==true? 'checked="checked"' :""?> />
								<label class="btn" for="limit_cities1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="limit_cities" id="limit_cities0" value="0" <?php echo $this->item->limit_cities==false? 'checked="checked"' :""?> />
								<label class="btn" for="limit_cities0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="metric-lbl" for="metric" class="hasTooltip" title=""><?php echo JText::_('LNG_METRIC'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="metric" id="metric1" value="1" <?php echo $this->item->metric==true? 'checked="checked"' :""?> />
								<label class="btn" for="metric1"><?php echo JText::_('LNG_MILES')?></label> 
								<input type="radio" class="validate[required]" name="metric" id="metric0" value="0" <?php echo $this->item->metric==false? 'checked="checked"' :""?> />
								<label class="btn" for="metric0"><?php echo JText::_('LNG_KM')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="vat-lbl" for="vat" class="hasTooltip" title=""><?php echo JText::_('LNG_VAT'); ?></label></div>
						<div class="controls">
							<input type="text" size=40 maxlength=20  id="vat" name = "vat" value="<?php echo $this->item->vat?>">
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="expiration_day_notice-lbl" for="expiration_day_notice" class="hasTooltip" title=""><?php echo JText::_('LNG_EXPIRATION_DAYS_NOTICE'); ?></label></div>
						<div class="controls">
							<input type="text" size=40 maxlength=20  id="expiration_day_notice" name = "expiration_day_notice" value="<?php echo $this->item->expiration_day_notice?>">
						</div>
					</div>
					
					<div class="control-group" style="display:none">
						<div class="control-label"><label id="direct_processing-lbl" for="direct_processing" class="hasTooltip" title=""><?php echo JText::_('LNG_DIRECT_PROCESSING'); ?></label></div>
						<div class="controls">
							<fieldset id="direct_processing_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="direct_processing" id="direct_processing1" value="1" <?php echo $this->item->direct_processing==true? 'checked="checked"' :""?> />
								<label class="btn" for="direct_processing1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="direct_processing" id="direct_processing0" value="0" <?php echo $this->item->direct_processing==false? 'checked="checked"' :""?> />
								<label class="btn" for="direct_processing0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					
					<div class="control-group">
						<div class="control-label"><label id="enable_multilingual-lbl" for="enable_multilingual" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_MULTILINGUAL'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_multilingual_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_multilingual" id="enable_multilingual1" value="1" <?php echo $this->item->enable_multilingual==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_multilingual1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_multilingual" id="enable_multilingual0" value="0" <?php echo $this->item->enable_multilingual==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_multilingual0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="add_url_id-lbl" for="add_url_id" class="hasTooltip" title=""><?php echo JText::_('LNG_ADD_URL_ID'); ?></label></div>
						<div class="controls">
							<fieldset id="add_url_id_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="add_url_id" id="add_url_id1" value="1" <?php echo $this->item->add_url_id==true? 'checked="checked"' :""?> />
								<label class="btn" for="add_url_id1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="add_url_id" id="add_url_id0" value="0" <?php echo $this->item->add_url_id==false? 'checked="checked"' :""?> />
								<label class="btn" for="add_url_id0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="captcha-lbl" for="captcha" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_CAPTCHA'); ?></label></div>
						<div class="controls">
							<fieldset id="captcha_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="captcha" id="captcha1" value="1" <?php echo $this->item->captcha==true? 'checked="checked"' :""?> />
								<label class="btn" for="captcha1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="captcha" id="captcha0" value="0" <?php echo $this->item->captcha==false? 'checked="checked"' :""?> />
								<label class="btn" for="captcha0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group" style="display:none">
						<div class="control-label"><label id="allow_multiple_companies-lbl" for="allow_multiple_companies" class="hasTooltip" title=""><?php echo JText::_('LNG_ALLOW_MULTIPLE_COMPANIES_PER_USER'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="allow_multiple_companies" id="allow_multiple_companies1" value="1" <?php echo $this->item->allow_multiple_companies==true? 'checked="checked"' :""?> />
								<label class="btn" for="allow_multiple_companies1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="allow_multiple_companies" id="allow_multiple_companies0" value="0" <?php echo $this->item->allow_multiple_companies==false? 'checked="checked"' :""?> />
								<label class="btn" for="allow_multiple_companies0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="enable_bookmarks-lbl" for="enable_bookmarks" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_BOOKMARKS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_bookmarks_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_bookmarks" id="enable_bookmarks1" value="1" <?php echo $this->item->enable_bookmarks==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_bookmarks1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_bookmarks" id="enable_bookmarks0" value="0" <?php echo $this->item->enable_bookmarks==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_bookmarks0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="front_end_acl-lbl" for="front_end_acl" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_FRONT_END_ACL'); ?></label></div>
						<div class="controls">
							<fieldset id="front_end_acl_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="front_end_acl" id="front_end_acl1" value="1" <?php echo $this->item->front_end_acl==true? 'checked="checked"' :""?> />
								<label class="btn" for="front_end_acl1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="front_end_acl" id="front_end_acl0" value="0" <?php echo $this->item->front_end_acl==false? 'checked="checked"' :""?> />
								<label class="btn" for="front_end_acl0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
				</div>	
			</div>
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
			<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_TERMS_AND_CONDITIONS'); ?></legend>
			<div class="control-group">
				<?php 
					$editor = JFactory::getEditor();
					echo $editor->display('terms_conditions', $this->item->terms_conditions, '550', '200', '80', '10', false);
				?>
			</div>
		</fieldset>
	</div>
</div>
		
			
<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>			
		