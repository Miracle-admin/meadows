<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 November 2012
 * @file name	:	views/user/tmpl/editportfolio.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Lets user to add/edit porfolio (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.formvalidation');
 
 $doc 	 = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");
 $doc->addScript("components/com_jblance/js/btngroup.js");
 
 $app  	 = JFactory::getApplication();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $editor = JFactory::getEditor();
 $config = JblanceHelper::getConfig();
 $user 	 = JFactory::getUser();
 
 //get the allowed portfolio for the user's plan
 $plan = JblanceHelper::whichPlan($user->id);
 $allowedPortfolio = $plan->portfolioCount;
 
 $link_new	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio&type=addnew');
 
 $type = $app->input->get('type', '', 'string');
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
 
 JblanceHelper::setJoomBriToken();
 ?>
 <script type="text/javascript">
<!--
	function validateForm(f){

		//check if skills are selected
		if(!$$('input[class="jb-checkboxes"]:checked')[0]){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_SELECT_SKILLS_FROM_THE_LIST', true); ?>');
			return false;
		}

		//validate the video link
		p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		var matches = jQuery('#jform_video_link').val().match(p);
		if(!matches){
			alert('<?php echo JText::_('COM_JBLANCE_ENTER_VALID_YOUTUBE_URL', true); ?>');
			return false;
		}
		
		if (document.formvalidator.isValid(f)) {
			
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
			alert(msg);
			return false;
	    }
		return true;
	}


	window.addEvent('domready', function(){
		attachFile('portfoliopicture', 'user.attachportfoliofile');
		attachFile('portfolioattachment1', 'user.attachportfoliofile');
		attachFile('portfolioattachment2', 'user.attachportfoliofile');
		attachFile('portfolioattachment3', 'user.attachportfoliofile');
		attachFile('portfolioattachment4', 'user.attachportfoliofile');
		attachFile('portfolioattachment5', 'user.attachportfoliofile');
	});
//-->
</script>

<div class="mtb-40">

<div class="pull-right">
	<?php if(count($this->portfolios) >= $allowedPortfolio) : ?>
	<?php $msg = JText::sprintf('COM_JBLANCE_REACHED_PORTFOLIO_LIMIT', $allowedPortfolio, array('jsSafe'=>true));?>
	<a href="javascript:void(0);" class="btn btn-primary" onclick="javascript:modalAlert('<?php echo JText::_('COM_JBLANCE_LIMIT_EXCEEDED', true); ?>', '<?php echo $msg; ?>', false);">
		<span><?php echo JText::_('COM_JBLANCE_ADD_PORTFOLIO'); ?></span>
	</a>
	<?php else : ?>
	<a href="<?php echo $link_new; ?>" class="btn btn-primary"><span><?php echo JText::_('COM_JBLANCE_ADD_PORTFOLIO'); ?></span></a>
	<?php endif; ?>
</div>
<?php //include_once(JPATH_COMPONENT.'/views/profilemenu.php'); ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormPortfolio" id="userFormPortfolio" class="form-validate form-horizontal" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PORTFOLIOS'); ?></div>
	<?php if(count($this->portfolios) > 0){ ?>
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_JBLANCE_TITLE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?></th>
				<th class="center"><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<th class="center"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $x=count($this->portfolios); $i < $x; $i++){
			$portfolio = $this->portfolios[$i];
			$link_edit	 = JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio&id='.$portfolio->id);
			$link_delete = JRoute::_('index.php?option=com_jblance&task=user.deleteportfolio&id='.$portfolio->id.'&'.JSession::getFormToken().'=1');
			?>
			<tr>
				<td><?php echo $i+1; ?></td>
				<td>
					<?php echo $portfolio->title; ?>
				</td>
				<td>
				<?php 
				$position = 40; // Define how many character you want to display.
				$message = strip_tags($portfolio->description); 
				$trimmed = substr($message, 0, $position); 
				echo $trimmed.'...';
				?>
				</td>
				<td class="center">
					<a href="<?php echo $link_edit; ?>"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></a>	|	 
					<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_DELETE', true); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_DELECT_PORTFOLIO', true); ?>', '<?php echo $link_delete; ?>');"><?php echo JText::_('COM_JBLANCE_DELETE'); ?></a>
				</td>
				<td class="center">
					<img src="components/com_jblance/images/s<?php echo $portfolio->published; ?>.png" alt="Status">
				</td>
		  </tr>
			<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
	</table>
	<?php 
 	}
 	else 
 		echo '<p>'.JText::_('COM_JBLANCE_NO_PORTFOLIO').'</p>';
	?>
<!-- Show the Edit layout only when there is no portfolio or add new or edit link is clicked -->
<?php if(/* count($this->portfolios) == 0 ||  */$this->row->id > 0 || ($type == 'addnew' && $allowedPortfolio > 0)){ ?>
	<div class="jbl_h3title">
	<?php echo ($this->row->id == 0) ? JText::_('COM_JBLANCE_ADD_PORTFOLIO') : JText::_('COM_JBLANCE_EDIT_PORTFOLIO'); ?>
	</div>
	<div class="control-group">
		<label class="control-label" for="jform_title"><?php echo JText::_('COM_JBLANCE_TITLE'); ?>:</label>
		<div class="controls">
			<input type="text" class="input-xlarge required" name="jform[title]" id="jform_title" value="<?php echo $this->row->title; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="jformid_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</label>
		<div class="controls">
			<?php 
			//$attribs = 'class="inputbox required" size="20" multiple ';
			//$defaultCategory = empty($this->row->id_category) ? 0 : explode(',', $this->row->id_category);
			//$categtree = $select->getSelectCategoryTree('jform[id_category][]', $defaultCategory, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
			//echo $categtree; 
			$attribs = '';
			$select->getCheckCategoryTree('jform[id_category][]', explode(',', $this->row->id_category), $attribs);
			?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="jform_start_date"><?php echo JText::_('COM_JBLANCE_START_DATE'); ?>:</label>
		<div class="controls">
			<?php 
			 $startdate = (empty($this->row->start_date)) ? '' : $this->row->start_date;
			 echo JHtml::_('calendar', $startdate, 'jform[start_date]', 'jform_start_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'32'));
			 ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="jform_finish_date"><?php echo JText::_('COM_JBLANCE_FINISH_DATE'); ?>:</label>
		<div class="controls">
			<?php 
			$finishdate = (empty($this->row->finish_date)) ? '' : $this->row->finish_date;
			echo JHtml::_('calendar', $finishdate, 'jform[finish_date]', 'jform_finish_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'32'));
			?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="jform_link"><?php echo JText::_('COM_JBLANCE_WEB_ADDRESS'); ?>:</label>
		<div class="controls">
			<input type="text" class="inputbox" name="jform[link]" id="jform_link" value="<?php echo $this->row->link; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="published"><?php echo JText::_('COM_JBLANCE_PUBLISHED'); ?>:</label>
		<div class="controls">
			<?php echo $select->YesNoBool('jform[published]', $this->row->published == 0 ? 0 : 1); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="video_link"><?php echo JText::_('COM_JBLANCE_YOUTUBE_LINK'); ?>:</label>
		<div class="controls">
			<input type="text" class="span4" name="jform[video_link]" id="jform_video_link" value="<?php echo $this->row->video_link; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="jform_description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</label>
		<div class="controls">
			<?php echo $editor->display('jform[description]', $this->row->description, '80%', '250', '30', '5', false, 'jform_description'); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="published"><?php echo JText::_('COM_JBLANCE_PORTFOLIO_IMAGE'); ?>:</label>
		<div class="controls">
			<?php
			if($this->row->picture){
				$attachment = explode(";", $this->row->picture);
				$showName = $attachment[0];
				$fileName = $attachment[1];
				
				$imgLoc = JBPORTFOLIO_URL.$fileName;
			?>
			<img src='<?php echo $imgLoc; ?>' width="<?php echo $width; ?>" class="img-polaroid" style="max-width: 450px; width: 95%" />
			<?php 
			}
			?>
			<div id="ajax-container-portfoliopicture"></div>
			<div id="file-attached-portfoliopicture"></div><div class="sp10">&nbsp;</div>
			<button type="button" id="portfoliopicture" class="btn"><i class="icon-picture"></i> <?php echo JText::_('COM_JBLANCE_ATTACH_IMAGE'); ?></button>
			<?php 
			//$tipmsg = JText::_('COM_JBLANCE_ATTACH_IMAGE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
			?>
			<!-- <img src="components/com_jblance/images/tooltip.png" class="hasTooltip" title="<?php echo $tipmsg; ?>"/> -->
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="published"><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</label>
		<div class="controls">
			<?php
			/* if($this->row->attachment){
				//echo LinkHelper::getPortfolioDownloadLink('portfolio', $this->row->id, 'user.download', );
			} */
			?>
			<ul class="unstyled">
				<?php 
				for($i=1; $i<=5; $i++){
					$attachmentColumnNum = 'attachment'.$i;
					if(!empty($this->row->$attachmentColumnNum)){
						$attachment = explode(";", $this->row->$attachmentColumnNum);
						$showName = $attachment[0];
						$fileName = $attachment[1];
						$fileUrl = JBPORTFOLIO_URL.$fileName;
					}
				?>
				<li style="padding: 5px 0px;">
					<div class="row-fluid">
						<div class="span6">
							<button type="button" id="portfolioattachment<?php echo $i; ?>" class="btn"><i class="icon-file"></i> <?php echo JText::_('COM_JBLANCE_ATTACH_FILE').' - '.$i; ?></button>
							<div id="ajax-container-portfolioattachment<?php echo $i; ?>"></div>
							<div id="file-attached-portfolioattachment<?php echo $i; ?>"></div>
						</div>
						<div class="span6">
							<?php if(!empty($this->row->$attachmentColumnNum)){ ?>
							<div class=""><em><?php echo JText::_('COM_JBLANCE_OLD_FILE'); ?>: </em><?php echo LinkHelper::getPortfolioDownloadLink('portfolio', $this->row->id, 'user.download', $attachmentColumnNum); //echo LinkHelper::GetHrefLink($fileUrl, $showName); ?></div>
							<?php } ?>
						</div>
					</div>
				</li>
				<?php
				}
				?>
			</ul>
			
			
			
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="btn btn-primary" /> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="btn btn-primary" />
	</div>
<?php } ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="user.saveportfolio" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>


 