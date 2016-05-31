<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<a class="add-review-link" href="javascript:void(0)" onclick="showReviewForm()" <?php echo ($appSettings->enable_reviews_users && $user->id ==0) ? 'style="display:none"':'' ?>><?php echo JText::_("LNG_ADD_REVIEW") ?></a>
<?php require_once 'default_addreview.php'; ?>
<br/>	
<?php if(count($this->reviews)==0){ ?>
	<p><?php echo JText::_('LNG_NO_REVIEWS') ?></p>
<?php } ?>
<ul id="reviews">
<a name="reviews"></a>
	<?php foreach($this->reviews as $review){?>
		<li class="review">
			<div class="review-content">
			
				<h4><?php echo $review->subject ?></h4>
				
				<div class="review-author">
					<p class="review-by-content">
					<span class="reviewer-name"> <?php echo $review->name ?> </span>,
						<span class="review-date"><?php echo JBusinessUtil::getDateGeneralFormat($review->creationDate) ?></span>
					</p>
				</div>
			
				<div class="rating-block">
					<?php if(!empty($review->scores) && !empty($review->criteriaNames)){?>
						<div class="review-rating" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
								<span itemprop="ratingValue"><?php echo number_format($review->rating,1) ?></span>
							</div>
						<?php foreach($review->scores as $key=>$value){
								if(empty( $review->criteriaNames[$key]))
									continue;
							?>
							<div class="review-criteria">
								<span class="review-criteria-name"><?php echo $review->criteriaNames[$key]?></span>
								<span title="<?php echo $value ?>" class="rating-review"></span>
							</div>
						<?php } ?>
					
					<?php }else{?>
						<div>
							<span title="<?php echo $review->rating ?>" class="rating-review"></span>
						</div>	
					<?php } ?>
					<div class="clear"></div>
				</div>

				
				<div class="review-description">
					<?php echo $review->description ?>
				</div>
				<?php if(isset($review->responses) && count($review->responses)>0){ 
					foreach($review->responses as $response){
					?>
					<div class="review-response">
						<strong><?php echo JText::_('LNG_REVIEW_RESPONSE') ?></strong><br/>
						<span class="bold"><?php echo $response->firstName ?> </span>
						<p><?php echo $response->response ?></p>
					</div>
				<?php
					}
					}?>
				
				<div class="review-actions">
					<ul>
						<li class="first">
							<a href="javascript:reportReviewAbuse(<?php echo $review->id?>)"><?php echo JText::_('LNG_REPORT_ABUSE') ?></a>
						</li>
						<li>
							<a href="javascript:respondToReview(<?php echo $review->id?>)"><?php echo JText::_('LNG_RESPOND_TO_REVIEW') ?></a>
						</li>
					</ul>
				</div>
				
				<div class="rate-review">
					<span class="rate"><?php echo JText::_("LNG_RATE_REVIEW")?>:</span>
					<ul>
						<li class="thumbup"><a rel="nofollow"
							id="increaseLike<?php echo $review->id ?>" 
							href="javascript:void(0)" onclick="increaseReviewLikeCount(<?php echo $review->id ?>)"><?php echo JText::_("LNG_THUMB_UP")?>
								</a> <span class="count"  > (<span id="like<?php echo $review->id ?>"><?php echo $review->likeCount ?></span>) </span>
						</li>
						<li class="thumbdown">
							<a rel="nofollow" 
							id="decreaseLike<?php echo $review->id ?>" 
							href="javascript:void(0)" onclick="increaseReviewDislikeCount(<?php echo $review->id ?>)"><?php echo JText::_("LNG_THUMB_DOWN")?></a> 
							<span class="count"  > (<span id="dislike<?php echo $review->id ?>"><?php echo $review->dislikeCount ?></span>) </span>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</li>
	<?php } ?>
</ul>
<div class="clear"></div>

<div id="report-abuse" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_REPORT_ABUSE') ?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" id="reportAbuse" name="reportAbuse"  method="post">
					<p>
						<?php echo JText::_("LNG_ABUSE_INFO");?>
					</p>
					<div class="report-abuse">
						<fieldset>
							<div class="form-item">
								<label for="subject"><?php echo JText::_('LNG_EMAIL') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email"><br>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
					
							<div class="form-item">
								<label for="rating_body" ><?php echo JText::_('LNG_REPORT_ABUSE_BECAUSE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="description" id="description" escape="false"></textarea><br>
									<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
									$namespace="jbusinessdirectory.contact";
									$class=" required";
									
									$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																		
									if(!empty($captcha)){	
										$captcha->display("captcha", "captcha-div-review-abuse", $class);
									}
									
									?>
									<div id="captcha-div-review-abuse"></div>
								</div>
							<?php } ?>
									
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="button" class="ui-dir-button" onclick="saveReviewAbuse()">
											<span class="ui-button-text"><?php echo JText::_("LNG_SUBMIT")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
					</div>
					<input type='hidden' name='task' value='companies.reportAbuse'/>
					<input type='hidden' name='view' value='companies' />
					<input type="hidden" id="reviewId" name="reviewId" value="" />
					<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
				</form>
			</div>
		</div>
	</div>
</div>


<div id="new-review-response" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_RESPOND_REVIEW') ?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<form id="reviewResponseFrm" name ="reviewResponseFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post">
					<p>
						<?php echo JText::_('LNG_RESPOND_REVIEW_INFO') ?>
					</p>
					<div class="review-repsonse">
						<fieldset>
		
							<div class="form-item">
								<label for="firstName"><?php echo JText::_('LNG_FIRST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="firstName" id="firstName"><br>
									<span class="error_msg" id="frmFirstName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="lastName" id="lastName"><br>
									<span class="error_msg" id="frmLastName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email"><br>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
					
							<div class="form-item">
								<label for="response" ><?php echo JText::_('LNG_REVIEW_RESPONSE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="response" id="response" escape="false"></textarea><br>
									<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
							
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
									$namespace="jbusinessdirectory.contact";
									$class=" required";
									
									$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																		
									if(!empty($captcha)){	
										$captcha->display("captcha", "captcha-div-review-response", $class);
									}
									
									?>
									<div id="captcha-div-review-response"></div>
								</div>
							<?php } ?>
		
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="button" class="ui-dir-button" onclick="saveReviewResponse()">
											<span class="ui-button-text"><?php echo JText::_("LNG_SUBMIT")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
					</div>
			
					<input type='hidden' name='task' value='companies.saveReviewResponse'/>
					<input type='hidden' name='view' value='companies' />
					<input type="hidden" id="reviewId" name="reviewId" value="" />
					<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
				</form>
			</div>
		</div>
	</div>
</div>

