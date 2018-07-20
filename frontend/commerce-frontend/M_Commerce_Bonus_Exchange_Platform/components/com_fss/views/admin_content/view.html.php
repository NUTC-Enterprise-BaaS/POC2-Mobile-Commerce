<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 227df3f83fcb339c3e282a7ad87aad91
*/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content.php');

class FssViewAdmin_Content extends FSSView
{	
	function display($tpl = null)
	{
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		$this->layout = FSS_Input::getCmd('layout',  FSS_Input::getCmd('_layout', ''));
		$this->view = FSS_Input::getCmd('view',  FSS_Input::getCmd('_view', ''));
		
		if (!FSS_Permission::PermAnyContent())
			return FSS_Admin_Helper::NoPerm();
		
		$this->type = FSS_Input::getCmd('type','');
		
		if ($this->type != "")
			return $this->displayType();
		
		$this->artcounts = FSS_ContentEdit::getArticleCounts();
		parent::display();
	}
	
	function displayType()
	{
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.$this->type.'.php');
		$class = "FSS_ContentEdit_{$this->type}";
		$content = new $class();
			
		$content->layout = $this->layout;
		$content->type = $this->type;
		$content->view = $this->view;
			
		FSS_Helper::IncludeModal();
			
		$content->Display();
	}
	
}
