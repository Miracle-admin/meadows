<?php 
/**
 * Attachment utility class
 * @author George
 *
 */

defined('_JEXEC') or die( 'Restricted access' );

class JBusinessDirectoryAttachments{
	
	function __construct()
	{
		parent::__construct();
	}
	
	static function getAttachments($attachmentType, $objectId, $active=false)
	{
		
		$activeFilter = "";
		if($active){
			$activeFilter = " and status=1 ";
		}
		
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from  #__jbusinessdirectory_company_attachments where type=$attachmentType $activeFilter and object_id=$objectId";
			$db->setQuery($query);
			$attachments = $db->loadObjectList();
			return $attachments;
		}
		
		return null;
	}
	
	static function deleteAttachmentsForObject($attachmentType,$objectId){
		
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "delete from #__jbusinessdirectory_company_attachments where type=$attachmentType and object_id=$objectId";
			$db->setQuery($query);
			$db->query();
		}
	}
	

	static function saveAttachment($attachmentType, $objectId, $name, $path, $status){
		$db =JFactory::getDBO();
		$name = $db->escape($name);
		$path = $db->escape($path);
	
		$query = "insert into #__jbusinessdirectory_company_attachments(type,object_id,name,path,status) values($attachmentType,$objectId,'$name','$path',$status)";
		
		$db->setQuery($query);
		$db->query();
	}
	
	static function saveAttachments($attachmentType, $attachmentPath, $objectId, $data, $id){
		self::deleteAttachmentsForObject($attachmentType,$objectId);

		if(!empty($data['attachment_name'])){
			foreach( $data['attachment_name'] as $i=>$name ){
				$path = $data['attachment_path'][$i];
				$status = $data['attachment_status'][$i];
				
				$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".ATTACHMENT_PATH .$attachmentPath.$id."/");
				$path_new = JBusinessUtil::makePathFile(JPATH_ROOT."/".ATTACHMENT_PATH .$attachmentPath.$objectId."/");
				
				if( !is_dir($path_new) ){
					if( !@mkdir($path_new) ){
						return false;
					}
				}
	
				if( $path_old.basename($path) != $path_new.basename($path) ){
					if(@rename($path_old.basename($path),$path_new.basename($path)) )	{
						$path = $attachmentPath.($objectId).'/'.basename($path);
					}
					else
					{
						return false;
					}
				}
				
				self::saveAttachment($attachmentType, $objectId, $name, $path, $status);
				
			}
		}
		return true;
	}
	
}

?>