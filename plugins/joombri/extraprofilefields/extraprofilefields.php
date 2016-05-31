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
class PlgJoombriExtraprofilefields extends JPlugin {

    var $app;
    var $validateFileFields = array("field_id_6" => "image", "field_id_12" => "image", "field_id_18" => "image", "field_id_23" => "video", "field_id_24" => "mp4");
    var $validateLinkFields = array("field_id_22", "field_id_17", "field_id_11", "field_id_5", "field_id_25", "field_id_26", "field_id_27", "field_id_28", "field_id_29", "field_id_30", "field_id_31");
    var $validateTextFields = array("field_id_1", "field_id_2", "field_id_7", "field_id_8", "field_id_13", "field_id_14", "field_id_19");
    var $validateTextAreaFields = array("field_id_4", "field_id_10", "field_id_16", "field_id_21");
    var $validateDateFields = array("field_id_3", "field_id_9", "field_id_15", "field_id_20");
    var $exts = array("image" => array("png", "jpg", "jpeg"), "video" => array("mp4"), "mp4" => array("mp4"));
    var $mimes = array("image" => array("image/png", "image/jpeg"), "video" => array("video/mp4"), "mp4" => array("video/mp4"));
    var $availablePlans = array("regular", "gold", "silver", "platinum");
    var $max_file_size = array("image" => 5242880, "video" => 20971520, "mp4" => 20971520); //5mb,20mb
    var $extrfields;
    var $fileFields;

    function onUserSaveProfile($jbuser, $param, $extraFields) {
    
        $user = JFactory::getUser();
        $this->app = JFactory::getApplication();
        $this->extrfields = $extraFields;
        $this->app = JFactory::getApplication();
        $this->fileFields = $_FILES;
//        $plan = JblanceHelper::whichPlan($user->id);
//        $plan = explode('-', $plan->name);
//        $plan = strtolower($plan[0]);
         $benefit = JblanceHelper::get('helper.planbenefits');
 
        $silverAccess = $benefit->isBtSubscribed("silver");
         $goldAccess = $benefit->isBtSubscribed("gold");
        $platinumAccess = $benefit->isBtSubscribed("platinum");
        $plan = '';
        if (!$silverAccess && !$goldAccess && !$platinumAccess)
            $plan = "regular"; 
  
        if ($plan != "regular") {
//        if ($plan != "regular" && in_array($plan, $this->availablePlans)) {
//            switch ($plan) {
            if ($silverAccess){
                for ($i = 13; $i <= 31; $i++) {
                    $this->extrfields["field_id_" . $i] = "";
                    if (array_key_exists("field_id_" . $i, $this->validateFileFields))
                        $this->fileFields["field_id_" . $i] = "";
                }
            }
            if ($goldAccess){
                for ($i = 23; $i <= 31; $i++) {
                    $this->extrfields["field_id_" . $i] = "";
                    if (array_key_exists("field_id_" . $i, $this->validateFileFields))
                        $this->fileFields["field_id_" . $i] = "";
                }
            }

//            switch ($plan) {
//                case "silver":
//                    for ($i = 13; $i <= 31; $i++) {
//                        $this->extrfields["field_id_" . $i] = "";
//                        if (array_key_exists("field_id_" . $i, $this->validateFileFields))
//                            $this->fileFields["field_id_" . $i] = "";
//                    }
//                    break;
//                case "gold":
//                    for ($i = 23; $i <= 31; $i++) {
//                        $this->extrfields["field_id_" . $i] = "";
//                        if (array_key_exists("field_id_" . $i, $this->validateFileFields))
//                            $this->fileFields["field_id_" . $i] = "";
//                    }
//                    break;
//            }
            //validate the fields using primordial techniques.
            foreach ($this->extrfields as $ek => $ev) {

                //validate text fields
                if (is_array($ev) && in_array($ek, $this->validateTextFields)) {
                    $this->validteField($ek, "text");
                }

                //validate text area fields
                if (is_array($ev) && in_array($ek, $this->validateTextAreaFields)) {
                    $this->validteField($ek, "textarea");
                }

                //validate date fields
                if (is_array($ev) && in_array($ek, $this->validateDateFields)) {
                    $this->validteField($ek, "date");
                }

                //validate link fields
                if (is_array($ev) && in_array($ek, $this->validateLinkFields)) {
                    $this->validteField($ek, "link");
                }

                //validate file fields
                if (array_key_exists($ek, $this->validateFileFields)) {
                    $this->validteField($ek, "file");
                }
            }

            //save the form fields
 
            if ($this->saveForm($this->extrfields, $this->fileFields)){
                 
               // $this->app->redirect(JRoute::_(JUri::root() . "index.php?option=com_jblance&view=user&layout=editprofile&Itemid=371", $msg = 'Successfully Saved', $msgType = 'message'));
          $link = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=371', false);
        $msg = JText::_('COM_JBLANCE_PROFILE_SAVED_SUCCESSFULLY');
        $this->app->redirect($link, $msg);
            }
            }
    }

    function validteField($ek, $type) {

        $valid = 1;
        $backUrl = JUri::root() . "index.php?option=com_jblance&view=user&layout=editprofile&Itemid=371";
        $entry = $this->extrfields[$ek];
        //validate text fields
        if ($type == "text") {
            $valid = $this->validateText($entry);
        }

        //validate textarea fields
        if ($type == "textarea") {
            $valid = $this->validateTextarea($entry);
        }
        //validate date fields
        if ($type == "date") {
            $valid = $this->validateDate($entry);
        }
        //validate link fields
        if ($type == "link") {
            $valid = $this->validateLink($entry);
        }
        //validate files
        if ($type == "file" && is_array($this->fileFields[$ek])) {
            $entry = $this->fileFields[$ek];

            $name = $entry['name'];
            $name = array_filter($name);
            $keys = array_keys($name);


            if (!empty($name)) {
                $fileType = $this->validateFileFields[$ek];
                for ($j = 0; $j < count($name); $j++) {
                    $keyEnt = $keys[$j];
                    $fname = $name[$keyEnt];
                    $ftype = $entry["type"][$keyEnt];
                    $ftmp_name = $entry["tmp_name"][$keyEnt];

                    $ferror = $entry["error"][$keyEnt];
                    $fsize = $entry["size"][$keyEnt];

                    $ext = pathinfo($fname, PATHINFO_EXTENSION);



                    //check for valid extension
                    if (!in_array($ext, $this->exts[$fileType])) {
                        $this->app->enqueueMessage('Invalid file type.', 'error');
                        $valid = 0;
                    }
                    //check for mime_content_type
                    if (!in_array($ftype, $this->mimes[$fileType])) {
                        $this->app->enqueueMessage('Invalid file content.', 'error');
                        $valid = 0;
                    }
                    //check for error
                    if ($ferror != 0) {
                        $this->app->enqueueMessage('Error uploading file.' . $ferror, 'error');
                        $valid = 0;
                    }
                    //check for size
                    if ($fsize > $this->max_file_size[$fileType]) {
                        $this->app->enqueueMessage('File size too large.', 'error');
                        $valid = 0;
                    }
                }
            }
        }
        if (!$valid)
            $this->app->redirect($backUrl);
    }

    function saveForm($postfields, $formfiles) {


        $user = JFactory::getUser();
        $row = JTable::getInstance('jbfields', 'Table');
        $row->load(array("userid" => $user->id));
        $isNew = $row->userid == "" ? true : false;
        $row->userid = $isNew ? $user->id : $row->userid;

        $data = array();
       // $fileData = $this->saveFiles($formfiles);


        foreach ($postfields as $pfk => $pfv) {
            if (!array_key_exists($pfk, $this->validateFileFields)) {

                $rffields = unserialize($row->$pfk);
                $rffield = array_filter($rffields);
               //echo '<pre>'; print_r($pfv); 
                
               // print_r($rffields);
                $rallFields = !empty($rffield) ? $pfv + $rffields : $pfv;
                $row->$pfk = serialize($rallFields);
            }
        }


        foreach ($fileData as $fdk => $fdv) {
            $ffield = unserialize($row->$fdk);
            $allFields = !empty($ffield) ? $ffield + $fdv : $fdv;
            $row->$fdk = serialize($allFields);
        }

        $row = $this->removeFile($row);


        if ($row->store())
            return true;
        return false;
    }

    function saveFiles($formfiles) {

        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $user = JFactory::getUser();
        $path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'developer' . DIRECTORY_SEPARATOR . $user->id;
        $pathThumb = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'developer' . DIRECTORY_SEPARATOR . $user->id . DIRECTORY_SEPARATOR . 'thumbnails';
        $files = array();
        if (!JFolder::exists($path))
            JFolder::create($path, $mode = 0755);

        if (!JFolder::exists($pathThumb))
            JFolder::create($pathThumb, $mode = 0755);

        $formfiles = array_filter($formfiles);



        foreach ($formfiles as $ffk => $ffv) {
            $length = count(array_filter($ffv['name']));
            if ($length != 0)
                $filteredFiles[$ffk] = $ffv;
        }



        foreach ($filteredFiles as $flfk => $flfv) {
            $upname = array_filter($flfv['name']);
            $upKeys = array_keys($upname);
            //upload the files
            for ($i = 0; $i < count($upname); $i++) {
                $keyUp = $upKeys[$i];
                $ext = pathinfo($upname[$keyUp], PATHINFO_EXTENSION);
                $ext = md5($flfk . $user->id . $keyUp) . '.' . $ext;
                $temp = $flfv['tmp_name'][$keyUp];
                if (JFile::upload($temp, $path . "/" . $ext)) {

                    $files[$flfk][$keyUp] = $ext;
                    JblanceHelper::createThumbs($path . "/" . $ext, $pathThumb);
                }
            }
        }

        return $files;
    }

    function removeFile($data) {

        jimport('joomla.filesystem.file');
        $user = JFactory::getUser();
        $path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'developer' . DIRECTORY_SEPARATOR . $user->id;
        $pathThumb = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'developer' . DIRECTORY_SEPARATOR . $user->id . DIRECTORY_SEPARATOR . 'thumbnails';
        $removedMedia = JRequest::getVar("removedmedia", "");


        if (!empty($removedMedia)) {
            $removedMedia = explode(',', $removedMedia);

            foreach ($removedMedia as $rmk => $rmv) {
                $remm = explode("-", $rmv);

                $length = count($remm);
                if ($length == 2 && property_exists($data, $remm[0])) {
                    $Remdata = unserialize($data->$remm[0]);
                    JFile::delete($path . DIRECTORY_SEPARATOR . $Remdata[$remm[1]]);
                    JFile::delete($pathThumb . DIRECTORY_SEPARATOR . $Remdata[$remm[1]]);

                    unset($Remdata[$remm[1]]);

                    $data->$remm[0] = serialize($Remdata);
                }
            }
        }
        return $data;
    }

    //primordial technique of validating text length.
    function validateText($entry) {
        $return = 1;
        $errors = array();
        foreach ($entry as $eval) {
            if (strlen($eval) > 50) {
                array_push($errors, 0);
            }
        }
        if (in_array(0, $errors)) {
            $this->app->enqueueMessage("Only 50 characters are allowed in the text field,Unable to save form.");
            $return = 0;
        }
        return $return;
    }

    //primordial technique of validating textarea .
    function validateTextarea($entry) {
        $return = 1;
        $errors = array();
        foreach ($entry as $eval) {
            if (strlen($eval) > 500) {
                array_push($errors, 0);
            }
        }
        if (in_array(0, $errors)) {
            $this->app->enqueueMessage("Only 500 characters are allowed in the description field,Unable to save form.");
            $return = 0;
        }
        return $return;
    }

    //primordial technique of validating link.
    function validateLink($entry) {
        $return = 1;
        $errors = array();
        $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

        foreach ($entry as $url) {
            if (!empty($url)) {
                if (!preg_match($pattern, $url)) {
                    array_push($errors, 0);
                }
            }
        }
        if (in_array(0, $errors)) {
            $this->app->enqueueMessage("Invalid link,Unable to save form.");
            $return = 0;
        }
        return $return;
    }

    //primordial technique of validating date.
    function validateDate($entry) {
        $return = 1;
        $errors = array();
        $Curryear = date("Y");
        $belowRange = 1980;
        foreach ($entry as $date) {
            if (!empty($date)) {
                if (!(($belowRange <= $date) && ($date <= $Curryear))) {
                    array_push($errors, 0);
                }
            }
        }

        if (in_array(0, $errors)) {
            $this->app->enqueueMessage("Invalid Date Value,Unable to save form.");
            $return = 0;
        }
        return $return;
    }

    public function onProfileProgress($points, $userid, $degrade = false) {

        if (!$userid)
            return false;
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__jblance_profilecompleted WHERE uid=" . $userid;
        $db->setQuery($query);
        $res = $db->loadResult();
        //   echo '<pre>';        print_r($res); die;
        if ($res) {
            $object = new stdClass();
            if ($degrade && ($res->completed - $points) > 0) {
                $object->completed = $res->completed - $points;
                $object->uid = $userid;
                $object->id = $res->id;
            } else if ($res->completed + $points < 100) {
                $object->completed = $res->completed + $points;
                $object->uid = $userid;
                $object->id = $res->id;
            } else {
                return false;
            }
            $result = JFactory::getDbo()->updateObject('#__jblance_profilecompleted', $object, 'id');
            return true;
        } else {
            if ($points >= 10 && $points <= 100 && !$degrade) {
                $query = 'insert into #__jblance_profilecompleted (uid, completed) values("' . $userid . '", "' . $points . '")';
                $db->setQuery($query);
                $res = $db->query();
            } else {
                return false;
            }
        }
        return true;
    }

}

function escapeZero($zero) {
    return($zero != "");
}

?>