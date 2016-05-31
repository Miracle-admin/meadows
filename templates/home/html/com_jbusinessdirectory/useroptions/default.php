<?php // no direct access
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$config = new JConfig();

//retrieving current menu item parameters
$currentMenuId = null;
$activeMenu = JFactory::getApplication()->getMenu()->getActive();
if(isset($activeMenu))
	$currentMenuId = $activeMenu->id ; // `enter code here`
$document = JFactory::getDocument(); // `enter code here`
$app = JFactory::getApplication(); // `enter code here`
if(isset($activeMenu)){
	$menuitem   = $app->getMenu()->getItem($currentMenuId); // or get item by ID `enter code here`
	$params = $menuitem->params; // get the params `enter code here`
}else{
	$params = null;
}

//set page title
if(!empty($params) && $params->get('page_title') != ''){
	$title = $params->get('page_title', '');
}
if(empty($title)){
	$title = JText::_("LNG_CONTROL_PANEL").' | '.$config->sitename;
}
$document->setTitle($title);

//set page meta description and keywords
$description = $this->appSettings->meta_description;
$document->setDescription($description);
$document->setMetaData('keywords', $this->appSettings->meta_keywords);

if(!empty($params) && $params->get('menu-meta_description') != ''){
	$document->setMetaData( 'description', $params->get('menu-meta_description') );
	$document->setMetaData( 'keywords', $params->get('menu-meta_keywords') );
}

$uri     = JURI::getInstance();
$url = $uri->toString( array('scheme', 'host', 'port', 'path'));

$user = JFactory::getUser();
if($user->id == 0){
	$app = JFactory::getApplication();
	$return = base64_encode(JRoute::_('index.php?option=com_jbusinessdirectory&view=useroptions'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return));
}

$appSettings =  JBusinessUtil::getInstance()->getApplicationSettings();
$enablePackages = $appSettings->enable_packages;
$enableOffers = $appSettings->enable_offers;
$hasBusiness = isset($this->companies) && count($this->companies)>0;
?>



<div id="user-options">
	<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
	    <div class="page-header">
	        <h1 class="title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	    </div>
	<?php }else{ ?>
		<h1 class="title">
			<?php echo JTEXT::_("LNG_CONTROL_PANEL") ?>
		</h1>
	<?php } ?>
	
	<?php if($this->actions->get('directory.access.controlpanel') || !$appSettings->front_end_acl){ ?>
		<?php if(!$hasBusiness){ ?>
		<p>
			<?php echo JText::_("LNG_USER_OPTION_INFO")?>
		</p>
		<?php } ?>
		<div class="user-options-container row">
			<ul class="">
				<?php if(!$hasBusiness && ($this->actions->get('directory.access.listings') || !$appSettings->front_end_acl)){ ?>
						<li class="option-button search col-md-4">
							<div href="#" class="box box-inset search">
                      
								<?php /*?><img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/search-find.png' ?>" />	<?php */?>
                                
                                
                                
								<h3> <?php echo JTEXT::_("LNG_CLAIM_COMPANY") ?></h3>
							 	<span> <?php echo JTEXT::_("LNG_CLAIM_COMPANY_INFO") ?>&nbsp;</span>
	 							<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="claim-search" id="claim-search" class="search-form" >
									<input type='hidden' name='view' value='search'>
									<input type='hidden' name='categorySearch' value=''>
									<div class="form-field">
										<input type="text" name="searchkeyword" id="searchkeyword"  value="" />
									</div>
									<button type="button" class="ui-dir-button ui-dir-button-green" onclick="jQuery('#claim-search').submit()">
											<span class="ui-button-text"> <?php echo JText::_("LNG_SEARCH")?></span>
									</button>
								</form>
							</div>
						</li>
				<?php } ?>
				
				<?php if($this->actions->get('directory.access.listings')|| !$appSettings->front_end_acl){?>
					<li class="option-button col-md-4">
						<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies') ?>" class="box box-inset">
							<?php /*?><img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/business-listings.png' ?>" /><?php */?>	
                            
                            <i class="fa fa-list-alt"></i>
                            
                            
							<h3>
								<?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA") ?>
							</h3>
							<p> <?php echo JTEXT::_("LNG_ADD_MODIFY_COMPANY_DATA_INFO") ?></p>
						</a>
					</li>
				<?php } ?>
				
				
				<?php if($enableOffers && ($this->actions->get('directory.access.offers')|| !$appSettings->front_end_acl)){?>
					<li class="option-button col-md-4" <?php  if(!$hasBusiness) echo 'style="opacity: .4;corsor: default;"'?>>
					 	<a class="box box-inset" href="<?php echo $hasBusiness?JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffers'):'#'; ?>">
					 		<?php /*?><img alt="<?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/special-offer.png' ?>" />	<?php */?>
                            
                            
                            
                            <i class="fa fa-tag"></i>
                            
					 		<h3>
					 			<?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS") ?>
		 					</h3>
		 					<p><?php echo JTEXT::_("LNG_ADD_MODIFY_OFFERS_INFO") ?></p>
		 				</a>
					</li>
				<?php } ?>
				
				<?php if($appSettings->enable_events && ($this->actions->get('directory.access.events')|| !$appSettings->front_end_acl)){?>		
					<li class="option-button col-md-4" <?php  if(!$hasBusiness) echo 'style="opacity: .4;corsor: default;"'?>>
					 	<a class="box box-inset" href="<?php  echo $hasBusiness?JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyevents'):'#' ?>">
					 		<?php /*?><img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_EVENTS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/events.png' ?>" />	<?php */?>
	<i class="fa fa-calendar"></i>
                            
					 		<h3>
					 			<?php echo JTEXT::_("LNG_MANAGE_YOUR_EVENTS") ?>
		 					</h3>
		 					<p> <?php echo JTEXT::_("LNG_EVENTS_INFO") ?></p>
		 				</a>
					</li>
				<?php } ?>	
					
					<li class="option-button col-md-4" <?php  if(!$hasBusiness) echo 'style="opacity: .4;corsor: default;"'?>>
					 	<a class="box box-inset" href="<?php  echo JRoute::_('index.php?option=com_jbusinessdirectory&view=orders') ?>">
					 		<?php /*?><img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_ORDERS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/orders.png' ?>" /><?php */?>
                            <i class="fa fa-list"></i>	
					 		<h3>
					 			<?php echo JTEXT::_("LNG_MANAGE_YOUR_ORDERS") ?>
		 					</h3>
		 					<p> <?php echo JTEXT::_("LNG_ORDERS_INFO") ?></p>
		 				</a>
					</li>
				<?php if($this->actions->get('directory.access.bookmarks')|| !$appSettings->front_end_acl) { ?>
					<?php if($appSettings->enable_bookmarks) { ?>	
						<li class="option-button col-md-4">
						 	<a class="box box-inset" href="<?php  echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managebookmarks') ?>">
						 		<?php /*?><img alt="<?php echo JTEXT::_("LNG_MANAGE_YOUR_BOOKMARKS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/bookmark.png' ?>" /><?php */?>	
<i class="fa fa-bookmark"></i>
						 		<h3>
						 			<?php echo JTEXT::_("LNG_MANAGE_YOUR_BOOKMARKS") ?>
			 					</h3>
			 					<p> <?php echo JTEXT::_("LNG_BOOKMARKS_INFO") ?></p>
			 				</a>
						</li>
					<?php }?>
				<?php }?>
					<li class="option-button col-md-4">
					 	<a class="box box-inset" href="<?php  echo JRoute::_('index.php?option=com_jbusinessdirectory&view=billingdetails&layout=edit') ?>">
					 		<?php /*?><img alt="<?php echo JTEXT::_("LNG_BILLING_DETAILS") ?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/images/user.png' ?>" /><?php */?>	
                            <i class="fa fa-bars"></i>

                            
					 		<h3>
					 			<?php echo JTEXT::_("LNG_BILLING_DETAILS") ?>
		 					</h3>
		 					<p> <?php echo JTEXT::_("LNG_BILLING_DETAILS_INFO") ?></p>
		 				</a>
					</li>
					
				</ul>
				<div class="clear"></div>
				
		</div>
	
	<?php }else{
			echo JText::_("LNG_NOT_AUTHORIZED");
		  }
	?>
</div>

<div class="add-container" style="display:none">
<?php 
jimport('joomla.application.module.helper');
// this is where you want to load your module position
$modules = JModuleHelper::getModules('user-banners');
?>
	<div class="company-categories">
		<?php 
		foreach($modules as $module)
		{
			echo JModuleHelper::renderModule($module);
		}
		
		?>
	</div>
</div>

<div class="clear"></div>