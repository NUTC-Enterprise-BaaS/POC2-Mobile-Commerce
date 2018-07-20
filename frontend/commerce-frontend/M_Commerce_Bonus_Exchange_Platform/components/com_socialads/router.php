<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Function to build router
 *
 * @param   array  &$query  A named array
 *
 * @return  array
 *
 * @since  1.6
 **/
function socialadsBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['task']))
	{
		$segments[] = implode('/', explode('.', $query['task']));
		unset($query['task']);
	}

	if (isset($query['view']))
	{
		$segments[] = $query['view'];
		unset($query['view']);
	}

	if (isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

	/**
	 * Function to parse route
	 *
	 * @param   array  $segments  A named array
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	function socialadsParseRoute($segments)
	{
		$vars = array();

		// View is always the first element of the array
		$vars['view'] = array_shift($segments);

		while (!empty($segments))
		{
			$segment = array_pop($segments);

			if (is_numeric($segment))
			{
				$vars['id'] = $segment;
			}
			else
			{
				$vars['task'] = $vars['view'] . '.' . $segment;
			}
		}

		return $vars;
	}
