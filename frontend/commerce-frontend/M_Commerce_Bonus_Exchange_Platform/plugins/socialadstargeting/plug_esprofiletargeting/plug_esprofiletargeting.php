<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Plg_Esprofiletargeting
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// Ensure this file is being included by a parent file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

$lang = JFactory::getLanguage();
$lang->load('plg_socialadstargeting_esprofiletargeting', JPATH_ADMINISTRATOR);

/**
 * class for ES profile targeting
 *
 * @since  1.6
 */
class Plgsocialadstargetingplug_Esprofiletargeting extends JPlugin
{
	/**
	 * Method to get es profile
	 *
	 * @param   string  $subject  A subject
	 * @param   array   $config   config parameter for targeting
	 *
	 * @return  array
	 *
	 * @since	1.6
	 */
	public function plgsocialadstargetingplug_esprofiletargeting($subject, $config)
	{
		parent::__construct($subject, $config);

		if ($this->params === false)
		{
				$this->_plugin = JPluginHelper::getPlugin('socialadstargeting', 'plug_esprofiletargeting');
				$this->params = json_decode($jPlugin->params);
		}
	}

	/**
	 * Method to get es frontend disply
	 *
	 * @param   array  $plgfields     plugin fields
	 * @param   array  $tableColumns  ad_fields table column
	 *
	 * @return  array
	 *
	 * @since	1.6
	 */
	public function onFrontendTargetingDisplay($plgfields, $tableColumns)
	{
		// Check required column exist in table
		if (in_array('esprofiletargeting_espt', $tableColumns))
		{
			$list = array();
			$pluginlist = '';

			// All options
			$list[0] = $this->_getList();

			// Preselected values
			$list[1] = '';

			if (is_array($plgfields))
			{
				$list[1] = explode(',', $plgfields->esprofiletargeting_espt);
			}

			$ht = array();

			if ($list[0])
			{
				$ht[] = $this->_getLayout($this->_name, $list);
			}
			else
			{
				$ht[] = null;
			}

			return $ht;
		}
	}

	/**
	 * Method to save targeting
	 *
	 * @param   array  $pluginlistfield  plugin fields
	 * @param   array  $tableColumns     ad_fields table column
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function onFrontendTargetingSave($pluginlistfield, $tableColumns)
	{
		// Check required column exist in table
		if (in_array('esprofiletargeting_espt', $tableColumns))
		{
			$param = "";
			$prvparam = "";

			if (is_array($pluginlistfield))
			{
				foreach ($pluginlistfield as $fields)
				{
					if (isset($fields['esprofile,select']))
					{
						$param .= $fields['esprofile,select'] . ",";
					}
				}
			}

			$param = substr_replace($param, "", -1);

			if ($param != "")
			{
				$paramsvalue = $param;
			}
			else
			{
				$paramsvalue = "";
			}

			$row = new stdClass;
			$row->esprofiletargeting_espt = $paramsvalue;

			return $row;
		}
	}

	/**
	 * OnAfterGetAds function of append one more AND in the SocialADs main query
	 *
	 * @param   array  $paramlist  params list
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function OnAfterGetAds($paramlist)
	{
		// Onlyif the entry for particular targeting plugin if present in #__ad_fields
		if (in_array('esprofiletargeting_espt', $paramlist))
		{
			$sub_query = array();

			$check = $this->_chkextension();

			if (!($check))
			{
				return;
			}

			$user = JFactory::getUser();
			$userid = $user->id;
			$query = "SELECT profile_id FROM #__social_profiles_maps WHERE user_id =$userid";
			$db = Jfactory::getDBO();
			$db->setQuery($query);
			$userlist = $db->loadObjectList();
			$query_str = array();

			if ($userlist)
			{
				foreach ($userlist as $userval)
				{
					$query_str[] = "b.esprofiletargeting_espt Like '%" . $userval->profile_id . "%'";
				}
			}

			$query_str[] = "b.esprofiletargeting_espt =''";
			$query_str = (count($query_str) ? ' ' . implode(" OR ", $query_str) : '');
			$sub_query[] = "(" . $query_str . ")";

			return $sub_query;
		}
	}

	/**
	 * OnAfterGetEstimate function of get a user profile
	 *
	 * @param   array  $plg_targetfiels  targeting fields plugin
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function OnAfterGetEstimate($plg_targetfiels)
	{
		if (!$plg_targetfiels)
		{
			return array();
		}

		$userlist = array();

		foreach ($plg_targetfiels as $key => $value)
		{
			if ($key == 'esprofile')
			{
				$query = "SELECT user_id from #__social_profiles_maps AS spm
				LEFT JOIN #__social_profiles AS sp ON sp.id=spm.profile_id WHERE spm.profile_id IN('$value')
				AND sp.state=1  GROUP BY spm.user_id";
				$db = Jfactory::getDBO();
				$db->setQuery($query);
				$userlist = $db->loadColumn();
			}
		}

		return $userlist;
	}

	/**
	 * Function to get selected fileds
	 *
	 * @param   array  $pluginlistfield  list of plugin fields
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function _getselected($pluginlistfield)
	{
		$pluginlist = "";

		if (isset($pluginlistfield))
		{
			foreach ($pluginlistfield as $key => $varfileds)
			{
				if ($key == "esprofiletargeting_espt")
				{
					$pluginlist = explode(',', $varfileds);
				}
			}
		}

		return $pluginlist;
	}

	/**
	 * _getList function give profile types
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function _getList()
	{
		$check = $this->_chkextension();

		if (!($check))
		{
			return;
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$list  = '';

		// Prepare query.
		$query->select('id, title');
		$query->from('#__social_profiles');
		$query->where('state = 1');
		$query->order('title ASC');

		// Inject the query and load the result.
		$db->setQuery($query);
		$list = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		return $list;
	}

	/**
	 * _chkextension function checks if the extension folder is present
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function _chkextension()
	{
		jimport('joomla.filesystem.file');
		$extpath = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easysocial';

		if (JFolder::exists($extpath) )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Function to get layout
	 *
	 * @param   string   $layout  layout for a plugin
	 * @param   boolean  $vars    variable to get a layout
	 * @param   string   $plugin  plugin name
	 * @param   string   $group   group of a plugin
	 *
	 * @return  html
	 *
	 * @since  1.6
	 */
	public function _getLayout($layout, $vars = false, $plugin = '', $group = 'socialadstargeting')
	{
		$plugin = $this->_name;
		ob_start();
		$layout = $this->_getLayoutPath($plugin, $group, $layout);
		include $layout;
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Function to get layout path
	 *
	 * @param   string  $plugin  plugin name
	 * @param   string  $group   group of a plugin
	 * @param   string  $layout  layout for a plugin
	 *
	 * @return  html
	 *
	 * @since  1.6
	 */
	public function _getLayoutPath($plugin, $group, $layout = 'default')
	{
		$app = JFactory::getApplication();

		if (JVERSION >= '1.6.0')
		{
			$defaultPath = JPATH_SITE . DS . 'plugins' . DS . $group . DS . $plugin . DS . $plugin .
			DS . $layout . '.php';
			$templatePath = JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'plugins' .
			DS . $group . DS . $plugin . DS . $plugin . DS . $layout . '.php';
		}
		else
		{
			$defaultPath = JPATH_SITE . DS . 'plugins' . DS . $group . DS . $plugin . DS . $layout . '.php';
			$templatePath = JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'plugins' .
			DS . $group . DS . $plugin . DS . $layout . '.php';
		}

		jimport('joomla.filesystem.file');

		if (JFile::exists($templatePath))
		{
			return $templatePath;
		}
		else
		{
			return $defaultPath;
		}
	}
}
