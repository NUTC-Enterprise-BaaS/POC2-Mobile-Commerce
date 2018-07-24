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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"VIEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

<div class="alert alert-error">
	<h4><?php echo JText::_('YOU_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_SUPPORT_TICKET'); ?></h4>
</div>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
