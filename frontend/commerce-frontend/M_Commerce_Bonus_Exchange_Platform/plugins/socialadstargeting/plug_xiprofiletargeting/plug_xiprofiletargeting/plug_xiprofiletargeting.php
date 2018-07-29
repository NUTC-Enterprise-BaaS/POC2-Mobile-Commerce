<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Plg_Xiprofiletargeting
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="control-group span6">
	<label class="ad-fields-lable "><?php echo JText::_("PROFILE_TYPE");?> </label>
	<div class="controls">
	<?php
	$options = array();

		foreach ($vars[0] as $result)
		{
			$options[] = JHtml::_('select.option', $result->id, $result->name, 'value', 'text');
		}

		echo JHtml::_(
						'select.genericlist', $options, 'plgdata[][xiprofile,select]',
						'class="sa-fields-inputbox inputbox input-medium chzn-done" onchange="sa.create.calculateReach() " size="3"
						multiple="multiple" ', 'value', 'text', $vars[1]
						);
		$options = array();
	?>
	</div>
</div>
