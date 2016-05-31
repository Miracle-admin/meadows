<?php defined('_JEXEC') or die('Restricted access'); 
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=updates');?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="" />
	<input type="hidden" name="boxchecked" value="1" />
	
	<?php echo JHTML::_( 'form.token' ); ?> 

	<fieldset class="adminform">
		 <legend><?php echo JText::_('LNG_UPDATES',true); ?></legend>
   		  <table class="" width="100%" border="0">
				<tbody>
					<tr>
						<td width="150px">
							<?php echo JText::_('LNG_ORDER_ID',true)?>
						</td>
						<td >
							 <input type="text" name="orderId" id="orderId" value="<?php echo isset($this->appSettings->order_id)?$this->appSettings->order_id:""?>">	<?php echo JText::_('LNG_ORDER_NOTICE',true)?>					
						</td>
		  				<td rowspan="3">
							 <div style="border:1px solid #ccc;width:300px;padding:10px;"><?php echo JText::_('LNG_UPDATE_NOTICE',true)?></div>
						</td> 
				  </tr>
				  <tr>
						<td>
							<?php echo JText::_('LNG_ORDER_EMAIL',true)?>
						</td>
						<td>
							<input type="text" name="orderEmail" id="orderEmail" value="<?php echo isset($this->appSettings->order_email)?$this->appSettings->order_email:""?>">	<?php echo JText::_('LNG_ORDER_EMAIL_NOTICE',true)?>					
						</td>
						<td colspan="3">
							&nbsp;
						</td>
						
				  </tr>	
				  	
				  <tr>
						<td colspan="3">
							&nbsp;
						</td>
				  </tr>
				  <tr>
				  		<td colspan="3">
				  		<?php echo JText::_('LNG_CURRENT_VERSION').' : <b><span class="red">'.$this->currentVersion."</span></b>" ?>
					  		<div id="orderData">
					  			<?php echo $this->expirationDate;?>
					  		</div>
				  		</td>
				  </tr>
				  <tr>
				  		<td colspan="2">
				  		&nbsp;
				  		</td>
				  </tr>
		
				</tbody>
			</table>
	</fieldset>			
	
	<fieldset class="adminform">
	<legend><?php echo JText::_('LNG_AVAILABLE_UPDATES',true)?></legend>
	<?php if (count($this->items)) : ?>
		<table class="table table-striped" >
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL',true); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_INSTALLER_HEADING_NAME'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_INSTALLER_HEADING_INSTALLTYPE'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_TYPE'); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JText::_('JVERSION'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_FOLDER'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_CLIENT'); ?>
					</th>
					<th width="25%">
						<?php echo JText::_('COM_INSTALLER_HEADING_DETAILSURL'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				jimport( 'joomla.application.componenet.helper' );
				$module = JComponentHelper::getComponent('com_jbusinessdirectory');
			//var_dump($this->items);
			foreach ($this->items as $i => $item) :
				if($module->id!=$item->extension_id)
					continue;
				
				$client = $item->client_id ? JText::_('JADMINISTRATOR') : JText::_('JSITE');
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php echo JHtml::_('grid.id', $i, $item->update_id); ?>
					</td>
					<td>
						<span class="editlinktip hasTooltip">
						<?php echo $this->escape($item->name); ?>
						</span>
					</td>
					<td class="center">
						<?php echo $item->extension_id ? JText::_('COM_INSTALLER_MSG_UPDATE_UPDATE') : JText::_('COM_INSTALLER_NEW_INSTALL') ?>
					</td>
					<td>
						<?php echo JText::_('COM_INSTALLER_TYPE_' . $item->type) ?>
					</td>
					<td class="center">
						<?php echo $item->version ?>
					</td>
					<td class="center">
						<?php echo @$item->folder != '' ? $item->folder : JText::_('COM_INSTALLER_TYPE_NONAPPLICABLE'); ?>
					</td>
					<td class="center">
						<?php echo $client; ?>
					</td>
					<td><?php echo $item->detailsurl ?>
						<?php if (isset($item->infourl)) : ?>
							<br />
							<a href="<?php echo $item->infourl; ?>" target="_blank">
							<?php echo $this->escape($item->infourl); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php else : ?>
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert" href="javascript:void(0)">&times;</a>
				<?php echo JText::_('COM_INSTALLER_MSG_UPDATE_NOUPDATES'); ?>
			</div>
		<?php endif; ?>
		
	</fieldset>		
	
</form>
