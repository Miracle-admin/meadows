<?php
/**
 * @version    $Id: myauth.php 7180 2007-04-23 16:51:53Z jinx $
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
/**
 * Example Authentication Plugin.  Based on the example.php plugin in the Joomla! Core installation
 *
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
class PlgAppmeadowsApmprofview extends JPlugin
{
    /**
     * This method should handle any authentication and report back to the subject
     * This example uses simple authentication - it checks if the password is the reverse
     * of the username (and the user exists in the database).
     *
     * @access    public
     * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
     * @param     array     $options        Array of extra options
     * @param     object    $response       Authentication response object
     * @return    boolean
     * @since 1.5
     */
    function onProfileviewed($type,$viewing,$viewer)
    {
	$user=JFactory::getUser();
	//if not viewing own profile
	if($viewing!=0 && $viewing!=$user->id)
	{
	

	
    $ip_addr= $this->get_client_ip();
	
	//check if already seen
	
	$allreadySeen=$this->alreadySeen($ip_addr,$viewing,$viewer,$type);
	
	
	if(!$allreadySeen)
	{
	$update=$this->updateCount($ip_addr,$type,$viewing,$viewer);
	$incrementCounter=$this->incrementCounter($viewing,$viewer,$type);
    }
	
	

    }
	
    }
	
	
	function alreadySeen($ip_addr,$viewing,$viewer,$type)
	{
    $db=JFactory::getDbo();
	
	if($type!="app")
	{
	
    $query =$viewer!=0?"SELECT * FROM #__jblance_profvisits WHERE visit_to='".$viewing."' AND visit_from='".$viewer."'  AND visit_type='profile'":"SELECT * FROM #__jblance_profvisits WHERE visitor_ip='".$ip_addr."'  AND visit_type='profile'";
	}
	else
	{
	$query =$viewer!=0?"SELECT * FROM #__jblance_profvisits WHERE visit_to='".$viewing."' AND visit_from='".$viewer."'  AND visit_type='app'":"SELECT * FROM #__jblance_profvisits WHERE visitor_ip='".$ip_addr."'  AND visit_type='app'";
	
	}
	$db->setQuery($query);
	$db->execute();
	
    $num_rows = $db->getNumRows();
	
	if($num_rows > 0 )
	{
	if($this->IsLastUpdate($viewer,$ip_addr))
	{
	return true;
	}
	else
	{
	return false;
	}
	}
	else
	{
	return false;
	} 
	}
	
	//function to calculate the last visit time
	function IsLastUpdate($uId,$vistor_ip)
      {

      $user=JFactory::getUser(); 
      $db = JFactory::getDbo();

      $query=$uid!=0?"SELECT visit_date FROM #__jblance_profvisits WHERE visit_from  = '".$user->id."' ORDER BY  id DESC":"SELECT visit_date FROM #__jblance_profvisits WHERE visitor_ip  = '".$vistor_ip."' ORDER BY  id DESC";
	  
	  $db->setQuery($query);
      $lastUpdate =  $db->loadObject();
      $lastUpdate=strtotime($lastUpdate->visit_date);

      $i_2_hours = strtotime('now -2 hours');
      if ($lastUpdate > $i_2_hours) 
      { 
      return true;
      }
      else
      {
      return false;
      } 

      }
	
	//function to get the ip address of the client
	function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
	
	//function to update the count.
	function updateCount($ip_addr,$type,$viewing,$viewer)
	{
	$db=JFactory::getDbo();
	$query="INSERT INTO #__jblance_profvisits(visitor_ip,visit_to,visit_from,visit_type) VALUES ('".$ip_addr."','".$viewing."','".$viewer."','".$type."')";
	
	$db->setQuery($query);
    return $db->execute();
	

	}

	//function to increment the counter
    function incrementCounter($viewing,$viewer,$type)
    {
	 
    $db=JFactory::getDbo();
    
	if($type!="app")
	{//increment the counter for viewing
	$query	="INSERT INTO #__jblance_visitcounts
  (uid , tome)
VALUES
  ('".$viewing."','1')
ON DUPLICATE KEY UPDATE
  tome     = tome + 1 ";

   $db->setQuery($query);
   $db->execute();

$this->updateDateSlots($viewing,$viewer);
 
 //update the corresponding slots in the database

    //increment the counter for viewer
if($viewer!=0)
	{
	
	jimport( 'joomla.access.access' );
    $groups = JAccess::getGroupsByUser($viewer);
   if(in_array(13,$groups))
   {
	
	
	$query	="INSERT INTO #__jblance_visitcounts
  (uid , byme)
VALUES
  ('".$viewer."','1')
ON DUPLICATE KEY UPDATE
  byme     = byme + 1 ";

  $db->setQuery($query);
   $db->execute();
   
   $this->updateDateSlots($viewer,$viewer);
	
	}
	

}
}
else
{
$query	="INSERT INTO #__jblance_visitcounts
  (uid , tomyapps)
VALUES
  ('".$viewing."','1')
ON DUPLICATE KEY UPDATE
  tomyapps     = tomyapps + 1 ";

   $db->setQuery($query);
   $db->execute();
   $this->updateAppDateSlots($viewing);
}
 }

 //function to calculate the date slots
function CalculateDateSlots($uid,$uidv)
{

$db=JFactory::getDbo();
 
$return=array();
$returnt=array();
 
//calculate date slots
$six_daysago = date('Y-m-d H:i:s', strtotime('-6 days', strtotime(date("Y-m-d H:i:s"))));

$twelve_daysago = date('Y-m-d H:i:s', strtotime('-12 days', strtotime(date("Y-m-d H:i:s"))));

$eighteen_daysago = date('Y-m-d H:i:s', strtotime('-18 days', strtotime(date("Y-m-d H:i:s"))));

$twentyfour_daysago = date('Y-m-d H:i:s', strtotime('-24 days', strtotime(date("Y-m-d H:i:s"))));

$one_monthago = date('Y-m-d H:i:s', strtotime('-1 month', strtotime(date("Y-m-d H:i:s"))));
  

//0count 6 days hit to me
$query= "SELECT count(*) AS sixdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$six_daysago."' AND NOW( ) AND visit_to='".$uid."' AND visit_type='profile'"; 

$db->setQuery($query);
$return['to_me_6d'] = $db->loadObject()->sixdays;

//1count 12 days hit to me	
$query= "SELECT count(*) AS twelvedays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twelve_daysago."' AND '".$six_daysago."' AND visit_to='".$uid."' AND visit_type='profile'"; 
$db->setQuery($query);
$return['tome_12d'] = $db->loadObject()->twelvedays;


//2count 18 days hits to me
$query= "SELECT count(*) AS eighteendays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$eighteen_daysago."' AND '".$twelve_daysago."' AND visit_to='".$uid."' AND visit_type='profile'"; 
$db->setQuery($query);
$return['tome_18d'] = $db->loadObject()->eighteendays;


//3count 24 days hits to me
$query= "SELECT count(*) AS twentyfourdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twentyfour_daysago."' AND '".$eighteen_daysago."' AND visit_to='".$uid."' AND visit_type='profile'"; 
$db->setQuery($query);
$return['tome_24d'] = $db->loadObject()->twentyfourdays;

//4count monthly hits to me
$query= "SELECT count(*) AS onemonth
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$one_monthago."' AND '".$twentyfour_daysago."' AND visit_to='".$uid."' AND visit_type='profile'"; 
$db->setQuery($query);
$return['tome_1m'] = $db->loadObject()->onemonth;

######################################## hits by me below ###########################################

//5count 6 days hit by me
$query= "SELECT count(*) AS sixdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$six_daysago."' AND NOW( ) AND visit_from='".$uidv."' AND visit_type='profile'"; 

$db->setQuery($query);
$returnt['byme_6d'] = $db->loadObject()->sixdays;

//6count 12 days hit by me	
$query= "SELECT count(*) AS twelvedays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twelve_daysago."' AND '".$six_daysago."' AND visit_from='".$uidv."' AND visit_type='profile'"; 
$db->setQuery($query);
$returnt['byme_12d'] = $db->loadObject()->twelvedays;


//7count 18 days hits by me
$query= "SELECT count(*) AS eighteendays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$eighteen_daysago."' AND '".$twelve_daysago."' AND visit_from='".$uidv."' AND visit_type='profile'"; 
$db->setQuery($query);
$returnt['byme_18d'] = $db->loadObject()->eighteendays;


//8count 24 days hits by me
$query= "SELECT count(*) AS twentyfourdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twentyfour_daysago."' AND '".$eighteen_daysago."' AND visit_from='".$uidv."' AND visit_type='profile'"; 
$db->setQuery($query);
$returnt['byme_24d'] = $db->loadObject()->twentyfourdays;

//9count monthly hits by me
$query= "SELECT count(*) AS onemonth
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$one_monthago."' AND '".$twentyfour_daysago."' AND visit_from='".$uidv."' AND visit_type='profile'";  
$db->setQuery($query);
$returnt['byme_1m'] = $db->loadObject()->onemonth;

return array($return,$returnt);
}

//update date slots
function updateDateSlots($id,$idviewer)
{
 $db        = JFactory::getDbo();
 $dateSlots =  $this->CalculateDateSlots($id,$idviewer);
 $dateSlotsm = $dateSlots[0];
 $dateSlotst=  $dateSlots[1];
 $dateSlotsmk = array_keys($dateSlotsm);
 $dateSlotstk = array_keys($dateSlotst);

 $querym = $db->getQuery(true);
 $queryt = $db->getQuery(true);
 
// Fields to update.
$fieldsm = array(
    $db->quoteName($dateSlotsmk[0]) . ' = ' . $db->quote($dateSlotsm[$dateSlotsmk[0]]),
    $db->quoteName($dateSlotsmk[1]) . ' = ' . $db->quote($dateSlotsm[$dateSlotsmk[1]]),
	$db->quoteName($dateSlotsmk[2]) . ' = ' . $db->quote($dateSlotsm[$dateSlotsmk[2]]),
	$db->quoteName($dateSlotsmk[3]) . ' = ' . $db->quote($dateSlotsm[$dateSlotsmk[3]]),
	$db->quoteName($dateSlotsmk[4]) . ' = ' . $db->quote($dateSlotsm[$dateSlotsmk[4]]));
	
	
$fieldst = array(
    $db->quoteName($dateSlotstk[0]) . ' = ' . $db->quote($dateSlotst[$dateSlotstk[0]]),
	$db->quoteName($dateSlotstk[1]) . ' = ' . $db->quote($dateSlotst[$dateSlotstk[1]]),
	$db->quoteName($dateSlotstk[2]) . ' = ' . $db->quote($dateSlotst[$dateSlotstk[2]]),
	$db->quoteName($dateSlotstk[3]) . ' = ' . $db->quote($dateSlotst[$dateSlotstk[3]]),
	$db->quoteName($dateSlotstk[4]) . ' = ' . $db->quote($dateSlotst[$dateSlotstk[4]]));
 
// Conditions for which records should be updated.
$conditionsm = array(
    $db->quoteName('uid') . ' = '.$id, 
    );
 
 $conditionst = array(
    $db->quoteName('uid') . ' = '.$idviewer, 
    );
 
$querym->update($db->quoteName('#__jblance_visitcounts'))->set($fieldsm)->where($conditionsm);
 
$db->setQuery($querym);
 
$db->execute();

$queryt->update($db->quoteName('#__jblance_visitcounts'))->set($fieldst)->where($conditionst);
 
$db->setQuery($queryt);
 
$db->execute();

 }	
	function updateAppDateSlots($viewer)
	{
	$db = JFactory::getDbo();
    $return=array();
 
    //calculate date slots
    $six_daysago = date('Y-m-d H:i:s', strtotime('-6 days', strtotime(date("Y-m-d H:i:s"))));

    $twelve_daysago = date('Y-m-d H:i:s', strtotime('-12 days', strtotime(date("Y-m-d H:i:s"))));

    $eighteen_daysago = date('Y-m-d H:i:s', strtotime('-18 days', strtotime(date("Y-m-d H:i:s"))));

    $twentyfour_daysago = date('Y-m-d H:i:s', strtotime('-24 days', strtotime(date("Y-m-d H:i:s"))));

    $one_monthago = date('Y-m-d H:i:s', strtotime('-1 month', strtotime(date("Y-m-d H:i:s"))));
  

    //0count 6 days hit to me
    $query= "SELECT count(*) AS sixdays
    FROM  `#__jblance_profvisits` 
    WHERE visit_date between '".$six_daysago."' AND NOW( ) AND visit_to='".$viewer."' AND visit_type='app'"; ; 

    $db->setQuery($query);
    $return['tomyapps_6d'] = $db->loadObject()->sixdays;
	//1count 12 days hit to me	
    $query= "SELECT count(*) AS twelvedays
    FROM  `#__jblance_profvisits` 
    WHERE visit_date between '".$twelve_daysago."' AND '".$six_daysago."' AND visit_to='".$viewer."' AND visit_type='app'"; 
    $db->setQuery($query);
    $return['tomyapps_12d'] = $db->loadObject()->twelvedays;


    //2count 18 days hits to me
    $query= "SELECT count(*) AS eighteendays
    FROM  `#__jblance_profvisits` 
    WHERE visit_date between '".$eighteen_daysago."' AND '".$twelve_daysago."' AND visit_to='".$viewer."' AND visit_type='app'"; 
    $db->setQuery($query);
    $return['tomyapps_18d'] = $db->loadObject()->eighteendays;


    //3count 24 days hits to me
    $query= "SELECT count(*) AS twentyfourdays
    FROM  `#__jblance_profvisits` 
    WHERE visit_date between '".$twentyfour_daysago."' AND '".$eighteen_daysago."' AND visit_to='".$viewer."' AND visit_type='app'"; 
    $db->setQuery($query);
    $return['tomyapps_24d'] = $db->loadObject()->twentyfourdays;

   //4count monthly hits to me
   $query= "SELECT count(*) AS onemonth
   FROM  `#__jblance_profvisits` 
   WHERE visit_date between '".$one_monthago."' AND '".$twentyfour_daysago."' AND visit_to='".$viewer."' AND visit_type='app'"; 
   
    $db->setQuery($query);
    $return['tomyapps_1m'] = $db->loadObject()->onemonth;
   
    $returnKeys=array_keys($return);
    $queryapp = $db->getQuery(true);
	
	$fieldsapp = array(
    $db->quoteName($returnKeys[0]) . ' = ' . $db->quote($return[$returnKeys[0]]),
    $db->quoteName($returnKeys[1]) . ' = ' . $db->quote($return[$returnKeys[1]]),
	$db->quoteName($returnKeys[2]) . ' = ' . $db->quote($return[$returnKeys[2]]),
	$db->quoteName($returnKeys[3]) . ' = ' . $db->quote($return[$returnKeys[3]]),
	$db->quoteName($returnKeys[4]) . ' = ' . $db->quote($return[$returnKeys[4]]));

   $conditionsapp = array(
    $db->quoteName('uid') . ' = '.$viewer, 
    );
 
 
 
   $queryapp->update($db->quoteName('#__jblance_visitcounts'))->set($fieldsapp)->where($conditionsapp);
 
   $db->setQuery($queryapp);
 
   $db->execute();
   
	}
}
?>