<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
 
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","MAIN_MENU"); ?>

<?php FSS_Helper::HelpText("menu_header"); ?>

<style>
.fss_mainmenu_phone {
	display: none;
}

@media (max-width: 766px)
{
	.fss_mainmenu {
		display: none;
	}
	.fss_mainmenu_phone {
		display: block;
	}
}

</style>

<?php if ($this->showadmin && $this->info_top): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS.'supportinfo.php'); ?>
<?php endif; ?>

<?php FSS_Helper::HelpText("menu_after_admin"); ?>

<?php
$border_class = "table-bordered";
if ($this->border > 0)
	$border_class = "table-borderless";

$style = "";
if ($this->template == "grid")
	$style = 'text-align:center';

$this->centerTable($this->menus, array (
		'cols' => $this->maincolums,
		'table-class' => 'table ' . $border_class. ' table-condensed fss_mainmenu',
		'table-width' => $this->mainwidth,
		'td-attrs' => 'valign="top"',
		'td-style' => $style,
		'td-class' => '',
		'tmpl' => JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS . '_' . $this->template . '.php'
		));
?>

<?php

// phone version 
$this->centerTable($this->menus, array (
	'cols' => 1,
	'table-class' => 'table ' . $border_class. ' table-condensed fss_mainmenu_phone',
	'table-width' => $this->mainwidth,
	'td-attrs' => 'valign="top"',
	'td-style' => '',
	'td-class' => '',
	'tmpl' => JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS . '_phone.php'
	));
?>

<?php if ($this->showadmin && !$this->info_top): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'main'.DS.'snippet'.DS.'supportinfo.php'); ?>
<?php endif; ?>

<?php FSS_Helper::HelpText("menu_footer"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>