<?php
   defined('_JEXEC') or die;
   
   ?>
<div class="developer-analytics-graph">
   <div class="heading">
      <h4>Your Aanlytics</h4>
      <p>By Upgrading Your Status You Can Get More Visibility And Apply For The  Best And Most Lucrative Projects On The App Meadows Platform!</p>
   </div>
   <p>
      <?php 
         if($planName=="Platinum"){
          ?>
      <img src="<?php echo JUri::root(); ?>templates/home/images/home/meadows-bg.jpg" style="width: 724px; height: 305px;" />
   <p><a style="position: absolute; top: 294px; right: 287px;" href="<?php echo JUri::root() ?>index.php?option=com_jblance&view=membership&layout=plans&Itemid=344"><button>Upgrade your status</button></a></p>
   <?php 
      }
      
      else{
      $chart=modDeveloperAnalyticsHelper::getChart();
      ?>
   <div class="dev-analytic">

         <div class="col-md-4" id="visits_to_me">
            <p>Visits to your profile</p>
            {tip Click here to view the full report of last 30 days.}
            <div class="dev-analy-cir"><?php echo $viewsCount->tome==''?0:$viewsCount->tome; ?></div>
            {/tip}
         </div>
         <div class="col-md-4" id="visits_by_me">
            <p >Visits from your profile</p>
            {tip Click here to view the full report of last 30 days}
            <div class="dev-analy-cir"><?php echo $viewsCount->byme==''?0:$viewsCount->byme; ?></div>
            {/tip}
         </div>
         <div class="col-md-4" id="visits_to_my_apps">
            <p>Visits to your apps</p>
            {tip Click here to view the full report of last 30 days.}
            <div class="dev-analy-cir"><?php echo $viewsCount->tomyapps==''?0:$viewsCount->tomyapps; ?></div>
            {/tip}
         </div>

<div class="dev-analytic-btm-prt">

         <div class="col-md-3">
            <p>Total earnings</p>
            <span>$<?php echo $totalFunds; ?></span>
         </div>
         <div class="col-md-3">
            <p>Completed orders</p>
            <span><?php echo $completedOrders->total_orders; ?></span>
         </div>


         <div class="col-md-3">
            <p>Source code earnings</p>
            <span><?php echo number_format($vmodel->getVendorPoints(),2, '.', ','); ?>pts</span>
         </div>
         <div class="col-md-3" id="rating-geek">
            <p>Geek ratings</p>
            <span><?php  JblanceHelper::getAvarageRate($user->id); ?></span>
         </div>
</div>
   </div>
   <?php } ?>
   </p>
</div>