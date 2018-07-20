<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pri_portfolio
 * @version     4.0
 *
 * @copyright   Copyright (C) 2016 Devpri SRL. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
class JFormFieldSettings extends JFormField
{
	public $type = 'settings';

	public function __construct($form = null)
	{
		parent::__construct($form);
		JHtml::_('jquery.ui', array(
			'core'
		));
	}
	protected function getInput()
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() .'modules/mod_pri_background/assets/js/settings.js');
		$document->addStyleSheet(JURI::root() .'modules/mod_pri_background/assets/css/settings.css');
	}
}
