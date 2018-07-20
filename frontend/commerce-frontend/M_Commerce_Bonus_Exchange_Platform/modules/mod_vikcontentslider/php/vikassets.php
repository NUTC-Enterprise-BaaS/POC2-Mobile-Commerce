<?php
/**------------------------------------------------------------------------
 * mod_vikcontentslider - VikContentSlider
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2015 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: https://e4j.com
 * Technical Support:  tech@e4j.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldVikassets extends JFormField {
	protected $type = 'Vikassets';

	protected function getInput() {
		JHtml::_('jquery.framework', true, true);
		JHtml::_('behavior.modal', 'a.modal');
		JFactory::getDocument()->addStyleSheet(JURI::root().'modules/mod_vikcontentslider/src/back-end-style.css');
		return '<script src="'.JURI::root().'modules/mod_vikcontentslider/src/back-end-script.js"></script>';
	}
	
}