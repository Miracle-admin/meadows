<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/register.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework', true);
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal', 'a.jb-modal');
//JHtml::_('bootstrap.tooltip');
jimport('joomla.application.component.controller');
$doc = JFactory::getDocument();
$doc->addScript("components/com_jblance/js/utility.js");


$app = JFactory::getApplication();
$user = JFactory::getUser();
$model = $this->getModel();
$config = JblanceHelper::getConfig();
$taxpercent = $config->taxPercent;
$taxname = $config->taxName;

$session = JFactory::getSession();
$ugid = $session->get('ugid', 0, 'register');
$planChosen = $session->get('planChosen', 0, 'register');
$planId = $session->get('planid', 0, 'register');
$skipPlan = $session->get('skipPlan', 0, 'register');

$jbuser = JblanceHelper::get('helper.user');  // create an instance of the class UserHelper

$step = $app->input->get('step', 0, 'int');
JText::script('COM_JBLANCE_AVAILABLE');

JblanceHelper::setJoomBriToken();

$devType = $this->devtype;
$work = $this->worktype;

$plan = $this->developerPlan;
//echo '<pre>'; print_r($plan); die;
$fileLimitConf = $config->projectMaxfileCount;


JText::script('COM_JBLANCE_SELECT_DEVELOPER_SKILL');
JText::script('COM_JBLANCE_ENTER_EMAIL');
JText::script('COM_JBLANCE_ENTER_VALID_EMAIL');
JText::script('COM_JBLANCE_EMAIL_AEXISTS');
JText::script('COM_JBLANCE_USERNAME_AEXISTS');
JText::script('COM_JBLANCE_URL_AEXISTS');
JText::script('COM_JBLANCE_CHOOSE_COUNTRY');
JText::script('COM_JBLANCE_CHOOSE_STATE');
JText::script('COM_JBLANCE_CHOOSE_CITY');
JText::script('COM_JBLANCE_CHOOSE_TERMS');

$countries = $this->countries;
$coun = $app->getUserState('pj_count', '');
$stt = $app->getUserState('pj_state', '');
$ctt = $app->getUserState('pj_city', '');
$model = $this->getModel();

//set country id
JRequest::setVar('location_id', $coun);

$state = $model->getLocations(false);

//set state id
JRequest::setVar('location_id', $stt);

$city = $model->getLocations(false);
?>
<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_jblance/views/developer/assets/jquery.validate.js'; ?>"></script>
<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_jblance/views/developer/assets/addmethods.js'; ?>"></script>
<script type="text/javascript" src="<?php echo JUri::root() . 'components/com_jblance/views/developer/assets/validate.js'; ?>"></script>
<?php
if ($step)
    echo JblanceHelper::getProgressBar($step);
?>
<?php
$document = &JFactory::getDocument();
$renderer = $document->loadRenderer('modules');
$position = 'fgrlogin';
$options = array('style' => 'raw');
echo $renderer->render($position, $options, null);
?>

<div class="container">
  <div class="dev-reg-wrap">
    <div class="dev-reg-hdr">
      <h2>Developer Registration </h2>
      <span> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque </span> </div>
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="regNewUser"  enctype="multipart/form-data" id="developerRegistration">
      <div class="dev-reg-common">
        <?php /*?> <h4><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></h4><?php */?>
        <?php echo JText::_('COM_JBLANCE_FIELDS_COMPULSORY'); ?>
        <div class="control-group">
          <label class="control-label" for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input type="text" name="username" id="username" value="<?php echo $app->getUserState('username', ''); ?>" class="inputbox hasTooltip required validate-username"  title="<?php //echo JHtml::tooltipText(JText::_('COM_JBLANCE_TT_USERNAME')); ?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="email"><?php echo JText::_('COM_JBLANCE_EMAIL'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input type="text" name="email" id="email" value="<?php echo $app->getUserState('email', ''); ?>" class="inputbox hasTooltip required validate-email"  title="<?php //echo JHtml::tooltipText(JText::_('COM_JBLANCE_TT_EMAIL')); ?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="password"><?php echo JText::_('COM_JBLANCE_PASSWORD'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input type="password" name="password" id="password" value="<?php echo $app->getUserState('password', ''); ?>" class="inputbox hasTooltip required validate-password" title="<?php //echo JHtml::tooltipText(JText::_('COM_JBLANCE_TT_PASSWORD')); ?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="password2"><?php echo JText::_('COM_JBLANCE_CONFIRM_PASSWORD'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input type="password" size="40" maxlength="100" name="password2" id="password2" value="<?php echo $app->getUserState('password2', ''); ?>"class="inputbox hasTooltip required validate-password" title="<?php //echo JHtml::tooltipText(JText::_('COM_JBLANCE_TT_REPASSWORD')); ?>">
          </div>
        </div>
        <h4><?php echo JText::_('COM_JBLANCE_DEVELOPER_INFORMATION'); ?></h4>
        <div class="control-group">
          <label class="control-label" for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input class="inputbox required" type="text" name="dname" value="<?php echo $app->getUserState('dname', ''); ?>" id="dname" size="40" value="" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="url"><?php echo JText::_('COM_JBLANCE_URL'); ?> <span class="redfont">*</span>:</label>
          <div class="controls">
            <input type="text" name="url" id="url" value="<?php echo $app->getUserState('url', ''); ?>" class="inputbox hasTooltip required validate-url"  title="<?php //echo JHtml::tooltipText(JText::_('COM_JBLANCE_TT_URL')); ?>">
          </div>
        </div>
        <?php if($work) { ?>
        <div class="controls">
          <label class="control-label" for="worktype"><?php echo JText::_('COM_JBLANCE_WORKTYPE'); ?> <span class="redfont">*</span>:</label>
          <?php  foreach ($work as $value) { ?>
          <?php //$work = $app->getUserState('work', ''); print_r($work);  ?>
          <!--<input type="radio" <?php // echo $app->getUserState('work','')==$value[0]? ' checked="checked"':'';   ?> value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?>-->
          
          <input type="radio" name="work" <?php echo $app->getUserState('work', '') == $value[0] || $value[0] == '76' ? ' checked="checked"' : ''; ?> value="<?php echo $value[0]; ?>">
          <?php echo $value[1]; ?>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="control-group">
          <label class="control-label" for="worktype"><?php echo JText::_('COM_JBLANCE_MAIN_SKILL'); ?> <span class="redfont">*</span>:</label>
          <select name="dev_type" >
            <option value="">What kind of professional are you looking for</option>
            <?php foreach ($devType as $value) { ?>
            <option <?php echo $app->getUserState('dev_type', '') == $value[0] ? "selected" : ''; ?> value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="controls">
          <h4>Country</h4>
          <div class="country_select_wrapper control-group">
            <div class="styled-select">
              <select name="pj_count" id="country_select"  >
                <option value="">--Select country--</option>
                <?php foreach ($countries as $value) { ?>
                <option <?php echo $app->getUserState('pj_count', '') == $value->id ? "selected" : ""; ?> value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
              </select>
              <span style="display:none;" id="loading_loc"><img src="images/loading-loc.gif"/></span></div>
          </div>
          <div class="country_select_wrapper control-group">
            <div class="styled-select">
              <select name="pj_state" id="state_select">
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
          <div class="country_select_wrapper control-group">
            <div class="styled-select">
              <select name="pj_city" id="city_select">
                <?php if (count($city) > 0) {
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
      </div>
      <?php /*?><div class="dev-reg-accwrap">
        <h4><?php echo JText::_('COM_JBLANCE_DEVELOPER_ACCOUNT'); ?></h4>
        <div class="control-group">
          <?php if (isset($_REQUEST['plan_id']) AND ! empty($_REQUEST['plan_id'])) { ?>
          <input type="hidden"  name="plan_id" id="form_<?php echo $_REQUEST['plan_id'] . "checked"; ?>" value="<?php echo $_REQUEST['plan_id']; ?>" >
          <?php
                    } else {

                        $plan_id = $app->getUserState('plan_id');
                        // echo '<pre>'; print_r($plan_id); die;
                        foreach ($plan as $plan) {
                            $planParams = json_decode($plan->params); //project upgrade prices 			
                            $showDescription = $planParams->short_desc . "<br>";
                            ?>
          <ul class="featured-labels">
            <li class="grey-box">
              <table border="0" width="100%" cellpadding="3" cellspacing="3">
                <tbody>
                  <tr>
                    <td><input type="radio"  name="plan_id" <?php if ($plan_id == $plan->id || $plan->id == 13) echo ' checked="checked"'; ?> id="form_<?php echo $planParams->heading; ?>" value="<?php echo $plan->id; ?>">
                      <label for="form_<?php //echo $planParams->heading;   ?>" class="labelCheckbox"></label></td>
                    <td><span class="labels label-plane <?php echo $planParams->heading; ?>"><?php echo $planParams->heading; ?></span></td>
                    <td><b><?php echo $planParams->heading . " "; ?></b><?php echo $planParams->short_desc; ?> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=plans', false); ?>" target="_blank">See more</a></td>
                    <td><?php if ($plan->price == 0) { ?>
                      <span class="price"><?php echo "Free"; ?></span>
                      <?php } else { ?>
                      <span class="price"><?php echo "$ " . $plan->price; ?></span>
                      <?php } ?>
                  </td>
                
                  </tr>
                
                  </tbody>
                
              </table>
            </li>
          </ul>
          <?php
                        }
                    }
                    ?>
          <input type="hidden" name="temp_var" value="1" />
        </div>
      </div><?php */?>
      <div class="dev-reg-common">
        <?php
                    $termid = $config->termArticleId;
                    $link = JRoute::_("index.php?option=com_content&view=article&id=" . $termid . '&tmpl=component');
                    ?>
        <div class="clearfix accept_terms_wrapper">
          <p>
            <input id="terms" type="checkbox" name="terms">
            <?php echo JText::sprintf('COM_JBLANCE_BY_CLICKING_YOU_AGREE', $link); ?></p>
        </div>
        <div class="">
          <input type="submit" value="<?php echo JText::_('COM_JBLANCE_I_ACCEPT_CREATE_MY_ACCOUNT'); ?>" class="signup-brn" />
        </div>
        <input type="hidden" name="option" value="com_jblance" />
        <input type="hidden" name="gateway" value="paypal" />
        <input type="hidden" name="task" value="developer.saveUserNew" />
        <?php echo JHtml::_('form.token'); ?> </div>
    </form>
  </div> </div>

<!--  <div class="post-project-cta-wrap">
    <h2>Need mobile app development services? </h2>
    <button>Post A Project</button>
    <span>in 5 minutes for free </span> </div>
</div>-->
