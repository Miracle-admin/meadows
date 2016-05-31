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
class modbuysource
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getSource( $params )
    {

   // Database query        
   $rows = array();         
  $db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('p.pid,p.vendid,p.price,p.alias,t.name AS itemname,p.curid,cn.symbol,t.description,pm.filid,pm.premium,fl.name,fl.type,pc.catid,pcn.name AS catalias');
$query->from('#__product_node AS p');
$query->join('inner', '#__product_trans AS t ON t.pid = p.pid');
$query->join('inner', '#__product_images AS pm ON pm.pid = p.pid');
$query->join('inner', '#__joobi_files AS fl ON pm.filid = fl.filid');
$query->join('inner', '#__product_node AS pn ON pn.pid = p.pid');
$query->join('inner', '#__productcat_product AS pc ON pc.pid = p.pid');
$query->join('inner', '#__currency_node AS cn ON cn.curid = p.curid');
$query->join('inner', '#__productcat_trans AS pcn ON pcn.catid = pc.catid');
$query->where('pm.premium = 1 AND pc.catid > 12 AND pn.blocked=0');
$query->order('pn.created DESC');
$query->setLimit(4);
$db->setQuery($query);;     
   $rows = $db->loadObjectList();

   // Get list items
    
    
    return $rows;
  }
}
?>