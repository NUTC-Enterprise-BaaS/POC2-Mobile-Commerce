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

class ThemesHelperFilter
{
	/**
	 * Renders the user's group tree
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function lists( $items = array() , $name = 'listitem' , $selected = 'all' , $initial = '' , $initialValue = 'all' )
	{
		$theme 	= FD::themes();

		$theme->set( 'initialValue' , $initialValue );
		$theme->set( 'initial'	, $initial );
		$theme->set( 'name'		, $name );
		$theme->set( 'items'	, $items );
		$theme->set( 'selected'	, $selected );

		$contents 	= $theme->output( 'admin/html/filter.list' );

		return $contents;
	}

	/**
	 * Renders the user's group tree
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function published( $name = 'state' , $selected = 'all' )
	{
		$theme 	= FD::themes();

		$theme->set( 'name'		, $name );
		$theme->set( 'selected'	, $selected );

		$contents 	= $theme->output( 'admin/html/filter.published' );

		return $contents;
	}

	/**
	 * Displays a search box in the filter
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function search( $value = '' , $name = 'search' )
	{
		$theme 	= FD::themes();

		$theme->set( 'value'	, $value );
		$theme->set( 'name'		, $name );

		$contents 	= $theme->output( 'admin/html/filter.search' );

		return $contents;
	}

	/**
	 * Displays the number of items per page selection
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function limit( $selected = 5 , $name = 'limit' , $step = 5 , $min = 5 , $max = 100 , $showAll = true )
	{
		$theme 		= FD::themes();

		$theme->set( 'selected'	, $selected );
		$theme->set( 'name' , $name );
		$theme->set( 'step'	, $step );
		$theme->set( 'min'	, $min );
		$theme->set( 'max'	, $max );
		$theme->set( 'showAll' , $showAll );

		$contents 	= $theme->output( 'admin/html/filter.limit' );

		return $contents;
	}

	/**
	 * Renders a select list for profiles
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function profiles( $name , $selected, $attributes = array() )
	{
		// Get the profiles
		$profilesModel 		= FD::model( 'Profiles' );
		$profiles 			= $profilesModel->getProfiles();
		$attributes 		= !empty( $attributes ) ? implode( ' ' , $attributes ) : '';

		// var_dump( $selected );

		$childs[]	= JHTML::_( 'select.option' , -1 , JText::_( 'COM_EASYSOCIAL_FILTER_SELECT_PROFILE' ) );

		$publishedOnly = false;
		foreach( $profiles as $profile )
		{
			$count 		= $profile->getMembersCount( $publishedOnly );
			$title 		= $profile->get( 'title' );

			$title 		= $count > 0 ? $title . ' (' . $count . ')' : $title;

			$childs[]	= JHTML::_( 'select.option' , $profile->id , $title );
		}

		//get un-assigned users
		$orphanCount = $profilesModel->getOrphanMembersCount( $publishedOnly );
		if( $orphanCount )
		{
			$title 		= JText::_( 'COM_EASYSOCIAL_FILTER_UNASSIGNED_PROFILE' ) . ' (' . $orphanCount . ')';
			$childs[]	= JHTML::_( 'select.option' , -2, $title );
		}


		return JHtml::_( 'select.genericlist' , $childs , $name , $attributes . ' data-table-grid-filter="" class="form-control input-sm"' , 'value' , 'text' , $selected );
	}

	/**
	 * Renders the user's group tree
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function usergroups( $name , $selected = false , $exclude = array() )
	{
		$db 		= FD::db();
		$sql		= $db->sql();

		$sql->select( '#__usergroups', 'a' );
		$sql->column( 'a.id', 'value' );
		$sql->column( 'a.title', 'text' );
		$sql->column( 'b.id', 'level', 'count distinct' );

		$sql->leftjoin( '#__usergroups', 'b' );
		$sql->on( 'a.lft', 'b.lft', '>' );
		$sql->on( 'a.rgt', 'b.rgt', '<' );

		if( !empty( $exclude ) )
		{
			$sql->where( 'a.id', $exclude, 'not in' );
		}

		// This only applies to joomla 1.6 onwards
		$sql->group( 'a.id', 'a.title', 'a.lft', 'a.rgt' );
		$sql->order( 'a.lft', 'asc' );

		$db->setQuery( $sql );
		$options 	= $db->loadObjectList();

		// If there's an error, throw the error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		$childs 	= array();

		$childs[]	= JHTML::_( 'select.option' , -1 , JText::_( 'COM_EASYSOCIAL_FILTER_SELECT_GROUP' ) );

		foreach( $options as &$option )
		{
			$text 		= str_repeat( '- ' , $option->level ) . $option->text;
			$childs[]	= JHTML::_( 'select.option' , $option->value , $text );
		}

		return JHtml::_( 'select.genericlist' , $childs , $name , ' data-table-grid-filter="" class="form-control input-sm"' , 'value' , 'text' , $selected );
	}
}
