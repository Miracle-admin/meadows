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
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework', true);
$doc = JFactory::getDocument();
$doc->addScript("components/com_jblance/js/utility.js");
$app = JFactory::getApplication();
$model = $this->getModel();
$user = JFactory::getUser();
$config = JblanceHelper::getConfig();
$showFeedsDashboard = $config->showFeedsDashboard;
$enableEscrowPayment = $config->enableEscrowPayment;
$enableWithdrawFund = $config->enableWithdrawFund;
JHtml::_('jquery.framework');
JHtml::script(Juri::base() . '/media/system/js/bpopup.js');
JText::script('COM_JBLANCE_CLOSE');


$link_edit_profile = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
$link_portfolio = JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio');
$link_messages = JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
$link_post_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject');
$link_list_project = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
$link_search_proj = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
$link_my_project = JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject');
$link_my_bid = JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid');
$link_my_services = JRoute::_('index.php?option=com_jblance&view=service&layout=myservice');
$link_service_bght = JRoute::_('index.php?option=com_jblance&view=service&layout=servicebought');
$link_deposit = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund');
$link_withdraw = JRoute::_('index.php?option=com_jblance&view=membership&layout=withdrawfund');
$link_escrow = JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow');
$link_transaction = JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction');
$link_managepay = JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay');
$link_subscr_hist = JRoute::_('index.php?option=com_alphauserpoints&view=managepayments');
$link_buy_subscr = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd');
//JHTML::_('behavior.modal'); 
JblanceHelper::setJoomBriToken();

JLoader::import('joomla.application.component.model');
JLoader::import('project', JPATH_SITE . DS . 'components' . DS . 'com_jblance' . DS . 'models');
$projects_model = JModelLegacy::getInstance('project', 'JblanceModel');

JRequest::setVar('limit', 2);
$plist = $projects_model->getShowMyProject();
$prjList = $plist[0];


if (!JBLANCE_FREE_MODE) {
    if (!$user->guest) {
        $planStatus = JblanceHelper::planStatus($user->id);


        if ($planStatus == '1') {
            $planS = JblanceHelper::getBtPlan();
            ?>
            <div class="jbbox-warning"> Your subscription has been <b><?php echo $planS['status']; ?></b> , <a href="<?php echo $link_subscr_hist; ?>">click here</a> to manage your payments. </div>
        <?php } elseif ($planStatus == '2') {
            ?>
            <div class="jbbox-info"> You do not have an active subscription , <a href="<?php echo $link_subscr_hist; ?>">click here</a> to manage your payments.  </div>
            <?php
        }
    }
}
?>
<!-- removing title<div class="jbl_h3title"><?php echo JText::_($this->userInfo->name) . ' ' . JText::_('COM_JBLANCE_DASHBOARD'); ?></div><div style="clear:both;"></div>--> 
<!--edit inside this-->
<div class="post-project-d-dashboard"><?php echo $this->current_geek_status; ?></div>
<!--//end post project-->
<div class="container developer-lf-wrap">
    <div class="row">
        <div class="col-md-8">
            <div class="developer-analytics-wrap">
                <h4>developer</h4>
                <div class="developer-analytics-inner"> <?php echo $this->developer_analytics; ?> </div>
                <div class="developer-analytics-wrap">
                    <!--// end developer analytics--> 
                </div>
            </div>

            <div class="dev-my-pro-wrap">            
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#my-projects">My Projects</a></li>
                    <li><a data-toggle="tab" href="#marketplace">Marketplace</a></li>
                </ul>

                <div class="tab-content box-border-ct">
                    <div id="my-projects" class="tab-pane fade in active">
                        <div class="devl-my-pro-wrap">
                            <div class="box_content_wrap">
                                <?php
                                if ($this->my_rows) {
                                    foreach ($this->my_rows as $prj) {
                                        $featured = $prj->is_featured;
                                        $urgent = $prj->is_urgent;
                                        $assisted = $prj->is_assisted;
                                        $private = $prj->is_private;
                                        $sealed = $prj->is_sealed;
                                        $nda = $prj->is_nda;
                                        ?>
                                        <div class="db_pro_title">
                                            <div class=""><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&pid=' . $prj->id . '&Itemid=308') ?>" class="project_title"> <?php echo $prj->project_title; ?></a></div>
                                            <ul class="promotions">
                                                <?php if ($featured) { ?>
                                                    <li data-promotion="featured">Featured</li>
                                                <?php } if ($private) { ?>
                                                    <li data-promotion="private">Private</li>
                                                <?php } if ($urgent) { ?>
                                                    <li data-promotion="urgent">Urgent</li>
                                                <?php } if ($sealed) { ?>
                                                    <li data-promotion="sealed">Sealed</li>
                                                <?php } if ($nda) { ?>
                                                    <li data-promotion="nda">NDA</li>
                                                <?php } if ($assisted) { ?>
                                                    <li data-promotion="Assisted">Assisted</li>
                                                <?php } ?>
                                            </ul>
                                            <div class="pro_desp_txt"><?php echo substr($prj->description, 0, 200) . '....'; ?></div>





                                            <ul class="bth-list-project">
                                                <li>  <a id="<?php echo "upgrade-" . $prj->id; ?>"   href="#" data-target="#myModal">Upgrade my Project</a> </li>
                                                <li><a class="edit-wrap" href="<?php echo JRoute::_(JUri::root() . 'index.php?option=com_jblance&view=project&layout=editprojectcustom&pid=' . $prj->id); ?>">Edit My Project</a></li>
                                                <li><a class="edit-wrap" href="<?php echo JRoute::_(JUri::root() . 'index.php?option=com_jblance&task=project.projectdashboard&pid=' . $prj->id); ?>">Project Dashboard</a></li>
                                            </ul>
                                            <div id="bpopup" > 
                                                <!--modal appear here--> 
                                            </div>
                                            <script type="text/javascript">
                                                jQuery('<?php echo "#upgrade-" . $prj->id; ?>').on('click', function () {
                                                    jQuery("#bpopup").bPopup({
                                                        content: 'ajax', //'ajax', 'iframe' or 'image'
                                                        closeClass: 'btn-close',
                                                        loadUrl: '<?php echo JUri::base() . "index.php?option=com_jblance&task=upgrades.showUpgrades&id=" . $prj->id; ?>'
                                                    })
                                                });

                                            </script> 
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<b> No project(s) found...! </b>';
                                }
                                ?>

                            </div></div>
                    </div>
                    <div id="marketplace" class="tab-pane fade">
                        <div class="devl-my-pro-wrap">
                            <div class="box_content_wrap">
                                <?php
                                if ($this->list_rows) {
                                    foreach ($this->list_rows as $prj) {
                                        ?>
                                        <div class="db_pro_title">
                                            <div class="">
                                                <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&pid=' . $prj->id . '&Itemid=308') ?>" class="project_title"> 
                                                    <?php echo $prj->project_title; ?></a></div>

                                            <div class="pro_desp_txt"><?php echo substr($prj->description, 0, 250) . '....'; ?></div>
                                            <div class="pro_desp_txt">
                                                <span><?php echo JText::_($prj->project_type); ?>/</span>
                                                <span><?php echo JblanceHelper::formatProjectDuration($prj->create_date, "days", $to, $toType, $less_great); ?></span>
                                                <span><?php echo JblanceHelper::getLocationNames($prj->id_location); ?></span>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<b> No project(s) found </b>';
                                }
                                ?>
                                <a class="abtn" target="_blank" id="selected_option" href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject&Itemid=197'); ?>">
                                    See All
                                </a>
                            </div>
                        </div>
                    </div>    





                </div>















            </div>
        </div>
        <div class="col-md-4  developer-rt-wrap">
            <div class="box-border-ct"><?php echo $this->profile_completion_status; ?></div>
<!--            <div class="box-border-ct"> <?php //echo $this->upload_new_source_code;    ?></div>-->
            <div class="box-border-ct">
                <div class="profile-complete-wrap">

                    <div class="scnew">
                        <h4>Upload new source code</h4>
                        <p>Share new source code on our platform and earn more money</p>
                        <select onchange="geturl(jQuery(this).val())" id="select_project_post">
                            <option value="1">Post new project</option>
                            <option value="2">Upload source code</option>
                        </select> 
                        <p>
                            <a target="_blank" id="selected_option" href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=editprojectcustom'); ?>">
                                <button type="submit">Next</button>
                            </a>
                        </p>

                    </div>
                    <script>
                        function geturl(id) {
                            var url = '';
                            if (id == 1) {
                                url = "<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=editprojectcustom'); ?>";
                            } else if (id == 2) {
                                url = "<?php echo JRoute::_('index.php?option=com_vmvendor&view=addproduct'); ?>";

                            }
                            jQuery("#selected_option").attr('href', url);
                        }
                    </script>
                </div>
            </div>

            <?php // echo $this->buy_source_code; ?>
            <?php // echo $this->create_a_new_auction;  ?>

        </div>
        <div class="dev-d-latest-project"><?php // echo $this->mod_jblance_devdashboard_latest_projects;     ?></div>
    </div>

    <div class="  dev-d-latest-apps">

        <div class="dev-d-latest-apps-hdr"> <h4> Buy  Source Code</h4>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusatium doloremque laudantium</p>
        </div>
        <?php //echo $this->create_a_new_auction; ?>

        <div class="dev-d-latest-apps-wrap"> 
            <?php
            $modules = JModuleHelper::getModules("top_selling_product");
            $document = JFactory::getDocument();
            $renderer = $document->loadRenderer('module');
            $attribs = array();
            $attribs['style'] = 'xhtml';
            foreach ($modules as $mod) {
                echo JModuleHelper::renderModule($mod, $attribs);
            }
            ?>
        </div>        
        <div class="text-center viw-all-gmas"> <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=virtuemart&productsublayout=0&Itemid=199'); ?>">View all apps</a></div>

    </div>
</div>
</div>
<!--//end comman container-->
</div>
<script type="text/javascript">
    jQuery(function () {
        var bar = jQuery("#dev_comp_progress .progress-bar ");
        var Width = bar.attr("aria-valuenow");
        bar.animate({"width": Width + "%"}, {duration: 100});

    })
</script>
<style>
    #container-profileviews,#container-profileviewsm,#container-profileviewap {
        background-color: #fff;
        border-radius: 10px;

        color: #111;
        display: none;
        min-width: 600px;
        padding: 25px;
    }
</style>