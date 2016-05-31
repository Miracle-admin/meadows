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

?>
<div id="companies-search" class="business-directory<?php echo $moduleclass_sfx ?>">
	<!-- strong id="current-search"><?php echo JText::_("LNG_FIND_BUSINESS")?></strong-->
	
	<div id="searchform" class="ui-tabs <?php echo $layoutType?>">

			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="keywordSearch" id="keywordSearch" onsubmit="return checkSearch()">
				<input type='hidden' name='task' value='searchCompaniesByName'>
				<input type='hidden' name='controller' value='search'>
				<input type='hidden' name='view' value='search'>
				<input class="search-item" type='hidden' id="searchkeyword" name='searchkeyword' value='<?php $session = JFactory::getSession(); echo $session->get('searchkeyword');?>'>
				<input class="search-item" type='hidden' id="zipcode" name='zipcode' value='<?php $session = JFactory::getSession(); echo $session->get('zipcode');?>'>
				<input class="search-item" type='hidden' id="citySearch" name='citySearch' value='<?php $session = JFactory::getSession(); echo $session->get('citySearch')?>'>
				<input class="search-item" type='hidden' id="regionSearch" name='regionSearch' value='<?php $session = JFactory::getSession(); echo $session->get('regionSearch')?>'>
				<input class="search-item" type='hidden' id="countrySearch" name='countrySearch' value='<?php $session = JFactory::getSession(); echo $session->get('countrySearch')?>'>
			
				<div class="form-container">
					<div class="form-field">
						<span class="label">
							<select name="search-option" id="search-option">
								<option value="searchkeyword"><?php echo JText::_("LNG_NAME") ?></option>
								<?php if($params->get('showZipcode')){ ?>
									<option value="zipcode"><?php echo JText::_("LNG_ZIPCODE") ?></option>
								<?php }?>
								<?php if($params->get('showCities')){ ?>
									<option value="citySearch"><?php echo JText::_("LNG_CITY") ?></option>
								<?php }?>
								<?php if($params->get('showRegions')){ ?>
									<option value="regionSearch"><?php echo JText::_("LNG_REGION") ?></option>
								<?php }?>
								<?php if($params->get('showCountries')){ ?>
										<option value="countrySearch"><?php echo JText::_("LNG_COUNTRY") ?></option>
								<?php }?>
							</select>
						</span>
					</div>
				
					<div class="form-field">
						<span class="label">
							<input class="search-field" type="text" name="search-value" id="search-value" value="<?php $session = JFactory::getSession(); echo $session->get('searchkeyword');?>" />
						</span>
					</div>

					<?php if($params->get('showCategories')){ ?>
					<div class="form-field">
						<span class="label">
							<select name="categorySearch" id="categories">
								<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
								<?php foreach($categories as $category){?>
									<option value="<?php echo $category->id?>" <?php $session = JFactory::getSession(); echo $session->get('categorySearch')==$category->id && $params->get('preserve')?" selected ":"" ?> ><?php echo $category->name?></option>
									<?php foreach($subCategories as $subCat){?>
										<?php if($subCat->parent_id == $category->id){?>
											<option value="<?php echo $subCat->id?>" <?php $session = JFactory::getSession(); echo $session->get('categorySearch')==$subCat->id && $params->get('preserve')?" selected ":"" ?> >-- <?php echo $subCat->name?></option>
										<?php } ?>
									<?php }?>
								<?php }?>
							</select>
						</span>
					</div>
					<?php }?>
				</div>
				
				<button type="submit" class="ui-dir-button">
					<span class="ui-button-text"><?php echo JText::_("LNG_SEARCH")?></span>
				</button>
				
				<a  style="display:none" id="categories-link" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=categories&view=categories&task=displaycategories') ?>"><?php echo JText::_("LNG_CATEGORY_LIST")?></a>
			</form>
	</div>
	<div class="clear"></div>
</div>


<script>

function checkSearch(){ 
	jQuery(".search-item").each(function(){
		jQuery(this).val("");
	});
	//console.debug(jQuery("#search-option").val());
	//console.debug(jQuery("#search-value").val());
	jQuery("#"+jQuery("#search-option").val()).val(jQuery("#search-value").val());

}

jQuery(document).ready(function(){
    jQuery(".search-item").each(function(){
		var val = jQuery(this).val();
		if(val!=""){
				jQuery("#search-option").val(jQuery(this).attr("name"))
				jQuery("#search-value").val(val);
		}	
	});
});



</script>



