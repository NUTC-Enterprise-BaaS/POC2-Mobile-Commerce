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

/*
 * Module chrome for rendering the module in a submenu
 */
function modChrome_no($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo $module->content;
	}
}

function modChrome_footer($module, &$params, &$attribs)
{
	if ($module->content) {
		if ($module->showtitle)
		{
			echo '<div class="panel-heading"><b>' . $module->title . '</b></div>';
		}
		echo $module->content;
	}
}

function modChrome_well($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
		}
		echo $module->content;
		echo "</div>";
	}
}


function modChrome_sidebar($module, &$params, &$attribs)
{
	if ($module->content)
	{	
		echo "<div class=\"panel panel-default panel-module " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
			echo "<div class=\"panel-heading " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
			if ($module->showtitle)
			{
				echo "<h3>" . $module->title . "</h3>";
			}
			echo "</div>";

			echo "<div class=\"panel-body " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
				echo $module->content;
			echo "</div>";
		echo "</div>";
	}
}