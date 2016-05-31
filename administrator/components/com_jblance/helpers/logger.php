<?php
class LoggerHelper 
{
/*
1. logfile
2. setLogLevel
3. logcategory
4. message
5. infoType
6. extension

example
$api_jb = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jblance'.DS.'helper.php';
include_once($api_jb);
$logger=JblanceHelper::get('helper.logger');
$logger::addLogs(array("logs.php",JLog::ALL,"log_category","meeage",JLog::WARNING,"com_alphauserpoints"));

*/
static function addLogs($params)
{
jimport('joomla.log.log');


$defaults=array("global_logs.php",JLog::ALL,"globallogs","Ann error has occured",JLog::WARNING,"");
$params=array_filter($params);
$params=$params+$defaults;

$logfile       = $params[0];
$setLogLevel   = $params[1];
$logcategory   = $params[2];
$message       = $params[3];
$infoType      = $params[4];
$extension     = $params[5];
$log           = true;

if($extension)
{
jimport('joomla.application.component.helper');
$paramsExt =  JComponentHelper::getParams($extension);

$log = $paramsExt->get('enable_logging') != 1?false:true;
$elog = $paramsExt->get('email_logging') != 1?false:true;
}
if($log)
{
JLog::addLogger(
       array(
          'text_file' => $logfile
       ),
       // Sets messages of all log levels to be sent to the file
       $setLogLevel,
      
       array($logcategory)
   );
   
 /*  echo "message = ".$message."<br>";
  echo "infoType = ".$infoType."<br>"; 
  echo "message = ".$message."<br>";   
   die; */
 if($elog)  
 {
 $receipt = array();
 for($i=1;$i<=5;$i++)
 {
 $reccount = "el_r".$i;
 $receiptent = $paramsExt->get($reccount);
 if($receiptent!="")
 {
  $receipt[]= $receiptent;
 }

 
 }

 if(count($receipt)>0)
 {
$config = JFactory::getConfig();
$sender = array( 
    "Appmeadows auto logger",
    "Appmeadows auto logger" 
);
 $mailer = JFactory::getMailer();
$mailer->setSender($sender);
$mailer->addRecipient($receipt);
$body   = $message; 
$mailer->setSubject('New logs received');
$mailer->setBody($body);

$send = $mailer->Send();
 }
 }
 
JLog::add($message, $infoType , $logcategory);

}

}
}
?>