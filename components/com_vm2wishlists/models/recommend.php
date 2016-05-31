<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class Vm2wishlistsModelRecommend extends JModelForm
{
    protected $list_data;
    //protected $productname;
    
    public function getListdata()
    {
        $db         = JFactory::getDBO();
        $app        = JFactory::getApplication();
        $listid = $app->input->getInt('listid');  
        $q ="SELECT vwl.id , vwl.list_name , vwl.list_description , vwl.icon_class ,vwl.privacy ,
        vwl.ordering ,  vwl.state ,  vwl.created_by 
        FROM `#__vm2wishlists_lists` vwl 
        WHERE vwl.id ='".$listid."' ";
        $db->setQuery($q);
        $this->list_data = $db->loadObject();
        return $this->list_data;
    }
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_vm2wishlists.recommend', 'recommend', array('control' => 'jform', 'load_data' => true));
        if (empty($form))
        {
            return false;
        }
        //$id = $this->getState('contact.id');
        //$params = $this->getState('params');
        //$contact = $this->_item[$id];
        //$params->merge($contact->params);
        return $form;
    }
}