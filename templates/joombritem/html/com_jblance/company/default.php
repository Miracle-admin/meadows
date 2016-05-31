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

<div class="project-wrapper-outer container">
    <div class="row">

        <form  method="get" id="filters-form" action="<?php echo JRoute::_('index.php?option=com_jblance&view=company') ?>">
            <div class="col-md-3">
                <div class="selct-cate">
                    <div class="srch-row">
                        <?php
                        $checkvalarr = array($this->filters);
                        $searched = JRequest::getVar("id_categ");
                        $parents = $this->parents;
                        foreach ($parents as $parent) {
                            echo '<fieldset>';
                            if ($searched && !in_array($child->id, $searched)) {
                                echo '<input type="checkbox" class="parent chkbx_' . $parent->id . '" id="' . $parent->id . '" name="" >' . $parent->category . '<br>';
                            } else {
                                echo '<input type="checkbox" class="parent chkbx_' . $parent->id . '" id="' . $parent->id . '" name="">' . $parent->category . '<br>';
                            }
                            $children = $model->getChild($parent->id);

                            foreach ($children as $child) {
                                if ($searched && !in_array($child->id, $searched)) {
                                    echo ' <input class="checkb parent_' . $parent->id . '" name="id_categ[' . $child->id . ']"  id="checkb parent_' . $parent->id . '" onChange="" type="checkbox" value="' . $child->id . '"/>-- ' . $child->category . '<br>';
                                } else {
                                    echo ' <input class="checkb parent_' . $parent->id . '" name="id_categ[' . $child->id . ']"  id="checkb parent_' . $parent->id . '" onChange="" checked="true" type="checkbox" value="' . $child->id . '"/>-- ' . $child->category . '<br>';
                                }
                                $state = $app->getUserState($child->category);
                                $app->setUserState($child->category, '');
                            }
                            echo '</fieldset>';
                        }
                        $mcompany = $this->companies;

                        //echo '<pre>'; print_r($this->companies);die;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9 "> 
                <div id="company_container" class="company_container">
                    <?php
                    foreach ($mcompany as $mycompany) {

                        //  $picture = !empty($mycompany->picture) ? JURI::root() . 'images/jblance/' . $mycompany->picture : JURI::root() . 'components/com_jblance/images/nophoto_big.png';
                        ?>

                        <div class="col-md-12 ft-comp selct-cate">

                            <div class="compimg col-md-2" >
                            <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $mycompany->user_id) ?>">
                                <?php
                                $attrib = 'width=100 height=100 class="img-polaroid"';
                                $avatar = JblanceHelper::getThumbnail($mycompany->user_id, $attrib);
                                echo!empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar, '', '', ' ') : '&nbsp;';
                                ?>
                                </a>
                            </div>
                            <div class="col-md-10">
                                <div class="company_name">
                                    <div class="certified-main">
                                    <p><i class="icon-bookmark"></i> <?php
                                        //echo '<pre>'; print_r($mycompany); die;
                                        $plan = JblanceHelper::getBtPlan($mycompany->user_id);
                                        //   print_r($plan['name']); die;
                                        if (!empty($plan)) {
                                            echo $plan['name'].'</p>';
                                            if($plan['name']!=''){
                                            
                                                echo '<div class="certified "><div class="head ">';
                                                echo JText::_('COM_JBLANCE_CERTIFICATION');
                                                echo '</div><div class="head-data ">';
echo ($mycompany->phone_skype=='1')?'<span  class="fa fa-phone"data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_PHONE_SKYPE_VERIFIED').'"></span>':'<span  class="fa fa-phone not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_PHONE_SKYPE_VERIFIED_NO').'"></span>';
echo ($mycompany->trusted_user=='1')?'<span class="fa fa-usd" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_TRUSTED_USER').'"></span>':'<span  class="fa fa-usd not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_TRUSTED_USER_NOT').'"></span>';
echo ($mycompany->real_business=='1')?'<span  class="fa fa-check" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_REAL_BUSINESS').'"></span>':'<span  class="fa fa-check not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_REAL_BUSINESS_NOT').'"></span>';
echo ($mycompany->validate_client=='1')?'<span class="fa fa-user" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_VALIDATE_CLIENT').'"></span>':'<span  class="fa fa-user not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_VALIDATE_CLIENT_NOT').'"></span>';
echo ($mycompany->english_skills=='1')?'<span class="fa fa-comment" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_ENGLISH_COM_SKILLS').'"></span>':'<span  class="fa fa-comment not"data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_ENGLISH_COM_SKILLS_NO').'"></span>';
                                                echo '</div></div>';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }

                                        //echo '<span>'.JblanceHelper::getAvarageRate($row->user_id).'</span';
                                        ?>


                                    </div>
                                    <div>
                                        <?php $rate = JblanceHelper::getAvarageRate($mycompany->user_id, true); ?>
                                        <?php if ($mycompany->rate > 0) { ?>
                                            <span class="font14" style="margin-left: 10px;"><?php echo JblanceHelper::formatCurrency($mycompany->rate, true, true, 0) . '/' . JText::_('COM_JBLANCE_HR'); ?></span>
                                        <?php } ?>
                                    </div>

                               

                                </div>
                                <div class="company_name">
                                    <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $mycompany->user_id) ?>">  
                                        <h3><?php echo $mycompany->name;
                                        ?></h3>
                                    </a>
                                </div>

                                <div class="compdesc">
                                    <span><?php
                                        $desc = JblanceHelper::getDescription($mycompany->user_id);
                                        echo substr($desc, 0, 150);
                                        ?>
                                    </span>
                                </div>
                                <br>
                                <div class="col-md-4">

                                    <b>Skills:</b>
                                    <?php
                                    $skills = JblanceHelper::getCategoryNames($mycompany->id_category);
                                    echo ($skills) ? $skills : "N/A";
                                    ?>


                                </div>
                                <div class="col-md-4">

                                    <b>Location:</b>
                                    <?php
                                    $location = JblanceHelper::getLocationNames($mycompany->id_location);
                                    echo ($location) ? $location : "N/A";
                                    ?>


                                </div>
                                <div class="col-md-4">

                                    <b>Mobile:</b>
                                    <?php
                                    
                                    echo ($mycompany->mobile) ? $mycompany->mobile : "N/A";
                                    ?>


                                </div>

                            </div>   
                        </div>

                        <?php
                    }
//  echo $this->pagination->getListFooter();
                    ?>
                    <div class="pagination pagination-centered clearfix">
                        <div class="display-limit pull-right">
                            <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
                            <?php echo $this->pageNav->getLimitBox(); ?>
                        </div>
                        <?php echo $this->pageNav->getPagesLinks(); ?>
                    </div>
                </div>
                <input type="hidden" name="option" value="com_jblance" />
                <input type="hidden" name="option" value="com_jblance" />
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt("Itemid") ?>" />
                <input type="hidden" name="view" value="company" />
            </div>
        </form>
    </div>
</div>	



<script type="text/javascript">
    jQuery(function () {
        jQuery('.checkb').change(function () {
            var temp = jQuery("#non-checked").val();
            jQuery("#non-checked").val(temp + ' ' + jQuery(this).val());
            var uncheck = jQuery("input:checkbox:not(:checked)").val();
           //jQuery("#filters-form").submit();
submit();
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
              //  jQuery("#filters-form").submit();
            }
              submit();
            
        });
 

        jQuery('fieldset').each(function(){
          var childCheckboxes = jQuery(this).find('input.checkb'),
              no_checked = childCheckboxes.filter(':checked').length;

          if(childCheckboxes.length == no_checked){
            jQuery(this).find('.parent').prop('checked',true);
          }
        });
        /*To check uncheck onclick***/
       
    })

jQuery(document).ready(function(){
    jQuery('[data-toggle="tooltip"]').tooltip(); 
});


function submit(){



var form= jQuery('#filters-form').serialize();
console.log(form);
    var saveData = jQuery.ajax({
                  type: 'POST',
                  url: "index.php?option=com_jblance&task=company.getAjaxCompany",
                  data:form ,
                  success: function(resultData) { 
                    //alert(resultData);
                    jQuery('#company_container').html(resultData);
                   // alert("Save Complete");
    var ur= jQuery(location).attr('href');
    jQuery('.pagenav').attr('href',ur);  
                     }
                });
        saveData.error(function() { 
            //alert("Something went wrong"); 
        });
    }
</script>