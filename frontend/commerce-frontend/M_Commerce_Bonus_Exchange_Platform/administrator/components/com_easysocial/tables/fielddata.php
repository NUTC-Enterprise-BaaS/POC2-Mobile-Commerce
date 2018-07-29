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

FD::import( 'admin:/tables/table' );

class SocialTableFieldData extends SocialTable
{
	/**
	 * The unique id for this item.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The unique field id.
	 * @var int
	 */
	public $field_id	= null;

	/**
	 * Key identified for the data.
	 * @var string
	 */
	public $datakey		= null;

	/**
	 * The unique item id. E.g: user id.
	 * @var int
	 */
	public $uid 		= null;

	/**
	 * The unique item type. E.g: 'user'
	 * @var string
	 */
	public $type		= null;

	/**
	 * The value of the specific field item in json format
	 * @var string
	 */
	public $data		= null;

	/**
	 * The value of the specific field item in raw string format
	 * @var string
	 */
	public $raw		= null;

	/**
	 * The field params
	 * @var string
	 */
	public $params = null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_fields_data' , 'id' , $db );
	}

	/**
	 * Override the JTable::load function to smartly assign the properties even if the record does not exist.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.2
	 * @access public
	 * @param  mixed     $keys   An optional primary key value to load the row by, or an array of fields to match. If not set the instance property value is used.
	 * @param  boolean   $reset  True to reset the default values before loading the new row.
	 * @return boolean           True if successful. False if row not found.
	 */
	public function load($keys = null, $reset = true)
	{
		$state = parent::load($keys, $reset);

		if (!$state && is_array($keys)) {
			foreach($keys as $key => $value) {
				$this->$key = $value;
			}
		}

		return $state;
	}

	/**
	 * Deprecated. Redirected to native load function instead.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.0
	 * @deprecated Deprecated since 1.2. Use native load function instead.
	 * @access public
	 * @param  Int		$fieldId	The field id
	 * @param  Int		$uid		The target unique id
	 * @param  String	$type		The target type
	 * @return SocialTableFieldData	The table object
	 */
	public function loadByField( $fieldId , $uid , $type )
	{
		return parent::load(array('field_id' => $fieldId, 'uid' => $uid, 'type' => $type));
	}
}
