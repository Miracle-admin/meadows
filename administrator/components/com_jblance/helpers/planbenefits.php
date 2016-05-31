<?php

/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	helpers/select.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class PlanbenefitsHelper {

    public $plans = array("r" => 13, "s1" => 2, "s2" => 14, "s3" => 15, "g1" => 11, "g2" => 16, "g3" => 17, "p1" => 12, "p2" => 18, "p3" => 19);
    public $subHeaders = array();

    public function __construct() {
        $db = JFactory::getDbo();
        $query = "SELECT id,title FROM #__jblance_planbenefits WHERE level=1 ORDER BY lft ";
        $db->setQuery($query);
        $pH = $db->loadObjectList();

        for ($i = 0; $i < count($pH); $i++) {
            $this->subHeaders[$pH[$i]->title] = $pH[$i]->id;
        }
    }

    public function getAllPlans() {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_plan where id != 5 ";
        $db->setQuery($query);
        $db->setQuery($query);
        $return = $db->loadObjectList();
        // echo '<pre>'; print_r($return);

        return $plans = array(
            "r" => $return[0]->id, "s1" => $return[1]->id,
            "s2" => $return[4]->id, "s3" => $return[5]->id,
            "g1" => $return[3]->id, "g2" => $return[6]->id,
            "g3" => $return[7]->id, "p1" => $return[2]->id,
            "p2" => $return[8]->id, "p3" => $return[9]->id,
            array(
                "r" => $return[0]->pidbt, "s1" => $return[1]->pidbt,
                "s2" => $return[4]->pidbt, "s3" => $return[5]->pidbt,
                "g1" => $return[3]->pidbt, "g2" => $return[6]->pidbt,
                "g3" => $return[7]->pidbt, "p1" => $return[2]->pidbt,
                "p2" => $return[8]->pidbt, "p3" => $return[9]->pidbt,
            )
        );
    }

    function getallPlanBenefits($key, $hid) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_benefitaccess WHERE plan_id=" . $this->plans[$key] . " AND ben_parent_id=" . $hid . " ORDER BY sort";
        $db->setQuery($query);
        $db->setQuery($query);
        $return = $db->loadObjectList();
        return $return;
    }

    function getPlanHeaders() {
        $plans = $this->plans;
        $config = JblanceHelper::getConfig();

        foreach ($plans as $key => $value) {
            $plan = JTable::getInstance('plan', 'Table');

            $plan->load($value);
            if ($plan && $plan->pidbt) {
                $btdata = JblanceHelper::getBraintreePlan($plan->pidbt);


                $params = json_decode($plan->params);
                $return[$key] = array('id' => $btdata['id'], 'planname' => $btdata['name'], 'heading' => $params->heading, 'short_desc' => $btdata['description'],
                    'price' => $btdata['price'], 'currencySymbol' => $config->currencySymbol, 'currencyCode' => $config->currencyCode);
            }
        }

        return $return;
    }

    function getBenefitDetails($id) {
        $planbenefit = JTable::getInstance('planbenefits', 'Table');
        $planbenefit->load($id);
        return $planbenefit;
    }

    function getHeaderChildren($hid) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_planbenefits WHERE parent_id=" . $hid . " ORDER BY lft";
        $db->setQuery($query);
        $db->setQuery($query);
        $return = $db->loadObjectList();
        return $return;
    }

    function getPlanDetails($key) {
        $plan = JTable::getInstance('plan', 'Table');
        $plan->load($this->plans[$key]);
        return array('plan' => $plan, 'params' => json_decode($plan->params));
    }

    function getPlanListing() {
        $config = JblanceHelper::getConfig();

        $subValues;
        $headerChildren;

        foreach ($this->subHeaders as $shkey => $shvalue) {

            $children = $this->getHeaderChildren($shvalue);



            $regular = $this->getallPlanBenefits('r', $shvalue);


            $silver = $this->getallPlanBenefits('s1', $shvalue);


            $gold = $this->getallPlanBenefits('g1', $shvalue);


            $platinum = $this->getallPlanBenefits('p1', $shvalue);

            $subValues[$shkey] = array(
                "Regular" => $regular,
                "Silver" => $silver,
                "Gold" => $gold,
                "Platinum" => $platinum
            );

            $headerChildren[$shkey] = $children;
        }

        return array('sub_values' => $subValues, 'headers' => $headerChildren);
    }

    function isSubscribed($needle) {
        $user = JFactory::getUser();
        $plan = JblanceHelper::whichPlan($user->id);
        $planWeight = array("regular" => 0, "silver" => 1, "gold" => 2, "platinum" => 3);
        $pn = $plan->name;
        $pn = explode("-", $pn);
        $pn = $pn[0];
        $pn = strtolower($pn);

        $priorityCurr = $planWeight[$pn];
        $priorityNeedle = $planWeight[$needle];
        if ($priorityCurr >= $priorityNeedle)
            return true;
        else
            return false;
    }
    
       function isBtSubscribed($needle) {
        $user = JFactory::getUser();
        $plans = JblanceHelper::getBtPlan($user->id);//whichPlan($user->id);
//echo '<pre>'; print_r($plans); die;
        if(($plans['name'] == "Platinum Geek") && $plans['status'] == "Active" )
            $plan = "platinum";
         if(($plans['name'] == "Golden Geek") && $plans['status'] == "Active" )
            $plan = "gold";
          if(($plans['name'] == "Silver Geek") && $plans['status'] == "Active" )
            $plan = "silver";
         //echo $plan ; die; 
          if($plan == $needle)
              return true;
          return false;

    }

}
