<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><style type="text/css">
div.templatedescription{
	color:#819197;
	text-align:center;
}

div.templatedescription img{
	display:block;
	clear:both;
	margin:auto;
	margin-bottom:10px;
	border:1px solid #EEEEEE;
	padding:5px;
	background-color:#fff;
	max-height:200px;
	max-width:190px;
}

div.templatearea{
	border:1px solid #e5e5e5;
	background-color:#fff;
	margin:9px 7px;
	padding:2px;
	width:205px;
	position: relative;
	display: inline-block;
	vertical-align: top;
	background: #fff;
	text-align: center;
	cursor:pointer;
	min-height:260px;
}

div.templatearea:after, div.templatearea:before {
	content: " ";
	position: absolute;
	width: 50%;
	height: 100px;
	z-index: -10;
}

div.templatearea:before{
	bottom:7px;
	left: 5px;
	transform: rotate(-3deg);
	box-shadow: 7px 6px 8px #333;
}
div.templatearea:after{
	bottom:7px;
	right: 5px;
	transform: rotate(3deg);
	box-shadow: -7px 6px 8px #333;
}

div.templatearea:hover{
	background-color:#e9ecf3;
}

div.templatetitle{
	color:#4a7cac;
	text-align:center;
	font-family:cursive;
	font-style:normal;
	margin-bottom:10px;
	text-shadow:0 1px 0 #FFFFFF;
	font-size:14px;
}

body{
	background-color:#f6f7f9 !important;
	min-width:650px !important;
	height:auto;
}
html{
	overflow-y:auto;
}
.rt-container, .rt-block{
	width:auto !important;
	background-color:#f6f7f9 !important;
}

</style>
<form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm" id="adminForm" >
<?php if($this->pageInfo->elements->total > $this->pageInfo->elements->page || !empty($this->pageInfo->search)){ ?>
	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<td style="text-align:center;">
					<?php acymailing_listingsearch($this->pageInfo->search); ?>
				</td>
			</tr>
			<tr>
				<td style="text-align:center;">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</thead>
	</table>
<?php }
$num = 0;
if(empty($this->pageInfo->limit->start)){
	$num++;
	?>
			<div class="templatearea emptytemplate" onclick="applyTemplate(0);">
					<div class="templatetitle"><?php echo JText::_('ACY_NONE'); ?></div>
					<div style="display:none" id="stylesheet_0"></div>
					<div style="display:none" id="htmlcontent_0"><br /></div>
					<div style="display:none" id="textcontent_0"></div>
					<div style="display:none" id="subject_0"></div>
					<div style="display:none" id="replyname_0"></div>
					<div style="display:none" id="replyemail_0"></div>
					<div style="display:none" id="fromname_0"></div>
					<div style="display:none" id="fromemail_0"></div>
			</div>
			<?php
}
				for($i = 0,$a = count($this->rows);$i<$a;$i++){
					$row =& $this->rows[$i];
					$num++;
			?>
				<div class="templatearea" onclick="applyTemplate(<?php echo $row->tempid?>);">
						<div class="templatetitle"><?php echo acymailing_dispSearch($row->name,$this->pageInfo->search); ?></div>
						<div class="templatedescription">
						<?php if(!empty($row->thumb)){ ?>
							<img src="<?php echo ACYMAILING_LIVE.$row->thumb ?>" />
						<?php } ?>
						<?php echo acymailing_absoluteURL(nl2br($row->description)); ?>
						</div>
						<div style="display:none" id="stylesheet_<?php echo $row->tempid;?>"><?php echo $row->stylesheet;?></div>
						<div style="display:none" id="htmlcontent_<?php echo $row->tempid;?>"><?php echo acymailing_absoluteURL($row->body);?></div>
						<div style="display:none" id="textcontent_<?php echo $row->tempid;?>"><?php echo $row->altbody;?></div>
						<div style="display:none" id="subject_<?php echo $row->tempid;?>"><?php echo $row->subject;?></div>
						<div style="display:none" id="replyname_<?php echo $row->tempid;?>"><?php echo $row->replyname;?></div>
						<div style="display:none" id="replyemail_<?php echo $row->tempid;?>"><?php echo $row->replyemail;?></div>
						<div style="display:none" id="fromname_<?php echo $row->tempid;?>"><?php echo $row->fromname;?></div>
						<div style="display:none" id="fromemail_<?php echo $row->tempid;?>"><?php echo $row->fromemail;?></div>
				</div>
			<?php
			if($num%3 == 0){
				echo '<div clear="both"></div>';
			}
				}
			?>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="theme" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
