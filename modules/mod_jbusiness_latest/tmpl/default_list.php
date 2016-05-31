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
<div class="latestbusiness<?php echo $moduleclass_sfx; ?>" >
<ul >
<?php foreach ($items as $company) :  ?>
	<li>
	
	<div class="business-logo">
		<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>">
			<?php if(isset($company->logoLocation) && $company->logoLocation!=''){?>
				<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>">
			<?php }else{ ?>
				<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
			<?php } ?>
		</a>
	</div>
	<div class="company-info">				
		<a class="company-name" href="<?php echo JBusinessUtil::getCompanyLink($company); ?>">
			<?php echo $company->name; ?>
		</a>
		<span class="company-address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
			<span itemprop="street-address"><?php echo $company->street_number.' '.$company->address?></span>, <span itemprop="locality"><?php echo $company->city?></span>, <span itemprop="county-name"><?php echo $company->county?></span>
		</span>
							
	</div>	
	</li>
<?php endforeach; ?>
</ul>
<div class="clear"></div>
</div>