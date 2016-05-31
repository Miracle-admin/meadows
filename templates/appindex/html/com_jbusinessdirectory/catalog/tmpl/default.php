<?php // no direct access
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';
$user = JFactory::getUser();

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
	$title = JText::_("LNG_CATALOG").' | '.$config->sitename;
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

jimport('joomla.application.module.helper');
// this is where you want to load your module position
$modules = JModuleHelper::getModules('categories-catalog');
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$fullWidth = true;
?>

<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1 class="title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php } ?>

<?php if(isset($modules) && count($modules)>0){?>
	<div class="company-categories">
		<?php 
		$fullWidth = false;
		foreach($modules as $module)
		{
			echo JModuleHelper::renderModule($module);
		}
		
		?>
	</div>
<?php } ?>
<div id="search-results" class="search-results <?php echo $fullWidth ?'search-results-full':'search-results-normal' ?>">
		<div id="search-details">
			<div class="catalog-letters"> 
				<div class="result-counter"><?php echo $this->pagination->getResultsCounter()?></div>
				<?php $letters = range('A', 'Z');
				$language = JFactory::getLanguage();
				$language_tag 	= $language->getTag();
				
				if($language_tag=="el-GR"){
					$letters=array('Α','Β','Γ','Δ','Ε','Ζ','Η','Θ','Ι','Κ','Λ','Μ','Ν','Ξ','Ο','Π','Ρ','Σ','Τ','Υ','Φ','Χ','Ψ','Ω');
				}else if($language_tag=="es-ES"){ 
					$letters=array('A','B','C','Ch','D','E','F','G','H','I','J','K','L','LL','M','N','Ñ','O','P','Q','R',' S','T','U','V','W','X','Y','Z');
				}else if($language_tag=="ru-RU"){ 
					$letters=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','ъ','Ы','ь','Э','Ю','Я');
				}
				?>
				
				 <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=catalog&letter=[x]') ?>">
				 	<span class="<?php echo $this->letter=='[x]'? 'letter-selected':'' ?>">#</span>
				 </a>
				
				 <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=catalog&letter=[0-9]') ?>">
				 	<span class="<?php echo strtoupper($this->letter)=='[0-9]'? 'letter-selected':'' ?>">0-9</span>
				 </a>
				
				<?php foreach($letters as $i){ ?>
					 <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=catalog&letter='.$i) ?>">
					 <?php 
					 	$class="no-class";
					 	if(strtoupper($this->letter) == $i){
					 		$class='letter-selected ';
					 	}
					 	
					 	if(isset($this->letters[$i])){
					 		$class.=" used-letter";
					 	}	
					 ?>
					 		<span class="<?php echo $class ?>"><?php echo $i ?> </span>
					 </a> 
				<?php } ?>
				 
				 <a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=catalog') ?>">
				 	<span class="<?php echo empty($this->letter)?'letter-selected':'' ?>"> <?php echo JText::_('LNG_ALL')?></span>
				 </a>
			</div>
		
			<div class="search-toggles">
			
				<p class="view-mode">
					<label><?php echo JText::_('LNG_VIEW')?></label>
					<a id="grid-view-link" class="grid" title="Grid" href="javascript:showGrid()"><?php echo JText::_("LNG_GRID")?></a>
					<a id="list-view-link" class="list active" title="List" href="javascript:showList()"><?php echo JText::_("LNG_LIST")?></a>
				</p>
				
				<?php if($appSettings->show_search_map){?>
					<p class="view-mode">
						<a id="map-link" class="map" title="Grid" href="javascript:showMap(true)"><?php echo JText::_("LNG_SHOW_MAP")?></a>
					</p>
				<?php }?>
			</div>
			<div class="clear"></div>
		</div>
	

	<div class="clear"></div>
	
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
		<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="adminForm" id="adminForm"  >
			
			<input type='hidden' name='task' value='searchCompaniesByName'/>
			<input type='hidden' name='controller' value='catalog' />
			<input type='hidden' name='view' value='catalog' />
			<input type='hidden' name='letter' value='<?php echo $this->letter ?>' />
			
			<?php echo $this->pagination->getListFooter(); ?>
		</form>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>

<script>
window.onload = function()	{
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

	<?php if ($appSettings->map_auto_show == 1) {?>
		showMap(true);
	<?php } ?>

	<?php if ($this->appSettings->search_view_mode == 1) {?>
		showGrid();
	<?php }else{ ?>
		showList();
	<?php }?>
};

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

</script>