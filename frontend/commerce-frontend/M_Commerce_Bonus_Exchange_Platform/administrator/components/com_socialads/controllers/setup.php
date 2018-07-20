<?php
/**
 * @version    SVN: <svn_id>
 * @package    JGive
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

/**
 * Dashboard form controller class.
 *
 * @package  JGive
 * @since    1.8
 */
class SocialAdsControllerSetup extends JControllerForm
{
	/**
	 * Manual Setup related chages: For now - 1. for overring the bs-2 view
	 *
	 * @return  JModel
	 *
	 * @since   1.6
	 */
	public function setup()
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$jinput = JFactory::getApplication()->input;
		$takeBackUp = $jinput->get("takeBackUp", 1);

		$client = 0;
		$defTemplate = SaCommonHelper::getSiteDefaultTemplate($client);

		$templatePath = JPATH_SITE . '/templates/' . $defTemplate . '/html/';

		$statusMsg = array();
		$statusMsg["component"] = array();

		// 1. Override component view
		$siteBs2views = JPATH_ROOT . "/components/com_socialads/views_bs2/site";

		// Check for COM_SOCIALADS folder in template override location
		$compOverrideFolder  = $templatePath . "com_socialads";

		if (JFolder::exists($compOverrideFolder))
		{
			if ($takeBackUp)
			{
				// Rename
				$backupPath = $compOverrideFolder . '_' . date("Ymd_H_i_s");
				$status = JFolder::move($compOverrideFolder, $backupPath);
				$statusMsg["component"][] = JText::_('COM_SOCIALADS_TAKEN_BACKUP_OF_OVERRIDE_FOLDER') . $backupPath;
			}
			else
			{
				$delStatus = JFolder::delete($compOverrideFolder);
			}
		}

		// Copy
		$status = JFolder::copy($siteBs2views, $compOverrideFolder);
		$statusMsg["component"][] = JText::_('COM_SOCIALADS_OVERRIDE_DONE') . $compOverrideFolder;
		$this->overrideLayout($templatePath);
		$this->displaySetup($statusMsg);
		exit;
	}

	/**
	 * Override the Layouts
	 *
	 * @param   String  $templatePath  TemplatePath eg JPATH_SITE . '/templates/protostar/html/'
	 *
	 * @return  JModel
	 *
	 * @since   1.6
	 */
	public function overrideLayout($templatePath)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$layoutPath = JPATH_SITE . "/layouts/bs2/ad";
		$layoutExist = $templatePath . 'layouts/ad';

		if (JFolder::exists($layoutExist))
		{
			JFolder::delete($layoutExist);
		}

		JFolder::copy($layoutPath, $templatePath . '/layouts/ad');
	}

	/**
	 * Override the Views
	 *
	 * @param   array  $statusMsg  The array of config values.
	 *
	 * @return  JModel
	 *
	 * @since   1.6
	 */
	public function displaySetup($statusMsg)
	{
		echo "<br/> =================================================================================";
		echo "<br/> " . JText::_("COM_SOCIALADS_BS2_OVERRIDE_PROCESS_START");
		echo "<br/> =================================================================================";

		foreach ($statusMsg as $key => $extStatus)
		{
			echo "<br/> <br/><br/>*****************  " . JText::_("COM_SOCIALADS_BS2_OVERRIDING_FOR") . " <strong>" . $key . "</strong> ****************<br/>";

			foreach ($extStatus as $k => $status)
			{
				$index = $k ++;
				echo $index . ") " . $status . "<br/> ";
			}
		}

		echo "<br/> " . JText::_("COM_SOCIALADS_BS2_OVERRIDING_DONE");
	}
}
