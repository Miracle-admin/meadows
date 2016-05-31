<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/tmpl/dashboard.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Displays the user Dashboard (jblance)
 */
$model = $this->model;
$app = & JFactory::getApplication();
?>

<script type="text/javascript">
    jQuery(function () {
        jQuery('.checkb').change(function () {
            var temp = jQuery("#non-checked").val();
            jQuery("#non-checked").val(temp + ' ' + jQuery(this).val());
            var uncheck = jQuery("input:checkbox:not(:checked)").val();
            jQuery("#filters-form").submit();
        })
        /*To check uncheck onclick***/
        jQuery('.parent').click(function (event) {
            var id = jQuery(this).attr('id');

            if (jQuery(this).prop("checked")) {
                jQuery('.parent_' + id).prop("checked", true);
            } else {


                jQuery('.parent_' + id).each(function () {
                    var temp = jQuery("#non-checked").val();
                    jQuery("#non-checked").val(temp + ' ' + jQuery(this).val());
                    jQuery(this).prop("checked", false);
                })
                jQuery("#filters-form").submit();
            }
        });
        /*To check uncheck onclick***/

    })
</script>
<div class="project-wrapper-outer container">
    <div class="row">

        <form  method="post" id="filters-form" action="<?php echo JRoute::_('index.php?option=com_jblance&view=company') ?>">
            <div class="col-md-3">
                <div class="selct-cate">
                    <div class="srch-row">
                        <?php
                        $checkvalarr = array($this->filters);

                        $parents = $this->parents;
                        foreach ($parents as $parent) {
                            echo '<input type="checkbox" class="parent chkbx_' . $parent->id . '" id="' . $parent->id . '" name="" checked="true">' . $parent->category . '<br>';
                            $children = $model->getChild($parent->id);

                            foreach ($children as $child) {
                                if (in_array($child->id, $checkvalarr)) {
                                    echo '<input class="checkb parent_' . $parent->id . '" name="category_id[' . $child->id . ']"  id="checkb parent_' . $parent->id . '" onChange="" type="checkbox" value="' . $child->id . '"/>-- ' . $child->category . '<br>';
                                } else {
                                    echo '<input class="checkb parent_' . $parent->id . '" name="category_id[' . $child->id . ']"  id="checkb parent_' . $parent->id . '" onChange="" checked="true" type="checkbox" value="' . $child->id . '"/>-- ' . $child->category . '<br>';
                                }
                                $state = $app->getUserState($child->category);
                                $app->setUserState($child->category, '');
                            }
                        }
                        $mcompany = $this->companies;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9 "> 
                <div class="company_container selct-cate">
                    <?php
                    foreach ($mcompany as $mycompany) {

                        //  $picture = !empty($mycompany->picture) ? JURI::root() . 'images/jblance/' . $mycompany->picture : JURI::root() . 'components/com_jblance/images/nophoto_big.png';
                        ?>

                        <div class="col-md-12 ft-comp ">

                            <div class="compimg col-md-2" >
                                <?php
                                $attrib = 'width=100 height=100 class="img-polaroid"';
                                $avatar = JblanceHelper::getThumbnail($mycompany->user_id, $attrib);
                                echo!empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar, '', '', ' ') : '&nbsp;';
                                ?>
                            </div>

                            <div class="company_name">
                                <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $mycompany->user_id) ?>">  
                                    <h3><?php echo $mycompany->name;
                                ?></h3>
                                </a>
                            </div>

                            <div class="compname">
                                <b>Skills:</b>
                                <?php
                                $skills = JblanceHelper::getCategoryNames($mycompany->id_category);
                                echo ($skills) ? $skills : "N/A";
                                ?>
                            </div>
                            <div class="compdesc">
                                <?php echo JblanceHelper::getLocationNames($mycompany->id_location) ?>
                            </div>
                            <div>
                                <?php $rate = JblanceHelper::getAvarageRate($mycompany->user_id, true); ?>
                                <?php if ($row->rate > 0) { ?>
                                    <span class="font14" style="margin-left: 10px;"><?php echo JblanceHelper::formatCurrency($mycompany->rate, true, true, 0) . '/' . JText::_('COM_JBLANCE_HR'); ?></span>
                                <?php } ?>
                            </div>

                            <div class="compdesc">
                                <span><?php
                                    $desc = JblanceHelper::getDescription($mycompany->user_id);
                                    echo substr($desc, 0, 150);
                                    ?>
                                </span>
                            </div>

                            <div class="compdesc">
                                <span>Rate: <?php echo $mycompany->rate; ?>$/hr</span>
                            </div>

                            <!--                        <div class="compname">
                                                        <a target="_blank" class="visit_link"  href="<?php //echo $link;       ?>">Website</a>
                                                    </div>  -->


                        </div>

                        <?php
                    }
                    echo $this->pagination->getListFooter();
                    ?>
                    <!--                    <div class="pagination pagination-centered clearfix">
                                            <div class="display-limit pull-right">
                    <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
                    <?php //echo $this->pageNav->getLimitBox();  ?>
                                            </div>
<?php //echo $this->pageNav->getPagesLinks();  ?>
                                        </div>-->
                </div>
                <input type="text" id="non-checked"  name="filterunchecked"/>
            </div>
        </form>
    </div>
</div>	


