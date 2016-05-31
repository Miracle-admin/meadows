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

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/slick.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/slick.js');

$idnt = rand(500, 1500);
?>
<div id="latestbusiness"  class="latestbusiness<?php echo $moduleclass_sfx; ?>" >
	<?php $index = 0;?>
	<div class="bussiness-slider responsive slider">
		<?php if(!empty($items))?>
		<?php foreach ($items as $company) {?>
			<?php $index ++;?>
			<div>
				<div class="slider-item">
					<div class="slider-content" style="<?php echo $backgroundCss?> <?php echo $borderCss?>">
						<a href="<?php echo JBusinessUtil::getCompanyLink($company, true)?>">
							<?php if(isset($company->logoLocation) && $company->logoLocation!=''){?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>">
							<?php }else{ ?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</a>
						
						<div class="info" onclick="document.location.href='<?php echo JBusinessUtil::getCompanyLink($company, true)?>'">
							<div class="hover_info">
								<h3><?php echo $company->name?></h3>
								
								<div class="" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
									<i class="dir-icon-map-marker"></i> <?php echo JBusinessUtil::getAddressText($company)?>
								</div>
								
								<?php if(!empty($company->phone)){ ?>
									<div itemprop="telephone">
										<i class="dir-icon-phone"></i> <?php echo $company->phone ?>
									</div>
								<?php } ?>
								
								<?php if(!empty($company->website)){ ?>
									<div>
										<a itemprop="website" title="<?php echo $company->name?>" target="_blank" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$company->id) ?>"><i class="dir-icon-globe"></i> <?php echo $company->website ?></a>
									</div>
								<?php } ?>
							</div> 
						</div>
					</div>
					<div class="slider-item-name">
						<h3><?php echo $company->name?></h3>
						<span title="<?php echo $company->averageRating ?>" class="rating-review-<?php echo $idnt ?>"></span>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<script>
jQuery(document).ready(function(){

	
	jQuery('.bussiness-slider').slick({
		  dots: false,
		  infinite: false,
		  prevArrow: '<a class="controller-prev" href="javascript:;"><span><i class="dir-icon-angle-left"></i></span></a>',
          nextArrow: '<a class="controller-next" href="javascript:;"><span><i class="dir-icon-angle-right"></i></span></a>',
          customPaging: function(slider, i) {
              return '<a class="controller-dot" href="javascript:;"><span><i class="dir-icon-circle"></i></span></a>';
          },
          <?php if ($params->get('autoplay')){ ?>	
          autoplay: true,
          autoplaySpeed:  <?php echo $params->get('autoplaySpeed')?>,
          <?php }?>
		  speed: 300,
		  slidesToShow: <?php echo $params->get('nrVisibleItems')?>,
		  slidesToScroll: <?php echo $params->get('nrItemsToScrool')?>,
				  infinite: true,
		  responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 3,
		        infinite: true,
		        dots: false
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		  ]
		});

	jQuery(".slider-content").each(function(){
		
	});


	jQuery('.rating-review-<?php echo $idnt ?>').raty({
		  half:       true,
		  size:       24,
		  starHalf:   'star-half.png',
		  starOff:    'star-off.png',
		  starOn:     'star-on.png',
		  start:   	  function() { return jQuery(this).attr('title')},
		  path:		  '<?php echo JURI::root().'components/com_jbusinessdirectory/assets/images/' ?>',
		  readOnly:   true
		});
});
</script>