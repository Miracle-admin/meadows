<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Post / Edit project (jblance)
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal', 'a.jb-modal');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', '.advancedSelect');

$doc = JFactory::getDocument();

$doc->addScript("components/com_jblance/js/btngroup.js");
$doc->addScript("components/com_jblance/js/utility.js");

$select = JblanceHelper::get('helper.select');  // create an instance of the class SelectHelper
$finance = JblanceHelper::get('helper.finance');  // create an instance of the class FinanceHelper
JblanceHelper::setJoomBriToken();
$devType = $this->devtype;
$platform = $this->platform;
$budget = $this->budget;
$config = JblanceHelper::getConfig();
$allowedExts = str_replace(' ', '', str_replace(',', '|', $config->projectFileText));
$select = JblanceHelper::get('helper.select');
$currencysym = $config->currencySymbol;
$countries = $this->countries;
$plan = $this->plan;
$fileLimitConf = $config->projectMaxfileCount;
//project upgrades
$sellfeatured = $plan->sellfeatured;
$sellurgent = $plan->sellurgent;
$sellprivate = $plan->sellprivate;
$sellsealed = $plan->sellsealed;
$sellassisted = $plan->sellassisted;
$sellnda = $plan->sellnda;
//bonus
$bonus = $plan->bonusFund;
$planParams = json_decode($plan->params);
//project upgrade prices
$sellfeaturedFee = $planParams->buyFeePerFeaturedProject;
$sellurgentFee = $planParams->buyFeePerUrgentProject;
$sellprivateFee = $planParams->buyFeePerPrivateProject;
$sellsealedFee = $planParams->buyFeePerSealedProject;
$sellassistedFee = $planParams->buyFeePerAssistedProject;
$sellndaFee = $planParams->buyFeePerNDAProject;
$app = JFactory::getApplication();
$guest = $this->guest[0];
$guestEmail = $this->guest[1];
$guestName = $this->guest[2];
$user = JFactory::getUser();


JText::script('COM_JBLANCE_ENTER_PJ_TITLE');
JText::script('COM_JBLANCE_ENTER_PJ_DESC');
JText::script('COM_JBLANCE_SELECT_PLATFORM');
JText::script('COM_JBLANCE_SELECT_BUDGET');
JText::script('COM_JBLANCE_SELECT_DEVELOPER_TYPE');
JText::script('COM_JBLANCE_ENTER_EMAIL');
JText::script('COM_JBLANCE_ENTER_VALID_EMAIL');
JText::script('COM_JBLANCE_EMAIL_EXISTS');
JText::script('COM_JBLANCE_CHOOSE_COUNTRY');
JText::script('COM_JBLANCE_TANDC_AGREE');
JText::script('COM_JBLANCE_ENTER_NUMBER_DAYS');
JText::script('COM_JBLANCE_ENTER_VALID_NUMBER');
JText::script('COM_JBLANCE_EMAIL_AEXISTS');

$coun = $app->getUserState('pj_count', '');
$stt = $app->getUserState('pj_state', '');
$model = $this->getModel();
$pid = JRequest::getInt("pid");
if ($pid) {
    $project_data = $model->getEditProject($pid);
    $this->row = $project_data[0];
}//echo '<pre>'; print_r($this->row); die;
$prj_title = '';
$prj_dec = '';
$prj_expires = '';
$prj_is_featured = '';
if (!empty($project_data)) {
    $prj_title = $project_data[0]->project_title;
    $prj_dec = $project_data[0]->description;
    $prj_expires = $project_data[0]->expires;
    $prj_is_featured = $project_data[0]->is_featured;
    $coun = $project_data[0]->id_location;
    $prj_dec = $project_data[0]->description;
    $prj_dec = $project_data[0]->description;
}
//echo '<pre>'; print_r($project_data);
//die;
//set country id

JRequest::setVar('location_id', $coun);

$state = $model->getLocations(false);

//set state id
JRequest::setVar('location_id', $stt);

$city = $model->getLocations(false);
$user = JFactory::getUser();


?>

<div class="post-projectp-plan-wrap">
  <h2>AppMeadows Project Post Plan</h2>
  <ul class="projectp-plan-tab">
    <li class="active"><a data-toggle="tab" href="#plan1">
    	<span class="cricle"><img src="<?php echo $this->baseurl; ?>/templates/home/images/dev-icon.png" alt=""></span>
      <h4>App Meadows Regular</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </a></li>
    <li><a data-toggle="tab" href="#plan2">
    <span class="cricle"><img src="<?php echo $this->baseurl; ?>/templates/home/images/pm-icon.png" alt=""></span>
      <h4>APP Meadows  PM</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </a></li>
    <li><a data-toggle="tab" href="#plan3">
    <span class="cricle"><img src="<?php echo $this->baseurl; ?>/templates/home/images/testing-icon.png" alt=""></span>
      <h4>App Meadows Test</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </a></li>
    <li><a data-toggle="tab" href="#plan4">
    <span class="cricle"><img src="<?php echo $this->baseurl; ?>/templates/home/images/agile-icon.png" alt=""></span>
      <h4>App Meadows Agile</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </a></li>
  </ul>
  <div class="tab-content">
    <div id="plan1" class="tab-pane fade in active">
      <form name="editprojectcustom" id="editprojectcustom" action="" method="post" enctype="multipart/form-data">
        <div class="dev-reg-wrap post-project-wrapper">
          <div class="dev-reg-hdr">
            <h2>App Meadows Regular</h2>
            <span> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque </span> </div>
          
          <!--banner--section--close-->
          <div class="dev-reg-common">
            <div class="input_sign_up_wrapper top-hdr-post">
              <div class="control-group">
                <input name="pj_title" value="<?php echo ($app->getUserState('pj_title', '')) ? $app->getUserState('pj_title', '') : $prj_title; ?>" type="text" 
                           class="" placeholder="Project name" aria-describedby="basic-addon1" 
                           data-validation="required" data-validation-error-msg="Please fill project title.">
              </div>
              <div class="control-group textarea-desp">
                <textarea name="pz_desc"  placeholder="Project Description" data-validation="required" data-validation-error-msg="Please fill project description." class="message_box_wrapper"><?php echo ($app->getUserState('pz_desc', '')) ? $app->getUserState('pz_desc', '') : $prj_dec; ?></textarea>
              </div>
              <div class="upload_drag_wrapper control-group">
                <label>Attach files</label>
                <?php
                    for ($i = 0; $i < $fileLimitConf; $i++) {
                        ?>
                <!--                            <input name="uploadFile<?php //echo $i;    ?>" type="file" id="uploadFile<?php //echo $i;    ?>" />-->
                <input  type="file" name="uploadFile<?php echo $i; ?>"
                                type="file" id="uploadFile<?php echo $i; ?>"
                                data-validation="mime size"
                                data-validation-allowing="jpg, gif, word, excel, pdf, zip"
                                data-validation-max-size="3M"
                                data-validation-error-msg-size="You can not upload images larger than 3M"
                                data-validation-error-msg-mime="You can only upload jpg, gif, word, excel, pdf, zip"/>
                <br>
                <?php }
                    ?>
                <div class="allowed_exts">(Allowed file types: <?php echo $config->projectFileText; ?>)</div>
              </div>
            </div>
            <div class="mess_wrapper control-group">
              <label class="  proposal_left_wrap">Open for proposals for</label>
              <div class="message_box_sign_up_wrap control">
                <input  data-validation="required number"  name="pj_expires" value="<?php echo ($app->getUserState('pj_expires', '')) ? $app->getUserState('pj_expires', '') : $prj_expires; ?>"  
                            type="text" >
              </div>
              <p class="days_mess_wrap">days (from 15 to 60 days)</p>
            </div>
            <div class="control-group">
              <?php
                if ($this->row->id) {
                    $cats = JblanceHelper::getCategoryNames($this->row->id_category);
                    $cats_exploaded = explode(", ", $cats);
                }
//                             /print_r($cats_exploaded[1]); die; 
                ?>
              <!--generate platform-->
              <div class="styled-select">
                <select name="pj_platform"   data-validation="required" data-validation-error-msg="Please select platform.">
                  <option value="">Select Platform</option>
                  <?php
                        foreach ($platform as $value) {
                            if ($this->row->id) {
                                ?>
                  <option <?php echo ( $cats_exploaded[1] == $value[1] ) ? "selected = selected" : '' ?> 
                                    value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?></option>
                  <?php } else { ?>
                  <option <?php echo $app->getUserState('pj_platform', '') == $value[0] ? "selected" : ''; ?> 
                                    value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?></option>
                  <?php } ?>
                  <!--                                <option <?php //echo $app->getUserState('pj_platform', '') == $value[0] ? "selected" : '';  ?> 
                                                    value="<?php //echo $value[0];  ?>"><?php //echo $value[1];  ?></option>-->
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <div class="styled-select">
                <select name="pj_budget" data-validation="required" data-validation-error-msg="Please select budget range.">
                  <option value="">Budget range</option>
                  <?php
                        foreach ($budget as $value) {

                            $val = $value->budgetmin . ',' . $value->budgetmax;
                            if ($this->row->id) {
                                ?>
                  <option <?php echo $this->row->budgetmin . ',' . $this->row->budgetmax == $val ? "selected" : ''; ?> 
                                    value="<?php echo $val; ?>"><?php echo $currencysym . $value->budgetmin . ' - ' . $currencysym . $value->budgetmax; ?></option>
                  <?php } else {
                                    ?>
                  <option <?php echo $app->getUserState('pj_budget', '') == $val ? "selected" : ''; ?> value="<?php echo $val; ?>"><?php echo $currencysym . $value->budgetmin . ' - ' . $currencysym . $value->budgetmax; ?></option>
                  <?php }
                        } ?>
                </select>
              </div>
            </div>
            <div class="control-group"> 
              <!-- generate the developer type dropdown-->
              <div class="styled-select">
                <select name="dev_type" data-validation="required" data-validation-error-msg="Please select developer type.">
                  <option value="">What kind of professional are you looking for</option>
                  <?php
                        foreach ($devType as $value) {
                            if ($this->row->id) {
                                ?>
                  <option <?php echo ( $cats_exploaded[0] == $value[1] ) ? "selected = selected" : '' ?> 
                                    value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?></option>
                  <?php } else { ?>
                  <option <?php echo $app->getUserState('dev_type', '') == $value[0] ? "selected" : ''; ?> value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?></option>
                  <?php }
} ?>
                </select>
              </div>
            </div>
          </div>
          
          <!--register--section--close-->
          <div class="dev-reg-common">
            <h4>User information</h4>
            <div class="input_sign_up_wrapper">
              <div class=" control-group">
                <input type="text" name="u_name" <?php echo!$guest ? "readonly='true'" : ''; ?> value="<?php echo!$guest ? $guestName : $app->getUserState('u_name', ''); ?>" class="" placeholder="Name" aria-describedby="basic-addon1">
              </div>
              <div class=" control-group">
                <input type="text" class="" name="u_email" <?php echo!$guest ? "readonly='true'" : ''; ?>value="<?php echo!$guest ? $guestEmail : $app->getUserState('u_email', ''); ?>" placeholder="Email" email-data="<?php echo!$guest ? "0" : "1"; ?>" aria-describedby="basic-addon1"    >
              </div>
            </div>
            <?php if ($this->row->id) { ?>
            <div class="control-group">
              <label class="control-label" for="level1"><?php echo JText::_('COM_JBLANCE_LOCATION'); ?> <span class="redfont">*</span>:</label>
              <?php if ($this->row->id_location > 0) { ?>
              <div class="controls"> <?php echo JblanceHelper::getLocationNames($this->row->id_location); ?>
                <button type="button" class="btn btn-mini" onclick="editLocation();"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></button>
              </div>
              <?php
                    }
                    ?>
              <div class="controls controls-row" id="location_info">
                <?php
                        $attribs = array('class' => 'input-medium', 'data-level-id' => '1', 'onchange' => 'getLocation(this, \'project.getlocationajax\');');

                        if ($this->row->id_location == 0) {
                            $attribs['class'] = 'input-medium required';
                            $attribs['style'] = 'display: inline-block;';
                        } else {
                            $attribs['style'] = 'display: none;';
                        }
                        echo $select->getSelectLocationCascade('location_level[]', '', 'COM_JBLANCE_PLEASE_SELECT', $attribs, 'level1');
                        ?>
                <input type="hidden" name="id_location" id="id_location" value="<?php echo $this->row->id_location; ?>" />
                <div id="ajax-container" class="dis-inl-blk"></div>
              </div>
            </div>
            <?php } else { ?>
            <div class="input_sign_up_wrapper">
              <h4>Country</h4>
              <div class="control-group">
                <div class="styled-select">
                  <select name="pj_count" id="country_select" data-validation="required" data-validation-error-msg="Please select country.">
                    <option value="">--Select country--</option>
                    <?php foreach ($countries as $value) { ?>
                    <option <?php echo $app->getUserState('pj_count', '') == $value->id ? "selected" : ""; ?> 
                                        value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                    <?php } ?>
                  </select>
                  <span style="display:none;" id="loading_loc"><img src="images/loading-loc.gif"/></span></div>
              </div>
              <div class="control-group">
                <div class="styled-select">
                  <select name="pj_state" id="state_select" data-validation="required" data-validation-error-msg="Please select state.">
                    <?php if (count($state) > 1) {
                                    ?>
                    <option value="">--Select state--</option>
                    <?php foreach ($state as $st) { ?>
                    <option <?php echo $app->getUserState('pj_state', '') == $st['id'] ? "selected" : ""; ?> value="<?php echo $st['id']; ?>"><?php echo $st['title']; ?></option>
                    <?php
                                    }
                                } else {
                                    ?>
                    <option value="">--Select state--</option>
                    <?php } ?>
                  </select>
                  <span style="display:none;" id="loading_state"><img src="images/loading-loc.gif"/></span></div>
              </div>
              <div class="control-group">
                <div class="styled-select">
                  <select name="pj_city" id="city_select">
                    <?php if (count($city) > 1) {
                                    ?>
                    <option value="">--Select city--</option>
                    <?php foreach ($city as $ct) { ?>
                    <option <?php echo $app->getUserState('pj_city', '') == $ct['id'] ? "selected" : ""; ?> value="<?php echo $ct['id']; ?>"><?php echo $ct['title']; ?></option>
                    <?php
                                    }
                                } else {
                                    ?>
                    <option value="">--Select city--</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          
          <!--details--section--close-->
          <?php /*  ?> <div class="dev-reg-accwrap">
            <h4>Featured Options</h4>
            <div class="row">
                <?php if ($sellurgent) { ?>
                    <!--urgent-->
                    <ul class="featured-labels">
                        <div class="acc_package_wrap">
                            <div class="pricing_check_wrap"><span class="check_btn">
                                    <input <?php echo $app->getUserState('buyFeePerUrgentProject', '') == 1 ? "checked" : ''; ?> id="Check16" name="buyFeePerUrgentProject" type="checkbox" value="1">
                                    <label for="Check16"></label>
                                </span> </div>
                            <h3>Urgent</h3>
                            <p><strong>Get your project validated<br>
                                    instantly and</strong> let developers<br>
                                know this project needs to start<br>
                                soon. </p>
                            <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym . $sellurgentFee; ?></a></div>
                        </div>
                    </ul>
                <?php } ?>
                <!--urgent end--> 

                <!--private-->
                <?php if ($sellprivate) { ?>
                    <ul class="featured-labels">
                        <div class="acc_package_wrap">
                            <div class="pricing_check_wrap"><span class="check_btn">
                                    <input id="Check17" <?php echo $app->getUserState('buyFeePerPrivateProject', '') == 1 ? "checked" : ''; ?> name="buyFeePerPrivateProject" type="checkbox" value="1">
                                    <label for="Check17"></label>
                                </span> </div>
                            <h3>Private</h3>
                            <p><strong>Get your project validated<br>
                                    instantly and</strong> let developers<br>
                                know this project needs to start<br>
                                soon. </p>
                            <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym . $sellprivateFee; ?></a></div>
                        </div>
                    </ul>
                <?php } ?>
                <!--private end--> 

                <!--featured start-->
                <?php if ($sellfeatured) { ?>
                    <ul class="featured-labels">
                        <div class="acc_package_wrap">
                            <div class="pricing_check_wrap"><span class="check_btn">
                                    <input id="Check18" name="buyFeePerFeaturedProject" <?php echo $app->getUserState('buyFeePerFeaturedProject', '') == 1 ? "checked" : ''; ?> type="checkbox" value="1">
                                    <label for="Check18"></label>
                                </span> </div>
                            <h3>Featured </h3>
                            <p><strong>Get your project validated<br>
                                    instantly and</strong> let developers<br>
                                know this project needs to start<br>
                                soon. </p>
                            <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym . $sellfeaturedFee; ?></a></div>
                        </div>
                    </ul>
                <?php } ?>
                <!--featured end--> 

                <!--assisted start-->
                <?php if ($sellassisted) { ?>
                    <ul class="featured-labels">
                        <div class="acc_package_wrap">
                            <div class="pricing_check_wrap"><span class="check_btn">
                                    <input id="Check19" name="buyFeePerAssistedProject" <?php echo $app->getUserState('buyFeePerAssistedProject', '') == 1 ? "checked" : ''; ?> type="checkbox" value="1">
                                    <label for="Check19"></label>
                                </span> </div>
                            <h3>Assisted</h3>
                            <p><strong>Get your project validated<br>
                                    instantly and</strong> let developers<br>
                                know this project needs to start<br>
                                soon. </p>
                            <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym . $sellassistedFee; ?></a></div>
                        </div>
                    </ul>
                <?php } ?>
                <!--assisted end--> 
            </div>
            <?php */?>
          <div class="dev-reg-common">
            <div class="accept_terms_wrapper accept_wrap">
              <p class="check_btn">
                <input id="Check21" name="term_cond" type="checkbox" data-validation="required" data-validation-error-msg="Please select accept the terms and conditions."
                           value="1" <?php echo $app->getUserState('term_cond', '') == 1 ? "checked" : ''; ?>>
                <label for="Check21"></label>
                <span class="receive_alert_wrapper">I accept the terms and conditions</span> </p>
            </div>
            <input type="submit" class="signup-brn" value="Create App Project"/>
          </div>
        </div>
        <input type="hidden" value="com_jblance" name="option">
        <input type="hidden" name="pid" value="<?php echo $this->row->id; ?>" />
        <input type="hidden" value="project.appsaveproject" name="task">
        <input type="hidden" id="filelimit" value="<?php echo $fileLimitConf; ?>" name="uploadLimit">
        <input type="hidden" id="allowedExts" value="<?php echo $allowedExts; ?>"/>
        <?php echo JHtml::_('form.token'); ?>
      </form>
    </div>
    <div id="plan2" class="tab-pane fade">
      <h3>APP Meadows  PM</h3>
    </div>
    <div id="plan3" class="tab-pane fade">
      <h3>App Meadows Test</h3>
    </div>
    <div id="plan4" class="tab-pane fade">
      <h3>App Meadows Agile</h3>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_jblance/views/project/assets/jquery.validate.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_jblance/views/project/assets/addmethods.js'; ?>"></script> 
<!--<script type="text/javascript" src="<?php //echo JUri::root() . 'components/com_jblance/views/project/assets/validate.js';   ?>"></script>-->

<?php
JHTML::script(Juri::base() . 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
?>
<script>
              jQuery.validate({
                  modules: 'security,toggleDisabled , file',
                  onSuccess: function (form) {
                      window.form = form;
                      jQuery("#loading_nonce").show();
                      //  return false;
                  },
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
//update countries and states and cities
              jQuery("#country_select").on('change', function () {
                  var field = jQuery(this).val();
                  var state = jQuery("#state_select");
                  var city = jQuery("#city_select");
                  var loading = jQuery("#loading_loc");
                  loading.show();
                  jQuery.post("index.php?option=com_jblance&task=project.getnewlocationAjax", {location_id: field}, function (a) {
                      state.empty();

                      var data = JSON.parse(a);
                      console.log(data);
                      if (data != 0)
                      {

                          state.append("<option value=''>--Select state--</option>");
                          for (x = 0; x < data.length; x++) {

                              state.append("<option value='" + data[x].id + "'>" + data[x].title + "</option>");
                              city.empty();
                              city.append("<option value=''>--Select city--</option>");
                          }

                      }
                      else
                      {
                          state.append("<option value=''>--No information--</option>");
                          city.empty();
                          city.append("<option value=''>--No information--</option>");
                      }

                      loading.hide();
                  })
              })
//update city
              jQuery("#state_select").on('change', function () {
                  var field = jQuery(this).val();

                  var city = jQuery("#city_select");
                  var loading = jQuery("#loading_state");
                  loading.show();
                  jQuery.post("index.php?option=com_jblance&task=project.getnewlocationAjax", {location_id: field}, function (a) {
                      city.empty();
                      var data = JSON.parse(a);
                      if (data != 0)
                      {
                          city.append("<option value=''>--Select city--</option>");
                          for (x = 0; x < data.length; x++) {
                              city.append("<option value='" + data[x].id + "'>" + data[x].title + "</option>");
                          }
                      }
                      else {
                          city.append("<option value=''>--No information--</option>");
                      }


                      loading.hide();
                  });
              });
              function editLocation() {
                  jQuery('#level1').css('display', 'inline-block').addClass('required');
              }
</script> 