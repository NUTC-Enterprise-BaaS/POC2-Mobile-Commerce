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

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Include the main simplified framework
require_once(__DIR__ . '/framework.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<link type="text/css" rel="stylesheet" href="<?php echo TEMPLATE_URI;?>/css/theme.css" />

	<?php if ($customCss) { ?>
	<link type="text/css" rel="stylesheet" href="<?php echo TEMPLATE_URI;?>/css/theme.css" />
	<?php } ?>
</head>
<body class="site">
<div class="theme-wrap">
	<header class="theme-header">
		<div class="container clearfix">
			<a href="javascript:void(0);" class="btn btn-sm nav-toggle toggle-l pull-left visible-xs">
				<i class="fa fa-bars"></i>
			</a>
			<a href="javascript:void(0);" class="btn btn-sm nav-toggle toggle-r pull-right visible-xs" data-toggle="collapse" data-target="#theme-subnav">
				<i class="fa fa-cog"></i>
			</a>

			<div class="brands pull-left">
				<?php if ($params->get('show_heading_title', true)) { ?>
				<h1><?php echo JText::_($params->get('title', 'Wanderers'));?></h1>
				<?php } ?>

				<?php if ($params->get('show_heading_description', true)) { ?>
				<small class="visible-lg">
					<?php echo JText::_($params->get('description', 'Social template for Joomla'));?>
				</small>
				<?php } ?>
			</div>
		</div>
	</header>

	<nav class="theme-nav navbar" role="navigation">
		<div class="container">
			<div id="theme-nav">
				<div class="navbar-main">
					<jdoc:include type="modules" name="position-1" style="none" />
				</div>
			</div>
		</div>
	</nav>
	
	<article class="theme-frame">
		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<!-- 404 mockup -->
					<div class="row">
						<div class="col-md-12">
							<div class="broken-page">
								<h1>404</h1>
								<h5>We're sorry, the page that you are looking for cannot be found.</h5>
								<p>The page that you are requesting does not exist on the site.</p>
								<div class="broken-box">
									<p><a href="<?php echo JURI::root();?>">Return to homepage</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
					
				<div class="col-md-3">
					<div class="sidebar-section">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="sidebar" />
						<!-- End Right Sidebar -->
					</div>
				</div>

			</div>
		</div>
	</article>

	<footer class="theme-footer">
		<div class="container">
			<div class="row">		

				<div class="col-lg-3">
					<jdoc:include type="modules" name="footer-1" style="footer" />
				</div>

				<div class="col-lg-3">
					<jdoc:include type="modules" name="footer-2" style="footer" />
				</div>

				<div class="col-lg-3">
					<jdoc:include type="modules" name="footer-3" style="footer" />
				</div>

				<div class="col-lg-3">
					<jdoc:include type="modules" name="footer-4" style="footer" />
				</div>

			</div>
		</div>
	</footer>
</div>
<script src="<?php echo TEMPLATE_URI;?>/scripts/jquery-1.10.2.min.js"></script>
<script src="<?php echo TEMPLATE_URI;?>/scripts/bootstrap.min.js"></script>
<script src="<?php echo TEMPLATE_URI;?>/scripts/theme.js"></script>
</body>
</html>