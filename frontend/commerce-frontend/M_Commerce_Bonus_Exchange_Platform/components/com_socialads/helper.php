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

jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.filesystem.folder');

/**
 * SocialAds frontend helper
 *
 * @since  3.1
 */
class SocialadsFrontendhelper
{
	/**
	 * This function return array of js files which is loaded from tjassesloader plugin.
	 *
	 * @param   array  &$jsFilesArray                  Js file's array.
	 * @param   array  &$firstThingsScriptDeclaration  Javascript to be declared first.
	 *
	 * @return  array
	 */
	public function getSocialadsJsFiles(&$jsFilesArray, &$firstThingsScriptDeclaration)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$app    = JFactory::getApplication();
		$input  = JFactory::getApplication()->input;
		$option = $input->get('option', '');
		$view   = $input->get('view', '');
		$layout = $input->get('layout', '');

		// Frontend Js files
		if (!$app->isAdmin())
		{
			if ($option == "com_socialads")
			{
				// Load the view specific js
				switch ($view)
				{
					case "adform":
						$jsFilesArray[] = 'media/com_sa/vendors/fuelux/fuelux2.3loader.min.js';

						// $jsFilesArray[] = 'media/com_sa/js/steps.js';
						$jsFilesArray[] = 'media/com_sa/js/createad.js';
						$jsFilesArray[] = 'media/com_sa/js/sa.js';

						if ($sa_params->get('geo_targeting') && file_exists(JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/GeoLiteCity.dat'))
						{
							if ($sa_params->get('jquery_ui') == 1)
							{
								$jsFilesArray[] = 'media/techjoomla_strapper/js/akeebajqui.js';
							}

							$jsFilesArray[] = 'media/com_sa/js/geo.js';
						}

					break;

					default:
					break;
				}
			}
		}
		else
		{
			if ($option == "com_socialads")
			{
				// Load the view specific js
				switch ($view)
				{
					case "form":
						$jsFilesArray[] = 'media/com_sa/vendors/fuelux/fuelux2.3loader.min.js';

						// $jsFilesArray[] = 'media/com_sa/js/steps_backend.js';
						$jsFilesArray[] = 'media/com_sa/js/createad.js';
						$jsFilesArray[] = 'media/com_sa/js/sa.js';

						if ($sa_params->get('geo_targeting') && file_exists(JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/GeoLiteCity.dat'))
						{
							if ($sa_params->get('jquery_ui') == 1)
							{
								$jsFilesArray[] = 'media/techjoomla_strapper/js/akeebajqui.js';
							}

							$jsFilesArray[] = 'media/com_sa/js/geo.js';
						}
					break;

					default:
					break;
				}
			}
		}

		$reqURI = JUri::root();

		// If host have wwww, but Config doesn't.
		if (isset($_SERVER['HTTP_HOST']))
		{
			if ((substr_count($_SERVER['HTTP_HOST'], "www.") != 0) && (substr_count($reqURI, "www.") == 0))
			{
				$reqURI = str_replace("://", "://www.", $reqURI);
			}
			elseif ((substr_count($_SERVER['HTTP_HOST'], "www.") == 0) && (substr_count($reqURI, "www.") != 0))
			{
				// Host do not have 'www' but Config does
				$reqURI = str_replace("www.", "", $reqURI);
			}
		}

		// Defind first thing script declaration.
		$loadFirstDeclarations          = "var root_url = '" . $reqURI . "';";
		$firstThingsScriptDeclaration[] = $loadFirstDeclarations;

		return $jsFilesArray;
	}
}
