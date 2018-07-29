<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.filesystem.file');

/**
 * Class for custom geolife element
 *
 * @since  1.0.0
 */
class JFormFieldGeolife extends JFormField
{
	protected $type = 'Geolife';

	/**
	 * Function to genarate html of custom element
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function getInput()
	{
		$html    = '';
		$params  = JComponentHelper::getParams('com_socialads');
		$maxmind = JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/GeoLiteCity.dat';

		if (JFile::exists($maxmind))
		{
			$html .= '<div class="span9 sa-elements-custom"><div class="alert alert-success ">
			<span class="icon-save"></span>' . JText::_('COM_SOCIALADS_GEOLITECITY_INSTALLED') . '</div>';

			// Condition to check if mbstring is enabled

			if (!function_exists('mb_convert_encoding'))
			{
				$html .= '<div class="alert alert-error">' . JText::_('COM_SOCIALADS_MB_EXT') . '</div>';
			}

			$html .= '</div>';
		}
		else
		{
			$html .= '<div class="span9 sa-elements-custom"><div class="alert alert-error">' . JText::_('COM_SOCIALADS_GEOLITECITY_INSTALLATION_1') . '
			</div>
			<div class="alert alert-info geo_target_instructions">
			<a target="_blank" href="http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz">
			' . JText::_('COM_SOCIALADS_GEOLITECITY_DOWNLOAD_LINK') . '</a><br>
			' . JText::_('COM_SOCIALADS_GEOLITECITY_INSTALLATION_2') . '<br>' . JText::_('COM_SOCIALADS_GEOLITECITY_INSTALLATION_3') . '</div>';

			// Condition to check if mbstring is enabled

			if (!function_exists('mb_convert_encoding'))
			{
				$html .= '<div class="alert alert-error">' . JText::_('COM_SOCIALADS_MB_EXT') . '</div>';
			}

			$html .= '</div>';
		}

		$return = $html;

		return $return;
	}
}
