<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	helpers/jblance.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
require_once (JPATH_ROOT . '/components/com_jblance/defines.jblance.php');

/**
 * Jblance helper.
 */
function jbimport($path) {
    require_once(JPATH_ADMINISTRATOR . '/components/com_jblance/helpers/' . str_replace('.', '/', $path) . '.php');
}

class JblanceHelper {

    public static function get($path) {
        list($group, $class) = explode('.', $path);
        //include_once($class.'.php');
        include_once(JPATH_ADMINISTRATOR . '/components/com_jblance/helpers/' . $class . '.php');

        $className = ucfirst($class) . ucfirst($group);

        if (!class_exists($className))
            return null;
        return new $className();
    }

    function createThumbs($src_file, $thumb, $width = 200, $height = 200, $overwrite = true) {
        $dst_file = $thumb . DIRECTORY_SEPARATOR . basename($src_file);

        if ($overwrite) {
            $max_width = $width;
            $max_height = $height;

            list($width, $height, $image_type) = getimagesize($src_file);

            switch ($image_type) {
                case 1: $src = imagecreatefromgif($src_file);
                    break;
                case 2: $src = imagecreatefromjpeg($src_file);
                    break;
                case 3: $src = imagecreatefrompng($src_file);
                    break;
                default: return $src_file;
                    break;
            }

            $x_ratio = $max_width / $width;
            $y_ratio = $max_height / $height;

            if (($width <= $max_width) && ($height <= $max_height)) {
                $tn_width = $width;
                $tn_height = $height;
            } elseif (($x_ratio * $height) < $max_height) {
                $tn_height = ceil($x_ratio * $height);
                $tn_width = $max_width;
            } else {
                $tn_width = ceil($y_ratio * $width);
                $tn_height = $max_height;
            }

            $tmp = imagecreatetruecolor($tn_width, $tn_height);

            /* Check if this image is PNG or GIF to preserve its transparency */
            if (($image_type == 1) OR ( $image_type == 3)) {
                imagealphablending($tmp, false);
                imagesavealpha($tmp, true);
                $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
                imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
            }
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

            /*
             * imageXXX() has only two options, save as a file, or send to the browser.
             * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
             * So I start the output buffering, use imageXXX() to output the data stream to the browser,
             * get the contents of the stream, and use clean to silently discard the buffered contents.
             */
            imagejpeg($tmp, $dst_file, 85);
        }

        return $dst_file;
    }

    //create media player

    function getVideoPalyer($path, $elem, $width = 640, $height = 360) {
        $PlayerEmbed = '
<script src="' . JUri::root() . 'media/com_jblance/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="x21x3Y4NW44vSRplyqdPILUk/LBNh6XMS1sldQ==";</script>

<script type="text/javascript">
    jwplayer("' . $elem . '").setup({
   	sources: [{
            file: "' . $path . '"
        },],
		logo: {
		file: "images/logo.png",
		link: "http://www.appmeadows.com",
		
	},
	height: ' . $height . ',
	width: ' . $width . '
    });

    	jwplayer("' . $elem . '").onError ( function(event) {
			setTimeout(function()
			{
				jwplayer("' . $elem . '").play(true);
			},2000);
		}
	);

</script>';
<<<<<<< .mine
	
	
	
	return $PlayerEmbed;
	
	
	
	}
	
	//get braintree plan
	
	static  function getBraintreePlan($Btpid,$dbase=false)
	{
	$isPlan=array();
	if($dbase)
	{
	$db=JFactory::getDbo();
    $q = " SELECT    name,pidbt,merchantId,billingDayOfMonth,billingFrequency,currencyIsoCode,currencyIsoCode,bt_description,description,numberOfBillingCycles,price_bt,trialDuration,trialDurationUnit,trialPeriod,createdAt,updatedAt FROM #__jblance_plan WHERE pidbt='".$Btpid."'";
	
	$db->setQuery($q);
	
	$res = $db->loadObject();
	if(!empty($Btpid))
	{
	$isPlan = array(
	                     "id"=>$res->pidbt,
                         "merchantId"=>$res->merchantId,
                         "billingDayOfMonth"=>$res->billingDayOfMonth,
                         "billingFrequency"=>$res->billingFrequency,
                         "currencyIsoCode"=>$res->currencyIsoCode,
                         "description"=>$res->bt_description,
                         "name"=>explode("(",$res->name)[0],
                         "numberOfBillingCycles"=>$res->numberOfBillingCycles ,
                         "price"=>$res->price_bt,
                         "trialDuration"=>$res->trialDuration,
                         "trialDurationUnit"=>$res->trialDurationUnit ,
                         "trialPeriod"=>$res->trialPeriod ,
                         "createdAt"=>$res->createdAt,
					     "updatedAt"=>$res->updatedAt,
						 "name"     =>$res->name
                        
	               );
	}
	}
	else{
	self::getBtConfig();
	
	$plans = Braintree_Plan::all();
	
	$isPlan = array();
	foreach($plans as $value)
	{
	if( $value->id==$Btpid)
	{
	$isPlan =     array( 
	                     "id"=>$value->id,
                         "merchantId"=>$value->merchantId,
                         "billingDayOfMonth"=>$value->billingDayOfMonth,
                         "billingFrequency"=>$value->billingFrequency,
                         "currencyIsoCode"=>$value->currencyIsoCode,
                         "description"=>$value->description,
                         "name"=>explode("(",$value->name)[0],
                         "numberOfBillingCycles"=>$value->numberOfBillingCycles ,
                         "price"=>$value->price,
                         "trialDuration"=>$value->trialDuration,
                         "trialDurationUnit"=>$value->trialDurationUnit ,
                         "trialPeriod"=>$value->trialPeriod ,
                         "createdAt"=>$value->createdAt->format('Y:m:d h:i:s'),
					     "updatedAt"=>$value->updatedAt->format('Y:m:d h:i:s'),
                         "addOns"=>$value->addOns,
                         "discounts"=>$value->discounts,
					     "plans"=>$value->plans
						 );
	}
	
=======



        return $PlayerEmbed;
>>>>>>> .r50
    }

<<<<<<< .mine
	
	$custInfo['id']        = $customer->id;
	$custInfo['firstName'] = $customer->firstName;
	$custInfo['email']     = $customer->email;
	$custInfo['createdAt'] = $customer->createdAt->format('j F Y h:i:s A');
	$custInfo['updatedAt'] = $customer->updatedAt->format('j F Y h:i:s A');
	$card = $customer->creditCards[0];
	
	$custInfo['recent_creditcard']     = array(
	                                    "bin"                  =>   $card->bin,
                                        "expirationMonth"      =>   $card->expirationMonth,		
                                        "expirationYear"       =>   $card->expirationYear,
                                        "last4"                =>   $card->last4,
                                        "cardType"             =>   $card->cardType,
                                        "cardholderName"       =>   $card->cardholderName,
                                        "commercial"           =>   $card->commercial,
                                        "countryOfIssuance"    =>   $card->countryOfIssuance,
                                        "customerId"           =>   $card->customerId,
                                        "customerLocation"     =>   $card->customerLocation,
                                        "debit"                =>   $card->debit,
                                        "default"              =>   $card->default,
                                        "durbinRegulated"      =>   $card->durbinRegulated,
                                        "expired"              =>   $card->expired,
                                        "healthcare"           =>   $card->healthcare,
                                        "issuingBank"          =>   $card->issuingBank,
                                        "payroll"              =>   $card->payroll,
                                        "prepaid"              =>   $card->prepaid,
                                        "maskedNumber"         =>   $card->maskedNumber,
										"imageUrl"             =>   $card->imageUrl,
										"token"                =>   $card->token
	                                    );
										 ;
										 
	$subscriptions         = array_reverse($card->subscriptions);
	
    $subscription         = $subscriptions[0];
	
	/* echo"<pre>";
	print_r($subscription);
	die; */
	
	
	
    $custInfo['recent_subscription'] = array(
	
	                    "balance"                =>  $subscription->balance,
						"billingDayOfMonth"      =>  $subscription->billingDayOfMonth,
                        "billingPeriodEndDate"   =>  $subscription->billingPeriodEndDate->format('j F Y h:i:s A'), 
                        "billingPeriodStartDate" =>  $subscription->billingPeriodStartDate->format('j F Y h:i:s A'), 
                        "createdAt"              =>  $subscription->createdAt->format('j F Y h:i:s A'), 
                        "updatedAt"              =>  $subscription->updatedAt->format('j F Y h:i:s A'), 
                        "currentBillingCycle"    =>  $subscription->currentBillingCycle, 
                        "daysPastDue"            =>  $subscription->daysPastDue, 
                        "failureCount"           =>  $subscription->failureCount, 
                        "firstBillingDate"       =>  $subscription->firstBillingDate->format('j F Y h:i:s A'), 
                        "id"                     =>  $subscription->id, 
                        "merchantAccountId"      =>  $subscription->merchantAccountId, 
                        "neverExpires"           =>  $subscription->neverExpires, 
                        "nextBillAmount"         =>  $subscription->nextBillAmount, 
                        "nextBillingPeriodAmount"  =>  $subscription->nextBillingPeriodAmount, 
                        "nextBillingDate"          =>  $subscription->nextBillingDate->format('j F Y h:i:s A'), 
                        "numberOfBillingCycles"    =>  $subscription->numberOfBillingCycles, 
                        "paidThroughDate"          =>  $subscription->paidThroughDate->format('j F Y h:i:s A'), 
                        "paymentMethodToken"       =>  $subscription->paymentMethodToken, 
                        "planId"                   =>  $subscription->planId, 
                        "price"                    =>  $subscription->price, 
                        "status"                   =>  $subscription->status, 
                        "trialDuration"            =>  $subscription->trialDuration,
						"trialDurationUnit"        =>  $subscription->trialDurationUnit,
						"trialPeriod"              =>  $subscription->trialPeriod
                                                );
=======
    //get braintree plan
>>>>>>> .r50

    static function getBraintreePlan($Btpid, $dbase = false) {
        $isPlan = array();
        if ($dbase) {
            $db = JFactory::getDbo();
            $q = " SELECT    name,pidbt,merchantId,billingDayOfMonth,billingFrequency,currencyIsoCode,currencyIsoCode,bt_description,description,numberOfBillingCycles,price_bt,trialDuration,trialDurationUnit,trialPeriod,createdAt,updatedAt FROM #__jblance_plan WHERE pidbt='" . $Btpid . "'";

            $db->setQuery($q);

            $res = $db->loadObject();
            if (!empty($Btpid)) {
                $isPlan = array(
                    "id" => $res->pidbt,
                    "merchantId" => $res->merchantId,
                    "billingDayOfMonth" => $res->billingDayOfMonth,
                    "billingFrequency" => $res->billingFrequency,
                    "currencyIsoCode" => $res->currencyIsoCode,
                    "description" => $res->bt_description,
                    "name" => $res->name,
                    "numberOfBillingCycles" => $res->numberOfBillingCycles,
                    "price" => $res->price_bt,
                    "trialDuration" => $res->trialDuration,
                    "trialDurationUnit" => $res->trialDurationUnit,
                    "trialPeriod" => $res->trialPeriod,
                    "createdAt" => $res->createdAt,
                    "updatedAt" => $res->updatedAt
                );
            }
        } else {
            self::getBtConfig();

            $plans = Braintree_Plan::all();

            $isPlan = array();
            foreach ($plans as $value) {
                if ($value->id == $Btpid) {
                    $isPlan = array(
                        "id" => $value->id,
                        "merchantId" => $value->merchantId,
                        "billingDayOfMonth" => $value->billingDayOfMonth,
                        "billingFrequency" => $value->billingFrequency,
                        "currencyIsoCode" => $value->currencyIsoCode,
                        "description" => $value->description,
                        "name" => $value->name,
                        "numberOfBillingCycles" => $value->numberOfBillingCycles,
                        "price" => $value->price,
                        "trialDuration" => $value->trialDuration,
                        "trialDurationUnit" => $value->trialDurationUnit,
                        "trialPeriod" => $value->trialPeriod,
                        "createdAt" => $value->createdAt->format('Y:m:d h:i:s'),
                        "updatedAt" => $value->updatedAt->format('Y:m:d h:i:s'),
                        "addOns" => $value->addOns,
                        "discounts" => $value->discounts,
                        "plans" => $value->plans
                    );
                }
            }
        }
        return $isPlan;
    }

<<<<<<< .mine
	
	$custInfo['id']        = $customer->id;
	$custInfo['firstName'] = $customer->firstName;
	$custInfo['email']     = $customer->email;
	$custInfo['createdAt'] = $customer->createdAt->format('j F Y h:i:s A');
	$custInfo['updatedAt'] = $customer->updatedAt->format('j F Y h:i:s A');
	$cards = $customer->creditCards[0];
	
	$custInfo['recent_creditcards'] = array("cardType"=>$cards->cardType,"expirationDate"=>$cards->expirationDate,"maskedNumber"=>$cards->maskedNumber,"last4"=>$cards->last4,"customerLocation"=>$cards->customerLocation,"issuingBank"=>$cards->issuingBank,"expired"=>$cards->expired,"imageUrl"=>$cards->imageUrl);
										 
	$subscriptions                   = array_reverse($cards->subscriptions);		
  //only first ten records are needed
  
    $subscriptions                  = array_slice($subscriptions,0,10);
	
	$custInfo['recent_subscriptions'] = $subscriptions;
		
=======
    public static function getBtCustomer($cid, $bt = false) {
        if ($bt) {
            return self::getBtCustomerBt($cid);
        } else {
            $custInfo = array();
            $user = JFactory::getUser($cid);
            $db = JFactory::getDbo();
            $q = "SELECT * FROM #__jblance_plan_subscr INNER JOIN #__jblance_plan ON #__jblance_plan_subscr.plan_id = #__jblance_plan.id  WHERE user_id='" . $cid . "'";
            $db->setQuery($q);
            $customer = $db->loadObject();

            if (!empty($customer)) {
                $custInfo['id'] = $user->id;
                $custInfo['username'] = $user->username;
                $custInfo['email'] = $user->email;
>>>>>>> .r50
<<<<<<< .mine
	}
	catch(Exception $e)
	                 {
										
	                 }
					 
	                 return $custInfo;
	}
	
	
	
	public static function hasJBProfile($userid){
		$db = JFactory::getDbo();
		
		//double check if user id > 0
		if($userid > 0){
			$query = "SELECT u.id FROM #__jblance_user u ".
					 "WHERE u.user_id = ".$db->quote($userid);
			$db->setQuery($query);
		
			if($db->loadResult())
				return 1;
			else
				return 0;
			}
		else {
			return 0;
		}
	}
	
	/*Available params:  progress-bar-success,progress-bar-info,progress-bar-warning,progress-bar-danger*/
	
		public static function getProfileCompleted($userid)
		{
		$db=JFactory::getDbo();
		
		$query="SELECT completed FROM #__jblance_profilecompleted WHERE uid=".$userid;
		$db->setQuery($query);
		$res=$db->loadObject();
		$return=array();
		
		$completed=$res->completed<=100?$res->completed:100;
		$return[0]=$completed;
		switch($completed)
		{
		case $completed<50:
		$attributes="progress-bar-danger";
		break;
		
		case $completed>50 && $completed <75:
		$attributes="progress-bar-warning";
		break;
		
		case $completed>75 && $completed <100:
		$attributes="progress-bar-info";
		break;
		
		case $completed==100:
		$attributes="progress-bar-success";
		break;
		}
		
		
		$progress=$completed.'%';
		$return[1]='<div id="dev_comp_progress" class="progress">
                    <div class="progress-bar '.$attributes.'" role="progressbar" aria-valuenow="'.$completed.'"
=======

                $custInfo['recent_creditcard'] = array(
                    "token" => $customer->token,
                    "bin" => $customer->bin,
                    "last4" => $customer->last4,
                    "cardType" => $customer->cardType,
                    "expirationDate" => $customer->expirationDate,
                    "customerLocation" => $customer->customerLocation,
                    "cardholderName" => $customer->cardholderName,
                    "imageUrl" => $customer->imageUrl,
                    "prepaid" => $customer->prepaid,
                    "healthcare" => $customer->healthcare,
                    "debit" => $customer->debit,
                    "durbinRegulated" => $customer->durbinRegulated,
                    "commercial" => $customer->commercial,
                    "payroll" => $customer->payroll,
                    "issuingBank" => $customer->issuingBank,
                    "countryOfIssuance" => $customer->countryOfIssuance,
                    "productId" => $customer->productId,
                    "uniqueNumberIdentifier" => $customer->uniqueNumberIdentifier,
                    "maskedNumber" => $customer->maskedNumber
                );





                $custInfo['recent_subscription'] = array(
                    "id" => $customer->id,
                    "name" => $customer->name,
                    "user_id" => $customer->user_id,
                    "plan_id" => $customer->planId,
                    "subscriptionId" => $customer->subscriptionId,
                    "approved" => $customer->approved,
                    "price" => $customer->price,
                    "tax_percent" => $customer->tax_percent,
                    "access_count" => $customer->access_count,
                    "gateway" => $customer->gateway,
                    "gateway_id" => $customer->gateway_id,
                    "trans_id" => $customer->trans_id,
                    "fund" => $customer->fund,
                    "date_buy" => $customer->date_buy,
                    "date_approval" => $customer->date_approval,
                    "date_expire" => $customer->date_expire,
                    "invoiceNo" => $customer->invoiceNo,
                    "lifetime" => $customer->lifetime,
                    "bids_allowed" => $customer->bids_allowed,
                    "bids_left" => $customer->bids_left,
                    "projects_allowed" => $customer->projects_allowed,
                    "projects_left" => $customer->projects_left,
                    "billing_day" => $customer->billing_day,
                    "createdAt" => $customer->createdAt,
                    "updatedAt" => $customer->updatedAt,
                    "currentBillingCycle" => $customer->currentBillingCycle,
                    "status" => $customer->status
                );
            }
        } return $custInfo;
    }

    //customer from braintree

    public static function getBtCustomerBt($cid) {

        self::getBtConfig();
        $custInfo = array();

        try {
            $customer = Braintree_Customer::find($cid);


            $custInfo['id'] = $customer->id;
            $custInfo['firstName'] = $customer->firstName;
            $custInfo['email'] = $customer->email;
            $custInfo['createdAt'] = $customer->createdAt->format('j F Y h:i:s A');
            $custInfo['updatedAt'] = $customer->updatedAt->format('j F Y h:i:s A');
            $card = $customer->creditCards[0];

            $custInfo['recent_creditcard'] = array(
                "bin" => $card->bin,
                "expirationMonth" => $card->expirationMonth,
                "expirationYear" => $card->expirationYear,
                "last4" => $card->last4,
                "cardType" => $card->cardType,
                "cardholderName" => $card->cardholderName,
                "commercial" => $card->commercial,
                "countryOfIssuance" => $card->countryOfIssuance,
                "customerId" => $card->customerId,
                "customerLocation" => $card->customerLocation,
                "debit" => $card->debit,
                "default" => $card->default,
                "durbinRegulated" => $card->durbinRegulated,
                "expired" => $card->expired,
                "healthcare" => $card->healthcare,
                "issuingBank" => $card->issuingBank,
                "payroll" => $card->payroll,
                "prepaid" => $card->prepaid,
                "maskedNumber" => $card->maskedNumber,
                "imageUrl" => $card->imageUrl
            );
            ;

            $subscriptions = array_reverse($card->subscriptions);

            $subscription = $subscriptions[0];

            /* echo"<pre>";
              print_r($subscription);
              die; */



            $custInfo['recent_subscription'] = array(
                "balance" => $subscription->balance,
                "billingDayOfMonth" => $subscription->billingDayOfMonth,
                "billingPeriodEndDate" => $subscription->billingPeriodEndDate->format('j F Y h:i:s A'),
                "billingPeriodStartDate" => $subscription->billingPeriodStartDate->format('j F Y h:i:s A'),
                "createdAt" => $subscription->createdAt->format('j F Y h:i:s A'),
                "updatedAt" => $subscription->updatedAt->format('j F Y h:i:s A'),
                "currentBillingCycle" => $subscription->currentBillingCycle,
                "daysPastDue" => $subscription->daysPastDue,
                "failureCount" => $subscription->failureCount,
                "firstBillingDate" => $subscription->firstBillingDate->format('j F Y h:i:s A'),
                "id" => $subscription->id,
                "merchantAccountId" => $subscription->merchantAccountId,
                "neverExpires" => $subscription->neverExpires,
                "nextBillAmount" => $subscription->nextBillAmount,
                "nextBillingPeriodAmount" => $subscription->nextBillingPeriodAmount,
                "nextBillingDate" => $subscription->nextBillingDate->format('j F Y h:i:s A'),
                "numberOfBillingCycles" => $subscription->numberOfBillingCycles,
                "paidThroughDate" => $subscription->paidThroughDate->format('j F Y h:i:s A'),
                "paymentMethodToken" => $subscription->paymentMethodToken,
                "planId" => $subscription->planId,
                "price" => $subscription->price,
                "status" => $subscription->status,
                "trialDuration" => $subscription->trialDuration,
                "trialDurationUnit" => $subscription->trialDurationUnit,
                "trialPeriod" => $subscription->trialPeriod
            );
        } catch (Exception $e) {
            
        }

        return $custInfo;
    }

    //full customer

    public static function getBtFullCustomer($cid) {
        self::getBtConfig();
        $custInfo = array();

        try {
            $customer = Braintree_Customer::find($cid);


            $custInfo['id'] = $customer->id;
            $custInfo['firstName'] = $customer->firstName;
            $custInfo['email'] = $customer->email;
            $custInfo['createdAt'] = $customer->createdAt->format('j F Y h:i:s A');
            $custInfo['updatedAt'] = $customer->updatedAt->format('j F Y h:i:s A');
            $cards = $customer->creditCards;
            foreach ($cards as $cd => $card) {
                $custInfo['recent_creditcards'][$cd] = array(
                    "bin" => $card->bin,
                    "expirationMonth" => $card->expirationMonth,
                    "expirationYear" => $card->expirationYear,
                    "last4" => $card->last4,
                    "cardType" => $card->cardType,
                    "cardholderName" => $card->cardholderName,
                    "commercial" => $card->commercial,
                    "countryOfIssuance" => $card->countryOfIssuance,
                    "customerId" => $card->customerId,
                    "customerLocation" => $card->customerLocation,
                    "debit" => $card->debit,
                    "default" => $card->default,
                    "durbinRegulated" => $card->durbinRegulated,
                    "expired" => $card->expired,
                    "healthcare" => $card->healthcare,
                    "imageUrl" => $card->imageUrl,
                    "issuingBank" => $card->issuingBank,
                    "payroll" => $card->payroll,
                    "prepaid" => $card->prepaid
                );
            }

            $subscriptions = array_reverse($card->subscriptions);

            foreach ($subscriptions as $sub => $subscription) {
                $plan = self::getBraintreePlan($subscription->planId);

                $name = $plan['name'];
                $name = explode("(", $name);
                $name = $name[0];


                $custInfo['recent_subscriptions'][$sub] = array(
                    'balance' => $subscription->balance,
                    'billingDayOfMonth' => $subscription->billingDayOfMonth,
                    'billingPeriodEndDate' => $subscription->billingPeriodEndDate->format('j F Y h:i:s A'),
                    'billingPeriodStartDate' => $subscription->billingPeriodStartDate->format('j F Y h:i:s A'),
                    'createdAt' => $subscription->createdAt->format('j F Y h:i:s A'),
                    'updatedAt' => $subscription->updatedAt->format('j F Y h:i:s A'),
                    'currentBillingCycle' => $subscription->currentBillingCycle,
                    'daysPastDue' => $subscription->daysPastDue,
                    'failureCount' => $subscription->failureCount,
                    'firstBillingDate' => $subscription->firstBillingDate->format('j F Y h:i:s A'),
                    'id' => $subscription->id,
                    'merchantAccountId' => $subscription->merchantAccountId,
                    'neverExpires' => $subscription->neverExpires,
                    'nextBillAmount' => $subscription->nextBillAmount,
                    'nextBillingPeriodAmount' => $subscription->nextBillingPeriodAmount,
                    'nextBillingDate' => $subscription->nextBillingDate->format('j F Y h:i:s A'),
                    'numberOfBillingCycles' => $subscription->numberOfBillingCycles,
                    'paidThroughDate' => $subscription->paidThroughDate->format('j F Y h:i:s A'),
                    'paymentMethodToken' => $subscription->paymentMethodToken,
                    'planId' => $subscription->planId,
                    'price' => $subscription->price,
                    'status' => $subscription->status,
                    'trialDuration' => $subscription->trialDuration,
                    'trialDurationUnit' => $subscription->trialDurationUnit,
                    'trialPeriod' => $subscription->trialPeriod,
                    'name' => $name
                );


                $Subtransactions = $subscription->transactions;

                foreach ($Subtransactions as $trk => $trv) {
                    $custInfo['recent_subscriptions'][$sub]['transactions'][$trk] = array(
                        'id' => $trv->id,
                        'status' => $trv->status,
                        'type' => $trv->type,
                        'amount' => $trv->amount,
                        'createdAt' => $trv->createdAt->format('j F Y h:i:s A'),
                        'creditCard' => array(
                            'token' => $trv->creditCard['token'],
                            'bin' => $trv->creditCard['bin'],
                            'last4' => $trv->creditCard['last4'],
                            'cardType' => $trv->creditCard['cardType'],
                            'expirationMonth' => $trv->creditCard['expirationMonth'],
                            'expirationYear' => $trv->creditCard['expirationYear'],
                            'customerLocation' => $trv->creditCard['customerLocation'],
                            'cardholderName' => $trv->creditCard['cardholderName'],
                            'imageUrl' => $trv->creditCard['imageUrl'],
                            'prepaid' => $trv->creditCard['prepaid'],
                            'healthcare' => $trv->creditCard['healthcare'],
                            'debit' => $trv->creditCard['debit'],
                            'durbinRegulated' => $trv->creditCard['durbinRegulated'],
                            'commercial' => $trv->creditCard['commercial'],
                            'payroll' => $trv->creditCard['payroll'],
                            'issuingBank' => $trv->creditCard['issuingBank'],
                            'countryOfIssuance' => $trv->creditCard['countryOfIssuance'],
                            'productId' => $trv->creditCard['productId'],
                            'uniqueNumberIdentifier' => $trv->creditCard['uniqueNumberIdentifier'],
                            'venmoSdk' => $trv->creditCard ['venmoSdk']
                        )
                    );
                }
            }

            $transactions = $subscription->transactions;
            foreach ($transactions as $trans => $transaction) {
                $custInfo['transactions'][$trans] = array(
                    'id' => $transaction->id,
                    'status' => $transaction->status,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'createdAt' => $transaction->createdAt->format('j F Y h:i:s A')
                );
            }
        } catch (Exception $e) {
            
        }
        return $custInfo;
    }

    public static function hasJBProfile($userid) {
        $db = JFactory::getDbo();

        //double check if user id > 0
        if ($userid > 0) {
            $query = "SELECT u.id FROM #__jblance_user u " .
                    "WHERE u.user_id = " . $db->quote($userid);
            $db->setQuery($query);

            if ($db->loadResult())
                return 1;
            else
                return 0;
        }
        else {
            return 0;
        }
    }

    /* Available params:  progress-bar-success,progress-bar-info,progress-bar-warning,progress-bar-danger */

    public static function getProfileCompleted($userid) {
        $db = JFactory::getDbo();

        $query = "SELECT completed FROM #__jblance_profilecompleted WHERE uid=" . $userid;
        $db->setQuery($query);
        $res = $db->loadObject();
        $return = array();

        $completed = $res->completed <= 100 ? $res->completed : 100;
        $return[0] = $completed;
        switch ($completed) {
            case $completed < 50:
                $attributes = "progress-bar-danger";
                break;

            case $completed > 50 && $completed < 75:
                $attributes = "progress-bar-warning";
                break;

            case $completed > 75 && $completed < 100:
                $attributes = "progress-bar-info";
                break;

            case $completed == 100:
                $attributes = "progress-bar-success";
                break;
        }


        $progress = $completed . '%';
        $return[1] = '<div id="dev_comp_progress" class="progress">
                    <div class="progress-bar ' . $attributes . '" role="progressbar" aria-valuenow="' . $completed . '"
>>>>>>> .r50
                    aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    ' . $progress . '
                   </div>
                   </div>';

        return $return;
    }

    public static function getConfig() {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_jblance/tables');
        $config = JTable::getInstance('config', 'Table');
        $config->load(1);

        // Convert the params field to an array.
        $registry = new JRegistry;
        $registry->loadString($config->params);
        $params = $registry->toObject();
        return $params;
    }

    public static function getLogo($userid, $att = '') {

        $avatars = self::getAvatarIntegration();


        return $avatars->getLink($userid, $att, 'picture');
        /* $db = JFactory::getDbo();

          //get the JoomBri picture
          $query = "SELECT picture FROM #__jblance_user WHERE user_id=".$db->quote($userid);
          $db->setQuery($query);
          $jbpic = $db->loadResult();

          $imgpath = JBPROFILE_PIC_PATH.'/'.$jbpic;
          $imgurl = JBPROFILE_PIC_URL.$jbpic.'?'.time();

          if(JFile::exists($imgpath)){
          return "<img src=$imgurl $att alt='img'>";
          }
          elseif($userid){
          $imgurl = JURI::root().'components/com_jblance/images/nophoto_big.png';
          return "<img src=$imgurl $att alt='img'>";
          } */
    }

    public static function getThumbnail($userid, $att = '') {
        $avatars = self::getAvatarIntegration();
        return $avatars->getLink($userid, $att, 'thumb');
    }

    //return true if the user group id is set to free mode.
    public static function isFreeMode($ugid) {
        $db = JFactory::getDbo();
        $query = "SELECT freeMode FROM `#__jblance_usergroup` WHERE id=" . $db->quote($ugid);
        $db->setQuery($query);
        $freeMode = $db->loadResult();
        return $freeMode;
    }

//return true if the plan id is set 
    public static function getPlanPrice($id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM `#__jblance_plan` WHERE id=" . $db->quote($id);
        $db->setQuery($query);
        $planInfo = $db->loadObject();
        return $planInfo;
    }

    public static function getGwayName($gwCode) {

        if ($gwCode != 'byadmin') {
            $db = JFactory::getDbo();
            $query = "SELECT gateway_name FROM #__jblance_paymode WHERE gwcode=" . $db->quote($gwCode);
            $db->setQuery($query);
            $gwayName = $db->loadResult();
        } else
            $gwayName = 'By Admin';

        return $gwayName;
    }

    public static function getPaymodeInfo($gwCode) {


        if ($gwCode != 'byadmin') {
            $db = JFactory::getDbo();
            $query = "SELECT * FROM #__jblance_paymode WHERE gwcode=" . $db->quote($gwCode);
            $db->setQuery($query);
            $config = $db->loadObject();

            //convert the params to object
            $registry = new JRegistry;
            $registry->loadString($config->params);
            $params = $registry->toObject();

            //bind the $params object to $plan and make one object
            foreach ($params as $k => $v) {
                $config->$k = $v;
            }

            return $config;
        } else
            return 'By Admin';
    }

    /**
     * Update the transaction of the users to the transaction table (#__jblance_transaction)
     * @param int $userid
     * @param string $transDtl
     * @param int $amount
     * @param int $plusMinus
     */
    public static function updateTransaction($userid, $transDtl, $amount, $plusMinus) {
        $app = JFactory::getApplication();
        $now = JFactory::getDate();
        //Insert the transaction into the transaction table in case the amount is greater than zero
        if ($amount > 0) {
            $row_trans = JTable::getInstance('transaction', 'Table');
            $row_trans->date_trans = $now->toSql();
            $row_trans->transaction = $transDtl;
            $row_trans->user_id = $userid;

            if ($plusMinus == 1)
                $row_trans->fund_plus = $amount;
            elseif ($plusMinus == -1)
                $row_trans->fund_minus = $amount;

            // pre-save checks
            if (!$row_trans->check()) {
                JError::raiseError(500, $row_trans->getError());
            }
            if (!$row_trans->store()) {
                JError::raiseError(500, $row_trans->getError());
            }
            $row_trans->checkin();
            return $row_trans;
        }
    }

    public static function getPaymentStatus($approved) {

        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        if ($approved == 0)
            $status = '<span class="label label-warning">' . JText::_('COM_JBLANCE_PAYMENT_PENDING') . '</span>';
        elseif ($approved == 1)
            $status = '<span class="label label-success">' . JText::_('COM_JBLANCE_COMPLETED') . '</span>';
        elseif ($approved == 2)
            $status = '<span class="label label-important">' . JText::_('COM_JBLANCE_CANCELLED') . '</span>';
        return $status;
    }

    public static function getEscrowPaymentStatus($status) {
        //$lang = JFactory::getLanguage();
        //$lang->load('com_jblance', JPATH_SITE);
        $html = '';
        if ($status == '' || empty($status))
            $html = '<span class="label label-warning">' . JText::_('COM_JBLANCE_PENDING') . '</span>';
        elseif ($status == 'COM_JBLANCE_RELEASED')
            $html = '<span class="label label-info">' . JText::_($status) . '</span>';
        elseif ($status == 'COM_JBLANCE_ACCEPTED')
            $html = '<span class="label label-success">' . JText::_($status) . '</span>';
        elseif ($status == 'COM_JBLANCE_CANCELLED')
            $html = '<span class="label label-important">' . JText::_($status) . '</span>';
        return $html;
    }

    public static function getApproveStatus($approved) {
        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        if ($approved == 0)
            $status = '<span class="label label-warning">' . JText::_('COM_JBLANCE_PENDING') . '</span>';
        elseif ($approved == 1)
            $status = '<span class="label label-success">' . JText::_('COM_JBLANCE_APPROVED') . '</span>';
        return $status;
    }

    //get the total available fund of the user
    public static function getTotalFund($userid) {
        $user = JFactory::getUser();
        $userid = $user->id;
        $totalPoints = 0;
        if (!class_exists("AlphaUserPointsHelper")) {
            $api_AUP = JPATH_SITE . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'helper.php';
            if (file_exists($api_AUP)) {
                require_once ($api_AUP);
                $totalPoints = AlphaUserPointsHelper::getCurrentTotalPoints('', $userid);
            }
        }
//echo 'xxxx<pre>'; print_r($totalPoints); die;
//        $db = JFactory::getDbo();
//        $total_fund = $total_withdraw = 0;
//        $query = "SELECT (SUM(fund_plus)-SUM(fund_minus)) FROM #__jblance_transaction WHERE user_id = " . $db->quote($userid);
//        $db->setQuery($query);
//        $total_fund = $db->loadResult();
//        $total_fund = empty($total_fund) ? 0 : $total_fund;
//
//        //check if the user has withdraw request. If any, reduce the amount from the total fund so that the request fund is locked
//        $query = "SELECT SUM(amount) FROM #__jblance_withdraw WHERE approved=0 AND user_id = " . $db->quote($userid);
//        $db->setQuery($query);
//        $total_withdraw = $db->loadResult();
//        $total_withdraw = empty($total_withdraw) ? 0 : $total_withdraw;
//
//        $total_available_fund = $total_fund - $total_withdraw;

        return $totalPoints;
    }

    //check withdraw request for a user
    public static function getWithdrawRequest($userId) {
        $db = JFactory::getDbo();
        $query = "SELECT SUM(amount) FROM #__jblance_withdraw WHERE approved=0 AND user_id = " . $db->quote($userId);
        $db->setQuery($query);
        $total_withdraw = $db->loadResult();
        $total_withdraw = empty($total_withdraw) ? 0 : $total_withdraw;
        return $total_withdraw;
    }

    public static function isAuthenticated($userid, $layout) {
        $app = JFactory::getApplication();
        $config = JblanceHelper::getConfig();
        $guestReporting = $config->enableGuestReporting;
        $profilePublic = $config->profilePublic;

        $noLoginLayouts = array('planadd', 'check_out', 'bank_transfer', 'listproject', 'detailproject', 'searchproject', 'userlist', 'viewservice', 'listservice', 'editprojectcustom', 'plans'); //these are the layouts that doesn't require login
        //if the guest reporting is enabled, then set the report layout to nologin layouts
        if ($guestReporting)
            $noLoginLayouts[] = 'report';

        //if profile is set to visible to public, then add it to no login layout
        if ($profilePublic) {
            $noLoginLayouts[] = 'viewprofile';
            $noLoginLayouts[] = 'viewportfolio';
        }

        if (in_array($layout, $noLoginLayouts)) {
            return true;
        }

        //if the user is not logged in
        if ($userid == 0) {
            //return to same page after login
            $returnUrl = JFactory::getURI()->toString();
            $msg = JText::_('COM_JBLANCE_MUST_BE_LOGGED_IN_TO_ACCESS_THIS_PAGE');
            $link_login = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($returnUrl), false);
            $app->enqueueMessage($msg, 'warning');
            $app->redirect($link_login);
        }
        if (self::hasJBProfile($userid)) {
            //check if the user is authorized to do an action/section
            $isAuthorized = self::isAuthorized($userid, $layout);
            if (!$isAuthorized) {
                $msg = JText::_('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE');
                $return = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
                $app->enqueueMessage($msg, 'warning');
                $app->redirect($return);
            }
        } else {
            $msg = JText::_('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE_CHOOSE_YOUR_ROLE');
            $return = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
            $app->enqueueMessage($msg, 'warning');
            $app->redirect($return);
        }
    }

    public static function isAuthorized($userid, $layout) {
        $jbuser = self::get('helper.user');
        $ugInfo = $jbuser->getUserGroupInfo($userid, null);

        //get the array of layouts the current user is not authorized
        $denied = self::deniedLayouts($userid);

        if (in_array($layout, $denied))
            return 0; //denied
        else
            return 1; //allowed
    }

    public static function deniedLayouts($userid) {
        $jbuser = self::get('helper.user');
        $ugInfo = $jbuser->getUserGroupInfo($userid, null);
        $config = JblanceHelper::getConfig();
        $enableEscrowPayment = $config->enableEscrowPayment;
        $enableWithdrawFund = $config->enableWithdrawFund;
        $deniedLayouts = array();

        /* //if the user group is in free-Mode, then set the following layouts as denied ones
          if(JBLANCE_FREE_MODE){
          $deniedLayouts[] = 'buycredit';
          $deniedLayouts[] = 'planadd';
          $deniedLayouts[] = 'planhistory';
          $deniedLayouts[] = 'showcredit';
          } */
        //check if escrow payment is enabled
        if (!$enableEscrowPayment) {
            $deniedLayouts[] = 'escrow';
        }
        //check if fund withdraw is enabled
        if (!$enableWithdrawFund) {
            $deniedLayouts[] = 'withdrawfund';
        }

        //get the array of layouts the current user is not authorized
        if (!$ugInfo->allowPostProjects) {   //following layouts are denied for freelancers
            $deniedLayouts[] = 'showmyproject';
            $deniedLayouts[] = 'editproject';
            $deniedLayouts[] = 'pickuser';
            $deniedLayouts[] = 'servicebought';
        }
        if (!$ugInfo->allowBidProjects) {    //following layouts are denied for buyers
            $deniedLayouts[] = 'showmybid';
            $deniedLayouts[] = 'placebid';
            $deniedLayouts[] = 'myservice';
            $deniedLayouts[] = 'editservice';
            $deniedLayouts[] = 'servicesold';
        }
        if (!$ugInfo->allowAddPortfolio) {
            $deniedLayouts[] = 'editportfolio';
        }

        return $deniedLayouts;
    }

    public static function getUserType($user_id) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser($user_id);
        $userType = new stdClass();
        $userType->buyer = false;
        $userType->freelancer = false;
        $userType->guest = false;
        $userType->joomlauser = false;  //this means the user is only a Joomla user and doesn't have JoomBri Profile

        if ($user->guest) {
            $userType->guest = true;
            return $userType;
        } else {
            if (!self::hasJBProfile($user_id)) {
                $userType->joomlauser = true;
                return $userType;
            }
        }

        $query = "SELECT ug.id,ug.name,ug.approval,ug.params FROM #__jblance_user u
				  LEFT JOIN #__jblance_usergroup ug ON u.ug_id = ug.id
				  WHERE u.user_id = " . $db->quote($user_id) . " AND ug.published=1"; //echo $query;
        $db->setQuery($query);
        $userGroup = $db->loadObject();

        //convert the params to object
        $registry = new JRegistry;
        $registry->loadString($userGroup->params);
        $params = $registry->toObject();

        if ($params->allowPostProjects)
            $userType->buyer = true;

        if ($params->allowBidProjects)
            $userType->freelancer = true;

        return $userType;
    }

    public static function getCategoryNames($id_categs) {
        $db = JFactory::getDbo();
        $query = "SELECT category,id FROM #__jblance_category c WHERE c.id IN ($id_categs)";
        $db->setQuery($query);
        $cats = $db->loadColumn();
        if ($cats)
            return implode($cats, ", ");
        else
            return '';
    }

    public static function getLocationNames($id_loc) {
        $db = JFactory::getDbo();
        $query = "SELECT parent.title FROM #__jblance_location AS node " .
                "LEFT JOIN #__jblance_location AS parent ON node.lft BETWEEN parent.lft AND parent.rgt " .
                "WHERE node.id = " . $db->quote($id_loc) . " AND parent.extension='' " .
                "ORDER BY node.lft";
        $db->setQuery($query);
        $locs = $db->loadColumn();
        if ($locs)
            return implode($locs, " &raquo; ");
        else
            return JText::_('COM_JBLANCE_NOT_MENTIONED');
    }

    public static function getProjectDuration($id_duration) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_duration d WHERE d.id=" . $db->quote($id_duration);
        $db->setQuery($query);
        $duration = $db->loadObject();

        $format = self::formatProjectDuration($duration->duration_from, $duration->duration_from_type, $duration->duration_to, $duration->duration_to_type, $duration->less_great);
        return $format;
    }

    /**
     * Method that returns the average rating of the user
     * @param int $userid	id of the user
     * @param boolean $html return html or not
     * @return mixed
     */
    public static function getAvarageRate($userid, $html = true) {

        $db = JFactory::getDbo();
        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        //get the average rating value
        $query = "SELECT AVG((quality_clarity+communicate+expertise_payment+professional+hire_work_again)/5) AS rating FROM #__jblance_rating " .
                "WHERE target=" . $db->quote($userid) . " AND quality_clarity <> 0";
        $db->setQuery($query);
        $avg = $db->loadResult();
        $avg = round($avg, 2);

        //get the no of rating the user has received
        $query = "SELECT COUNT(*) AS count FROM #__jblance_rating " .
                "WHERE target=" . $db->quote($userid) . " AND quality_clarity <> 0";
        $db->setQuery($query);
        $count = $db->loadResult();

        if ($html == false) {
            return $avg;
        } else {
            JHtml::_('bootstrap.tooltip');
            ?>
            <?php $tip = JHtml::tooltipText(JText::sprintf('COM_JBLANCE_RATING_VALUE_TOOLTIP', $avg)); ?>
            <span class="label label-warning" style="vertical-align: top;"><?php echo $avg; ?></span>
            <span class="rating_bar hasTooltip" title="<?php echo $tip; ?>">
                <span style="width:<?php echo $avg * 10 * 2; ?>%"><!-- convert the rating into percent --></span>
            </span>
            <span class="small"><?php echo JText::sprintf('COM_JBLANCE_COUNT_REVIEWS', $count); ?></span>
            <?php
            return $avg;
        }
    }

    public static function getUserRateProject($userid, $projectid) {
        $db = JFactory::getDbo();
        $query = "SELECT (quality_clarity+communicate+expertise_payment+professional+hire_work_again)/5 AS rating FROM #__jblance_rating " .
                "WHERE target=" . $userid . " AND project_id = " . $projectid;
        $db->setQuery($query);
        $rating = $db->loadResult();
        $rating = round($rating, 2);
        return $rating;
    }

    //svc is service order id
    public static function getUserRating($user_id, $svc_proj_id, $type) {
        $db = JFactory::getDbo();
        $query = "SELECT (quality_clarity + communicate + expertise_payment + professional + hire_work_again) / 5 AS rating FROM #__jblance_rating " .
                "WHERE target=" . $db->quote($user_id) . " AND project_id=" . $db->quote($svc_proj_id) . " AND type=" . $db->quote($type); //echo $query;
        $db->setQuery($query);
        $rating = $db->loadResult();
        $rating = round($rating, 2);
        return $rating;
    }

    public static function getRatingHTML($rate, $tooltip = '') {
        $rate = round($rate, 1);
        JHtml::_('behavior.tooltip');
        ?>
        <div class="rating">
            <span class="label label-warning" style="vertical-align: top;"><?php echo number_format($rate, 1); ?></span>
            <span class="rating_bar">
                <span style="width:<?php echo $rate * 10 * 2; ?>%"></span>
            </span>
            <div class="clearfix"></div></div>
        <?php
    }

    //2.Which Plan to Use?
    public static function whichPlan($userid = null) {


        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $is_expired = false;

        $jbuser = self::get('helper.user');
        $ug_id = $jbuser->getUserGroupInfo($userid, null)->id;
        $current_plan = JblanceHelper::planStatus($userid);
        $isactiveplan = '';
        if (!$current_plan)
            $isactiveplan = 'Active';
        else if ($current_plan == 1)
            $isactiveplan = 'Expired';
        else
            $isactiveplan = 'No Plan';
        // echo '<pre>'; print_r($current_plan); die;
        $query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE user_id = $userid AND approved = 1";
        $db->setQuery($query);
        $id_max = $db->loadResult();
        if ($id_max) {
            //check if the plan is expired or not
            $query = 'SELECT (TO_DAYS(s.date_expire) - TO_DAYS(NOW())) daysleft FROM  #__jblance_plan_subscr s WHERE s.id=' . $id_max;
            $db->setQuery($query);
            $days_left = $db->loadResult();

            if ($days_left < 0)
                $is_expired = true;
        }

        //  if(!JblanceHelper::)

        if (!$id_max || $is_expired) { //user has no active plan or it is expired. so choose the default plan (free plan)
            $query = "SELECT * FROM #__jblance_plan WHERE `default_plan` = 1 AND ug_id=" . $db->quote($ug_id);
        } else {
            $query = "SELECT * FROM #__jblance_plan WHERE id = (
			SELECT plan_id FROM #__jblance_plan_subscr WHERE id = " . $db->quote($id_max) . " )";
        }
        $db->setQuery($query);
        $plan = $db->loadObject();

        //convert the params to object
        $registry = new JRegistry;
        $registry->loadString($plan->params);
        $params = $registry->toObject();

        //bind the $params object to $plan and make one object
        foreach ($params as $k => $v) {
            $plan->$k = $v;
        }
        $plan->isactiveplan = $isactiveplan;
        //   echo '<pre>'; print_r($plan); die;
        return $plan;
    }

    public static function getCuBtPlan() {

        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $pkey = $user->id . "." . $user->name . ".bt.customer";
        $customer = $app->getUserState($pkey);


        if (!empty($customer)) {
            $sub = $customer['recent_subscription'];
            $name = $sub['name'];
            $name = explode("(", $name);
            $customer['recent_subscription']['name'] = $name[0];
            return $customer['recent_subscription'];
        }


        /* $name = explode("-",$plan->name);
          return ucfirst($name[0]); */
    }

    public static function countUnreadMsg($msgid = 0) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        if ($msgid > 0)
            $query = "SELECT COUNT(is_read) isRead FROM #__jblance_message WHERE idTo=$user->id AND (id=$msgid OR parent=$msgid) AND is_read=0 AND deleted=0";
        else
            $query = "SELECT COUNT(is_read) isRead FROM #__jblance_message WHERE idTo=$user->id AND is_read=0 AND deleted=0";

        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    /**
     * Get JoomBri profile integration object
     *
     * Returns the global {@link JoombriProfile} object, only creating it if it doesn't already exist.
     *
     * @return object JoombriProfile
     */
    public static function getProfile() {
        jbimport('integration.profile');
        return JoombriProfile::getInstance();
    }

    /**
     * Get Joombri avatar integration object
     *
     * Returns the global {@link JoombriAvatar} object, only creating it if it doesn't already exist.
     *
     * @return object JoombriAvatar
     */
    public static function getAvatarIntegration() {
        jbimport('integration.avatar');
        return JoombriAvatar::getInstance();
    }

    /**
     * Return the amount formatted with currency symbol and/or code
     * 
     * @param float $amount Amount to be formatted
     * @param boolean $setCurrencySymbol Prefix currency symbol 
     * @param boolean $setCurrencyCode Suffix currency code
     * @param integer $decimal No of decimal points
     * @return string Formatted currency
     */
    public static function formatCurrency($amount, $setCurrencySymbol = true, $setCurrencyCode = false, $decimal = 2) {

        $config = self::getConfig();
        $currencySym = $config->currencySymbol;
        $currencyCod = $config->currencyCode;
        $formatted = number_format($amount, $decimal, '.', ',');

        if ($setCurrencySymbol)
            $formatted = $currencySym . '' . $formatted;

        if ($setCurrencyCode)
            $formatted .= ' ' . $currencyCod;

        return $formatted;
    }

    public static function showRemainingDHM($endDate, $type = 'LONG', $runOutMsg) {

        $now = JFactory::getDate();
        $diff = self::dateTimeDiff($now, $endDate);

        if ($now > $endDate)
            return JText::_($runOutMsg);

        if ($diff->y > 0)
            $formatted = JText::sprintf('COM_JBLANCE_YEAR_MONTHS_' . $type, $diff->y, $diff->m);
        elseif ($diff->m > 0)
            $formatted = JText::sprintf('COM_JBLANCE_MONTHS_DAYS_' . $type, $diff->m, $diff->d);
        elseif ($diff->d > 0)
            $formatted = JText::sprintf('COM_JBLANCE_DAYS_HOURS_' . $type, $diff->d, $diff->h);
        elseif ($diff->h > 0)
            $formatted = JText::sprintf('COM_JBLANCE_HOURS_MINUTES_' . $type, $diff->h, $diff->i);
        elseif ($diff->i > 0)
            $formatted = JText::sprintf('COM_JBLANCE_MINUTES_SECS_' . $type, $diff->i, $diff->s);
        else
            $formatted = JText::sprintf('COM_JBLANCE_SECS_' . $type, $diff->s);

        return $formatted;
    }

    public static function showTimePastDHM($startDate, $type = 'LONG') {

        $now = JFactory::getDate();
        $diff = self::dateTimeDiff($now, $startDate); //print_r($diff);

        if ($diff->y > 0)
            $formatted = JText::sprintf('COM_JBLANCE_YEAR_MONTHS_' . $type, $diff->y, $diff->m);
        elseif ($diff->m > 0)
            $formatted = JText::sprintf('COM_JBLANCE_MONTHS_DAYS_' . $type, $diff->m, $diff->d);
        elseif ($diff->d > 0)
            $formatted = JText::sprintf('COM_JBLANCE_DAYS_HOURS_' . $type, $diff->d, $diff->h);
        elseif ($diff->h > 0)
            $formatted = JText::sprintf('COM_JBLANCE_HOURS_MINUTES_' . $type, $diff->h, $diff->i);
        elseif ($diff->i > 0)
            $formatted = JText::sprintf('COM_JBLANCE_MINUTES_SECS_' . $type, $diff->i, $diff->s);
        else
            $formatted = JText::sprintf('COM_JBLANCE_SECS_' . $type, $diff->s);

        $formatted .= ' ' . JText::_('COM_JBLANCE_AGO');

        return $formatted;
    }

    /**
     * @param date $fromdate
     * @param date $toDate
     * @return stdClass
     */
    public static function dateTimeDiff($fromdate, $todate) {

        $alt_diff = new stdClass();
        $alt_diff->y = floor(abs($fromdate->format('U') - $todate->format('U')) / (60 * 60 * 24 * 365));
        $alt_diff->m = floor((floor(abs($fromdate->format('U') - $todate->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365)) / 30);
        $alt_diff->d = floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));
        $alt_diff->h = floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60 * 60)) - ($alt_diff->y * 365 * 24) - ($alt_diff->m * 30 * 24 ) - ($alt_diff->d * 24));
        $alt_diff->i = floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60)) - ($alt_diff->y * 365 * 24 * 60) - ($alt_diff->m * 30 * 24 * 60) - ($alt_diff->d * 24 * 60) - ($alt_diff->h * 60));
        $alt_diff->s = floor(floor(abs($fromdate->format('U') - $todate->format('U'))) - ($alt_diff->y * 365 * 24 * 60 * 60) - ($alt_diff->m * 30 * 24 * 60 * 60) - ($alt_diff->d * 24 * 60 * 60) - ($alt_diff->h * 60 * 60) - ($alt_diff->i * 60));
        $alt_diff->invert = (($fromdate->format('U') - $todate->format('U')) > 0) ? 0 : 1;

        /* $alt_diff->d =  floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60*24)) );
          $alt_diff->h =  floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60))  - ($alt_diff->d * 24) );
          $alt_diff->i = floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60)) -  ($alt_diff->d * 24 * 60) -  ($alt_diff->h * 60) );
          $alt_diff->s =  floor( floor(abs($fromdate->format('U') - $todate->format('U'))) -  ($alt_diff->d * 24 * 60*60) -  ($alt_diff->h * 60*60) -  ($alt_diff->i * 60) );
          $alt_diff->invert =  (($fromdate->format('U') - $todate->format('U')) > 0)? 0 : 1 ; */

        return $alt_diff;
    }

    public static function getFeeds($limit, $notify = '') {

        $user = JFactory::getUser();
        $feeds = self::get('helper.feeds');  // create an instance of the class FeedsHelper

        $feeds = $feeds->getFeedsData($user->id, $limit, $notify);
        return $feeds;
    }

    public static function parseTitle($title) {
        /* $title = str_replace(array(" ", "&", "`", "~", "!", "@", "#", "$", "%", "^", "*", "(", ")", "+", "_", "=", "{", "}", "[", "]", ":", ";", "'", "\"", "<", ">", ",", ".", "/", "?"), "-", strip_tags(strtolower($title)));
          for($n=1; $n<=10; $n++){
          $title = str_replace(array("--", "---", "----"), "-", $title);

          if(substr($title, 0, 1) == "-")
          $title = substr($title, 1);

          if(substr($title, -1, 1) == "-")
          $title = substr($title, 0, -1);
          } */
        if (JFactory::getConfig()->get('unicodeslugs') == 1) {
            $output = JFilterOutput::stringURLUnicodeSlug($title);
        } else {
            $output = JFilterOutput::stringURLSafe($title);
        }
        return $output;
    }

    /**
     * Identify whether the order is deposit or plan purchase
     * 
     * @param string $invoice_num
     * @return string
     */
    public static function identifyDepositOrPlan($invoice_num) {
        $db = JFactory::getDbo();
        $type = '';
        $return = array();

        // search for invoice number in plan subscription table
        $query = "SELECT id FROM #__jblance_plan_subscr p WHERE p.invoiceNo = " . $db->quote($invoice_num);
        $db->setQuery($query);
        $result = $db->loadResult();

        //if result is empty search for invoice number in deposit table
        if ($result) {
            $return['type'] = 'plan';
            $return['id'] = $result;
        } else {
            $query = "SELECT id FROM #__jblance_deposit d WHERE d.invoiceNo = " . $db->quote($invoice_num);
            $db->setQuery($query);
            $result = $db->loadResult();
            if ($result) {
                $return['type'] = 'deposit';
                $return['id'] = $result;
            } else {
                $return = array();
            }
        }
        return $return;
    }

    public static function approveSubscription($subscrid) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $row = JTable::getInstance('plansubscr', 'Table');
        $row->load($subscrid);

        $query = "SELECT p.* FROM #__jblance_plan p WHERE p.id=" . $row->plan_id;
        $db->setQuery($query);
        $plan = $db->loadObject();

        // Update the transaction table if not approved
        if (!$row->approved) {
            $transDtl = JText::_('COM_JBLANCE_BUY_SUBSCR') . ' - ' . $plan->name;
            $row_trans = JblanceHelper::updateTransaction($row->user_id, $transDtl, $row->fund, 1);

            //save status subscription "approved"
            $now = JFactory::getDate();
            $date_approve = $now->toSql();
            $now->modify("+$plan->days $plan->days_type");
            $date_expires = $now->toSql();

            $row->approved = 1;
            $row->date_approval = $date_approve;
            $row->date_expire = $date_expires;
            $row->gateway_id = time();
            $row->trans_id = $row_trans->id;
            $row->access_count = 1;

            if (!$row->check())
                JError::raiseError(500, $row->getError());

            if (!$row->store())
                JError::raiseError(500, $row->getError());

            $row->checkin();

            $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
            $jbmail->alertAdminSubscr($row->id, $row->user_id);
            $jbmail->alertUserSubscr($row->id, $row->user_id);

            return $row;
        }
    }

    function approveFundDeposit($deposit_id) {
        $row = JTable::getInstance('deposit', 'Table');
        $row->load($deposit_id);

        // Update the transaction table if not approved
        if (!$row->approved) {
            $transDtl = JText::_('COM_JBLANCE_DEPOSIT_FUNDS');
            $row_trans = JblanceHelper::updateTransaction($row->user_id, $transDtl, $row->amount, 1);

            //save status billing "approved"
            $now = JFactory::getDate();
            $date_approve = $now->toSql();
            $row->approved = 1;
            $row->date_approval = $date_approve;
            $row->trans_id = $row_trans->id;

            if (!$row->check())
                JError::raiseError(500, $row->getError());

            if (!$row->store())
                JError::raiseError(500, $row->getError());

            $row->checkin();

            $jbmail = JblanceHelper::get('helper.email');  // create an instance of the class EmailHelper
            $jbmail->sendAdminDepositFund($row->id);

            return $row;
        }
    }

    //3.Status of member's plan
    public static function planStatus($userid = null) {
        $app = JFactory::getApplication();
        $user = JFactory::getUser($userid);
        $pkey = $user->id . "." . $user->name . ".bt.customer";
        $customer = $app->getUserState($pkey);


        if (!empty($customer)) {
            $subscription = $customer['recent_subscription'];
            $status = $subscription['status'];

            if ($status == "Expired" || $status == "Pending" || $status == "Past Due" || $status == "Canceled") {
                return 1;
            } else {
                return null;
            }
        } else {
            return 2;
        }
        /* $db  = JFactory::getDbo();
          $now = JFactory::getDate();

          $query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE approved=1 AND user_id=".$db->quote($userid);
          $db->setQuery($query);
          $id_max = $db->loadResult();

          if(!$id_max)	//user has no active plan. so choose the default plan (free plan)
          return 2;

          $query = "SELECT * FROM #__jblance_plan_subscr WHERE id=".$db->quote($id_max);
          $db->setQuery($query);
          $last_subscr = $db->loadObject();

          $query = "SELECT * FROM #__jblance_plan WHERE id=".$db->quote($last_subscr->plan_id);
          $db->setQuery($query);
          $last_plan = $db->loadObject();

          if($now > $last_subscr->date_expire)
          return 1;	// The user's subscr has expired
          else
          return null; */
    }

    public static function getBtPlan($userId = null) {
        if ($userId != null) {
            $customer = self::getBtCustomer($userId);
            $plan = $customer['recent_subscription'];


            $name = $plan['name'];
            $name = explode("(", $name);
            $plan['name'] = $name[0];
        } else {
            $plan = self::getCuBtPlan();
        }
        return $plan;
    }

    public static function processMessage() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $msgid = $app->input->get('msgid', '', 'int');

        $query = "UPDATE #__jblance_message SET deleted=1 WHERE id=" . $msgid . " OR parent=" . $msgid;
        $db->setQuery($query);
        if ($db->execute())
            echo 'OK';
        else
            echo 'NO';
        exit;
    }

    public static function getProgressBar($currStep = 0) {

        $totalStep = self::getTotalSteps();
        $width = intval(($currStep / $totalStep) * 100);

        $html = '<div class="progress progress-striped">' .
                '<div class="bar" style="width:' . $width . '%;"></div>' .
                '</div>';
        return $html;
    }

    public static function getTotalSteps() {
        $user = JFactory::getUser();
        $session = JFactory::getSession();
        $skipPlan = $session->get('skipPlan', 0, 'register');
        $total = 4;

        if ($user->guest) {
            if ($skipPlan)
                $total -= 1;
        }
        else {
            if ($skipPlan)
                $total -= 2;
            else
                $total -= 1;
        }
        return $total;
    }

    public static function formatProjectDuration($from, $fromType, $to, $toType, $less_great) {
        if (empty($less_great)) {
            return self::getDaysType($from, $fromType) . ' - ' . self::getDaysType($to, $toType);
        } else {
            if ($less_great == '<')
                return JText::_('COM_JBLANCE_LESS_THAN') . ' ' . self::getDaysType($to, $toType);
            if ($less_great == '>')
                return JText::_('COM_JBLANCE_OVER') . ' ' . self::getDaysType($from, $fromType);
        }
    }

    public static function getDaysType($days, $daysType) {

        switch ($daysType) {
            case 'days':
                $lang = ($days == 1) ? JText::_('COM_JBLANCE_DAY') : JText::_('COM_JBLANCE_DAYS');
                break;
            case 'weeks':
                $lang = ($days == 1) ? JText::_('COM_JBLANCE_WEEK') : JText::_('COM_JBLANCE_WEEKS');
                break;
            case 'months':
                $lang = ($days == 1) ? JText::_('COM_JBLANCE_MONTH') : JText::_('COM_JBLANCE_MONTHS');
                break;
            case 'years':
                $lang = ($days == 1) ? JText::_('COM_JBLANCE_YEAR') : JText::_('COM_JBLANCE_YEARS');
                break;
        }
        $lang = $days . ' ' . $lang;
        return $lang;
    }

    public static function countUnapprovedMsg($msgid = 0) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = '';

        if ($msgid > 0)
            $query = "SELECT COUNT(approved) isApproved FROM #__jblance_message WHERE (id=" . $db->quote($msgid) . " OR parent=" . $db->quote($msgid) . ") AND approved=0 AND deleted=0";
        else
            $query = "SELECT COUNT(approved) isApproved FROM #__jblance_message WHERE approved=0 AND deleted=0";

        $db->setQuery($query); //echo $query;
        $total = $db->loadResult(); //echo $total;
        return $total;
    }

    public static function setJoomBriToken() {
        $doc = JFactory::getDocument();
        $addtoken = JSession::getFormToken();
        $addtokenjs = 'var JoomBriToken="' . $addtoken . '=1";';
        $doc->addScriptDeclaration($addtokenjs);
    }

    public static function getHttpResponse($url, $referer = null, $_data = null, $method = 'post', $userAgent = null, $headers = null) {


        // convert variables array to string:
        $data = '';
        if (is_array($_data) && count($_data) > 0) {
            // format --> test1=a&test2=b etc.
            $data = array();
            while (list($n, $v) = each($_data)) {
                $data[] = "$n=$v";
            }
            $data = implode('&', $data);
            $contentType = "Content-type: application/x-www-form-urlencoded\r\n";
        } else {
            $data = $_data;
            $contentType = "Content-type: text/xml\r\n";
        }

        if (is_null($referer)) {
            $referer = JURI::root();
        }

        // parse the given URL
        $url = parse_url($url);
        if (!isset($url['scheme'])) {
            return false;
        }

        // extract host and path:
        $host = $url['host'];
        $path = isset($url['path']) ? $url['path'] : '/';

        // Prepare host and port to connect to
        $connhost = $host;
        $port = 80;

        // Workaround for some PHP versions, where fsockopen can't connect to
        // 'localhost' string on Windows servers
        if ($connhost == 'localhost') {
            $connhost = gethostbyname('localhost');
        }

        // Handle scheme
        if ($url['scheme'] == 'https') {
            $connhost = 'ssl://' . $connhost;
            $port = 443;
        } else if ($url['scheme'] != 'http') {
            return false;
        }

        // open a socket connection
        $errno = null;
        $errstr = null;
        $fp = @fsockopen($connhost, $port, $errno, $errstr, 5);
        if (!is_resource($fp) || ($fp === false)) {
            return false;
        }

        if (!is_null($userAgent)) {
            $userAgent = "User-Agent: " . $userAgent . "\r\n";
        }

        // send the request
        if ($method == 'post') {
            // Check the first fputs, sometimes fsockopen doesn't fail, but fputs doesn't work
            if (!@fputs($fp, "POST $path HTTP/1.1\r\n")) {
                @fclose($fp);
                return false;
            }

            if (!is_null($userAgent)) {
                fputs($fp, $userAgent);
            }
            fputs($fp, "Host: $host\r\n");
            fputs($fp, "Referer: $referer\r\n");
            fputs($fp, $contentType);
            fputs($fp, "Content-length: " . strlen($data) . "\r\n");

            // Send additional headers if set
            if (is_array($headers)) {
                foreach ($headers as $h) {
                    $h = rtrim($h);
                    $h .= "\r\n";
                    fputs($fp, $h);
                }
            }

            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $data);
        } elseif ($method == 'get') {
            $query = '';
            if (isset($url['query'])) {
                $query = '?' . $url['query'];
            }
            // Check the first fputs, sometimes fsockopen doesn't fail, but fputs doesn't work
            if (!@fputs($fp, "GET {$path}{$query} HTTP/1.1\r\n")) {
                @fclose($fp);
                return false;
            }
            if (!is_null($userAgent)) {
                fputs($fp, $userAgent);
            }
            fputs($fp, "Host: $host\r\n");

            // Send additional headers if set
            if (is_array($headers)) {
                foreach ($headers as $h) {
                    $h = rtrim($h);
                    $h .= "\r\n";
                    fputs($fp, $h);
                }
            }

            fputs($fp, "Connection: close\r\n\r\n");
        }

        $result = '';
        while (!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }

        // close the socket connection:
        fclose($fp);

        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);

        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';

        $response = new stdClass();
        $response->header = $header;
        $response->content = $content;

        // Handle chunked transfer if needed
        if (strpos(strtolower($response->header), 'transfer-encoding: chunked') !== false) {
            $parsed = '';
            $left = $response->content;

            while (true) {
                $pos = strpos($left, "\r\n");
                if ($pos === false) {
                    return $response;
                }

                $chunksize = substr($left, 0, $pos);
                $pos += strlen("\r\n");
                $left = substr($left, $pos);

                $pos = strpos($chunksize, ';');
                if ($pos !== false) {
                    $chunksize = substr($chunksize, 0, $pos);
                }
                $chunksize = hexdec($chunksize);

                if ($chunksize == 0) {
                    break;
                }

                $parsed .= substr($left, 0, $chunksize);
                $left = substr($left, $chunksize + strlen("\r\n"));
            }

            $response->content = $parsed;
        }

        // Get the response code from header
        $headerLines = explode("\n", $response->header);
        $header1 = explode(' ', trim($headerLines[0]));
        $code = intval($header1[1]);
        $response->code = $code;

        return $response;
    }

    public static function checkFavorite($targetId, $type) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = '';

        $query = "SELECT COUNT(*) FROM #__jblance_favourite " .
                "WHERE actor=" . $db->quote($user->id) . " AND target=" . $db->quote($targetId) . " AND type=" . $db->quote($type);
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    public static function checkOwnershipOfOperation($id, $type) {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();

        if ($type == 'project') {
            $project = JTable::getInstance('project', 'Table');
            $project->load($id);
            if ($project->publisher_userid != $user->id)
                return false;
            else
                return true;
        }
        elseif ($type == 'service') {
            $service = JTable::getInstance('service', 'Table');
            $service->load($id);
            if ($service->user_id != $user->id)
                return false;
            else
                return true;
        }
        elseif ($type == 'projectprogress') {
            $query = "SELECT b.user_id assigned,p.publisher_userid publisher FROM #__jblance_bid b " .
                    "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
                    "WHERE b.id = " . $db->quote($id);
            $db->setQuery($query);
            $bidInfo = $db->loadObject();


            if ($bidInfo->assigned != $user->id && $bidInfo->publisher != $user->id)
                return false;
            else
                return true;
        }
        elseif ($type == 'serviceprogress') {
            $query = "SELECT s.user_id seller,so.user_id buyer FROM #__jblance_service_order so " .
                    "LEFT JOIN #__jblance_service s ON so.service_id=s.id " .
                    "WHERE so.id = " . $db->quote($id);
            $db->setQuery($query);
            $orderInfo = $db->loadObject();
            if ($orderInfo->seller != $user->id && $orderInfo->buyer != $user->id)
                return false;
            else
                return true;
        }
    }

    public static function getCompletedOrders($userid) {
        $db = JFactory::getDbo();


        $query = "SELECT count(id) as total_orders FROM #__jblance_bid WHERE p_status='COM_JBLANCE_COMPLETED' AND user_id=" . $db->quote($userid);
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }

    public static function getLocation() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $location_id = $app->input->get('location_id', 0, 'int');
        $cur_level = $app->input->get('cur_level', 0, 'int');
        $nxt_level = $app->input->get('nxt_level', 0, 'int');
        $task1 = $app->input->get('task_val', '', 'string');

        $query = 'SELECT COUNT(*) FROM #__jblance_location WHERE parent_id=' . $db->quote($location_id);
        $db->setQuery($query);
        $count = $db->loadResult();

        if ($location_id > 0 && $count > 0) {
            $query = 'SELECT id AS value, title AS text FROM #__jblance_location ' .
                    'WHERE published=1 AND parent_id=' . $db->quote($location_id) . ' ' .
                    'ORDER BY lft';
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $types[] = JHtml::_('select.option', '', '- ' . JText::_('COM_JBLANCE_PLEASE_SELECT') . ' -');
            foreach ($items as $item) {
                $types[] = JHtml::_('select.option', $item->value, JText::_($item->text));
            }

            $attribs = array('class' => 'input-medium required', 'data-level-id' => $nxt_level, 'onchange' => 'getLocation(this, \'' . $task1 . '\');', 'required' => 'required');

            $lists = JHtml::_('select.genericlist', $types, 'location_level[]', $attribs, 'value', 'text', '', 'level' . $nxt_level);
            echo $lists;
        } else {
            echo '0';
        }
        $app->close();
    }

    public static function getGoogleMap($lat, $long, $title) {
        $doc = JFactory::getDocument();
        $doc->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp");
        $doc->setMetaData('viewport', 'initial-scale=1.0, user-scalable=no');

        $script = "
			var map;
			function initialize() {
				var myLatlng = new google.maps.LatLng($lat, $long);
				var mapOptions = {
			    	zoom: 8,
			    	center: myLatlng
			  	};
				map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			
				var marker = new google.maps.Marker({
		      		position: myLatlng,
					map: map,
					title: ''
		  		});
		  		
				var infowindow = new google.maps.InfoWindow({
					content: '" . addslashes($title) . "'
				});
				
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
			}
			
			google.maps.event.addDomListener(window, 'load', initialize);";

        $doc->addScriptDeclaration($script);
    }

    /**
     * Show the feature/unfeature links
     *
     * @param   int      $value      The state value
     * @param   int      $i          Row number
     * @param   boolean  $canChange  Is user allowed to change?
     *
     * @return  string       HTML code
     */
    public static function boolean($value = 0, $i, $taskOn = null, $taskOff = null) {
        JHtml::_('bootstrap.tooltip');

        $task = ($value) ? $taskOff : $taskOn;
        $toggle = (!$task) ? false : true;

        // Array of image, task, title, action
        $states = array(
            0 => array('unpublish', $taskOn, 'JNO', 'JGLOBAL_CLICK_TO_TOGGLE_STATE'),
            1 => array('publish', $taskOff, 'JYES', 'JGLOBAL_CLICK_TO_TOGGLE_STATE'),
        );
        $state = JArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon = $state[0];

        if ($toggle) {
            $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-' . $icon . '"></i></a>';
        } else {
            $html = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-' . $icon . '"></i></a>';
        }

        return $html;
    }

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {

        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_DASHBOARD'), 'index.php?option=com_jblance&view=admproject&layout=dashboard', $vName == 'dashboard');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_PROJECTS'), 'index.php?option=com_jblance&view=admproject&layout=showproject', $vName == 'showproject');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_SERVICES'), 'index.php?option=com_jblance&view=admproject&layout=showservice', $vName == 'showservice');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_USERS'), 'index.php?option=com_jblance&view=admproject&layout=showuser', $vName == 'showuser');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_SUBSCRIPTIONS'), 'index.php?option=com_jblance&view=admproject&layout=showsubscr', $vName == 'showsubscr');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_DEPOSITS'), 'index.php?option=com_jblance&view=admproject&layout=showdeposit', $vName == 'showdeposit');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_WITHDRAWALS'), 'index.php?option=com_jblance&view=admproject&layout=showwithdraw', $vName == 'showwithdraw');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_ESCROWS'), 'index.php?option=com_jblance&view=admproject&layout=showescrow', $vName == 'showescrow');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_REPORTINGS'), 'index.php?option=com_jblance&view=admproject&layout=showreporting', $vName == 'showreporting');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_PRIVATE_MESSAGES'), 'index.php?option=com_jblance&view=admproject&layout=managemessage', $vName == 'managemessage');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_CONFIGURATION'), 'index.php?option=com_jblance&view=admconfig&layout=configpanel', $vName == 'configpanel');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_SUMMARY'), 'index.php?option=com_jblance&view=admproject&layout=showsummary', $vName == 'showsummary');
        JHtmlSidebar::addEntry(JText::_('COM_JBLANCE_TITLE_ABOUT'), 'index.php?option=com_jblance&view=admproject&layout=about', $vName == 'about');
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_jblance';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

    static function getBtConfig() {

        $apiBt = JPATH_SITE . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'libraries' . DS . 'bt' . DS . 'lib' . DS . 'Braintree.php';

        include_once($apiBt);

        jimport('joomla.application.component.helper');
        $params = JComponentHelper::getParams('com_alphauserpoints');
        $mode = $params->get('mode') != 1 ? "production" : "sandbox";
        $bt_merch_id = $params->get('bt_merch_id');
        $bt_pub_key = $params->get('bt_pub_key');
        $bt_pvt_key = $params->get('bt_pvt_key');


        Braintree\Configuration::environment($mode);
        Braintree\Configuration::merchantId($bt_merch_id);
        Braintree\Configuration::publicKey($bt_pub_key);
        Braintree\Configuration::privateKey($bt_pvt_key);
    }

}

class JBMediaHelper {

    public static function uploadFile($post, $project) {
        $app = JFactory::getApplication();
        $projfile = JTable::getInstance('projectfile', 'Table');

        //check if path exists, else create
        if (!file_exists(JBPROJECT_PATH)) {
            if (mkdir(JBPROJECT_PATH)) {
                JPath::setPermissions(JBPROJECT_PATH, '0777');
                if (file_exists(JPATH_SITE . '/images/index.html')) {
                    copy(JPATH_SITE . '/images/index.html', JBPROJECT_PATH . '/index.html');
                }
            }
        }

        //REMOVE THE FILES `IF` CHECKED
        $removeFiles = $app->input->get('file-id', null, 'array');
        if (!empty($removeFiles)) {
            foreach ($removeFiles as $removeFileId) {
                $projfile->load($removeFileId);
                $old_doc = $projfile->file_name;
                $delete = JBPROJECT_PATH . '/' . $old_doc;
                unlink($delete);
                $projfile->delete($removeFileId);
            }
        }

        $uploadLimit = $post['uploadLimit'];
        for ($i = 0; $i < $uploadLimit; $i++) {
            $file = $_FILES['uploadFile' . $i];

            if ($file['size'] > 0) {
                //check if the resume file can be uploaded
                $err = null;
                if (!self::canUpload($file, $err, 'project', $project->id)) {
                    // The file can't be upload
                    $app->enqueueMessage(JText::_($err) . ' - ' . JText::sprintf('COM_JBLANCE_ERROR_FILE_NAME', $file['name']), 'error');
                    continue; //continues goes to the for loop but break breaks the for loop
                }

                self::uploadEachFile($file, $project, $projfile);
            } // end of file size
        } //upload file loop end
    }

    function uploadEachFile($file, $project, $projfile) {
        //get the new file name
        $new_doc = "proj_" . $project->id . "_" . strtotime("now") . "_" . $file['name'];
        $new_doc = preg_replace('/[[:space:]]/', '_', $new_doc); //replace space in the file name with _
        $new_doc = JFile::makeSafe($new_doc);
        $dest = JBPROJECT_PATH . '/' . $new_doc;
        $soure = $file['tmp_name'];
        // Move uploaded file
        $uploaded = JFile::upload($soure, $dest);

        $projfile->id = 0;
        $projfile->project_id = $project->id;
        $projfile->file_name = $new_doc;
        $projfile->show_name = JFile::makeSafe($file['name']);
        $projfile->hash = md5_file($file['tmp_name']);

        // pre-save checks
        if (!$projfile->check()) {
            JError::raiseError(500, $projfile->getError());
        }
        // save the changes
        if (!$projfile->store()) {
            JError::raiseError(500, $projfile->getError());
        }
        $projfile->checkin();
    }

    public static function messageAttachFile() {

        $response = array();
        $file = $_FILES['uploadmessage'];

        if ($file['size'] > 0) {
            //check if the resume file can be uploaded
            $err = null;
            if (!self::canUpload($file, $err, 'message', '')) {
                // The file can't be upload
                $response['result'] = 'NO';
                $response['msg'] = $err;
                $response['elementID'] = 'uploadmessage';
                echo json_encode($response);
                exit;
            }

            if (!file_exists(JBMESSAGE_PATH)) {
                if (mkdir(JBMESSAGE_PATH)) {
                    JPath::setPermissions(JBMESSAGE_PATH, '0777');
                    if (file_exists(JPATH_SITE . '/images/index.html')) {
                        copy(JPATH_SITE . '/images/index.html', JBMESSAGE_PATH . '/index.html');
                    }
                }
            }

            //get the new file name
            $new_doc = "msg_" . strtotime("now") . "_" . $file['name'];
            $new_doc = preg_replace('/[[:space:]]/', '_', $new_doc); //replace space in the file name with _
            $new_doc = JFile::makeSafe($new_doc);
            $dest = JBMESSAGE_PATH . '/' . $new_doc;
            $soure = $file['tmp_name'];
            // Move uploaded file
            $uploaded = JFile::upload($soure, $dest);

            $response['result'] = 'OK';
            $response['attachvalue'] = $file['name'] . ";" . $new_doc;
            $response['attachname'] = $file['name'];
            $response['msg'] = JText::_('COM_JBLANCE_FILE_ATTACHED_SUCCESSFULLY');
            $response['elementID'] = 'uploadmessage';
            echo json_encode($response);
            exit;
        }
    }

    public static function serviceUploadFile() {

        jimport('joomla.filesystem.folder');

        $app = JFactory::getApplication();
        $file = $app->input->files->get('serviceFile');
        $registry = new JRegistry();
        $response = array();

        if ($file['size'] > 0) {
            if (!JFolder::exists(JBSERVICE_PATH)) {
                if (JFolder::create(JBSERVICE_PATH)) {
                    if (JFile::exists(JPATH_SITE . '/images/index.html')) {
                        JFile::copy(JPATH_SITE . '/images/index.html', JBSERVICE_PATH . '/index.html');
                    }
                }
                if (JFolder::create(JBSERVICE_PATH . '/thumb')) {
                    if (JFile::exists(JPATH_SITE . '/images/index.html')) {
                        JFile::copy(JPATH_SITE . '/images/index.html', JBSERVICE_PATH . '/thumb/index.html');
                    }
                }
            }

            $docPrefix = 'svc_';
            $new_doc = $docPrefix . strtotime("now") . "_" . $file['name'];
            $new_doc = preg_replace('/[[:space:]]/', '_', $new_doc); //replace space in the file name with _
            $new_doc = JFile::makeSafe($new_doc);

            self::resize($file, '80', '80', JBSERVICE_PATH . '/thumb/' . $new_doc);

            $dest = JBSERVICE_PATH . '/' . $new_doc;
            $soure = $file['tmp_name'];
            // Move uploaded file
            $uploaded = JFile::upload($soure, $dest);

            //create name, servername and size array and convert to json
            $returnValue = array('name' => $file['name'], 'servername' => $new_doc, 'size' => filesize($dest));
            $registry->loadArray($returnValue);
            $returnValue = $registry->toString();

            $response['result'] = 'OK';
            $response['attachvalue'] = $file['name'] . ";" . $new_doc . ";" . filesize($dest);
            $response['msg'] = JText::_('COM_JBLANCE_FILE_ATTACHED_SUCCESSFULLY');
            echo json_encode($response);
            $app->close();
        } else {
            $response['result'] = 'NO';
            $response['msg'] = 'File is empty';
            echo json_encode($response);
            $app->close();
        }
    }

    public static function removeServiceFile() {
        $app = JFactory::getApplication();
        $attachValue = $app->input->get('attachvalue', '', 'string');
        $attFile = explode(';', $attachValue);
        $serverFilePath = JBSERVICE_PATH . '/' . $attFile[1];
        $serverThumbPath = JBSERVICE_PATH . '/thumb/' . $attFile[1];

        if (JFile::exists($serverFilePath)) {
            $delpic = unlink($serverFilePath);
        }
        if (JFile::exists($serverThumbPath)) {
            $delpic = unlink($serverThumbPath);
        }
        echo JText::_('COM_JBLANCE_FILE_REMOVED_SUCCESSFULLY');
        ;
        $app->close();
    }

    /**
     * @param File $file
     * @param int $width
     * @param int $height
     * @param string $path This includes the path and file name of thumb image
     * @return string
     */
    public static function resize($file, $width, $height, $path) {

        list($w, $h) = getimagesize($file['tmp_name']);   /* Get original image x y */
        $ratio = max($width / $w, $height / $h);     /* calculate new image size with ratio */
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);

        $imgString = file_get_contents($file['tmp_name']);  /* read binary data from image file */
        $image = imagecreatefromstring($imgString);    /* create image from string */
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);

        /* Save image */
        switch ($file['type']) {
            case 'image/jpeg':
                imagejpeg($tmp, $path, 100);
                break;
            case 'image/png':
                imagepng($tmp, $path, 0);
                break;
            case 'image/gif':
                imagegif($tmp, $path);
                break;
            default:
                exit;
                break;
        }
        /* cleanup memory */
        imagedestroy($image);
        imagedestroy($tmp);

        return $path;
    }

    public static function portfolioAttachFile() {

        $app = JFactory::getApplication();
        $elementID = $app->input->get('elementID', '', 'string');

        $response = array();
        $file = $_FILES[$elementID];

        //get the type whether portfolio image or file
        if ($elementID == 'portfoliopicture') {
            $type = 'picture';
            $docPrefix = 'pic_';
        } elseif ((strpos($elementID, 'portfolioattachment') === 0)) {
            $type = 'project';
            $docPrefix = 'file_';
        }

        if ($file['size'] > 0) {
            //check if the resume file can be uploaded
            $err = null;
            if (!self::canUpload($file, $err, $type, '')) {
                // The file can't be upload
                $response['result'] = 'NO';
                $response['msg'] = $err;
                $response['elementID'] = $elementID;
                echo json_encode($response);
                exit;
            }

            if (!file_exists(JBPORTFOLIO_PATH)) {
                if (mkdir(JBPORTFOLIO_PATH)) {
                    JPath::setPermissions(JBPORTFOLIO_PATH, '0777');
                    if (file_exists(JPATH_SITE . '/images/index.html')) {
                        copy(JPATH_SITE . '/images/index.html', JBPORTFOLIO_PATH . '/index.html');
                    }
                }
            }

            //get the new file name
            $new_doc = $docPrefix . strtotime("now") . "_" . $file['name'];
            $new_doc = preg_replace('/[[:space:]]/', '_', $new_doc); //replace space in the file name with _
            $new_doc = JFile::makeSafe($new_doc);
            $dest = JBPORTFOLIO_PATH . '/' . $new_doc;
            $soure = $file['tmp_name'];
            // Move uploaded file
            $uploaded = JFile::upload($soure, $dest);

            $response['result'] = 'OK';
            $response['attachvalue'] = $file['name'] . ";" . $new_doc;
            $response['attachname'] = $file['name'];
            $response['msg'] = JText::_('COM_JBLANCE_FILE_ATTACHED_SUCCESSFULLY');
            $response['elementID'] = $elementID;
            echo json_encode($response);
            exit;
        }
    }

    public static function uploadCustomFieldFile($field_id) {
        $app = JFactory::getApplication();
        $response = array();

        //check if path exists, else create
        if (!file_exists(JBCUSTOMFILE_PATH)) {
            if (mkdir(JBCUSTOMFILE_PATH)) {
                JPath::setPermissions(JBCUSTOMFILE_PATH, '0777');
                if (file_exists(JPATH_SITE . '/images/index.html')) {
                    copy(JPATH_SITE . '/images/index.html', JBCUSTOMFILE_PATH . '/index.html');
                }
            }
        }

        //REMOVE THE CUSTOM FILE `IF` CHECKED
        $chkAttach = $app->input->get('chk-customfile-' . $field_id, '', 'string');
        if (!empty($chkAttach)) {
            $attFile = explode(';', $chkAttach);
            $filename = $attFile[1];
            $delete = JBCUSTOMFILE_PATH . '/' . $filename;
            if (JFile::exists($delete)) {
                unlink($delete);
                $response['result'] = 'NO';
            }
        }

        $file = $_FILES['custom_field_' . $field_id];
        if ($file['size'] > 0) {
            //check if the custom file can be uploaded
            $err = null;
            if (!self::canUpload($file, $err, 'custom', '')) {
                // The file can't be upload
                $app->enqueueMessage(JText::_($err) . ' - ' . JText::sprintf('COM_JBLANCE_ERROR_FILE_NAME', $file['name']), 'error');
                $response['result'] = 'NO';
                $response['msg'] = $err;
                return $response;
            }

            //get the new file name
            $new_doc = "custom_" . strtotime("now") . "_" . $file['name'];
            $new_doc = preg_replace('/[[:space:]]/', '_', $new_doc); //replace space in the file name with _
            $new_doc = JFile::makeSafe($new_doc);
            $dest = JBCUSTOMFILE_PATH . '/' . $new_doc;
            $soure = $file['tmp_name'];

            // Move uploaded file
            $uploaded = JFile::upload($soure, $dest);

            $response['result'] = 'OK';
            $response['attachvalue'] = $file['name'] . ";" . $new_doc;
            $response['attachname'] = $file['name'];
            return $response;
        } // end of file size
        return $response;
    }

    public static function canUpload($file, &$err, $attachType, $projectId) {

        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);
        $config = JblanceHelper::getConfig();
        $db = JFactory::getDbo();

        if ($file['error'] != 0) {
            $err = JText::_('COM_JBLANCE_UPLOAD_FILE_ERROR');
            return false;
        }

        if ($attachType == 'project') {
            //check if the file type is allowed
            $type = $config->projectFileType;
            $allowed = explode(',', $type);
            $format = $file['type'];
            if (!preg_match('/(.*)\.(zip|docx)/', $file['name'])) {
                if (!in_array($format, $allowed)) {
                    $err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
                    return false;
                }
            }
            //check for the maximum file size
            $maxSize = $config->projectMaxsize;
            if ((int) $file['size'] / 1024 > $maxSize) {
                $err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
                return false;
            }
            //check for max file count allowed per project
            $fileLimitConf = $config->projectMaxfileCount;
            $query = "SELECT COUNT(f.id) FROM #__jblance_project_file f WHERE f.project_id='" . $projectId . "' AND f.is_nda_file=0";
            $db->setQuery($query);
            $fileCount = $db->loadResult();

            if ($fileCount >= $fileLimitConf) {
                $err = JText::sprintf('COM_JBLANCE_MAX_FILE_FOR_PROJECT_EXCEEDED_ALLOWED_COUNT', $fileLimitConf);
                return false;
            }
        }

        if ($attachType == 'picture') {
            //check if the file type is allowed
            $type = $config->imgFileType;
            $allowed = explode(',', $type);
            $format = $file['type'];
            if (!in_array($format, $allowed)) {
                $err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
                return false;
            }
            //check for the maximum file size
            $maxSize = $config->imgMaxsize;
            if ((int) $file['size'] / 1024 > $maxSize) {
                $err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
                return false;
            }
        }

        if ($attachType == 'message' || $attachType == 'custom') {
            //check if the file type is allowed
            $type = $config->projectFileType;
            $allowed = explode(',', $type);
            $format = $file['type'];
            if (!preg_match('/(.*)\.(zip|docx)/', $file['name'])) {
                if (!in_array($format, $allowed)) {
                    $err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
                    return false;
                }
            }
            //check for the maximum file size
            $maxSize = $config->projectMaxsize;
            if ((int) $file['size'] / 1024 > $maxSize) {
                $err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
                return false;
            }
        }

        if ($attachType == 'video') {
            $type = 'video/x-flv';
            $allowed = explode(',', $type);
            $format = $file['type'];

            if (!in_array($format, $allowed)) {
                $err = 'COM_JBLANCE_YOUR_FILE_IS_IGNORED';
                return false;
            }

            $maxSize = $config->vidMaxsize;
            if ((int) $file['size'] / 1024 > $maxSize) {
                $err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
                return false;
            }
        }
        return true;
    }

    //3.Upload Photo
    public static function uploadPictureMedia() {
        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        //UPLOAD FILE
        $file = $_FILES['photo'];
        $userid = $app->input->get('userid', '', 'int');
        $response = array();

        $jbuser = JblanceHelper::get('helper.user');
        $jbuserid = $jbuser->getUser($userid)->id; //echo $jbuserid;

        $row = JTable::getInstance('jbuser', 'Table');
        $row->load($jbuserid); //print_r($row);

        $oldpicloc = JBPROFILE_PIC_PATH . '/' . $row->picture;
        $oldtmbloc = JBPROFILE_PIC_PATH . '/' . $row->thumb;

        $newpicname = $userid . '_' . strtotime('now') . '_pic' . '.jpg';
        $newtmbname = $userid . '_' . strtotime('now') . '_tmb' . '.jpg';

        $config = JblanceHelper::getConfig();
        //$allowed = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png', 'image/gif', 'image/ico', 'image/x-icon');
        $type = $config->imgFileType;
        $allowed = explode(',', $type);
        $pwidth = $config->imgWidth;
        $pheight = $config->imgHeight;
        $maxsize = $config->imgMaxsize;
        if ($file['size'] > 0 && ($file['size'] / 1024 < $maxsize)) {
            if (!file_exists(JPATH_SITE . '/images/jblance')) {
                if (mkdir(JPATH_SITE . '/images/jblance')) {
                    JPath::setPermissions(JPATH_SITE . '/images/jblance', '0777');
                    if (file_exists(JPATH_SITE . '/images/index.html')) {
                        copy(JPATH_SITE . '/images/index.html', JPATH_SITE . '/images/jblance/index.html');
                    }
                }
            }
            if ($file['error'] != 0) {
                echo JText::_('COM_JBLANCE_UPLOAD_PHOTO_ERROR');
                exit;
            }
            if ($file['size'] == 0) {
                $file = null;
            }
            if (!in_array($file['type'], $allowed)) {
                $file = null;
                $response['result'] = 'NO';
                $response['msg'] = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
                echo json_encode($response);
                exit;
            }
            if ($file != null) {
                $dest = JBPROFILE_PIC_PATH . '/' . $newpicname; //echo $dest;exit;
                $dest_tmb = JBPROFILE_PIC_PATH . '/' . $newtmbname;

                if (JFile::exists($oldpicloc)) {
                    $delpic = unlink($oldpicloc);
                    $deltmb = unlink($oldtmbloc);
                }
                /* $soure = $file['tmp_name'];

                  $uploaded = JFile::upload($soure, $dest);
                  $fileAtr = getimagesize($dest);
                  $widthOri = $fileAtr[0];
                  $heightOri = $fileAtr[1];
                  $type = $fileAtr['mime'];
                  $img = false;
                  switch ($type){
                  case 'image/jpeg':
                  case 'image/jpg':
                  case 'image/pjpeg':
                  $img = imagecreatefromjpeg($dest);
                  break;
                  case 'image/ico':
                  $img = imagecreatefromico($dest);
                  break;
                  case 'image/x-png':
                  case 'image/png':
                  $img = imagecreatefrompng($dest);
                  break;
                  case 'image/gif':
                  $img = imagecreatefromgif($dest);
                  break;
                  }
                  if(!$img){
                  return false;
                  }
                  $curr = @getimagesize($dest);
                  $perc_w = $pwidth / $widthOri;
                  $perc_h = $pheight / $heightOri;
                  if(($widthOri < $pwidth) && ($heightOri < $pheight)){
                  //return;
                  }
                  if($perc_h > $perc_w){
                  $pwidth = $pwidth;
                  $pheight = round($heightOri * $perc_w);
                  }
                  else {
                  $pheight = $pheight;
                  $pwidth = round($widthOri * $perc_h);
                  }
                  $nwimg = imagecreatetruecolor($pwidth, $pheight);

                  if(($fileAtr[2] == IMAGETYPE_GIF) || ($fileAtr[2] == IMAGETYPE_PNG)){
                  $trnprt_indx = imagecolortransparent($img);
                  // If we have a specific transparent color
                  if($trnprt_indx >= 0){
                  $trnprt_color = imagecolorsforindex($img, $trnprt_indx);	// Get the original image's transparent color's RGB values
                  $trnprt_indx  = imagecolorallocate($nwimg, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);	// Allocate the same color in the new image resource
                  imagefill($nwimg, 0, 0, $trnprt_indx);			// Completely fill the background of the new image with allocated color.
                  imagecolortransparent($nwimg, $trnprt_indx);	// Set the background color for new image to transparent
                  }
                  // Always make a transparent background color for PNGs that don't have one allocated already
                  elseif($fileAtr[2] == IMAGETYPE_PNG){
                  imagealphablending($nwimg, false);	 // Turn off transparency blending (temporarily)
                  $color = imagecolorallocatealpha($nwimg, 0, 0, 0, 127);	 // Create a new transparent color for image
                  imagefill($nwimg, 0, 0, $color);	// Completely fill the background of the new image with allocated color.
                  imagesavealpha($nwimg, true);	 	// Restore transparency blending
                  }
                  }

                  imagecopyresampled($nwimg, $img, 0, 0, 0, 0, $pwidth, $pheight, $widthOri, $heightOri);

                  //create thumb
                  $tmb = @imagecreatetruecolor(64 ,64);
                  imagecopyresampled($tmb, $nwimg, 0, 0, 0, 0, 64, 64, $pwidth, $pheight);
                  //imagecopy($tmb, $nwimg, 0, 0, 0, 0, $pwidth, $pheight);

                  switch($fileAtr[2]){
                  case IMAGETYPE_GIF:
                  imagegif($nwimg, $dest);
                  imagegif($tmb, $dest_tmb);
                  break;
                  case IMAGETYPE_JPEG:
                  imagejpeg($nwimg, $dest);
                  imagejpeg($tmb, $dest_tmb);
                  break;
                  case IMAGETYPE_PNG:
                  imagepng($nwimg, $dest);
                  imagepng($tmb, $dest_tmb);
                  break;
                  default:
                  return false;
                  }

                  imagedestroy($tmb);
                  imagedestroy($nwimg);
                  imagedestroy($img);

                  $row->picture = $newpicname;
                  $row->thumb = $newtmbname;

                  // pre-save checks
                  if (!$row->check()){
                  JError::raiseError(500, $row->getError());
                  }
                  // save the changes
                  if (!$row->store()){
                  JError::raiseError(500, $row->getError());
                  }
                  $row->checkin(); */



// start upload file 				
                //	$userID = JRequest::getVar('userID'); // $this->getVendorid($user->id = 673);		
                //$userid = $jbuserid;
                $db = JFactory::getDBO();
                $q = "SELECT  `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '" . $userid . "' ";
                $db->setQuery($q);
                $vendor_id = $db->loadResult();

                //jimport('joomla.filesystem.file');
                //$image = JRequest::getVar('vendor_image', null, 'files', 'array');
                $image = $_FILES['photo'];


                $db = JFactory::getDBO();
                $image['name'] = JFile::makeSafe($image['name']);
                if ($image['name'] != '') {
                    //	echo "<pre>"; print_r($image); die;
                    /////// check if there allready is a vendor image
                    $imgisvalid = 1;
                    $q = "SELECT vm.`virtuemart_media_id` , vm.`file_url` , vm.`file_url_thumb` 
				FROM `#__virtuemart_medias` vm 
				LEFT JOIN `#__virtuemart_vendor_medias` vvm ON vvm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
				WHERE vvm.`virtuemart_vendor_id`='" . $vendor_id . "' ";
                    $db->setQuery($q);
                    $vendorimages = $db->loadRow();
                    $virtuemart_media_id = $vendorimages[0];
                    $file_url = $vendorimages[1];
                    $file_url_thumb = $vendorimages[2];
                    $vendorimage_path = 'images/stories/virtuemart/vendor/';
                    $vendorthumb_path = 'images/stories/virtuemart/vendor/resized/';
                    $infosImg = getimagesize($image['tmp_name']);
                    //if ( (substr($image['type'],0,5) != 'image' || $infosImg[0] > $maximgside || $infosImg[1] > $maximgside ) ){
                    if ((substr($image['type'], 0, 5) != 'image')) {
                        JError::raiseWarning(100, JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT'));
                        $imgisvalid = 0;
                    }
                    //JError::raiseWarning( 100,$imgisvalid);
                    $vendor_image = strtolower($user->id . "_" . $image['name']);
                    $target_imagepath = JPATH_BASE . '/' . $vendorimage_path . $vendor_image;
                    if ($imgisvalid) {
                        if (JFile::upload($image['tmp_name'], $target_imagepath)) {
                            $app->enqueueMessage('<i class="vmv-icon-ok"></i> ' . JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOADRENAME_SUCCESS') . ' ' . $vendor_image);
                        } else
                            JError::raiseWarning(100, JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_ERROR'));
                    }
                    $ext = JFile::getExt($image['name']);
                    $ext = strtolower($ext);
                    $ext = str_replace('jpeg', 'jpg', $ext);
                    //SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
                    switch ($ext) {
                        case 'jpg':
                            $source = imagecreatefromjpeg($target_imagepath);
                            $large_source = imagecreatefromjpeg($target_imagepath);
                            break;
                        case 'png':
                            $source = imagecreatefrompng($target_imagepath);
                            $large_source = imagecreatefrompng($target_imagepath);
                            break;
                        case 'gif':
                            $source = imagecreatefromgif($target_imagepath);
                            $large_source = imagecreatefromgif($target_imagepath);
                            break;
                        default:
                            //JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_INVALID') );
                            $imgisvalid = 0;
                            break;
                    }
                    if ($vendor_image != '' && $imgisvalid) {
                        list($width, $height) = getimagesize($target_imagepath);
                        if ($width > $maximgside) {
                            $resizedH = ( $maximgside * $height) / $width;
                            if ($ext == 'gif')
                                $largeone = imagecreate($maximgside, $resizedH);
                            else
                                $largeone = imagecreatetruecolor($maximgside, $resizedH);
                            imagealphablending($largeone, false);
                            imagesavealpha($largeone, true);
                            $transparent = imagecolorallocatealpha($largeone, 255, 255, 255, 127);
                            imagefilledrectangle($largeone, 0, 0, $maximgside, $resizedH, $transparent);
                            imagecopyresampled($largeone, $large_source, 0, 0, 0, 0, $maximgside, $resizedH, $width, $height);
                        }
                        else {
                            $largeone = $target_imagepath;
                        }
                        switch ($ext) {
                            case 'jpg':
                                imagejpeg($largeone, JPATH_BASE . '/' . $image_path . '/' . $vendor_image, $thumbqual);
                                break;
                            case 'jpeg':
                                imagejpeg($largeone, JPATH_BASE . '/' . $image_path . '/' . $vendor_image, $thumbqual);
                                break;
                            case 'png':
                                imagepng($largeone, JPATH_BASE . '/' . $image_path . '/' . $vendor_image);
                                break;
                            case 'gif':
                                imagegif($largeone, JPATH_BASE . '/' . $image_path . '/' . $vendor_image);
                                break;
                        }
                        imagedestroy($largeone);

                        if ($width >= $height) {
                            $resizedH = ($vmconfig_img_width * $height) / $width;
                            if ($ext == 'gif')
                                $thumb = imagecreate($vmconfig_img_width, $resizedH);
                            else
                                $thumb = imagecreatetruecolor($vmconfig_img_width, $resizedH);
                            imagealphablending($thumb, false);
                            imagesavealpha($thumb, true);
                            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
                            imagefilledrectangle($thumb, 0, 0, $vmconfig_img_width, $resizedH, $transparent);
                            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $vmconfig_img_width, $resizedH, $width, $height);
                        }
                        else {
                            $resizedW = ( VmConfig::get('img_height') * $width) / $height;
                            if ($ext == 'gif')
                                $thumb = imagecreate($resizedW, VmConfig::get('img_height'));
                            else
                                $thumb = imagecreatetruecolor($resizedW, VmConfig::get('img_height'));
                            imagealphablending($thumb, false);
                            imagesavealpha($thumb, true);
                            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
                            imagefilledrectangle($thumb, 0, 0, $resizedW, VmConfig::get('img_height'), $transparent);
                            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $resizedW, VmConfig::get('img_height'), $width, $height);
                        }
                        switch ($ext) {
                            case 'jpg':
                                imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path . '/' . $vendor_image, $thumbqual);
                                break;
                            case 'jpeg':
                                imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path . '/' . $vendor_image, $thumbqual);
                                break;
                            case 'png':
                                imagepng($thumb, JPATH_BASE . '/' . $thumb_path . '/' . $vendor_image);
                                break;
                            case 'gif':
                                imagegif($thumb, JPATH_BASE . '/' . $thumb_path . '/' . $vendor_image);
                                break;
                        }
                        imagedestroy($thumb);
                        if ($virtuemart_media_id != '') { // we updated the picture
                            // delete all media file
                            if ($file_url != $image_path . JFile::makeSafe($vendor_image)) { // only delete old file if new filename and old are diferent. If same file has allready been overwritten
                                if ($file_url != '')
                                    JFile::delete($file_url);
                                if ($file_url_thumb != '')
                                    JFile::delete($file_url_thumb);
                            }
                            $q = "UPDATE `#__virtuemart_medias` SET 
						 `file_title`='" . $db->escape($vendor_title) . "' , 
						 `file_mimetype`='" . $image['type'] . "' , 
						`file_url` = '" . $vendorimage_path . JFile::makeSafe($vendor_image) . "' , 
						 `file_url_thumb` ='" . $vendorthumb_path . JFile::makeSafe($vendor_image) . "' ,
						 `modified_on`='" . date('j F Y h:i:s A') . "' , 
						 `modified_by` ='" . $user->id . "' 
						 WHERE `virtuemart_media_id` ='" . $virtuemart_media_id . "' ";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                        else { // we insert the new file					
                            $q = "INSERT INTO `#__virtuemart_medias` 
							( `virtuemart_vendor_id` , `file_title` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_product_image` , `published` , `created_on` , `created_by`)
							VALUES
							(  '" . $vendor_id . "' , '" . $db->escape($vendor_title) . "' , '" . $image['type'] . "' , 'vendor' , '" . $vendorimage_path . JFile::makeSafe($vendor_image) . "' , '" . $vendorthumb_path . JFile::makeSafe($vendor_image) . "' , '1', '1' , '" . date('j F Y h:i:s A') . "' , '" . $user->id . "' )";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                            $virtuemart_media_id = $db->insertid();
                            $q = "INSERT INTO `#__virtuemart_vendor_medias` 
							( `virtuemart_vendor_id` , `virtuemart_media_id` )
							VALUES
							(  '" . $vendor_id . "' , '" . $virtuemart_media_id . "'  )";
                            $db->setQuery($q);
                            if (!$db->query())
                                die($db->stderr(true));
                        }
                    }
                }
                // end upload file 	


                $response['result'] = 'OK';
                $response['image'] = JBPROFILE_PIC_URL . $newpicname . '?' . time();
                $response['thumb'] = JBPROFILE_PIC_URL . $newtmbname . '?' . time();
                $response['width'] = $pwidth;
                $response['height'] = $pheight;
                $response['imgname'] = $newpicname;
                $response['tmbname'] = $newtmbname;
                $response['msg'] = JText::_('COM_JBLANCE_PICTURE_UPLOADED_SUCCESSFULLY');
                $response['return'] = JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture', false);
                echo json_encode($response);
                exit;
            }
        }
        else {
            if ($file['size'] / 1024 > $maxsize) {
                $response['result'] = 'NO';
                $response['msg'] = JText::sprintf('COM_JBLANCE_PICTURE_EXCEEDS_LIMIT', $maxsize);
                echo json_encode($response);
                exit;
            }
        }
    }

    public static function removePictureMedia() {
        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        $userid = $app->input->get('userid', 0, 'int');

        $jbuser = JblanceHelper::get('helper.user');
        $jbuserid = $jbuser->getUser($userid)->id;

        $row = JTable::getInstance('jbuser', 'Table');
        $row->load($jbuserid);

        $destpic = JBPROFILE_PIC_PATH . '/' . $row->picture;
        $desttmb = JBPROFILE_PIC_PATH . '/' . $row->thumb;

        $response = array();

        if (JFile::exists($destpic)) {
            $delpic = unlink($destpic);
            $deltmb = unlink($desttmb);

            $row->picture = '';
            $row->thumb = '';
            // pre-save checks
            if (!$row->check()) {
                JError::raiseError(500, $row->getError());
            }
            // save the changes
            if (!$row->store()) {
                JError::raiseError(500, $row->getError());
            }
            $row->checkin();

            $response['result'] = 'OK';
            $response['msg'] = JText::_('COM_JBLANCE_PICTURE_REMOVED_SUCCESSFULLY');
            echo json_encode($response);
            exit;
        } else {
            $response['result'] = 'NO';
            $response['msg'] = JText::_('COM_JBLANCE_FILE_DOES_NOT_EXIST');
            echo json_encode($response);
            exit;
        }
    }

    public static function cropPictureMedia() {
        $lang = JFactory::getLanguage();
        $lang->load('com_jblance', JPATH_SITE);

        $url = JBPROFILE_PIC_PATH . '/' . $_POST['imgLoc'];
        $tmb = JBPROFILE_PIC_PATH . '/' . $_POST['tmbLoc'];

        $response = array();

        if (JFile::exists($url)) {

            $width = $_POST['cropW'];
            $height = $_POST['cropH'];
            $left = $_POST['cropX'];
            $top = $_POST['cropY'];

            header("Content-type: image/jpg");
            $src = @imagecreatefromjpeg($url);
            $im = @imagecreatetruecolor($width, $height);

            imagecopy($im, $src, 0, 0, $left, $top, $width, $height);
            imagejpeg($im, $tmb, 100);
            imagedestroy($im);

            $response['result'] = 'OK';
            $response['msg'] = JText::_('COM_JBLANCE_THUMBNAIL_SAVED_SUCCESSFULLY');
            $response['return'] = JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture', false);
        } else {
            $response['result'] = 'NO';
            $response['msg'] = JText::_('COM_JBLANCE_ERROR_SAVING_THUMBNAIL');
        }
        echo json_encode($response);
        exit;
    }

    public static function getFileInfo($type, $id) {
        $db = JFactory::getDbo();
        $fileInfo = array();
        if ($type == 'portfolio') {
            $query = "SELECT attachment FROM #__jblance_portfolio WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $attachment = explode(";", $db->loadResult());
            $showName = $attachment[0];
            $fileName = $attachment[1];

            $fileInfo['fileUrl'] = JBPORTFOLIO_URL . $fileName;
            $fileInfo['filePath'] = JBPORTFOLIO_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        } elseif ($type == 'project') {
            $query = "SELECT file_name,show_name FROM #__jblance_project_file WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $projFile = $db->loadObject();
            $showName = $projFile->show_name;
            $fileName = $projFile->file_name;

            $fileInfo['fileUrl'] = JBPROJECT_URL . $fileName;
            $fileInfo['filePath'] = JBPROJECT_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        } elseif ($type == 'message') {
            $query = "SELECT attachment FROM #__jblance_message WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $attachment = explode(";", $db->loadResult());
            $showName = $attachment[0];
            $fileName = $attachment[1];

            $fileInfo['fileUrl'] = JBMESSAGE_URL . $fileName;
            $fileInfo['filePath'] = JBMESSAGE_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        } elseif ($type == 'nda') {
            $query = "SELECT attachment FROM #__jblance_bid WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $attachment = explode(";", $db->loadResult());
            $showName = $attachment[0];
            $fileName = $attachment[1];

            $fileInfo['fileUrl'] = JBBIDNDA_URL . $fileName;
            $fileInfo['filePath'] = JBBIDNDA_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        } elseif ($type == 'customfile') {
            $query = "SELECT value FROM #__jblance_custom_field_value WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $attachment = explode(";", $db->loadResult());
            $showName = $attachment[0];
            $fileName = $attachment[1];

            $fileInfo['fileUrl'] = JBCUSTOMFILE_URL . $fileName;
            $fileInfo['filePath'] = JBCUSTOMFILE_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        }

        return $fileInfo;
    }

    public static function getPorfolioFileInfo($type, $id, $attachmentColumnNum) {
        $db = JFactory::getDbo();
        $fileInfo = array();
        if ($type == 'portfolio') {
            $query = "SELECT $attachmentColumnNum FROM #__jblance_portfolio WHERE id=" . $db->quote($id);
            $db->setQuery($query);

            $attachment = explode(";", $db->loadResult());
            $showName = $attachment[0];
            $fileName = $attachment[1];

            $fileInfo['fileUrl'] = JBPORTFOLIO_URL . $fileName;
            $fileInfo['filePath'] = JBPORTFOLIO_PATH . '/' . $fileName;
            $fileInfo['fileName'] = $fileName;
            $fileInfo['showName'] = $showName;
        }

        return $fileInfo;
    }

    public static function downloadFile() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $type = $app->input->get('type', '', 'string');
        $id = $app->input->get('id', 0, 'int');
        $attach = $app->input->get('attachment', '', 'string');

        if ($type != 'portfolio')
            $fileInfo = self::getFileInfo($type, $id);
        else
            $fileInfo = self::getPorfolioFileInfo($type, $id, $attach);

        //print_r($fileInfo);exit;

        $filePath = $fileInfo['filePath'];
        $fileUrl = $fileInfo['fileUrl'];
        $showName = $fileInfo['showName'];

        self::setDownloadHeader($filePath, $fileUrl, $showName);
    }

    /**
     * @param json $attachments Attachments stored in db as json format
     * @param string $type Type can be service, project, portfolio 
     * @param boolean $defaultImg Yes to return default image location
     * @return array $return Returns array containing the file name, servername, size and location 
     */
    public static function processAttachment($attachments, $type, $defaultImg = true) {
        $return = array();
        $registry = new JRegistry;
        $registry->loadString($attachments);
        $files = $registry->toArray();
        $filePath = '';

        switch ($type) {
            case 'service':
                $fileUrl = JBSERVICE_URL;
                $filePath = JBSERVICE_PATH;
                break;
        }

        //if there is no attachment, send an array with default image location
        if (!empty($files)) {
            foreach ($files as $file) {
                $value = explode(';', $file);

                $obj['name'] = $value[0];
                $obj['servername'] = $value[1];
                $obj['size'] = $value[2];
                $obj['location'] = JFile::exists($filePath . '/' . $value[1]) ? $fileUrl . $value[1] : JPATH_COMPONENT . '/images/default_image.png';
                $obj['thumb'] = $fileUrl . 'thumb/' . $value[1];
                //$obj['location'] = $fileUrl.$value[1];
                $return[] = $obj;
            }
        } else {
            if ($defaultImg) {
                $obj['location'] = $obj['thumb'] = 'components/com_jblance/images/default_image.png';
                $return[] = $obj;
            }
        }
        return $return;
    }

    public static function renderImageCarousel($attachments, $type) {

        $files = self::processAttachment($attachments, $type, false);

        if (empty($files))
            return;

        $html = '';
        $html .= '<div id="myCarousel" class="carousel slide">';

        $html .= '<ol class="carousel-indicators">';
        foreach ($files as $key => $file) {
            $active = ($key == 0) ? 'class="active"' : '';
            $html .= '<li data-target="#myCarousel" data-slide-to="' . $key . '" ' . $active . '></li>';
        }
        $html .= '</ol>';

        $html .= '<div class="carousel-inner">';
        foreach ($files as $key => $file) {
            $active = ($key == 0) ? 'active' : '';
            $html .= '<div class="item ' . $active . '">';
            $html .= '<img class="img-polaroid" style="margin: auto;" src="' . $file['location'] . '" alt="">';
            $html .= '</div>';
        }
        $html .= '</div>';

        $html .= '<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>';
        $html .= '<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>';

        $html .= '</div>';

        return $html;
    }

    function setDownloadHeader($filePath, $fileUrl, $fileName) {
        $view_types = array();
        $view_types = explode(',', 'html,htm,txt,pdf,doc,jpg,jpeg,png,gif');

        clearstatcache();

        if (!file_exists($filePath))
            $len = 0;
        else
            $len = filesize($filePath);

        $filename = basename($filePath);
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $ctype = self::datei_mime($file_extension); //$ctype = 'application/force-download';
        ob_end_clean();

        // needed for MS IE - otherwise content disposition is not used?
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header("Cache-Control: public, must-revalidate");
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        // header("Pragma: no-cache");  // Problems with MS IE
        header("Expires: 0");
        header("Content-Description: File Transfer");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header("Content-Type: " . $ctype);
        header("Content-Length: " . (string) $len);

        if (!in_array($file_extension, $view_types))
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        else
            header('Content-Disposition: inline; filename="' . $fileName . '"'); // view file in browser

        header("Content-Transfer-Encoding: binary\n");

        @readfile($filePath);
        exit;
    }

    function datei_mime($filetype) {

        switch ($filetype) {
            case "ez": $mime = "application/andrew-inset";
                break;
            case "hqx": $mime = "application/mac-binhex40";
                break;
            case "cpt": $mime = "application/mac-compactpro";
                break;
            case "doc": $mime = "application/msword";
                break;
            case "bin": $mime = "application/octet-stream";
                break;
            case "dms": $mime = "application/octet-stream";
                break;
            case "lha": $mime = "application/octet-stream";
                break;
            case "lzh": $mime = "application/octet-stream";
                break;
            case "exe": $mime = "application/octet-stream";
                break;
            case "class": $mime = "application/octet-stream";
                break;
            case "dll": $mime = "application/octet-stream";
                break;
            case "oda": $mime = "application/oda";
                break;
            case "pdf": $mime = "application/pdf";
                break;
            case "ai": $mime = "application/postscript";
                break;
            case "eps": $mime = "application/postscript";
                break;
            case "ps": $mime = "application/postscript";
                break;
            case "xls": $mime = "application/vnd.ms-excel";
                break;
            case "ppt": $mime = "application/vnd.ms-powerpoint";
                break;
            case "wbxml": $mime = "application/vnd.wap.wbxml";
                break;
            case "wmlc": $mime = "application/vnd.wap.wmlc";
                break;
            case "wmlsc": $mime = "application/vnd.wap.wmlscriptc";
                break;
            case "vcd": $mime = "application/x-cdlink";
                break;
            case "pgn": $mime = "application/x-chess-pgn";
                break;
            case "csh": $mime = "application/x-csh";
                break;
            case "dvi": $mime = "application/x-dvi";
                break;
            case "spl": $mime = "application/x-futuresplash";
                break;
            case "gtar": $mime = "application/x-gtar";
                break;
            case "hdf": $mime = "application/x-hdf";
                break;
            case "js": $mime = "application/x-javascript";
                break;
            case "nc": $mime = "application/x-netcdf";
                break;
            case "cdf": $mime = "application/x-netcdf";
                break;
            case "swf": $mime = "application/x-shockwave-flash";
                break;
            case "tar": $mime = "application/x-tar";
                break;
            case "tcl": $mime = "application/x-tcl";
                break;
            case "tex": $mime = "application/x-tex";
                break;
            case "texinfo": $mime = "application/x-texinfo";
                break;
            case "texi": $mime = "application/x-texinfo";
                break;
            case "t": $mime = "application/x-troff";
                break;
            case "tr": $mime = "application/x-troff";
                break;
            case "roff": $mime = "application/x-troff";
                break;
            case "man": $mime = "application/x-troff-man";
                break;
            case "me": $mime = "application/x-troff-me";
                break;
            case "ms": $mime = "application/x-troff-ms";
                break;
            case "ustar": $mime = "application/x-ustar";
                break;
            case "src": $mime = "application/x-wais-source";
                break;
            case "zip": $mime = "application/x-zip";
                break;
            case "au": $mime = "audio/basic";
                break;
            case "snd": $mime = "audio/basic";
                break;
            case "mid": $mime = "audio/midi";
                break;
            case "midi": $mime = "audio/midi";
                break;
            case "kar": $mime = "audio/midi";
                break;
            case "mpga": $mime = "audio/mpeg";
                break;
            case "mp2": $mime = "audio/mpeg";
                break;
            case "mp3": $mime = "audio/mpeg";
                break;
            case "aif": $mime = "audio/x-aiff";
                break;
            case "aiff": $mime = "audio/x-aiff";
                break;
            case "aifc": $mime = "audio/x-aiff";
                break;
            case "m3u": $mime = "audio/x-mpegurl";
                break;
            case "ram": $mime = "audio/x-pn-realaudio";
                break;
            case "rm": $mime = "audio/x-pn-realaudio";
                break;
            case "rpm": $mime = "audio/x-pn-realaudio-plugin";
                break;
            case "ra": $mime = "audio/x-realaudio";
                break;
            case "wav": $mime = "audio/x-wav";
                break;
            case "pdb": $mime = "chemical/x-pdb";
                break;
            case "xyz": $mime = "chemical/x-xyz";
                break;
            case "bmp": $mime = "image/bmp";
                break;
            case "gif": $mime = "image/gif";
                break;
            case "ief": $mime = "image/ief";
                break;
            case "jpeg": $mime = "image/jpeg";
                break;
            case "jpg": $mime = "image/jpeg";
                break;
            case "jpe": $mime = "image/jpeg";
                break;
            case "png": $mime = "image/png";
                break;
            case "tiff": $mime = "image/tiff";
                break;
            case "tif": $mime = "image/tiff";
                break;
            case "wbmp": $mime = "image/vnd.wap.wbmp";
                break;
            case "ras": $mime = "image/x-cmu-raster";
                break;
            case "pnm": $mime = "image/x-portable-anymap";
                break;
            case "pbm": $mime = "image/x-portable-bitmap";
                break;
            case "pgm": $mime = "image/x-portable-graymap";
                break;
            case "ppm": $mime = "image/x-portable-pixmap";
                break;
            case "rgb": $mime = "image/x-rgb";
                break;
            case "xbm": $mime = "image/x-xbitmap";
                break;
            case "xpm": $mime = "image/x-xpixmap";
                break;
            case "xwd": $mime = "image/x-xwindowdump";
                break;
            case "msh": $mime = "model/mesh";
                break;
            case "mesh": $mime = "model/mesh";
                break;
            case "silo": $mime = "model/mesh";
                break;
            case "wrl": $mime = "model/vrml";
                break;
            case "vrml": $mime = "model/vrml";
                break;
            case "css": $mime = "text/css";
                break;
            case "asc": $mime = "text/plain";
                break;
            case "txt": $mime = "text/plain";
                break;
            case "gpg": $mime = "text/plain";
                break;
            case "rtx": $mime = "text/richtext";
                break;
            case "rtf": $mime = "text/rtf";
                break;
            case "wml": $mime = "text/vnd.wap.wml";
                break;
            case "wmls": $mime = "text/vnd.wap.wmlscript";
                break;
            case "etx": $mime = "text/x-setext";
                break;
            case "xsl": $mime = "text/xml";
                break;
            case "flv": $mime = "video/x-flv";
                break;
            case "mpeg": $mime = "video/mpeg";
                break;
            case "mpg": $mime = "video/mpeg";
                break;
            case "mpe": $mime = "video/mpeg";
                break;
            case "qt": $mime = "video/quicktime";
                break;
            case "mov": $mime = "video/quicktime";
                break;
            case "mxu": $mime = "video/vnd.mpegurl";
                break;
            case "avi": $mime = "video/x-msvideo";
                break;
            case "movie": $mime = "video/x-sgi-movie";
                break;
            case "asf": $mime = "video/x-ms-asf";
                break;
            case "asx": $mime = "video/x-ms-asf";
                break;
            case "wm": $mime = "video/x-ms-wm";
                break;
            case "wmv": $mime = "video/x-ms-wmv";
                break;
            case "wvx": $mime = "video/x-ms-wvx";
                break;
            case "ice": $mime = "x-conference/x-cooltalk";
                break;
            case "rar": $mime = "application/x-rar";
                break;
            default: $mime = "application/octet-stream";
                break;
        }
        return $mime;
    }

}
