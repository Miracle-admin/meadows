<?php
// No direct access
defined('_JEXEC') or die;
$users = $helper::getUserList();
//echo '<pre>'; print_r($helper); die;
$jinput = JFactory::getApplication()->input;
JLoader::import('joomla.application.component.model');
JLoader::import('admproject', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jblance' . DS . 'models');
$admprojects_model = JModelLegacy::getInstance('admproject', 'JblanceModel');
$fields = JblanceHelper::get('helper.fields');
?>
<div style="clear:both"></div>
<div class="row featured_com_wrap">
    <div class="container-fluid">
        <div class="container">

            <h3>Featured Mobile App Development Companies</h3>
            <p class="p-caption">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
            <?php
            $count = count($users);
            if ($count > 0) {
                foreach ($users as $uk => $uv) {
                    $uid = $uv->id;
                    $jinput->set('cid', array($uid));
                    $UserDetail = $admprojects_model->getEditUser();
                    $desc = $helper::getDescription($uid);
                    $desc = !empty($desc) ? $desc : "N/A";
                    $link = $helper::getLink($uid);
                    $link = !empty($link) ? $link : "#";
                    $title = $UserDetail[0]->biz_name;
                    $picture = !empty($UserDetail[0]->picture) ? JURI::root() . 'images/jblance/' . $UserDetail[0]->picture : JURI::root() . 'components/com_jblance/images/nophoto_big.png';
                    ?>

                    <div class="mobcontainer col-md-3 col-sm-3">
                        <div class="compimg">

                            <img src="<?php echo $picture; ?>" alt="<?php echo $title ?>"/>

                        </div>

                        <div class="compname">
                            <?php echo JblanceHelper::getCategoryNames($UserDetail[0]->id_category) ?>
                        </div>
                        <div class="compdesc">
                            <?php echo JblanceHelper::getLocationNames($UserDetail[0]->id_location) ?>
                        </div>
                        <div class="compname">
                            <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $UserDetail[0]->user_id) ?>">  
                                <h3><?php echo $title . ' (' . $UserDetail[0]->name . ')';
                     ?></h3>
                            </a>
                        </div>

                        <div class="compdesc">
                            <span><?php echo substr($desc, 0, 150); ?></span>
                        </div>

                        <div class="compdesc">
                            <span>Rate: <?php echo $UserDetail[0]->rate; ?>$/hr</span>
                        </div>

                        <div class="compname">
                            <a target="_blank" class="visit_link"  href="<?php echo $link; ?>">Website</a>
                        </div>
                    </div>

                    <?php
                }//die;
                ?>

                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. montes<br>

                    nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>

                <div class="col-md-12 button_wrapper">
                    <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=userlist&Itemid=198'); ?>">See More Companies</a>
                </div>
<?php } else { ?> 
                <div class="no_featured_companies_set">
                    There are no any featured companies or app developers.
                </div>

<?php } ?> 
        </div>
    </div>
</div>


