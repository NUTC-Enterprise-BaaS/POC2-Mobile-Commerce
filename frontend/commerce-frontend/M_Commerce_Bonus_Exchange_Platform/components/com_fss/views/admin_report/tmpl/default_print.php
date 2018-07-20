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
<?php if (FSS_Input::getCmd('type') != "bare"): ?>
	<?php echo FSS_Helper::PageTitle("Reports", $this->report->title); ?>
<?php endif; ?>

<?php if (FSS_Input::getCmd('type') == ""): ?>
	<div class="well well-small form-horizontal form-condensed">
		<?php echo $this->report->listFilterValues(); ?>
	</div>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_report'.DS.'snippet'.DS.'_report_table.php'); ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>


<script>
jQuery(document).ready( function () {
	window.print();
});
</script>