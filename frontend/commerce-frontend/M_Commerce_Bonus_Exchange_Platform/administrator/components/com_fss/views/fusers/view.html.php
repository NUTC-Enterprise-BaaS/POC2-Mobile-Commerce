<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php');

class FsssViewFusers extends JViewLegacy
{
 
    function display($tpl = null)
    {
		FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');
		
		FSS_Helper::IncludeModal();
		
		JToolBarHelper::title( JText::_("Permissions"), 'fss_users' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        //JToolBarHelper::addNew("OK");
		$bar = JToolbar::getInstance('toolbar');
		$bar->appendButton('Standard', 'new', "FSS_Add_User", "add", false);

        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
	
	function Item($title, $link, $icon, $help)
	{
?>
		<div class="fss_main_item fssTip" title="<?php echo JText::_($help); ?>">	
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
}



