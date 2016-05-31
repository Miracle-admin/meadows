<?php
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_ADMINISTRATOR . '/components/com_installer/models/update.php';

class JBusinessDirectoryModelUpdates extends InstallerModelUpdate
{ 
	function __construct()
	{
		parent::__construct();
	}
	function store($post){
		$table = $this->getTable("ApplicationSettings");
		if($table->updateOrder($post['orderId'],$post['orderEmail']))
			return true; 
		else return false;
	}
	
	public function update($uids, $minimum_stability = JUpdater::STABILITY_STABLE)
	{
		$result = true;
		foreach ($uids as $uid)
		{
			$update = new JUpdate;
			$instance = JTable::getInstance('update');
			$instance->load($uid);
			$update->loadFromXML($instance->detailsurl);
			$update->set('extra_query', $instance->extra_query);
	
			// Install sets state and enqueues messages
			$res = $this->install($update);
	
			if ($res)
			{
				$instance->delete($uid);
			}
	
			$result = $res & $result;
		}
	
		// Set the final state
		$this->setState('result', $result);
	}
	
	private function install($update)
	{	
		
		try{
			$app = JFactory::getApplication();
			if (isset($update->get('downloadurl')->_data))
			{
				$url = $update->downloadurl->_data;
	
				$extra_query = $update->get('extra_query');
	
				if ($extra_query)
				{
					if (strpos($url, '?') === false)
					{
						$url .= '?';
					}
					else
					{
						$url .= '&amp;';
					}
	
					$url .= $extra_query;
				}
			}
			else
			{
				JError::raiseWarning('', JText::_('COM_INSTALLER_INVALID_EXTENSION_UPDATE'));
				return false;
			}
			
			$url .="&orderId=".JBusinessUtil::getInstance()->getApplicationSettings()->order_id."&orderEmail=".JBusinessUtil::getInstance()->getApplicationSettings()->order_email."&clientData=".urlencode(JURI::root()." - ".$_SERVER['REMOTE_ADDR']);
	
			$p_file = JInstallerHelper::downloadPackage($url);
			// Was the package downloaded?
			if (!$p_file)
			{
				JError::raiseWarning('', JText::sprintf('COM_INSTALLER_PACKAGE_DOWNLOAD_FAILED', $url));
				return false;
			}
	
			$config		= JFactory::getConfig();
			$tmp_dest	= $config->get('tmp_path');
	
			// Unpack the downloaded package file
			$package	= JInstallerHelper::unpack($tmp_dest . '/' . $p_file);
	
			// Get an installer instance
			$installer	= JInstaller::getInstance();
			$update->set('type', $package['type']);
	
			if(empty($package['dir'])){
				throw new Exception("");
			}
			// Install the package
			if (!$installer->update($package['dir']))
			{
				// There was an error updating the package
				$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
				$result = false;
			}
			else
			{
				// Package updated successfully
				$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
				$result = true;
			}
			
			if(!$result){
				$msg = JText::_('LNG_INVALID_ORDER_OR_EXPIRED_SUPPORT'); 
			}
			
	
			// Quick change
			$this->type = $package['type'];
	
			// Set some model state values
			$app->enqueueMessage($msg);
	
			// TODO: Reconfigure this code when you have more battery life left
			$this->setState('name', $installer->get('name'));
			$this->setState('result', $result);
			$app->setUserState('com_installer.message', $installer->message);
			$app->setUserState('com_installer.extension_message', $installer->get('extension_message'));
	
			// Cleanup the install files
			if (!is_file($package['packagefile']))
			{
				$config = JFactory::getConfig();
				$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
			}
	
			JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		}
		catch(Exception $e){
			$msg = JText::_('LNG_INSTALL_UPDATE_ERROR');
			$app->enqueueMessage($msg);
		}

		return $result;
	}
	public function getExpirationDate(){
		try{
			$url = "http://updates.cmsjunkie.com/security/productinfo.php?sku=j-businessdirectory";
			$url.="&orderId=".JBusinessUtil::getApplicationSettings()->order_id."&orderEmail=".JBusinessUtil::getApplicationSettings()->order_email;
			//echo $url;
			$ch = curl_init();
			$timeout = 10;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$rawdata = curl_exec($ch);
			curl_close($ch);
			return $rawdata;
		}
		catch(Exception $e){
			echo "sdsd"; exit;
		}
	}
	
	public function getCurrentVersion(){				
		$module = JComponentHelper::getComponent('com_jbusinessdirectory');
		$extension = JTable::getInstance('extension');
		$extension->load($module->id);
		$data = json_decode($extension->manifest_cache, true);
		return $data['version'];
	}
	
	
}
?>