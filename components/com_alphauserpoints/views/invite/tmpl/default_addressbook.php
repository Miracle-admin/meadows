<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 - Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) {
   define('DS', DIRECTORY_SEPARATOR);
}

?>
<div style="padding: 10px;">
<fieldset>
	<div style="float: right">
		<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
			<?php echo JText::_( 'AUP_CANCEL' );?></button>
	</div>
	<div class="configuration" >
		<?php echo JText::_( 'AUP_IMPORT_FROM_YOUR_ADDRESS_BOOK' );?>
	</div>
</fieldset>
<?php
	// check if OpenInviter Component exist
	$file_install_com_openinviter = JPATH_SITE.DS.'components'.DS.'com_openinviter'.DS.'OpenInviter'.DS.'openinviter.php';
	if ( file_exists ( $file_install_com_openinviter ) ) {
		require_once(JPATH_SITE.DS.'components'.DS.'com_openinviter'.DS.'OpenInviter'.DS.'openinviter.php');
	}

	require_once(JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helpers'.DS.'openinviter.php'); 
	
?>
</div>


