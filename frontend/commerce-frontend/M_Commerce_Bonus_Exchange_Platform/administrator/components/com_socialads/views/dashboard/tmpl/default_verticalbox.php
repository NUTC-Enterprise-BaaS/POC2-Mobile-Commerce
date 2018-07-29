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
<?php if (!$this->downloadid): ?>
	<div class="clearfix pull-right">
		<div class="alert alert-warning">
			<?php
				echo JText::sprintf('COM_SA_LIVE_UPDATE_DOWNLOAD_ID_MSG', '<a href="https://techjoomla.com/my-account/add-on-download-ids" target="_blank">' . JText::_('COM_SA_LIVE_UPDATE_DOWNLOAD_ID_MSG2') . '</a>');
			?>
		</div>
	</div>

	<div class="clearfix"></div>
<?php endif; ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-bullhorn"></i>
		<strong><?php echo JText::_('COM_SOCIALADS'); ?></strong>
	</div>

	<div class="panel-body">
		<blockquote class="blockquote-reverse"><?php echo JText::_('COM_SOCIALADS_ABOUT'); ?></blockquote>

		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs" id="myTab">
					<li class="active">
						<a data-toggle="tab" href="#links"><?php echo JText::_('COM_SA_LINKS'); ?></a>
					</li>
					<li>
						<a data-toggle="tab" href="#tj-dashboard-news"><?php echo JText::_('COM_SOCIALADS_TABS_NEWS'); ?></a>
					</li>
					<li>
						<a data-toggle="tab" href="#about"><?php echo JText::_('COM_SOCIALADS_TABS_ABOUT'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="links">
						<?php echo $this->loadTemplate('links'); ?>
					</div>
					<div class="tab-pane" id="tj-dashboard-news">
					</div>
					<div class="tab-pane" id="about">
						<?php echo $this->loadTemplate('about'); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="center thumbnail">
					<?php
					$logo_path = '<img src="' . JUri::root(true) . '/media/com_sa/images/techjoomla.png" alt="Techjoomla"/>';
					?>
					<a href='http://techjoomla.com/?utm_source=clientinstallation&utm_medium=dashboard&utm_term=socialads&utm_content=textlink&utm_campaign=socialads_ci' target='_blank'>
						<?php echo $logo_path; ?>
					</a>
				</div>
			</div>
			<div class="center">
				<p><?php echo JText::_('COM_SA_COPYRIGHT'); ?></p>
			</div>
		</div>

	</div>
</div>
