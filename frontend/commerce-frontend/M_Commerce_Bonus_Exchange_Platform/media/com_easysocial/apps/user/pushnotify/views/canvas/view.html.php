<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 %COMPANY_NAME%. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* %PACKAGE% is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * Canvas view for Textbook app
 *
 * @since	1.0
 * @access	public
 */
class PushnotifyViewCanvas extends SocialAppsView
{
	/**
	 * This method is invoked automatically and must exist on this view.
	 *
	 * @since	1.0.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 * @return 	void
	 */
	public function display( $userId )
	{
		// Requires the viewer to be logged in to access this app
		Foundry::requireLogin();

		// We want the user object from EasySocial so we can do funky stuffs.
		$user 	= Foundry::user( $userId );

		// Since we are on the canvas page, we have the flexibility to change the page title.
		if( $user->isViewer() )
		{
			$title 	= JText::_( 'Your Textbooks' );
		}
		else
		{
			$title	= JText::sprintf( 'Textbooks from %1s' , $user->getName() );
		}

		// Set the page title. You can use JFactory::getDocument()->setTitle( 'title' ) as well.
		Foundry::page()->title( $title );

		// Load up the model
		$model 	= $this->getModel( 'Pushnotify' );

		// Get the list of textbooks created by the user.
		$result = $model->getItems( $userId );

		// Since the table decorates the date, we want to load them into the tables respectively.
		$textbooks 	= array();
	
		if( $result )
		{
			foreach( $result as $row )
			{
				// Load up the textbook's ORM
				$book 	= $this->getTable( 'Pushnotify' );
				$book->bind( $row );

				$textbooks[]	= $book;
			}	
		}

		// Assign the textbooks to the theme files.
		// This option is totally optional, you can use your own theme object to output files.
		$this->set( 'textbooks' , $textbooks );
		$this->set( 'user'		, $user );

		// If you use the built in theme manager, the namespace is relative to the following folder,
		// /media/com_easysocial/apps/user/textbook/themes/default

		$namespace 	= 'canvas/default';

		// Output the contents
		echo parent::display( $canvas );
	}
}
