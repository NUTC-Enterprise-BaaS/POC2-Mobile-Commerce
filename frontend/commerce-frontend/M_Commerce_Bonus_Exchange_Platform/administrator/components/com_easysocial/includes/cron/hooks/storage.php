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

class SocialCronHooksStorage extends EasySocial
{
	/**
	 * Executes all remote storage
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function execute(&$states)
	{
		// Offload videos to remote location
		$states[] = $this->syncVideos();

		// Sync cached images from links to remote location
		$states[] = $this->syncLinkImages();

		// Offload photos to remote location
		$states[] = $this->syncPhotos();

		// Process avatar storages here
		$states[] = $this->syncAvatars();

		// Process file storages here
		$states[] = $this->syncFiles();
	}

	/**
	 * Retrieves the storage type
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function getStorageType($type)
	{
		$type = $this->config->get('storage.' . $type, 'joomla');

		return $type;
	}

	/**
	 * Retrieves the storage library
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function getStorageLibrary($type)
	{
		return FD::storage($this->getStorageType($type));
	}

	/**
	 * Retrieves the limit on the number of files to sync
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function getUploadLimit($type)
	{
		$type = $this->getStorageType($type);
		$limit = (int) $this->config->get('storage.' . $type . '.limit');

		return $limit;
	}

	/**
	 * Determines if we should delete the local file
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function deleteable($type)
	{
		$delete = $this->config->get('storage.' . $type . '.delete');

		return $delete;
	}

	/**
	 * Creates a new log entry when a file is uploaded
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function log($id, $type, $success)
	{
		$storageType = $this->getStorageType($type);

		// Add this to the storage logs
		$log = FD::table('StorageLog');
		$log->object_id = $id;
		$log->object_type = $type;
		$log->target = $storageType;
		$log->state = $success;
		$log->created = FD::date()->toSql();

		return $log->store();
	}

	/**
	 * Retrieves the list of log items
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getFailedObjects($objectType, $state = SOCIAL_STATE_UNPUBLISHED)
	{
		$db = FD::db();
		$sql = $db->sql();
		$sql->select('#__social_storage_log');
		$sql->column('object_id');
		$sql->where('object_type', $objectType);
		$sql->where('state', $state);

		$db->setQuery($sql);

		$ids = $db->loadColumn();

		return $ids;
	}

	/**
	 * Synchronizes cached images from local storage to remote storage
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncLinkImages()
	{
		$storageType = $this->getStorageType('links');

		// If site is configured to storage in joomla, we don't need to do anything
		if ($storageType == 'joomla') {

			$defaultMessage = JText::_('Current link photo storage is set to local and there are no item stored externally to download.');

			// Check if any photos with the "storage" property to non "joomla"
			$model = ES::model('Links');	

			$linkImages = $model->getLinkImagesStoredExternally();

			// Check if do not have any "storage" property to Amazon 
			if (!$linkImages) {
				return $defaultMessage;
			}

			// Load up the storage library
			$storage = ES::storage('amazon');
			$total = 0;

			foreach ($linkImages as $linkImage) {

				$table = ES::table('LinkImage');
				$table->bind($linkImage);

				// Relative path to the item
				$relativePath = ES::cleanPath($this->config->get('links.cache.location'));

				// call the api to retrieve back the data
				$storage->pull($relativePath);

				$states = array();

				// make sure these files donwloaded into local server
				$linkImageFile = JPATH_ROOT . '/' . $relativePath . '/' . $linkImage->internal_url;
				$states[] = JFile::exists($linkImageFile);

				// Determines if the download was successful
				$success = !in_array(false, $states);

				// If all the files were downloaded successfully
				if ($success) {
					$table->storage = $storageType;
					$table->store();

					$total += 1;
				}

				// Add this to the storage logs
				$this->log($table->id, 'linkimage.pull', $success);				
			}

			if ($total > 0) {
				return JText::sprintf('%1s link photos downloaded to local server.', $total);
			}

			return $defaultMessage;
		}

		// Get the storage library
		$storage = $this->getStorageLibrary('links');
		$limit = $this->getUploadLimit('links');

		// Get a list of items that should be excluded
		$exclusion = $this->getFailedObjects('linkimages');

		// Get a list of cached images to be synchronized over.
		$model = FD::model('Links');
		$options = array('storage' => SOCIAL_STORAGE_JOOMLA, 'limit' => $limit, 'exclusion' => $exclusion);
		$images = $model->getCachedImages($options);

		if (!$images) {
			return JText::_('No cached link images to sync with Amazon S3 right now.');
		}

		$states = array();
		$total = 0;

		foreach ($images as $image) {

			$state = $storage->push($image->internal_url, $image->getAbsolutePath(), $image->getRelativePath());

			if ($state) {

				if ($this->deleteable($storageType)) {
					JFile::delete($image->getAbsolutePath());
				}

				// Store the new storage type
				$image->storage = $storageType;
				$image->store();

				$total += 1;
			}

			$states[] = $state;

			// Create a log for this item
			$this->log($image->id, 'linkimage', $state);
		}

		if ($total > 0) {
			return JText::sprintf('%1s cached images uploaded to remote storage', $total);
		}

		return JText::sprintf('No cached images to upload to remote storage');
	}

	/**
	 * Synchronizes photos from local storage to remote storage.
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncPhotos()
	{
		$storageType = $this->getStorageType('photos');	

		// If site is configured to storage in joomla, we don't need to do anything
		if ($storageType == 'joomla') {

			$defaultMessage = JText::_('Current photo storage is set to local and there are no item stored externally to download.');

			// Check if any photos with the "storage" property to non "joomla"
			$model = ES::model('Photos');

			$photos = $model->getPhotosStoredExternally('amazon');

			// Check if do not have any "storage" property to Amazon 
			if (!$photos) {
				return $defaultMessage;
			}

			// Load up the storage library
			$storage = ES::storage('amazon');
			$total = 0;

			foreach ($photos as $photo) {
				$table = ES::table('Photo');
				$table->bind($photo);

				// Get the relative path to the photo
				$photoFolder = $table->getFolder(true, false);

				// call the api to retrieve back the data
				$storage->pull($photoFolder);

				// Get list of allowed photos
				$types = array('thumbnail', 'large', 'square', 'featured', 'original', 'stock');			
				$states = array();

				// We need to run this to ensure that all the files are restored back to it's correct local storage.
				foreach ($types as $type) {

					// Check if the file was already downloaded
					$path = $table->getPath($type);

					$states[] = JFile::exists($path);
				}

				// Determines if the download was successful
				$success = !in_array(false, $states);

				// If all the files were downloaded successfully
				if ($success) {	
					$table->storage = $storageType;
					$table->store();

					$stream = ES::table('StreamItem');
					$options = array('context_type' => SOCIAL_TYPE_PHOTO, 'context_id' => $table->id);
					$exists = $stream->load($options);

					if ($exists) {
						$stream->params = json_encode($table);
						$stream->store();
					}

					$total += 1;
				}

				// Add this to the storage logs
				$this->log($table->id, 'photos.pull', $success);				
			}

			if ($total > 0) {
				return JText::sprintf('%1s photos downloaded to local server.', $total);
			}

			return $defaultMessage;
		}

		// Load up the storage library
		$storage = ES::storage($storageType);

		// Get the number of files to process at a time
		$limit = $this->getUploadLimit('photos');

		// Get a list of photos that failed during the transfer
		$exclusion = $this->getFailedObjects('photos');

		// Get a list of files to be synchronized over.
		$model = ES::model('Photos');
		$options = array(
						'pagination' => $limit,
						'storage' => SOCIAL_STORAGE_JOOMLA,
						'ordering' => 'created',
						'sort' => 'asc',
						'exclusion' => $exclusion
					);

		// Get a list of photos to sync to amazon
		$photos = $model->getPhotos($options);
		$total = 0;

		if (!$photos) {
			return JText::_('No photos to upload to Amazon S3 right now.');
		}

		// Get list of allowed photos
		$allowed = array('thumbnail', 'large', 'square', 'featured', 'medium', 'original', 'stock');

		foreach ($photos as $photo) {

			$album = ES::table('Album');
			$album->load($photo->album_id);

			// If the album no longer exists, skip this
			if (!$album->id) {
				continue;
			}

			// Get the base path for the album
			$basePath = $photo->getStoragePath($album);
			$states = array();

			// Now we need to get all the available files for this photo
			$metas = $model->getMeta($photo->id, SOCIAL_PHOTOS_META_PATH);

			foreach ($metas as $meta) {

				// To prevent some faulty data, we need to manually reconstruct the path here.
				$absolutePath = $meta->value;

				$file = basename($absolutePath);

				$container = ES::cleanPath($this->config->get('photos.storage.container'));

				// Reconstruct the path to the source file
				$source = JPATH_ROOT . '/' . $container . '/' . $album->id . '/' . $photo->id . '/' . $file;

				if (!JFile::exists($source)) {
					$states[] = false;
				} else {
					// To prevent faulty data, manually reconstruct the path here.
					$dest = $container . '/' . $album->id . '/' . $photo->id . '/' . $file;
					$dest = ltrim($dest, '/');

					// We only want to upload certain files
					if (in_array($meta->property, $allowed)) {

						$fileName = $photo->title . $photo->getExtension($meta->property);
						$state = $storage->push($fileName, $source, $dest);

						// Delete the source file if successfull and configured to do so.
						if ($state && $this->deleteable($storageType)) {
							JFile::delete($source);
						}

						$states[] = $state;
					}
				}
			}

			$success = !in_array(false, $states);

			// If there are no errors, we want to update the storage for the photo
			if ($success) {

				$photo->storage = $storageType;
				$state = $photo->store();

				// if photo storage successfully updated to amazon, we need to update the cached object in stream_item.
				// Find and update the object from stream_item.
				$stream = ES::table('StreamItem');
				$options = array('context_type' => SOCIAL_TYPE_PHOTO, 'context_id' => $photo->id);
				$exists = $stream->load($options);

				if ($exists) {
					$stream->params = json_encode($photo);
					$stream->store();
				}

				$total += 1;
			}

			// Add this to the storage logs
			$this->log($photo->id, 'photos', $success);
		}

		if ($total > 0) {
			return JText::sprintf('%1s photos uploaded to remote storage', $total);
		}

		return JText::sprintf('No photos to upload to remote storage');
	}

	/**
	 * Synchronizes videos from local storage to remote storage.
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncVideos()
	{
		$storageType = $this->getStorageType('videos');

		// If site is configured to storage in joomla, we don't need to do anything
		if ($storageType == 'joomla') {

			$defaultMessage = JText::_('Current video storage is set to local and there are no item stored externally to download.');

			// Check if any avatars with the "storage" property to non "joomla"
			$model = ES::model("Videos");
			$videos = $model->getVideosStoredExternally();

			// Check if do not have any "storage" property to Amazon 
			if (!$videos) {
				return $defaultMessage;
			}

			// Load up the storage library
			$storage = ES::storage('amazon');
			$total = 0;

			foreach ($videos as $video) {

				$table = ES::table('Video');
				$table->bind($video);

				// Reconstruct the path to the source file
				$relativePath = '/' . ES::cleanPath($this->config->get('video.storage.container')) . '/' . $video->id;

				// call the api to retrieve back the data
				$storage->pull($relativePath);

				$states = array();

				// check the video thumbnail file exist or not
				if ($video->thumbnail) {

					// normalize the thumbnail path
					$relativeThumbPath = str_ireplace(JPATH_ROOT, '', $video->thumbnail);

					$absoluteThumbPath = JPATH_ROOT . '/' . $relativeThumbPath;

					$states[] = JFile::exists($absoluteThumbPath);
				}

				// check the video file exist or not
				if ($table->isUpload()) {
					
					// normalize the thumbnail path
					$relativeVideoPath = str_ireplace(JPATH_ROOT, '', $video->path);

					$absoluteVideoPath = JPATH_ROOT . '/' . $relativeVideoPath;

					$states[] = JFile::exists($absoluteVideoPath);
				}

				// Determines if the download was successful
				$success = !in_array(false, $states);

				// If all the files were downloaded successfully
				if ($success) {
					$table->storage = $storageType;
					$table->store();

					$total += 1;
				}
				
				// Add this to the storage logs
				$this->log($table->id, 'videos.pull', $success);
			}

			if ($total > 0) {
				return JText::sprintf('%1s videos downloaded from amazon to local.', $total);
			}

			return $defaultMessage;
		}

		// Load up the storage library
		$storage = FD::storage($storageType);

		// Get the number of files to process at a time
		$limit = $this->getUploadLimit('videos');

		// Get a list of photos that failed during the transfer
		$exclusion = $this->getFailedObjects('videos');

		// Get a list of files to be synchronized over.
		$model = ES::model('Videos');
		$options = array(
						'pagination' > $limit,
						'storage' => SOCIAL_STORAGE_JOOMLA,
						'ordering' => 'random',
						'privacy' => false,
						'exclusion' => 	$exclusion
					);

		// Get a list of photos to sync to amazon
		$result = $model->getVideos($options);
		$total = 0;

		if (!$result) {
			return JText::_('No videos to sync to Amazon S3 right now.');
		}

		foreach ($result as $video) {
			
			// Upload the thumbnail of the video
			$source = JPATH_ROOT . '/' . $video->getRelativeThumbnailPath();
			$destination = '/' . $video->getRelativeThumbnailPath();
			$thumbnailState = $storage->push($video->getThumbnailFileName(), $source, $destination);

			// Upload the video file
			if ($video->isUpload()) {
				$source = JPATH_ROOT . '/' . $video->getRelativeFilePath();
				$destination = '/' . $video->getRelativeFilePath();
				$videoTable = $video->getItem();
				$videoFileState = $storage->push($videoTable->file_title, $source, $destination);
			} else {
				$videoFileState = true;
			}

			// Default to fail
			$success = false;

			if ($videoFileState && $thumbnailState) {
				$success = true;

				// Set the storage for the video to the respective storage type
				$table = $video->getItem();
				$table->storage = $storageType;
				$table->store();
			}

			// If file is pushed to the server successfully, we need to delete the entire video container.
			if ($this->deleteable($storageType) && $success) {
				$container = JPATH_ROOT . '/' . $video->getContainer();

				// Try to delete the container now
				JFolder::delete($container);
			}

			if ($success) {
				$total += 1;
			}

			// Add this to the storage logs
			$this->log($video->id, 'videos', $success);
		}

		if ($total > 0) {
			return JText::sprintf('%1s videos uploaded to remote storage', $total);
		}

		return JText::_('No videos to upload to remote storage');
	}

	/**
	 * Synchronizes files to remote storage
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncFiles()
	{
		$storageType = $this->getStorageType('files');

		if ($storageType == 'joomla') {

			$defaultMessage = JText::_('Current file storage is set to local and there are no item stored externally to download.');

			// Check if any files with the "storage" property to non "joomla"
			$model = ES::model('Files');
			$files = $model->getFilesStoredExternally();

			// Check if do not have any "storage" property to Amazon 
			if (!$files) {
				return $defaultMessage;
			}

			// Load up the storage library
			$storage = ES::storage('amazon');
			$total = 0;

			foreach ($files as $file) {
				$table = ES::table('File');
				$table->bind($file);

				// Reconstruct the path to the source file
				$relativePath = '/' . ES::cleanPath($this->config->get('files.storage.container'));
				$relativePath .= '/' . ES::cleanPath($this->config->get('files.storage.' . $file->type . '.container') . '/' . $file->uid);

				// call the api to retrieve back the data
				$storage->pull($relativePath);

				$states = array();

				// make sure these files donwloaded into local server
				$existFile = JPATH_ROOT . $relativePath . '/' . $file->hash;
				$states[] = JFile::exists($existFile);

				// Determines if the download was successful
				$success = !in_array(false, $states);

				// If all the files were downloaded successfully
				if ($success) {
					$table->storage = $storageType;
					$table->store();

					$total += 1;
				}

				// Add this to the storage logs
				$this->log($table->id, 'files.pull', true);
			}
			
			if ($total > 0) {
				return JText::sprintf('%1s files downloaded to local server.', $total);
			}

			return $defaultMessage;
		}

		// Get the storage library
		$storage = $this->getStorageLibrary('files');

		// Get the number of files to process at a time
		$limit = $this->getUploadLimit('files');

		// Get a list of files to be synchronized over.
		$model = FD::model('Files');

		// Get a list of excluded avatars that previously failed.
		$exclusion = $this->getFailedObjects('files');
		$options = array('storage' => SOCIAL_STORAGE_JOOMLA, 'limit' => 10, 'exclusion' => $exclusion, 'ordering' => 'created', 'sort' => 'asc');

		$files = $model->getItems($options);
		$total = 0;

		foreach ($files as $file) {

			// Get the source file
			$source = $file->getStoragePath() . '/' . $file->hash;

			// Get the destination file
			$dest = $file->getStoragePath(true) . '/' . $file->hash;

			$success = $storage->push($file->name, $source, $dest);

			if ($success) {

				// Once the file is uploaded successfully delete the file physically.
				if ($this->deleteable($storageType)) {
					JFile::delete($source);
				}

				// Do something here.
				$file->storage = $storageType;
				$file->store();

				$total	+= 1;
			}

			// Create a new storage log for this transfer
			$this->log($file->id, 'files', $success);
		}

		if ($total > 0) {
			return JText::sprintf('%1s files uploaded to remote storage', $total);
		}

		return JText::_('Nothing to process for files');
	}

	/**
	 * Synchronizes avatars from the site over to remote storage
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncAvatars()
	{
		$storageType = $this->getStorageType('avatars');

		if ($storageType == 'joomla') {

			$defaultMessage = JText::_('Current avatar storage is set to local and there are no item stored externally to download.');

			// Check if any avatars with the "storage" property to non "joomla"
			$model = ES::model('Avatars');
			$avatars = $model->getAvatarsStoredExternally('amazon');

			// Check if do not have any "storage" property to Amazon 
			if (!$avatars) {
				return $defaultMessage;
			}

			// Load up the storage library
			$storage = ES::storage('amazon');
			$total = 0;

			foreach ($avatars as $avatar) {

				$table = ES::table('Avatar');
				$table->bind($avatar);

				// Reconstruct the path to the source file
				$relativePath = '/' . ES::cleanPath($this->config->get('avatars.storage.container'));
				$relativePath .= '/' . ES::cleanPath($this->config->get('avatars.storage.' . $avatar->type)) . '/' . $avatar->uid;

				// call the api to retrieve back the data
				$storage->pull($relativePath);

				// Get available avatar sizes
				$sizes = ES::user()->getAvatarSizes();
				$states = array();

				foreach ($sizes as $size) {
					$avatarFile = JPATH_ROOT . $relativePath . '/' . $avatar->$size;

					$states[] = JFile::exists($avatarFile);
				}
				
				// Determines if the download was successful
				$success = !in_array(false, $states);

				// If all the files were downloaded successfully
				if ($success) {
					$table->storage = $storageType;
					$table->store();

					$total += 1;
				}

				// Add this to the storage logs
				$this->log($table->id, 'avatars.pull', $success);
			}


			if ($total > 0) {
				return JText::sprintf('%1s avatars downloaded to local server.', $total);
			}

			return $defaultMessage;
		}

		// Get the storage library
		$storage = $this->getStorageLibrary('avatars');

		// Get the number of files to process at a time
		$limit = $this->getUploadLimit('avatars');

		// Get a list of excluded avatars that previously failed.
		$exclusion 	= $this->getFailedObjects('avatars');

		// Get a list of avatars to be synchronized over.
		$model = FD::model('Avatars');
		$options = array('limit' => $limit , 'storage' => SOCIAL_STORAGE_JOOMLA , 'uploaded' => true, 'exclusion' => $exclusion);
		$avatars = $model->getAvatars($options);
		$total = 0;

		if (!$avatars) {
			return JText::_('No avatars to upload to Amazon S3 right now.');
		}


		// Go through each avatars that needs to be uploaded on the remote storage
		foreach ($avatars as $avatar) {

			// Get available avatar sizes
			$sizes = ES::user()->getAvatarSizes();
			
			// By default the state would be false unless all operations are successfull.
			$success = false;
			$states = array();
			$filesToBeDeleted = array();

			foreach ($sizes as $size) {

				// Get the abolute path to the specific avatar size
				$relativePath = $avatar->getPath(constant('SOCIAL_AVATAR_' . strtoupper($size)), false);
				$absolutePath = JPATH_ROOT . '/' . $relativePath;
				
				if (!JFile::exists($absolutePath)) {
					$states[] = false;
				} else {
					// We also need to queue each of the absolute paths so that we can delete it later on
					$filesToBeDeleted[] = $absolutePath;

					// Get the file name to be used
					$fileName = basename($relativePath);

					$states[] = $storage->push($fileName, $absolutePath, $relativePath);
				}
			}

			// If there is no false result from the states, we assume everything got uploaded succesfully.
			if (!in_array(false, $states)) {
				$avatar->storage = $storageType;

				if ($this->deleteable($storageType)) {
					foreach ($filesToBeDeleted as $file) {
						JFile::delete($file);
					}
				}

				$avatar->store();
				$success = true;
			}

			// Add this to the storage logs
			$this->log($avatar->id, 'avatars', $success);

			$total += 1;
		}

		if ($total > 0) {
			return JText::sprintf('%1s avatars uploaded to remote storage', $total);
		}
	}	
}
