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
	$title = JText::_("LNG_OFFERS").' | '.$config->sitename;
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

$fullWidth = true;
$enableSearchFilter = $this->appSettings->enable_search_filter_offers;
?>


<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1 class="title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php } ?>
 
<div id="offers" class="row-fluid">
	<?php if(!empty($this->category) || !empty($this->location)){?>
		
	<div id="search-path">
		<ul>
			<?php if(isset($this->category)){ ?>
			<li>
				<a class="search-filter-elem" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers') ?>"><?php echo JText::_('LNG_ALL_CATEGORIES') ?></a>
			</li>
			<?php } ?>
		<?php 
			if(isset($this->searchFilter["path"])){
			foreach($this->searchFilter["path"] as $path) {
				if($path[0]==1)
					continue;
			?>
			<li>
				<a  class="search-filter-elem" href="<?php echo JBusinessUtil::getOfferCategoryLink($path[0], $path[2]) ?>"><?php echo $path[1]?></a>
			</li>
		<?php }
			} 
		?>
			<li>
				<?php if(isset($this->category)) echo $this->category->name ?>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
	<?php } ?>
	
	
	<div class="row-fluid">
		<?php if($enableSearchFilter){
			$fullWidth = false;
				?>
			<div id="search-filter" class="search-filter moduletable span3">
				<h3><?php echo JText::_("LNG_SEARCH_FILTER")?></h3>
				<div class="search-category-box">
				
					<?php if(!empty($this->location["latitude"])){ ?>
						<h4><?php echo JText::_("LNG_DISTANCE")?></h4>
						<ul>
							<li>
								<?php if($this->radius != 50){ ?>
									<a href="javascript:changeRadius(50)" >50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
								<?php }else{ ?>
									<strong>50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
								<?php } ?>
							</li>
							<li>
								<?php if($this->radius != 25){ ?>
									<a href="javascript:changeRadius(25)">25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
								<?php }else{ ?>
									<strong>25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
								<?php } ?>
							</li>
							<li>
								<?php if($this->radius != 10){ ?>
									<a href="javascript:changeRadius(10)">10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
								<?php }else{ ?>
									<strong>10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
								<?php } ?>
							</li>
							<li>
								<?php if($this->radius != 0){ ?>
									<a href="javascript:changeRadius(0)"><?php echo JText::_("LNG_ALL")?></a>
								<?php }else{ ?>
									<strong><?php echo JText::_("LNG_ALL")?></strong>
								<?php } ?>
							</li>
						</ul>
					<?php } ?>
				
				<h4><?php echo JText::_("LNG_CATEGORIES")?></h4>
					<ul>
					<?php 
					if(isset($this->searchFilter["categories"])){
						foreach($this->searchFilter["categories"] as $filterCriteria){ 
							$fullWidth = false;
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
				
				<?php
					jimport('joomla.application.module.helper');
					// this is where you want to load your module position
					$modules = JModuleHelper::getModules('offers-filter');
					
					 if(isset($modules) && count($modules)>0){
						$fullWidth = false;
						foreach($modules as $module)
						{
							echo JModuleHelper::renderModule($module);
						}
					 }	
				 ?>
			</div>
		<?php }?>
	
		
		
		<div id="search-results" class="<?php echo $fullWidth ?'search-results-full span12':'search-results-normal span9' ?> ">
			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="adminForm" id="adminForm"  >
				<div id="search-details">
					
					<div class="search-toggles">
						<span class="sortby"><?php echo JText::_('LNG_SORT_BY');?>: </span>
						<select name="orderBy" class="orderBy inputbox input-medium" onchange="changeOrder(this.value)">
							<?php echo JHtml::_('select.options', $this->sortByOptions, 'value', 'text',  $this->orderBy);?>
						</select>
						<p class="view-mode">
							<label><?php echo JText::_('LNG_VIEW')?></label>
							<a id="grid-view-link" class="grid" title="Grid" href="javascript:showGrid()"><?php echo JText::_("LNG_GRID")?></a>
							<a id="list-view-link" class="list active" title="List" href="javascript:showList()"><?php echo JText::_("LNG_LIST")?></a>
						</p>
						
						<?php if($this->appSettings->show_search_map){?>
							<p class="view-mode">
								<a id="map-link" class="map" title="Grid" href="javascript:showMap(true)"><?php echo JText::_("LNG_SHOW_MAP")?></a>
							</p>
						<?php } ?>
						<div class="clear"></div>
					</div>
					
					<span class="search-keyword">
						<div class="result-counter"><?php echo $this->pagination->getResultsCounter()?></div> 
					
						<?php  if(isset($this->categoryId) && $this->categoryId!=0){ ?>
							<?php echo JText::_("LNG_RESULTS_FOR")?> 
							<strong>'<?php echo $this->category->name ?>'</strong>
						<?php }else{ ?>	
							<?php if(!empty($this->searchkeyword) || !empty($this->citySearch) || !empty($this->regionSearch) || !empty($this->zipCode)){
									echo JText::_('LNG_RESULTS_FOR');
									echo ' "<strong>';
									$searchText ="";
									$searchText.= !empty($this->searchkeyword)?$this->searchkeyword.", ":"";
									$searchText.= !empty($this->citySearch)?$this->citySearch.", ":"";
									$searchText.= !empty($this->regionSearch)?$this->regionSearch.", ":"";
									$searchText.= !empty($this->zipCode)?$this->zipCode.", ":"";	
									$searchText = trim(trim($searchText), ",");
									echo $searchText;
									echo '</strong>" ';
							 }
						}?>
					</span>	
				</div>
				
				<div id="companies-map-container" style="display:none">
					<?php require_once 'map.php' ?>
				</div>
				<?php 
					 if($this->appSettings->offer_search_results_grid_view==1){
					 	require_once 'grid_view_2.php';
					 }else{
					 	require_once 'grid_view_1.php';
					 }
					 
					 require_once 'list_view.php';
				?>
				<div class="pagination" <?php echo $this->pagination->total==0 ? 'style="display:none"':''?>>
					<?php echo $this->pagination->getListFooter(); ?>
					<div class="clear"></div>
				</div>
			
				<input type='hidden' name='view' value='offers' />
				<input type='hidden' name='categories' id="categories-filter" value='<?php echo isset($this->categories)?$this->categories:"" ?>' />
				<input type='hidden' name='categoryId' value='<?php echo isset($this->categoryId)?$this->categoryId:"0" ?>' />
				<input type='hidden' name='searchkeyword' value='<?php echo isset($this->searchkeyword)?$this->searchkeyword:'' ?>' />
				<input type='hidden' name='categorySearch' value='<?php echo isset($this->categorySearch)?$this->categorySearch: '' ?>' />
				<input type='hidden' name='citySearch' value='<?php echo isset($this->citySearch)?$this->citySearch: '' ?>' />
				<input type='hidden' name='regionSearch' value='<?php echo isset($this->regionSearch)?$this->regionSearch: '' ?>' />
				<input type='hidden' name='zipcode' value='<?php echo isset($this->zipCode)?$this->zipCode: '' ?>' />
				<input type='hidden' name='radius' id="radius" value='<?php echo isset($this->radius)?$this->radius: '' ?>' />
			</form>	
			<div class="clear"></div>	
		</div>	
	</div>
 </div>
 
<script>
jQuery(document).ready(function(){
	<?php if ($this->appSettings->offers_view_mode == 1) {?>
		showGrid();
	<?php }else{ ?>
		showList();
	<?php }?>
});
				
function changeRadius(radius){
	jQuery("#radius").val(radius);
	jQuery("#adminForm").submit();
}
		
function changeOrder(orderField){
	jQuery("#orderBy").val(orderField);
	jQuery("#adminForm").submit();	
}

function showMap(display){
	jQuery("#map-link").toggleClass("active");

	if(jQuery("#map-link").hasClass("active")){
		jQuery("#companies-map-container").show();
		jQuery("#map-link").html("<?php echo JText::_("LNG_HIDE_MAP")?>");
		loadMapScript();
	}else{
		jQuery("#map-link").html("<?php echo JText::_("LNG_SHOW_MAP")?>");
		jQuery("#companies-map-container").hide();
	}
}

function showList(){
	jQuery("#offer-list-view").show();
	jQuery("#layout").hide();

	jQuery("#grid-view-link").removeClass("active");
	jQuery("#list-view-link").addClass("active");
}

function showGrid(){
	jQuery("#offer-list-view").hide();
	jQuery("#layout").show();
	applyIsotope();
	
	jQuery("#grid-view-link").addClass("active");
	jQuery("#list-view-link").removeClass("active");
}
		
</script>
