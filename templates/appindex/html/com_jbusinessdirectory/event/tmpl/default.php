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

$document = JFactory::getDocument();
$config = new JConfig();

$uri = JURI::getInstance();
$url = $uri->toString( array('scheme', 'host', 'port', 'path'));

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

$title = stripslashes($this->event->name)." | ".$config->sitename;
$description = !empty($this->event->short_description)?$this->event->short_description:$appSettings->meta_description;

$document->setTitle($title);
$document->setDescription($description);
$document->setMetaData('keywords', $appSettings->meta_keywords);

if(!empty($this->event->pictures)){
	$document->addCustomTag('<meta property="og:image" content="'.JURI::root().PICTURES_PATH.$this->event->pictures[0]->picture_path .'" /> ');
}
$document->addCustomTag('<meta property="og:type" content="website"/>');
$document->addCustomTag('<meta property="og:url" content="'.$url.'"/>');
$document->addCustomTag('<meta property="og:site_name" content="'.$config->sitename.'"/>');
?>
<?php require_once JPATH_COMPONENT_SITE."/include/fixlinks.php"?>
<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php"?>

<div> <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events'); ?>"><?php echo JText::_("BACK") ?></a></div>
<div id="event-container" class="event-container row-fluid">
	<div class="row-fluid">
		<?php if(!empty($this->event->pictures)){?>
			<div id="event-image-container" class="event-image-container span6">
				<?php 
						$this->pictures = $this->event->pictures;
						require_once JPATH_COMPONENT_SITE.'/include/image_gallery.php'; 
						
					?>
			</div>
		<?php } ?>
		<div id="event-content" class="event-content span6">
			<h2>
				<?php echo $this->event->name?>
			</h2>
				<div class="event-details">
						<div class="event-location">
							<i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($this->event)?>
						</div>
						
						<div class="event-date">
							<i class="dir-icon-calendar"></i> <?php echo JBusinessUtil::getDateGeneralFormat($this->event->start_date).(!empty($this->event->start_date) && $this->event->start_date!=$this->event->end_date?" - ".JBusinessUtil::getDateGeneralFormat($this->event->end_date):"")?>, 
							<?php echo JBusinessUtil::convertTimeToFormat($this->event->start_time)." ".JText::_("LNG_UNTIL")." ".JBusinessUtil::convertTimeToFormat($this->event->end_time) ?>
						</div>
						
						<?php if(!empty($this->event->price)){?>
							<div class="event-price">
								<?php echo JText::_("LNG_PRICE")?>: <strong><?php echo JBusinessUtil::getPriceFormat($this->event->price) ?></strong>
							</div>
						<?php } ?>
						
						<div class="event-type">
							<?php echo JText::_("LNG_TYPE")?>: <strong><?php echo $this->event->eventType?></strong>
						</div>
				</div>
	          	<div class="company-details">
					<table>
						<tr>
							<td><strong><?php echo JText::_('LNG_COMPANY_DETAILS') ?></strong></td>
						</tr>
						<tr>
							<td><a href="<?php echo JBusinessUtil::getCompanyLink($this->event->company)?>"> <?php echo $this->event->company->name?></a></td>
						</tr>
						<tr>
							<td><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getAddressText($this->event->company) ?></td>
						</tr>
						<?php if(!empty($this->event->company->phone)){?>
						<tr>
							<td><i class="dir-icon-phone dir-icon-large"></i> <a href="tel:<?php  echo $this->event->company->phone; ?>"><?php  echo $this->event->company->phone; ?></a></td>
						</tr>
						<?php } ?>
						<?php if(!empty($this->event->company->website)){?>
							<tr>
								<td><a target="_blank" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$this->event->company->id) ?>"><i class="dir-icon-link "></i>  <?php echo JText::_('LNG_WEBSITE')?></a></td>
							</tr>
						<?php } ?>
					</table>
		
				</div>
			</div>
		</div>
		<div class="event-description">
			<?php echo $this->event->description?>
		</div>
	<div class="clear"></div>
</div>

<script>


// starting the script on page load
jQuery(document).ready(function(){
	jQuery("img.image-prv").click(function(e){
		jQuery("#image-preview").attr('src', this.src);	
	});
});		

</script>