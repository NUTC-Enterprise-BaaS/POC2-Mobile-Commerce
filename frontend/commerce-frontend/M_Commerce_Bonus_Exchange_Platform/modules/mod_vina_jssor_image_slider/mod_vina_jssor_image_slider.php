<?php
/*
# ------------------------------------------------------------------------
# Vina Jssor Image Slider for Joomla 3
# ------------------------------------------------------------------------
# Copyright(C) 2014 www.VinaGecko.com. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaGecko.com
# Websites: http://vinagecko.com
# Forum:    http://vinagecko.com/forum/
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__) . '/helper.php';

// load json code
$slider = json_decode($params->get('slides', ''));

// load data
$slides   = modVinaJssorImageSliderHelper::getSildes($slider);
$timthumb = JURI::base() . 'modules/mod_vina_jssor_image_slider/libs/timthumb.php?a=c&q=99&z=0';

// check if don't have any slide
if(empty($slides)) {
	echo "You don't have any slide!";
	return;
}

// display layout
require JModuleHelper::getLayoutPath('mod_vina_jssor_image_slider', $params->get('layout', 'default'));

// display copyright text. You can't remove it!
modVinaJssorImageSliderHelper::getCopyrightText($module);