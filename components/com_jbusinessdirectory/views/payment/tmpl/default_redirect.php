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

?>

<div class='div_redirect_paysite'>
	<?php echo JText::_("Please wait while we process the payment.")?>
</div>
				
<form id="paymentFrm" name="paymentFrm" method="post" action="<?php echo $this->paymentProcessor->getPaymentGatewayUrl();?>" >
	<?php echo $this->paymentProcessor->getHtmlFields();?>

</form>


<script type="text/javascript" >
	window.setTimeout("document.paymentFrm.submit()", 1000);
</script>