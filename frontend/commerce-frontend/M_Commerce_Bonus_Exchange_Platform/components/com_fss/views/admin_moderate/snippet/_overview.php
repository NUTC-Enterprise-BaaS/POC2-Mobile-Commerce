<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::x( '&layout=moderate&ident=' )."'><img src='". JURI::root( true ) ."/components/com_fss/assets/images/support/moderate_24.png'>&nbsp;" . JText::_("MODERATE"). "</a>",false); ?>

	<p>
		<?php echo JText::sprintf("MOD_STATUS",$this->comments->GetModerateTotal(),FSSRoute::_( 'index.php?option=com_fss&view=admin_moderate' )); ?>
	</p>
	<?php $this->comments->DisplayModStatus(); ?>