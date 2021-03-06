<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Bipin Thakur
 * @description	: 	Post / Edit project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.tooltip');
 $benefits       = $this->benefit;
 $plans          = $benefits->plans;
 $monthlies      = array($plans['s1'],$plans['g1'],$plans['p1']);
 $sixes          = array($plans['s2'],$plans['g2'],$plans['p2']);
 $years          = array($plans['s3'],$plans['g3'],$plans['p3']);
 $headers=$benefits->subHeaders;
 $headerValues=$benefits->getPlanListing()['headers'];
 $cvalues=$benefits->getPlanListing()['sub_values'];
 $planHeaders=$benefits->getPlanHeaders();
 $headerNames=$benefits->subHeaders;
 $phm=array('r','s1','g1','p1');
 $phs=array('r','s2','g2','p2');
 $phy=array('r','s3','g3','p3');
$user	= JFactory::getUser();
 ?>


<div class="dev_plans">
<div class="buttons">
			<a id="1month-button" href="#" class="one-month">1 month</a>
			<a id="6months-button" href="#" class="six-month">6 months</a>
			<a id="12months-button" href="#" class="one-year">1 year</a>
		</div>
<div class="dev_plan_headers">

<?php foreach($phm as $phk=>$phv): ?>
<div class="plan_<?php echo strtolower(str_replace ( ' ', '_' , $planHeaders[$phv]['planname'])); ?> plan_header">

<span class="plane"></span>
<span class="type"><?php echo $planHeaders[$phv]['heading']; ?></span>
<div class="price">

<?php if( $planHeaders[$phv]['price']==0 ){?>
<span>Free</span>
<?php } else { 
if($phk!=0){
$phmonthly=$planHeaders[$phv]['price'];

$phsixmonthly=$planHeaders[$phs[$phk]]['price']/12;
$sixOff=intval((($phmonthly-$phsixmonthly)/$phmonthly)*100);
$phyearly=$planHeaders[$phy[$phk]]['price']/12;
$yearOff=intval((($phmonthly-$phyearly)/$phmonthly)*100);
//if equal to 100 or greater dont show
}
?>

<span><sup><?php echo $planHeaders[$phv]['currencySymbol']; ?></sup><strong><span class="plan0 plani"><?php echo  $phmonthly;?></span><span class="plan1 plani" style="display:none;"  data-off="<?php echo round($sixOff,1); ?>"><?php echo round($phsixmonthly,1); ?></span><span class="plan2 plani" style="display:none;" data-off="<?php echo round($yearOff,1); ?>"><?php echo round($phyearly,1); ?></span></strong><span >/mo</span></span>


<?php } ?>



</div>
<span class="price-breakdown">
<?php echo $planHeaders[$phv]['short_desc'];

?>
</span>
<a href="<?php echo Juri::root()."index.php?option=com_jblance&task=membership.purchaseplan&id=".$planHeaders[$phv]['id']; ?>" class="<?php echo $planHeaders[$phv]['price']==0?"free":"paid";?> planselect"><?php echo $planHeaders[$phv]['price']==0?"Join":"Get Started";?></a>



</div>
<?php endforeach; ?>


</div>
<div class="dev_plans_benefits">
<div class="ben_sidebar">

<?php  foreach($headerValues as $hk=> $hv)
 {
 
 ?>
 
<div class="ben_sidebar_header"><?php echo $hk; ?></div>
 
 <?php foreach ($hv as $hvv)
 {?>

 <span class="ben_title"><?php echo $hvv->title; ?>
 <?php echo !empty($hvv->ben_desc)? '<span class="hasTip ben_desc" title="'.$hvv->ben_desc.'"><img src="'.JUri::root().'/images/qm.png" /></span>': ""; ?>
 </span>
 

<?php }

 } ?>
</div>

<div class="ben_center">

<?php
foreach($headers as $kh=>$kv)
{
?>
<div class="<?php echo $kh; ?>"><div class="<?php echo $kh; ?>-header header"></div>
<?php 
foreach($cvalues[$kh] as $cvk=>$cvv)
{
?>

<div class="ben_disp  <?php echo $cvk; ?>">
<?php 
foreach($cvv as $cvvv)
{
$state=$cvvv->state==0?'<img src="'.JUri::root().'images/not-avail.png"/>':'<img src="'.JUri::root().'images/avail.png"/>';

$custom=$cvvv->custom==''?'<img src="'.JUri::root().'images/not-avail.png"/>':$cvvv->custom;

$contDisp=$cvvv->type=="custom"?$custom:$state;
?>
<span class="ben_cont <?php echo "ben-".$cvk;  ?>"><?php echo $contDisp;?></span>
<?php
}
?>
</div>
<?php
}
?>
</div>
<?php 

}

 ?>
 </div>
 </div>

 <input type="hidden" id="monthlies" value="<?php echo implode(',', $monthlies);?>">
  <input type="hidden" id="sixes" value="<?php echo implode(',',$sixes);?>">
   <input type="hidden" id="years" value="<?php echo implode(',', $years);?>">
 </div>
 <script type="text/javascript">
 
 jQuery(function(){
var plansIds=[jQuery("#monthlies").val().split(','),jQuery("#sixes").val().split(','),jQuery("#years").val().split(',')];
jQuery(".buttons a").on('click',function(){
i=0;
var inDex=jQuery(this).index();
var ids=plansIds[inDex];
var paids=jQuery(".dev_plan_headers .paid");
paids.each(function(){
var link=jQuery(this).attr("href");
var numb = link.substring(link.lastIndexOf('=') + 1);
var newLink=link.replace(numb, ids[i] );
jQuery(this).attr("href",newLink)
i++
})
})
jQuery(".buttons a").eq(0).addClass("int_selected");
jQuery(".buttons a").on('click',function(){
jQuery(".buttons a").removeClass("int_selected");
jQuery(this).addClass("int_selected");
var intIndex=jQuery(this).index();
var price=jQuery(".price .plan"+intIndex);
var planInterval=jQuery(".price .plani").hide();
price.show();
})
var buttons  = jQuery(".buttons a");
var sixMonth = buttons.eq(1);
var yearly   = buttons.eq(2);
var plani    = jQuery(".plani");
var plani1   = parseInt(plani.eq(1).attr('data-off'));
var plani2   = parseInt(plani.eq(2).attr('data-off'));
var sixMontht= sixMonth.text();
var yearlyt = yearly.text();
if(plani1 > 0)
{
sixMonth.text(sixMontht+"( "+plani1+"% off )");
}
if(plani2 > 0)
{
yearly.text(yearlyt+"( "+plani2+"% off )")
}
})
 </script>
<style type="text/css">
.ben_title {
    border: 1px solid #BBB;
    display: block;
    padding: 10px;
	background-color: #EEEFEA;
}
.ben_sidebar {
    margin: 0 0 0 69px;
    width: 388px;
    float: left;
	font-weight: bold;
}
.ben_center
{
font-weight:bold;
}
.ben_sidebar_header
{
    background-color: #143A49;
    color: #FFF;
    font-weight: bold;
    padding: 6px;
}

.ben_center .header
{
background-color: #143A49;
height: 31px;
width: 828px;
}



.ben_center div
{
    
    width: 836px;
    float: left;
}
.ben_center .ben_disp
{
float: left;
width: 207px;
}
.ben_center .ben_cont
{
border: 1px solid #BBB;
border-left: none;
padding: 10px 28px;
background-color: #EEEFEA;
display:block;
    text-align: center;
}
.ben_center img
{
    width: 16px;
}
span.hasTip.ben_desc img {
    width: 15px;
}

span.hasTip.ben_desc {
    float: right;
}
.dev_plans_benefits {
    clear: both;
}

.plan_header {
   float: left;
    width: 195px;
    padding: 6px 1px 0 16px;
    background-color: #333;
    font-weight: bold;
    color: #FFF;
    margin: 0 12px 0 0;
    border-radius: 7px 7px 0px 0px;
	
}
.dev_plan_headers {
    float: right;
    margin: 0 8px 0 0;
}
a.planselect {
        display: block;
    background-color: #FFF;
    width: 86px;
    padding: 3px;
    border-radius: 8px;
    margin: 6px 0 7px 36px;
    color: #818181;
    text-align: center;
}
.plan_free_trial
{
background-color: #666666;
}
.buttons {
   
    text-align: right;
    padding: 16px 22px 19px 0;
}
.buttons a
{
    background-color: #FEFEFE;
    box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.2);
    padding: 10px;
    color: #666;
	}
	
	.int_selected{
	    background-color: #143A49 !important;
    color: #FFF !important;
	}
</style>