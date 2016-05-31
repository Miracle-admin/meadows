<?php 
/**
 * Translation utility class
 * @author George
 *
 */

defined('_JEXEC') or die( 'Restricted access' );

class JBusinessDirectoryTranslations{
	
	private function __construct(){
	}
	
	public static function getInstance(){
		static $instance;
		if ($instance === null) {
			$instance = new JBusinessDirectoryTranslations();
		}
		return $instance;
	}
	
	public static function getCategoriesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->categoriesTranslations)){
			$translations = self::getCategoriesTranslationsObjects();
			$instance->categoriesTranslations = array();
			
			foreach($translations as $translation){
				$instance->categoriesTranslations[$translation->object_id]= $translation;
			}
			
		}
		return $instance->categoriesTranslations;
	}
	
	static function getCategoriesTranslationsObjects(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
							from  #__jbusinessdirectory_categories c
							inner join  #__jbusinessdirectory_language_translations t on c.id=t.object_id where type=".CATEGORY_TRANSLATION." and language_tag='$language'";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	public static function getBusinessTypesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->businessTypesTranslations)){
			$translations = self::getBusinessTypesTranslationsObject();
			$instance->businessTypesTranslations = array();
			foreach($translations as $translation){
				$instance->businessTypesTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->businessTypesTranslations;
	}
	
	static function getBusinessTypesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
							from  #__jbusinessdirectory_company_types bt
							inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".TYPE_TRANSLATION." and language_tag='$language'";
		
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	public static function getAttributesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->attributeTranslations)){
			$translations = self::getAttributesTranslationsObject();
			$instance->attributeTranslations = array();
			foreach($translations as $translation){
				$instance->attributeTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->attributeTranslations;
	}
	
	static function getAttributesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
					from  #__jbusinessdirectory_attributes a
					inner join  #__jbusinessdirectory_language_translations t on a.id=t.object_id where t.type=".ATTRIBUTE_TRANSLATION." and language_tag='$language'";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	static function getObjectTranslation($translationType,$objectId,$language)
	{
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from  #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId and language_tag='$language'";
			$db->setQuery($query);
			$translation = $db->loadObject();
			return $translation;
		}
		else return null;
	}
	
	static function getAllTranslations($translationType,$objectId)
	{
		$translationArray=array();
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId order by language_tag";
			$db->setQuery($query);
			$translations = $db->loadObjectList();
			
			if(count($translations)>0){
				foreach($translations as $translation){
					$translationArray[$translation->language_tag."_name"]=$translation->name;
					$translationArray[$translation->language_tag]=$translation->content;
					$translationArray[$translation->language_tag."_short"]=$translation->content_short;
				}
			}
		}
		return $translationArray;
	}
	
	static function deleteTranslationsForObject($translationType,$objectId){
		
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "delete from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId";
			$db->setQuery($query);
			$db->query();
		}
	}

	static function saveTranslation($translationType, $objectId, $language, $name, $shortContent, $content){
		$db =JFactory::getDBO();
		$shortContent = $db->escape($shortContent);
		$content = $db->escape($content);
		$query = "insert into #__jbusinessdirectory_language_translations(type,object_id,language_tag,name, content_short,content) values($translationType,$objectId,'$language','$name','$shortContent','$content')";
		$db->setQuery($query);
		
		return $db->query();
		
	}
	
	static function saveTranslations($translationType,$objectId, $identifier){
		self::deleteTranslationsForObject($translationType,$objectId);
		$languages = JBusinessUtil::getLanguages();
		
		foreach($languages as $lng ){
			
			$description = 	JRequest::getVar( $identifier.$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			$shortDescription = JRequest::getVar( "short_".$identifier.$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			$name = JRequest::getVar( "name_".$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
				
			if(!empty($description) || !empty($shortDescription) || !empty($name)){
				self::saveTranslation($translationType, $objectId, $lng, $name, $shortDescription, $description);
			}
		}
	}
	
	static function getAllTranslationObjects($translationType,$objectId)
	{
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId order by language_tag";
			$db->setQuery($query);
			$translations = $db->loadObjectList();
		}
		return $translations;
	}
	
	static function updateEntityTranslation(&$object, $translationType){
		
		$language = JFactory::getLanguage()->getTag();
		$translation = self::getObjectTranslation($translationType, $object->id, $language);
		if(!empty($translation)){
			if(!empty($translation->content_short))
				$object->short_description = $translation->content_short;
			if(!empty($translation->content))
				$object->description = $translation->content;
			if(!empty($translation->name))
				$object->name = $translation->name;
		}
		
		//slogan - for businesses
		$translation = self::getObjectTranslation(BUSSINESS_SLOGAN_TRANSLATION, $object->id, $language);
		if(!empty($translation)){
			$object->slogan = $translation->content;
		}
		
		if(!empty($object->categoryIds)){
			$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
			$translations = array();
			$categoriesIds = explode(",",$object->categoryIds);
			$categoryNames =  explode("#", $object->categoryNames);
			foreach($categoriesIds as $i=>$categoriesId){
				if(!empty($categoryTranslations[$categoriesId])){
					$translations[] = $categoryTranslations[$categoriesId]->name;
				}else{
					$translations[] = $categoryNames[$i];
				}
			}
		
			$object->categoryNames = implode("#",$translations);
		}
		
		if(!empty($object->typeId)){
			$typeTranslations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
			if(!empty($typeTranslations[$object->typeId])){
				$object->typeName = $typeTranslations[$object->typeId]->name;
			}
		}
	}
	
	static function updateBusinessListingsTranslation(&$companies){
		$ids = array();
		
		if(empty($companies)){
			return;
		}
		
		foreach($companies as $company)
			$ids[] = $company->id;
		$objectIds = implode(',', $ids);
		
		
		$translationType = BUSSINESS_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
		
		$db =JFactory::getDBO();
		$query = "select object_id, content_short from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		//dump($translations);
		$short_description = array();
		foreach($translations as $translation){
			$short_description[$translation->object_id]= $translation->content_short;
		}
		
		foreach($companies as &$company){
			if(!empty($short_description[$company->id])){
				$company->short_description = $short_description[$company->id];
			}
			
			$typeTranslations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
			if(isset($company->typeId) && !empty($typeTranslations[$company->typeId])){
				$company->typeName = $typeTranslations[$company->typeId]->name;
			}
			
			if(!empty($company->categoryIds)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				$translations = array();
				$categoriesIds = explode(",",$company->categoryIds);
				$categoryNames =  explode("#", $company->categoryNames);
				foreach($categoriesIds as $i=>$categoriesId){
					if(!empty($categoryTranslations[$categoriesId])){
						$translations[] = $categoryTranslations[$categoriesId]->name;
					}else{
						$translations[] = $categoryNames[$i];
					}
				}
				
				$company->categoryNames = implode("#",$translations);
			}
			
			//update main category translation
			if(!empty($company->mainCategoryId)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				if(!empty($categoryTranslations[$company->mainCategoryId])){
					$company->mainCategory = $categoryTranslations[$company->mainCategoryId]->name;
				}
			}			
		}
	}
	
	static function updateCategoriesTranslation(&$categories){
		if(empty($categories)){
			return;
		}

		$translations = JBusinessDirectoryTranslations::getCategoriesTranslations();
		foreach($categories as &$categoryS){
			$category = $categoryS;
			if(is_array($category)){
				$category[0]->subcategories = $category["subCategories"];
				$category = $category[0];
				
			}
			
			if(!empty($category->id) && isset($translations[$category->id])&& !empty($translations[$category->id]->name)){
				$category->name = $translations[$category->id]->name;
			}
			if(!empty($category->subcategories)){
				
				foreach($category->subcategories as &$subcat){
					if(is_array($subcat)){
						$subcat= $subcat[0];
						
					}
					//dump($translations[$subcat->id]);
					if(!empty($translations[$subcat->id]) && !empty($translations[$subcat->id]->name)){
						$subcat->name = $translations[$subcat->id]->name;
					}
				}
			}
		}
	}
	
	static function updateAttributesTranslation(&$attributes){
		if(empty($attributes)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getAttributesTranslations();
		foreach($attributes as &$attribute){
			if(!empty($translations[$attribute->id])){
				$attribute->name = $translations[$attribute->id]->name;
			}
		}
	}
	
	static function updateTypesTranslation(&$types){
		if(empty($types)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
		foreach($types as &$type){
			if(!empty($translations[$type->id])){
				$type->name = $translations[$type->id]->name;
			}
		}
	}
	
	static function updateBusinessListingsSloganTranslation(&$companies){
		$ids = array();
		
		if(empty($companies)){
			return;
		}
		
		foreach($companies as $company)
			$ids[] = $company->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = BUSSINESS_SLOGAN_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		
		$short_description = array();
		foreach($translations as $translation){
			$short_description[$translation->object_id]= $translation->content;
		}
	
		foreach($companies as &$company){
			if(!empty($short_description[$company->id])){
				$company->slogan = $short_description[$company->id];
			}
		}
	
		//dump($companies);
	}
	
	
	static function updateOffersTranslation(&$offers){
	
		$ids = array();
		
		if(empty($offers)){
			return;
		}
		
		foreach($offers as $offer)
			$ids[] = $offer->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = OFFER_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content_short from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
	
		$short_description = array();
		foreach($translations as $translation){
			$short_description[$translation->object_id]= $translation->content_short;
		}
	
		foreach($offers as &$offer){
			if(!empty($short_description[$offer->id])){
				$offer->short_description = $short_description[$offer->id];
			}
			
			if(!empty($offer->categoryIds)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				$translations = array();
				$categoriesIds = explode(",",$offer->categoryIds);
				$categoryNames =  explode("#", $offer->categoryNames);
				foreach($categoriesIds as $i=>$categoriesId){
					if(!empty($categoryTranslations[$categoriesId])){
						$translations[] = $categoryTranslations[$categoriesId]->name;
					}else{
						$translations[] = $categoryNames[$i];
					}
				}
			
				$offer->categoryNames = implode("#",$translations);
			}
		}
	}
	
	static function updateEventsTranslation(&$events){
	
		$ids = array();
		
		if(empty($events)){
			return;
		}
		
		foreach($events as $event)
			$ids[] = $event->id;
		$objectIds = implode(',', $ids);
	
		$translationType = EVENT_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		$short_description = array();
		foreach($translations as $translation){
			$description[$translation->object_id]= $translation->content;
		}
	
		foreach($events as &$event){
			if(!empty($description[$event->id])){
				$event->description = $description[$event->id];
			}
		}
	
	}
	
	static function updatePackagesTranslation(&$packages){
	
		$ids = array();
		
		if(empty($packages)){
			return;
		}
		
		foreach($packages as $package)
			$ids[] = $package->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = PACKAGE_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		$short_description = array();
		foreach($translations as $translation){
			$description[$translation->object_id]= $translation->content;
		}
	
		foreach($packages as &$package){
			if(!empty($description[$package->id])){
				$package->description = $description[$package->id];
			}
		}
	
	}
	
	
}

?>