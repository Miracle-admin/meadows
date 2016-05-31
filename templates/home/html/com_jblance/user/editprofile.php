<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/editprofile.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit profile (jblance)
 */
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework', true);
// JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

$doc = JFactory::getDocument();
$doc->addScript("components/com_jblance/js/utility.js");

$user = JFactory::getUser();
$model = $this->getModel();

$select = JblanceHelper::get('helper.select');  // create an instance of the class SelectHelper

$jbuser = JblanceHelper::get('helper.user');  // create an instance of the class UserHelper
$userInfo = $jbuser->getUserGroupInfo($user->id, null);

$config = JblanceHelper::getConfig();
$currencysym = $config->currencySymbol;
$currencycod = $config->currencyCode;
$maxSkills = $config->maxSkills;
$benefit = JblanceHelper::get('helper.planbenefits');
JText::script('COM_JBLANCE_CLOSE');
$silverAccess = $benefit->isBtSubscribed("silver");
$goldAccess = $benefit->isBtSubscribed("gold");
$platinumAccess = $benefit->isBtSubscribed("platinum");


JHtml::script(Juri::base() . 'media/system/js/clone-form.js');

$app = JFactory::getApplication();
//echo"<pre>";
//print_r($_SESSION);
//print_r($_SESSION);$this->userInfo
for ($i = 1; $i <= 31; $i++) {
    $field = 'field_id_' . $i;
    $val = $app->getUserState($field, JRequest::getVar($field, ''));
    //echo "<b style='color:red;'>".$field."</b><br>";
    $allfields[$field] = array();
    foreach ($val as $vk) {

        //echo $vk.'<br>';
        $allfields[$field][] = $vk;
    }
    
}

$this->extra_fields = (count($allfields) < 1)?$allfields:$this->extra_fields;
?>
<script type="text/javascript">
<!--
    function validateForm(f) {
        var checkLength = $$('input[name="id_category[]"]').length;		//check if checkbox exists on the page. For Buyer, checkbox is not present
        if (checkLength != 0) {
            //check if Skills is checked or not
            if (!$$('input[name="id_category[]"]:checked')[0]) {
                alert('<?php echo JText::_('COM_JBLANCE_PLEASE_SELECT_SKILLS_FROM_THE_LIST', true); ?>');
                return false;
            }

            //check for maximum Skills
<?php if ($maxSkills > 0) { ?>
                maxSkills = parseInt('<?php echo $maxSkills; ?>');
                sel = $$('input[name="id_category[]"]:checked').length;
                if (sel > maxSkills) {
                    alert('<?php echo JText::sprintf('COM_JBLANCE_MAXIMUM_SKILL_LIMIT_EXCEEDED', $maxSkills, array('jsSafe' => true)); ?>');
                    return false;
                }
<?php } ?>
        }

        if (document.formvalidator.isValid(f)) {

        }
        else {
            var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
            if ($('rate') && $('rate').hasClass('invalid')) {
                msg = msg + '\n\n* ' + '<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC_ONLY', true); ?>';
            }
            alert(msg);
            return false;
        }
        return true;
    }

<?php if ($maxSkills > 0) { ?>
        window.addEvent('domready', function () {
            var checkLength = $$('input[name="id_category[]"]').length;	//check if checkbox exists on the page. For Buyer, checkbox is not present
            if (checkLength != 0) {
                $$('input[name="id_category[]"]').addEvent('click', updateSkillCheckbox);
                updateSkillCheckbox();
            }
        });
<?php } ?>

    function updateSkillCheckbox() {
        maxSkills = parseInt('<?php echo $maxSkills; ?>');

        sel = $$('input[name="id_category[]"]:checked').length;
        $('skill_left_span').set('html', sel);

        if (sel >= maxSkills)
            $$('input[name="id_category[]"]:not(:checked)').set('disabled', true);
        else
            $$('input[name="id_category[]"]:not(:checked)').set('disabled', false);
    }

    function editLocation() {
        $('level1').setStyle('display', 'inline-block').addClass('required');
    }

    //remove media
    jQuery(function () {
        jQuery(".remove_pic a").on("click", function (e) {
            e.preventDefault();
            if (confirm("Are you sure,you wanna delete this file.Changes wont take place unless you save the form.")) {
                var elem = jQuery(this);
                e.preventDefault();

                var c = "." + elem.attr("id");
                var relem = elem.attr("data-remove");
                var oldVal = jQuery("#removedmedia").val();
                var numbers = oldVal.split(',');

                console.log(c);
                if (oldVal.indexOf(relem) == -1)
                {
                    if (oldVal.length > 0)
                    {
                        removedMedia = oldVal + "," + relem;
                    }
                    else
                    {
                        removedMedia = relem;
                    }
                }

                jQuery(c).fadeOut(800, function () {
                    jQuery("#" + relem).fadeIn(800);


                });
                jQuery("#removedmedia").val(removedMedia);
            }


        })

    })


//-->
</script>
<?php //include_once(JPATH_COMPONENT.'/views/profilemenu.php');  ?>

<div class="col-md-9">
    <div class="row">
        <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="editProfile" id="frmEditProfile" class="edit-profile-wrapper form-validate form-horizontal" onsubmit="return validateForm(this);" enctype="multipart/form-data">
            <img id="output"/>
            <div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?></div>
            <fieldset>
                <?php /* ?><legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend><?php */ ?>
                <div class="control-group">
                    <label class="control-label"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
                    <div class="controls"> <?php echo $this->userInfo->username; ?> </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?> <span class="redfont">*</span>:</label>
                    <div class="controls">
                        <input class="inputbox required" type="text" name="name" id="name" value="<?php echo $this->userInfo->name; ?>" />
                    </div>
                </div>
                <!-- Company Name should be visible only to users who can post job -->
                <?php //echo '<pre>'; print_r($userInfo); die;
                if ($userInfo->allowPostProjects) :
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="biz_name"><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?> <span class="redfont">*</span>:</label>
                        <div class="controls">
                            <input class="inputbox required" type="text" name="biz_name" id="biz_name" value="<?php echo $this->userInfo->biz_name; ?>" />
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Skills and hourly rate should be visible only to users who can work/bid -->
<?php if ($userInfo->allowBidProjects) : ?>
                    <div class="control-group">
                        <label class="control-label" for="rate"><?php echo JText::_('COM_JBLANCE_HOURLY_RATE'); ?> <span class="redfont">*</span>:</label>
                        <div class="controls">
                            <div class="input-prepend input-append"> <span class="add-on"><?php echo $currencysym; ?></span>
                                <input class="input-mini required validate-numeric" type="text" name="rate" id="rate" value="<?php echo $this->userInfo->rate; ?>" />
                                <span class="add-on"><?php echo $currencycod . ' / ' . JText::_('COM_JBLANCE_HOUR'); ?></span> </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="id_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?> <span class="redfont">*</span>:</label>
                        <div class="controls">
    <?php if ($maxSkills > 0) { ?>
                                <div class="bid_project_left pull-left">
                                    <div><span id="skill_left_span" class="font26"><?php echo count(explode(',', $this->userInfo->id_category)) ?></span>/<span><?php echo $maxSkills; ?></span></div>
                                    <div><?php echo JText::_('COM_JBLANCE_SKILLS'); ?></div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="sp10">&nbsp;</div>
                            <?php } ?>
                            <?php
                            //$attribs = 'class="inputbox required" size="20" multiple ';
                            //$categtree = $select->getSelectCategoryTree('id_category[]', explode(',', $this->userInfo->id_category), 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
                            //echo $categtree; 
                            $attribs = '';
                            $select->getCheckCategoryTree('id_category[]', explode(',', $this->userInfo->id_category), $attribs);
                            ?>
                        </div>
                    </div>
<?php endif; ?>
            </fieldset>
            <fieldset>
                <legend><?php echo JText::_('COM_JBLANCE_CONTACT_INFORMATION'); ?></legend>
                <div class="control-group">
                    <label class="control-label" for="address"><?php echo JText::_('COM_JBLANCE_ADDRESS'); ?> <span class="redfont">*</span>:</label>
                    <div class="controls">
                        <textarea name="address" id="address" rows="3" class="input-xlarge required contact-address"><?php echo $this->userInfo->address; ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="level1"><?php echo JText::_('COM_JBLANCE_LOCATION'); ?> <span class="redfont">*</span>:</label>
<?php if ($this->userInfo->id_location > 0) { ?>
                        <div class="controls"> <?php echo JblanceHelper::getLocationNames($this->userInfo->id_location); ?>
                            <button type="button" class="btn btn-mini" onclick="editLocation();"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></button>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="controls controls-row" id="location_info">
                        <?php
                        $attribs = array('class' => 'input-medium', 'data-level-id' => '1', 'onchange' => 'getLocation(this, \'project.getlocationajax\');');

                        if ($this->userInfo->id_location == 0) {
                            $attribs['class'] = 'input-medium required';
                            $attribs['style'] = 'display: inline-block;';
                        } else {
                            $attribs['style'] = 'display: none;';
                        }
                        echo $select->getSelectLocationCascade('location_level[]', '', 'COM_JBLANCE_PLEASE_SELECT', $attribs, 'level1');
                        ?>
                        <input type="hidden" name="id_location" id="id_location" value="<?php echo $this->userInfo->id_location; ?>" />
                        <div id="ajax-container" class="dis-inl-blk"></div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="postcode"><?php echo JText::_('COM_JBLANCE_ZIP_POSTCODE'); ?> <span class="redfont">*</span>:</label>
                    <div class="controls">
                        <input class="input-small required" type="text" name="postcode" id="postcode" value="<?php echo $this->userInfo->postcode; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="mobile"><?php echo JText::_('COM_JBLANCE_CONTACT_NUMBER'); ?> :</label>
                    <div class="controls">
                        <input class="input-large" type="text" name="mobile" id="mobile" value="<?php echo $this->userInfo->mobile; ?>" />
                    </div>
                </div>
            </fieldset>
<?php

 if ($userInfo->name == 'Developers') { ?>
                <!-- extrea fields for user profile-->
                <fieldset class="extra_fields">
                    <!--apps  only if user has silver access-->
                    <div class="expand_all">
                        <button id="expand_all">Expand All</button>
                    </div>
                    <?php
                    if ($silverAccess || $goldAccess || $platinumAccess) {
                        
                       // echo '<pre>'; print_r($this->extra_fields); 
                        for ($i = 0; $i < 5; $i++) {
                            $num_fields = count($this->extra_fields['field_id_1']);
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            $fields = $allfields['field_id_' . $i];
                            
                             //echo 'field_id_'.$i;
                            // echo $fields[$i].'<br>'; 
                         
                            ?>
                            <div style= "<?php echo $hidden; ?>"  id="entry-<?php echo $fields[$i]; ?>" class="clonedInput_0  clone">
                                <div class="bg-success clonetitle">Your Apps <?php echo $i != 0 ? $i + 1 : ""; ?></div>
                                <fieldset id="apps">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_1">Your role</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" value="<?php echo $this->extra_fields['field_id_1'][$i]; ?>" type="text" name="field_id_1[]" id="field_id_1"  />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_2">Name</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_2[]" id="field_id_2" value="<?php echo $this->extra_fields['field_id_2'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_3">Year</label>
                                        <div class="controls">
                                            <select data-validation="date" data-validation-format="yyyy" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_3[]" id="field_id_3" value="<?php $this->extra_fields['field_id_3'][$i]; ?>" >
                                                <option value="">-- Select Year --</option>
                                                <?php
                                                for ($d = 1980; $d <= date("Y"); $d++):
                                                    $selected = $d == $this->extra_fields['field_id_3'][$i] ? "selected" : '';
                                                    echo'<option ' . $selected . ' value="' . $d . '">' . $d . '</option>';
                                                endfor;
                                                ?>
                                            </select>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_4">Description</label>
                                        <div class="controls">
                                            <textarea data-validation="length" data-validation-length="max500" data-validation-optional="true" <?php echo $disabled; ?>  class="input-large" type="text" name="field_id_4[]" id="field_id_4" ><?php echo $this->extra_fields['field_id_4'][$i]; ?> </textarea>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_5">Project URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?>  class="input-large" type="text" name="field_id_5[]" id="field_id_5" value="<?php echo $this->extra_fields['field_id_5'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_6">Upload Images</label>
                                        <div class="controls">
            <?php if (isset($this->extra_fields['field_id_6'][$i])): ?>
                                                <div class="prev_image app-img-<?php echo $i; ?>" id="prev_image_apps<?php echo $i; ?>"> <a href="media/developer/<?php echo $user->id . '/' . $this->extra_fields['field_id_6'][$i]; ?>" target="_blank" type="image" class="jcepopup" title="<?php echo $this->extra_fields['field_id_2'][$i]; ?>"> <img  src="media/developer/<?php echo $user->id . '/thumbnails/' . $this->extra_fields['field_id_6'][$i]; ?>" alt="" /> </a>
                                                    <div  class="remove_pic"><a  id="app-img-<?php echo $i; ?>" class="btn btn-danger" data-remove="field_id_6-<?php echo $i; ?>" href="#">Remove</a></div>
                                                </div>
                                            <?php endif; ?>
            <?php $display = isset($this->extra_fields['field_id_6'][$i]) ? "display:none;" : ""; ?>
                                            <input data-validation="mime size" data-validation-allowing="jpg, png , jpeg" data-validation-max-size="5M"  data-validation-optional="true"
                                                   style="<?php echo $display; ?>" class="input-large" <?php echo $disabled; ?>  type="file" name="field_id_6[]" id="field_id_6-<?php echo $i; ?>"  />
                                            <span class="field_tip"> {tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_1" name="btnAdd_1" class="btn btn-primary">View more</button>
                            <button type="button" style="display:none;" id="btnDel_1" name="btnDel_1" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsbscr_yourapps">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/your-apps.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Silver Geek</a> </div>
                        </div>
    <?php } ?>

                    <!--end-->

                    <?php
                    if ($silverAccess || $goldAccess || $platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>"   class="clonedInput_1 clone">
                                <div class="bg-success clonetitle">Your Widgets:</div>
                                <fieldset id="widgets">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_7">Your role</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_7[]" id="field_id_7" value="<?php echo $this->extra_fields['field_id_7'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_8">Name</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_8[]" id="field_id_8" value="<?php echo $this->extra_fields['field_id_8'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_9">Year</label>
                                        <div class="controls">
                                            <select data-validation="date" data-validation-format="yyyy" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_9[]" id="field_id_9" value="<?php $this->extra_fields['field_id_9'][$i]; ?>" >
                                                <option value="">-- Select Year --</option>
                                                <?php
                                                for ($d = 1980; $d <= date("Y"); $d++):
                                                    $selected = $d == $this->extra_fields['field_id_9'][$i] ? "selected" : '';
                                                    echo'<option ' . $selected . ' value="' . $d . '">' . $d . '</option>';
                                                endfor;
                                                ?>
                                            </select>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_10">Description</label>
                                        <div class="controls">
                                            <textarea data-validation="length" data-validation-length="max500" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_10[]" id="field_id_10" ><?php echo $this->extra_fields['field_id_10'][$i]; ?></textarea>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_11">Project URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_11[]" id="field_id_11" value="<?php echo $this->extra_fields['field_id_11'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_12">Upload Images</label>
                                        <div class="controls">
                                            <div class="controls">
            <?php if (isset($this->extra_fields['field_id_12'][$i])): ?>
                                                    <div class="prev_image widg-img-<?php echo $i; ?>" id="prev_image_apps<?php echo $i; ?>"> <a href="media/developer/<?php echo $user->id . '/' . $this->extra_fields['field_id_12'][$i]; ?>" target="_blank" type="image" class="jcepopup" title="<?php echo $this->extra_fields['field_id_8'][$i]; ?>"> <img  src="media/developer/<?php echo $user->id . '/thumbnails/' . $this->extra_fields['field_id_12'][$i]; ?>" alt="" /> </a>
                                                        <div  class="remove_pic"><a  id="widg-img-<?php echo $i; ?>" class="btn btn-danger" data-remove="field_id_12-<?php echo $i; ?>" href="#">Remove</a></div>
                                                    </div>
                                                <?php endif; ?>
            <?php $display = isset($this->extra_fields['field_id_12'][$i]) ? "display:none;" : ""; ?>
                                                <input data-validation="mime size" data-validation-allowing="jpg, png , jpeg" data-validation-max-size="5M"  data-validation-optional="true" style="<?php echo $display; ?>" class="input-large" <?php echo $disabled; ?>  type="file" name="field_id_12[]" id="field_id_12-<?php echo $i; ?>"  />
                                                <span class="field_tip"> {tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_2" name="btnAdd_2" class="btn btn-primary">View more</button>
                            <button type="button" style="display:none;" id="btnDel_2" name="btnDel_2" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsbscr_yourrole">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/your-widgets.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Silver Geek</a> </div>
                        </div>
                    <?php } ?>
                    <div class="resume">Add Your Resume</div>
                    <?php
                    if ($goldAccess || $platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_2 clone">
                                <div class="bg-success clonetitle">Your Experience:</div>
                                <fieldset id="experience">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_13">Your role</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_13[]" id="field_id_13" value="<?php echo $this->extra_fields['field_id_13'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_14">Name</label>
                                        <div class="controls">
                                            <input data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_14[]" id="field_id_14" value="<?php echo $this->extra_fields['field_id_14'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_15">Year</label>
                                        <div class="controls">
                                            <select data-validation="date" data-validation-format="yyyy" data-validation-optional="true"  <?php echo $disabled; ?> class="input-large" type="text" name="field_id_15[]" id="field_id_15" value="<?php $this->extra_fields['field_id_15'][$i]; ?>" >
                                                <option value="">-- Select Year --</option>
                                                <?php
                                                for ($d = 1980; $d <= date("Y"); $d++):
                                                    $selected = $d == $this->extra_fields['field_id_15'][$i] ? "selected" : '';
                                                    echo'<option ' . $selected . ' value="' . $d . '">' . $d . '</option>';
                                                endfor;
                                                ?>
                                            </select>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_16">Description</label>
                                        <div class="controls">
                                            <textarea data-validation="length" data-validation-length="max500" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_16[]" id="field_id_16" ><?php echo $this->extra_fields['field_id_16'][$i]; ?></textarea>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_17">Company URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_17[]" id="field_id_17" value="<?php echo $this->extra_fields['field_id_17'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_18">Upload Images</label>
                                        <div class="controls">
                                            <div class="controls">
            <?php if (isset($this->extra_fields['field_id_18'][$i])): ?>
                                                    <div class="prev_image exp-img-<?php echo $i; ?>" id="prev_image_apps<?php echo $i; ?>"> <a href="media/developer/<?php echo $user->id . '/' . $this->extra_fields['field_id_18'][$i]; ?>" target="_blank" type="image" class="jcepopup" title="<?php echo $this->extra_fields['field_id_14'][$i]; ?>"> <img  src="media/developer/<?php echo $user->id . '/thumbnails/' . $this->extra_fields['field_id_18'][$i]; ?>" alt="" /> </a>
                                                        <div  class="remove_pic"><a  id="exp-img-<?php echo $i; ?>" class="btn btn-danger" data-remove="field_id_18-<?php echo $i; ?>" href="#">Remove</a></div>
                                                    </div>
                                                <?php endif; ?>
            <?php $display = isset($this->extra_fields['field_id_18'][$i]) ? "display:none;" : ""; ?>
                                                <input data-validation="mime size" data-validation-allowing="jpg, png , jpeg" data-validation-max-size="5M"  data-validation-optional="true" style="<?php echo $display; ?>" class="input-large" <?php echo $disabled; ?>  type="file" name="field_id_18[]" id="field_id_18-<?php echo $i; ?>"  />
                                                <span class="field_tip"> {tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_3" name="btnAdd_3" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_3" name="btnDel_3" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsbscr_exp">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/your-experience.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Gold Geek</a> </div>
                        </div>
                    <?php } ?>
                    <?php
                    if ($goldAccess || $platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_3 clone">
                                <div class="bg-success clonetitle">Your Qualifications:</div>
                                <fieldset id="qualifications">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_19">Name</label>
                                        <div class="controls">
                                            <input  data-validation="length" data-validation-length="max50" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_19[]" id="field_id_19" value="<?php echo $this->extra_fields['field_id_19'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_20">Year</label>
                                        <div class="controls">
                                            <select data-validation="date" data-validation-format="yyyy" data-validation-optional="true"  <?php echo $disabled; ?> class="input-large" type="text" name="field_id_20[]" id="field_id_20" value="<?php $this->extra_fields['field_id_20'][$i]; ?>" >
                                                <option value="">-- Select Year --</option>
                                                <?php
                                                for ($d = 1980; $d <= date("Y"); $d++):
                                                    $selected = $d == $this->extra_fields['field_id_20'][$i] ? "selected" : '';
                                                    echo'<option ' . $selected . ' value="' . $d . '">' . $d . '</option>';
                                                endfor;
                                                ?>
                                            </select>
                                            {tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_21">Description</label>
                                        <div class="controls">
                                            <textarea data-validation="length" data-validation-length="max500" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_21[]" id="field_id_21" ><?php echo $this->extra_fields['field_id_21'][$i]; ?></textarea>
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_4" name="btnAdd_4" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_4" name="btnDel_4" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsbscr_yourqualifications">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/your-qualifications.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Gold Geek</a> </div>
                        </div>
                    <?php } ?>
                    <?php
                    if ($goldAccess || $platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_4 clone">
                                <div class="bg-success clonetitle">Your Websites:</div>
                                <fieldset id="websites">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_22">Company URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?>  class="input-large" type="text" name="field_id_22[]" id="field_id_22" value="<?php echo $this->extra_fields['field_id_22'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_5" name="btnAdd_5" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_5" name="btnDel_5" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsubscr_yourqualifications">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/your-websites.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Gold Geek</a> </div>
                        </div>
                    <?php } ?>
                    <div class="dealmakers">Add your deal makers (Make your profile stand out from the crowd)</div>
                    <?php
                    if ($platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_5 clone">
                                <div class="bg-success clonetitle">Upload a photo or video tour of your office:</div>
                                <fieldset id="office_video ">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_23">Upload a photo or video tour of your office</label>
                                        <div class="controls">
                                            <?php
                                            if (isset($this->extra_fields['field_id_23'][$i])):

                                                $path = JUri::root() . 'media/developer/' . $user->id . '/' . $this->extra_fields['field_id_23'][$i];
                                                //	$elem='prev_video_vto'.$i; 
                                                //   echo 	JblanceHelper::getVideoPalyer($path,$elem);
                                                ?>
                                                <div class="prev_video vid-tour-<?php echo $i; ?>" id="prev_video_vto<?php echo $i; ?>"> <a href="<?php echo $path; ?>" target="_blank" type="video/mp4" class="jcepopup" data-mediabox-controls="controls" data-mediabox-poster="images/logo.png" data-mediabox-height="483" title="Video tour:Office" data-mediabox-width="854"><img src="images/film-strip.png"/></a>
                                                    <div  class="remove_pic"><a  id="vid-tour-<?php echo $i; ?>" class="btn btn-danger" data-remove="field_id_23-<?php echo $i; ?>" href="#">Remove</a></div>
                                                </div>
                                            <?php endif; ?>
            <?php $display = isset($this->extra_fields['field_id_23'][$i]) ? "display:none;" : ""; ?>
                                            <input data-validation="mime size" data-validation-allowing="mp4" data-validation-max-size="20M"  data-validation-optional="true" <?php echo $disabled; ?> style="<?php echo $display; ?>" class="input-large" type="file" name="field_id_23[]" id="field_id_23-<?php echo $i; ?>"  />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_6" name="btnAdd_6" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_6" name="btnDel_6" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsubscr_yourweb">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/video-tour-office.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Platinum Geek</a> </div>
                        </div>
                    <?php } ?>
                    <?php
                    if ($platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_6 clone">
                                <div class="bg-success clonetitle">Upload a client testimonial (mp4):</div>
                                <fieldset id="testimonial">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_24">Upload a client testimonial (mp4)</label>
                                        <div class="controls">
                                            <?php
                                            if (isset($this->extra_fields['field_id_24'][$i])):

                                                $path = JUri::root() . 'media/developer/' . $user->id . '/' . $this->extra_fields['field_id_24'][$i];
                                                //	$elem='prev_video_vto'.$i; 
                                                //   echo 	JblanceHelper::getVideoPalyer($path,$elem);
                                                ?>
                                                <div class="prev_video test-vid-<?php echo $i; ?>" id="prev_video_vto<?php echo $i; ?>"> <a href="<?php echo $path; ?>" target="_blank" type="video/mp4" class="jcepopup" data-mediabox-controls="controls" data-mediabox-poster="images/logo.png" data-mediabox-height="483" title="Video tour:Office" data-mediabox-width="854"><img src="images/film-strip.png"/></a>
                                                    <div  class="remove_pic"><a  id="test-vid-<?php echo $i; ?>" class="btn btn-danger" data-remove="field_id_24-<?php echo $i; ?>" href="#">Remove</a></div>
                                                </div>
                                            <?php endif; ?>
            <?php $display = isset($this->extra_fields['field_id_24'][$i]) ? "display:none;" : ""; ?>
                                            <input style="<?php echo $display; ?>" data-validation="mime size" data-validation-allowing="mp4" data-validation-max-size="20M"  data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="file" name="field_id_24[]" id="field_id_24-<?php echo $i; ?>"  />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_7" name="btnAdd_7" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_7" name="btnDel_7" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsubscr_testimonial">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/testimonial.png"/> </div>
                            <div class="renewsubscription"> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Platinum Geek</a> </div>
                        </div>
                    <?php } ?>
                    <?php
                    if ($platinumAccess) {
                        for ($i = 0; $i < 5; $i++) {
                            $hidden = $i > 0 ? "display:none;" : "display:block;";
                            $disabled = $i > 0 ? "disabled='disabled'" : "";
                            ?>
                            <div id="entry1" style= " <?php echo $hidden; ?>" class="clonedInput_7 clone">
                                <div class="bg-success clonetitle">Link your Social Media:</div>
                                <fieldset id="link_sm">
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_25">Facebook URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_25[]" id="field_id_25" value="<?php echo $this->extra_fields['field_id_25'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_26">Google+ URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_26[]" id="field_id_26" value="<?php echo $this->extra_fields['field_id_26'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_27">Twitter URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_27[]" id="field_id_27" value="<?php echo $this->extra_fields['field_id_27'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_28">Linked In URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_28[]" id="field_id_28" value="<?php echo $this->extra_fields['field_id_28'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_29">Behance URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_29[]" id="field_id_29" value="<?php echo $this->extra_fields['field_id_29'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_30">Pinterest URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_30[]" id="field_id_30" value="<?php echo $this->extra_fields['field_id_30'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="field_id_31">Dribble URL</label>
                                        <div class="controls">
                                            <input data-validation="url" data-validation-optional="true" <?php echo $disabled; ?> class="input-large" type="text" name="field_id_31[]" id="field_id_31" value="<?php echo $this->extra_fields['field_id_31'][$i]; ?>" />
                                            <span class="field_tip">{tip Wow!::Now I can give something a cool tooltip!}<img src="images/help.png"/>{/tip}</span> </div>
                                    </div>
                                </fieldset>
                            </div>
        <?Php } ?>
                        <p>
                            <button type="button" id="btnAdd_8" name="btnAdd_8" class="btn btn-primary">View more</button>
                            <button style="display:none;" type="button" id="btnDel_8" name="btnDel_8" class="btn btn-danger">Close</button>
                        </p>
    <?php } else { ?>
                        <div class="up_subscription upsubscr_sm">
                            <div class="disabled_field"> <img src="<?php JUri::root() ?>images/disabledfields/social-media.png"/> </div>
                            <div class="renewsubscription"> <a  href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans&Itemid=526'); ?>">Upgrade To Platinum Geek</a> </div>
                        </div>
                <?php } ?>
                </fieldset>
<?php } ?>
            <!--end-->
            <div class="form-actions">
                <input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="btn btn-primary" />
            </div>
            <input type="hidden" name="option" value="com_jblance">
            <input type="hidden" name="task" value="user.saveprofile">
            <input type="hidden" name="id" value="<?php echo $this->userInfo->id; ?>">
            <input type="hidden" id="removedmedia" name="removedmedia"/>
        </form>
    </div>

</div>
<div class="col-md-3">



</div>








<?php
echo JHtml::_('form.token');
JHtml::stylesheet(Juri::base() . 'media/com_jblance/jQuery-Form-Validator-master/form-validator/theme-default.min.css');
JHTML::script(Juri::base() . 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
?>
<script type="text/javascript">
    jQuery.validate({
        form: "#frmEditProfile",
        modules: "security, file",
        onElementValidate: function (valid, $el, $form, errorMess)
        {
            if (!valid)
            {
                var type = $el.attr("type");
                if (type == "file")
                {
                    $el.attr("value", "");
                }
            }
        }
    });
</script> 
