 <?php
 
 
 function incrementCounter($viewing,$viewer)
    {
	 
    $db=JFactory::getDbo();
    
	//increment the counter for viewing
	$query	="INSERT INTO #__jblance_visitcounts
  (uid , tome)
VALUES
  ('".$viewing."','1')
ON DUPLICATE KEY UPDATE
  tome     = tome + 1 ";

   $db->setQuery($query);
   $db->execute();

$this->updateDateSlots($viewing);
 
 //update the corresponding slots in the database

    //increment the counter for viewer
if($viewer!=0)
	{
	$query	="INSERT INTO #__jblance_visitcounts
  (uid , byme)
VALUES
  ('".$viewer."','1')
ON DUPLICATE KEY UPDATE
  byme     = byme + 1 ";

  $db->setQuery($query);
   $db->execute();
   
   $this->updateDateSlots($viewer);
	
	}

 }

function CalculateDateSlots($uid)
{

$db=JFactory::getDbo();
 
$return=array();
 
//calculate date slots
$six_daysago = date('Y-m-d H:i:s', strtotime('-6 days', strtotime(date("Y-m-d H:i:s"))));

$twelve_daysago = date('Y-m-d H:i:s', strtotime('-12 days', strtotime(date("Y-m-d H:i:s"))));

$eighteen_daysago = date('Y-m-d H:i:s', strtotime('-18 days', strtotime(date("Y-m-d H:i:s"))));

$twentyfour_daysago = date('Y-m-d H:i:s', strtotime('-24 days', strtotime(date("Y-m-d H:i:s"))));

$one_monthago = date('Y-m-d H:i:s', strtotime('-1 month', strtotime(date("Y-m-d H:i:s"))));
  

//count 6 days hit	
$query= "SELECT count(*) AS sixdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$six_daysago."' AND NOW( ) AND visit_to='".$uid."'"; 

$db->setQuery($query);
$return['to_me_6d'] = $db->loadObject()->sixdays;

//count 12 days hit 	
$query= "SELECT count(*) AS twelvedays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twelve_daysago."' AND '".$six_daysago."' AND visit_to='".$uid."'";
$db->setQuery($query);
$return['tome_12d'] = $db->loadObject()->twelvedays;


//count 18 days hits
$query= "SELECT count(*) AS eighteendays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$eighteen_daysago."' AND '".$twelve_daysago."' AND visit_to='".$uid."'";
$db->setQuery($query);
$return['tome_18d'] = $db->loadObject()->eighteendays;


//count 24 days hits
$query= "SELECT count(*) AS twentyfourdays
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$twentyfour_daysago."' AND '".$eighteen_daysago."' AND visit_to='".$uid."'";
$db->setQuery($query);
$return['tome_24d'] = $db->loadObject()->twentyfourdays;

//count monthly hits
$query= "SELECT count(*) AS onemonth
FROM  `#__jblance_profvisits` 
WHERE visit_date between '".$one_monthago."' AND '".$twentyfour_daysago."' AND visit_to='".$uid."'"; 
$db->setQuery($query);
$return['tome_1m'] = $db->loadObject()->onemonth;


return $return;
}

//update date slots
function updateDateSlots($id)
{
 $db        = JFactory::getDbo();
 $dateSlots =  $this->CalculateDateSlots($id);
 $dateSlotsK = array_keys($dateSlots);

 $query = $db->getQuery(true);
 
// Fields to update.
$fields = array(
    $db->quoteName($dateSlotsK[0]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[0]]),
    $db->quoteName($dateSlotsK[1]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[1]]),
	$db->quoteName($dateSlotsK[2]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[2]]),
	$db->quoteName($dateSlotsK[3]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[3]]),
	$db->quoteName($dateSlotsK[4]) . ' = ' . $db->quote($dateSlots[$dateSlotsK[4]])
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('uid') . ' = '.$id, 
    );
 
$query->update($db->quoteName('#__jblance_visitcounts'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();
 }	
 
 ?>