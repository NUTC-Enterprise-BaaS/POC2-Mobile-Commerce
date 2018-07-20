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
<?php echo FSS_Helper::PageTitle("ADMIN_OVERVIEW"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php echo FSS_GUIPlugins::output("adminOverviewTop"); ?>

<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin")): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php if (FSS_Permission::CanModerate()): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_moderate'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php if (FSS_Permission::PermAnyContent()): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_content'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php echo FSS_GUIPlugins::output("adminOverviewBottom"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>