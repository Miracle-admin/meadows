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
jimport( 'joomla.html.html.tabs' );

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<?php 	
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
			
				echo JHtml::_('tabs.start', 'tab_hotel_id', $options);
				
				echo JHtml::_('tabs.panel', JText::_('LNG_GENERAL_SETTINGS'), 'panel_1_id');
				require_once 'general.php';
				
				echo JHtml::_('tabs.panel', JText::_('LNG_METADATA_SETTINGS'), 'panel_1_id');
				require_once 'metadata.php';
				
				echo JHtml::_('tabs.panel', JText::_('LNG_FRONT_END'), 'panel_2_id');
				require_once 'frontend.php';
				
				echo JHtml::_('tabs.panel', JText::_('LNG_COMPANY_ATTRIBUTES'), 'panel_3_id');
				require_once 'defaultattributes.php';

				echo JHtml::_('tabs.panel', JText::_('LNG_LANGUAGES'), 'panel_3_id');
				require_once 'languages.php';
					
				echo JHtml::_('tabs.end');
				
			?>

		
	</div>
	<input type="hidden" name="sendmail_from" value="<?php echo $this->item->sendmail_from?>" />
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="applicationsettings_id" value="<?php echo $this->item->applicationsettings_id?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script>
	jQuery(document).ready(function(){
		jQuery("#enable_packages1").click(function(){
			jQuery("#assign-packages").show();
		});

		jQuery("#enable_packages2").click(function(){
			jQuery("#assign-packages").hide();
		});
	});
</script>

