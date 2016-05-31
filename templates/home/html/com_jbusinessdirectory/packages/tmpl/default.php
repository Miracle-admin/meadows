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

//retrieving current menu item parameters
$currentMenuId = null;
$activeMenu = JFactory::getApplication()->getMenu()->getActive();
if(isset($activeMenu))
	$currentMenuId = $activeMenu->id ; // `enter code here`
$document = JFactory::getDocument(); // `enter code here`
$app = JFactory::getApplication(); // `enter code here`
if(isset($activeMenu)){
	$menuitem   = $app->getMenu()->getItem($currentMenuId); // or get item by ID `enter code here`
	$params = $menuitem->params; // get the params `enter code here`
}else{
	$params = null;
}

//set page title
if(!empty($params) && $params->get('page_title') != ''){
	$title = $params->get('page_title', '');
}
if(empty($title)){
	$title = JText::_("LNG_PACKAGES").' | '.$config->sitename;
}
$document->setTitle($title);

//set page meta description and keywords
$description = $this->appSettings->meta_description;
$document->setDescription($description);
$document->setMetaData('keywords', $this->appSettings->meta_keywords);

if(!empty($params) && $params->get('menu-meta_description') != ''){
	$document->setMetaData( 'description', $params->get('menu-meta_description') );
	$document->setMetaData( 'keywords', $params->get('menu-meta_keywords') );
}

$user = JFactory::getUser();
?>

<div id="process-container" class="process-container">
	<div class="process-steps-wrapper">
		<div id="process-steps-container" class="process-steps-container">
			<div class="main-process-step" >
				<div class="process-step-number">1</div>
				<?php echo JText::_("LNG_CHOOSE_PACKAGE")?>
			</div>
			<div class="main-process-step" >
				<div class="process-step-number">2</div>
				<?php echo JText::_("LNG_BASIC_INFO")?>
			</div>
			<div class="main-process-step">
				<div class="process-step-number">3</div>
				<?php echo JText::_("LNG_LISTING_INFO")?>
			</div>
		</div>
	
		<div class="meter">
			<span style="width: 13%"></span>
		</div>
	</div>
	<div class="clear"></div>
</div>


<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=businessuser.checkUser'); ?>" method="post" name="package-form" id="package-form" >
<div class="featured-product-container">
	<?php foreach($this->packages as $package){?>		
		<div class="col-md-3 higlight-enable" >
			<div class="featured-product-col-border" id="hss">
				<div class="featured-product-head">
					<div class="head-text" >
						<div class="name">
							<?php echo $package->name ?> 
						</div>
						<div class="price" >
							<span class="item1">
								<span class="price-item"><?php echo $package->price == 0 ? JText::_("LNG_FREE"):JBusinessUtil::getPriceFormat($package->price)?></span>
								 <?php echo $package->price == 0 ? "":" / " ?>	
								<?php if($package->days > 0 ) {?>
								 	<span>
								 		<?php 
								 			echo $package->time_amount;
								 			echo ' ';
								 			$time_unit = JText::_('LNG_DAYS');
								 			switch($package->time_unit){
								 				case "D":
								 					$time_unit = JText::_('LNG_DAYS');
								 					break;
								 				case "W":
								 					$time_unit = JText::_('LNG_WEEKS');
								 					break;
								 				case "M":
								 					$time_unit = JText::_('LNG_MONTHS');
								 					break;
								 				case "Y":
								 					$time_unit = JText::_('LNG_YEARS');
								 					break;
								 			}
								 			
								 			echo $time_unit;
								 		?>
								 		</span>
								 <?php }?>	
								</span>
							<span class="item2">
								<?php echo $package->description ?>
							</span>
						</div>
					</div>
				</div>
				
				<?php foreach($this->packageFeatures as $key=>$featureName){?>
				<div class="featured-product-cell" >
					<?php 
						$class="not-contained-feature";
						if(isset($package->features) && in_array($key, $package->features)){
							$class="contained-feature";
						}
					?>
					<div><img class="<?php echo $class?>"/><?php echo $featureName?></div>
				</div>
				<?php } ?>
				
				
				<?php foreach($this->customAttributes as $customAttribute){
					$class="not-contained-feature";
					if(isset($package->features) && in_array($customAttribute->code,$package->features)){
						$class="contained-feature";
					}
					?>
					<div class="featured-product-cell " >
						<div><img class="<?php echo $class?>"/><?php echo $customAttribute->name?></div>
					</div>
				<?php }?>
				
			</div>
			<div class="select-buttons">
				<button type="button" class="ui-dir-button ui-dir-button-green" onclick="selectPackage(<?php echo $package->id?>)">
					<span class="ui-button-text"><?php echo JText::_("LNG_SELECT_PACKAGE")?></span>
				</button>
				<div class="clear"></div>
			</div>
		</div>
	<?php } ?>
	</div>
	<div class="clear"></div>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="filter_package" id="filter_package" value="" />
	<input type="hidden" name="companyId" value="<?php echo $this->companyId ?>" />
	<input type="hidden" name="task" value="businessuser.checkUser" /> 
</form>
<script>

jQuery(document).ready(function(){
	//jQuery('div.featured-product-col').removeClass("highlight");
	jQuery('div.higlight-enable').mouseenter(function() {
		jQuery(this).addClass("highlight");
	}).mouseleave(function() {
		jQuery(this).removeClass("highlight");
});

calibrateElements();
jQuery(window).resize(function(){
		calibrateElements();
	});
});

function calibrateElements(){
	jQuery("#features > .featured-product-cell").each(function(index){
		jQuery("#hss > .featured-product-cell:nth-child("+(index+2)+")").height(jQuery(this).height()-1);
		jQuery("#hsp > .featured-product-cell:nth-child("+(index+2)+")").height(jQuery(this).height()-1);
		jQuery("#hms > .featured-product-cell:nth-child("+(index+2)+")").height(jQuery(this).height()-1);
		jQuery("#hmp > .featured-product-cell:nth-child("+(index+2)+")").height(jQuery(this).height()-1);
		jQuery("#hpp > .featured-product-cell:nth-child("+(index+2)+")").height(jQuery(this).height()-1);
	});
};	

function selectPackage(packageId){
		jQuery("#filter_package").val(packageId);
		jQuery("#package-form").submit();
}

</script>