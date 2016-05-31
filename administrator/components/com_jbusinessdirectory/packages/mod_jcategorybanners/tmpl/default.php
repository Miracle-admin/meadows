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
//dump($list);
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
		thumbnails: "none",
		maxScaleRatio: 1000,
		imageCrop: false,
		autoplay: <?php echo $params->get('slide_duration')?>,
		transition: "flash",
		responsive: true
	});	               
	</script>
<?php }else{ 
	$baseurl = JURI::base();
?>
<div class="bannergroup<?php echo $moduleclass_sfx ?>">
<?php if (isset($headerText)) : ?>
	<?php echo $headerText; ?>
<?php endif; ?>

<?php foreach($list as $item):?>
	<div class="banneritem">
		<?php $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);?>
		<?php if($item->type==1) :?>
			<?php // Text based banners ?>
			<?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
		<?php else:?>
			<?php $imageurl = $item->params->get('imageurl');?>
			<?php $width = $item->params->get('width');?>
			<?php $height = $item->params->get('height');?>
			<?php if (BannerHelper::isImage($imageurl)) :?>
				<?php // Image based banner ?>
				<?php $alt = $item->params->get('alt');?>
				<?php $alt = $alt ? $alt : $item->name ;?>
				<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER') ;?>
				<?php if ($item->clickurl) :?>
					<?php // Wrap the banner in a link?>
					<?php $target = $params->get('target', 1);?>
					<?php if ($target == 1) :?>
						<?php // Open in a new window?>
						<a
							href="<?php echo $link; ?>" target="_blank"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php elseif ($target == 2):?>
						<?php // open in a popup window?>
						<a
							href="<?php echo $link;?>" onclick="window.open(this.href, '',
								'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
								return false"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php else :?>
						<?php // open in parent window?>
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
					<?php endif;?>
				<?php else :?>
					<?php // Just display the image if no link specified?>
					<img
						src="<?php echo $baseurl . $imageurl;?>"
						alt="<?php echo $alt;?>"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				<?php endif;?>
			<?php elseif (BannerHelper::isFlash($imageurl)) :?>
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
					<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
					<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
				>
					<param name="movie" value="<?php echo $imageurl;?>" />
					<embed
						src="<?php echo $imageurl;?>"
						loop="false"
						pluginspage="http://www.macromedia.com/go/get/flashplayer"
						type="application/x-shockwave-flash"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				</object>
			<?php endif;?>
		<?php endif;?>
		<div class="clr"></div>
	</div>
<?php endforeach; ?>

<?php if ($footerText) : ?>
	<div class="bannerfooter">
		<?php echo $footerText; ?>
	</div>
<?php endif; ?>
</div>
<?php } ?>