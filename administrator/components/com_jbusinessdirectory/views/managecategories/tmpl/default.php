<?php 

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

if( JRequest::getString( 'task') !='edit')
{
?>
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecategories');?>" method="post" id="adminForm" name="adminForm">
	<input type ="checkbox" name="boxchecked"  id="boxchecked"  style="display:none" checked="checked">
	<div id="editcell">
		<?php 
			//dump($this->items);
			echo $this->displayAllCategories( $this->items,1);

		?>
	</div>
	<input type="hidden" name="task" value="" />
	
	<?php echo JHTML::_( 'form.token' ); ?> 
	<script  type="text/javascript">
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'edit') 
			{
				submitform( pressbutton );
				return;
			} else {
				submitform( pressbutton );
			}
		}
	</script>
</form>
<?php
}
else
{

?>
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecategories');?>" method="post" id="adminForm" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_MANAGE_CATEGORIES'); ?></legend>
		<div id="categories-layer">
			<?php for ($i=1;$i<=4;$i++){ ?>
				<div class="categories-level-layer">
					<div class="categories-level-container">
						<h2> Level <?php echo $i?>	</h2>
						<div class="categories-content" id="categories-level-<?php echo $i?>">
							<?php if($i==1) echo $this->displayCategories( $this->item); ?>
						</div>
						<?php if($i==1){ ?>
						<a href="javascript:" onclick="addNewCategory(0);"><?php echo JText::_("LNG_ADD_NEW") ?></a>
						<?php } ?>
					</div>
				</div>
			<?php }	?>
		</div>
	</fieldset>
	<script  type="text/javascript">
	
	Joomla.submitbutton = function(pressbutton){
		var form = document.adminForm;
		submitform( pressbutton );
	}
	</script>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>

<script>
jQuery(document).ready(function(){
	jQuery('#uploadfile').change(function() {
		var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
		var path = jQuery('#uploadfile').val();
		if (path.search(fisRe) == -1)
		{
			alert(' JPG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload('<?php echo JURI::base()?>components/com_jbusinessdirectory/assets/upload.php?t=<?php echo strtotime('now')?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(CATEGORY_PICTURES_PATH)?>', function(responce)
		{
			//alert(responce);
			if( responce =='' )
			{
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE')?>");
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
						setUpLogo("<?php echo CATEGORY_PICTURES_PATH ?>" + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
					}
					else if( jQuery(this).attr("error") == 1 )
					alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED')?>");
					else if( jQuery(this).attr("error") == 2 )
					alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE')?>");
					else if( jQuery(this).attr("error") == 3 )
					alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY')?>");
					else if( jQuery(this).attr("error") == 4 )
					alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE')?>");
				});
					
				jQuery(this).val('');
			}
		}, 'html'
		);
	});

	jQuery('#markerfile').change(function() {
		var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
		var path = jQuery('#markerfile').val();
		if (path.search(fisRe) == -1)
		{
			alert(' JPG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload('<?php echo JURI::base()?>components/com_jbusinessdirectory/assets/upload.php?t=<?php echo strtotime('now')?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH)?>&_target=<?php echo urlencode(CATEGORY_PICTURES_PATH)?>', function(responce)
		{
			//alert(responce);
			if( responce =='' )
			{
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE')?>");
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
						setUpMarker("<?php echo CATEGORY_PICTURES_PATH ?>" + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
					}
					else if( jQuery(this).attr("error") == 1 )
					alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED')?>");
					else if( jQuery(this).attr("error") == 2 )
					alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE')?>");
					else if( jQuery(this).attr("error") == 3 )
					alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY')?>");
					else if( jQuery(this).attr("error") == 4 )
					alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE')?>");
				});
					
				jQuery(this).val('');
			}
		}, 'html'
		);
	});
		
});

function setUpLogo(path, name){
	jQuery("#frmCategoryImageLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', "<?php echo JURI::root().PICTURES_PATH ?>" + path );
	img_new.setAttribute('id', 'categoryImg');
	img_new.setAttribute('class', 'category-image');
	jQuery("#picture-preview").empty();
	jQuery("#picture-preview").append(img_new);
}

function setUpMarker(path, name){
	console.debug(path);
	jQuery("#frmCategoryMarkerLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', "<?php echo JURI::root().PICTURES_PATH ?>" + path );
	img_new.setAttribute('id', 'markerImg');
	img_new.setAttribute('class', 'category-image');
	jQuery("#marker-preview").empty();
	jQuery("#marker-preview").append(img_new);
}
</script>
<?php
}
?>

	<div id="manageCategoryFrm" style="display:none;">
  		<div id="popup_container">
    <!--Content area starts-->

    		<div class="head">
      		    <div class="head_inner">
               <h2> <?php echo JText::_('LNG_MANAGE_CATEGORY'); ?></h2>
               <a href="javascript:void(0)" class="cancel_btn" onclick="closePopup(); return false;"><span class="cancel_icon">&nbsp;</span><?php echo JText::_('LNG_CANCEL'); ?></a></div>
            </div>
            <div class="content">
                    <div class="descriptions" >

                       <div id="content_section_tab_data1">
                       		<span id="frm_error_msg_categories" class="text_error" style="display: none;"></span>
							<input type="hidden" name="frmCategoryId" id="frmCategoryId" value="0">
							<input type="hidden" name="frmCategoryImageLocation" id="frmCategoryImageLocation" value="">
							<input type="hidden" name="frmCategoryMarkerLocation" id="frmCategoryMarkerLocation" value="">
							<input type="hidden" name="frmParentCategoryId" id="frmParentCategoryId" value="0">
						<div class="row" id="category-container">
							<div class="form_row">  
								<label><span class="red_text">*</span> <?php echo JText::_("LNG_NAME")?></label>
                 				<div class="outer_input">
								    <input class="text" name="frmCategoryName" id="frmCategoryName" value="" type="text" maxLength="100">
									<span class="error_msg" id="frmCategoryName_error_msg" style="display: none;"></span>
								</div>
							</div>
							<div class="detail_box">
								<div  class="form-detail req"></div>
								<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
								<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="" maxLength="100">
								<div class="clear"></div>
							</div>
							<div class="form_row">  
								<label><span class="red_text">*</span> <?php echo JText::_("LNG_DESCRIPTION")?></label>
                 				<div class="outer_input">
								    <textarea  name="frmCategoryDescription" id="frmCategoryDescription" style="width: 300px" cols="50" rows="7" maxLength="350">
								    	
								    </textarea>
								    
									<span class="error_msg" id="frmSenderName_error_msg" style="display: none;"></span>
								</div>
							</div>
							<div class="form_row">  
								<label><span class="red_text">*</span> <?php echo JText::_("LNG_IMAGE")?></label>
                 				<div class="outer_input">
								    <input type="file" name="uploadfile" id="uploadfile" value="" size="30">
									<span class="error_msg" id="frmCategoryImage_error_msg" style="display: none;"></span>
								</div>
								
							</div>		
							
							<div class="form_row">  
								<label><span class="red_text">*</span> <?php echo JText::_("LNG_MARKER")?></label>
                 				<div class="outer_input">
								    <input type="file" name="markerfile" id="markerfile" value="" size="30">
									<span class="error_msg" id="frmCategoryMarker_error_msg" style="display: none;"></span>
							</div>
								
						</div>		
						</div>
						<div class="category-image-preview" id="picture-preview">
							<?php echo JText::_("LNG_IMAGE")?><br/>
							<img id="categoryImg" src="" />
							<div class="clear"></div>
							<a href="javascript:removeImage();"><?php echo JText::_("LNG_REMOVE") ?></a>
						</div>
						 <div class="category-image-preview" id="marker-preview">
						 	<?php echo JText::_("LNG_MARKER")?><br/>
							<img id="markerImg" src="" />
							<div class="clear"></div>
							<a href="javascript:removeMarker();"><?php echo JText::_("LNG_REMOVE") ?></a>
						</div>
						
						
						<div class="proceed_row">
                           <!--button sec starts-->
                              <input  class="submit" name="btnSave" id="btnSave" value="Save" onclick="saveCategory(this.form);" type="submit" />    
                             
                              <input type="submit" value="Cancel" class="cancel" name="btnCancel" id="btnCancel" onclick="closePopup();" />
                          </div>
                          <!--button sec ends-->
                        </div>
                        <div class="buttom_sec" id="frmEnvironmentsFormSubmitWait" style="display: none;"> <span class="error_msg" style="background-image: none; color: rgb(0, 0, 0) ! important;">Please wait...</span> </div>
            </div>
          </div>
          </div>
     </div>
     
     
     