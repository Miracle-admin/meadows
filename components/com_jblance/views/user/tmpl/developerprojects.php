<?php
                if ($this->rows) {
                    foreach ($this->rows as $prj) {
                        $featured = $prj->is_featured;
                        $urgent = $prj->is_urgent;
                        $assisted = $prj->is_assisted;
                        $private = $prj->is_private;
                        $sealed = $prj->is_sealed;
                        $nda = $prj->is_nda;
                        ?>
                        <div class="db_pro_title">
                            <div class=""><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id=' . $prj->id . '&Itemid=308') ?>" class="project_title"> <?php echo $prj->project_title; ?></a></div>
                            <ul class="promotions">
                                <?php if ($featured) { ?>
                                    <li data-promotion="featured">Featured</li>
                                <?php } if ($private) { ?>
                                    <li data-promotion="private">Private</li>
                                <?php } if ($urgent) { ?>
                                    <li data-promotion="urgent">Urgent</li>
                                <?php } if ($sealed) { ?>
                                    <li data-promotion="sealed">Sealed</li>
                                <?php } if ($nda) { ?>
                                    <li data-promotion="nda">NDA</li>
                                <?php } if ($assisted) { ?>
                                    <li data-promotion="Assisted">Assisted</li>
                                <?php } ?>
                            </ul>
                            <div class="pro_desp_txt"><?php echo substr($prj->description, 0, 200) . '....'; ?></div>

                            <ul class="bth-list-project">
                                <li>  <a id="<?php echo "upgrade-" . $prj->id; ?>"   href="#" data-target="#myModal">Upgrade my Project</a> </li>
                                <li><a class="edit-wrap" href="<?php echo JRoute::_(JUri::root() . 'index.php?option=com_jblance&view=project&layout=editproject&id=' . $prj->id . '&Itemid=308'); ?>">Edit My Project</a></li>
                                <li><a class="edit-wrap" href="<?php echo JRoute::_(JUri::root() . 'index.php?option=com_jblance&task=project.projectdashboard&id=' . $prj->id); ?>">Project Dashboard</a></li>
                            </ul>
                            <div id="bpopup" > 
                                <!--modal appear here--> 
                            </div>
                            <script type="text/javascript">
                                jQuery('<?php echo "#upgrade-" . $prj->id; ?>').on('click', function () {
                                    jQuery("#bpopup").bPopup({
                                        content: 'ajax', //'ajax', 'iframe' or 'image'
                                        closeClass: 'btn-close',
                                        loadUrl: '<?php echo JUri::base() . "index.php?option=com_jblance&task=upgrades.showUpgrades&id=" . $prj->id; ?>'
                                    })
                                });

                            </script> 
                        </div>
                        <?php }
                    ?>

                    <?php
                } else {
                    echo '<b> No project(s) found...! </b>';
                }