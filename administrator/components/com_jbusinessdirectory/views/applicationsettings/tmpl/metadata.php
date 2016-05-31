<div class="width-100">
<fieldset class="adminform long metadata">
	<legend><?php echo JText::_('LNG_METADATA_SETTINGS'); ?></legend>
	<ul class="adminformlist">
		<li>
			<label title="" class="hasTip" for="meta_description" id="meta_description-lbl" aria-invalid="false" ><?php echo JText::_('LNG_META_DESCRIPTION'); ?></label>	
			<textarea rows="3" cols="60" id="meta_description" name="meta_description" class="" aria-invalid="false"><?php echo $this->item->meta_description ?></textarea>
		</li>
		<li>
			<label title="" class="hasTip" for="meta_keywords" id="meta_keywords_lbl"><?php echo JText::_('LNG_META_KEYWORDS'); ?></label>					
			<textarea rows="3" cols="60" id="meta_keywords" name="meta_keywords"><?php echo $this->item->meta_keywords ?></textarea>
		</li>		
		<li>
			<label title="" class="hasTip" for="meta_description_facebook" id="meta_description_facebook-lbl" aria-invalid="false" ><?php echo JText::_('LNG_META_DESCRIPTION_FACEBOOK'); ?></label>	
			<textarea rows="3" cols="60" id="meta_description_facebook" name="meta_description_facebook" class="" aria-invalid="false"><?php echo $this->item->meta_description_facebook ?></textarea>
		</li>			
	</ul>
	</fieldset>
</div>	