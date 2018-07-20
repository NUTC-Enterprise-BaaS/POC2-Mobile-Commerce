<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');?>

<?php if ($addata->ignore != '')
{
	?>
	<span class="ad_ignore_button_span" >
		<img title="<?php echo JText::_('COM_SOCIALADS_CLK_IGN'); ?>"
			class="ad_ignore_button layout6_ad_ignore_button"
			src="<?php echo JUri::root(true) . '/media/com_sa/images/cross.gif'; ?>"
			alt=""
			onclick="<?php echo $addata->ignore; ?>">
	</span>
<?php
} ?>

<div class="ad_prev_wrap layout6_ad_prev_wrap">
	<div class="preview-bodytext layout6_ad_prev_third">
		<?php echo $adHtmlTyped; ?>
	</div>
</div>
