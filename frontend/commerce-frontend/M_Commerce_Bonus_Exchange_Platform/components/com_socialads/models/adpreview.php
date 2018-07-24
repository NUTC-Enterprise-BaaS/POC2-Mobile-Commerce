<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Socialads records.
 *
 * @since  1.6
 */
class SocialadsModelAdpreview extends JModelList
{
	/**
	 * Function to get ad preview
	 *
	 * @param   Int  $ad_id  Id of ad for which preview is generated
	 *
	 * @since  1.6
	 *
	 * @return addata
	 */
	public function getAdPreview($ad_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('a.ad_id'));
		$query->select($db->quoteName('a.ad_body'));
		$query->select($db->quoteName('a.ad_title'));
		$query->select($db->quoteName('a.ad_image'));
		$query->from($db->quoteName('#__ad_data` AS a'));
		$query->where($db->quoteName('a.ad_id') . '=' . $ad_id);
		$db->setQuery($query);
		$addata = $db->loadObject();

		return $addata;
	}
}
