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

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-bar-chart-o fa-fw"></i>
		<?php echo JText::_('COM_SOCIALADS_MONTHLY_INCOME_MONTH'); ?>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div id="graph-monthly-sales">
				</div>
				<div class="center" id="graph-monthly-sales-msg">
					<?php echo JText::_("COM_SOCIALADS_NO_DATA_FOUND"); ?>
				</div>
				<hr class="hr hr-condensed"/>
				<div class="center">
					<?php echo JText::_('COM_SOCIALADS_BAR_CHART_HAXIS_TITLE'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
