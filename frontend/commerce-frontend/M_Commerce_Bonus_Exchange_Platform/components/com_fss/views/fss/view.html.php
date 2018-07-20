<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 87659bd65ee9f9e68534a74d5440b2b5
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

class FssViewFss extends FSSView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
		$option = FSS_Input::getCmd('option');
		if ($option == "com_fst")
		{
			$link = FSSRoute::_('index.php?option=com_fss&view=test',false);
		} else if ($option == "com_fsf")
		{
			$link = FSSRoute::_('index.php?option=com_fss&view=faq',false);
		} else {
			$link = FSSRoute::_('index.php?option=com_fss&view=main',false);
		}
		$mainframe->redirect($link);
    }
}

