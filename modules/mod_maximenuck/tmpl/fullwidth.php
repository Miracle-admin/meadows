<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
//$tmpitem = reset($items);
//$columnstylesbegin = isset($tmpitem->columnwidth) ? ' style="width:' . $tmpitem->columnwidth . 'px;float:left;"' : '';
if ($params->get('behavior', 'mouseover') == 'clickclose') {
	$close = '<span class="maxiclose">' . JText::_('MAXICLOSE') . '</span>';
} else {
	$close = '';
}
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$maximenufixedclass = ($params->get('menuposition', '0') == 'bottomfixed') ? ' maximenufixed' : '';
$start = (int) $params->get('startLevel');
$direction = $langdirection == 'rtl' ? 'right' : 'left';
?>
<!-- debut Maximenu CK, par cedric keiflin -->
	<div class="<?php echo $orientation_class . ' ' . $langdirection ?><?php echo $maximenufixedclass ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" style="z-index:<?php echo $params->get('zindexlevel', '10'); ?>;">
        <div class="maxiroundedleft"></div>
        <div class="maxiroundedcenter">
            <ul class="<?php echo $params->get('moduleclass_sfx'); ?> maximenuck" style="position:relative;" >
				<?php
				if ($logoimage) {
					$logoheight = $logoheight ? ' height="' . $logoheight . '"' : '';
					$logowidth = $logowidth ? ' width="' . $logowidth . '"' : '';
					$logofloat = ($params->get('orientation', 'horizontal') == 'vertical') ? '' : 'float: ' . $params->get('logoposition', 'left') . ';';
					$styles = 'style="' . $logofloat . 'margin: ' . $params->get('logomargintop', '0') . 'px ' . $params->get('logomarginright', '0') . 'px ' . $params->get('logomarginbottom', '0') . 'px ' . $params->get('logomarginleft', '0') . 'px' . '"';
					$logolinkstart = $logolink ? '<a href="' . JRoute::_($logolink) . '" style="margin-bottom: 0 !important;margin-left: 0 !important;margin-right: 0 !important;margin-top: 0 !important;padding-bottom: 0 !important;padding-left: 0 !important;padding-right: 0 !important;padding-top: 0 !important;background: none !important;">' : '';
					$logolinkend = $logolink ? '</a>' : '';
					?>
					<li class="maximenucklogo" style="margin-bottom: 0 !important;margin-left: 0 !important;margin-right: 0 !important;margin-top: 0 !important;">
						<?php echo $logolinkstart ?><img src="<?php echo $logoimage ?>" alt="<?php echo $params->get('logoalt', '') ?>" <?php echo $logowidth . $logoheight . $styles ?> /><?php echo $logolinkend ?>
					</li>
				<?php } ?>
				<?php if ($params->get('usemobileburgericon') === '1') {
					echo '<label for="' . $params->get('menuid', 'maximenuck') . '-maximenumobiletogglerck" class="maximenumobiletogglericonck" style="display:none;">&#x2261;</label>'
							. '#<input id="' . $params->get('menuid', 'maximenuck') . '-maximenumobiletogglerck" class="maximenumobiletogglerck" type="checkbox" style="display:none;"/>';
				} ?>
				<?php
				$zindex = 12000;

				foreach ($items as $i => &$item) {
					$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
					// test if need to be dropdown
					//    $stopdropdown = ($item->level > 120) ? '-nodrop' : '';
					$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
					$stopdropdown = $params->get('stopdropdownlevel', '0');
					$stopdropdownclass = ( $item->level > 1 && $item->level > $start) ? ' nodropdown' : '';
					if ($item->level > $start) {
						$item->classe = str_replace('parent', '', $item->classe);
					}

					$createnewrow = (isset($item->createnewrow) AND $item->createnewrow) ? '<div style="clear:both;"></div>' : '';
					$columnstyles = isset($item->columnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->columnwidth) . ';float:left;"' : '';
					$nextcolumnstyles = isset($item->nextcolumnwidth) ? ' style="width:' . modMaximenuckHelper::testUnit($item->nextcolumnwidth) . ';float:left;"' : '';

					if (isset($item->colonne) AND (isset($previous) AND !$previous->deeper)) {
						echo '</ul><div class="clr"></div></div>' . $createnewrow . '<div class="maximenuck2" ' . $columnstyles . '><ul class="maximenuck2">';
					}
					if (isset($item->content) AND $item->content) {
						echo '<li data-level="' . $itemlevel . '" class="maximenuck maximenuckmodule' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . '" ' . $item->mobile_data . '>' . $item->content;
						$item->ftitle = '';
					}


					if ($item->ftitle != "") {
						$title = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
						$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
						// manage HTML encapsulation
						$classcoltitle = $item->params->get('maximenu_classcoltitle', '') ? ' class="' . $item->params->get('maximenu_classcoltitle', '') . '"' : '';
						$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<' . $item->tagcoltitle . $classcoltitle . '>' : '';
						$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</' . $item->tagcoltitle . '>' : '';

						$linkrollover = '';
						// manage image
						if ($item->menu_image) {
							// manage image rollover
							$menu_image_split = explode('.', $item->menu_image);

							if (isset($menu_image_split[1])) {
								// manage active image
								if (isset($item->active) AND $item->active) {
									$menu_image_active = $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . '.' . $menu_image_split[1];
									if (JFile::exists(JPATH_ROOT . '/' . $menu_image_active)) {
										$item->menu_image = $menu_image_active;
									}
								}
								// manage hover image
								$menu_image_hover = $menu_image_split[0] . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1];
								if (isset($item->active) AND $item->active AND JFile::exists(JPATH_ROOT . '/' . $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1])) {
									$linkrollover = ' onmouseover="javascript:this.querySelector(\'img\').src=\'' . JURI::base(true) . '/' . $menu_image_split[0] . $params->get('imageactiveprefix', '_active') . $params->get('imagerollprefix', '_hover') . '.' . $menu_image_split[1] . '\'" onmouseout="javascript:this.querySelector(\'img\').src=\'' . JURI::base(true) . '/' . $item->menu_image . '\'"';
								} else if (JFile::exists(JPATH_ROOT . '/' . $menu_image_hover)) {
									$linkrollover = ' onmouseover="javascript:this.querySelector(\'img\').src=\'' . JURI::base(true) . '/' . $menu_image_hover . '\'" onmouseout="javascript:this.querySelector(\'img\').src=\'' . JURI::base(true) . '/' . $item->menu_image . '\'"';
								}
							}

							$imagesalign = ($item->params->get('maximenu_images_align', 'moduledefault') != 'moduledefault') ? $item->params->get('maximenu_images_align', 'top') : $params->get('menu_images_align', 'top');
							$image_dimensions = ( $item->params->get('maximenuparams_imgwidth', '') != '' && ($item->params->get('maximenuparams_imgheight', '') != '') ) ? ' width="' . $item->params->get('maximenuparams_imgwidth', '') . '" height="' . $item->params->get('maximenuparams_imgheight', '') . '"' : '';
							if ($item->params->get('menu_text', 1) AND !$params->get('imageonly', '0')) {
								switch ($imagesalign) :
									default:
									case 'default':
										$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="left"' . $image_dimensions . '/><span class="titreck">' . $item->ftitle . $description . '</span> ';
										break;
									case 'bottom':
										$linktype = '<span class="titreck">' . $item->ftitle . $description . '</span><img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" style="display: block; margin: 0 auto;"' . $image_dimensions . ' /> ';
										break;
									case 'top':
										$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" style="display: block; margin: 0 auto;"' . $image_dimensions . ' /><span class="titreck">' . $item->ftitle . $description . '</span> ';
										break;
									case 'rightbottom':
										$linktype = '<span class="titreck">' . $item->ftitle . $description . '</span><img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="top"' . $image_dimensions . '/> ';
										break;
									case 'rightmiddle':
										$linktype = '<span class="titreck">' . $item->ftitle . $description . '</span><img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="middle"' . $image_dimensions . '/> ';
										break;
									case 'righttop':
										$linktype = '<span class="titreck">' . $item->ftitle . $description . '</span><img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="bottom"' . $image_dimensions . '/> ';
										break;
									case 'leftbottom':
										$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="top"' . $image_dimensions . '/><span class="titreck">' . $item->ftitle . $description . '</span> ';
										break;
									case 'leftmiddle':
										$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="middle"' . $image_dimensions . '/><span class="titreck">' . $item->ftitle . $description . '</span> ';
										break;
									case 'lefttop':
										$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '" align="bottom"' . $image_dimensions . '/><span class="titreck">' . $item->ftitle . $description . '</span> ';
										break;
								endswitch;
							} else {
								$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->ftitle . '"' . $image_dimensions . '/>';
							}
						} else {
							$linktype = '<span class="titreck">' . $item->ftitle . $description . '</span>';
						}

						echo '<li data-level="' . $itemlevel . '" class="maximenuck' . $stopdropdownclass . $item->classe . ' level' . $itemlevel . ' ' . $item->liclass . ' fullwidth" style="z-index : ' . $zindex . ';position:static;" ' . $item->mobile_data . '>';
						switch ($item->type) :
							default:
								echo $opentag . '<a' . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
								break;
							case 'separator':
								echo $opentag . '<span' . $linkrollover . ' class="separator ' . $item->anchor_css . '">' . $linktype . '</span>' . $closetag;
								break;
							case 'heading':
								echo $opentag . '<span' . $linkrollover . ' class="nav-header ' . $item->anchor_css . '">' . $linktype . '</span>' . $closetag;
								break;
							case 'url':
							case 'component':
								switch ($item->browserNav) :
									default:
									case 0:
										echo $opentag . '<a' . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '"' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
									case 1:
										// _blank
										echo $opentag . '<a' . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" target="_blank" ' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
									case 2:
										// window.open
										echo $opentag . '<a' . $linkrollover . ' class="maximenuck ' . $item->anchor_css . '" href="' . $item->flink . '" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes\');return false;" ' . $title . $item->rel . '>' . $linktype . '</a>' . $closetag;
										break;
								endswitch;
								break;
						endswitch;
					}

					if ($item->deeper) {
						// set the styles for the submenus container
						$item->styles = "style=\"";
						$item->innerstyles = "style=\"";
						if ( $item->level == $start && $params->get('orientation', 'horizontal') == 'horizontal' ) {
							$item->styles .= "position:absolute;left:0;right:0;";
							$item->innerstyles .= "width:auto;";
						} else if ( $item->level == $start && $params->get('orientation', 'horizontal') == 'vertical' ) {
							$item->styles .= "position:absolute;left:100%;top:0;bottom:0;";
							if (isset($item->submenuswidth))
								$item->innerstyles .= "width:" . modMaximenuckHelper::testUnit($item->submenuswidth) . ";";
						} else {
							$item->styles .= "position:static;display:block;height:auto;";
							if (isset($item->submenuswidth)) {
								$item->innerstyles .= "width:" . modMaximenuckHelper::testUnit($item->submenuswidth) . ";";
							} else {
								$item->innerstyles .= "width:auto;";
							}
						}
						if (isset($item->submenuswidth) || $item->leftmargin || $item->topmargin || $item->colbgcolor || isset($item->submenucontainerheight)) {
							if ($item->leftmargin)
								$item->styles .= "margin-".$direction.":" . modMaximenuckHelper::testUnit($item->leftmargin) . ";";
							if ($item->topmargin)
								$item->styles .= "margin-top:" . modMaximenuckHelper::testUnit($item->topmargin) . ";";
							// if (isset($item->submenuswidth))
								// $item->innerstyles .= "width:" . modMaximenuckHelper::testUnit($item->submenuswidth) . ";";
							if (isset($item->colbgcolor) && $item->colbgcolor)
								$item->styles .= "background:" . $item->colbgcolor . ";";
							if (isset($item->submenucontainerheight) && $item->submenucontainerheight)
								$item->innerstyles .= "height:" . modMaximenuckHelper::testUnit($item->submenucontainerheight) . ";";
						}
						$item->styles .= "\"";
						$item->innerstyles .= "\"";
						
						echo "\n\t<div class=\"floatck\" " . $item->styles . ">" . $close . "<div class=\"maxidrop-top\"><div class=\"maxidrop-top2\"></div></div><div class=\"maxidrop-main\" " . $item->innerstyles . "><div class=\"maxidrop-main2\"><div class=\"maximenuck2 first \" " . $nextcolumnstyles . ">\n\t<ul class=\"maximenuck2\">";
						// if (isset($item->coltitle))
						// echo $item->coltitle;
					}
					// The next item is shallower.
					elseif ($item->shallower) {
						echo "\n\t</li>";
						echo str_repeat("\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>\n\t</li>", $item->level_diff);
					}
					// the item is the last.
					elseif ($item->is_end) {
						echo str_repeat("</li>\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>", $item->level_diff);
						echo "</li>";
					}
					// The next item is on the same level.
					else {
						//if (!isset($item->colonne))
						echo "\n\t\t</li>";
					}

					$zindex--;
					$previous = $item;
				}
				?>
            </ul>
        </div>
        <div class="maxiroundedright"></div>
        <div style="clear:both;"></div>
    </div>
    <!-- fin maximenuCK -->
