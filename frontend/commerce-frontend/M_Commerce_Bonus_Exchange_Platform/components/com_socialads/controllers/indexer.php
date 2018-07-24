<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport('joomla.application.component.controller');
//require_once(JPATH_COMPONENT . DS . 'helper.php');
include_once(JPATH_COMPONENT.DS.'controller.php');

class socialadsControllerIndexer extends JControllerLegacy
{

	public function makeIndexing()
	{
			// require(JPATH_SITE.DS."administrator".DS."components".DS."com_socialads".DS."config".DS."config.php");
			$saParams = JComponentHelper::getParams('com_socialads');

			$input=JFactory::getApplication()->input;
            //$post=$input->post;
            //$input->get
			$pkey = $input->get('pkey');
			$indexlimit = $input->get('indexlimit');
			$indexlimitstart = $input->get('indexlimitstart');

			// if($pkey!=$socialads_config['cron_key'])
			if ($pkey != $saParams->get('cron_key'))
			{
				echo JText::_("CRON_KEY_MSG");
				return;
			}
			if(JVERSION >= '2.5.0')
			{
				$model = $this->getModel('indexer');
				$model->makeIndexing($indexlimitstart,$indexlimit,$pkey);
			}

	}



}// class end


