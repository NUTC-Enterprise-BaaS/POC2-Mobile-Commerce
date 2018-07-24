<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

class SocialStorage
{
	private $adapter = null;

	public function __construct($storage = 'joomla')
	{
		// Always lowercase the storage name
		$storage = strtolower($storage);

		$file = __DIR__ . '/' . $storage . '/' . $storage . '.php';
		require_once($file);

		$className = 'SocialStorage' . ucfirst($storage);
		
		$this->adapter = new $className();
	}

	public function factory($storage = 'joomla')
	{
		return new self($storage);
	}

	/**
	 * Maps back the call method functions to the helper.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	Method's name.
	 * @param	mixed	Arguments
	 * @return
	 */
	public function __call($method, $args)
	{
		$refArray = array();

		if ($args) {
			foreach ($args as &$arg) {
				$refArray[]	=& $arg;
			}
		}

		return call_user_func_array(array($this->adapter, $method), $refArray);
	}
}

interface SocialStorageInterface
{
	public function init();

	public function createContainer($container);

	public function getPermalink($relativePath);

	public function push($fileName, $path, $relativePath);

	public function pull($relativePath);

	public function delete($relativePath, $folder = false);
}
