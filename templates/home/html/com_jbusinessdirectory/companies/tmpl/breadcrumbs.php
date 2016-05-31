<div id="search-path">
	<ul>
		<li><?php echo JText::_("LNG_YOU_ARE_HERE")?>:</li>
		<?php if(isset($this->category)){ ?>
		<li>
			<a class="search-filter-elem" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=search&view=search') ?>"><?php echo JText::_('LNG_ALL_CATEGORIES') ?></a>
		</li>
		<?php } ?>
	<?php 
		if(isset($this->company->path)){
		foreach($this->company->path as $path) {?>
		<li>
			<a  class="search-filter-elem" href="<?php echo JBusinessUtil::getCategoryLink($path->id, $path->alias) ?>"><?php echo $path->name?></a>
		</li>
	<?php }
		} 
	?>
		<li>
			<?php echo $this->company->name ?>
		</li>
	</ul>
</div>