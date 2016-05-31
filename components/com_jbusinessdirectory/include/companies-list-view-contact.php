<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$enableSEO = $appSettings->enable_seo;
$enablePackages = $appSettings->enable_packages;
$enableRatings = $appSettings->enable_ratings;
$enableNumbering = $appSettings->enable_numbering;
$user = JFactory::getUser();

$showData = !($user->id==0 && $appSettings->show_details_user == 1);
?>

<div id="results-container" class="list-contact" <?php echo $this->appSettings->search_view_mode?'style="display: none"':'' ?>>
<?php 
if(!empty($this->companies)){
	foreach($this->companies as $index=>$company){
	?>
		<div id="result" class="result <?php echo isset($company->featured) && $company->featured==1?"featured":"" ?>">
			<?php if(!empty($company->featured)){?>
			
			<div class="business-container  row-fluid">
				<div class="business-info span4">
					<?php if(isset($company->packageFeatures) && in_array(SHOW_COMPANY_LOGO,$company->packageFeatures) || !$enablePackages){ ?>
					<div class="company-image">
						<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>">
							<?php if(isset($company->logoLocation) && $company->logoLocation!=''){?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>"/>
							<?php }else{ ?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>"/>
							<?php } ?>
						</a>
					</div>
					<?php } ?>
					
					<div>
						<span class="company-address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
							<span itemprop="address"><?php echo JBusinessUtil::getAddressText($company) ?></span>
						</span>

						<?php if( $showData && (isset($company->packageFeatures) && in_array(PHONE, $company->packageFeatures) || !$enablePackages )){ ?> 
							<?php if(!empty($company->phone)) { ?>
								<span class="phone" itemprop="tel">
										<a href="tel:<?php  echo $company->phone; ?>"><?php  echo $company->phone; ?></a>
								</span>
							<?php } ?>
						<?php } ?>

					</div>
					<div class="company-rating" <?php echo !$enableRatings? 'style="display:none"':'' ?>>
						<div style="display:none" class="rating-awareness tooltip">
							<div class="arrow">»</div>
							<div class="inner-dialog">
							<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
							<strong><?php echo JText::_('LNG_INFO') ?></strong>
								<p>
									<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
								</p>
							</div>
						</div>
						<div class="rating">
							<p class="rating-average" title="<?php echo $company->averageRating?>" alt="<?php echo $company->id?>" style="display: block;"></p>
						</div>						
					</div>
				</div>
		
				<div class="business-details span8">
					<div class="result-content">
						<h3 class="business-name">
							<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>" ><span itemprop="name"> <?php echo $company->name?> </span></a>
						</h3>
						
						<div class="company-short-description">
							<?php echo $company->short_description?> 
						</div>
						
						<div class="company-options">
							<ul>
								<li><a rel="nofollow" href="javascript:showContactCompany(<?php echo $company->id?>)"><?php echo JText::_('LNG_CONTACT') ?></a></li>
								<li><a rel="nofollow" href="javascript:showQuoteCompany(<?php echo $company->id?>)"><?php echo JText::_('LNG_QUOTE') ?></a></li>
								<li><a rel="nofollow" href="<?php echo JBusinessUtil::getCompanyLink($company)?>"><?php echo JText::_('LNG_MORE_INFO') ?></a></li>
							</ul>
						</div>	
						
			
					</div>	
				</div>
			</div>
			<?php }else{?>
				<div class="row-fluid">
					<div class="span12">
						<div class="company-options right">
							<ul>
								<li><a rel="nofollow" href="<?php echo JBusinessUtil::getCompanyLink($company)?>"><?php echo JText::_('LNG_MORE_INFO') ?></a></li>
							</ul>
						</div>	
						<h3 class="business-name">
							<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>" ><span itemprop="name"> <?php echo $company->name?> </span></a>
						</h3>
						
						<span class="company-address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
							<span itemprop="street-address"><?php echo $company->street_number.' '.$company->address?></span>
							<span itemprop="locality"><?php echo $company->city?></span>, <span itemprop="county-name"><?php echo $company->county?></span> <span itemprop="zipcode"><?php echo $company->postalCode?></span>
						</span>
						
						
					</div>	
				</div>
			<?php }?>
			
				<?php if(isset($company->featured) && $company->featured==1){ ?>
						<div class="featured-text">
							<?php echo JText::_("LNG_FEATURED")?>
						</div>
					<?php } ?>
			
			<div class="result-actions">
				<ul>
					<li> </li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	<?php 
	
	}
}
?>

</div>



<script>
function showQuoteCompany(companyId){
	jQuery("#company-quote #companyId").val(companyId);
	jQuery.blockUI({ message: jQuery('#company-quote'), css: {width: 'auto',top: '10%', left:"0", position:"absolute"} });
	jQuery('.blockUI.blockMsg').center();
	jQuery('.blockOverlay').click(jQuery.unblockUI); 

}	

function showContactCompany(companyId){
	jQuery("#company-contact #companyId").val(companyId);
	jQuery.blockUI({ message: jQuery('#company-contact'), css: {width: 'auto',top: '10%', left:"0", position:"absolute"} });
	jQuery('.blockUI.blockMsg').center();
	jQuery('.blockOverlay').click(jQuery.unblockUI); 

}	

function requestQuoteCompany(){
	var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=companies.requestQuoteCompanyAjax', false, -1); ?>";

	var postData="";
	postData +="&firstName="+jQuery("#company-quote #firstName").val();
	postData +="&lastName="+jQuery("#company-quote #lastName").val();
	postData +="&email="+jQuery("#company-quote #email").val();
	postData +="&description="+jQuery("#company-quote #description").val();
	postData +="&companyId="+jQuery("#company-quote #companyId").val();
	postData +="&category="+jQuery("#company-quote #category").val();
	postData +="&recaptcha_response_field="+jQuery("#company-quote #recaptcha_response_field").val();
	postData +="&g-recaptcha-response="+jQuery("#company-quote #g-recaptcha-response-1").val();
	
	jQuery.post(baseUrl, postData, processContactCompanyResult);
}

function contactCompany(){
	var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=companies.contactCompanyAjax', false, -1); ?>";

	var postData="";
	postData +="&firstName="+jQuery("#company-contact #firstName").val();
	postData +="&lastName="+jQuery("#company-contact #lastName").val();
	postData +="&email="+jQuery("#company-contact #email").val();
	postData +="&description="+jQuery("#company-contact #description").val();
	postData +="&companyId="+jQuery("#company-contact #companyId").val();
	postData +="&recaptcha_response_field="+jQuery("#captcha-div-contact #recaptcha_response_field").val();
	postData +="&g-recaptcha-response="+jQuery("#captcha-div-contact #g-recaptcha-response").val();
	
	jQuery.post(baseUrl, postData, processContactCompanyResult);
}


function processContactCompanyResult(responce){
	var xml = responce;
	jQuery(xml).find('answer').each(function()
	{
		if( jQuery(this).attr('error') == '1' ){
			 jQuery.blockUI({ 
				 	message: '<strong><?php echo JText::_("COM_JBUSINESS_ERROR")?></strong><br/><br/><p>'+jQuery(this).attr('errorMessage')+'</p>'
		        }); 
		     setTimeout(jQuery.unblockUI, 2000); 
		}else{
			 jQuery.blockUI({ 
				 	message: '<h3><?php echo JText::_("COM_JBUSINESS_DIRECTORY_COMPANY_CONTACTED")?></h3>'
		        }); 
		     setTimeout(jQuery.unblockUI, 2000); 
		}
	});
}

</script>