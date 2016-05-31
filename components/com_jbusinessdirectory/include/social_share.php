<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="top-right-container" class="share">
		<strong><?php echo JText::_('LNG_SHARE') ?>:</strong>
		<ul>
			<li>
				<div class="fb-like" data-href="<?php echo $url?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</li>
			<li>
				<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</li>
			<li>
				<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: <?php echo JFactory::getLanguage()->getTag()?></script>
				<script type="IN/Share" data-counter="right"></script>
			</li>
			<li>
				<!-- Place this tag where you want the +1 button to render. -->
				<div class="g-plusone" data-size="medium"></div>
				
				<!-- Place this tag after the last +1 button tag. -->
				<script type="text/javascript">
				  (function() {
				    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				    po.src = 'https://apis.google.com/js/platform.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</li>
			
		</ul>
</div>