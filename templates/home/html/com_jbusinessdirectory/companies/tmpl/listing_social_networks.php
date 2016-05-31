<?php if(($showData && isset($this->package->features) && in_array(SOCIAL_NETWORKS, $this->package->features) || !$appSettings->enable_packages)
						&& ((!empty($this->company->linkedin) || !empty($this->company->youtube) ||!empty($this->company->facebook) || !empty($this->company->twitter) || !empty($this->company->googlep) || !empty($this->company->linkedin) || !empty($this->company->skype)))){ ?> 
	<div id="social-networks-container">
		
		<ul class="social-networks">
			<li>
				<span class="social-networks-follow"><?php echo JText::_("LNG_FOLLOW_US")?>: &nbsp;</span>
			</li>
			<?php if(!empty($this->company->facebook)){ ?>
			<li >
				<a title="Follow us on Facebook" target="_blank" rel="nofollow" class="share-social facebook" href="<?php echo $this->company->facebook ?>">Facebook</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->twitter)){ ?>
			<li >
				<a title="Follow us on Twitter" target="_blank" rel="nofollow" class="share-social twitter" href="<?php echo $this->company->twitter ?>">Twitter</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->googlep)){ ?>
			<li >
				<a title="Follow us on Google" target="_blank" rel="nofollow" class="share-social google" href="<?php echo $this->company->googlep ?>">Google</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->linkedin)){ ?>
			<li >
				<a title="Linkedin" target="_blank" rel="nofollow" class="share-social linkedin" href="<?php echo $this->company->linkedin?>">Linkedin</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->skype)){ ?>
			<li >
				<a title="skype" target="_blank" rel="nofollow" class="share-social skype" href="skype:<?php echo $this->company->skype?>">Skype</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->youtube)){ ?>
			<li >
				<a title="Follow us on Youyube" target="_blank" rel="nofollow" class="share-social youtube" href="<?php echo $this->company->youtube?>">Youtube</a>			
			</li>
			<?php } ?>
		</ul>
		
	</div>
<?php } ?>