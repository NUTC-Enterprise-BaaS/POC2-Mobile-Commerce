<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="media faq_faq faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>_cont faq_<?php echo $faq['id']; ?>_cont"">
	<div class="pull-right">
		<?php echo $this->content->EditPanel($faq); ?>
	</div>
	<div class="media-body">
		<h5 class="media-heading">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&faqid=' . $faq['id']); ?>'>
				<?php echo $faq['question']; ?>
			</a>
		</h5>
	</div>
</div>