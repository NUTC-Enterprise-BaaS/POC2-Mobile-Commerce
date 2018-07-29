<?php


defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * The Company Controller
 *
 */
class JBusinessDirectoryControllerCompanyMessage extends JControllerForm
{
    /**
     * Dummy method to redirect back to standard controller
     *
     */
    public function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=companymessages', false));
    }

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     */
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $context = 'com_jbusinessdirectory.edit.company';
        $result = parent::cancel();

    }

}