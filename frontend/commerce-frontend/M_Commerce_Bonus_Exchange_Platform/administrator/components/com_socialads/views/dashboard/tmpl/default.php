<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/media/techjoomla_strapper/bs3/css/bootstrap.min.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/font-awesome/css/font-awesome.min.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/morris/morris.css');

$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard-sb-admin.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/tjdashboard.css');

$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/morris.min.js');
$document->addScript(JUri::root(true) . '/media/com_sa/vendors/morris/raphael.min.js');

$document->addScript(JUri::root(true) . '/media/com_sa/js/sa.js');

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
?>

<div class="<?php echo SA_WRAPPER_CLASS; ?> tj-dashboard" id="sa-dashboard">
	<?php
	//if (JVERSION >= '3.0'):
		if (!empty($this->sidebar)):
	?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php
		else:
	?>
	<div id="j-main-container">
	<?php
		endif;
	//endif;
	?>
		<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=dashboard');?>" method="post" name="adminForm" id="adminForm">
			<!-- TJ Bootstrap3 -->
			<div class="tjBs3">
				<!-- TJ Dashboard -->
				<div class="tjDB">
					<!-- Start - version -->
					<div class="row">
						<?php echo $this->loadTemplate('version'); ?>
					</div>

					<!-- Start - stat boxes -->
					<div class="row">
						<?php echo $this->loadTemplate('statboxes'); ?>
					</div>

					<div class="row">
						<div class="col-lg-8 col-md-8">
							<!-- Start - Bar Chart for Monthly Income for past 12 months -->
							<div class="row">
								<div class="col-lg-12">
									<?php echo $this->loadTemplate('barchart'); ?>
								</div>
							</div>

							<!-- Start - donut chart for perodic order details -->
							<div class="row">
								<div class="col-lg-7 col-md-12 col-sm-12">
									<?php echo $this->loadTemplate('donutchart'); ?>
								</div>

								<!-- Start - stats tables -->
								<div class="col-lg-5 col-md-12 col-sm-12">
									<?php echo $this->loadTemplate('stattables'); ?>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-4">
							<?php echo $this->loadTemplate('verticalbox'); ?>
						</div>
					</div>
				</div>
				<!-- /.tjDB TJ Dashboard -->
			<div>
			<!-- /.tjBs3TJ TJ Bootstrap3 -->
		</form>
	</div>
</div>

<script type="text/javascript">
	saAdmin.dashboard.initDashboardJs();
</script>
