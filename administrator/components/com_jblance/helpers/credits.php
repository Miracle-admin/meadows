<?php
class CreditsHelper 
{
/*
1. plugin_function
2. referrerid
3. onetime
4. rid
5. datareference
6. randompoints
7. feedback
8. force
9. frontmessage

example
$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helper.php';
include_once($api_jb);
$credits=JblanceHelper::get('helper.credits');
$credits::UpdateCredits(array("","","","","Appmeadows payment",99000000,"","","$99000000 has been credited to your account."));

*/
static function UpdateCredits($params)
{
$defaults=array('plgaup_appmeadowscredits', $referrerid='', $onetime=false,$rid='', $datareference='', $randompoints=0, $feedback=true, $force=0, $frontmessage='' );
$params=array_filter($params);
$params=$params+$defaults;

$plugin_function       = $params[0];
$referrerid            = $params[1];
$onetime               = $params[2];
$rid                   = $params[3];
$datareference         = $params[4];
$randompoints          = $params[5];
$feedback              = $params[6];
$force                 = $params[7];
$frontmessage          = $params[8];



$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
if ( file_exists($api_AUP))
{
$keyreference='';
require_once ($api_AUP);
if($onetime)
{ 
$keyreference = AlphaUserPointsHelper::buildKeyreference($plugin_function, $rid );
}


return AlphaUserPointsHelper::newpoints($plugin_function, $referrerid, $keyreference, $datareference, $randompoints, $feedback, $force, $frontmessage);
}  
}
}
?>