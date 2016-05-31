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
$link_subscr_hist = JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory');
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
            ?>

            <div class="jbbox-warning"> <?php echo JText::sprintf('COM_JBLANCE_USER_SUBSCRIPTION_EXPIRED', $link_buy_subscr); ?> </div>
        <?php } elseif ($planStatus == '2') {
            ?>
            <div class="jbbox-info"> <?php echo JText::sprintf('COM_JBLANCE_USER_DONT_HAVE_ACTIVE_PLAN', $link_subscr_hist); ?> </div>
        <?php
        }
    }
}
?>
<!-- removing title<div class="jbl_h3title"><?php echo JText::_($this->userInfo->name) . ' ' . JText::_('COM_JBLANCE_DASHBOARD'); ?></div><div style="clear:both;"></div>--> 
<!--edit inside this-->
<?php 

//echo '<pre>'; print_r($user); die;
if(in_array("13", $user->groups) ){
?>
<div class="post-project-d-dashboard"><?php echo $this->current_geek_status; ?></div>
<?php } ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 developer-lf-wrap">
            <h4>Client </h4>
            <ul class="nav my-projects-dev-c nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">My Projects</a></li>
                <!--<li><a data-toggle="tab" href="#menu1">My Pending Task</a></li>-->
            </ul>
            <div class="box-border-ct">
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="devl-my-pro-wrap">
                            <div class="box_content_wrap">
                                <?php
                                foreach ($prjList as $prj) {
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
                                            <li> <a id="<?php echo "upgrade-" . $prj->id; ?>"   href="#" data-target="#myModal">Upgrade my Project</a> </li>
                                            <li><a class="edit-wrap" href="<?php echo JRoute::_(JUri::root() . 'index.php?option=com_jblance&view=project&layout=editprojectcustom&pid=' . $prj->id . '&Itemid=308'); ?>">Edit My Project</a></li>
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
<?php } ?>
                            </div>
<?php /* ?> <a class="a-btn-green" href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject&Itemid=308'); ?>">View All Project</a><?php */ ?>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <div class="box_wrapper row" id="my_task_wrapper">
                            <div class="col-md-12 db_clnt_title">
                                <h3 ><?php echo JText::_('COM_JBLANCE_TASKS_PENDING'); ?></h3>
                                <ul class="clearfix">
                                    <?php
                                    if (!empty($this->pendings)) {
                                        foreach ($this->pendings as $pending) {
                                            ?>
                                            <li><i class="icon-warning-sign"></i> <?php echo $pending; ?></li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="alert alert-info"><?php echo JText::_('COM_JBLANCE_NO_TASK_PENDING_YOUR_ACTION'); ?></div>
    <?php
}
?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php /* ?><div id="news_feed_wrapper">
              <div class="heading">
              <h4><?php echo JText::_('COM_JBLANCE_NEWS_FEED'); ?></h4>
              </div>
              <div class="box-border-ct ">
              <div class="box_content_wrap">
              <?php if($showFeedsDashboard) : ?>
              <!--<h3><?php // echo JText::_('COM_JBLANCE_NEWS_FEED'); ?></h3>-->
              <?php
              $n=count($this->feeds);
              if($n == 0){ ?>
              <div class="alert alert-info"><?php echo JText::_('COM_JBLANCE_NO_NEWSFEEDS_OR_POSTS'); ?></div>
              <?php
              }
              for($i=0, $n=count($this->feeds); $i < $n; $i++) {
              $feed = $this->feeds[$i]; ?>
              <div class="media jb-borderbtm-dot" id="jbl_feed_item_<?php echo $feed->id; ?>">
              <div class="row">
              <!--<div class="col-md-1 feed_user_img">
              <?php  // echo $feed->logo; ?>
              </div>-->
              <div class=" col-md-8 feed-title"> <?php echo $feed->title; ?> </div>
              <div class="col-md-4 action-btn"><i class="icon-calendar"></i> <?php echo $feed->daysago; ?> <span id="feed_hide_<?php echo $feed->id; ?>" class="help-inline">
              <?php if($feed->isMine) : ?>
              <a class="btn-link" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'remove');" href="javascript:void(0);"> <i class="icon-remove"></i> <?php echo JText::_('COM_JBLANCE_REMOVE'); ?> </a>
              <?php endif; ?>
              <a class="btn-link" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'hide');" href="javascript:void(0);"> <i class="icon-eye-close"></i> <?php echo JText::_('COM_JBLANCE_HIDE'); ?> </a> </span> </div>
              </div>
              </div>
              <?php
              }
              ?>
              <?php endif; ?>
              </div>
              </div>
              </div><?php */ ?>
        </div>
        <div class="col-md-4 developer-rt-wrap">

            <div class="box-border-ct"><?php echo $this->profile_completion_status; ?></div>
   <!--            <div class="box-border-ct"> <?php //echo $this->upload_new_source_code;     ?></div>-->
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


<?php /* ?>     <?php $modules = JModuleHelper::getModules("buysourcecodetemp"); $document = JFactory::getDocument(); $renderer = $document->loadRenderer('module'); $attribs = array(); $attribs['style'] = 'xhtml'; foreach($modules as $mod) { echo JModuleHelper::renderModule($mod, $attribs); } <?php */ ?>



            <!--// post project-->

            <?php /* ?><div class="new_top_games_wrapper db_clnt_title" role="tabpanel">
              <h3> Featured App & Templates</h3>
              <!-- Nav tabs -->
              <ul class="" id="featuregames" role="tablist">
              <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Top Selling</a></li>
              <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">New Releases</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
              <?php $modules = JModuleHelper::getModules("top_selling_product"); $document = JFactory::getDocument(); $renderer = $document->loadRenderer('module'); $attribs = array(); $attribs['style'] = 'xhtml'; foreach($modules as $mod) { echo JModuleHelper::renderModule($mod, $attribs); }
              ?>
              <!--Top Selling Apps-->
              </div>
              <div role="tabpanel" class="tab-pane" id="profile">
              <?php
              $modules = JModuleHelper::getModules("new_releases_apps"); $document = JFactory::getDocument(); $renderer = $document->loadRenderer('module'); $attribs = array(); $attribs['style'] = 'xhtml'; foreach($modules as $mod) { echo JModuleHelper::renderModule($mod, $attribs); }
              ?>
              <!--New Releases Apps-->
              </div>
              </div>
              </div><?php */ ?>
            <!--//featured & top selling products-->

<?php // $modules = JModuleHelper::getModules("post_new_project"); $document = JFactory::getDocument(); $renderer = $document->loadRenderer('module'); $attribs = array(); $attribs['style'] = 'xhtml'; foreach($modules as $mod) { echo JModuleHelper::renderModule($mod, $attribs); }  ?>
            <!--//start new project--> 

        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="  dev-d-latest-apps container mb-90">
            <div class="dev-d-latest-apps-hdr">
                <h4> Buy  Source Code</h4>
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
<script>
    jQuery('#featuregames li a').click(function (e) {
        e.preventDefault()
        jQuery(this).tab('show')
    })
</script>
<style type="text/css">
    #bpopup
    {
        width:50% !important;
    }
    #bpopup .close{
        display:none;
    }
</style>
