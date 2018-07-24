<?php
/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$title	='';
$date	='';
?>
<script type="text/javascript">
	jQuery(function($) {
		$('#sp-nh<?php echo $uniqid ?>').spNewsHighlighter({
			'interval': <?php echo $interval; ?>,
            'fxduration': <?php echo $fxduration; ?>,
            'animation': "<?php echo $effects; ?>"
		});
	});
</script>

<div id="sp-nh<?php echo $uniqid ?>" class="sp_news_higlighter">
	<div class="sp-nh-buttons">
		<span class="sp-nh-text"><?php echo $title_text; ?></span>
		<?php if ($showbutton) { ?>
			<div id="sp-nh-prev<?php echo $uniqid;?>" class="sp-nh-prev"></div>
			<div id="sp-nh-next<?php echo $uniqid;?>" class="sp-nh-next"></div>
		<?php } ?>
	</div>	
	<div id="sp-nh-items<?php echo $uniqid ?>" class="sp-nh-item">
		<?php foreach ($list as $item): ?>
			<div class="sp-nh-item">
				<?php
					if($showtitle) 
						$title  = '<span class="sp-nh-title">' . modNewsHighlighterHelper::getText($item->title,$titlelimit,$titleas) . '</span>';

					if($date_format !='disabled') 
						$date = ' - <span class="sp-nh-date">' . JHTML::_('date', $item->date, JText::_($date_format)) . '</span>';	
					
					$text = $title.$date;
					
					$newstext = $linkable ? '<a class="sp-nh-link" href="' .$item->link. '">' . $text . '</a>' : $text;
					
					echo $newstext;
				?>	
			</div>
		<?php endforeach; ?>
	</div>
	<div style="clear:both"></div>	
</div>