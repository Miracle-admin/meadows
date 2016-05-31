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
require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';

$baseurl = JURI::base();
$id = rand();
$count = count($list);
?>
	
<?php if($params->get('slideshow')==1){?>	
	<div id="banner-<?php echo $id?>" class="slider<?php echo $moduleclass_sfx ?>">
		
	</div>
	<script>
    Galleria.loadTheme('<?php echo $baseurl ?>modules/mod_jbanners/galleria/themes/classic/galleria.classic.js');	
	var data<?php echo $id?> = [
		<?php foreach ($list as $index=>$item) : ?>
			<?php $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);?>
			<?php if ($item->type == 1) :?>
				<?php // Text based banners ?>
				<?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
			<?php else:?>
				<?php $imageurl = $item->params->get('imageurl');?>
				<?php $width = $item->params->get('width');?>
				<?php $height = $item->params->get('height');?>
				<?php if (BannerHelper::isImage($imageurl)) :?>
					<?php // Image based banner ?>
					<?php $alt = $item->params->get('alt');?>
					<?php $alt = $alt ? $alt : $item->name; ?>
					<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
					<?php if ($item->clickurl) :?>
						<?php // Wrap the banner in a link?>
							{
								image: '<?php echo $baseurl . $imageurl;?>',
								link:  '<?php echo $link; ?>'
							} 
					<?php else :?>
						<?php // Just display the image if no link specified?>
							{
								image: '<?php echo $baseurl . $imageurl;?>',
							} 
					<?php endif;?>
				<?php endif;?>
				
			<?php endif;?>
			<?php echo $index==$count-1?'':',';?>
		<?php endforeach; ?>
	];
    jQuery("#banner-<?php echo $id?>").galleria({
		data_source: data<?php echo $id?>,
		width: <?php echo  $params->get('width')?>,
		height: <?php echo $params->get('height')?>,
		thumbnails: "numbers",
		maxScaleRatio: 1000,
		imageCrop: true,
		autoplay: <?php echo $params->get('slide_duration')?>,
		transition: "flash",
		responsive: true
	});	               
	</script>
<?php }else{ ?>
	<?php 
		$index = rand(0,$count-1);
		$item = $list[$index];
		$imageurl = $item->params->get('imageurl');
		$link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);
		$width = $item->params->get('width');
		$height = $item->params->get('height');
	?>
	<div id="banner-<?php echo $id?>" class="slider">
		<?php $alt = $item->params->get('alt');?>
		<?php $alt = $alt ? $alt : $item->name; ?>
		<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
		<?php if ($item->clickurl) :?>
			<a
				href="<?php echo $link;?>"
				title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
				<img
					src="<?php echo $baseurl . $imageurl;?>"
					alt="<?php echo $alt;?>"
					<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
					<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
				/>
			</a>
		<?php else :?>
			<?php // Just display the image if no link specified?>
			<img
				src="<?php echo $baseurl . $imageurl;?>"
				alt="<?php echo $alt;?>"
				<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
				<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
			/>
		<?php endif;?>
	</div>

<?php } ?>		
		