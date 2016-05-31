<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.Contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;



/**
 * Contact Plugin
 *
 * @since  3.2
 */
class PlgAppmeadowsApmpupaypal extends JPlugin
{

	public function onUpgradePurchased($jbProject,&$upsellPurchased,&$upsellAmmount)
	{
	    
		$app=JFactory::getApplication();
	    $user=JFactory::getUser();
	    $bussiness=$this->params->get('paypal_id', '');
		$mode=$this->params->get('test_mode', '0');
		$currency=$this->params->get('currency_code', 'USD');
		$action=$mode==0?"https://www.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr";
	    $config=JblanceHelper::getConfig();
		$currSym=$config->currencySymbol;
		$currCode=$config->currencyCode;
		
		$cancel=JRoute::_(JUri::base()."index.php?option=com_jblance&view=user&layout=dashboard&Itemid=148");
		$return=JRoute::_(JUri::base()."index.php?option=com_jblance&task=project.returnafterpayment&gateway=paypal");
		$notify=JRoute::_(JUri::base()."index.php?option=com_jblance&amp;task=project.paymentnotify&gateway=paypal");
		$uppurch='';
		foreach($upsellPurchased as $upp){$uppurch.=$upp." ";}
        $custom=$jbProject.'-'.$user->id.'-'.$uppurch;
		
		//redirect if session expired
		if(empty($jbProject))
		{
		$app->redirect(JRoute::_("index.php"),"Payment session expired");
		
		}
		
		
		$upgradesold=array('buyFeePerUrgentProject'=>'Urgent project','buyFeePerPrivateProject'=>'Private project',    'buyFeePerFeaturedProject'=>'Featured project','buyFeePerAssistedProject'=>'Assisted project');
		
		?>
		<form method="post" name="paypal_form" action="<?php echo $action;?>">
	<div class="jbl_h3title">Checkout</div>
	<!-- ************************************************************** plan checkout section ******************************************* -->
			<div class="sp10">&nbsp;</div>
		<div class="well well-small jbbox-gradient">
			<h2>Cart</h2>
			<table style="width: 100%;">
				<thead>
					<tr>
					<th class="text-left">Name</th>
					<th class="text-left">Pay Mode</th>
					<th>Purchase</th>
					<th>Total</th>
					</tr>
				</thead>
				<tbody>
				<tr>
				<td>Upgrade project</td><td>Paypal</td>
				<td>
				<?php
                 foreach($upsellPurchased as $up)
				 {
				 ?>
				 <?php echo $upgradesold[$up]; ?></br>
				 <?php 
				 }
				?>
				</td>
				<td>
				<?php
                 foreach($upsellPurchased as $ammount=>$upg)
				 {
				 ?>
				 <?php echo $currSym.$ammount; ?></br>
				 <?php 
				 }
				?>
				<hr>
				<?php echo $currSym.$upsellAmmount; ?>
				</td>
				</tr>
					
			</tbody></table>
		</div>
		
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $bussiness; ?>">
		<input type="hidden" name="return" value="<?php echo $return; ?>">
		<input type="hidden" name="cancel_return" value="<?php echo $cancel; ?>">
		<input type="hidden" name="notify_url" value="<?php echo $return; ?>">
		<input type="hidden" name="item_name" value="Payment for project upgrades on AppMeadows">
		<input type="hidden" name="amount" value="<?php echo $upsellAmmount;?>">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="currency_code" value="<?php echo $currency;?>">
		<input type="hidden" name="tax_rate" value="0">
		<input type="hidden" name="custom" value="<?php echo $custom; ?>">
		<br><br><input type="submit" value="Proceed for Payment">
		</form>
		
		<?php 
	}

	
	//verify paypal ipn
	public function onReceivedIpn($data)
	{
	   $app=JFactory::getApplication();
	   $mode=$this->params->get('test_mode', '0');
	   $url=$mode==0?"https://www.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr";
	   $url_parsed = parse_url($url);
       if($url_parsed['scheme'] == 'https'){
			$url_parsed['port'] = 443;
			$ssl = 'ssl://';
		} 
		else {
			$url_parsed['port'] = 80;
			$ssl = '';
		}
		
		$post_string = '';    
		foreach ($data as $field=>$value) { 
			$this->ipn_data["$field"] = $value;
			$post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
		}
		$post_string.="cmd=_notify-validate";

		$fp = fsockopen($ssl.$url_parsed['host'], $url_parsed['port'], $errnum, $errstr, 30); 
		if(!$fp){
			$this->last_error = "Error : fsockopen error no. $errnum: $errstr";
			return false;
			$app->enqueueMessage("Error : fsockopen error no. $errnum: $errstr", 'error');
		}
		else { 
			fputs($fp, "POST ".$url_parsed['path']." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$url_parsed['host']."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $post_string . "\r\n\r\n");
			
			while(!feof($fp)){ 
				$this->ipn_response .= fgets($fp, 1024); 
			} 
			
			fclose($fp); // close connection
		}

		if (preg_match("/VERIFIED/", $this->ipn_response)){
			return true;       
		}
		else {
		
			return false;
		}
	}
}
