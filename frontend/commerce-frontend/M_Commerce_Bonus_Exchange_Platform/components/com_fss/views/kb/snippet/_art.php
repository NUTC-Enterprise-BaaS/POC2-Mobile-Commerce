<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php $what = FSS_Input::getCmd('what', ''); ?>

	<?php if ($this->view_mode != 'popup'): ?>	
	<div class='media'>
		<div class="pull-right">
			<?php echo $this->content->EditPanel($art); ?>
		</div>
		<div class="media-body">
			<?php if ($what == "recent" && FSS_Settings::get( 'kb_show_recent_stats' ) && $art['modified'] != "0000-00-00 00:00:00"):?>
				<span class="pull-right">
					<?php echo FSS_Helper::Date($art['modified'], FSS_DATE_SHORT); ?>
				</span>
			<?php endif; ?>
			<?php if ($what == "viewed" && FSS_Settings::get( 'kb_show_viewed_stats' )):?>
				<span class="pull-right">
					<?php echo $art['views']; ?> <?php echo JText::_("VIEW_S") ?>
				</span>
			<?php endif; ?>
			<?php if ($what == "rated" && FSS_Settings::get( 'kb_show_rated_stats' )):?>
				<span class="pull-right">
					<?php echo $art['rating']; ?>
					<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/highestrated_small.png" width="16" height="16">
				</span>
			<?php endif; ?>
			<h5 class="media-heading">
				<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=kb&kbartid=' . $art['id'] ); ?>'>
					<?php echo $art['title']; ?>
				</a>
			</h5>
		</div>
	</div>
	<?php elseif ($this->view_mode == 'popup'): ?>	
	<div class='media'>
		<div class="pull-right">
			<?php echo $this->content->EditPanel($art); ?>
		</div>
		<div class="media-body">
			<h5 class="media-heading">
				<a class="show_modal_iframe" data_modal_width="<?php echo FSS_Settings::get("kb_popup_width"); ?>" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=kb&tmpl=component&kbartid=' . $art['id'] ); ?>'>
					<?php echo $art['title']; ?>
				</a>
			</h5>
		</div>		
	</div>	
	<?php endif; ?>

