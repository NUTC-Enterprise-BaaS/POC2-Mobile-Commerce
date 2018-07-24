<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=deleteorphaned'); ?>' class='btn btn-danger'>Remove orphaned files</a>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean'); ?>' class='btn btn-success'>Cancel</a>
</p>

<pre>
<?php foreach ($this->orpahned as $orphaned): ?>
<?php echo $orphaned; ?>

<?php endforeach; ?>
</pre>