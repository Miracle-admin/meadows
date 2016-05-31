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

$title = stripslashes($this->offer->subject)." | ".$config->sitename;
$description = !empty($this->offer->description)?strip_tags(JBusinessUtil::truncate($this->offer->description,300)):$appSettings->meta_description;

$document->setTitle($title);
$document->setDescription($description);
$document->setMetaData('keywords', $appSettings->meta_keywords);

if(!empty($this->offer->pictures)){
	$document->addCustomTag('<meta property="og:image" content="'.JURI::root().PICTURES_PATH.$this->offer->pictures[0]->picture_path .'" /> ');
}
$document->addCustomTag('<meta property="og:type" content="website"/>');
$document->addCustomTag('<meta property="og:url" content="'.$url.'"/>');
$document->addCustomTag('<meta property="og:site_name" content="'.$config->sitename.'"/>');
?>

<?php require_once JPATH_COMPONENT_SITE."/include/fixlinks.php"?>
<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php"?>

<div> <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers'); ?>"><?php echo JText::_("BACK") ?></a></div>
<div id="offer-container" class="offer-container row-fluid">
	<div class="row-fluid">
		<?php if(!empty($this->offer->pictures)){?>
			<div id="offer-image-container" class="offer-image-container span6">
				<?php 
	
					$this->pictures = $this->offer->pictures;
					require_once JPATH_COMPONENT_SITE.'/include/image_gallery.php'; 
					
				?>
			</div>
		<?php }?>
		<div id="offer-content" class="offer-content span6">
			<div class="dir-print"><a href="javascript:printOffer(<?php echo $this->offer->id ?>)"><?php echo JText::_("LNG_PRINT")?></a></div>
			<h1>
				<?php echo $this->offer->subject?>
			</h1>
	          <div class="offer-details">
					<table>
						<?php if(!empty($this->offer->price)){?>
						<tr>
							<th><?php echo JText::_('LNG_PRICE') ?>:</th>
							<td class="price-old"><?php echo JBusinessUtil::getPriceFormat($this->offer->price) ?></td>
						</tr>
						<?php } ?>
						
						<?php if(!empty($this->offer->specialPrice) || !empty($this->offer->price)){?>
						<tr>
							<th><?php echo JText::_('LNG_SPECIAL_PRICE') ?>:</th>
							<td><?php echo JBusinessUtil::getPriceFormat($this->offer->specialPrice)?></td>
						</tr>
						<?php } ?>
					</table>
					
					<div class="offer-location">
						<span itemprop="address"><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($this->offer)?></span>
					</div>
					<div class="offer-dates">
						<i class="dir-icon-calendar"></i>
						<?php 
							echo  JBusinessUtil::getDateGeneralFormat($this->offer->startDate)." - ". JBusinessUtil::getDateGeneralFormat($this->offer->endDate);
						?>
					</div>
					
					<div class="offer-categories">
						<div><strong><?php echo JText::_("LNG_CATEGORIES")?></strong></div>
						<?php 
								$categoryIds = explode(',',$this->offer->categoryIds);
								$categoryNames = explode('#',$this->offer->categoryNames);
								$categoryAliases = explode('#',$this->offer->categoryAliases);
								for($i=0;$i<count($categoryIds);$i++){
									?>
										 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($categoryIds[$i], $categoryAliases[$i]) ?>"><?php echo $categoryNames[$i]?><?php echo $i<(count($categoryIds)-1)? ',&nbsp;':'' ?> </a>
									<?php 
								}
						?>
					</div>
					
					<?php if(!empty($this->offer->attachments)){?>
						<div class="offer-attachments">
							<div><strong><?php echo JText::_("LNG_FILES")?></strong></div>
							<div> 
								<?php foreach($this->offer->attachments as $attachment){?>	
									<a target="_blank" href="<?php echo JURI::root()."/".ATTACHMENT_PATH.$attachment->path?>"><?php echo !empty($attachment->name)?$attachment->name:basename($attachment->path)?></a> </li>
								<?php }?>
							</div>
						</div>
					<?php } ?>
				</div>
			
				<div class="company-details">
					<table>
						<tr>
							<td><strong><?php echo JText::_('LNG_COMPANY_DETAILS') ?></strong></td>
						</tr>
						<tr>
							<td><a href="<?php echo JBusinessUtil::getCompanyLink($this->offer->company)?>"> <?php echo $this->offer->company->name?></a></td>
						</tr>
						<tr>
							<td><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getAddressText($this->offer->company)?></td>
						</tr>
						<?php if(!empty($this->offer->company->phone)){?>
							<tr>
								<td><i class="dir-icon-phone dir-icon-large"></i> <a href="tel:<?php  echo $this->offer->company->phone; ?>"><?php  echo $this->offer->company->phone; ?></a></td>
							</tr>
						<?php } ?>
						<?php if(!empty($this->offer->company->website)){?>
							<tr>
								<td><a target="_blank" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$this->offer->company->id) ?>"><i class="dir-icon-link "></i> <?php echo JText::_('LNG_WEBSITE')?></a></td>
							</tr>
						<?php } ?>
					</table>
		
				</div>
			
		</div>
			
	
	</div>
	
	<div>
		<div class="offer-description">
			<h4> <?php echo JText::_('LNG_OFFER_DESCRIPTION') ?> </h4>
			<?php echo $this->offer->description?>
		</div>
		
		
	</div>
	<div class="clear"></div>
</div>

<div id="offer-dialog" class="offer" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<iframe id="offerIfr" height="500" src="">
			
			</iframe>
		</div>
	</div>
</div>


<script>


// starting the script on page load
jQuery(document).ready(function(){
	jQuery("img.image-prv").click(function(e){
		jQuery("#image-preview").attr('src', this.src);	
	});

	
});		

function printOffer(offerId){
	//var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offer&tmpl=component'); ?>";
	//baseUrl = baseUrl + "&offerId="+offerId;
	//jQuery("#offerIfr").attr("src",baseUrl);
	//jQuery.blockUI({ message: jQuery('#offer-dialog'), css: {width: '900px', top: '5%', position: 'absolute'} });
	//jQuery('.blockOverlay').click(jQuery.unblockUI); 

	 var winref = window.open('<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offer&tmpl=component'); ?>&offerId='+offerId,'windowName','width=1050,height=700');
	 if (window.print) winref.print();
}
</script>