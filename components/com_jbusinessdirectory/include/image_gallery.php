<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div class='picture-container'>
 
<div id="gallery" class="content">
	<div id="controls" class="controls" style="display:none"></div>
	<div class="slideshow-container">
		<div id="loading" class="loader"></div>
		<div id="slideshow-gallery" class="slideshow-gallery"></div>
	</div>
	<div id="caption" class="caption-container"></div>
</div>
<div id="thumbs" class="gallery-navigation">
	<ul class="thumbs noscript">
		<?php if (!empty($this->pictures)){
			?>	
			<?php foreach( $this->pictures as $picture ){ ?>
					<li>
						<a class="thumb" name="leaf" href="<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>" 
						 title="<?php echo !empty($picture->picture_info)?$picture->picture_info:""?>"
						>
							<img 
									alt="<?php echo isset($picture->picture_info)?$picture->picture_info:""?>"
									class="img_picture" src='<?php echo JURI::root().PICTURES_PATH.$picture->picture_path ?>' />
						</a>
					</li>
			 <?php }	?>	
					<?php 
						}else{  
							echo JText::_("LNG_NO_IMAGES"); 
						}
					?>
					</ul>
				</div>
				<div style="clear: both;"></div>
			</div>
			
		<?php if (!empty($this->pictures)){?>	
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				
				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				jQuery('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = jQuery('#thumbs').galleriffic({
					delay:                     5000,
					numThumbs:                 14,
					preloadAhead:              5,
					enableTopPager:            false,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow-gallery',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          false,
					renderNavControls:         false,
					playLinkText:              '<?php echo JText::_("LNG_PLAY_SLIDESHOW");?>',
					pauseLinkText:             '<?php echo JText::_("LNG_PAUSE_SLIDESHOW");?>',
					prevLinkText:              '&lsaquo; <?php echo JText::_("LNG_PREVIOUS_PHTO");?>',
					nextLinkText:              '<?php echo JText::_("LNG_NEXT_PHOTO");?> &rsaquo;',
					nextPageLinkText:          '<?php echo JText::_("LNG_NEXT");?> &rsaquo;',
					prevPageLinkText:          '&lsaquo; <?php echo JText::_("LNG_PREV");?>',
					enableHistory:             false,
					autoStart:                 true,
					syncTransitions:           true,
					defaultTransitionDuration: 0,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
			});
		</script>
	<?php } ?>
	
	