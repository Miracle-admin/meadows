<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/view.html.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();


if ($config->loadBootstrap) {
    JHtml::_('bootstrap.loadCss', true, $direction);
}

$document->addStyleSheet("components/com_jblance/css/style.css");
if ($direction === 'rtl')
    $document->addStyleSheet("components/com_jblance/css/style-rtl.css");
?>

<div class="sp10">&nbsp;</div>
<?php

/**
 * HTML View class for the Jblance component
 */
class JblanceViewCompany extends JViewLegacy {

    protected $items;
    protected $pagination;

    function __construct() {
        parent::__construct();
        // Set the pagination request variables
        JRequest::setVar('limit', JRequest::getVar('limit', 5, '', 'int'));
        JRequest::setVar('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    }

    function display($tpl = null) {
        $this->pagination = $this->get('Pagination');
        $model = $this->getModel();

        $companies = $model->getCompanies();
        $this->assignRef('companies', $companies[0]);

        $filters = $model->getfilters();
        $this->assignRef('filters', $filters);

        $parents = $model->getParents();
        $this->assignRef('parents', $parents);

        //$total=$model->getTotal();

        $this->assignRef('pageNav', $companies[1]);
        //$this->assignRef('rows', $rows);
        $this->assignRef('params', $companies[2]);
        $this->assignRef('total', $companies[4]);

        $this->assignRef('model', $model);
//		jimport('joomla.html.pagination');
//		$this->_pagination = new JPagination($total, JRequest::getVar('limitstart'), JRequest::getVar('limit') );
        //$this->pagination = $this->_pagination; 
        parent::display($tpl);
    }

}
