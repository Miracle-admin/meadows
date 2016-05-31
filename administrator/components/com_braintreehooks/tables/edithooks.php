<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Edithooks table class.
 *
 * @package     Braintreehooks
 * @subpackage  Tables
 */
class BraintreehooksTableEdithooks extends JTable
{

     var    $id              = null;
	 var    $webhook_name    = null;
	 var	$last_triggered  = null;
	 var	$active          = null;
	 var    $logs            = null;
	 var	$trigger_count   = null;
	 var	$ordering        = null;


	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__braintreehooks', 'id', $db);
	}

	/**
     * Overloaded check function
     */
    public function check()
	{
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

		
		return parent::store($updateNulls);
	}
}
?>