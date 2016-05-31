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
class JblanceViewUser extends JViewLegacy {

    function display($tpl = null) {

        $app = JFactory::getApplication();
        $layout = $app->input->get('layout', 'dashboard', 'string');
        $model = $this->getModel();
        $user = JFactory::getUser();

        JblanceHelper::isAuthenticated($user->id, $layout);
        switch ($layout) {
            case 'dashboard':
                $return = $model->getDashboard();

                $profile_completion_status = "mod_profile_completion_status";
                $modulePcs = JModuleHelper::getModule($profile_completion_status);
                $profile_completion_status = JModuleHelper::renderModule($modulePcs);
                $this->assignRef('profile_completion_status', $profile_completion_status);

                $current_geek_status = "mod_current_geek_status";
                $moduleCgs = JModuleHelper::getModule($current_geek_status);
                $current_geek_status = JModuleHelper::renderModule($moduleCgs);
                $this->assignRef('current_geek_status', $current_geek_status);
                $dbElements = $return[0];
                $userInfo = $return[1];
                $feeds = $return[2];
                $pendings = $return[3];

                $this->assignRef('dbElements', $dbElements);
                $this->assignRef('userInfo', $userInfo);
                $this->assignRef('feeds', $feeds);
                $this->assignRef('pendings', $pendings);
                break;
            case 'editprofile':
                $return = $model->getEditProfile();
                $userInfo = $return[0];
                $fields = $return[1];
                $extrFields = $return[2];

                $this->assignRef('userInfo', $userInfo);
                $this->assignRef('fields', $fields);
                $this->assignRef('extra_fields', $extrFields);
                break;
            case 'editpicture':
                $return = $model->getEditPicture();
                $row = $return[0];

                $this->assignRef('row', $row);
                break;
            case 'editportfolio':
                $return = $model->getEditPortfolio();
                $row = $return[0];
                $portfolios = $return[1];

                $this->assignRef('row', $row);
                $this->assignRef('portfolios', $portfolios);
                break;
            case 'userlist':
                $return = $model->getUserList();
                $rows = $return[0];
                $pageNav = $return[1];
                $params = $return[2];

                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('rows', $rows);
                $this->assignRef('params', $params);
                break;
            case 'viewportfolio':
                $return = $model->getViewPortfolio();
                $row = $return[0];
                $this->assignRef('row', $row);
                break;
            case 'viewprofile':
                $return = $model->getViewProfile();
                $userInfo = $return[0];
                $fields = $return[1];
                $fprojects = $return[2];
                $frating = $return[3];
                $bprojects = $return[4];
                $brating = $return[5];
                $portfolios = $return[6];

                $this->assignRef('userInfo', $userInfo);
                $this->assignRef('fields', $fields);
                $this->assignRef('fprojects', $fprojects);
                $this->assignRef('frating', $frating);
                $this->assignRef('bprojects', $bprojects);
                $this->assignRef('brating', $brating);
                $this->assignRef('portfolios', $portfolios);
                break;
            case 'dashboarddeveloper':
                jimport('joomla.application.module.helper');

                //All projects
                $projects_model = $this->getModel('project');
                $my_projects = $projects_model->getShowMyProject(5);
                $rows = $my_projects[0];
                $pageNav = $my_projects[1];
                $params = $my_projects[2];
                $this->assignRef('my_rows', $rows);
                $this->assignRef('my_pageNav', $pageNav);
                $this->assignRef('my_params', $params);
                // my all project lists
                $list_projects = $projects_model->getListProject(5);
                $list_rows = $list_projects[0];
                $list_pageNav = $list_projects[1];
                $list_params = $list_projects[2];
                $this->assignRef('list_rows', $list_rows);
                $this->assignRef('list_pageNav', $list_pageNav);
                $this->assignRef('list_params', $list_params);

                $profile_completion_status = "mod_profile_completion_status";
                $developer_analytics = "mod_developer_analytics";
                $current_geek_status = "mod_current_geek_status";
                $start_a_new_course = "mod_start_a_new_course";
                $create_a_new_auction = "mod_create_a_new_auction";
                $buy_source_code = "mod_buy_source_code";
                $upload_new_source_code = "mod_upload_new_source_code";
                $post_new_project = "mod_post_new_project";
                $mod_jblance_devdashboard_latest_projects = "mod_jblance_devdashboard_latest_projects";
                $upload_new_source_code = "mod_my_projects";

                $modulePcs = JModuleHelper::getModule($profile_completion_status);
                $profile_completion_status = JModuleHelper::renderModule($modulePcs);
                $this->assignRef('profile_completion_status', $profile_completion_status);


                $moduleDa = JModuleHelper::getModule($developer_analytics);
                $developer_analytics = JModuleHelper::renderModule($moduleDa);
                $this->assignRef('developer_analytics', $developer_analytics);

                $moduleCgs = JModuleHelper::getModule($current_geek_status);
                $current_geek_status = JModuleHelper::renderModule($moduleCgs);
                $this->assignRef('current_geek_status', $current_geek_status);

                $moduleSan = JModuleHelper::getModule($start_a_new_course);
                $start_a_new_course = JModuleHelper::renderModule($moduleSan);
                $this->assignRef('start_a_new_course', $start_a_new_course);

                $module = JModuleHelper::getModule($create_a_new_auction);
                $create_a_new_auction = JModuleHelper::renderModule($module);
                $this->assignRef('create_a_new_auction', $create_a_new_auction);

                $moduleBsc = JModuleHelper::getModule($buy_source_code);
                $buy_source_code = JModuleHelper::renderModule($moduleBsc);
                $this->assignRef('buy_source_code', $buy_source_code);

                $moduleUnsc = JModuleHelper::getModule($upload_new_source_code);
                $upload_new_source_code = JModuleHelper::renderModule($moduleUnsc);
                $this->assignRef('upload_new_source_code', $upload_new_source_code);

                $modulePnp = JModuleHelper::getModule($post_new_project);
                $post_new_project = JModuleHelper::renderModule($modulePnp);
                $this->assignRef('post_new_project', $post_new_project);

                $moduleLprj = JModuleHelper::getModule($mod_jblance_devdashboard_latest_projects);
                $mod_jblance_devdashboard_latest_projects = JModuleHelper::renderModule($moduleLprj);
                $this->assignRef('mod_jblance_devdashboard_latest_projects', $mod_jblance_devdashboard_latest_projects);
                break;
            case 'notify':
                $return = $model->getNotify();
                $row = $return[0];
                $this->assignRef('row', $row);
                break;
            case 'developerprojects':
                jimport('joomla.application.module.helper');

                //All projects
                $projects_model = $this->getModel('project');
                $my_projects = $projects_model->getShowMyProject(0);
                // echo '<pre>'; print_r($my_projects[0]); die;
                $rows = $my_projects[0];
                $pageNav = $my_projects[1];
                $params = $my_projects[2];
                $this->assignRef('rows', $rows);
                $this->assignRef('pageNav', $pageNav);
                $this->assignRef('params', $params);
                break;
            default:
                break;
        }
//        if ($layout == 'dashboard') {
//            
//        } elseif ($layout == 'editprofile') {
//
//           
//        } elseif ($layout == 'editpicture') {
//         
//        } elseif ($layout == 'editportfolio') {
//          
//        } elseif ($layout == 'userlist') {
//          
//        } elseif ($layout == 'viewportfolio') {
//
//           
//        } elseif ($layout == 'viewprofile') {
//
//           
//        } elseif ($layout == 'notify') {
//
//         
//        } elseif ($layout == 'dashboarddeveloper') {
//
//           }



        parent::display($tpl);
    }

}
