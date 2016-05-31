<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
$tmpitem = reset($items);
$columnstylesbegin = isset($tmpitem->columnwidth) ? ' style="width:' . $tmpitem->columnwidth . 'px;float:left;"' : '';
$orientation_class = ( $params->get('orientation', 'horizontal') == 'vertical' ) ? 'maximenuckv' : 'maximenuckh';
$start = (int) $params->get('startLevel');
$direction = $langdirection == 'rtl' ? 'right' : 'left';
?>
<!-- debut maximenu CK, par cedric keiflin -->
<div class="<?php echo $orientation_class . ' ' . $langdirection ?>" id="<?php echo $params->get('menuid', 'maximenuck'); ?>" >
        <div class="maximenuck2"<?php echo $columnstylesbegin; ?>>
            <ul class="maximenuck2 <?php echo $params->get('moduleclass_sfx'); ?>">
<?php
$zindex = 12000;

foreach ($items as $i => &$item) {
	$item->mobile_data = isset($item->mobile_data) ? $item->mobile_data : '';
	$itemlevel = ($start > 1) ? $item->level - $start + 1 : $item->level;
	if ($params->get('calledfromlevel')) {
		$itemlevel = $itemlevel + $params->get('calledfromlevel') - 1;
	}
	$createnewrow = (isset($item->createnewrow) AND $item->createnewrow) ? '<div style="clear:both;"></div>' : '';
	$columnstyles = isset($item->columnwidth) ? ' style="width:' . $item->columnwidth . 'px;float:left;"' : '';
	 if (isset($item->colonne) AND (isset($items[$lastitem]) AND !$items[$lastitem]->deeper)) {
        echo '</ul><div class="clr"></div></div>'.$createnewrow.'<div class="maximenuck2" ' . $columnstyles . '><ul class="maximenuck2">';
     }
    if (isset($item->content) AND $item->content) {
        echo '<li class="maximenuck maximenuflatlistck '. $item->classe . ' level' . $itemlevel .' '.$item->liclass . '" data-level="' . $itemlevel . '" ' . $item->mobile_data . '>' . $item->content;
		$item->ftitle = '';
    }


    if ($item->ftitle != "") {
		$title = $item->anchor_title ? ' title="'.$item->anchor_title.'"' : '';
		$description = $item->desc ? '<span class="descck">' . $item->desc . '</span>' : '';
		// manage HTML encapsulation
		// $item->tagcoltitle = $item->params->get('maximenu_tagcoltitle', 'none');
		$classcoltitle = $item->params->get('maximenu_classcoltitle', '') ? ' class="'.$item->params->get('maximenu_classcoltitle', '').'"' : '';
		// if ($item->tagcoltitle != 'none') {
			// $item->ftitle = '<'.$item->tagcoltitle.$classcoltitle.'>'.$item->ftitle.'</'.$item->tagcoltitle.'>';
		// }
		$opentag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '<'.$item->tagcoltitle.$classcoltitle.'>' : '';
		$closetag = (isset($item->tagcoltitle) AND $item->tagcoltitle != 'none') ? '</'.$item->tagcoltitle.'>' : '';

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
		}
		else {
			$linktype = '<span class="titreck">'.$item->ftitle.$description.'</span>';
		}

		if ($params->get('imageonly', '0') == '1')
			$item->ftitle = '';
		echo '<li class="maximenuck maximenuflatlistck '. $item->classe . ' level' . $itemlevel .' '.$item->liclass . '" style="z-index : ' . $zindex . ';" data-level="' . $itemlevel . '" ' . $item->mobile_data . '>';
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

    /*if ($item->deeper) {

        if (isset($item->submenuswidth) || $item->leftmargin || $item->topmargin || $item->colbgcolor) {
            $item->styles = "style=\"";
            if ($item->leftmargin)
                $item->styles .= "margin-left:" . $item->leftmargin . "px;";
            if ($item->topmargin)
                $item->styles .= "margin-top:" . $item->topmargin . "px;";
            if (isset($item->submenuswidth))
                $item->styles .= "width:" . $item->submenuswidth . "px;";
            if (isset($item->colbgcolor) && $item->colbgcolor)
                $item->styles .= "background:" . $item->colbgcolor . ";";

            $item->styles .= "\"";
        } else {
            $item->styles = "";
        }

        echo "\n\t<div class=\"floatck\" " . $item->styles . ">" . $close . "<div class=\"maxidrop-top\"><div class=\"maxidrop-top2\"></div></div><div class=\"maxidrop-main\"><div class=\"maxidrop-main2\"><div class=\"maximenuCK2 first \" " . $columnstyles . ">\n\t<ul class=\"maximenuCK2\">";
        if (isset($item->coltitle))
            echo $item->coltitle;
    }*/
    // The next item is shallower.
    /*elseif ($item->shallower) {
        echo "\n\t</li>";
        echo str_repeat("\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>\n\t</li>", $item->level_diff);
    }*/
    // the item is the last.
    /*elseif ($item->is_end) {
        echo str_repeat("</li>\n\t</ul>\n\t<div class=\"clr\"></div></div><div class=\"clr\"></div></div></div><div class=\"maxidrop-bottom\"><div class=\"maxidrop-bottom2\"></div></div></div>", $item->level_diff);
        echo "</li>";
    }*/
    // The next item is on the same level.
    // else {
        // if (!isset($item->colonne))
            echo "\n\t\t</li>\n";
    // }

    $zindex--;
    $lastitem = $i;
}
?>
            </ul>
			<div style="clear:both;"></div>
        </div>
	</div>
    <!-- fin maximenuCK -->
