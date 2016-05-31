<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/view.html.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

$document = JFactory::getDocument();
$direction = $document->getDirection();
$config = JblanceHelper::getConfig();

if ($config->loadBootstrap) {
    JHtml::_('bootstrap.loadCss', true, $direction);
}

$document->addStyleSheet("components/com_jblance/css/style.css");
if ($direction === 'rtl')
    $document->addStyleSheet("components/com_jblance/css/style-rtl.css");
?>
<?php
//include_once(JPATH_COMPONENT.'/views/jbmenu.php'); 
jimport('joomla.application.module.helper');
$module = JModuleHelper::getModule('maximenuck');
echo JModuleHelper::renderModule($module);
?>
<div class="sp10">&nbsp;</div>
<?php

/**
 * HTML View class for the Jblance component
 */
class JblanceViewProject extends JViewLegacy {

    function display($tpl = null) {

        $app = JFactory::getApplication();
        $layout = $app->input->get('layout', 'editproject', 'string');
        $model = $this->getModel();
        $user = JFactory::getUser();

        JblanceHelper::isAuthenticated($user->id, $layout);
        switch ($layout) {
            case 'editproject':
                $return = $model->getEditProject();
                $row = $return[0];
                $projfiles = $return[1];
                $fields = $return[2];

                $this->assignRef('row', $row);
                $this->assignRef('projfiles', $projfiles);
                $this->assignRef('fields', $fields);
                break;

            case 'showmyproject':
                $return = $model->getShowMyProject();
                $rows = $return[0];
                $pageNav = $return[1];
                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                break;
            case 'listproject':

                $return = $model->getListProject();
                $rows = $return[0];
                $pageNav = $return[1];
                $params = $return[2];
                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('params', $params);
                break;
            case 'detailproject':
                $return = $model->getDetailProject();
                $row = $return[0];
                $projfiles = $return[1];
                $bids = $return[2];
                $fields = $return[3];
                $forums = $return[4];

                $this->assignRef('row', $row);
                $this->assignRef('projfiles', $projfiles);
                $this->assignRef('bids', $bids);
                $this->assignRef('fields', $fields);
                $this->assignRef('forums', $forums);

                //set page title
                $doc = JFactory::getDocument();
                $doc->setTitle($row->project_title);
                if ($row->metadesc)
                    $doc->setMetaData('description', $row->metadesc);
                if ($row->metakey)
                    $doc->setMetaData('keywords', $row->metakey);
                break;
            case 'placebid':
                $return = $model->getPlaceBid();
                $project = $return[0];
                $bid = $return[1];
                $this->assignRef('project', $project);
                $this->assignRef('bid', $bid);
                break;
            case 'projecthistory':
                $return = $model->getProjectHistory();

                //echo "<pre>"; print_r($return); die('test return');

                $rows = $return[0];
                $pageNav = $return[1];
                $params = $return[2];
                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('params', $params);
                break;
            case 'showmybid':
                $return = $model->getShowMyBid();
                $rows = $return[0];
                $this->assignRef('rows', $rows);
                break;
            case 'currentprojects':
                $return = $model->getShowMyOngoingProjects();
                $rows = $return[0];
                $this->assignRef('rows', $rows);
                break;
            case 'pickuser':
                $id = JRequest::getInt('projectId');
                $return = $model->getPickUser();
                $pdashboard = $model->getProjectdashboard();
                $rows = $return[0];
                $project = $return[1];
                $this->assignRef('rows', $rows);
                $this->assignRef('project', $project);
                $this->assignRef('title', $pdashboard['project_title']);
                $this->assignRef('description', $pdashboard['description']);
                $this->assignRef('remaining', $pdashboard['remaining']);
                $this->assignRef('bids', $pdashboard['bids']);
                $this->assignRef('location', $pdashboard['location']);
                $this->assignRef('budget', $pdashboard['budget']);
                $this->assignRef('approved', $pdashboard['approved']);
                $this->assignRef('projectStatusBar', $pdashboard['projectBar']);
                $this->assignRef('category', $pdashboard['category']);
                $this->assignRef('upgrades', $pdashboard['upgrades']);
                $this->assignRef('upgradespurchased', $pdashboard['upgradespurchased']);
                $this->assignRef('step', $pdashboard['step']);
                //set page title
                $doc = JFactory::getDocument();
                $doc->setTitle(ucfirst(ucfirst(trim($pdashboard['project_title']))) . ":Select Developer");
                break;
            case 'rateuser':
                $return = $model->getRateUser();
                $rate = $return[0];
                $project = $return[1];
                $this->assignRef('rate', $rate);
                $this->assignRef('project', $project);
                $pdashboard = $model->getProjectdashboard();
                $this->assignRef('projectStatusBar', $pdashboard['projectBar']);
                $this->assignRef('step', $pdashboard['step']);
                break;
            case 'searchproject':
                $return = $model->getSearchProject();
                $getdefaultCategoryCheckedList = $model->getdefaultCategoryCheckedList();
                $getdefaultMainCategoryCheckedList = $model->getdefaultMainCategoryCheckedList();
                $defaultCheckedList = $model->getdefaultCheckedList();

                $j = 0;
                foreach ($getdefaultCategoryCheckedList as $key => $val) {
                    $default[] = $getdefaultCategoryCheckedList[$j];
                    $j++;
                }

                $k = 0;
                foreach ($getdefaultMainCategoryCheckedList as $key => $val) {
                    $default[] = $getdefaultMainCategoryCheckedList[$k];
                    $k++;
                }

                $i = 0;
                foreach ($defaultCheckedList as $key => $val) {
                    $default[] = $defaultCheckedList[$i]->id;
                    $i++;
                }

                $rows = $return[0];
              //  echo '<pre>'; print_r($rows); die
                $pageNav = $return[1];

                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('defaultChecked', $default);
                break;
            case 'inviteuser':
                $return = $model->getInviteUser();
                $rows = $return[0];
                $project = $return[1];
                $pageNav = $return[2];

                $this->assignRef('rows', $rows);
                $this->assignRef('project', $project);
                $this->assignRef('pageNav', $pageNav);
                break;
            case 'invitetoproject':
                $return = $model->getInviteToProject();
                $projects = $return[0];
                $this->assignRef('projects', $projects);
                break;
            case 'projectprogress':
                $return = $model->getProjectProgress();
                $row = $return[0];
                $messages = $return[1];
                $pdashboard = $model->getProjectdashboard();
                $this->assignRef('row', $row);
                $this->assignRef('messages', $messages);
                $this->assignRef('rows', $rows);
                $this->assignRef('project', $project);
                $this->assignRef('title', $pdashboard['project_title']);
                $this->assignRef('description', $pdashboard['description']);
                $this->assignRef('remaining', $pdashboard['remaining']);
                $this->assignRef('bids', $pdashboard['bids']);
                $this->assignRef('location', $pdashboard['location']);
                $this->assignRef('budget', $pdashboard['budget']);
                $this->assignRef('approved', $pdashboard['approved']);
                $this->assignRef('projectStatusBar', $pdashboard['projectBar']);
                $this->assignRef('category', $pdashboard['category']);
                $this->assignRef('upgrades', $pdashboard['upgrades']);
                $this->assignRef('upgradespurchased', $pdashboard['upgradespurchased']);
                $this->assignRef('step', $pdashboard['step']);
                $user = JFactory::getUser();

                $doc = JFactory::getDocument();
                $doc->setTitle(ucfirst(ucfirst(trim($pdashboard['project_title']))) . ":Work progress");
                break;
            case 'talkpo':
                $return = $model->getProjectProgress();
                $row = $return[0];
                $messages = $return[1];
                $pdashboard = $model->getProjectdashboard();
                $this->assignRef('row', $row);
                $this->assignRef('messages', $messages);
                $this->assignRef('rows', $rows);
                $this->assignRef('project', $project);
                $this->assignRef('title', $pdashboard['project_title']);
                $this->assignRef('description', $pdashboard['description']);
                $this->assignRef('remaining', $pdashboard['remaining']);
                $this->assignRef('bids', $pdashboard['bids']);
                $this->assignRef('location', $pdashboard['location']);
                $this->assignRef('budget', $pdashboard['budget']);
                $this->assignRef('approved', $pdashboard['approved']);
                $this->assignRef('projectStatusBar', $pdashboard['projectBar']);
                $this->assignRef('category', $pdashboard['category']);
                $this->assignRef('upgrades', $pdashboard['upgrades']);
                $this->assignRef('upgradespurchased', $pdashboard['upgradespurchased']);
                $this->assignRef('step', $pdashboard['step']);
                $user = JFactory::getUser();

                $doc = JFactory::getDocument();
                $doc->setTitle(ucfirst(ucfirst(trim($pdashboard['project_title']))) . ":Work progress");
                break;
            case 'editprojectcustom':
                if ($id = JRequest::getInt("pid")) {
                    //die;
                    $return1 = $model->getEditProject($id);
                    $row = $return1[0];
                    $this->assignRef('row', $row);
                }
                $return = $model->getEditProjectwithregistration();
                $devType = $return[0];
                $platform = $return[1];
                $budget = $return[2];
                $countries = $return[3];
                $plan = $return[4];
                $guest = $return[5];

                $this->assignRef('devtype', $devType);
                $this->assignRef('platform', $platform);
                $this->assignRef('budget', $budget);
                $this->assignRef('countries', $countries);
                $this->assignRef('plan', $plan);
                $this->assignRef('guest', $guest);
                break;
            case 'projectdashboard':
                $return = $model->getProjectdashboard();

                $this->title = $return['project_title'];
                $this->description = $return['description'];
                $this->remaining = $return['remaining'];
                $this->bids = $return['bids'];
                $this->location = $return['location'];
                $this->budget = $return['budget'];
                $this->approved = $return['approved'];
                $this->projectStatusBar = $return['projectBar'];
                $this->category = $return['category'];
                $this->upgrades = $return['upgrades'];
                $this->upgradespurchased = $return['upgradespurchased'];
                $this->step = $return['step'];

                //set page title
                $doc = JFactory::getDocument();
                $doc->setTitle(ucfirst(ucfirst(trim($this->title))) . ":Dashboard");
                break;
            case 'agreement':
                $return = $model->getProjectdashboard();

                $this->title = $return['project_title'];
                $this->description = $return['description'];
                $this->remaining = $return['remaining'];
                $this->bids = $return['bids'];
                $this->location = $return['location'];
                $this->budget = $return['budget'];
                $this->approved = $return['approved'];
                $this->projectStatusBar = $return['projectBar'];
                $this->category = $return['category'];
                $this->upgrades = $return['upgrades'];
                $this->upgradespurchased = $return['upgradespurchased'];
                $this->step = $return['step'];

                //set page title
                $doc = JFactory::getDocument();
                $doc->setTitle(ucfirst(ucfirst(trim($this->title))) . ":Dashboard");
                break;
            case 'clientprojecthistory':
                $return = $model->getClientProjectHistory();

                $rows = $return[0];
                $pageNav = $return[1];
                $params = $return[2];
                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('params', $params);
                break;
            case 'clientalert':
                $return = $model->getClientNotify();

                $this->assignRef('rows', $return);
                break;
            default:
                break;
        }
//        if ($layout == 'editproject') {
//
//          
//        } elseif ($layout == 'showmyproject') {
//         
//        } elseif ($layout == 'listproject') {
//          
//        } elseif ($layout == 'detailproject') {
//           
//        }
//        elseif ($layout == 'placebid') {
//          
//        } elseif ($layout == 'projecthistory') {
//          
//        } elseif ($layout == 'showmybid') {
//           
//        } elseif ($layout == 'currentprojects') {
//           
//        } elseif ($layout == 'pickuser') {
//          
//        } elseif ($layout == 'rateuser') {
//           
//        } elseif ($layout == 'searchproject') {
//          
//        } elseif ($layout == 'inviteuser') {
//           
//        } elseif ($layout == 'invitetoproject') {
//          
//        } elseif ($layout == 'projectprogress') {
//          
//        } elseif ($layout == 'talkpo') {
//           
//        } elseif ($layout == 'editprojectcustom') {
//
//            
//        } elseif ($layout == 'projectdashboard') {
//
//           
//        } elseif ($layout == 'agreement') {
//
//          
//        }
//        // start code by kamal
//        elseif ($layout == 'clientprojecthistory') {
//           
//        } elseif ($layout == 'clientalert') {
//          
//        }
        // end code by kamal

        parent::display($tpl);
    }

}
