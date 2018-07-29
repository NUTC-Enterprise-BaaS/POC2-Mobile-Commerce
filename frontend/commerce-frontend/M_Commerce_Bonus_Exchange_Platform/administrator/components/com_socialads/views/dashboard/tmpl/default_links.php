<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// no direct access
defined('_JEXEC') or die;
?>

<div class="clearfix">&nbsp;</div>

<div class="list-group">
	<div class="list-group-item">
		<a href="http://techjoomla.com/table/documentation-for-socialads/?utm_source=clientinstallation&utm_medium=dashboard&utm_term=socialads&utm_content=textlink&utm_campaign=socialads_ci" target="_blank"><i class="icon-file"></i>
			<?php echo JText::_('COM_SA_DOCS'); ?>
		</a>
	</div>

	<div class="list-group-item">
		<a href="http://techjoomla.com/documentation-for-socialads/faqs-for-socialads.html/?utm_source=clientinstallation&utm_medium=dashboard&utm_term=socialads&utm_content=textlink&utm_campaign=socialads_ci" target="_blank">
			<?php
			if (JVERSION >= '3.0')
			{
				echo '<i class="icon-help"></i>';
			}
			else
			{
				echo '<i class="icon-question-sign"></i>';
			}

			echo JText::_('COM_SA_FAQS');
			?>
		</a>
	</div>

	<div class="list-group-item">
		<a href="http://feeds.feedburner.com/techjoomla/blogfeed" target="_blank">
			<?php
			if (JVERSION >= '3.0')
			{
				echo '<i class="icon-feed"></i>';
			}
			else
			{
				echo '<i class="icon-bell"></i>';
			}

			echo JText::_('COM_SA_RSS');
			?>
		</a>
	</div>

	<div class="list-group-item">
		<a href="https://techjoomla.com/support-tickets/?utm_source=clientinstallation&utm_medium=dashboard&utm_term=socialads&utm_content=textlink&utm_campaign=socialads_ci" target="_blank">
			<?php
			if (JVERSION >= '3.0')
			{
				echo '<i class="icon-support"></i>';
			}
			else
			{
				echo '<i class="icon-user"></i>';
			}

			echo JText::_('COM_SA_TECHJOOMLA_SUPPORT_CENTER');
			?>
		</a>
	</div>

	<div class="list-group-item">
		<a href="http://extensions.joomla.org/extensions/extension/ads-a-affiliates/banner-management/socialads-for-joomla" target="_blank">
			<?php
			if (JVERSION >= '3.0')
			{
				echo '<i class="icon-quote"></i>';
			}
			else
			{
				echo '<i class="icon-bullhorn"></i>';
			}

			echo JText::_('COM_SA_LEAVE_JED_FEEDBACK');
			?>
		</a>
	</div>
</div>
