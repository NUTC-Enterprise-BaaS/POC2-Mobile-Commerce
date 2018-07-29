<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if ($this->use_letter_bar): ?>
	<p class="text-center center fss_glossary_bar">
	<?php foreach ($this->letters as $letter => $ok): ?>
		<?php if (!$ok): ?>
			<span class="letter-disabled">
				&nbsp;<?php echo $letter; ?>&nbsp;
			</span>
		<?php else: ?>
			<span class="letter-present">
				<?php if ($this->use_letter_bar == 2): ?>
					&nbsp;<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=glossary&letter=' . strtolower($letter)); ?>'><?php echo $letter; ?></a>&nbsp;
				<?php else : ?>
					&nbsp;<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=glossary#letter_' . strtolower($letter)); ?>'>&nbsp;<?php echo $letter; ?></a>&nbsp;
				<?php endif; ?>
			</span>
		<?php endif; ?>
	<?php endforeach; ?>
</p>
<?php endif; ?>
