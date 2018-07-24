<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<h1>Ticket Attachment Tools</h1>

<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=removethumb'); ?>' class='btn btn-default'>Remove all thumbnails</a>
</p>

<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=verifydisk'); ?>' class='btn btn-default'>Validate disk locations</a>
</p>

<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=orphaned'); ?>' class='btn btn-default'>Remove orphaned files</a>
</p>

<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=missing'); ?>' class='btn btn-default'>Remove attachments with file missing on disk</a>
</p>
<p>
	<a href='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=cleaninline'); ?>' class='btn btn-default'>Convert inline images to attachments</a>
</p>


<iframe frameborder="0" border="0" style='width: 100%;height: 400px;' src='<?php echo JRoute::_('index.php?option=com_fss&view=attachclean&task=stats&tmpl=component'); ?>'>
</iframe>

