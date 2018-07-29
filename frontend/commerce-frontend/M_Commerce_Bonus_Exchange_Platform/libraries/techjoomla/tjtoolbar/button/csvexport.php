<?php
/**
 * @version    SVN: <svn_id>
 * @package    Techjoomla.Libraries
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * CsvExportButton
 *
 * @package     Techjoomla.Libraries
 * @subpackage  TjCsv
 * @since       1.0
 */
class JToolbarButtonCsvExport extends JToolbarButton
{
	/**
	 * Fetch the HTML for the button
	 *
	 * @param   string  $buttontext  Text to be used to show
	 * @param   Array   $messages    Array of Succes, exportError, inprogress messages
	 *
	 * @return  string  HTML string for the button
	 *
	 * @since   3.0
	 */
	public function fetchButton($buttontext = '', $messages = null)
	{
		$input = Jfactory::getApplication()->input;
		$csv_url = 'index.php?option=' . $input->get('option') . '&view=' . $input->get('view') . '&format=csv';

		$document = JFactory::getDocument();
		$document->addScript(JUri::root() . 'libraries/techjoomla/assets/js/tjexport.js');
		$document->addScriptDeclaration("var csv_export_url='{$csv_url}';");
		$document->addScriptDeclaration("var csv_export_success='{$messages['success']}';");
		$document->addScriptDeclaration("var csv_export_error='{$messages['error']}';");
		$document->addScriptDeclaration("var csv_export_inprogress='{$messages['inprogress']}';");

		// Store all data to the options array for use with JLayout
		$options = array();
		$options['text'] = JText::_($buttontext);
		$options['btnClass'] = 'btn btn-small';
		$options['doTask'] = "tjexport.exportCsv(0)";
		$options['class'] = 'icon-download export';

		// Instantiate a new JLayoutFile instance and render the layout
		$layout = new JLayoutFile('joomla.toolbar.standard');

		return $layout->render($options);
	}

	/**
	 * Get the button CSS Id
	 *
	 * @param   string   $type      Unused string.
	 * @param   string   $name      Name to be used as apart of the id
	 * @param   string   $text      Button text
	 * @param   string   $task      The task associated with the button
	 * @param   boolean  $list      True to allow use of lists
	 * @param   boolean  $hideMenu  True to hide the menu on click
	 *
	 * @return  string  Button CSS Id
	 *
	 * @since   3.0
	 */
	public function fetchId($type = 'Standard', $name = '', $text = '', $task = '', $list = true, $hideMenu = false)
	{
	}
}
