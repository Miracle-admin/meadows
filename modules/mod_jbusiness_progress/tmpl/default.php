<?php // no direct access
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
$user = JFactory::getUser();

?>

<div class="progress-container">
	
	<h1><?php echo $params->get("title"); ?></h1>
	
	<div class="progress-steps">
		<div class="progress-step">
			<div class="progress-img">
				<div class="profile <?php echo $user->id == 0?"current":"completed" ?>">
					<div class="step">
							<i>1</i>
					</div>
				</div>
			</div>
			<div class="progress-content">
				<h3><?php echo $params->get("member_title"); ?></h3>
				<p class="bottom"><?php echo $params->get("member_text"); ?></p>
			</div>
		</div>
		<div class="progress-step">
			<div class="progress-img">
				<div class="business <?php echo $user->id == 0?"":"current" ?>">
					<div class="step">
						<i>2</i>
					</div>
				</div>
			</div>
			<div class="progress-content">
				<h3><?php echo $params->get("add_business_title"); ?></h3>
				<p class="bottom"><?php echo $params->get("add_business_text"); ?></p>
			</div>
		</div>
	</div>
	
</div>
