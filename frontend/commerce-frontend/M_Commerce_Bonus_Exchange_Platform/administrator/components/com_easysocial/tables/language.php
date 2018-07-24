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

/**
 * Object mapping for `#__social_languages` table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableLanguage extends SocialTable
{
	/**
	 * The unique id of the application
	 * @var int
	 */
	public $id			= null;

	/**
	 * The type of the application. E.g: fields, applications
	 * @var string
	 */
	public $title		= null;

	/**
	 * Determines if the application is a core application.
	 * @var int
	 */
	public $locale		= null;

	/**
	 * Determines if the application is only used for processing only.
	 * @var int
	 */
	public $updated		= null;

	/**
	 * Determines if the application is a unique application.
	 * @var int
	 */
	public $state		= null;

	/**
	 * The unique element of the application.
	 * @var string
	 */
	public $translator		= null;

	/**
	 * The group type of the application. E.g: people, groups , events etc.
	 * @var string
	 */
	public $progress 		= null;

	/**
	 * The title of the application
	 * @var string
	 */
	public $params		= null;

	/**
	 * Used for caching internally.
	 * @var Array
	 */
	public $layouts 		= null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_languages' , 'id' , $db );
	}

	/**
	 * Determines if the language is installed
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function isInstalled()
	{
		return $this->state == SOCIAL_LANGUAGES_INSTALLED;			
	}

	/**
	 * Allows caller to uninstall a language
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function uninstall()
	{
		$locale = $this->locale;

		$paths = array(JPATH_ADMINISTRATOR . '/language/' . $locale, JPATH_ROOT . '/language/' . $locale);

		// Get the list of files on each folders
		foreach ($paths as $path) {
			
			$filter = 'easysocial';
			$files = JFolder::files($path, $filter, false, true);

			if (!$files) {
				continue;
			}

			foreach ($files as $file) {
				JFile::delete($file);
			}
		}

		$this->state = SOCIAL_LANGUAGES_NOT_INSTALLED;
		return $this->store();
	}

	/**
	 * Installs a language file
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function install()
	{
		$params 	= $this->getParams();

		// Get the api key
		$config = FD::config();
		$key = $config->get('general.key');

		// Get the download url
		$url = $params->get('download');

		if (!$url) {
			$this->setError(JText::_('COM_EASYSOCIAL_LANGUAGES_DOWNLOAD_URL_EMPTY'));
			return false;
		}

		// Download the language file
		$connector 	= FD::connector();
		$connector->addUrl($url);
		$connector->setMethod( 'POST' );
		$connector->addQuery('key', $key);
		$connector->connect();

		// Get the contents of the zip file
		$result = $connector->getResult($url);

		// Create a temporary storage for this file
		$md5 = md5(FD::date()->toSql());
		$storage = SOCIAL_TMP . '/' . $md5 . '.zip';
		$state = JFile::write($storage, $result);

		// Set the path for the extracted folder
		$extractedFolder = SOCIAL_TMP . '/' . $md5;

		jimport('joomla.filesystem.archive');

		// Extract the language's archive file
		$state = JArchive::extract( $storage , $extractedFolder );

		// Throw some errors when we are unable to extract the zip file.
		if (!$state) {
			return false;
		}

		$metaPath = $extractedFolder . '/meta.json';

		// Read the meta data file
		$obj = FD::makeObject($metaPath);

		// Get the resources
		$resources = $obj->resources;


		foreach ($resources as $file) {

			// Get the correct path based on the meta's path
			$languageFolder = $this->getPath($file->path);
			$languageFolder	= $languageFolder . '/language';

			// Construct the absolute path
			$path = $languageFolder . '/' . $this->locale;

			// If the folder does not exist, create it first
			if (!JFolder::exists($path)) {
				JFolder::create($path);
			}


			// Set the destination path
			$destFile   = $path . '/' . $this->locale . '.' . $file->title;
			$sourceFile = $extractedFolder . '/' . $file->path . '/' . $this->locale . '.' . $file->title;

			// Try to copy the file
			$state = JFile::copy($sourceFile, $destFile);

			if (!$state) {
				$this->setError(JText::_('COM_EASYSOCIAL_LANGUAGES_ERROR_COPYING_FILES'));
				return false;
			}
		}

		// After everything is copied, ensure that the extracted folder is deleted to avoid dirty filesystem
		JFile::delete($storage);
		JFolder::delete($extractedFolder);

		// Once the language files are copied accordingly, update the state
		$this->state = SOCIAL_LANGUAGES_INSTALLED;

		return $this->store();
	}

	public function getPath( $metaPath )
	{
		switch( $metaPath )
		{
			case 'site':
			case 'module':
				$path	= JPATH_ROOT;
			break;

			case 'admin':
			case 'fields':
			case 'plugins':
			case 'plugin':
			case 'menu':
			case 'apps':
				$path 	= JPATH_ROOT . '/administrator';
			break;
		}

		return $path;
	}
}
