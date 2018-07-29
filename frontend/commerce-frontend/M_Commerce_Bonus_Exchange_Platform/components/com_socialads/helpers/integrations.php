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

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Helper class
 *
 * @since  1.6
 */
class SaIntegrationsHelper
{
	/**
	 * Functin to get CB option
	 *
	 * @param   array  $fields  CB fields
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public static function getCBOptions($fields)
	{
		$db = JFactory::getDBO();
		$cbchk = saCommonHelper::checkForSocialIntegration();

		if (!empty($cbchk))
		{
			for ($i = 0; $i < count($fields); $i++)
			{
				$query = "SELECT fieldid as id, fieldtitle as options FROM #__comprofiler_field_values WHERE fieldid=" . $fields[$i]->mapping_fieldid;
				$db->setQuery($query);
				$mapping_options[] = $db->loadobjectlist();
			}
		}

		if (!empty($mapping_options))
		{
			return $mapping_options;
		}
	}

	/**
	 * Functin to get ES option
	 *
	 * @param   array  $fields  ES fields
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public static function getESOptions($fields)
	{
		$db = JFactory::getDBO();

		// $socialadshelper = new socialadshelper();
		$eschk = saCommonHelper::checkForSocialIntegration();

		if (!empty($eschk))
		{
			for ($i = 0; $i < count($fields); $i++)
			{
				if ($fields[$i]->mapping_fieldtype != 'textbox')
				{
					$field_option = array();
					require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
					$field = Foundry::table('Field');
					$field->load($fields[$i]->mapping_fieldid);
					$filed_array = new stdClass;
					$filed_array->id = $fields[$i]->mapping_fieldid;
					$options_value = Foundry::fields()->getOptions($field);

					/*
					if(empty($options_value))
					{
						$model     = Foundry::model( 'Fields' );
						$options_value = $model->getOptions($fields[$i]->mapping_fieldid);
						$options_value=$options_value['items'];
					}
					*/
					if (isset($options_value))
					{
						$options = implode("\n", $options_value);
						$filed_array->options = $options;
						$field_option[] = $filed_array;
						$mapping_options[] = $field_option;
					}
				}
			}
		}

		// Print_r($mapping_options); die('dssdfg');
		if (!empty($mapping_options))
		{
			return $mapping_options;
		}
	}

	/**
	 * Functin to get JS option
	 *
	 * @param   array  $fields  JS fields
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public static function getJSOptions($fields)
	{
		$db = JFactory::getDBO();

		// $saCommonHelper = new SaCommonHelper;
		$jschk = saCommonHelper::checkForSocialIntegration();

		if (!empty($jschk))
		{
			for ($i = 0; $i < count($fields); $i++)
			{
				$query = "SELECT id as id, options as options
				 FROM #__community_fields
				 WHERE id=" . $fields[$i]->mapping_fieldid;
				$db->setQuery($query);
				$mapping_options[] = $db->loadObjectList();
			}
		}

		if (!empty($mapping_options))
		{
			return $mapping_options;
		}
	}

	/**
	 * Functin to get all the fields of JS/CB
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public static function getFields()
	{
		$db   = JFactory::getDBO();
		$user = JFactory::getUser();
		$query = "SELECT * FROM #__ad_fields_mapping";
		$db->setQuery($query);
		$fields = $db->loadObjectList();

		$params = JComponentHelper::getParams('com_socialads');
		$type = $params->get('social_integration');

		if ($type == 'Community Builder')
		{
			// Calling CB functions for options
			$options = self::getCBOptions($fields);
		}
		elseif ($type == 'JomSocial')
		{
			// Calling JS functions for options
			$options = self::getJSOptions($fields);
		}
		elseif ($type == 'EasySocial')
		{
			// Calling ES functions for options
			$options = self::getESOptions($fields);
		}

		// Dont go inside if options are empty
		if (!empty($options))
		{
			foreach ($options as $optn)
			{
				foreach ($optn as $k => $v)
				{
					$i = 0;
					$id1 = $optn[$k]->id;
					$id2 = $optn[$k++]->id;

					if ($id1 == $id2)
					{
						foreach ($optn as $o)
						{
								$arr[] = $o->options;
						}

						$finalopt = implode("\n", $arr);
						$arr = array();
						$opt = new stdClass;
						$opt->mapping_fieldid = $optn[0]->id;
						$opt->mapping_options = $finalopt;
						$db->updateObject('#__ad_fields_mapping', $opt, 'mapping_fieldid');
					}
					else
					{
						$opt->mapping_fieldid = $optn[0]->id;
						$opt->mapping_options = $optn[0]->options;
						$db->updateObject('#__ad_fields_mapping', $opt, 'mapping_fieldid');
					}
				}
			}
		}

		// End of options are empty condition
		$query = "SELECT * FROM #__ad_fields_mapping";
		$db->setQuery($query);
		$allfields = $db->loadObjectList();

		return $allfields;
	}

	/**
	 * Load CB language
	 *
	 * @return  boolean
	 *
	 * @since  3.1
	 */
	public static function loadCbLang()
	{
		jimport('joomla.filesystem.file');

		if (JFile::exists(JPATH_SITE . "/components/com_comprofiler/plugin/language/default_language/language.php"))
		{
			global $_CB_framework, $_CB_database, $ueConfig, $mainframe;

			if (defined('JPATH_ADMINISTRATOR'))
			{
				if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php'))
				{
					return false;
				}

				include_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
			}
			else
			{
				if (!JFile::exists($mainframe->getCfg('absolute_path') . '/administrator/components/com_comprofiler/plugin.foundation.php'))
				{
					return false;
				}

				include_once $mainframe->getCfg('absolute_path') . '/administrator/components/com_comprofiler/plugin.foundation.php';
			}

			require JPATH_SITE . "/components/com_comprofiler/plugin/language/default_language/language.php";

			return true;
		}

		return false;
	}

	/**
	 * Load JomSocial language
	 *
	 * @return  boolean
	 *
	 * @since  3.1
	 */
	public static function loadJomsocialLang()
	{
		jimport('joomla.filesystem.folder');

		if (JFolder::exists(JPATH_ROOT . '/components/com_community'))
		{
			// Load language file for plugin frontend
			$lang = JFactory::getLanguage();
			$lang->load('com_community', JPATH_SITE);

			return true;
		}

		return false;
	}

	/**
	 * Function to display Jlike button
	 *
	 * @param   string   $ad_url  Ad path
	 * @param   integer  $id      ad id
	 * @param   string   $title   ad title
	 *
	 * @return  array
	 *
	 * @since  1.0
	 */
	public static function DisplayjlikeButton($ad_url,$id,$title)
	{
		$jlikeparams = array();
		$jlikeparams['url'] = $ad_url;
		$jlikeparams['id'] = $id;
		$jlikeparams['title'] = $title;
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('content');
		$grt_response = $dispatcher->trigger('onAfterSaAdDispay', array('com_socialads.viewad', $jlikeparams));

		if (!empty($grt_response['0']))
		{
			return $grt_response['0'];
		}
		else
		{
			return '';
		}
	}
}
