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

/**
 * Field application for Gender
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserGender extends SocialFieldItem
{
	/**
	 * Returns the available options for selection of this field
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.1
	 * @access public
	 * @return Array    The available options
	 */
	public function getOptions()
	{
		$options = array(
			1 => JText::_( 'PLG_FIELDS_GENDER_OPTION_MALE' ),
			2 => JText::_( 'PLG_FIELDS_GENDER_OPTION_FEMALE' )
		);

		return $options;
	}

	public function getValue()
	{
		$container = $this->getValueContainer();

		$gender = new stdClass;

		switch ($container->data)
		{
			case 1:
			case '1':
				$gender->text = 'PLG_FIELDS_GENDER_DISPLAY_MALE';
				break;
			case 2:
			case '2':
				$gender->text = 'PLG_FIELDS_GENDER_DISPLAY_FEMALE';
				break;
			default:
				$gender->text = 'PLG_FIELDS_GENDER_OPTION_NOT_SPECIFIED';
				break;
		}

		$gender->value = $container->data;
		$gender->title = JText::_($gender->text);

		$container->value = $gender;

		return $container;
	}

	public function getDisplayValue()
	{
		$value = $this->getValue();

		$term = 'PLG_FIELDS_GENDER_OPTION_NOT_SPECIFIED';

		if( $value == 1 )
		{
			$term = 'PLG_FIELDS_GENDER_DISPLAY_MALE';
		}

		if( $value == 2 )
		{
			$term = 'PLG_FIELDS_GENDER_DISPLAY_FEMALE';
		}

		return JText::_( $term );
	}

	/**
	 * Performs validation for the gender field.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function validate( $value )
	{
		// Catch for errors if this is a required field.
		if( $this->isRequired() && empty( $value ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_GENDER_VALIDATION_GENDER_REQUIRED' ) );

			return false;
		}

		return true;
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post, &$registration )
	{
		$value = !empty($post[$this->inputName]) ? $post[$this->inputName] : '';

		return $this->validate( $value );
	}

	/**
	 * Performs validation when a user updates their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		$value = !empty($post[$this->inputName]) ? $post[$this->inputName] : '';

		return $this->validate( $value );
	}

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
	public function onRegister( &$post, &$registration )
	{
		// Get the default value.
		$value 		= '';

		// If the value exists in the post data, it means that the user had previously set some values.
		if( !empty( $post[ $this->inputName ] ) )
		{
			$value 	= $post[ $this->inputName ];
		}

		// Detect if there's any errors.
		$error 	= $registration->getErrors( $this->inputName );

		$this->set( 'error'		, $error );
		$this->set( 'value'		, $value );

		return $this->display();
	}


	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function onDisplay( $user )
	{
		$value 	= $this->value;

		if( empty( $value ) )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		// Push variables into theme.
		$this->set( 'value'	, $value );

		// linkage to advanced search page.
		$field = $this->field;
		if ($field->type == SOCIAL_FIELDS_GROUP_USER && $field->searchable) {
			$params = array( 'layout' => 'advanced' );
			$params['criterias[]'] = $field->unique_key . '|' . $field->element;
			$params['operators[]'] = 'equal';
			$params['conditions[]'] = $this->value;

			$advsearchLink = FRoute::search($params);
			$this->set( 'advancedsearchlink'	, $advsearchLink );
		}

		return $this->display();
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @return
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$value 	= !empty($post[$this->inputName]) ? $post[$this->inputName] : $this->value;

		$error = $this->getError( $errors );

		$this->set( 'value', $value );
		$this->set( 'error', $error );


		return $this->display();
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}

	public function onRegisterOAuthBeforeSave( &$post, $client )
	{
		if( empty( $post['gender'] ) )
		{
			return;
		}

		$post[$this->inputName] = 0;

		if( $post['gender'] === 'male' )
		{
			$post[$this->inputName] = 1;
		}

		if( $post['gender'] === 'female' )
		{
			$post[$this->inputName] = 2;
		}
	}

	public function onOAuthGetMetaFields( &$fields )
	{
		$fields[] = 'gender';
	}

	/**
	 * Checks if this field is complete.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.2
	 * @access public
	 * @param  SocialUser    $user The user being checked.
	 */
	public function onFieldCheck($user)
	{
		return $this->validate($this->value);
	}

	/**
	 * Trigger to get this field's value for various purposes.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.2
	 * @access public
	 * @param  SocialUser    $user The user being checked.
	 * @return Mixed               The value data.
	 */
	public function onGetValue($user)
	{
		return $this->getValue();
	}

	/**
	 * Checks if this field is filled in.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.3
	 * @access public
	 * @param  array        $data   The post data.
	 * @param  SocialUser   $user   The user being checked.
	 */
	public function onProfileCompleteCheck($user)
	{
		if (!FD::config()->get('user.completeprofile.strict') && !$this->isRequired()) {
			return true;
		}

		return !empty($this->value);
	}
}

class SocialFieldsUserGenderValue extends SocialFieldValue
{
	public function toString()
	{
		return $this->value->title;
	}

	public function toDisplay($display = '', $linkToAdvancedSearch = false)
	{
		$value = $this->value->title;

		if (! $this->value->value) {
			return '';
		}

		$lib = FD::privacy( FD::user()->id );
		if (! $lib->validate( 'field.' . $this->element, $this->field_id, SOCIAL_TYPE_FIELD, $this->uid )) {
			return '';
		}

		if ($display) {
			// append gender icon.
			$icontype = $this->value->value == 1 ? 'male' : 'female';
			$icon = '';

			if ($this->value->value != 3) {
				$icon = '<i class="fa mr-5 fa-' . $icontype . '"></i>';
			}

			$value = $icon . $this->value->title;
		}

		if ($linkToAdvancedSearch) {
			$params = array( 'layout' => 'advanced' );
			$params['criterias[]'] = $this->unique_key . '|' . $this->element;
			$params['operators[]'] = 'equal';
			$params['conditions[]'] = $this->value->value;

			$advsearchLink = FRoute::search($params);

			$value = '<a class="fd-small muted" href="'.$advsearchLink.'">' . $value . '</a>';
		}

		return $value;

	}
}
