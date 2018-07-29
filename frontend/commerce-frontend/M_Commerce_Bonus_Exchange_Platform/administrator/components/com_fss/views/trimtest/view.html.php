<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.utilities.date');

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS.'emailcheck.php');

class fsssViewTrimTest extends JViewLegacy
{
	
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		JHtml::_('behavior.framework');
		JHTML::_('behavior.tooltip');

		$task = JRequest::getVar('task');

		if ($task == "test")
			return $this->doTest();

		JToolBarHelper::title( JText::_("EMail Trim Test"), 'fss_trimtest' );
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();
		
		parent::display($tpl);
	}

	function doTest()
	{
		$ec = new FSSCronEMailCheck();
		$ec->plainmsg = JRequest::getVar('email', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$ec->TrimMessage();

		$ec->plainmsg = str_replace("[/quoted]", "", $ec->plainmsg);
		$parts = explode("[quoted]", $ec->plainmsg);

		if (count($parts) > 1)
		{
			echo "<h4>Message:</h4>";
			echo "<pre class='main'>";
			echo $parts[0];
			echo "</pre>";
			echo "<h4>Hidden (will be displayed in 'quoted' rollover when viewing the message):</h4>";
			echo "<pre class='trimmed'>";
			echo $parts[1];
			echo "</pre>";
		} else {
			echo "No split detected<br>";
		}

		exit;
	}
}


