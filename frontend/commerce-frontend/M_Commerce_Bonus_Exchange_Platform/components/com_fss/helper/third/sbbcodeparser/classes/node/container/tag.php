<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

namespace SBBCodeParser;
defined('_JEXEC') or die;

class Node_Container_Tag extends Node_Container
{
	/**
	 * Tag name of this node
	 * @var string
	 */
	protected $tag;

	/**
	 * Assoc array of attributes
	 * @var array
	 */
	protected $attribs;


	public function __construct($tag, $attribs)
	{
		$this->tag     = $tag;
		$this->attribs = $attribs;
	}

	/**
	 * Gets the tag of this node
	 * @return string
	 */
	public function tag()
	{
		return $this->tag;
	}

	/**
	 * Gets the tags attributes
	 * @return array
	 */
	public function attributes()
	{
		return $this->attribs;
	}
}
		  	 	 	 		  		