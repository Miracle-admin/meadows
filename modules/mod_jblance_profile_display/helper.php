<?php

class modJblanceProfileDisplayHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
	 
	var $fileTypes = array("field_id_6"=>"image","field_id_12"=>"image","field_id_18"=>"image","field_id_23"=>"video","field_id_24"=>"video");
	
	var $fieldsets=array("apps"=>array(),"widgets"=>array() , "experience"=>array(),"qualifications"=>array(),"websites"=>array() ," videotour"=>array() ,"clienttestimonial"=>array() ,"socialmedia"=>array());
	
	var $linkFields=array("field_id_22"=>"Website","field_id_25"=>"Facebook","field_id_26"=>"Google+","field_id_27"=>"Twitter","field_id_28"=>"Linked In","field_id_29"=>"Behance","field_id_30"=>"Pintrest","field_id_31"=>"Dribble","field_id_5"=>"Project Url","field_id_11"=>"Project Link","field_id_17"=>"Company URL");
	
	
	
	var $prepairedFields = array();
	
	function __construct()
    {
	
	
	$model = JModelLegacy::getInstance('user', 'JblanceModel'); 
	$user= JFactory::getUser();
	$fields = $model->getEditProfile();
	$fields = $fields[2];
	
	
	foreach($fields as $fk=>$fv)
	{
	$rowlen = count($fv);
	
	//insert images
	if(array_key_exists($fk,$this->fileTypes) && $this->fileTypes[$fk] =="image")
	{
	for($i=0;$i<$rowlen;$i++)
	{
	if(!empty($fv[$i]))
	{
	$fields[$fk][$i]='<a href="media/developer/'.$user->id.'/'.$fv[$i].'" target="_blank" type="image" class="jcepopup" title="">
    <img  src="media/developer/'.$user->id.'/thumbnails/'.$fv[$i].'" alt="" />
    </a>';
	}
	}
	}
	
	//insert videos
	if(array_key_exists($fk,$this->fileTypes) && $this->fileTypes[$fk] =="video")
	{
	for($i=0;$i<$rowlen;$i++)
	{
	if(!empty($fv[$i]))
	{
	$path=JUri::root().'media/developer/'.$user->id.'/'.$fv[$i];
	
	
	$fields[$fk][$i]='<a href="'.$path.'" target="_blank" type="video/mp4" class="jcepopup" data-mediabox-controls="controls" data-mediabox-poster="images/logo.png" data-mediabox-height="483"  data-mediabox-width="854"><img src="images/film-strip.png"/></a>';
	}
	}
	}
	//prepare links
	
	if(array_key_exists($fk,$this->linkFields))
	{
	
	for($i=0;$i<$rowlen;$i++)
	{
	if(!empty($fv[$i]))
	{
	$path=JUri::root().'media/developer/'.$user->id.'/'.$fv[$i];
	$fields[$fk][$i]='<a href="'.$fv[$i].'" target="_blank" >'.$this->linkFields[$fk].'</a>';
	}
	}
	}
	
	
	
	$this->prepairedFields = $this->filterSubscription($fields);
	
	
	
	}
	
	
	
	
	}
	
	function getFieldGroups($fieldname)
	{
	
	return $this->prepairedFields;
	
	}
	
	private function filterSubscription($field)
	{
	$benefit = JblanceHelper::get('helper.planbenefits');
	
	$silverAccess=$benefit ->isSubscribed("silver");
	
    $goldAccess=$benefit->isSubscribed("gold");
	
    $platinumAccess=$benefit->isSubscribed("platinum");
	
	echo $platinumAccess;
	return $field;
	
	}
}

?>