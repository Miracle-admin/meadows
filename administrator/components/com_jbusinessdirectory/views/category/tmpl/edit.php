<?php
/**
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
jimport('joomla.html.pane');

$app = JFactory::getApplication();
$input = $app->input;
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'category.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<div class="category-form-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_CATEGORY_DETAILS');?></h2>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<?php 
						if($this->appSettings->enable_multilingual) {
							$options = array(
								'onActive' => 'function(title, description){
									description.setStyle("display", "block");
									title.addClass("open").removeClass("closed");
								}',
								'onBackground' => 'function(title, description){
									description.setStyle("display", "none");
									title.addClass("closed").removeClass("open");
								}',
								'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
								'useCookie' => true, // this must not be a string. Don't use quotes.
							);
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
								if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)){
									$langContent = $this->item->name;
								}
								echo "<input type='text' name='name_$lng' id='name'.$lng' class='input_txt validate[required]' value='".$langContent."'  maxLength='100'>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="100">
						<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
					<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>"  maxLength="100">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="name"><?php echo JText::_('LNG_PARENT')?> </label> 
					<select name="parent_id" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->categoryOptions, 'value', 'text', $this->item->parent_id);?>
					</select>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<?php 
						if($this->appSettings->enable_multilingual) {
							$options = array(
								'onActive' => 'function(title, description){
									description.setStyle("display", "block");
									title.addClass("open").removeClass("closed");
								}',
								'onBackground' => 'function(title, description){
									description.setStyle("display", "none");
									title.addClass("closed").removeClass("open");
								}',
								'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
								'useCookie' => true, // this must not be a string. Don't use quotes.
							);
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)){
									$langContent = $this->item->description;
								}
								echo "<textarea id='description'.$lng' name='description_$lng' class='input_txt' cols='75' rows='10' maxLength='".CATEGORY_DESCRIPTIION_MAX_LENGHT."'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<textarea name="description" id="description" class="input_txt validate[required]"  cols="75" rows="10"  maxLength="<?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?>" onkeyup="calculateLenght();"><?php echo $this->item->description ?></textarea>
							<div class="clear"></div>
							<div class="description-counter">	
								<input type="hidden" name="descriptionMaxLenght" id="descriptionMaxLenght" value="<?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?>" />	
								<label for="decriptionCounter">(Max. <?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?> characters).</label>
								<?php echo JText::_('LNG_REMAINING')?><input type="text" value="0" id="descriptionCounter" name="descriptionCounter">			
							</div>
					<?php } ?>
				</div>
				<div class="form-box">
					<h3> <?php echo JText::_('LNG_IMAGE');?></h3>
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="imageLocation" id="imageLocation" value="<?php echo $this->item->imageLocation?>">
							<input type="file" id="imageUploader" name="uploadfile" size="50">		
							<div class="clear"></div>
							<a href="javascript:removeImage();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>					
					</div>
					<div class="picture-preview" id="picture-preview">
						<?php
							if(!empty($this->item->imageLocation)) {
								echo "<img  id='categoryImg' src='".JURI::root().PICTURES_PATH.$this->item->imageLocation."'/>";
							}
						?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="form-box">
					<h3><?php echo JText::_('LNG_MARKER');?></h3>
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="markerLocation" id="markerLocation" value="<?php echo $this->item->markerLocation?>">
							<input type="file" id="markerfile" name="markerfile" size="50">		
							<div class="clear"></div>
							<a href="javascript:removeMarker();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>					
					</div>
					
					<div class="picture-preview" id="marker-preview">
						<?php 
						if(!empty($this->item->markerLocation)) {
							echo "<img id='markerImg' src='".JURI::root().PICTURES_PATH.$this->item->markerLocation."'/>";
						} ?>
					</div>		
					<div class="clear"></div>
				</div>	
			</div>
		</fieldset>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
		<input type="hidden" name="view" id="view" value="company" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>

<script>

jQuery(document).ready(function() {
		if(jQuery("#descriptionCounter").val())
			jQuery("#descriptionCounter").val(parseInt(jQuery("#description").attr('maxlength')) - jQuery("#description").val().length);
});

function calculateLenght(){
	var obj = jQuery("#description");
    var max = parseInt(obj.attr('maxlength'));

    if(obj.val().length > max){
        obj.val(obj.val().substr(0, obj.attr('maxlength')));
    }
  
 	jQuery("#descriptionCounter").val((max - obj.val().length));
}
</script>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>