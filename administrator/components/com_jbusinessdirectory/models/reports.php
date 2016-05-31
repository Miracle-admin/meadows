<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');

class JBusinessDirectoryModelReports extends JModelList { 
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'cp.id',
				'name', 'cp.name',
				'description', 'cp.description'
			);
		}

		parent::__construct($config);

		$this->eventId = JRequest::getVar('eventId');
	}

	function getReports(){
		$reportTable = JTable::getInstance("Report", "JTable");
		$reports = $reportTable->getReports();
		
		return $reports;
	}
	
	function getReportData(){
		$reportId = JRequest::getVar("reportId", null);
		
		if(empty($reportId))
			return null;
		
		$reportTable = JTable::getInstance("Report", "JTable");
		$report = $reportTable->getReport($reportId);
		
		$orderBy = JRequest::getVar("orderBy","cp.id");
		
		$reportData = $reportTable->getReportData($report->selected_params, $orderBy);
		
		$generatedReport = new stdClass();
		$generatedReport->headers = explode(",",$report->selected_params);
		$generatedReport->customHeaders = explode(",",$report->custom_params);
		$generatedReport->data = $reportData;
		$generatedReport->report = $report;
		
		$attributesTable = JTable::getInstance("Attribute", "JTable");
		$generatedReport->attributes = $attributesTable->getAttributes();
		$generatedReport->customHeaders= $this->processHeaders($generatedReport->customHeaders, $generatedReport->attributes);
		
		$attributeOptionsTable = JTable::getInstance("AttributeOptions", "JTable");
		$attributeOptions = $attributeOptionsTable->getAllAttributeOptions();
	
		$generatedReport->data = $this->processData($generatedReport->data, $attributeOptions);
		
		return $generatedReport;
	}
	
	function processData($reportData, $attributeOptions){
		foreach($reportData as $data){
			$data->customAttributes = array(); 
			$customAttributes = explode("#",$data->custom_attributes);
			foreach($customAttributes as $customAttribute){
				$values = explode("||",$customAttribute);
				$obj = new stdClass();
			
				if(count($values)<3)
					continue;
				
				$obj->name = $values[0];
				$obj->code = $values[1];
				$obj->atr_code = $values[2];
				$obj->value = $values[3];
				if($obj->atr_code !="input"){
					$values = explode(",",$obj->value);
					$result = array();
					foreach($values as $value){
						foreach($attributeOptions as $attributeOption){
							if($value == $attributeOption->id){
								$result[] = $attributeOption->name;
							}
						}
					}
					if(!empty($result))
						$obj->value = implode(",",$result);
				}
				
				$data->customAttributes[$obj->name] = $obj;
			}
		}
		
		return $reportData;
	}

	
	function processHeaders($headers, $attributes){
		$result = array();
		foreach($headers as $header){
			foreach($attributes as $attribute){
				if($attribute->code == $header){
					$result[] = $attribute->name;
				}
			}
		}
		return $result;
	}
	
	function exportReportToCSV($generatedReport){
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
		
		$csv_output="";
		$params =  JBusinessDirectoryHelper::getCompanyParams();
		foreach ($generatedReport->headers as $header){
			$csv_output .= JText::_($params[$header]);
			$csv_output .= ";";
		}

		foreach ($generatedReport->customHeaders as $header){
			$csv_output .=  $header;
			$csv_output .= ";";
		}
	
		$csv_output .= "\n";
		
		foreach ($generatedReport->data as $data){
			foreach ($generatedReport->headers as $header){
				$csv_output .= $data->$header;
				$csv_output .= ";";
			}
			foreach ($generatedReport->customHeaders as $header){
				$csv_output .=  !empty($data->customAttributes[$header])?$data->customAttributes[$header]->value:"";
				$csv_output .= ";";
			}
			$csv_output .= "\n";
		}
		
		$fileName = "jbusinessdirectory_report";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header("Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
}
?>

