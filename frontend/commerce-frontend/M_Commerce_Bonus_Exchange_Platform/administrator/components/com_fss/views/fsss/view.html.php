<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'settings.php');
jimport('joomla.html.pane');


class FsssViewFsss extends JViewLegacy
{
 
    function display($tpl = null)
	{
		FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');
	
		if (JRequest::getVar('hide_template_warning'))
		{
			$db = JFactory::getDBO();
			$sql = "REPLACE INTO #__fss_settings (setting, value) VALUES ('bootstrap_template', '" . $db->escape(FSS_Helper::GetTemplate()) . "')";
			$db->setQuery($sql);
			$db->Query();
			
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_fss&view=fsss', false));
		}

		JToolBarHelper::title( JText::_( 'FREESTYLE_SUPPORT_PORTAL' ), 'fss.png' );
		FSSAdminHelper::DoSubToolbar();
	
		parent::display($tpl);
	}
	
	function Item($title, $link, $icon, $help)
	{
?>
		<div class="fss_main_item fssTip" data-placement="right" title="<?php echo JText::_($help); ?>">	
			<div class="fss_main_icon">
				<a href="<?php echo FSSRoute::_($link); // OK ?>">
					<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_fss/assets/images/<?php echo $icon;?>-48x48.png" width="48" height="48">
				</a>
			</div>
			<div class="fss_main_text">
				<a href="<?php echo FSSRoute::_($link); // OK ?>">
					<?php echo JText::_($title); ?>
				</a>
			</div>
		</div>	
<?php
	}	

	function FSJItem($title, $link, $com, $icon, $help)
	{
		if (strtoupper($title) == $title) // If we are all uppercase, needs translating
			$title = JText::_($title);
		?>
		<div class="fss_main_item fssTip" data-placement="right" title="<?php echo JText::_($help); ?>">	
			<div class="fss_main_icon">
				<a href="<?php echo FSSRoute::_($link); // OK ?>">
					<img src="<?php echo JURI::root( true ); ?>/administrator/components/<?php echo $com; ?>/assets/images/<?php echo $icon;?>-48.png" width="48" height="48">
				</a>
			</div>
			<div class="fss_main_text">
				<a href="<?php echo FSSRoute::_($link); // OK ?>">
					<?php echo JText::_($title); ?>
				</a>
			</div>
		</div>	
<?php
	}
}


