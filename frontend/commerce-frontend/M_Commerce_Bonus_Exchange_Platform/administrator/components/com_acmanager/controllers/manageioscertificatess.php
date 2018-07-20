<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
jimport('joomla.filesystem.file');
jimport( 'joomla.filesystem.folder' );

use Joomla\Utilities\ArrayHelper;

/**
 * Manageioscertificatess list controller class.
 *
 * @since  1.6
 */
class AcmanagerControllerManageioscertificatess extends JControllerAdmin
{
	
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_acmanager&view=appusers');
	}
	/**
	 * Method to clone existing Manageioscertificatess
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_ACMANAGER_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_ACMANAGER_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_acmanager&view=manageioscertificatess');
	}
	
	/**
	 * Method to save ios certificates
	 *
	 * @return void
	 */
	public function saveCerti()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');
		/*
		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_ACMANAGER_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_ACMANAGER_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}*/

		$this->setRedirect('index.php?option=com_acmanager&view=manageioscertificatess');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'manageioscertificates', $prefix = 'AcmanagerModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	
	/**
        * The main function triggered after on format upload
        *
        * @return object of result and message
        *
        * @since 1.0
        * */
       public function processupload()
       {
               header('Cache-Control: no-cache, must-revalidate');
               header('Content-type: application/json');

               $oluser_id = JFactory::getUser()->id;

               /* If user is not logged in*/
               if (!$oluser_id)
               {
                       $ret['OUTPUT']['flag']        =        0;
                       $ret['OUTPUT']['msg']        =        JText::_('COM_IDEAS_MUST_LOGIN_TO_UPLOAD');
                       $ret['OUTPUT']['filename'] = '<input type="hidden" id="jform_attachment" name="jform[attachment]" value="">';
                       $ret['OUTPUT']['ShowFiles'] = '';

                       echo json_encode($ret);
                       jexit();
               }


               $input = JFactory::getApplication()->input;
               $lesson_id = $input->get('lesson_id', '', 'INT');

               $inputId = "jform_attachment" . $lesson_id;
               $inputName = "jform[attachment]";

               $files = $input->files;

               $post = $input->post;

               $file_to_upload        =        $files->get('FileInput', '', 'ARRAY');

               /* Validate the uploaded file*/
               $ret = $this->validateupload($file_to_upload);

               /* $rs1       = @mkdir(JPATH_SITE . '/media/com_tjlms/lessons/'.$lesson_id .'/submission', 0777); */
               //$rs0 = @mkdir(JPATH_SITE . '/media/com_tjlms/lessons/' . $lesson_id, 0777);
               //$rs  = @mkdir(JPATH_SITE . '/media/com_tjlms/lessons/' . $lesson_id . '/submission', 0777);
               //$rs1 = @mkdir(JPATH_SITE . '/media/com_tjlms/lessons/' . $lesson_id . '/submission/' . $oluser_id, 0777);

               /* $rs1       = @mkdir(JPATH_SITE . '/plugins/tjassignment/submission/uploads/tmp', 0777); */

               // Start file heandling functionality *
               $filename = $file_to_upload['name'];
               $filetype = $file_to_upload['type'];
               $file_attached        = $file_to_upload['tmp_name'];

               // $uploads_dir = JPATH_SITE . '/plugins/tjassignment/submission/uploads/tmp/' . $filename;
               $base_path = JPATH_SITE . '/ios_certificates';
               $uploads_dir = JPATH_SITE . '/ios_certificates/'. $filename;


               // Check file already exists
               //$res = move_uploaded_file($file_attached, $uploads_dir);

               if(!JFolder::exists($base_path))
				{
					$fld_res = JFolder::create($base_path, $mode=0777);

				}
				
				if(JFolder::exists($base_path))
				{
					$ret['flag'] = JFile::upload($file_attached,$base_path.'/'.$filename);
					$ret['msg'] = 'Upload success';
				}
				
				//$ret['flag'] = $cp_res;
				$ret['filename'] = $filename;
				
				echo json_encode($ret);
				jexit();
        }
        
     /**
	 * The function to validate the uploaded format file
	 *
	 * @param   MIXED  $file_to_upload  file object
	 *
	 * @return  object of result and message
	 *
	 * @since 1.0.0
	 * */
	public function validateupload($file_to_upload)
	{
		$return = 1;
		$msg	= '';

		if ($file_to_upload["error"] == UPLOAD_ERR_OK)
		{
		}
		else
		{
			$return = 0;
			$msg = JText::_("COM_TJLMS_ERROR_UPLOADINGFILE", $filename);
		}

		$output['res'] = $return;
		$output['msg'] = $msg;

		return $output;
	}
        

}
