<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if (FSS_Settings::get( 'kb_show_views' )) : ?>
	<?php $what = FSS_Input::getCmd('what'); ?>
	
	<div class="well well-mini margin-small">

		<?php if ($what != "") : ?>
			<a href='<?php echo FSSRoute::_( '&what=' );// FIX LINK ?>'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/allkb.png">
				<?php echo JText::_("ALL_ARTICLES"); ?>
			</a>
			&nbsp;&nbsp;
		<?php endif; ?>

		<?php if (FSS_Settings::get( 'kb_show_recent' ) && $what != "recent") : ?>
			<a href='<?php echo FSSRoute::_( '&what=recent' );// FIX LINK ?>'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/mostrecent-small.png">
				<?php echo JText::_("MOST_RECENT"); ?>
			</a>
			&nbsp;&nbsp;
		<?php endif; ?>

		<?php if (FSS_Settings::get( 'kb_show_viewed' ) && $what != "viewed") : ?>
			<a href='<?php echo FSSRoute::_( '&what=viewed' );// FIX LINK ?>'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/mostviewed-small.png">
				<?php echo JText::_("MOST_VIEWED"); ?>
			</a>
			&nbsp;&nbsp;
		<?php endif; ?>

		<?php if (FSS_Settings::get( 'kb_show_rated' ) && $what != "rated") : ?>
			<a href='<?php echo FSSRoute::_( '&what=rated' );// FIX LINK ?>'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/highestrated_small.png">
				<?php echo JText::_("HIGHEST_RATED"); ?>
			</a>
		<?php endif; ?>

	</div>
<?php endif; ?>
