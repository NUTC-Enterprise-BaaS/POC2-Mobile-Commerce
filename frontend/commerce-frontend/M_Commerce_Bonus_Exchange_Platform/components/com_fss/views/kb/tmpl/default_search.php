<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php foreach ($this->results as $product): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>
<?php endforeach; ?>
<?php if (count($this->results) == 0): ?>
	<p class="fss_no_results"><?php echo JText::_("NO_PRODUCTS_MATCH_YOUR_SEARCH_CRITERIA"); ?></p>
<?php endif; ?>
<div class="fss_clear"></div>
<?php if ($this->main_prod_pages) echo $this->pagination->getListFooter(); ?>

