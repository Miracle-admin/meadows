<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * This class allows general installation of files related to plugin for AlphaUserPoints
 */
class aupInstaller {

	var $errno			= 0;
	var $error			= "";
	var $_unpackdir		= "";

	/** @var string The directory where the element is to be installed */
	var $_plugindir 		= '';
	var $_uploadfile		= null;
	var $_realname			= null;
	var $_foldername		= null;

	/**
	* Constructor
	*/
	function aupInstaller() 
	{
		$this->_plugindir = JPath::clean( JPATH_COMPONENT_ADMINISTRATOR . DS .'assets'. DS .'plugins' );		
	}

	/**
	 * Installation of a single file or archive for the AlphaUserPoints files
	 * @param array uploadfile	retrieved information transferred by the upload form
	 */
	function install( $uploadfile = null ) 
	{
		if( $uploadfile === null ) return false;
		
		jimport('joomla.filesystem.folder');		
		
		$this->_uploadfile = $uploadfile['tmp_name'];
		$this->_realname = $uploadfile['name'];		
		
		$pos = strrpos ($this->_realname, ".");		
		if ( $pos )
		{
			$this->_foldername = substr($this->_realname, 0, $pos);
			
			JFolder::create($this->_plugindir . DS . $this->_foldername);
			$this->_plugindir .= DS . $this->_foldername;			
		}

		return $this->upload($uploadfile);
	}

	/**
	* Uploads and unpacks a file
	* @return boolean True on success, False on error
	*/
	function upload($uploadfile) {		
	
		if( substr( strtolower($this->_realname), -4) != ".xml" ) {
			if(!$this->extractArchive($uploadfile) ) {
				$this->error = JText::_('AUP_EXTRACT_ERROR');				
				JError::raiseWarning(0, $this->error );
				return false;
			}
		}

		if( !is_array( $this->_uploadfile ) ) {
			if(! @copy($this->_uploadfile, $this->_plugindir . DS . $this->_realname) ) {
				$this->errno = 2;
				$this->error = JText::_('AUP_FILEUPLOAD_ERROR');				
				JError::raiseWarning(0, $this->error );
				return false;
			} else {
				$file = $this->_foldername . DS . $this->_realname;
			}
		} else {
			$file = array();
			$i = 0;
			foreach ( $this->_uploadfile as $_file ) {
				
				if(! @copy($this->_unpackdir . DS . $_file, $this->_plugindir . DS . $_file) ) {
					$this->errno = 2;
					$this->error = JText::_('AUP_FILEUPLOAD_ERROR');
					JError::raiseWarning(0, $this->error );
					return false;
				}
				
				$file[$i] = $this->_foldername . DS . $_file;
				$i++;
			}
		}
		return $file;
	}

	/**
	* Extracts the package archive file
	* @return boolean True on success, False on error
	*/
	function extractArchive($plugin) {
	
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.archive.archive');

		if ( $plugin['error'] || $plugin['size'] < 1 )
		{
			JError::raiseWarning(0, JText::_('AUP_FILEUPLOAD_ERROR')) ;
			return false;
		}				
		$config = JFactory::getConfig();
		$dest 	= $config->get('config.tmp_path').DS.$plugin['name'];			
		
		$uploaded = JFile::upload($plugin['tmp_name'], $dest);
		if (!$uploaded) {
			JError::raiseWarning(0, JText::_('AUP_FILEUPLOAD_ERROR')) ;
			return false;
		} 	
		
		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');		
		//$extractdir = JPath::clean(dirname($dest).DS.$tmpdir);			
		$extractdir = $config->get('config.tmp_path').DS.$tmpdir;		
		$result = JArchive::extract($dest, $extractdir);
		
		if (!$result) {
			JError::raiseWarning(0, JText::_('AUP_FILEUPLOAD_ERROR')) ;
			return false;
		}		
		
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));
		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir.DS.$dirList[0]))
			{
				$extractdir = JPath::clean($extractdir.DS.$dirList[0]);
			}
		}		
		
		$this->_uploadfile = $dirList;
		$this->_unpackdir = $extractdir;
		
		return true;
		
	}
}
?>