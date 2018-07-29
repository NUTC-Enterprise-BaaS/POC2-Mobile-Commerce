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
<?php echo FSS_Helper::PageTitle("TESTIMONIALS",$this->product['title']);?>
<?php $hideprodlink = 1; ?>
<?php $product =$this->product; ?>
<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'test'.DS.'snippet'.DS.'_prod.php'); ?>

<?php $count = $this->comments->DisplayComments(); ?>
<?php if ($count == 0): ?>
	<?php if ($product['id'] == 0): ?>
		<?php if ($product['title'] != ""): ?>
			<?php echo JText::_('NO_GENERAL_TESTS'); ?>
		<?php else: ?>
			<?php echo JText::_('THERE_ARE_NO_TESTIMONIALS_TO_DISPLAY'); ?>
		<?php endif; ?>
	<?php else :?>
		<?php echo JText::_('NO_TESTS_FOR_PRODUCT'); ?>
	<?php endif; ?>
<?php endif; ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>