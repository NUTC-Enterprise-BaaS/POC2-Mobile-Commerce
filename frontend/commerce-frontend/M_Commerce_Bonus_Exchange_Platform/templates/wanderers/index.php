<?php
/**
* @package		Wanderers
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Wanderers is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Include the main simplified framework
require_once(__DIR__ . '/framework.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<link type="text/css" rel="stylesheet" href="<?php echo TEMPLATE_URI;?>/css/theme.css" />

	<?php if ($customCss) { ?>
	<link type="text/css" rel="stylesheet" href="<?php echo TEMPLATE_URI;?>/css/custom.css" />
	<?php } ?>
</head>
<body class="site">
<div class="theme-wrap">
	<header class="theme-header">
		<div class="container clearfix">
			<a href="javascript:void(0);" class="btn btn-sm nav-toggle toggle-l pull-left visible-xs">
				<i class="fa fa-bars"></i>
			</a>
			<!-- <a href="javascript:void(0);" class="btn btn-sm nav-toggle toggle-r pull-right visible-xs" data-toggle="collapse" data-target="#theme-subnav">
				<i class="fa fa-cog"></i>
			</a> -->

			<div class="brands pull-left">
				<?php if ($this->params->get('show_heading_title', true)) { ?>
				<h1><?php echo JText::_($this->params->get('title', 'Wanderers'));?></h1>
				<?php } ?>

				<?php if ($this->params->get('show_heading_description', true)) { ?>
				<small class="visible-lg">
					<?php echo JText::_($this->params->get('description', 'Social template for Joomla'));?>
				</small>
				<?php } ?>
			</div>

			<?php if ($this->countModules('position-top')) { ?>
			<div class="theme-helper btn-toolbar pull-right" role="toolbar">
				<jdoc:include type="modules" name="position-top" style="none" />
			</div>
			<?php } ?>
		</div>
	</header>

	<?php if ($this->countModules('position-1')) { ?>
	<nav class="theme-nav navbar<?php echo Wanderers::isSubmenu() || Wanderers::hasSubmenu() ? ' display-child' : '';?>" role="navigation">
		<div class="container">
			<div id="theme-nav">
				<div class="navbar-main">
					<jdoc:include type="modules" name="position-1" style="none" />
				</div>
			</div>
		</div>
	</nav>
	<?php } ?>

	<article class="theme-frame">
		<div class="container">

			<?php if ($this->countModules('position-2')) { ?>
			<div class="row">
				<div class="col-lg-12">
					<jdoc:include type="modules" name="position-2" />
				</div>
			</div>
			<?php } ?>


			<div class="row">
				<div class="col-md-<?php echo $this->countModules('position-7') && $input->get('option', '', 'default') != 'com_easysocial' ? '9' : '12';?>">

					<?php if ($input->get('option', '', 'default') == 'com_easysocial') { ?>
						<jdoc:include type="component" />
					<?php } else { ?>
						<div class="content-section">
							<jdoc:include type="component" />
						</div>
					<?php } ?>
				</div>
					
				<?php if ($this->countModules('position-7') && $input->get('option', '', 'default') != 'com_easysocial') { ?>
				<div class="col-md-3">
					<div class="sidebar-section">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="sidebar" />
						<!-- End Right Sidebar -->
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</article>

	<footer class="theme-footer">
		<div class="container">
			<div class="row">		

				<div class="col-lg-3">
					<?php if ($this->countModules('footer-1')) { ?>
					<jdoc:include type="modules" name="footer-1" style="footer" />
					<?php } ?>
				</div>

				<div class="col-lg-3">
					<?php if ($this->countModules('footer-2')) { ?>
					<jdoc:include type="modules" name="footer-2" style="footer" />
					<?php } ?>
				</div>

				<div class="col-lg-3">
					<?php if ($this->countModules('footer-3')) { ?>
					<jdoc:include type="modules" name="footer-3" style="footer" />
					<?php } ?>
				</div>

				<div class="col-lg-3">
					<?php if ($this->countModules('footer-4')) { ?>
					<jdoc:include type="modules" name="footer-4" style="footer" />
					<?php } ?>
				</div>

			</div>
		</div>
	</footer>
</div>
<script src="<?php echo TEMPLATE_URI;?>/scripts/bootstrap.min.js"></script>
<script src="<?php echo TEMPLATE_URI;?>/scripts/theme.js"></script>
</body>
</html>