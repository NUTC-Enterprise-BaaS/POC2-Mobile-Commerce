<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::_( 'index.php?option=com_fss&view=report' )."'>
	<img class='fss_support_main_image' src='". JURI::root( true ) ."/components/com_fss/assets/images/support/report_24.png'>&nbsp;" . JText::_("REPORTS"). "</a>",false); ?>
<p>
	<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=report' ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
</p>
		