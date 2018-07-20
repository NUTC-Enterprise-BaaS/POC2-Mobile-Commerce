<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated d8ea4b2cf4dfc681998468cce8441222
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'admin_helper.php');

class FssViewAdmin extends FSSView
{
	var $parser = null;
	var $layoutpreview = 0;

	function display($tpl = null)
	{		
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		$layout = FSS_Input::getCmd('layout');
		if ($layout == "support")
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_support',false));
		if ($layout == "content")
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_content',false));
		if ($layout == "moderate")
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_moderate',false));
		if ($layout == "shortcut")
			return JFactory::getApplication()->redirect(FSSRoute::_('index.php?option=com_fss&view=admin_shortcut',false));
		
		$can_view = false;
		$view = array();
		if (FSS_Permission::PermAnyContent())
		{
			$view[] = FSSRoute::_('index.php?option=com_fss&view=admin_content',false);
			$can_view = true;
		}
		if (FSS_Permission::AdminGroups())
		{
			$view[] = FSSRoute::_('index.php?option=com_fss&view=admin_groups',false);
			$can_view = true;
		}
		if (FSS_Permission::auth("fss.reports", "com_fss.reports"))
		{
			$view[] = FSSRoute::_('index.php?option=com_fss&view=admin_report',false);
			$can_view = true;
		}
		if (FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
		{
			$view[] = FSSRoute::_('index.php?option=com_fss&view=admin_support',false);
			$can_view = true;
		}
		if (FSS_Permission::CanModerate())
		{
			$view[] = FSSRoute::_('index.php?option=com_fss&view=admin_moderate',false);
			$can_view = true;
		}
		
		if (!$can_view)
			return FSS_Admin_Helper::NoPerm();
		
		// if only 1 section visible, then view that section only
		if (count($view) == 1)
		{
			$mainframe = JFactory::getApplication();
			$link = reset($view);
			$mainframe->redirect($link);	
		}

		
		$this->comments = new FSS_Comments(null,null);

		$this->artcounts = FSS_ContentEdit::getArticleCounts();

		parent::display();
	}
}

