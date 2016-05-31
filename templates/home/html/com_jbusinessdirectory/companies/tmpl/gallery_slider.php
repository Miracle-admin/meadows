<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 20125 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php if(!empty($this->pictures)){?>	
<div class="slidergallery" id="slidergallery" style="width:auto">
	<div id="pageContainer">
		<div id="slideshow">
	   		<div id="slidesContainer">
	      		<div class="slide-dir">
	      			<ul class="gallery">
						<?php 
							$index = 1;
							$totalItems = count($this->pictures); 
						?>
						<?php foreach( $this->pictures as $picture ){ ?>
							<li>
								<a href="<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>" rel="prettyPhoto[pp_gal]" title="<?php echo $picture->picture_info ?>"> 
									<img src="<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>" alt="<?php echo $picture->picture_info ?>" />
								</a>
							</li>
							
						<?php } ?>
					</ul>
			
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<script>
jQuery(document).ready(function() {
    jQuery('.gallery').magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,2] // Will preload 0 - before current, and 1 after the current image
      },
      image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function(item) {
          return item.el.attr('title');
        }
      }
    });
  });
</script>