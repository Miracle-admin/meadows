<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once 'header.php';
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php'; 
?>

<?php 
require_once 'breadcrumbs.php';
?>

<div class="company-name">
	<span style="display: none">
		<?php  echo isset($this->company->comercialName) ? $this->company->comercialName : ""; ?> 	
	</span>
	<span>
		<?php  echo isset($this->company->name)?$this->company->name:"" ; ?>	
	</span>
</div>
<div class="clear"></div>
<div>
	<?php echo JText::_("LNG_COMPANY_INACTIVE")?>
</div>