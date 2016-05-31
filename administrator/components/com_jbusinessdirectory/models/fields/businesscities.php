<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldBusinessCities extends JFormFieldList {

    protected $type = 'businesscategories';

    // getLabel() left out

    /**
     * Method to get the custom field options.
     * Use the query attribute to supply a query to generate the list.
     *
     * @return  array  The field option objects.
     *
     * @since   11.1
     */
    protected function getOptions()
    {
    	$options = array();
    	$options[] = JHtml::_('select.option', "", "All cities");
    
    	// Initialize some field attributes.
    	$key = "id";
    	$value = "name";
    	$translate = $this->element['translate'] ? (string) $this->element['translate'] : false;
    	$query = ' SELECT distinct city FROM #__jbusinessdirectory_companies where state =1 and city!="" order by city asc';
    
    	// Get the database object.
    	$db = JFactory::getDBO();
    
    	// Set the query and get the result list.
    	$db->setQuery($query);
    	$items = $db->loadObjectlist();
    
    	// Build the field options.
    	if (!empty($items))
    	{
    		foreach ($items as $item)
    		{
    			if ($translate == true)
    			{
    				$options[] = JHtml::_('select.option', $item->city, $item->city);
    			}
    			else
    			{
    				$options[] = JHtml::_('select.option', $item->city, $item->city);
    			}
    		}
    	}
    
    	// Merge any additional options in the XML definition.
    	$options = array_merge(parent::getOptions(), $options);
    
    	return $options;
    }
}