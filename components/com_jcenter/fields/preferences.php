<?php 
/** @copyright Copyright (c) 2007-2015 Joobi Limited. All rights reserved.
* @link joobi.co
* @license GNU GPLv3 */
if((defined('_JEXEC')) && !defined('JOOBI_SECURE')) define('JOOBI_SECURE',true);
defined('JOOBI_SECURE') or die('J....');

class JFormFieldPreferences extends JFormField{


protected $type='preferences';








protected function getInput(){

				$joobiEntryPoint='' ;
		if(defined('JPATH_ROOT')) $path=JPATH_ROOT;
		elseif(isset($mosConfig_absolute_path)) $path=$mosConfig_absolute_path;
			$status=include( $path . DIRECTORY_SEPARATOR . 'joobi' . DIRECTORY_SEPARATOR  . 'entry.php' );

		if( !$status){
			echo 'We were unable to load the joobi library. If you removed the joobi folder, please also remove this extension.';
			return '';
		}
		if( ! IS_ADMIN ) return false;

		JHtml::_('behavior.modal', 'a.modal');
		$eid=WGlobals::get( 'id', 0, 'int' );
		WText::load( 'api.node' );

		if( !empty($eid)){

						$libraryCMSMenuC=WAddon::get( 'api.' . JOOBI_FRAMEWORK . '.cmsmenu' );
			$extensionO=$libraryCMSMenuC->loadExtension( $eid );

			$lang=JFactory::getLanguage();
			if( !$lang->hasKey( $extensionO->module )){
								$libraryCMSMenuC->createLanguagefile( $extensionO->module );
			}
			$option=WGlobals::getApp();
			$css=' style="color: green;float: left;font-weight: bold;margin-top: 5px;text-decoration: underline;"';
			$link=WPage::linkPopUp( 'controller=main-widgets-preference&task=edit&id=' . $eid . '&goty=' . $option );

			$text='<a ' . $css . ' rel="{handler: \'iframe\', size: {x: 800, y: 800}}"
href="'.$link.'" title="'.WText::t('1206732392OZUQ').'" class="modal">' . WText::t('1377725235IENV') . '</a>';


		}else{
			$text='<span style="color: red;float: left;font-weight: bold;margin-top: 5px;text-decoration: underline;">' . WText::t('1377910927MTVO') . '</span>';
		}
		return $text;

	}
}