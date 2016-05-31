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
	$title = JText::_("LNG_SEARCH").' | '.$config->sitename;
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
$enableSearchFilter = $this->appSettings->enable_search_filter;
?>

<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1 class="title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php } ?>

<div id="search-path">
	<ul>
		<?php if(isset($this->category)){ ?>
		<li>
			<a class="search-filter-elem" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search') ?>"><?php echo JText::_('LNG_ALL_CATEGORIES') ?></a>
		</li>
		<?php } ?>
	<?php 
		if(isset($this->searchFilter["path"])){
		foreach($this->searchFilter["path"] as $path) {
			if($path[0]==1)
				continue;
			?>
		<li>
			<a  class="search-filter-elem" href="<?php echo JBusinessUtil::getCategoryLink($path[0], $path[2]) ?>"><?php echo $path[1]?></a>
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
<div class="row-fluid">
<?php if($enableSearchFilter){?>
<div id="search-filter" class="search-filter moduletable span3">
	<h3><?php echo JText::_("LNG_SEARCH_FILTER")?></h3>
	<div class="search-category-box">
	 <?php if(!empty($this->location["latitude"])){ ?>
		<h4><?php echo JText::_("LNG_DISTANCE")?></h4>
		<ul>
			<li>
				<?php if($this->radius != 50){ ?>
					<a href="javascript:setRadius(50)">50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php }else{ ?>
					<strong>50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 25){ ?>
					<a href="javascript:setRadius(25)">25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php }else{ ?>
					<strong>25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 10){ ?>
					<a href="javascript:setRadius(10)">10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php }else{ ?>
					<strong>10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 0){ ?>
					<a href="javascript:setRadius(0)"><?php echo JText::_("LNG_ALL")?></a>
				<?php }else{ ?>
					<strong><?php echo JText::_("LNG_ALL")?></strong>
				<?php } ?>
			</li>
		</ul>
	<?php } ?>
	
	<h4><?php echo JText::_("LNG_CATEGORIES")?></h4>
		<?php if($this->appSettings->search_type==0){ ?>
		<ul>
		<?php 
		if(isset($this->searchFilter["categories"])){
			foreach($this->searchFilter["categories"] as $filterCriteria){ 
				if($filterCriteria[1]>0){
				?>
				<li>
					<?php if(isset($this->category) && $filterCriteria[0][0]->id == $this->category->id){ ?>
						<strong><?php echo $filterCriteria[0][0]->name; ?>&nbsp;</strong><?php echo '('.$filterCriteria[1].')' ?>
					<?php }else {?>
						<a href="javascript:chooseCategory(<?php echo $filterCriteria[0][0]->id?>)"><?php echo $filterCriteria[0][0]->name; ?></a>
						<?php echo '('.$filterCriteria[1].')' ?>
					<?php } ?>
				</li>
			<?php }
				}
			}?>
		</ul>
		<?php }else{?>
			<ul>
			<?php 
			if(isset($this->searchFilter["categories"])){
				foreach($this->searchFilter["categories"] as $filterCriteria){ 
					if($filterCriteria[1]>0){
					?>
					<li <?php if(in_array($filterCriteria[0][0]->id,$this->selectedCategories)) echo 'class="selectedlink"';  ?>>
						<div <?php if(in_array($filterCriteria[0][0]->id,$this->selectedCategories)) echo 'class="selected"';  ?>>
							<a href="javascript:void(0)" onclick="<?php echo in_array($filterCriteria[0][0]->id,$this->selectedCategories)?"removeFilterRule(".$filterCriteria[0][0]->id.")":"addFilterRule(".$filterCriteria[0][0]->id.")";?>"> <?php echo $filterCriteria[0][0]->name ?>  <?php echo in_array($filterCriteria[0][0]->id,$this->selectedCategories) ? '<span class="cross">(remove)</span>':"";  ?></a>
							<?php echo '('.$filterCriteria[1].')' ?>
						</div>
						<?php if(isset($filterCriteria[0]["subCategories"])){?>
							<ul>
							<?php foreach($filterCriteria[0]["subCategories"] as $subcategory){?>
								<li <?php if(in_array($subcategory[0]->id,$this->selectedCategories)) echo 'class="selectedlink"';  ?>>
									<div <?php if(in_array($subcategory[0]->id,$this->selectedCategories)) echo 'class="selected"';  ?>>
										<a href="javascript:void(0)" onclick="<?php echo in_array($subcategory[0]->id,$this->selectedCategories)?"removeFilterRule(".$subcategory[0]->id.")":"addFilterRule(".$subcategory[0]->id.")";?>"> <?php echo $subcategory[0]->name ?>  <?php echo in_array($subcategory[0]->id,$this->selectedCategories) ? '<span class="cross">(remove)</span>':"";  ?></a>
									</div>	
								</li>
							<?php }?>
							</ul>
						<?php } ?>
					</li>
				<?php }
					}
				}?>
			</ul>
		<?php } ?>
	</div>
	
<?php 
jimport('joomla.application.module.helper');
// this is where you want to load your module position
$modules = JModuleHelper::getModules('search-banners');
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$fullWidth = true;
?>
<?php if(isset($modules) && count($modules)>0){?>
	<div class="search-banners">
		<?php 
		$fullWidth = false;
		foreach($modules as $module)
		{
			echo JModuleHelper::renderModule($module);
		}
		
		?>
	</div>
<?php } ?>
	
</div>
<?php }?>
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="adminForm" id="adminForm"  >
	<div id="search-results" class="search-results <?php echo !$enableSearchFilter ?'search-results-full span12':'search-results-normal span9' ?> ">
		<?php if(isset($this->category) && $this->appSettings->show_cat_description){?>
			<div class="category-container">
				<?php if(!empty($this->category->imageLocation)){ ?>
					<div class="categoy-image"><img alt="<?php echo $this->category->name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->category->imageLocation ?>"></div>
				<?php } ?>
				<h3><?php echo $this->category->name?></h3>
				<div>
					<?php echo $this->category->description?>
				</div>
				<div class="clear"></div>
			</div>
		<?php }else if(!empty($this->country) && $this->appSettings->show_cat_description){  ?>
			<div class="category-container">
				<?php if(!empty($this->country->logo)){ ?>
					<div class="categoy-image"><img alt="<?php echo $this->country->country_name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->country->logo ?>"></div>
				<?php } ?>
				<h3><?php echo $this->country->country_name?></h3>
				<div>
					<?php echo $this->country->description?>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
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
			<?php require_once JPATH_COMPONENT_SITE.'/include/search-map.php' ?>
		</div>
		
		<?php 
			require_once JPATH_COMPONENT_SITE.'/include/companies-grid-view.php';
			if($this->appSettings->search_result_view == 1){
				require_once JPATH_COMPONENT_SITE.'/include/companies-list-view.php';
			}else if($this->appSettings->search_result_view == 2){
				require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-intro.php';
			}else if($this->appSettings->search_result_view == 3){
				require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-contact.php';
			}else if($this->appSettings->search_result_view == 4){
				require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-compact.php';
			}else{
				require_once JPATH_COMPONENT_SITE.'/include/companies-list-view.php';
			}
			
				
		?>
		<div class="pagination" <?php echo $this->pagination->total==0 ? 'style="display:none"':''?>>
			
				<?php echo $this->pagination->getListFooter(); ?>
			
			<div class="clear"></div>
		</div>
	</div>
	<input type='hidden' name='task' value='searchCompaniesByName'/>
	<input type='hidden' name='controller' value='search' />
	<input type='hidden' name='categories' id="categories-filter" value='<?php echo !empty($this->categories)?$this->categories:"" ?>' />
	<input type='hidden' name='view' value='search' />
	<input type='hidden' name='categoryId' id='categoryId' value='<?php echo !empty($this->categoryId)?$this->categoryId:"0" ?>' />
	<input type='hidden' name='searchkeyword' value='<?php echo !empty($this->searchkeyword)?$this->searchkeyword:'' ?>' />
	<input type='hidden' name='categorySearch' value='<?php echo !empty($this->categorySearch)?$this->categorySearch: '' ?>' />
	<input type='hidden' name='citySearch' value='<?php echo !empty($this->citySearch)?$this->citySearch: '' ?>' />
	<input type='hidden' name='regionSearch' value='<?php echo !empty($this->regionSearch)?$this->regionSearch: '' ?>' />
	<input type='hidden' name='countrySearch' value='<?php echo !empty($this->countrySearch)?$this->countrySearch: '' ?>' />
	<input type='hidden' name='typeSearch' value='<?php echo !empty($this->typeSearch)?$this->typeSearch: '' ?>' />
	<input type='hidden' name='zipcode' value='<?php echo !empty($this->zipCode)?$this->zipCode: '' ?>' />
	<input type='hidden' name='radius' id="radius" value='<?php echo !empty($this->radius)?$this->radius: '' ?>' />
	<input type='hidden' name='filter_active' id="filter_active" value="<?php echo !empty($this->filterActive)?$this->filterActive: '' ?>" />
</form>
<div class="clear"></div>

</div>
<?php 
if($this->appSettings->search_result_view == 3){
	require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-contact-util.php';
}
?>

<script>
jQuery(document).ready(function(){
	jQuery('.rating-average').raty({
		  half:       true,
		  precision:  false,
		  size:       24,
		  starHalf:   'star-half.png',
		  starOff:    'star-off.png',
		  starOn:     'star-on.png',
		  start:   	  function() { return jQuery(this).attr('title')},
		  path:		  '<?php echo COMPONENT_IMAGE_PATH?>',
		  click: function(score, evt) {
			  <?php $user = JFactory::getUser(); 
			  	if($appSettings->enable_reviews_users && $user->id ==0){
			  	?>
			  	jQuery(this).raty('start',jQuery(this).attr('title'));
			  	jQuery(this).parent().parent().find(".rating-awareness").show();
			  <?php }else{  ?>
			  	  updateCompanyRate(jQuery(this).attr('alt'),score);
			 <?php } ?>
		  }	
		});

	jQuery('.button-toggle').click(function() {  
		 if(!jQuery(this).hasClass("active")) {       
             jQuery(this).addClass('active');
         }
		jQuery('.button-toggle').not(this).removeClass('active'); // remove buttonactive from the others
		
	});

	<?php if ($this->appSettings->map_auto_show == 1) {?>
		showMap(true);
	<?php } ?>

	<?php if ($this->appSettings->search_view_mode == 1) {?>
		showGrid();
	<?php }else{ ?>
		showList();
	<?php }?>
	
});

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
	jQuery("#results-container").show();
	jQuery("#jbd-grid-view").hide();

	jQuery("#grid-view-link").removeClass("active");
	jQuery("#list-view-link").addClass("active");
}

function showGrid(){
	jQuery("#results-container").hide();
	jQuery("#jbd-grid-view").show();
	applyIsotope();
	jQuery(window).resize();
	
	jQuery("#grid-view-link").addClass("active");
	jQuery("#list-view-link").removeClass("active");
}

function chooseCategory(categoryId){
	jQuery("#categoryId").val(categoryId);
	jQuery("#adminForm").submit();
}

function addFilterRule(catId) {
	catId = catId +";";
	if (jQuery("#categories-filter").val().length > 0){
		jQuery("#categories-filter").val(jQuery("#categories-filter").val() + catId);
	}else{
		jQuery("#categories-filter").val(catId);
	}
	jQuery("#filter_active").val("1");
	jQuery("#adminForm").submit();
}

function removeFilterRule(catId) {
	catId = catId +";";
	var str = jQuery("#categories-filter").val();
	jQuery("#categories-filter").val((str.replace(catId, "")));
	jQuery("#filter_active").val("1");
	jQuery("#adminForm").submit();
}

function setRadius(radius){
	jQuery("#adminForm > #radius").val(radius);
	jQuery("#adminForm").submit();
}

</script>
	
	