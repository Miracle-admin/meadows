<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	controllers/user.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 

class JblanceControllerCompany extends JControllerLegacy {
	
	
	public function getAjaxCompany(){
		//echo 'hkjl';
		//print_r($_POST);
                    $company = $this->getModel('company');
                    $results = $company->AjaxGetCompany($_POST);
    //print_r($results);
?>
 <?php
                    foreach ($results['0'] as $mycompany) {

                        //  $picture = !empty($mycompany->picture) ? JURI::root() . 'images/jblance/' . $mycompany->picture : JURI::root() . 'components/com_jblance/images/nophoto_big.png';
                        ?>

                        <div class="col-md-12 ft-comp selct-cate">

                            <div class="compimg col-md-2" >
                                <?php
                                $attrib = 'width=100 height=100 class="img-polaroid"';
                                $avatar = JblanceHelper::getThumbnail($mycompany->user_id, $attrib);
                                echo!empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar, '', '', ' ') : '&nbsp;';
                                ?>
                            </div>
                            <div class="col-md-10">
                                <div class="company_name">
                                    <div class="certified-main">
                                    <p><i class="icon-bookmark"></i> <?php
                                        //echo '<pre>'; print_r($mycompany); die;
                                        $plan = JblanceHelper::getBtPlan($mycompany->user_id);
                                        //   print_r($plan['name']); die;
                                        if (!empty($plan)) {
                                            echo $plan['name'].'</p>';
                                            if($plan['name']!=''){
                                            
                                                echo '<div class="certified "><div class="head ">';
                                                echo JText::_('COM_JBLANCE_CERTIFICATION');
                                                echo '</div><div class="head-data ">';
echo ($mycompany->phone_skype=='1')?'<span  class="fa fa-phone"data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_PHONE_SKYPE_VERIFIED').'"></span>':'<span  class="fa fa-phone not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_PHONE_SKYPE_VERIFIED_NO').'"></span>';
echo ($mycompany->trusted_user=='1')?'<span class="fa fa-usd" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_TRUSTED_USER').'"></span>':'<span  class="fa fa-usd not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_TRUSTED_USER_NOT').'"></span>';
echo ($mycompany->real_business=='1')?'<span  class="fa fa-check" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_REAL_BUSINESS').'"></span>':'<span  class="fa fa-check not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_REAL_BUSINESS_NOT').'"></span>';
echo ($mycompany->validate_client=='1')?'<span class="fa fa-user" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_VALIDATE_CLIENT').'"></span>':'<span  class="fa fa-user not" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_VALIDATE_CLIENT_NOT').'"></span>';
echo ($mycompany->english_skills=='1')?'<span class="fa fa-comment" data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_ENGLISH_COM_SKILLS').'"></span>':'<span  class="fa fa-comment not"data-toggle="tooltip" title="'.JText::_('COM_JBLANCE_ENGLISH_COM_SKILLS_NO').'"></span>';
                                                echo '</div></div>';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }

                                        //echo '<span>'.JblanceHelper::getAvarageRate($row->user_id).'</span';
                                        ?>


                                    </div>
                                    <div>
                                        <?php $rate = JblanceHelper::getAvarageRate($mycompany->user_id, true); ?>
                                        <?php if ($mycompany->rate > 0) { ?>
                                            <span class="font14" style="margin-left: 10px;"><?php echo JblanceHelper::formatCurrency($mycompany->rate, true, true, 0) . '/' . JText::_('COM_JBLANCE_HR'); ?></span>
                                        <?php } ?>
                                    </div>

                               

                                </div>
                                <div class="company_name">
                                    <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $mycompany->user_id) ?>">  
                                        <h3><?php echo $mycompany->name;
                                        ?></h3>
                                    </a>
                                </div>

                                <div class="compdesc">
                                    <span><?php
                                        $desc = JblanceHelper::getDescription($mycompany->user_id);
                                        echo substr($desc, 0, 150);
                                        ?>
                                    </span>
                                </div>
                                <br>
                                <div class="col-md-4">

                                    <b>Skills:</b>
                                    <?php
                                    $skills = JblanceHelper::getCategoryNames($mycompany->id_category);
                                    echo ($skills) ? $skills : "N/A";
                                    ?>


                                </div>
                                <div class="col-md-4">

                                    <b>Location:</b>
                                    <?php
                                    $location = JblanceHelper::getLocationNames($mycompany->id_location);
                                    echo ($location) ? $location : "N/A";
                                    ?>


                                </div>
                                <div class="col-md-4">

                                    <b>Mobile:</b>
                                    <?php
                                    
                                    echo ($mycompany->mobile) ? $mycompany->mobile : "N/A";
                                    ?>


                                </div>

                            </div>   
                        </div>

                        <?php
                    }
//  echo $this->pagination->getListFooter();
                  //echo '<pre>'; print_r($results['1']); ?>
                    <div class="pagination pagination-centered clearfix">
                        <div class="display-limit pull-right">
                            <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
                            <?php echo $results['1']->getLimitBox(false); ?>
                        </div>
                        <?php echo $results['1']->getPagesLinks(); ?>
                    </div>
<?php


                    die('');
	}

}