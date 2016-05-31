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

function preparequickIconButton($access,$url, $image, $text, $description=null){
	$actions = JBusinessDirectoryHelper::getActions();
	if($actions->get($access)){
	?>
		<li class="option-button">
			<a class="box box-inset" href="<?php  echo $url ?>"> <?php echo JHTML::_('image','administrator/components/com_jbusinessdirectory/assets/img/'.$image, $text); ?>
				<h3>
					<?php echo $text; ?>
				</h3>
				<p>
					<?php echo $description ?>
				</p>
			</a>
		</li>
	<?php
	}
}
?>
<div class="row-fluid">

<div id='business-cpanel' class="business-cpanel span9">
	<ul>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=applicationsettings", 'settings.png', JText::_('LNG_APPLICATION_SETTINGS') );?>
		<?php echo preparequickIconButton("directory.access.categories", "index.php?option=".JBusinessUtil::getComponentName()."&view=categories", 'categories.png', JText::_('LNG_MANAGE_CATEGORIES') );?>
		<?php echo preparequickIconButton("directory.access.listings", "index.php?option=".JBusinessUtil::getComponentName()."&view=companies", 'business_listings.png', JText::_('LNG_MANAGE_COMPANIES') );?>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=attributes", 'custom_fields.png', JText::_('LNG_MANAGE_CUSTOM_FIELDS') );?>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=companytypes", 'business_types.png', JText::_('LNG_MANAGE_COMPANY_TYPES') );?>
		<?php echo preparequickIconButton("directory.access.offers", "index.php?option=".JBusinessUtil::getComponentName()."&view=offers", 'offers.png', JText::_('LNG_MANAGE_OFFERS') );?>
		<?php echo preparequickIconButton("directory.access.events", "index.php?option=".JBusinessUtil::getComponentName()."&view=events", 'events.png', JText::_('LNG_MANAGE_EVENTS') );?>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=eventtypes", 'event_types.png', JText::_('LNG_MANAGE_EVENT_TYPES') );?>
		<?php echo preparequickIconButton("directory.access.packages", "index.php?option=".JBusinessUtil::getComponentName()."&view=packages", 'packages.png', JText::_('LNG_MANAGE_PACKAGES') );?>
		<?php echo preparequickIconButton("directory.access.discounts", "index.php?option=".JBusinessUtil::getComponentName()."&view=discounts", 'discounts.png', JText::_('LNG_MANAGE_DISCOUNTS') );?>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=orders", 'invoice.png', JText::_('LNG_MANAGE_ORDERS') );?>
		<?php echo preparequickIconButton("directory.access.payment.config", "index.php?option=".JBusinessUtil::getComponentName()."&view=paymentprocessors", 'payment.png', JText::_('LNG_PAYMENT_PROCESSORS') );?>
		<?php echo preparequickIconButton("directory.access.countries", "index.php?option=".JBusinessUtil::getComponentName()."&view=countries", 'managecountries_48_48_icon.png', JText::_('LNG_MANAGE_COUNTRIES') );?>
		<?php echo preparequickIconButton("directory.access.reviews", "index.php?option=".JBusinessUtil::getComponentName()."&view=ratings", 'rating-icon.png', JText::_('LNG_MANAGE_RATINGS') );?>
		<?php echo preparequickIconButton("directory.access.reviews", "index.php?option=".JBusinessUtil::getComponentName()."&view=reviews", 'reviews.png', JText::_('LNG_MANAGE_REVIEWS') );?>
		<?php echo preparequickIconButton("directory.access.reviews", "index.php?option=".JBusinessUtil::getComponentName()."&view=reviewresponses", 'review_responses.png', JText::_('LNG_MANAGE_REVIEW_RESPONSES') );?>
		<?php echo preparequickIconButton("directory.access.reviews", "index.php?option=".JBusinessUtil::getComponentName()."&view=reviewabuses", 'review_abuses.png', JText::_('LNG_MANAGE_REVIEW_ABUSES') );?>
		<?php echo preparequickIconButton("directory.access.reports", "index.php?option=".JBusinessUtil::getComponentName()."&view=reports", 'reports.png', JText::_('LNG_REPORTS') );?>
		<?php echo preparequickIconButton("directory.access.emails", "index.php?option=".JBusinessUtil::getComponentName()."&view=emailtemplates", 'emailtemplated.png', JText::_('LNG_MANAGE_EMAIL_TEMPLATES') );?>
		<?php echo $this->appSettings->limit_cities==1?preparequickIconButton("directory.access.cities", "index.php?option=".JBusinessUtil::getComponentName()."&view=cities", 'cities.png', JText::_('LNG_MANAGE_CITIES') ):"";?>
		<?php echo preparequickIconButton("directory.access.directory.management", "index.php?option=".JBusinessUtil::getComponentName()."&view=updates", 'downloads.png', JText::_('LNG_UPDATE') );?>
	</ul>
	<div class="clear"></div>
</div>

<div id="informations" class="span3 informations">
	<div id="accordion-info">
		<h3>
			<?php echo JText::_("LNG_DIRECTORY_STATISTICS");?>
		</h3>
		<div>
			<p>
				<?php echo JText::_("LNG_TOTAL_BUSINESSES");?>
				:
				<?php echo $this->statistics->totalListings ?>
				<br />
				<?php echo JText::_("LNG_TODAY");?>
				:
				<?php echo $this->statistics->today ?>
				,
				<?php echo JText::_("LNG_THIS_WEEK");?>
				:
				<?php echo $this->statistics->week ?>
				,
				<?php echo JText::_("LNG_THIS_MONTH");?>
				:
				<?php echo $this->statistics->month ?>
				,
				<?php echo JText::_("LNG_THIS_YEAR");?>
				:
				<?php echo $this->statistics->year ?>
			</p>
			<p>
				<?php echo JText::_("LNG_TOTAL_CATEGORIES");?>
				:
				<?php echo $this->statistics->totalCategories ?>
			</p>
			<p>
				<?php echo JText::_("LNG_TOTAL_OFFERS");?>
				:
				<?php echo $this->statistics->totalOffers ?>
				<br />
				<?php echo JText::_("LNG_ACTIVE");?>
				:
				<?php echo $this->statistics->activeOffers ?>
			</p>
			<p>
				<?php echo JText::_("LNG_TOTAL_EVENTS");?>
				:
				<?php echo $this->statistics->totalEvents ?>
				<br />
				<?php echo JText::_("LNG_ACTIVE");?>
				:
				<?php echo $this->statistics->activeEvents ?>
			</p>
			<p>
				<?php echo JText::_("LNG_TOTAL_INCOME");?>
				:
				<?php echo intval($this->income->total) ?>
				<br />
				<?php echo JText::_("LNG_TODAY");?>
				:
				<?php echo intval($this->income->total) ?>
				,
				<?php echo JText::_("LNG_THIS_WEEK");?>
				:
				<?php echo intval($this->income->total) ?>
				,
				<?php echo JText::_("LNG_THIS_MONTH");?>
				:
				<?php echo intval($this->income->total) ?>
				,
				<?php echo JText::_("LNG_THIS_YEAR");?>
				:
				<?php echo intval($this->income->total) ?>
			</p>
		</div>
		<h3>About CMS Junkie</h3>
		<div>
			<p>
				CMSJunkie offers top quality commercial CMS products: extensions,
				templates, themes, modules for open sources content management
				systems. All products are completely customizable and ready to be
				used as a basis for a clean and high-quality website. We are now
				working with following CMS systems: Magento, Drupal, Joomla. <br />
			</p>
			<p>The CMSJunkie Store team can answer your questions about
				purchasing, usage of our products, returns, and more. Our aim is to
				keep every one of our customers happy and we are not just saying
				that. We understand the importance of deadlines to our clients and
				we deliver on time and keep everything on schedule.</p>
			<p>For any questions or issues pleace contact us through following
				channels.
			
			
			<ul>
				<li><a
					href="http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1"
					title="Support forum"> Support forums</a></li>
				<li><a href="http://www.cmsjunkie.com/contacts/"
					title="Contact CMS Junkie">Contact</a></li>
				<li><a href="http://www.cmsjunkie.com/docs/jbusinessdirectory/index.html"
					title="Online documentation">Online documentation</a></li>
			</ul>

			<h4>Custom Services</h4>
			<p>
				We do offer <strong>custom development</strong>. If you are
				interested to contract us to perform some customizations, please
				feel free to <a href="http://www.cmsjunkie.com/contacts/"
					title="Contact CMS Junkie">contact us</a>!
			</p>
		</div>

		<h3>Release history</h3>
		<div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-6-0_release/">J-BusinessDirectory
							- version 3.6.0 BETA has been released.</a>
					</div>
					<i>Wednesday, June 4, 2014</i>
				</div>
				<div class="postContent">
					<p>J-BusinessDirectory extension - version 3.6.0 BETA - has been
						released. We have added some new improvements to ease the
						administration process, new features and bug fixes.</p>
					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-6-0_release/">Read
						More</a>
				</div>
			</div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-5-1_release/">Version
							3.5.1 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Saturday, May 10, 2014</i>
				</div>
				<div class="postContent">
					<p>We have released the version 3.5.1 of the J-BusinessDirectory
						extension with some important bug fixes.</p>
					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-5-1_release/">Read
						More</a>
				</div>
			</div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-5-0_release/">Version
							3.5.0 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Tuesday, April 29, 2014</i>
				</div>
				<div class="postContent">
					<p>We have released the version 3.5.0 of the J-BusinessDirectory
						extension with some new features, improvements and important bug
						fixes.</p>
					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-5-0_release/">Read
						More</a>
				</div>
			</div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-4-3_release/">Version
							3.4.3 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Thursday, March 20, 2014</i>
				</div>
				<div class="postContent">
					<p>We have released the version 3.4.3 of the J-BusinessDirectory
						extension with some improvements and important bug fixies.</p>
					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-4-3_release/">Read
						More</a>
				</div>
			</div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-4-2_release/">Version
							3.4.2 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Tuesday, March 11, 2014</i>
				</div>
				<div class="postContent">
					<p>We have released the version 3.4.2 of the J-BusinessDirectory
						extension with some improvements and bug fixies.</p>
					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/post/joomla_business_directory_3-4-2_release/">Read
						More</a>
				</div>
				
			</div>

			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/joomla_business_directory_3-4-0_release/">Version
							3.4.0 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Friday, February 28</i>
				</div>
				<div class="postContent">
					<p>Good news everyone. We just released the version 3.4.0 of the
						J-BusinessDirectory extension with some new features and
						improvements.</p>
					<a
						href="http://www.cmsjunkie.com/blog/joomla_business_directory_3-4-0_release/"
						class="aw-blog-read-more">Read More</a>
				</div>
			</div>
			<div class="postWrapper">
				<div class="postTitle">
					<div>
						<a
							href="http://www.cmsjunkie.com/blog/joomla_business_directory_3-3-0_release/">Version
							3.3.0 of the J-BusinessDirectory extension has been released.</a>
					</div>
					<i>Tuesday, January 28, 2014</i>
				</div>
				<div class="postContent">
					<p>J-BusinessDirectory is getting better and better. We have
						released the version 3.3.0 of the J-BusinessDirectory extension
						with some new features and improved user experience.</p>

					<a class="aw-blog-read-more"
						href="http://www.cmsjunkie.com/blog/joomla_business_directory_3-3-0_release/">Read
						More</a>
				</div>
			</div>

			<div>
				<a href="http://www.cmsjunkie.com/blog/cat/news-joomla-business-directory/">More news</a>
			</div>
		</div>
	</div>
	<div class="clearfix social">
		<div style="float: left;">
			<h4>Connect with us:</h4>
		</div>
		<div class="block-content">
			<ul class="social">
				<li><a target="social" href="http://twitter.com/cmsjunkie"
					class="twitter"><span>Twitter</span> </a></li>
				<li><a target="social" href="http://facebook.com/cmsjunkie"
					class="facebook"><span>Facebook</span> </a></li>
				<li><a href="mailto:info@cmsjunkie.com" class="email"><span>Email</span>
				</a></li>
				<li><a target="social"
					href="https://plus.google.com/100376620356699373069/posts"
					class="google"><span>Google</span> </a></li>
			</ul>
		</div>
	</div>
</div>

</div>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option"
		value="<?php echo JBusinessUtil::getComponentName()?>" />
</form>

<script>
jQuery(document).ready(function(){
	jQuery("#accordion-info").accordion({
		 heightStyle: "content"
	 });
});

</script>
