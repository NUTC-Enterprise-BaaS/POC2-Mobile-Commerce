<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStylePopup(); ?>
<?php echo FSS_Helper::PageTitlePopup(JText::_("PRODUCTS_SELECTED_FOR_GROUP")); ?>

<ul>
	<?php foreach ($this->products as $product): ?>
		<li><?php echo $product->title; ?></li>
	<?php endforeach; ?>

	<?php if (count($this->products) == 0): ?>
		<li>None Selected</li>
	<?php endif; ?>
</ul>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>