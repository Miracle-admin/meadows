<?php
/*------------------------------------------------------------------------
# mod_featcats - Featured Categories
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

$doc = JFactory::getDocument();

if ($css!=-1) :
	$doc->addStyleSheet('modules/mod_featcats/assets/'.$css);
endif;

if ($pag) :
	$script = "var sfolder = '" . JURI::base(true) . "';";
	$doc->addScriptDeclaration($script);
	$doc->addScript('modules/mod_featcats/assets/featcats.js');
endif;
?>
<ul class="featcats<?php echo $moduleclass_sfx; ?> row-fluid" id="featcats-<?php echo $mid; ?>">
	<?php foreach ($cats as $id=>$cat) : ?>
    <li class="<?php echo $cat->col_class; ?>" id="featcat-<?php echo $mid; ?>-<?php echo $id; ?>">
        <?php if ($show_more==2) : ?><a href="<?php echo $cat->category_link; ?>" class="fc_more"><?php echo JText::_('MOD_FEATCATS_MORE_ARTICLES'); ?></a><?php endif; ?>
        <?php if ($params->get('cat_image') && $cat->category_image) : ?>
			<?php if ($link_cats) : ?><a href="<?php echo $cat->category_link; ?>"><?php endif; ?>
			<img src="<?php echo JURI::base(false).$cat->category_image; ?>" class="fc_cat_image" />
            <?php if ($link_cats) : ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if ($cat_heading) : ?><?php echo '<h' . $cat_heading . '>'; ?><?php if ($link_cats) : ?><a href="<?php echo $cat->category_link; ?>"><?php endif; ?><?php echo $cat->category_title; ?><?php if ($link_cats) : ?></a><?php endif; ?><?php echo '</h' . $cat_heading . '>'; ?><?php endif; ?>
		<?php if ($cat->articles) : ?>
			<div id="fc_ajax-<?php echo $mid; ?>-<?php echo $id; ?>" class="fc_ajax">
				<?php require JModuleHelper::getLayoutPath('mod_featcats', 'cat'); ?>
			</div>
		<?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
<div style="clear:both"></div>
