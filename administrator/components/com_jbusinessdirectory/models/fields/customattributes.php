<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCustomAttributes extends JFormFieldList {

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
    	$options[] = JHtml::_('select.option', "", "Select attribute");
   	
    	$query = ' SELECT id,name FROM #__jbusinessdirectory_attributes where status = 1  order by name asc';
    
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
    			
    			$options[] = JHtml::_('select.option', $item->id, $item->name);
    		}
    	}
    
    	// Merge any additional options in the XML definition.
    	$options = array_merge(parent::getOptions(), $options);
    
    	return $options;
    }
}