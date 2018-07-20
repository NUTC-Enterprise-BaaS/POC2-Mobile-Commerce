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
<?php echo FSS_Helper::PageTitle("NO_PERM"); ?>

<p class="alert alert-warning">
	<?php echo JText::_("YOU_DO_NOT_HAVE_PERMISSION_TO_PERFORM_AND_SUPPORT_ADMINISTRATION_ACTIVITIES"); ?>
</p>

<?php $user = JFactory::getUser(); if ($user->id == 0): ?>
	<?php if (!empty($this)): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_login_form.php'); ?>
	<?php else : ?>
		<?php 
		$v = new FSSView();
		include $v->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_login_form.php'); 
		?>
	<?php endif; ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
