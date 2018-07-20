<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<?php if ($miniheader) { ?>
<span>
<?php } else { ?>
<span>
<?php } ?>
    <i class="fa fa-users muted"></i>
    <a href="<?php echo $permalink;?>"><?php echo JText::sprintf(FD::string()->computeNoun('COM_EASYSOCIAL_EVENTS_TOTAL_GUESTS', $event->getTotalGoing()), $event->getTotalGoing()); ?></a>

<?php if ($miniheader) { ?>
</span>
<?php } else { ?>
</span>
<?php } ?>
