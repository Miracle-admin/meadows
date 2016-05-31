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

$span= $params->get('layout-type')=="vertical"?"span12":"span3";

?>
<div id="latestbusiness" class="latestbusiness<?php echo $moduleclass_sfx; ?>" >
	<?php $index = 0;?>
	<div class="row-fluid ">
		<?php foreach ($items as $company) {?>
			<?php $index ++;?>
			<div class="company-box <?php echo $span ?>">
				<div class="full-width-logo" style="<?php echo $backgroundCss?> <?php echo $borderCss?>">
					<div class="offer-overlay">
						<div class="offer-vertical-middle">
							<div> 
								<a href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>" class="btn-view"><?php echo JText::_("LNG_VIEW")?></a>
							</div>
						</div>
						
					</div>
					<a href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>">
						<?php if(isset($company->logoLocation) && $company->logoLocation!=''){?>
							<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>">
						<?php }else{ ?>
							<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
						<?php } ?>
					</a>
				</div>
				<div class="company-info">				
					<a class="company-name" href="<?php echo JBusinessUtil::getCompanyLink($company, true); ?>">
						<?php echo $company->name; ?>
					</a>				
					<p>
						<?php 
							if(!empty($company->slogan)){
								echo $company->slogan;
							}else if(!empty($company->short_description)){
								echo JBusinessUtil::truncate($company->short_description, 200);
							}else if(!empty($company->description)){
								echo JBusinessUtil::truncate($company->description, 200);
							}
						?>
					</p>
					
				</div>	
				<div class="company-options">
					<div class="dir-category">
						<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=search&task=showCompaniesFromCategory&view=search&categoryId='.$company->mainCategoryId) ?>"><?php echo $company->mainCategory ?></a>
					</div>					
					<span class="company-address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
						<span itemprop="locality"><?php echo $company->city?></span>, <span itemprop="county-name"><?php echo $company->county?></span>
					</span>
					
					<a class="ui-dir-button" href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>">
						<span class="ui-button-text"><?php echo JText::_("LNG_VIEW_DETAILS")?></span>
					</a>
				</div>	
			</div>
			<?php if($index%4 == 0 && count($items)>$index){ ?>
				</div>	<div class="row-fluid">		
			<?php }?>
			
			
		
		<?php } ?>
	</div>
</div>

<script>
jQuery(document).ready(function(){
	jQuery(".full-width-logo").each(function(){
	});
});
</script>