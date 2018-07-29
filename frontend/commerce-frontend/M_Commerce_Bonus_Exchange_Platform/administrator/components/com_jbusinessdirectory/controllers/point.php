<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * The Company Controller
 *
 */
class JBusinessDirectoryControllerPoint extends JControllerForm
{
	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=packages', false));
	}
}
