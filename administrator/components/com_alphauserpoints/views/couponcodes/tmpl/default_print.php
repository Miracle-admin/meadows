<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<br />
<div class="printcoupon"><a href="javascript:window.print()"><?php echo  JText::_( 'AUP_PRINT' ) ; ?></a></div>
<br /><br />
<div class="cut"></div>
<div class="coupon"><div class="couponcolor">
	<div class="infocoupon">	
	<h1><?php echo $this->couponcode; ?></h1>
	<h2><?php echo getFormattedPointsAdm($this->points) . ' ' . JText::_( 'AUP_POINTS' ); ?> </h2>
	</div>
	<div class="qrcode"><img src="<?php echo JURI::base(); ?>components/com_alphauserpoints/assets/coupons/QRcode/250/<?php echo strtoupper($this->couponcode); ?>.png" alt="" align="absmiddle" /></div>
	<div class="infosite">
	<?php echo $this->sitename; ?> - <?php echo JURI::root(); ?>
	</div>
</div></div>