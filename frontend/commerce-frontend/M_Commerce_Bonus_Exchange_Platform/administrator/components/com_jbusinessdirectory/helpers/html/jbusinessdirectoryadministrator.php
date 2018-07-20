<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Content HTML helper
 *
 * @since  3.0
 */
abstract class JHtmlJBusinessDirectoryAdministrator {

	/**
	 * Show the feature/unfeature links
	 *
	 * @param   int      $value      The state value
	 * @param   int      $i          Row number
	 * @param   boolean  $canChange  Is user allowed to change?
	 *
	 * @return  string       HTML code
	 */
	public static function featured($value = 0, $i, $canChange = true) {

		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('unfeatured',	'companies.featured',	'COM_JBUISNESSDIRECTORY_UNFEATURED',	'JGLOBAL_TOGGLE_FEATURED'),
			1	=> array('featured',	'companies.unfeatured',	'COM_JBUISNESSDIRECTORY_FEATURED',		'JGLOBAL_TOGGLE_FEATURED'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];

		if ($canChange) {
			$html = '<a href="#" onclick="document.location.href=\''.JURI::root().'administrator/index.php?option=com_jbusinessdirectory&task=company.changeFeaturedState&id=.'.$i.'\'" class="btn btn-micro hasTooltip'.($value == 1 ? ' active' : '') .'" title="'.JHtml::tooltipText($state[3]).'"><i class="icon-'.$icon.'"></i></a>';
		}
		else {
			$html = '<a class="btn btn-micro hasTooltip disabled'.($value == 1 ? ' active' : '').'" title="'.JHtml::tooltipText($state[2]).'"><i class="icon-'.$icon.'"></i></a>';
		}

		return $html;
	}

	/**
	 * Show the publish/unpublish links
	 *
	 * @param   int      $value      The state value
	 * @param   int      $i          Row number
	 * @param   boolean  $canChange  Is user allowed to change?
	 *
	 * @return  string       HTML code
	 */
	public static function published($value = 0, $i, $canChange = true) {

		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('unpublish',	'companies.published',	'COM_JBUISNESSDIRECTORY_UNPUBLISHED',	'JPUBLISHED'),
			1	=> array('publish',	'companies.unpublished',	'COM_JBUISNESSDIRECTORY_PUBLISHED',		'JUNPUBLISHED'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];

		if ($canChange) {
			$html = '<a href="#" onclick="document.location.href=\''.JURI::root().'administrator/index.php?option=com_jbusinessdirectory&task=company.changeState&id=.'.$i.'\'" class="btn btn-micro hasTooltip'.($value == 1 ? ' active' : '') .'" title="'.JHtml::tooltipText($state[3]).'"><i class="icon-'.$icon.'"></i></a>';
		}
		else {
			$html = '<a class="btn btn-micro hasTooltip disabled'.($value == 1 ? ' active' : '').'" title="'.JHtml::tooltipText($state[2]).'"><i class="icon-'.$icon.'"></i></a>';
		}

		return $html;
	}
}
