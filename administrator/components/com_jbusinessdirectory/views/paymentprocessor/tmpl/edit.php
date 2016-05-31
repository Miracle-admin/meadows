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
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
	<fieldset class="adminform">
		<legend> <?php echo JText::_('LNG_PAYMENT_PROCESSOR',true); ?></legend>

		<table class="admintable" id="table_processor_fields">
			<TR>
				<TD class="key" width="10%" nowrap=""><?php echo JText::_('LNG_NAME',true); ?> </TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "name"
						id			= "name"
						value		= '<?php echo $this->item->name;?>'
						size		= "40"
					/>
				</TD>
			</TR>
			<TR>
				<TD class="key" width="10%" nowrap=""><?php echo JText::_('LNG_TYPE',true); ?> </TD>
				<TD nowrap align=left>
					<input 
						type		= "text"
						name		= "type"
						id			= "type"
						value		= '<?php echo $this->item->type;?>'
						size		= "40"
						<?php if($this->item->id){ ?>
							readonly	="readonly"
						<?php }?>
					/>
				</TD>
			</TR>
			<TR>
				<TD class="key" width="10%" nowrap=""><?php echo JText::_('LNG_TIMEOUT',true); ?> </TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "timeout"
						id			= "timeout"
						value		= '<?php echo $this->item->timeout;?>'
						size		= "40"
					/><?php echo JText::_('LNG_TIMEOUT_PROCESSOR_DESC',true); ?>
				</TD>
			</TR>
			
			<tr>
				<td class="key"><?php echo JText::_('LNG_MODE',true); ?></td>
				<td>
					<select name="mode" id="mode" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->modes, 'value', 'text', $this->item->mode);?>
					</select>
				</td>
				<TD>&nbsp;</TD>
			</tr>
			
			<tr>
				<td class="key"><?php echo JText::_('LNG_STATUS',true); ?></td>
				<td>
					<select name="status" id="processorStatus" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->item->status);?>
					</select>
				</td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_ID',true); ?></td>
				<td><?php echo $this->item->id ?></td>
				<TD>&nbsp;</TD>
			</tr>	
			<tr>
				<td colspan="3">
					</br>
					<?php echo JText::_('LNG_DEFINE_PROCESSOR_EXTRA_FIELDS',true); ?>
					</br>
				</td>
			</tr>
			<?php 
				$i = 0;
				if( count($this->item->processorFields) > 0 )
				{
					foreach( $this->item->processorFields as $key => $value )
					{
					?>
					<TR>
						<TD  class="key" width=10% nowrap><?php echo JText::_("LNG_".strtoupper($value->column_name),true);?> :
							<input type='hidden' name='column_name[]' id='column_name[]' value='<?php echo $value->column_name?>' >
						</TD>
						<TD nowrap align=left width=30% align=left valign=center>
							<input 
								type		= "text"
								name		= "column_value[]"
								id			= "column_value[]"
								value		= '<?php echo $value->column_value?>'
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
								src ="<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/del_icon.png"?>"
								onclick 	="delFeatureRoomOption(<?php echo $i+8?>);" 
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
									src ="<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/addIcon.jpg"?>"
									onclick 	="addFeatureRoomOption();" 
									onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
									onmouseout	=	"this.style.cursor='default'"
								>
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
						<TD  class="key" width=10% nowrap><?php echo JText::_('LNG_COLUMN_NAME',true);?> :</TD>
						<td>
							<input 
									type		= "text"
									name		= "column_name[]"
									id			= "column_name[]"
									value		= ''
									size		= 32
									maxlength	= 128
									autocomplete= OFF
								/>
						</td>
						
						<td>
							<?php echo JText::_('LNG_COLUMN_VALUE',true);?> :
							<input 
									type		= "text"
									name		= "column_value[]"
									id			= "column_value[]"
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
								src ="<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/addIcon.jpg"?>"
								onclick 	="addFeatureRoomOption();" 
								onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
								onmouseout	=	"this.style.cursor='default'"
							>
						</TD>
					</tr>	
						<?php
				}
				?>
		</table>
	</fieldset>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>

<script>
function delFeatureRoomOption(pos)
{
	var attrType = document.getElementsByName("attribute_type_id");
	var tb = document.getElementById('table_processor_fields');
	//alert(tb);
	if( tb==null )
	{
		alert('Undefined table, contact administrator !');
	}
	
	if( pos >= tb.rows.length )
		pos = tb.rows.length-1;
	tb.deleteRow( pos );
}

function addFeatureRoomOption()
{   var attrType = document.getElementsByName("attribute_type_id");
	var tb = document.getElementById('table_processor_fields');
	//alert(tb);
	if( tb==null )
	{
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new			= document.createElement('td');  
	td1_new.innerHTML	= '<?php echo JText::_('LNG_COLUMN_NAME',true);?>';
	td1_new.class = "key";
	
	var td2_new			= document.createElement('td');  
	td2_new.style.textAlign='left';

	var input_o_new 	= document.createElement('input');
	input_o_new.setAttribute('type',		'text');
	input_o_new.setAttribute('name',		'column_name[]');
	input_o_new.setAttribute('id',			'column_name[]');
	input_o_new.setAttribute('size',		'32');
	input_o_new.setAttribute('maxlength',	'128');

	var td3_new			= document.createElement('td');  
	td3_new.style.textAlign='left';
	td3_new.innerHTML	= '<?php echo JText::_('LNG_COLUMN_VALUE',true);?>:&nbsp;&nbsp;';
	
	var input_col_val_new 	= document.createElement('input');
	input_col_val_new.setAttribute('type',		'text');
	input_col_val_new.setAttribute('name',		'column_value[]');
	input_col_val_new.setAttribute('id',		'column_value[]');
	input_col_val_new.setAttribute('size',		'32');
	input_col_val_new.setAttribute('maxlength',	'128');
	//input_o_new.autocomplete				= 'off';
	
	
	var img_del		 	= document.createElement('img');
	img_del.setAttribute('src', "<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/icon_delete.png"?>");
	img_del.setAttribute('alt', 'Delete option');
	img_del.setAttribute('height', '12px');
	img_del.setAttribute('width', '12px');
	img_del.setAttribute('onclick', 'delFeatureRoomOption('+ (tb.rows.length) +')');
	img_del.setAttribute('onmouseover', "this.style.cursor='hand';this.style.cursor='pointer'");
	img_del.setAttribute('onmouseout', "this.style.cursor='default'");
	
	td2_new.appendChild(input_o_new);
	td2_new.innerHTML = td2_new.innerHTML + "&nbsp;&nbsp;";

	td3_new.appendChild(input_col_val_new);
	td3_new.innerHTML = td3_new.innerHTML + "&nbsp;&nbsp;";
	td3_new.appendChild(img_del);


	var tr_new = tb.insertRow(tb.rows.length);
	tr_new.setAttribute('id', 'options_attr');
	tr_new.appendChild(td1_new);
	tr_new.appendChild(td2_new);
	tr_new.appendChild(td3_new);
}

</script>