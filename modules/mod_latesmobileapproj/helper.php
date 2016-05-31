<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link docs.joomla.org/J2.5:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modLatest
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getLatest( $params )
    {

   // Database query        
   $list = array();         
   $query = "select * from #__jblance_project WHERE id_category LIKE '50,%' OR id_category LIKE ',%50%,' OR id_category LIKE ',%50,' OR id_category='50' ORDER BY ID DESC";     
   $db =& JFactory::getDBO();   // <-- ERROR IS HERE!!!
   $db->setQuery( $query );     
   $rows = $db->loadObjectList();

   // Get list items
    
    
    return $rows;
  }
}
?>