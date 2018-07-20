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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include the fields library
FD::import( 'admin:/includes/fields/dependencies' );

// Include helper lib
require_once( dirname( __FILE__ ) . '/helper.php' );

/**
 * Field application for Joomla user editor
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserJoomla_user_editor extends SocialFieldItem
{
	protected $editor	= null;

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegister( &$post , &$registration )
	{
		$jConfig = FD::jconfig();

		// Get value.
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : $jConfig->editor;

		// Set value.
		$this->set( 'value', $value );

		// Get editors.
		$editors 	= SocialEditorHelper::getEditors();

		// Set editors.
		$this->set( 'editors'	, $editors );

		// Check for errors.
		$error		= $registration->getErrors( $this->inputName );

		// Set errors.
		$this->set( 'error' 	, $error );

		// Output the registration template.
		return $this->display();
	}

	/**
	 * Save trigger which is called before really saving the object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialUser	The user object.
	 * @return	bool	The state of the trigger
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post , &$user )
	{
		$value = $post[$this->inputName];

		$user->setParam( 'editor', $value );

		unset( $post[$this->inputName] );

		return true;
	}

	/**
	 * Displays the field input for user when they edit their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$value = '';

		// Get value.
		$jConfig = FD::jconfig();
		$tmp = $jConfig->editor;
		if( !empty( $tmp ) )
		{
			$value = $tmp;
		}

		$tmp = $user->getParam( 'editor' );
		if( !empty( $tmp ) )
		{
			$value = $tmp;
		}

		if( !empty( $post[ $this->inputName ] ) )
		{
			$value = $post[ $this->inputName ];
		}

		// Set value.
		$this->set( 'value', $value );

		// Get editors.
		$editors 	= SocialEditorHelper::getEditors();

		// Set editors.
		$this->set( 'editors'	, $editors );

		// Check for errors.
		$error		= $this->getError( $errors );

		// Set errors.
		$this->set( 'error' 	, $error );

		// Output the edit template.
		return $this->display();
	}

	/**
	 * Save trigger which is called before really saving the object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialUser	The user object.
	 * @return	bool	The state of the trigger
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditBeforeSave( &$post , &$user )
	{
		$value = $post[$this->inputName];

		$user->setParam( 'editor', $value );

		unset( $post[$this->inputName] );

		return true;
	}


	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}
}
