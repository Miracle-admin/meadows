<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

 // no direct access
defined('_JEXEC') or die('Restricted access');

$heightforicon = '';
if ( !$this->params->get( 'showImage' ) ) $heightforicon='height="20"';
?>
<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
<?php endif; ?>
	<table class="category table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th id="icon_header_title">
					<?php echo JText::_('AUP_MEDALS'); ?>
				</th>
				<th id="medals_header_title">
					<?php echo JText::_('AUP_NAME'); ?>
				</th>
				<th id="description_header_title">
					<?php echo JText::_( 'AUP_DESCRIPTION' ); ?>
				</th>
				<th id="awarded_header_title">
					<?php echo JText::_( 'AUP_AWARDED' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php	
			for ($i=0, $n=count( $this->levelrank ); $i < $n; $i++)			
			{
				$row 	=& $this->levelrank[$i];
				
				$icone = '';
				
				$startmedals = '';
				$endmedals  = '';
				if ( $row->nummedals ) {	
					$link = 'index.php?option=com_alphauserpoints&amp;view=medals&amp;task=detailsmedal&amp;cid='.$row->id;		
					$startmedals = '<a href="'.$link.'" class="thumbnail">';
					$endmedals  = '</a>';
				}
				
				if ( $this->params->get( 'showImage' ) ) {				
					if ($row->image ) {
						$pathimage = JPATH_COMPONENT . DS . 'assets/images/awards/large/'.$row->image;
						$image = new JImage( $pathimage );
						$icone = $image->createThumbs( array( $this->params->get( 'heightImage', 32 ) .'x'.$this->params->get( 'heightImage', 32 ) ), JImage::CROP_RESIZE, JPATH_COMPONENT .DS. 'assets'.DS.'images'.DS.'awards'.DS.'large'.DS.'thumbs' );
						$icone = myImage::getLivePathImage($icone);
						$icone = '<img src="'.$icone.'" alt="" />';
					} else $icone ='';				
				} else {				
					if ($row->icon ) {
						$pathicon = JURI::root() . 'components/com_alphauserpoints/assets/images/awards/icons/';
						$icone = '<img src="'.$pathicon . $row->icon.'" width="16" height="16" ';
					} else $icone ='';					
				}					
				
			?>
			<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<td headers="categorylist_header_title" class="list-title">
					<?php echo $startmedals.$icone.$endmedals; ?>
				</td>
				<td headers="categorylist_header_title" class="list-title">
					<?php echo JText::_( $row->rank ); ?>
				</td>
				<td headers="categorylist_header_title" class="list-title">
					<?php echo JText::_( $row->description ); ?>
				</td>
				<td headers="categorylist_header_title" class="list-title">
					<?php					
					if ( $row->nummedals ) {					
										
						echo '<a href="'.JRoute::_( $link ).'"><span class="badge badge-info">'.$row->nummedals.'</span></a>';
					} else 	echo '<span class="badge">0</span>';
					?>
				</td>
			</tr>
		
		<?php
			}
		?>
		</tbody>
	</table>
	<div class="pagination">
	<?php 
	echo $this->pagination->getPagesCounter() . "<br />" ;
	echo $this->pagination->getPagesLinks(); 
	?>
	</div>
<?php
	/** 
	*
	*  Provide copyright on frontend
	*  If you remove or hide this line below,
	*  please make a donation if you find AlphaUserPoints useful
	*  and want to support its continued development.
	*  Your donations help by hardware, hosting services and other expenses that come up as we develop,
	*  protect and promote AlphaUserPoints and other free components.
	*  You can donate on http://www.alphaplug.com
	*
	*/	
	getCopyrightNotice ();
?>
