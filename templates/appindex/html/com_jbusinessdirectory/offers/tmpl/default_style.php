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
$fullWidth = true;

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$enableSearchFilter = $appSettings->enable_search_filter_offers;
?>

<div id="offers">

<?php if($this->categoryId ){?>
 <div id="search-path">
	<ul>
	<?php foreach($this->searchFilter["path"] as $path) {?>
		<li>
			<a href="<?php echo JBusinessUtil::getOfferCategoryLink($path[0], $path[2]) ?>"><?php echo $path[1]?></a>
		</li>
	<?php } ?>
		<li>
			<?php  if(isset($this->category)) echo $this->category->name ?>
		</li>
	</ul>
</div>
<div class="clear"></div>

<?php if($enableSearchFilter){
	$fullWidth = false;
		?>
	<div id="search-filter" class="search-filter moduletable">
		<h3><?php echo JText::_("LNG_SEARCH_FILTER")?></h3>
		<div class="search-category-box">
		<h4><?php echo JText::_("LNG_CATEGORIES")?></h4>
			<ul>
			<?php 
			if(isset($this->searchFilter["categories"])){
				foreach($this->searchFilter["categories"] as $filterCriteria){ 
					?>
				<li>
					<?php if( isset($this->category) && $filterCriteria[0][0]->id == $this->category->id){ ?>
						<strong><?php echo $filterCriteria[0][0]->name; ?>&nbsp;</strong><?php echo '('.$filterCriteria[1].')' ?>
					<?php }else{?>
					<a href="<?php echo JBusinessUtil::getOfferCategoryLink($filterCriteria[0][0]->id, $filterCriteria[0][0]->alias) ?>"><?php echo $filterCriteria[0][0]->name; ?></a>
					<?php echo '('.$filterCriteria[1].')' ?>
					<?php } ?>
				</li>
			<?php }
				}
			?>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
<?php }?>
<?php } else {
	jimport('joomla.application.module.helper');
	// this is where you want to load your module position
	$modules = JModuleHelper::getModules('categories-offers');
	
	 if(isset($modules) && count($modules)>0){
		$fullWidth = false;
		foreach($modules as $module)
		{
			echo JModuleHelper::renderModule($module);
		}
	 }	
 }
 ?>
<div class='offers-container <?php echo $fullWidth ?'full':'noClass' ?> '>
	<?php 
		if(isset($this->offers) && count($this->offers)>0){?>
			<div class="row-fluid"> 
				<?php foreach ($this->offers as $i=>$offer){?>
					<div class="offer-box span3" >
						<div class="offer-img-container">
							<a class="offer-image"
								href="<?php echo JBusinessUtil::getOfferLink($offer->id, $offer->alias) ?>">
								<img alt="<?php ?>"
								src="<?php echo JURI::root()."/".PICTURES_PATH.$offer->picture_path?>"> </a>
						</div>
						
						<div class="offer-subject">
							<a
								title="<?php echo $offer->subject?>"
								href="<?php echo JBusinessUtil::getOfferLink($offer->id, $offer->alias) ?>"><?php echo $offer->subject?>
								</a>
						</div>
						
					</div>
					<?php if(($i+1)%4==0 && $i!=0){?>
					</div>
					<div class="row-fluid"> 
					<?php }?>
				<?php } ?>
			</div>
		<?php } else {
			echo JText::_("LNG_NO_OFFER_FOUND");
		} ?>
</div>
		
<div class="clear"></div>		
</div>