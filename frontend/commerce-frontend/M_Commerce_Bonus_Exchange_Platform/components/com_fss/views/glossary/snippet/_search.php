<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if ($this->show_search): ?>
	<form id="glossary_search" action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=glossary' );?>" method="post" name="fssForm">
	<div class="input-append">
			<input class="glossary_search" type="text" name="search" value="<?php echo $this->search; ?>" placeholder="<?php echo JText::_('SEARCH_GLOSSARY'); ?>">
			<input id="kb_submit" class="btn btn-primary" type="submit" value="<?php echo JText::_('SEARCH'); ?>">
			<input id="art_reset" class="btn btn-default" type="submit" value="<?php echo JText::_('RESET'); ?>" onclick="jQuery('.glossary_search').val('');">
		</div>
	</form>
<?php endif; ?>
