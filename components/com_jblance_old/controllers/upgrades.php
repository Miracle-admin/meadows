<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	controllers/membership.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class JblanceControllerUpgrades extends JControllerLegacy {

    function __construct() {
        parent :: __construct();
    }

    public function showUpgrades() {
        $user = JFactory::getUser();

        //plan subscribed by user
        $plan = JblanceHelper::whichPlan($user->id);

        $featuredProjectFee = $plan->buyFeePerFeaturedProject;
        $urgentProjectFee = $plan->buyFeePerUrgentProject;
        $privateProjectFee = $plan->buyFeePerPrivateProject;
        $sealedProjectFee = $plan->buyFeePerSealedProject;
        $ndaProjectFee = $plan->buyFeePerNDAProject;
        $assistedProjectFee = $plan->buyFeePerAssistedProject;
        $chargePerProject = $plan->buyChargePerProject;
        $sellFeatured = $plan->sellfeatured;
        $sellUrgent = $plan->sellurgent;
        $sellPrivate = $plan->sellprivate;
        $sellSealed = $plan->sellsealed;
        $sellAssisted = $plan->sellassisted;
        $sellNda = $plan->sellnda;
        $isactiveplan = $plan->isactiveplan;

        $app = JFactory::getApplication();

        //total fund in user account;
        $totalFund = JblanceHelper::getTotalFund($user->id);

        //project id
        $pid = JRequest::getVar('id');

        $row = JTable::getInstance('project', 'Table');

        //project records
        $row->load($pid);

        //project publisher
        $publisherId = $row->publisher_userid;

        //current user
        $userId = $user->id;

        //is approved
        $approved = $row->approved

        //generate the response there is no need of additional load of model and view
        ?>
        <script type="text/javascript">
            //method to get the length of object
            Object.size = function (obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key))
                        size++;
                }
                return size;
            };

            //function to update total ammount 
            function updateTotalAmount(el) {
                var success = jQuery("#up-success");
                var error = jQuery("#up-error");
                var warning = jQuery("#up-warning");
                success.hide();
                error.hide();
                warning.hide();
                var element = el.name;
                var tot = parseFloat($('totalamount').get('value'));
                var fee = 0;

                if (element == 'is_featured')
                    fee = parseFloat('<?php echo $featuredProjectFee; ?>');
                else if (element == 'is_urgent')
                    fee = parseFloat('<?php echo $urgentProjectFee; ?>');
                else if (element == 'is_private')
                    fee = parseFloat('<?php echo $privateProjectFee; ?>');
                else if (element == 'is_assisted')
                    fee = parseFloat('<?php echo $assistedProjectFee; ?>');
                else if (element == 'is_sealed')
                    fee = parseFloat('<?php echo $sealedProjectFee; ?>');
                else if (element == 'is_nda')
                    fee = parseFloat('<?php echo $ndaProjectFee; ?>');

                if ($(element).checked) {
                    tot = parseFloat(tot + fee);

                }
                else {
                    tot = parseFloat(tot - fee);

                }
                $('subtotal').set('html', tot);
                $('totalamount').set('value', tot);
            }


            //function to promote the project
            function upgradeList()
            {
                var subTot = parseInt(jQuery("#totalamount").val());
                var tot = parseInt('<?php echo $totalFund; ?>');
                console.log(subTot);
                // get all the inputs into an array.
                var inputs = jQuery('#upgradesList :input[type="checkbox"]');

                //convert it into associative array
                var values = {};

                inputs.each(function () {
                    if (this.name != '' && this.checked)
                    {
                        values[this.name] = jQuery(this).val();
                    }
                });


                var subLength = Object.size(values);

                //if allready prometed

                if (inputs.length == 0)
                {
                    alert("You have allready promoted your project , to all the available options.");
                }


                //if trying to submit empty values
                if (subLength == 0 && inputs.length != 0)
                {
                    alert("Please select an upgrade option");
                }
                else if (subTot > tot)
                {
                    alert('Your balance is insufficient to promote your project.');
                }
                else
                {
                    values.subtotal = subTot;
                    values.pid =<?php echo $pid; ?>;
                    var serialized = jQuery.param(values);
                    //post the data to update the records
                    jQuery.ajax('index.php?option=com_jblance&task=upgrades.upgradeListing',
                            {
                                data: serialized,
                                method: "POST",
                                success: updated
                            }
                    )
                }
            }
            function updated(data)
            {
                var success = jQuery("#up-success");
                var error = jQuery("#up-error");
                var warning = jQuery("#up-warning");
                var response = parseInt(data);
                if (data == 1)
                {
                    success.show();
                    location.reload();
                }
                if (data == 0)
                {
                    error.show();
                    location.reload();
                }
                if (data == 2)
                {
                    warning.show();
                    location.reload();
                }
            }


        </script>

        <div class="modal-content" style="min-width: 1000px;">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo JText::_('COM_JBLANCE_PROMOTE_YOUR_LISTING'); ?></h4>
            </div>
            <div class="modal-body">
                <fieldset id="upgradesList">
                    <?php
                    if (!$user->guest) {
                        if ($isactiveplan == 'Active') {
                            ?>

                            <ul class="upgrades">
                                <!-- The project once set as 'Featured' should not be able to change again -->
                                <?php if ($sellFeatured): ?>
                                    <li class="project_upgrades">

                                        <div class="pad">
                                            <?php if (!$row->is_featured) : ?>
                                                <input type="checkbox" id="is_featured" name="is_featured" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" /> 
                                                <span class="upgrade featured"></span> 
                                                <p><?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT_DESC'); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($featuredProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade featured"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_FEATURED_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>

                                    </li>
                                <?php endif; ?>
                                <!-- The project once set as 'Urgent' should not be able to change again -->
                                <?php if ($sellUrgent): ?>
                                    <li class="project_upgrades">
                                        <div class="pad">
                                            <?php if (!$row->is_urgent) : ?>
                                                <input type="checkbox" id="is_urgent" name="is_urgent" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" /> 
                                                <span class="upgrade urgent"></span> 
                                                <p><?php echo JText::_('COM_JBLANCE_URGENT_PROJECT_DESC'); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($urgentProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade urgent"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_AN_URGENT_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($sellPrivate): ?>
                                    <!-- The project once set as 'Private' should not be able to change again -->
                                    <li class="project_upgrades">
                                        <div class="pad">
                                            <?php if (!$row->is_private) : ?>
                                                <input type="checkbox" id="is_private" name="is_private" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                                                <span class="upgrade private"></span> 
                                                <p><?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT_DESC'); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($privateProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade private"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($sellSealed): ?>
                                    <!-- The project once set as 'Sealed' should not be able to change again -->
                                    <li class="project_upgrades">
                                        <div class="pad">
                                            <?php if (!($row->is_sealed)) : ?>
                                                <input type="checkbox" id="is_sealed" name="is_sealed" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                                                <span class="upgrade sealed"></span> 
                                                <p><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_DESC'); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($sealedProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade sealed"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_SEALED_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($sellNda): ?>
                                    <!-- The project once set as 'NDA' should not be able to change again -->
                                    <li class="project_upgrades">
                                        <div class="pad">
                                            <?php if (!$row->is_nda) : ?>
                                                <input type="checkbox" id="is_nda" name="is_nda" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                                                <span class="upgrade nda"></span> 
                                                <p><?php echo JText::sprintf('COM_JBLANCE_NDA_PROJECT_DESC', ''); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($ndaProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade nda"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_NDA_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php endif; ?>

                                <!--added assisted-->
                                <?php if ($sellAssisted): ?>
                                    <li class="project_upgrades">
                                        <div class="pad">
                                            <?php if (!$row->is_assisted) : ?>
                                                <input type="checkbox" id="is_assisted" name="is_assisted" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" /> 
                                                <span class="upgrade assisted"></span> 
                                                <p><?php echo JText::_('COM_JBLANCE_ASSISTED_PROJECT_DESC'); ?></p>
                                                <span class="price"><?php echo JblanceHelper::formatCurrency($assistedProjectFee); ?></span>
                                            <?php else : ?>
                                                <span class="upgrade assisted"></span>
                                                <p><?php echo JText::_('COM_JBLANCE_THIS_IS_AN_ASSISTED_PROJECT'); ?></p>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                <?php endif; ?>

                                <!--end-->



                                <!--Private invite hidden The project once set as 'Private Invide' should not be able to change again -->
                                <!--<li class="project_upgrades">
                                        <div class="pad">
                                                <input type="checkbox" id="is_private_invite" name="is_private_invite" value="1" class="project_upgrades" <?php echo ($row->is_private_invite) ? 'checked' : ''; ?> />
                            <span class="upgrade invite"></span> 
                            <p><?php echo JText::_('COM_JBLANCE_PRIVATE_INVITE_PROJECT_DESC'); ?></p>
                                                <div class="clearfix"></div>
                                        </div>
                                </li>-->
                                <li class="project_upgrades">
                                    <div class="pad">
                                        <div class="row-fluid">
                                            <div class="span4">
                                                <?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE') ?> : <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($totalFund); ?></span>
                                            </div>
                                            <div class="span4">
                                                <?php if ($chargePerProject > 0 && $isNew) : ?>
                                                    <?php echo JText::_('COM_JBLANCE_CHARGE_PER_PROJECT'); ?> : <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($chargePerProject); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="span4">
                                                <?php echo JText::_('COM_JBLANCE_TOTAL') ?> : <span class="font16 boldfont"><?php echo $currencysym; ?><span id="subtotal">0.00</span></span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </li>
                            </ul>
                            <?php
                        }else {
                            echo '<div class="not_authenticated">You have not subscribed any plan or your plan expired.</div>';
                        }
                    } else {
                        ?>
                        <div class="not_authenticated">Your session has been expired.Please login first.</div>
                    <?php } ?>
                </fieldset>
                <div class="modal-footer">
                    <div style="display:none;" id="up-success" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Project Successfully Upgraded.
                    </div>
                    <div style="display:none;" id="up-error" class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        You Are Not Authorized To Perform This Action Or Your balance Is Insufficient To Perform This Action.
                    </div>
                    <div style="display:none;" id= "up-warning" class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button>
                        Unable To Process Your Request Please Try Again Later.
                    </div>
                    <button type="button" class="btn-close">Close</button>
                    <button type="button"   onclick="upgradeList();" class="btn btn-primary">Upgrade</button>
                    <input type="hidden" name="totalamount" id="totalamount" value="0.00" />
                </div>
            </div>   
        </div>
        <?php
        $app->close();
    }

//form submits here

    public function upgradeListing() {

        $app = JFactory::getApplication();

        $pid = JRequest::getVar('pid');

        $is_featured = JRequest::getVar('is_featured', '');

        $is_nda = JRequest::getVar('is_nda', '');

        $is_private = JRequest::getVar('is_private', '');

        $is_private_invite = JRequest::getVar('is_private_invite', '');

        $is_assisted = JRequest::getVar('is_assisted', '');

        $is_sealed = JRequest::getVar('is_sealed', '');

        $is_urgent = JRequest::getVar('is_urgent', '');

        $pid = JRequest::getVar('pid');

        $subtotal = JRequest::getVar('subtotal');

        $user = JFactory::getUser();

        $userId = $user->id;

        $totalFund = JblanceHelper::getTotalFund($user->id);

        $row = JTable::getInstance('project', 'Table');

        $project = $row->load($pid);
        // echo '<pre>'; print_r($row); die;
        $publisher_userid = $row->publisher_userid;

//if the user is owner of project
        if ($userId == $publisher_userid && $subtotal <= $totalFund) {
            $comma_plan = '';
            if (!empty($is_featured)) {
                $row->is_featured = $is_featured;
                $comma_plan .= 'Featured, ';
            }
            if (!empty($is_nda)) {
                $row->is_nda = $is_nda;
                $comma_plan .= 'NDA, ';
            }
            if (!empty($is_private)) {
                $row->is_private = $is_private;
                $comma_plan .= 'Private, ';
            }
            if (!empty($is_private_invite)) {
                $row->is_private_invite = $is_private_invite;
                $comma_plan .= 'Private Invite, ';
            }
            if (!empty($is_assisted)) {
                $row->is_assisted = $is_assisted;
                $comma_plan .= 'Assisted, ';
            }
            if (!empty($is_sealed)) {
                $row->is_sealed = $is_sealed;
                $comma_plan .= 'Sealed, ';
            }
            if (!empty($is_urgent)) {
                $row->is_urgent = $is_urgent;
                $comma_plan .= 'Urgent, ';
            }
            $comma_plan = rtrim($comma_plan, ', ');
            if ($row->store()) {


                $api_jb = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jblance' . DS . 'helper.php';
                include_once($api_jb);
                $credits = JblanceHelper::get('helper.credits');
                $project_link = '<a href =' . JRoute::_("index.php?option=com_jblance&view=project&layout=detailproject&id=" . $pid) . '>' . $row->project_title . '<a>';
                $datarefference = "Purchase: (" . $comma_plan . ") Spent: $" . $subtotal . " Upgraded project " . $project_link;
                $credits::UpdateCredits(array("", "", "", "", "$datarefference", -$subtotal, "", "", "Payment upgraded successfully..!"));
                $db = JFactory::getDbo();
                $trans = new stdClass();
                $trans->date_trans = date('Y-m-d H:i:s');
                $trans->transaction = "Buy Project Upgrades(Deposited Funds)";
                $trans->fund_plus = 0;
                $trans->fund_minus = $subtotal;
                $trans->user_id = $userId;
                //email
//                if ($subtotal > 0) {
//                    $deduct = $db->insertObject('#__jblance_transaction', $trans);
//                    $jbmail = JblanceHelper::get('helper.email');
//                    $jbmail->sendProjectupgradeNotificationAdmin($pid, $userId);
//                }
                echo 1;
            } else {
                echo 2;
            }
        } else {
            echo 0;
        }

        $app->close();
    }

    function showPayments() {
        $app = JFactory::getApplication();

        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        if ($user->id == 0) {
            echo'<b style="color:red;">Your login session has expired.Click <a href="' . JRoute::_(JUri::root() . 'index.php?option=com_users&view=login') . '">here</a> to login.</b>';
            $app->close();
        }

        $id = JRequest::getInt('id', 0);
        $po = JRequest::getInt('po', 0);
        $query = "SELECT * FROM #__jblance_escrow WHERE from_id='" . $po . "' AND to_id='" . $user->id . "' AND project_id='" . $id . "' AND type = 'COM_JBLANCE_PROJECT'";

        $db->setQuery($query);
        $res = $db->loadObjectList();

        $html = "";
        if ($res) {


            $from_id = $res[0]->from_id;
            $poavatar = JblanceHelper::getThumbnail($from_id);
            $userHelper = JblanceHelper::get('helper.user');
            $profile = $userHelper->getUser($from_id);
            $poName = $profile->jbname;
            $html.='<div class="pm_info">
       <div class="from_amt">' . $poavatar . '<span>' . $profile->jbname . '</span></div><hr></hr>';
            foreach ($res as $val) {
                $ammount = $val->amount;
                $status = $val->status;
                if ($status == 'COM_JBLANCE_ACCEPTED') {
                    $status = '<span class="accepted">Accepted</span>';
                } elseif ($status == 'COM_JBLANCE_RELEASED') {
                    $status = '<span class="released">Released</span>';
                }

                $html.= '<div class="pm_infam"><img src="images/dollar.png"/> ' . $ammount . $status . '</div><div class="separator"><hr></hr></div>';
            }

            $html.='</div>';
        } else {
            $html = '<div class="no_rev"><b style="color:red;">Payments not found.</b></div>';
        }

        echo $html;
        $app->close();
    }

    function showReviews() {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $id = JRequest::getInt('id', 0);
        if ($user->id == 0) {
            echo'<b style="color:red;">Your login session has expired.Click <a href="' . JRoute::_(JUri::root() . 'index.php?option=com_users&view=login') . '">here</a> to login.</b>';
            $app->close();
        }
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_rating WHERE project_id='" . $id . "' AND target='" . $user->id . "' AND rate_type='COM_JBLANCE_FREELANCER'";
        $db->setQuery($query);
        $rowRating = $db->loadObject();
        $actor = $rowRating->actor;

        $avatar = JblanceHelper::getThumbnail($actor);
        $html = '';
        if ($actor != '') {
            $userHelper = JblanceHelper::get('helper.user');
            $profile = $userHelper->getUser($actor);



            $html.='<div class="client_info"><div class="clname">' . $profile->jbname . '</div><div class="client_avatar">' . $avatar . '</div></div>';

            $rating = JblanceHelper::getUserRateProject($user->id, $id);
            $rating = JblanceHelper::getRatingHTML($rating);
            $html.= '<div class="pj_reviews">';
            $html.= '<div class="avg_rating"><b>Overall Rating: </b>' . $rating . '</div>';
            $html.= '<div class="quality_of_work"><b>Quality of Work: </b>' . JblanceHelper::getRatingHTML($rowRating->quality_clarity) . '</div>';
            $html.= '<div class="communication"><b>Communication: </b>' . JblanceHelper::getRatingHTML($rowRating->communicate) . '</div>';
            $html.= '<div class="expertise"><b>Expertise: </b>' . JblanceHelper::getRatingHTML($rowRating->expertise_payment) . '</div>';
            $html.= '<div class="professionalism"><b>Professionalism: </b>' . JblanceHelper::getRatingHTML($rowRating->professional) . '</div>';
            $html.= '<div class="hireagain"><b>Would Hire Again: </b>' . JblanceHelper::getRatingHTML($rowRating->hire_work_again) . '</div>';
            $html.= '</div>';
        } else {
            $html.='<div class="no_rev"><b style="color:red;">No Reviews Found</b></div>';
        }
        echo $html;
        $app->close();
    }

}
