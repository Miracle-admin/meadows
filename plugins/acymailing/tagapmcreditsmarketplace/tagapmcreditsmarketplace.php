<?php

class plgAcymailingTagapmcreditsmarketplace extends JPlugin
{

	function plgAcymailingApmcreditsmarketplace(&$subject, $config){
		parent::__construct($subject, $config);

		//This is just to fix a bug with an old version of Joomla where the params where not loaded
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'example');
			$this->params = new JParameter( $plugin->params );
		}
	}

		//This function will enable you to display a new tab in the tag interface (when you click Newsletter->create->tags)
		//If you don't want an interface on the tag system, just remove this function, this is not mandatory
	 function acymailing_getPluginType() {

	 	$onePlugin = new stdClass();
		//Tab name for the tag interface
	 	$onePlugin->name = 'Appmeadows Credits And Marketplace Tags';
		//Name of the function which will be triggered if your tab is selected on our tag interface
		//The value for this variable should change for each plugin, don't forget to make it unique for your own plugin!! (this is the function name you will use just below)
	 	$onePlugin->function = 'acymailingtagapmcreditsmarketplace_show';
		//Help url on our website... this will be only useful if you send us a documentation of your plugin
	 	//$onePlugin->help = 'plugin-example';

	 	return $onePlugin;
	 }

	//This is the function name I specified in the previous $onePlugin->function argument.
	//This function will be triggered if my tab is selected on the interface
	//If you don't want an interface on the tag system, just remove this function
	 function acymailingtagapmcreditsmarketplace_show(){
	 ?>
	 <script language="javascript" type="text/javascript">
			function applyTag(tagname){
				var string = '{apmcmtag:'+tagname;
				
				string += '}';
				setTag(string);
				insertTag();
			}
		</script>
	 <?php
         if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php'))
		 {
         echo 'This code can not work without the AcyMailing Component';
         return false;
         }
	  
	    $jbtags = (array)acymailing_get('helper.credmkt');
		
		$text = '<table class="adminlist table table-striped table-hover" cellpadding="1">';
		 
		 $k = 0;
		foreach($jbtags as $fk=> $fieldname){
			
			$text .= '<tr  onclick="applyTag(\''.$fk.$type.'\');" ><td class="acytdcheckbox"></td><td>'.$fk.'</td><td>'.$fieldname.'</td></tr>';
			$k = 1-$k;
		}
		
    $text .= '</table>';
	$text.="<div><p style='color:red;'><b>All tags are contextual.</b></p>
	<p><b style='color:red;'>*tag is only available during user registration.</b></p><p><b><a style='color:green;' target='_blank' href='".JUri::base()."index.php?option=com_acymailing&ctrl=newsletter&task=viewContextualTagsCredmkt'>Click here</a></b> To view available tags in different contexts.</p></div>";
	
	echo $text;

	
	 }

	 //This function will be triggered on the preview screen and during the send process to replace personal global tags
	 //Any tag which is not user specific should be replaced by this function in order to optimize the process
	 //The last argument $send indicates if the message will be send (set to true) or displayed as a preview (set to false)
	function acymailing_replacetags(&$email,$send = true){
	
	
		/* //You should replace tags in the three following variables:
		$email->body = str_replace('{tagexample}','my string',$email->body); //HTML version of the Newsletter
		$email->altbody = str_replace('{tagexample}','my string',$email->altbody); //text version of the Newsletter
		$email->subject = str_replace('{tagexample}','my string',$email->subject); //Subject of the Newsletter */
	}

	//This function will be triggered during the send process and on the preview screen to replace personal tags (user specific information)
	//If you don't want to replace personal tags (specific to the user), then you can delete this function
	//The last argument $send indicates if the message will be send (set to true) or displayed as a preview (set to false)
	function acymailing_replaceusertags(&$email,&$user,$send = true){
	
	
		//You should replace tags in the three following variables:
		$email->body = str_replace('{tagexampleuser}','my string for'.$user->email,$email->body); //HTML version of the Newsletter
		$email->altbody = str_replace('{tagexampleuser}','my string for'.$user->email,$email->altbody); //text version of the Newsletter
		$email->subject = str_replace('{tagexampleuser}','my string for'.$user->email,$email->subject); //Subject of the Newsletter
	}

	//This function is triggered when an auto-Newsletter has to be generated.
	//You can replace tags from there but the main purpose is to block or not the Newsletter generation.
	//For example you may want to block the Newsletter if you don't have a new content on your website
	function acymailing_generateautonews(&$email){

		$return = new stdClass();
		$return->status = true;
		$return->message = '';

		//Do you want to generate the Newsletter from the auto-Newsletter?
		$generate = false;
		if(!$generate){
			$return->status = false;
			$return->message = 'The generation is blocked because...';
		}
		return $return;
	}

}//endclass