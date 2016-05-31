<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{	
		
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<?php 
$appSetings = JBusinessUtil::getInstance()->getApplicationSettings();
$user = JFactory::getUser(); 
?>

<?php  if(isset($isProfile)) { ?>
	<div class="buttons">
	
		<span class="button">
			<button onClick="saveCompanyInformation();return false;" value="<?php echo JText::_('LNG_SAVE'); ?>"><?php echo JText::_('LNG_SAVE'); ?></button>
		</span>

		<span class="button gray">
			<button onClick="cancel()" value="<?php echo JText::_('LNG_CANCEL'); ?>"><?php echo JText::_('LNG_CANCEL'); ?></button>
		</span>
	</div>
	<div class="clear"></div>		
<?php  } ?>

<div class="category-form-container">	
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">

			<h2> <?php echo JText::_('LNG_COUNTRY_DETAILS');?></h2>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<input type="text"
						name="country_name" id="country_name" class="input_txt" value="<?php echo $this->item->country_name ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					
					<?php 
						if($this->appSettings->enable_multilingual){
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
							foreach( $this->languages  as $k=>$lng ){
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)){
									$langContent = $this->item->description;
								}
								
								echo "<textarea id='description'.$lng' name='description_$lng' class='input_txt' cols='75' rows='10' maxLength='200'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							
							echo JHtml::_('tabs.end');
						}else {
						?>
							<textarea name="description" id="description" class="input_txt"  cols="75" rows="5"  maxLength="200"
								 onkeyup="calculateLenght();"><?php echo $this->item->description ?></textarea>
						<?php 
						}
						?>
						
				</div>
				
				<div class="detail_box" style="display:none">
					<div  class="form-detail req"></div>
					<label for="price"><?php echo JText::_('LNG_CURRENCY')?> </label> 
					<input type="text"
						name="price" id="price" class="input_txt"
						value="<?php echo $this->item->country_currency ?>">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box" style="display:none">
					<div  class="form-detail req"></div>
					<label for="price"><?php echo JText::_('LNG_CURRENCY_SHORT')?> </label> 
					<input type="text"
						name="price" id="price" class="input_txt"
						value="<?php echo $this->item->country_currency_short ?>">
					<div class="clear"></div>
				</div>

			</div>
			</fieldset>
					
			<fieldset  class="boxed">
						<div class="form-box">
							<h2> <?php echo JText::_('LNG_LOGO');?> <?php $attributeConfig["logo"]=ATTRIBUTE_OPTIONAL?"(". JText::_('LNG_OPTIONAL').")":"" ?></h2>
							
							<div class="form-upload-elem">
								<div class="form-upload">
									<label class="optional" for="logo"><?php echo JText::_("LNG_SELECT_IMAGE_TYPE") ?>.</label>
									<p class="hint"><?php echo JText::_('LNG_LOGO_MAX_SIZE');?></p>
									<input type="hidden" name="logo" id="logoLocation" value="<?php echo $this->item->logo?>">
									<input type="hidden" id="MAX_FILE_SIZE" value="2097152" name="MAX_FILE_SIZE">
									<input type="file" id="uploadLogo" name="uploadLogo" size="50">		
									<div class="clear"></div>
									<a href="javascript:removeLogo()"><?php echo JText::_("LNG_REMOVE_LOGO")?></a>
								</div>					
								<div class="info">
									<?php if($attributeConfig["logo"]==ATTRIBUTE_OPTIONAL){ ?>
										<div class="info-box">
												<?php echo JText::_('LNG_ADD_LOGO_CONTINUE');?> 
										</div>
									<?php } ?>
								</div>
							</div>
							
							<div class="picture-preview" id="picture-preview">
								<?php
									if(!empty($this->item->logo)){
										echo "<img src='".JURI::root().PICTURES_PATH.$this->item->logo."'/>";
									}
								?>
							</div>		
								
							<div class="clear"></div>
							<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
						</div>
						
					</fieldset>

	<script  type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#uploadLogo').change(function() {
					//jQuery("#item-form").validationEngine('detach');
					var fisRe 	= /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
					var path = jQuery('#uploadLogo').val();
					if (path.search(fisRe) == -1)
					{   
						alert(' JPG,JPEG, BMP, GIF, PNG only!');
						return false;
					}  
					
					jQuery(this).upload('<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime('now')?>&picture_type=<?php echo PICTURE_TYPE_OFFER?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(COUNTRIES_PICTURES_PATH.($this->item->id+0).'/')?>', function(responce) 
																										{
																											if( responce =='' )
																											{
																												alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
																												jQuery(this).val('');
																											}
																											else
																											{
																												var xml = responce;
																												// alert(responce);
																												jQuery(xml).find("picture").each(function()
																												{
																													if(jQuery(this).attr("error") == 0 )
																													{
																														setUpLogo(
																																	"<?php echo COUNTRIES_PICTURES_PATH.($this->item->id+0).'/'?>" + jQuery(this).attr("path"),
																																	jQuery(this).attr("name")
																														);
																													}
																													else if( jQuery(this).attr("error") == 1 )
																														alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
																													else if( jQuery(this).attr("error") == 2 )
																														alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
																													else if( jQuery(this).attr("error") == 3 )
																														alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
																													else if( jQuery(this).attr("error") == 4 )
																														alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
																												});
																												
																												jQuery(this).val('');
																											}
																										}, 'html'
					);

					//jQuery("#item-form").validationEngine('attach');
		        });
			
			});

			function setUpLogo(path, name){
				jQuery("#logoLocation").val(path);
				var img_new		 	= document.createElement('img');
				img_new.setAttribute('src', "<?php echo JURI::root().PICTURES_PATH ?>" + path );
				img_new.setAttribute('class', 'company-logo');
				img_new.setAttribute('alt', '<?php echo JText::_('LNG_LOGO',true) ?>');
				img_new.setAttribute('title', '<?php echo JText::_('LNG_LOGO',true) ?>');
				jQuery("#picture-preview").empty();
				jQuery("#picture-preview").append(img_new);
			}

			function removeLogo() {
				jQuery("#logoLocation").val("");
				jQuery("#picture-preview").html("");
			}
		
	</script>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	<?php echo JHTML::_( 'form.token' ); ?>
	 
</form>
</div>

