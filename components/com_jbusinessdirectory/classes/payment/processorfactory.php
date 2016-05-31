<?php
/*------------------------------------------------------------------------
# JAdManager
# author SoftArt
# copyright Copyright (C) 2012 SoftArt.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.SoftArt.com
# Technical Support:  Forum - http://www.SoftArt.com/forum/j-admanger-forum/?p=1
-------------------------------------------------------------------------*/
//defined('_JEXEC') or die('Restricted access');

class ProcessorFactory{

	//get processor instance based on the class name 
	function getProcessor($processorType){
		if($processorType=="") $processorType=PROCESSOR_CASH;
		if (class_exists($processorType)){
			$processor = new $processorType();
		}
		else 
			throw new Exception("Processor $processorType does not exist");
		
		return $processor;
	}
		
}