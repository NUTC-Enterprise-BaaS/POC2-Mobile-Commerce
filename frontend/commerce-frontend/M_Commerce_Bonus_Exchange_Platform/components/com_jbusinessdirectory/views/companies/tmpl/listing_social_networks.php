<?php if(($showData && isset($this->package->features) && in_array(SOCIAL_NETWORKS, $this->package->features) || !$appSettings->enable_packages)
						&& ((!empty($this->company->linkedin) || !empty($this->company->youtube) ||!empty($this->company->facebook) || !empty($this->company->twitter) 
						|| !empty($this->company->googlep) || !empty($this->company->linkedin) || !empty($this->company->skype) || !empty($this->company->instagram) || !empty($this->company->pinterest)))){ ?> 
	<div id="social-networks-container">
		
		<ul class="social-networks">
			<li>
				<span class="social-networks-follow"><?php echo JText::_("LNG_FOLLOW_US")?>: &nbsp;</span>
			</li>
			<?php if(!empty($this->company->facebook)){ ?>
			<li >
				<a title="Follow us on Facebook" target="_blank"  class="share-social facebook" href="<?php echo $this->company->facebook ?>">Facebook</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->twitter)){ ?>
			<li >
				<a title="Follow us on Twitter" target="_blank"  class="share-social twitter" href="<?php echo $this->company->twitter ?>">Twitter</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->googlep)){ ?>
			<li >
				<a title="Follow us on Google" target="_blank"  class="share-social google" href="<?php echo $this->company->googlep ?>">Google</a>			
			</li>
			<?php } ?>
			<?php if(!empty($this->company->linkedin)){ ?>
			<li >
				<a title="Follow us on LinkedIn" target="_blank" rel="nofollow" class="share-social linkedin" href="<?php echo $this->company->linkedin?>">LinkedIn</a>
			</li>
			<?php } ?>
			<?php if(!empty($this->company->skype)){ ?>
			<li >
				<a title="Skype" target="_blank"  class="share-social skype" href="skype:<?php echo $this->company->skype?>">Skype</a>
			</li>
			<?php } ?>
			<?php if(!empty($this->company->youtube)){ ?>
			<li >
				<a title="Follow us on YouTube" target="_blank" rel="nofollow" class="share-social youtube" href="<?php echo $this->company->youtube?>">YouTube</a>
			</li>
			<?php } ?>
			<?php if(!empty($this->company->instagram)){ ?>
			<li >
				<a title="Follow us on Instagram" target="_blank"  class="share-social instagram" href="<?php echo $this->company->instagram?>">Instagram</a>
			</li>
			<?php } ?>
			<?php if(!empty($this->company->pinterest)){ ?>
			<li >
				<a title="Follow us on Pinterest" target="_blank" rel="nofollow" class="share-social pinterest" href="<?php echo $this->company->pinterest?>">Pinterest</a>
			</li>
			<?php } ?>
		</ul>
		
	</div>
<?php } ?>