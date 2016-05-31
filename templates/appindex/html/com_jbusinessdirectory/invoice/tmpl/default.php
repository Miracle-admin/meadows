<?php // no direct access
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

defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

if($user->id !=$this->item->company->userId){
	$app = JFactory::getApplication();
	$app->redirect(JURI::root());
}

?>
<div>
	<h1><?php echo JText::_("LNG_ORDER_DETAILS")?></h1>
	<div>
		<table width="95%">
			<tbody>
				<tr>
					<td valign="top" align="left"><table>
							<tbody>
								<tr>
									<td><b><?php echo JText::_('LNG_NUMBER'); ?>: </b></td>
									<td><?php echo $this->item->id ?></td>
								</tr>
								<tr>
									<td><b><?php echo JText::_('LNG_DATE'); ?>:</b></td>
									<td><?php echo $this->item->created ?></td>
								</tr>
								<tr>
									<td><b><?php echo JText::_('LNG_ORDER_ID'); ?>:</b></td>
									<td><?php echo $this->item->order_id ?></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="40%">
						<strong><?php echo  $this->item->company->name?></strong><br/>
						<?php echo $this->item->company->street_number?> <?php echo $this->item->company->address?>, <?php echo  $this->item->company->city?>, <br/>
						<?php echo  $this->item->company->postalCode?>, <?php echo  $this->item->company->country_name?>
					</td>
				</tr>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>
	<table class="address" width="95%">
		<tbody>
			<tr bgcolor="#D9E5EE" class="heading">
				<td width="50%"><b><?php echo JText::_('LNG_BILLING_DETAILS'); ?></b></td>
			</tr>
			<tr>
				<td><?php echo JText::_('LNG_FIRST_NAME'); ?>: <?php echo $this->item->billingDetails->first_name ?></td>
			</tr>
			<tr>
				
				<td><?php echo JText::_('LNG_LAST_NAME'); ?>: <?php echo $this->item->billingDetails->last_name ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('LNG_COMPANY'); ?>: <?php echo $this->item->billingDetails->company_name ?></td>
			</tr>
			<tr>	
				<td><?php echo JText::_('LNG_CITY'); ?>: <?php echo $this->item->billingDetails->city ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('LNG_REGION'); ?>: <?php echo $this->item->billingDetails->region ?></td>
			</tr>
			<tr>	
				<td><?php echo JText::_('LNG_POSTAL_CODE'); ?>: <?php echo $this->item->billingDetails->postal_code ?></td>
			</tr>
			<tr>	
				<td><?php echo JText::_('LNG_COUNTRY'); ?>: <?php echo $this->item->billingDetails->country ?></td>
			</tr>
			<tr>	
				<td><?php echo JText::_('LNG_PHONE'); ?>: <?php echo $this->item->billingDetails->phone ?></td>
			</tr>
			<tr>	
				<td><?php echo JText::_('LNG_EMAIL'); ?>: <?php echo $this->item->billingDetails->email ?></td>
			</tr>
		</tbody>
	</table>
	<br/>
	<table class="product" width="95%">
		<thead>
			<tr bgcolor="#D9E5EE" class="heading">
				<td><b><?php echo JText::_('LNG_PRODUCT_SERVICE'); ?></b></td>
				<td><b><?php echo JText::_('LNG_DESCRIPTION'); ?></b></td>
				<td align="right"><b><?php echo JText::_('LNG_QUANTITY'); ?></b></td>
				<td align="right"><b><?php echo JText::_('LNG_UNIT_PRICE'); ?></b></td>
				<td align="right"><b><?php echo JText::_('LNG_TOTAL'); ?></b></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $this->item->service ?></td>
				<td><?php echo $this->item->description ?></td>
				<td align="right">1</td>
				<td align="right"><?php echo $this->item->initial_amount ?> </td>
				<td align="right"><?php echo $this->item->initial_amount ?> </td>
			</tr>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			
			<?php if($this->item->discount_amount>0){?>
				<tr>
					<td align="right" colspan="4"><b><?php echo JText::_('LNG_DISCOUNT'); ?>:</b></td>
					<td align="right"><?php echo JBusinessUtil::getPriceFormat($this->item->discount_amount) ?> </td>
				</tr>
			<?php } ?>
			<tr>
				<td align="right" colspan="4"><b><?php echo JText::_('LNG_SUB_TOTAL'); ?>:</b></td>
				<td align="right"><?php echo JBusinessUtil::getPriceFormat($this->item->initial_amount- $this->item->discount_amount) ?> </td>
			</tr>
			<?php if($this->appSettings->vat>0){?>
				<tr>
					<td align="right" colspan="4"><b><?php echo JText::_('LNG_VAT'); ?> (<?php echo $this->appSettings->vat?>%):</b></td>
					<td align="right"><?php echo JBusinessUtil::getPriceFormat($this->item->vat_amount) ?> </td>
				</tr>
			<?php } ?>
			<tr>
				<td align="right" colspan="4"><b><?php echo JText::_('LNG_TOTAL'); ?>:</b></td>
				<td align="right"><?php echo JBusinessUtil::getPriceFormat($this->item->amount) ?> </td>
			</tr>
		</tbody>
	</table>


	<div class="printbutton">
		<a onclick="window.print()" href="javascript:void(0);"><?php echo JText::_("LNG_PRINT")?></a>
	</div>

</div>
