<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class plgQuickiconFSS_Icon extends JPlugin {

	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		
		$app = JFactory::getApplication();
		
		// only in Admin and only if the component is enabled
		if ($app->isSite()) {
			return;
		}
	}

	public function onGetIcons($context) {
		if ($context != $this->params->get('context', 'mod_quickicon')) {            
			return;
		}
		
		return array(array(
			'link' => JRoute::_('index.php?option=com_fss'),
			'image' => JURI::root() . 'administrator/components/com_fss/assets/images/fss-48x48.png',
			'access' => array(),
			'text' => JText::_('Freestyle Support Portal'),
			'id' => 'plg_quickicon_fss_icon'
			));
	}
}
