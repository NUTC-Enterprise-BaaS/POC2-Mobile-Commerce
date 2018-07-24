<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2016 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Layout for text ads only (ie. title & decrip)
$ht = '';

if ($addata->ignore != '')
{
	$ht .= '<span class="ad_ignore_button_span" style="display:none;"><img title="'
	. JText::_('COM_SOCIALADS_CLK_IGN') . '" class="ad_ignore_button layout2_ad_ignore_button" src="'
	. JUri::root(true) . '/media/com_sa/images/cross.gif" alt="" onclick="' . $addata->ignore . '" /></span>';
}

$ht .= '<div class="ad_prev_wrap layout2_ad_prev_wrap">';

// Ad title starts here...
$ht .= '<!--div for preview ad-title-->
	<div class="layout2_ad_prev_first">';
$ht .= '<a class="preview-title preview-title-lnk layout2_ad_prev_anchor" href="' . $addata->link . '" target="_blank">';
$ht .= '' . $addata->ad_title;
$ht .= '</a>';
$ht .= '</div>';

// Ad title ends here
// Ad description starts here...
$ht .= '<!--div for preview ad-descrip-->
		<div class="preview-bodytext layout2_ad_prev_third">';
$ht .= '' . $adHtmlTyped;
$ht .= '</div>';

// Ad description ends here
$ht .= '</div>';
echo $ht;
