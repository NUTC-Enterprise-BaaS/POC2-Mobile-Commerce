<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("FREQUENTLY_ASKED_QUESTIONS","TAGS"); ?>

	<div class="fss_spacer"></div>

	<div class="media">
		<a class="pull-left" href="#" onclick="return false;">
			<img class="media-object" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tags-64x64.png' width='64' height='64'>
		</a>
		
		<div class="media-body">
			<div style="min-height: 64px">
						
				<h4 class="media-heading">
					<?php if (FSS_Settings::Get('faq_cat_prefix')): ?>
						<?php echo JText::_("FAQS"); ?> 
					<?php endif; ?>
					<?php echo JText::_('TAGS'); ?>
				</h4>
			
			</div>
			
			<div>
			
				<?php if (count($this->tags)) foreach ($this->tags as $tag) : ?>
					<div class='media'>
						<div class="media-body">
							<h4 class="media-heading">
								<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($tag->tag)); ?>'>
									<?php echo $tag->tag; ?>
								</a>
							</h4>
						</div>
					</div>	
				<?php endforeach; ?>
				<?php if (count($this->tags) == 0): ?>
					<p><?php echo JText::_("NO_TAGS_FOUND");?></p>
				<?php endif; ?>

			</div>
		</div>
	</div>	

	
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>
