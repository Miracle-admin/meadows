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
$showbottom      = ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft        = ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));

if ($showRightColumn == 0 and $showleft == 0)
{
	$showno = 0;
}

JHtml::_('behavior.framework', true);

// Get params
$color          = $this->params->get('templatecolor');
$logo           = $this->params->get('logo');
$navposition    = $this->params->get('navposition');
$headerImage    = $this->params->get('headerImage');
$doc            = JFactory::getDocument();
$app            = JFactory::getApplication();
$templateparams	= $app->getTemplate(true)->params;
$config         = JFactory::getConfig();
$bootstrap      = explode(',', $templateparams->get('bootstrap'));
$jinput         = JFactory::getApplication()->input;
$option         = $jinput->get('option', '', 'cmd');

if (in_array($option, $bootstrap))
{
	// Load optional rtl Bootstrap css and Bootstrap bugfixes
	JHtml::_('bootstrap.loadCss', true, $this->direction);
}

$doc->addStyleSheet($this->baseurl . '/templates/system/css/system.css');
/*$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/position.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/layout.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/print.css', $type = 'text/css', $media = 'print');*/
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/bootstrap.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/style.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/font-awesome.css', $type = 'text/css', $media = 'screen,projection');

$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/bootstrap.js', 'text/javascript');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/jquery.meanmenu.js', 'text/javascript');
/*JHtml::_('bootstrap.framework');*/
$logouttok=JSession::getFormToken();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<?php //require __DIR__ . '/jsstrings.php';?>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
		<meta name="HandheldFriendly" content="true" />
		<meta name="apple-mobile-web-app-capable" content="YES" />
        
		<jdoc:include type="head" />
	</head>
	<body>
    <script>
	jQuery(document).ready(function () {
	    jQuery('header nav').meanmenu();
	});
	</script>
    <header>
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
                   <!--<li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=117');?>">View Profile</a></li>-->
				  <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&username='.$user->username);?>">View Profile</a></li>
                  <li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=117');?>">Edit Profile</a></li>
                  <li><a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode($link_home)); ?>">Logout How_Work</a></li>
                </ul>
                   </div>
                  
                </div>
                <?php
				}
				
			}
			?>
    
  </div>
    </header>
	<!--end header-->
	<div class="center">
		<?php 
		if(isset($user->emailvalid) AND !empty($user->emailvalid)){ ?>	
			<jdoc:include type="modules" name="email-valid" />	
		<?php }	?>
	</div>
     <?php if ($this->countModules( 'become_banner' )) : ?>
    	<jdoc:include type="modules" name="become_banner" />
	<?php endif; ?>
    
   <div class="container how-it-work_wrapper pad">
  <div class="row">
    <?php if ($this->countModules( 'how_it_works' )) : ?>
    	<jdoc:include type="modules" name="how_it_works" />
	<?php endif; ?>
       	 <jdoc:include type="message" />
          <jdoc:include type="component" />
  </div>
</div>
   
    

  
  
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
                
                <div class="col-md-5 pull-right">
                
                	<jdoc:include type="modules" name="meadows-newsletter"  style="xhtml" />
                	<!--//end newsletter-->
                
                	<jdoc:include type="modules" name="footer-social-information"  style="xhtml" />
                    <!--//end social footer-->
                
                </div>
            </div>
        </div>
    </div>
    <!--//end footer-->
      	

	
    
    
	</body>   
</html>	
