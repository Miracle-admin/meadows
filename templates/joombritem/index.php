<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.beez3
 * 
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

JLoader::import('joomla.filesystem.file');

// Check modules
$showRightColumn = ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
$showbottom = ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft = ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));

if ($showRightColumn == 0 and $showleft == 0) {
    $showno = 0;
}

JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework');
JHtml::_('bootstrap.loadCss', true, $this->direction);


// Get params
$color = $this->params->get('templatecolor');
$logo = $this->params->get('logo');
$navposition = $this->params->get('navposition');
$headerImage = $this->params->get('headerImage');
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params;
$config = JFactory::getConfig();
$bootstrap = explode(',', $templateparams->get('bootstrap'));
$jinput = JFactory::getApplication()->input;
$option = $jinput->get('option', '', 'cmd');



if (in_array($option, $bootstrap)) {
    // Load optional rtl Bootstrap css and Bootstrap bugfixes
    JHtml::_('bootstrap.loadCss', true, $this->direction);
}

$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
$doc->addStyleSheet(JURI::base() . 'templates/home/css/style.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet(JURI::base() . 'templates/home/css/buttons.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet(JURI::base() . 'templates/home/css/custom.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet(JURI::base() . 'templates/home/css/font-awesome.css', $type = 'text/css', $media = 'screen,projection');



/* JHtml::_('bootstrap.framework'); */
$logouttok = JSession::getFormToken();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
    <?php
    $itemid = JRequest::getVar('Itemid');
    $menu = &JSite::getMenu();
    $active = $menu->getItem($itemid);
    $params = $menu->getParams($active->id);
    $pageclass = $params->get('pageclass_sfx');
    ?>
    <head>
        <?php //require __DIR__ . '/jsstrings.php'; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
        <meta name="HandheldFriendly" content="true" />
        <meta name="apple-mobile-web-app-capable" content="YES" />
        <jdoc:include type="head" />
    </head>
    <body class="existingclass <?php echo $pageclass ? htmlspecialchars($pageclass) : 'default'; ?>">
        <script>
            jQuery(document).ready(function () {
                //jQuery('header nav').meanmenu();
                var htmlCont = jQuery(".jbMenuIcon").html();
                jQuery('div.notification_wrap').append(htmlCont);
            });
        </script>
        <header class="container-fluid">
            <div class="row">
                <div class="container header-top-prt">
                    <div class="row">
                      <div class="col-md-3"> <a href="index.php" class="logo"><img src="<?php echo $this->baseurl; ?>/images/logo.png"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle')); ?>" /></a><!--<a href="index.php"><span class="logo-txt">App Meadows</span></a>--> </div>
                        <!--//end logo-->
                        <div class="col-md-5 search-bar-top-hdr">
                            <jdoc:include type="modules" name="search-bar-header" />
                            <i class="fa fa-search" aria-hidden="true"></i>

                        </div>
                        <div class="col-md-4">
                            <div class="login_before_menu">
                                <?php
                                $db = JFactory::getDbo();
                                $user = JFactory::getUser();
                                if (!empty($user->id)) {
                                    $user_id = $user->id;
                                    $query = "select u.name,ju.thumb from #__users AS u INNER JOIN #__jblance_user AS ju ON ju.user_id=u.id WHERE ju.user_id=$user_id";
                                    $db->setQuery($query);
                                    $result = $db->loadRow();
                                    ?>
                                    <div class="nav-wrapper-right">
                                        <?php
                                        if ($user_id) {
                                            $link_home = JRoute::_('index.php');
                                            $dashboard = '';
                                            if (in_array("13", $user->groups)) {
                                                $dashboard = 'index.php?option=com_jblance&view=user&layout=dashboarddeveloper&Itemid=368';
                                            } elseif (in_array("11", $user->groups)) {
                                                $dashboard = 'index.php?option=com_jblance&view=user&layout=dashboard&Itemid=394';
                                            }
                                            ?>
                                            <div class="nav-wrapper-right" >
                                                <div class="user_control">
                                                    <img height="40" width="40" src="<?php echo JURI::root() ?>images/jblance/<?php echo ($result[1]=='')?'avatar.jpg':$result[1]; ?>"/>
                                                    <a href="<?php echo JRoute::_($dashboard); ?>"><?php echo $user->username; ?></a>
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                      <!--<li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=149'); ?>">View Profile</a></li>-->
                                                        <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=363&username=' . $user->username); ?>">View Profile</a></li>
                                                        <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=150'); ?>">Edit Profile</a></li>
                                                        <li><a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . base64_encode($link_home)); ?>">Logout</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <jdoc:include type="modules" name="login-menu" />
                                <?php }
                                ?>
                            </div>
                        </div>
                        <!--//end top header--> 
                    </div>
                </div>
                <div class="nav-wrapper">
                    <nav class="container">
                        <div class="row">
                            <jdoc:include type="modules" name="information-menu" />
                        </div>
                    </nav>
                </div>
                <!--//end navigation top--> 
            </div>
        </header>
        <?php /* ?><header id="db_menu">
          <div class="nav-wrapper-outer container">
          <div class="col-md-2 col-sm-2 col-xs-12 logo-wrap">
          <a href="index.php" class="logo"><img src="<?php echo $this->baseurl; ?>/images/logo.png"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle')); ?>" /></a></div>
          <nav class="col-md-8 col-sm-8 ">
          <div class="nav-wrapper">
          <jdoc:include type="modules" name="information-menu" />
          </div>
          </nav>
          <div class="col-md-2 col-sm-6 col-xs-12 pull-right">
          <div class="login_before_menu">
          <?php
          $db = JFactory::getDbo();
          $user = JFactory::getUser();
          if (!empty($user->id)) {
          $user_id = $user->id;
          $query = "select u.name,ju.thumb from #__users AS u INNER JOIN #__jblance_user AS ju ON ju.user_id=u.id WHERE ju.user_id=$user_id";
          $db->setQuery($query);
          $result = $db->loadRow();
          ?>
          <div class="nav-wrapper-right">
          <?php
          if (!empty($result[1])) {
          $link_home = JRoute::_('index.php');
          ?>
          <div class="nav-wrapper-right" >
          <div class="user_control"><img height="40" width="40" src="<?php echo JURI::root() ?>images/jblance/<?php echo $result[1]; ?>"/> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=149'); ?>"><?php echo $result[0]; ?></a>
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
          <!--<li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=149'); ?>">View Profile</a></li>-->
          <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=363&username=' . $user->username); ?>">View Profile</a></li>
          <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=150'); ?>">Edit Profile</a></li>
          <li><a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . base64_encode($link_home)); ?>">Logout </a></li>
          </ul>
          </div>
          </div>
          <?php
          }
          ?>
          </div>
          <?php
          } else {
          ?>
          <jdoc:include type="modules" name="login-menu" />
          <?php }
          ?>
          </div>

          <!-- /btn-group -->
          </div>
          </div>

          </header><?php */ ?>
        <div class="center">
            <?php if (isset($user->emailvalid) AND ! empty($user->emailvalid)) { ?>
                <jdoc:include type="modules" name="email-valid" />
            <?php } ?>
        </div>

        <!--end top banner-->
        <?php if (JRequest::getVar("option") != "com_search") { ?>
            <jdoc:include type="modules" name="banner-top-common"  />
        <?php } ?>
        <!--//common banner-->

        <?php /* ?><header>
          <div class="nav-wrapper-outer">
          <div class="col-md-2 logo-wrap"> <a href="<?php echo JURI::root(); ?>"><img src="images/logo2.png"/></a></div>
          <div class="col-md-8">
          <jdoc:include type="modules" name="joombri-top-menu" />
          </div>
          <?php
          $db=JFactory::getDbo();
          $user	= JFactory::getUser();
          if(!empty($user->id))
          {
          $user_id=$user->id;
          $query="select u.name,ju.thumb from #__users AS u INNER JOIN #__jblance_user AS ju ON ju.user_id=u.id WHERE ju.user_id=$user_id";
          $db->setQuery($query);
          $result=$db->loadRow();
          if(!empty($result[0]))
          {
          $link_home = JRoute::_('index.php');
          ?>
          <div class="col-md-2">
          <div class="nav-wrapper-right"> <img height="44" width="44" src="<?php echo JURI::root() ?>images/jblance/<?php echo $result[1];  ?>"/> <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=117');?>"><?php echo $result[0];  ?></a>
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
          <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=117');?>">View Profile</a></li>
          <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=117');?>">Edit Profile</a></li>
          <li><a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode($link_home)); ?>">Logout</a></li>
          </ul>
          </div>

          </div>
          <?php
          }

          }
          ?>

          </div>
          </header><?php */ ?>
        <!--end header-->

        <div class="  project-wrapper-outer container" id="meadoes-content-wrap">
            <div class="row">
                <jdoc:include type="message" />
                <jdoc:include type="component" />
            </div>
            <!--Home page modules-->
            <?php if ($this->countModules('position-15')) : ?>
                <jdoc:include type="modules" name="position-15" />
            <?php endif; ?>
        </div>
        <!--Home page modules END--> 
        <!--//end content-->

        <?php if ($this->countModules('user1')) : ?>
            <div class="user1">
                <jdoc:include type="modules" name="user1" style="rounded" />
            </div>
        <?php endif; ?>
        <div class="container-fluid" id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <jdoc:include type="modules" name="about-appmeadows"  style="xhtml" />
                        <!--//end about app meadows-->

                        <jdoc:include type="modules" name="footer-Menu"  style="xhtml" />
                        <!--//end footer menu-->

                        <div class="copyright">&copy; 2015-<?php echo date("Y") ?> AppMeadows.com </div>
                        <!--//end copyright--> 

                    </div>
                    <div class="col-md-5 pull-right" id="meadows_newsletter">
                        <jdoc:include type="modules" name="meadows-newsletter"  style="xhtml" />
                        <!--//end newsletter-->

                        <div class="social_like">
                            <h3>Follow Us</h3>
                            <div class="facebook_like">
                                <div class="fb-like" data-href="https://www.facebook.com/AppMeadows" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                            </div>
                            <div class="tweet_follow"><a href="https://twitter.com/AppMeadows" class="twitter-follow-button" data-show-count="false">Follow @AppMeadows</a> 
                                <script>!function (d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = p + '://platform.twitter.com/widgets.js';
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, 'script', 'twitter-wjs');</script></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--//end footer-->

        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3&appId=219147791442640";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>


        <script>
            jQuery(window).scroll(function () {
                var top = jQuery(window).scrollTop();
                if (top > 120) // height of float header
                    jQuery('.maxiroundedcenter').addClass('stick');
                else
                    jQuery('.maxiroundedcenter').removeClass('stick');
            })
        </script> 

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

    </body>
</html>
