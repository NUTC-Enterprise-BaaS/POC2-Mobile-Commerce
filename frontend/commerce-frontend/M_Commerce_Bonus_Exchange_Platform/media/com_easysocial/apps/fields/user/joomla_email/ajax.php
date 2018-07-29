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
 * Processes ajax calls for the Joomla_Email field.
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserJoomla_Email extends SocialFieldItem
{
	/**
	 * Determines if the email address is valid.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function isValid()
	{
		// Get ajax library.
		$ajax 	= FD::ajax();

		// Get the value from the query.
		$email 	= JRequest::getVar( 'email' );

		// Get the user id of the profile being editted
		$userid = JRequest::getInt( 'userid' );

		// Get the user object
		$user = FD::user( $userid );

		// Check for required
		if( $this->isRequired() && empty( $email ) )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_REQUIRED' ) );
		}

		// Check against regex
		if (!empty($email) && $this->params->get('regex_validate')) {
			$format = $this->params->get('regex_format');
			$modifier = $this->params->get('regex_modifier');

			$pattern = '/' . $format . '/' . $modifier;

			$result = preg_match($pattern, $email);

			if (empty($result)) {
				return $ajax->reject(JText::_('PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_INVALID_FORMAT'));
			}
		}

		$valid 	= SocialFieldsUserJoomlaEmailHelper::isValid( $email );

		// Check for email validity
		if (!$valid) {
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_INVALID_EMAIL' ) );
		}

		// Check for allowed
		if (!SocialFieldsUserJoomlaEmailHelper::isAllowed($email, $this->params)) {
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_DOMAIN_IS_NOT_ALLOWED' ) );
		}

		// Check for disallowed
		if (SocialFieldsUserJoomlaEmailHelper::isDisallowed($email, $this->params)) {
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_DOMAIN_IS_DISALLOWED' ) );
		}

		// Check for forbidden words
		if (SocialFieldsUserJoomlaEmailHelper::isForbidden($email, $this->params)) {
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_CONTAINS_FORBIDDEN' ) );
		}

		// Check for existance
		if (SocialFieldsUserJoomlaEmailHelper::exists($email, $user->email)) {
			return $ajax->reject( JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_ALREADY_USED' ) );
		}

		return $ajax->resolve();
	}
}
