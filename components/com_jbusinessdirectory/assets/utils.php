<?php
/*------------------------------------------------------------------------
 # JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

if (!function_exists('dump')) {
	function dump()
	{
		$args = func_get_args();

		echo '<pre>';

		foreach ($args as $arg) {
			var_dump($arg);
		}
		echo '</pre>';
		//exit;
	}
}

function dbg( $text )
{
	echo "<pre>";
	var_dump($text);
	echo "</pre>";
}


class JBusinessUtil{

	var $applicationSettings ;

	private function __construct()
	{

	}

	public static function getInstance()
	{
		static $instance;
		if ($instance === null) {
			$instance = new JBusinessUtil();
		}
		return $instance;
	}

	public static function getApplicationSettings(){
		$instance = JBusinessUtil::getInstance();

		if(!isset($instance->applicationSettings)){
			$instance->applicationSettings = self::getAppSettings();
		}
		return $instance->applicationSettings;
	}
	
	static function getAppSettings(){
		$db		= JFactory::getDBO();
		$query	= "	SELECT fas.*, df.*, c.currency_name, c.currency_id FROM #__jbusinessdirectory_applicationsettings fas
					inner join  #__jbusinessdirectory_date_formats df on fas.date_format_id=df.id
					inner join  #__jbusinessdirectory_currencies c on fas.currency_id=c.currency_id";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObject();
	}

	public static function loadClasses(){
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');


		//load payment processors
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'payment'.DS.'processors';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}

		//load payment processors
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'payment';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}

		//load services
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'services';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}
	}


	public static function getURLData($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function getCoordinates($zipCode){
		$limitCountries = array();
		$location = null;
		
		$url ="http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($zipCode);
		$data = file_get_contents($url);
		$search_data = json_decode($data);
		if(!empty($search_data)){
			$lat =  $search_data->results[0]->geometry->location->lat;
			$lng =  $search_data->results[0]->geometry->location->lng;
			
			if(!empty($limitCountries)){
				foreach($search_data->results as $result){
					$country = "";
					foreach($result->address_components as $addressCmp){
						if(!empty($addressCmp->types) && $addressCmp->types[0]=="country"){
							$country = $addressCmp->short_name;
						}
					}
					if(in_array($country, $limitCountries)){
						$lat =  $result->geometry->location->lat;
						$lng =  $result->geometry->location->lng;
					}
				}
			}
		
			$location =  array();
			$location["latitude"] = $lat;
			$location["longitude"] = $lng;
		}
		
		return $location;
	}


	public static function parseDays($days){
		$date1 = time();
		$date2 = strtotime("+$days day");

		$diff = abs($date2 - $date1);

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		$result = new stdClass();

		$result->days = $days;
		$result->months = $months;
		$result->years = $years;

		return $result;
	}

	
	static function getComponentName(){
		$componentname = JRequest::getVar('option');
		return $componentname;
	}
	
	static function makePathFile($path)
	{
		$path_tmp = str_replace( '\\', DIRECTORY_SEPARATOR, $path );
		$path_tmp = str_replace( '/', DIRECTORY_SEPARATOR, $path_tmp);
		return $path_tmp;
	}
	
	static function convertTimeToMysqlFormat($time){
		if(empty($time))
			return null;
		$strtotime = strtotime($time);
		$time = date('H:i:s',$strtotime);
		return $time;
	}
	
	static function convertTimeToFormat($time){
		if(empty($time))
			return null;
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$strtotime = strtotime($time);
		$time = date($appSettings->time_format,$strtotime);
		return $time;
	}
	
	static function convertToFormat($date){
		if(isset($date) && strlen($date)>6 && $date!="0000-00-00" && $date!="00-00-0000"){
			try{
				$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
				$date = substr($date,0,10);
				list($yy,$mm,$dd)=explode("-",$date);
				if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)){
					$date = date($appSettings->dateFormat, strtotime($date));
				}else{
					$date="";
				}
			}catch(Exception $e){
				$date="";
			}
		}
		return $date;
	}
	
	static function convertToMysqlFormat($date){
		if(isset($date) && strlen($date)>6){
			$date = date("Y-m-d", strtotime($date));
		}
		return $date;
	}
	
	static function getDateGeneralFormat($data){
		$dateS="";
		if(isset($data) && strlen($data)>6  && $data!="0000-00-00"){
			//$data =strtotime($data);
			//setlocale(LC_ALL, 'de_DE');
			//$dateS = strftime( '%e %B %Y', $data );
			$date = JFactory::getDate($data);
			$dateS = $date->format('j F Y');
			//$dateS = date( 'j F Y', $data );
		}
	
		return $dateS;
	}
	
	static function getDateGeneralShortFormat($data){
		$dateS="";
		if(isset($data) && strlen($data)>6  && $data!="0000-00-00"){
			//$data =strtotime($data);
			//$dateS = strftime( '%e %b %Y', $data );
			//$dateS = date( 'j M Y', $data );
			$date = JFactory::getDate($data);
			$dateS = $date->format('j M Y');
		}
	
		return $dateS;
	}
	
	static function getDateGeneralFormatWithTime($data){
		if(empty($data)){
			return null;
		}
		$data =strtotime($data);
		$dateS = date( 'j M Y  G:i:s', $data );
	
		return $dateS;
	}
	
	static function getTimeText($time){
		$result = date('g:iA', strtotime($time));
		
		return $result;
	}
	
	static function get(){
	
	}
	
	static function loadModules($position){
		require_once(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php');
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		$db =JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__modules WHERE position='$position' AND published=1 ORDER BY ordering");
		$modules = $db->loadObjectList();
		if( count( $modules ) > 0 )
		{
			foreach( $modules as $module )
			{
				//just to get rid of that stupid php warning
				$module->user = '';
				$params = array('style'=>'xhtml');
				echo $renderer->render($module, $params);
			}
		}
	}
	
	static function getItemIdS(){
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$menu = $app->getMenu();
		$itemid="";
		
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		if(isset($activeMenu)){
			$itemid= JFactory::getApplication()->getMenu()->getActive()->id;
		}
		
		$defaultMenu = $menu->getDefault($lang->getTag());
		if(!empty($defaultMenu) && $itemid == $defaultMenu->id){
			$itemid	= "";
		}
		$itemidS="";
		if(!empty($itemid)){
			$itemidS = '&Itemid='.$itemid;
		}
		
		return $itemidS;
	}
	
	static function getCompanyLink($company, $addIndex=null){
		$itemidS = self::getItemIdS();
			
		$companyAlias = trim($company->alias);
		$companyAlias = stripslashes(strtolower($companyAlias));
		$companyAlias = str_replace(" ", "-", $companyAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
	
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		if(!$appSettings->enable_seo){
			$companyLink = $company->id;
			if(JFactory::getConfig()->get("sef")){
				$companyLink = $company->id;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&companyId='.$companyLink.$itemidS);
		}else{
			if($appSettings->add_url_id == 1){ 
				$companyLink = $company->id."-".htmlentities(urlencode($companyAlias));
			}else{
				$companyLink = htmlentities(urlencode($companyAlias));
			}
			
			if($appSettings->listing_url_type==2){
				$categoryPath = self::getBusinessCategoryPath($company);
				$path="";
			
				foreach($categoryPath as $cp){
					$path = $path. JApplication::stringURLSafe($cp->name)."/";
				}
				$companyLink=strtolower($path).$companyLink;
			}else if($appSettings->listing_url_type==3){
				$companyLink= strtolower($company->county)."/".strtolower($company->city)."/".$companyLink;
			}
			
			$url = JURI::base().$index.$companyLink;
		}
	
		return $url;
	}
	
	static function getCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
				
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$categoryLink = $categoryId;
		
		
		if(!$appSettings->enable_seo){
			$categoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=search&categoryId='.$categoryLink.$itemidS);
		}else{
			if($appSettings->add_url_id == 1){ 
				$categoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$categoryLink = htmlentities(urlencode($categoryAlias));
			}
			$url = JURI::base().$index.CATEGORY_URL_NAMING."/".$categoryLink;
		}
		
		return $url;
	}
	
	static function getOfferCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$offerCategoryLink = $categoryId;
		
		if(!$appSettings->enable_seo){
			$offerCategoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=offers&categoryId='.$offerCategoryLink.$itemidS);
		}else{
			if($appSettings->add_url_id == 1){
				$offerCategoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$offerCategoryLink =htmlentities(urlencode($categoryAlias));
			}
			
			$url = JURI::base().$index.OFFER_CATEGORY_URL_NAMING."/".$offerCategoryLink;
		}
		
		return $url;
	}
	
	static function getEventCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$menu = $app->getMenu();
		$itemid="";
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		if(isset($activeMenu)){
			$itemid= JFactory::getApplication()->getMenu()->getActive()->id;
		}
		
		if($itemid == $menu->getDefault($lang->getTag())->id){
			$itemid	= "";
		}
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
			
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$eventCategoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=events&categoryId='.$eventCategoryLink.'&Itemid='.$itemid);
		}else{
			if($appSettings->add_url_id == 1){
				$eventCategoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$eventCategoryLink = htmlentities(urlencode($categoryAlias));
			}
				
			$url = JURI::base().$index.EVENT_CATEGORY_URL_NAMING."/".$eventCategoryLink;
		}
	
		return $url;
	}
	
	static function getOfferLink($offerId, $offerAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
			
		$offerAlias = trim($offerAlias);
		$offerAlias = stripslashes(strtolower($offerAlias));
		$offerAlias = str_replace(" ", "-", $offerAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$offerLink = $offerId;
		
		if(!$appSettings->enable_seo){
			$offerLink = $offerId;
			if(JFactory::getConfig()->get("sef")){
				$offerLink = $offerId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=offer&offerId='.$offerLink.$itemidS);
		}else{
			if($appSettings->add_url_id == 1){
				$offerLink = $offerId."-".htmlentities(urlencode($offerAlias));
			}else{
				$offerLink = htmlentities(urlencode($offerAlias));
			}
				$url = JURI::base().$index.OFFER_URL_NAMING."/".$offerLink;
		}
		
		return $url;
	}
	
	static function getEventLink($eventId, $eventAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$eventAlias = trim($eventAlias);
		$eventAlias = stripslashes(strtolower($eventAlias));
		$eventAlias = str_replace(" ", "-", $eventAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$eventLink = $eventId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $eventId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=event&eventId='.$eventLink.$itemidS);
		}else{
			if($appSettings->add_url_id == 1){
				$eventLink = $eventId."-".htmlentities(urlencode($eventAlias));
			}else{
				$eventLink = htmlentities(urlencode($eventAlias));
			}
			$url = JURI::base().$index.EVENT_URL_NAMING."/".$eventLink;
		}
	
		return $url;
	}
	
	static function isJoomla3(){
		$version = new JVersion();
		$versionA =  explode(".", $version->getShortVersion());
		if($versionA[0] =="3"){
			return true;
		}
		return false;
	}
	
	
	static function truncate($text, $length, $suffix = '&hellip;', $isHTML = true){
		$i = 0;
		$tags = array();
		if($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach($m as $o){
				if($o[0][1] - $i >= $length)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				if($t[0] != '/')
					$tags[] = $t;
				elseif(end($tags) == substr($t, 1))
				array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}
	
		$output = substr($text, 0, $length = min(strlen($text),  $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	
		// Get everything until last space
		$one = substr($output, 0, strrpos($output, " "));
		// Get the rest
		$two = substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
		// Extract all tags from the last bit
		preg_match_all('/<(.*?)>/s', $two, $tags);
		// Add suffix if needed
		if (strlen($text) > $length) {
			$one .= $suffix;
		}
		// Re-attach tags
		$output = $one . implode($tags[0]);
	
		return $output;
	}
	
	static function getAlias($title, $alias){
		if (empty($alias) || trim($alias) == ''){
			$alias = $title;
		}
		
		$alias = JApplication::stringURLSafe($alias);
		
		if (trim(str_replace('-', '', $alias)) == ''){
			$alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		
		return $alias;
	}
	
	static function getAddressText($company){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$address="";
		if(isset($company->publish_only_city) && $company->publish_only_city){
			$address=$company->city.' '.$company->county;
			return $address;
		}					
		
		if(isset($company->street_number)){
			if($appSettings->address_format==0){
				$address = $company->street_number.' '.$company->address;
			}else{
				$address = $company->address.' '.$company->street_number;
			}
		}
		
		if($appSettings->address_format==1){
			if(!empty($company->postalCode) || $company->city){
				$address .=",";
			}
			
			if(!empty($company->postalCode)){
				$address .= " ".$company->postalCode;
			}
			
			if(!empty($company->city)){
				$address .= " ".$company->city;
			}
		}
		
		if($appSettings->address_format==0){
			if(!empty($company->postalCode) || $company->city){
				$address .=",";
			}
			
			if(!empty($company->city)){
				$address .= " ".$company->city;
			}
			
			if(!empty($company->postalCode)){
				$address .= " ".$company->postalCode;
			}
			
		}
	
		
		if(!empty($company->county)){
			$address .= ", ".$company->county;
		}
		
		
		return $address;
	}
	
	static function getLocationAddressText($street_number,$address, $city, $county, $postalCode ){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$address="";
	
		if(isset($street_number)){
			if($appSettings->address_format==0){
				$address = $street_number.' '.$address;
			}else{
				$address = $address.' '.$street_number;
			}
		}
	
		if($appSettings->address_format==1){
			if(!empty($postalCode) || $city){
				$address .=",";
			}
				
			if(!empty($postalCode)){
				$address .= " ".$postalCode;
			}
				
			if(!empty($city)){
				$address .= " ".$city;
			}
		}
	
		if($appSettings->address_format==0){
			if(!empty($postalCode) || $city){
				$address .=",";
			}
				
			if(!empty($city)){
				$address .= " ".$city;
			}
				
			if(!empty($postalCode)){
				$address .= " ".$postalCode;
			}
				
		}
	
	
		if(!empty($county)){
			$address .= ", ".$county;
		}
	
	
		return $address;
	}
	
	static function getLocationText($item){
		$location="";
	
		if(!empty($item->address)){
			$location .= $item->address;
		}
		
		if(!empty($item->city)){
			$location .= ", ".$item->city;
		}
		if(!empty($item->county)){
			$location .= ", ".$item->county;
		}
		
		if(empty($item->address) && empty($item->city) && !empty($item->locaiton)){
			$location = $item->locaiton; 
		}
	
		return $location;
	}
	

	static function getBusinessCategoryPath($company){
		
		$categories = self::getCategories();
		
		$category = null;
		$categoryId = 0;
		if(!empty($company->mainSubcategory)){
			//dump($company->mainSubcategory);
			$categoryId = $company->mainSubcategory;
		}else{
			$categoryIds = explode(",",$company->categoryIds);
			$categoryId = $categoryIds[0];
		}
	
		$category = self::getCategory($categories, $categoryId);
		$path=array();
		if(!empty($category)){
			$path[]=$category;
		
			while($category->parent_id != 1){
				if(!$category->parent_id)
					break;
				$category=self::getCategory($categories, $category->parent_id);
				$path[] = $category;
			}
				
			$path = array_reverse($path);
		}
		
		return $path;
	}
	
	static function getCategories(){
		$instance = JBusinessUtil::getInstance();
		
		if(!isset($instance->categories)){
			JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
			$categoryTable =JTable::getInstance("Category","JBusinessTable");
			$categories = $categoryTable->getAllCategories();
			$instance->categories = $categories;
		}
		return $instance->categories;
	}
	
	static function getCategory($categories, $categoryId){
		if(empty($categories) || empty($categoryId))
			return null;
		
		foreach($categories as $category){
			if($category->id == $categoryId){
				return $category;
			}
		}
		return null;
	}
	
	static function getLanguages(){
		$languages = JLanguage::getKnownLanguages();
		$result = array();
		foreach ($languages as $key=>$language){
			$result[]=$key;
		}
		sort($result);
		return $result;
	} 
	
	static function getCurrentLanguageCode(){
		$lang = JFactory::getLanguage()->getTag();
		$lang = explode("-",$lang);
		return $lang[0];
	}
	
	static function getCategoriesOptions($published, $catId = null, $showRoot = false){
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)		
		->select('a.id AS value, a.name AS text, a.level, a.published')
		->from('#__jbusinessdirectory_categories AS a')
		->join('LEFT', $db->quoteName('#__jbusinessdirectory_categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		
		if(!empty($catId)){
			$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_categories') . ' AS p ON p.id = ' . (int) $catId)
			->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
		}
		
		if (($published))	{
			$query->where('a.published = 1');
		}
		
		if(!$showRoot){
			$query->where('a.id >1');
		}
		
		$query->group('a.id, a.name, a.level, a.lft, a.rgt, a.parent_id, a.published')
		->order('a.lft ASC');
		
		$db->setQuery($query);
		$options = $db->loadObjectList();
		$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
		
		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			if ($options[$i]->published == 1)
			{
				if(!empty($categoryTranslations[$options[$i]->value]))
					$options[$i]->text = $categoryTranslations[$options[$i]->value]->name;
				if($showRoot){
					$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
				}else{
					$options[$i]->text = str_repeat('- ', $options[$i]->level-1) . $options[$i]->text;
				}
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}
		
		return $options;
	}
	
	static function getPriceFormat($amount){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$dec_point=".";
		$thousands_sep = ",";
		
		if($appSettings->amount_separator==2){
			$dec_point=",";
			$thousands_sep = ".";
		}
		
		$currencyString = $appSettings->currency_name;
		if($appSettings->currency_display==2){
			$currencyString = $appSettings->currency_symbol;
		}
		
		$amountString = number_format ($amount , 2 , $dec_point,  $thousands_sep);
		
		if($appSettings->currency_location==1){
			$result = $currencyString." ".$amountString;
		}else{
			$result =$amountString." ".$currencyString;
		}
		
		return $result;
	}
	
	static function convertPriceToMysql($number){
		$number = str_replace('.', '', $number); // remove fullstop
		$number = str_replace(' ', '', $number); // remove spaces
		$number = str_replace(',', '.', $number); // change comma to fullstop
		
		return $number;
	}
	
	public static function loadAdminLanguage(){
		$language = JFactory::getLanguage();
		$language_tag 	= $language->getTag();
		//$language_tag = "tr-TR";
		$x = $language->load(
				'com_jbusinessdirectory' , dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jbusinessdirectory'.DS.'language') ,
				$language_tag,true );
	}
	
	public static function loadSiteLanguage(){
		$language = JFactory::getLanguage();
		$language_tag 	= $language->getTag();
		
		$x = $language->load(
		'com_jbusinessdirectory' , dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jbusinessdirectory'.DS.'language') ,
		$language_tag,true );
					
		$language_tag = str_replace("-","_",$language->getTag());
		setlocale(LC_TIME , $language_tag.'.UTF-8');
	}
	
	
	public static function removeDirectory($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	
	public static function dayToString ($day, $abbr = false)
	{
		$date = new JDate();
		return addslashes($date->dayToString($day, $abbr));
	}
	
	public static function monthToString ($month, $abbr = false)
	{
		$date = new JDate();
		return addslashes($date->monthToString($month, $abbr));
	}
	
	static function getNumberOfDays($startData, $endDate){
	
		$nrDays = floor((strtotime($endDate) - strtotime($startData)) / (60 * 60 * 24));
	
		return $nrDays;
	}
	
	static public function includeValidation(){
		JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/validationEngine.jquery.css');
		$tag = JBusinessUtil::getCurrentLanguageCode();
		
		if(!file_exists(JPATH_COMPONENT_SITE.'/assets/js/validation/jquery.validationEngine-'.$tag.'.js'))
			$tag ="en";
		
		JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/validation/jquery.validationEngine-'.$tag.'.js');
		JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/validation/jquery.validationEngine.js');
	}
}
?>