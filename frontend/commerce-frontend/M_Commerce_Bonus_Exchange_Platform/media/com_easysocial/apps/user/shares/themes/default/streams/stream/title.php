<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<?php if ($actor->id == $target->id && $actor->id == $this->my->id) { ?>
	<?php echo JText::sprintf('APP_USER_SHARES_STREAM_TITLE_REPOST_OWN_STREAM', $link);?>
<?php } else { ?>
	<?php echo JText::sprintf('APP_USER_SHARES_STREAM_TITLE_REPOST_STREAM', $actor->getName(), $this->html('html.user', $target->id), $link); ?>
<?php } ?>
