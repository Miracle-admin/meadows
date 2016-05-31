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
JHtml::_('behavior.tooltip');
?>


<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'attribute.cancel' || !validateCmpForm()){
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=attribute');?>" method="post" name="adminForm" id="item-form">


<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_ATTRIBUTE_DETAILS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NAME'); ?></strong><br />Name of the attribute" id="menu_item_id-lbl" for="name" class="hasTooltip required" title=""><?php echo JText::_('LNG_NAME'); ?></label></div>
				<div class="controls">
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
								echo "<input type='text' name='name_$lng' id='name'.$lng' class='input_txt validate[required]' value='".$langContent."'  maxLength='50'>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<input class="validate[required] text-input" name="name" id="name" value="<?php echo $this->item->name?>" size="50" type="text">
						<?php } ?>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_CODE'); ?></strong><br />Code of the attribute" id="menu_item_id-lbl" for="code" class="hasTooltip required" title=""><?php echo JText::_('LNG_CODE'); ?></label></div>
				<div class="controls"><input placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="text-input" name="code" id="code" value="<?php echo $this->item->code?>" size="50" type="text"></div>
			</div>

			<div class="control-group" style="display:none">
				<div class="control-label"><label id="show_in_filter-lbl" for="show_in_filter" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_IN_FILTER'); ?></label></div>
				<div class="controls">
					<fieldset id="show_in_filter_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_in_filter" id="show_in_filter1" value="1" <?php echo $this->item->show_in_filter==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_in_filter1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_in_filter" id="show_in_filter0" value="0" <?php echo $this->item->show_in_filter==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_in_filter0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="is_mandatory-lbl" for="is_mandatory" class="hasTooltip" title=""><?php echo JText::_('LNG_MANDATORY'); ?></label></div>
				<div class="controls">
					<fieldset id="is_mandatory_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="is_mandatory" id="is_mandatory1" value="1" <?php echo $this->item->is_mandatory==true? 'checked="checked"' :""?> />
						<label class="btn" for="is_mandatory1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="is_mandatory" id="is_mandatory0" value="0" <?php echo $this->item->is_mandatory==false? 'checked="checked"' :""?> />
						<label class="btn" for="is_mandatory0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_in_front-lbl" for="show_in_front" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_IN_FRONT'); ?></label></div>
				<div class="controls">
					<fieldset id="show_in_front_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_in_front" id="show_in_front1" value="1" <?php echo $this->item->show_in_front==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_in_front1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_in_front" id="show_in_front0" value="0" <?php echo $this->item->show_in_front==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_in_front0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="state-lbl" for="state" class="hasTooltip" title=""><?php echo JText::_('LNG_STATE'); ?></label></div>
				<div class="controls">
					<select name="status" class="inputbox input-medium" class="validate[required] select">
						<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->status);?>
					</select>
				</div>
			</div>
			
			<TABLE class="admintable" id="table_feature_options" border=0>
		
			<TR>
				<TD><label id="state-lbl" for="state" class="hasTooltip" title=""><?php echo JText::_('LNG_TYPE'); ?></label></TD>
				<TD nowrap  align=left class="app-option">
				<?php 
					foreach($this->attributeTypes as $key=>$value){
				?>
					<input type="radio" class="validate[required] radio"
						name		= "type"
						id			= "type"
						value		= "<?php echo $value->id; ?>"
						<?php echo (isset($this->item->type) && $this->item->type==$value->id)? " checked " :""?>
						onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
						onmouseout	=	"this.style.cursor='default'"
						onclick ="doAction(this.value);"
					/>
					<?php echo $value->name; ?>
				<?php }?>		
				</TD>
				<TD nowrap>
					&nbsp;
				</TD>
			</TR>
			
			
			<TR>
				<TD colspan="3"><hr/></TD>
			</TR>
			<?php
			$i = 0;
			if( count($this->attributeOptions) > 0 )
			{
				foreach( $this->attributeOptions as $key => $value )
				{
				?>
				<TR id="options-attr-<?php echo $key?>"> 
					<TD  class="key" width=10% nowrap><?php echo JText::_("LNG_OPTION_NAME");?>:</TD>
					<TD nowrap align=left width=30% align=left valign=center>
						<input type='hidden' name='attribute_id[]' id='attribute_id[]' value='<?php echo $value->attribute_id?>' >
						<input type='hidden' name='option_id[]' id='option_id[]' value='<?php echo $value->id?>' >
						<input 
							type		= "text"
							name		= "option_name[]"
							id			= "option_name[]"
							value		= '<?php echo $value->name?>'
							size		= 32
							maxlength	= 128
							autocomplete= OFF
						/>
						<?php
						if( $i>0)
						{
						?>
						<img
							valign		=middle
							width		=12px 
							height  	=12px
							title		='Delete option'
							src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/deleteIcon.png"?>"
							onclick 	="deleteAttributeOption('options-attr-<?php echo $key?>');" 
							onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
							onmouseout	=	"this.style.cursor='default'"
						>
						<?php
						}
						else if( $i==0)
						{
						?>
							<img 
								width		=16px 
								height 		=16px
								title		='Add option'
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/addIcon.jpg"?>"
								onclick 	="addAttributeOption();" 
								onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
								onmouseout	=	"this.style.cursor='default'"
							>
						</TD>
						<?php
						}
						?>
					</TD>
				</TR>
				<?php
				$i++;
				}
			}
			else 
			{
				?>
				<tr id="options_attr">
					<TD  class="key" width=10% nowrap><?php echo JText::_("LNG_OPTION_NAME");?>:</TD>
					<td>
						<input 
								type		= "text"
								name		= "option_name[]"
								id			= "option_name[]"
								value		= ''
								size		= 32
								maxlength	= 128
								autocomplete= OFF
							/>
						&nbsp;
						<img 
							width		=16px 
							height 		=16px
							title		='Add option'
							src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/addIcon.jpg"?>"
							onclick 	="addAttributeOption();" 
							onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
							onmouseout	=	"this.style.cursor='default'"
						>
					</TD>
				</tr>	
					<?php
			}
			?>

		</TABLE>
			
			
		</fieldset>
	</div>


	<script language="javascript" type="text/javascript">
		
		function validateForm(){
			var form = document.adminForm;
		
			if( !validateField( form.name, 'string', false, "<?php echo JText::_("LNG_PLEASE_INSERT_ATTRIBUTE_NAME",true)?>" ) )
				return false;
			
			if( form.elements["option_name[]"].type !="text")
			{
				if( !validateField( form.elements["option_name[]"], 'string', false, "<?php echo JText::_("LNG_PLEASE_INSERT_OPTION_NAME",true)?>" ) )
					return false;
			}

			return true;
		}
	
		function doAction(value){
			disabled = false; 	
			if(value==1 || value==6)
				disabled = true; 	
			var optionsTemp = document.getElementsByName("option_name[]");
		    for (i = 0; i < optionsTemp.length; ++i) {
		    	optionsTemp[i].disabled = disabled;
		    }
		}
		
		function deleteAttributeOption(id)
		{		
			 return (elem=document.getElementById(id)).parentNode.removeChild(elem);
		}
		
		function addAttributeOption()
		{   var attrType = document.getElementsByName("type");
			if(attrType[0].checked){
				alert('<?php echo JText::_("LNG_INPUT_TYPE_NO_OPTIONS",true);?>')
				return false; 
			}
			var tb = document.getElementById('table_feature_options');
			//alert(tb);
			if( tb==null )
			{
				alert('Undefined table, contact administrator !');
			}
			
			var td1_new			= document.createElement('td');  
			td1_new.innerHTML	= '<?php echo JText::_("LNG_OPTION_NAME",true);?>:';
			td1_new.className	='key';
			
			var td2_new			= document.createElement('td');  
			td2_new.style.textAlign='left';
		

			var input_o_new 	= document.createElement('input');
			input_o_new.setAttribute('type',		'text');
			input_o_new.setAttribute('name',		'option_name[]');
			input_o_new.setAttribute('id',			'option_name[]');
			input_o_new.setAttribute('size',		'32');
			input_o_new.setAttribute('maxlength',	'128');
			//input_o_new.autocomplete				= 'off';
			var d = new Date();
			var id = d.getTime(); 
			
			
			var tr_new = tb.insertRow(tb.rows.length);
			tr_new.setAttribute('id', 'options-attr-'+(id));
			
			var img_del		 	= document.createElement('img');
			img_del.setAttribute('src', "<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/deleteIcon.png"?>");
			img_del.setAttribute('alt', 'Delete option');
			img_del.setAttribute('height', '12px');
			img_del.setAttribute('width', '12px');
			img_del.setAttribute('onclick', 'deleteAttributeOption(\''+ 'options-attr-'+(id) +'\')');
			img_del.setAttribute('onmouseover', "this.style.cursor='hand';this.style.cursor='pointer'");
			img_del.setAttribute('onmouseout', "this.style.cursor='default'");
			
			td2_new.appendChild(input_o_new);
			td2_new.innerHTML = td2_new.innerHTML + "&nbsp;&nbsp;";
			td2_new.appendChild(img_del);


			
			tr_new.appendChild(td1_new);
			tr_new.appendChild(td2_new);

		}
		
	</script>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>


<script>
jQuery(document).ready(function(){
	jQuery("#item-form").validationEngine('attach');
});

function validateCmpForm(){
	var isError = jQuery("#item-form").validationEngine('validate');
	return !isError;
}
</script>