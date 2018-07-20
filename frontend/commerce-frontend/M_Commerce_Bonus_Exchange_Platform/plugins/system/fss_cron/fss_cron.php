<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'cron.php' );

	class plgSystemFSS_Cron extends JPlugin
	{ 
		/**
		 * Constructor
		 *
		 * For php4 compatibility we must not use the __constructor as a constructor for plugins
		 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
		 * This causes problems with cross-referencing necessary for the observer design pattern.
		 *
		 * @access	protected
		 * @param	object	$subject The object to observe
		 * @param 	array   $config  An array that holds the plugin configuration
		 * @since	1.0
		 */
		function __construct( &$subject, $config )
		{
			parent::__construct( $subject, $config );
		}
		
		function onAfterInitialise()
		{
			if (JFactory::getApplication()->isAdmin()) return;

			FSS_Cron_Helper::runCron();
		}
	}
}