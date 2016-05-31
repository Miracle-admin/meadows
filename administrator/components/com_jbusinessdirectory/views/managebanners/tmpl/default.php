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


$appSetings = JBusinessUtil::getInstance()->getApplicationSettings();
//dump($appSetings);
//dump($appSetings->calendarFormat);
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

if( JRequest::getString( 'task') !='edit' && 	JRequest::getString( 'task') !='add' )
{
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="index.php" method="post" name="adminForm">
	<div id="editcell">
		<fieldset id="filter-bar">
			<div class="filter-search fltlft">
				<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
	
				<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			<div class="filter-select fltrt">
				<select name="filter_type_id" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_TYPE');?></option>
					<?php echo JHtml::_('select.options', $this->bannerTypes, 'value', 'text', $this->state->get('filter.type_id'));?>
				</select>
				<select name="filter_state_id" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATE');?></option>
					<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->state->get('filter.state_id'));?>
				</select>
			</div>
		</fieldset>
		<div class="clr"> </div>
		<TABLE class="adminlist">
			<thead>
				<th width='1%'>#</th>
				<th width='1%' align=center>&nbsp;</th>
				<th width='20%' align=center><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'b.name', $listDirn, $listOrder); ?></th>
				<th width='10%' align=center><?php echo JHtml::_('grid.sort', 'LNG_START_DATE', 'b.start_date', $listDirn, $listOrder); ?></th>
				<th width='10%' align=center><?php echo JHtml::_('grid.sort', 'LNG_END_DATE', 'b.end_date', $listDirn, $listOrder); ?></th>
				<th width='10%' align=center><?php echo JHtml::_('grid.sort', 'LNG_TYPE', 'bt.name', $listDirn, $listOrder); ?></th>
				<th width='10%' align=center><?php echo JHtml::_('grid.sort', 'LNG_STATE', 'b.state', $listDirn, $listOrder); ?></th>
			</thead>
			<tbody>

			
			<?php
			$nrcrt = 1;
			$i=0;
			//if(0)
			foreach( $this->items as $banner)
			{
				?>
				<TR class="row<?php echo $i%2?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD align=center><?php echo $nrcrt++?></TD>
					<TD align=center>
						<input type="radio" name="boxchecked"
							id="boxchecked" value="<?php echo $banner->id?>"
							onclick="adminForm.bannerId.value = '<?php echo $banner->id?>'" /> 
					</TD>
					<TD align=left><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&controller=managebanners&view=managebanners&task=edit&bannerId='. $banner->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $banner->name?>
						</B>
					</a>
					</TD>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat($banner->start_date) ?>
					</td>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat($banner->end_date) ?>
					</td>
					<td>
						<?php echo $banner->typeName ?>
					</td>
					<td valign=top align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($banner->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&controller=managebanners&view=managebanners&task=chageState&bannerId='. $banner->id )?> '
											"
							/>
					</td>
				</TR>
			<?php
				$i++;
			}
			?>
			</tbody>
			<tfoot>
			    <tr>
			      <td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
			    </tr>
			 </tfoot>
		</TABLE>
	</div>
	<input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" value="" /> 
	 <input type="hidden" name="view" value="managebanners" /> 
	 <input type="hidden" name="bannerId" value="" />
	 <input type="hidden" name="controller" id="contoller" value="<?php echo JRequest::getCmd('controller', 'J-BusinessDirectory')?>" />
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 
	<?php echo JHTML::_( 'form.token' ); ?> 
	<script  type="text/javascript">
		<?php
			Joomla.submitbutton = function(pressbutton) 
		?>
		{
			var form = document.adminForm;

			if( pressbutton =='back' )
			{
				form.elements['task'].value 		= '';
				form.elements['view'].value 		= '';
				form.elements['controller'].value 	= '';
				submitform( pressbutton )
			}
	         
			if (pressbutton == 'edit' || pressbutton == 'Delete') 
			{
				var isSel = false;
				if( form.elements['boxchecked'].length == null )
				{
					if(form.elements['boxchecked'].checked)
					{
						isSel = true;
					}
				}
				else
				{
					for( i = 0; i < form.boxchecked.length; i ++ )
					{
						if(form.elements['boxchecked'][i].checked)
						{
							isSel = true;
							break;
						}
					}
				}
				
				if( isSel == false )
				{
					alert('<?php echo JText::_('LNG_YOU_MUST_SELECT_ONE_RECORD')?>');
					return false;
				}
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
<form action="index.php" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_MANAGE_BANNERS'); ?></legend>
		<TABLE class="admintable" align="left" border="0">
			<tr>
				<td class="key"><?php echo JText::_('LNG_NAME'); ?></td>
				<td><input type="text" name="name" id="name" size="50" value="<?php echo $this->item->name ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td width="10%" align="left" class="key" nowrap ><?php echo JText::_("LNG_STATE")?> :</TD>
					<TD nowrap colspan=2>
					<label style="float:left" for="state_1"><?php echo JText::_('LNG_ACTIVE'); ?></label>
						<input 
							type		= "radio"
							name		= "state"
							id			= "state_1"
							value		= '1'
							<?php echo $this->item->state==true? " checked " :""?>
							accesskey	= "Y"
							onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
							onmouseout	="this.style.cursor='default'"

							
						/>
						
						&nbsp;
						<label style="float:left" for="state_2"><?php echo JText::_('LNG_DISABLED'); ?></label>
						<input 
							type		= "radio"
							name		= "state"
							id			= "state_2"
							value		= '0'
							<?php echo $this->item->state==false? " checked " :""?>
							accesskey	= "N"
							onmouseover	="this.style.cursor='hand';this.style.cursor='pointer'"
							onmouseout	="this.style.cursor='default'"

						/>
						
					</TD>
			</tr>
			<tr style="display:none">
				<TD class="key"><?php echo JText::_('LNG_COMPANY'); ?></TD>
				<td><input type="text"  name="companyId" id="companyId" value="<?php echo $this->item->companyId ?>"/></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<TD class="key"><?php echo JText::_('LNG_TIME_PERIOD'); ?></TD>
				<td><label style="min-width: 50px;"><?php echo JText::_('LNG_FROM'); ?></label><?php echo JHTML::_('calendar', $this->item->start_date== $appSetings->defaultDateValue?'': $this->item->start_date, 'start_date', 'start_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
				<label style="min-width: 50px;clear:none"><?php echo JText::_('LNG_TO'); ?></label>
				<?php echo JHTML::_('calendar', $this->item->end_date== $appSetings->defaultDateValue?'': $this->item->end_date, 'end_date', 'end_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<TD class="key"><?php echo JText::_('LNG_TYPE'); ?></TD>
				<td>
					<div id="banner-type-holder" class="option-holder">
					<?php
						echo $this->displayBannerTypes( $this->item->types, $this->item->type );
					?>
					</div>
					<a href="javascript:void(0);" onclick="showManageBanners();"><?php echo JText::_('LNG_EDIT_TYPES'); ?> </a>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_URL'); ?></td>
				<td><input type="text" name="url" id="url" size="50" value="<?php echo $this->item->url ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			
			<tr>
				<TD class="key"><?php echo JText::_('LNG_IMAGE'); ?></TD>
				<td>
				<input type="file" id="uploadfile" name="uploadfile" size="50">	
				<input type="hidden" name="imageLocation" id="imageLocation" value="<?php echo $this->item->imageLocation?>">	
				<td>
			<tr>
			<tr>
				<td></td>
				<td><div id="picture-preview">
				<?php
					if(isset($this->item->imageLocation)){
						echo "<img src='".$this->item->imageLocation."'/>";
					}
				?></div>
				</td>
			<tr>
		</TABLE>
	</fieldset>
	<script  type="text/javascript">
	<?php
	
	Joomla.submitbutton = function(pressbutton) 
	
	{
		var form = document.adminForm;
		submitform( pressbutton );
	}

	var deleteImagePath = "<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>";


	jQuery(document).ready(function(){
				jQuery('#uploadfile').change(function() {
					var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
					var path = jQuery('#uploadfile').val();
					if (path.search(fisRe) == -1)
					{   
						alert(' JPG, BMP, GIF, PNG only!');
						return false;
					}  
					<?php 
						$baseUrl = JURI::base();
						
							
					?>
					
					jQuery(this).upload('<?php echo $baseUrl?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime('now')?>&_root_app=<?php echo urlencode(JPATH_COMPONENT_ADMINISTRATOR)?>&_target=<?php echo urlencode(BANNER_PICTURES_PATH.($this->item->id).'/')?>', function(responce) 
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
																														setUpBanner(
																																	"<?php echo BANNER_PICTURES_PATH.($this->item->id).'/'?>" + jQuery(this).attr("path"),
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

	function setUpBanner(path, name){
		<?php 
				$baseUrl = JURI::base();
				
					
			?>
		jQuery("#imageLocation").val('<?php echo IMAGE_BASE_PATH ?>'+path);
		var img_new		 	= document.createElement('img');
		img_new.setAttribute('src', "<?php echo $baseUrl ."components/".JBusinessUtil::getComponentName()?>" + path );
		img_new.setAttribute('class', 'company-logo');
		img_new.setAttribute('alt', '<?php echo JText::_('LNG_BANNER') ?>');
		img_new.setAttribute('title', '<?php echo JText::_('LNG_BANNER') ?>');
		jQuery("#picture-preview").empty();
		jQuery("#picture-preview").append(img_new);
	}
	</script>
	
	</script>
	<input type="hidden" name="bannerId" value="<?php echo $this->item->id ?>" /> 
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="managebanners" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>


<div id="showBannersNewFrm" style="display:none;">
<div id="popup_container">
<!--Content area starts-->

<div class="head">
<div class="head_inner">
<h2> <?php echo JText::_('LNG_MANAGE_BANNERS_TYPES'); ?></h2>
               <a href="javascript:void(0)" class="cancel_btn" onclick="closePopup(); return false;"><span class="cancel_icon">&nbsp;</span><?php echo JText::_('LNG_CANCEL'); ?></a></div>
            </div>
            <div class="content">
                    <div class="descriptions" >

                       <div id="content_section_tab_data1">
                       	<span id="frm_error_msg_banner" class="text_error" style="display: none;"></span> 
						<div class="row" id="banner-container">
						</div>
						 
					 	<div class="option-row">
							<a href="javascript:" onclick="addNewBanner(0,'')"><?php echo JText::_('LNG_ADD_NEW_BANNER_TYPE'); ?></a>
						</div>
						<div class="proceed_row">
                           <!--button sec starts-->
                              <button name="btnSave" id="btnSave" onclick="saveBanners(this.form);" type="submit" class="submit">    
                                     <span><span>Save</span></span>
                              </button>
                              <input value="Cancel" class="cancel" name="btnCancel" id="btnCancel" onclick="closePopup(); return false;" type="button">
                          </div>
                          <!--button sec ends-->
                        </div>
                        <div class="buttom_sec" id="frmBannersFormSubmitWait" style="display: none;"> <span class="error_msg" style="background-image: none; color: rgb(0, 0, 0) ! important;">Please wait...</span> </div>
            </div>
          </div>
          </div>
     </div>

<?php 
}
?>
     
     