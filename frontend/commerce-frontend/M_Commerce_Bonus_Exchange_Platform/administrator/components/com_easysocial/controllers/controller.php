<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Include main controller here.
FD::import( 'admin:/controllers/main' );

class EasySocialController extends EasySocialControllerMain
{
	protected $app	= null;

	// This will notify the parent class that this is for the back end.
	protected $location = 'backend';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Internal function to update the database with the necessary columns
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark
	 */
	public function update()
	{
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		// Lookup for sql files.
		$path 	= SOCIAL_ADMIN . '/updates';

		$files 	= JFolder::files( $path, '.json$', true, true );
		$info 	= FD::getInstance( 'Info' );

		if( !$files )
		{
			$info->set( 'Nothing to update' );
			return $this->setRedirect( 'index.php?option=com_easysocial' );
		}

		foreach( $files as $file )
		{
			$contents 	= JFile::read( $path . '/' . $file );

			$db 		= FD::db();
			$db->setQuery( $contents );

			$db->Query();
		}

		$str 	= implode( ',' , $files );

		$info->set( 'Files ' . $str . ' executed.' );

		$this->setRedirect( 'index.php?option=com_easysocial' );
	}

	/**
	 * This is the center of the brain to process all views.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 */
	public function display( $cacheable = false , $urlparams = false )
	{
		$document	= JFactory::getDocument();
		$type		= $document->getType();

		$viewName	= JRequest::getCmd( 'view', $this->getName() );

		// Set the layout
		$viewLayout	= JRequest::getCmd( 'layout', 'default' );

		$view		= $this->getView( $viewName , $type , '' );
		$view->setLayout($viewLayout);

		if( $type == 'ajax' )
		{
			if( !method_exists( $view , $viewLayout ) )
			{
				$view->display();
			}
			else
			{
				$json 		= FD::json();
				$params 	= $json->decode( JRequest::getVar( 'params' ) );

				call_user_func_array( array( $view , $viewLayout ) , $params );
			}
		}
		else
		{
			if( $viewLayout != 'default' )
			{
				if( !method_exists( $view , $viewLayout ) )
				{
					$view->display();
				}
				else
				{
					call_user_func_array( array( $view , $viewLayout ) , array() );
				}
			}
			else
			{
				$view->display();
			}
		}
	}

	/**
	 * Allows a caller to check if a task exist since we're able to access $taskMap from this derived class.
	 *
	 * @since	1.0
	 * @param	string	The name of the task.
	 * @return	bool	True if exists, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function taskAliasExist( $task )
	{
		$keys 	= array_keys( $this->taskMap );

		return in_array( $task , $keys );
	}
}
