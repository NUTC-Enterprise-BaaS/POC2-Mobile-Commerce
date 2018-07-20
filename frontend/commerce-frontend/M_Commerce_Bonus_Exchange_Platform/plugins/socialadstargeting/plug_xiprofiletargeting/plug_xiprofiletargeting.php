<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Plg_Jsprofiletargeting
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// Ensure this file is being included by a parent file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

$lang = JFactory::getLanguage();
$lang->load('plg_socialadstargeting_xiprofiletargeting', JPATH_ADMINISTRATOR);

/**
 * class for XI profile targeting
 *
 * @since  1.6
 */
class Plgsocialadstargetingplug_Xiprofiletargeting extends JPlugin
{
	/**
	 * Method to get xi profile
	 *
	 * @param   string  $subject  A subject
	 * @param   array   $config   config parameter for targeting
	 *
	 * @return  array
	 *
	 * @since	1.6
	 */
	public function plgsocialadstargetingplug_xiprofiletargeting($subject, $config)
	{
		parent::__construct($subject, $config);

		if ($this->params === false)
		{
				$this->_plugin = JPluginHelper::getPlugin('socialadstargeting', 'plug_xiprofiletargeting');
				$this->params = json_decode($jPlugin->params);
		}
	}

	/**
	 * Method to get group frontend disply
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
		if (in_array('xiprofiletargeting_xipt', $tableColumns))
		{
			$list = array();
			$pluginlist = '';

			// All options
			$list[0] = $this->_getList();

			// Preselected values
			$list[1] = '';

			if (is_array($plgfields))
			{
				$list[1] = explode(',', $plgfields->xiprofiletargeting_xipt);
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
		if (in_array('xiprofiletargeting_xipt', $tableColumns))
		{
			$param = "";
			$prvparam = "";

			if (is_array($pluginlistfield))
			{
				foreach ($pluginlistfield as $fields)
				{
					if (isset($fields['xiprofile,select']))
					{
						$param .= $fields['xiprofile,select'] . ",";
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
			$row->xiprofiletargeting_xipt = $paramsvalue;

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
		if (array_key_exists('xiprofiletargeting_xipt', $paramlist))
		{
			$sub_query = array();
			$check = $this->_chkextension();

			if (!($check))
			{
				return;
			}

			$user = JFactory::getUser();
			$userid = $user->id;
			$query = "SELECT profiletype from #__xipt_users WHERE userid=$userid";
			$db = Jfactory::getDBO();
			$db->setQuery($query);
			$userlist = $db->loadObjectList();
			$query_str = array();

			if ($userlist)
			{
				foreach ($userlist as $userval)
				{
					$query_str[] = "b.xiprofiletargeting_xipt Like '%" . $userval->profiletype . "%'";
				}
			}

			$query_str[] = "b.xiprofiletargeting_xipt =''";
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
			if ($key == 'xiprofile')
			{
				$query = "SELECT userid from #__xipt_users AS xu
				LEFT JOIN  #__xipt_profiletypes AS xp  ON xu.profiletype=xp.id
				WHERE xu.profiletype IN('$value') AND xp.published=1 GROUP BY xu.userid";
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
				if ($key == "xiprofiletargeting_xipt")
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

		$list = "";
		$query = "SELECT id,name FROM #__xipt_profiletypes  ";
		$db = Jfactory::getDBO();
		$db->setQuery($query);
		$list = $db->loadObjectList();

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
		$extpath = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_xipt';

		if (JFolder::exists($extpath))
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
			$defaultPath = JPATH_SITE . DS . 'plugins' . DS . $group . DS . $plugin . DS . $plugin . DS . $layout . '.php';
			$templatePath = JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS .
			'plugins' . DS . $group . DS . $plugin . DS . $plugin . DS . $layout . '.php';
		}
		else
		{
			$defaultPath = JPATH_SITE . DS . 'plugins' . DS . $group . DS . $plugin . DS . $layout . '.php';
			$templatePath = JPATH_SITE . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS .
			'plugins' . DS . $group . DS . $plugin . DS . $layout . '.php';
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
