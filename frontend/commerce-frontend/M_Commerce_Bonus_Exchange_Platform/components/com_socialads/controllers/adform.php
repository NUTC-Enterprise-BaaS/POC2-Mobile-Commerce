<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Ad form controller class.
 *
 * @package     SocialAds
 * @subpackage  com_socialads
 * @since       1.0
 */
class SocialadsControllerAdform extends JControllerForm
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  Default parameter
	 *
	 * @see  JController
	 *
	 * @since  1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->view_list = 'ads';
	}

	/**
	 * Function to get promote plugin
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getPromoterPlugins()
	{
		// Get selected user id
		$input = JFactory::getApplication()->input;
		$uid = $input->get('uid', '', 'INT');
		$model = $this->getModel('adform');
		$result = array();
		$result['html'] = $model->getPromoterPlugins($uid);

		echo json_encode($result);
		jexit();
	}

	/**
	 * Function to get zones
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getZones()
	{
		$input = JFactory::getApplication()->input;
		$typ   = $input->get('a_type', '', 'STRING');
		$model = $this->getModel('adform');
		echo $model->getZones($typ);
		jexit();
	}

	/**
	 * Function to get zone data
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getZonesData()
	{
		$input = JFactory::getApplication()->input;
		$typ   = $input->get('zone_id', 0, 'INT');
		$model = $this->getModel('adform');
		echo $model->getZonesData($typ);
		jexit();
	}

	/**
	 * Function to change layout
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function changeLayout()
	{
		$adseen = 2;
		$document = JFactory::getDocument();
		$input = JFactory::getApplication()->input;
		$layout = $input->get('layout');
		$addata = new stdClass;
		$addata->ad_title = $input->get('title', '', 'STRING');

		if ($addata->ad_title == '')
		{
			$addata->ad_title = JText::_("COM_SOCIALADS_AD_SAMPLEAD_TITLE");
		}

		$addata->ad_body = $input->get('body', '', 'STRING');

		if ($addata->ad_body == '')
		{
			$addata->ad_body = JText::_('COM_SOCIALADS_AD_SAMPLEAD_BODY');
		}

		$addata->link = '#';
		$addata->ignore = "";
		$upload_area = 'id="upload_area"';
		$plugin = 'plug_' . $layout;
		$addata->ad_adtype_selected = $input->get('a_type');
		$addata->adzone = $input->get('a_zone');
		$addata->ad_image = '';
		$adHtmlTyped = '';

		// If it's 'text ad' don't set image
		if ($addata->ad_adtype_selected == 'text')
		{
			$adHtmlTyped .= $addata->ad_body;
		}
		else
		{
			$addata->ad_image = $input->get('img', '', 'STRING');
			$addata->ad_image = str_replace(JUri::root(), '', $addata->ad_image);

			if ($addata->ad_image == '')
			{
				$addata->ad_image = 'media/com_sa/images/no_img_default.jpg';
			}
		}

		$adHtmlTyped = SaAdEngineHelper::getInstance()->getAdHtmlByMedia(
		$upload_area, $addata->ad_image, $addata->ad_body, $addata->link,
		$layout, $addata->adzone, $track = 0
		);

		$layout = JPATH_SITE . '/plugins/socialadslayout/' . $plugin . '/' . $plugin . '/layout.php';
		$document->addStyleSheet(JUri::root() . 'plugins/socialadslayout/' . $plugin . '/' . $plugin . '/layout.css');
		$css = JUri::root() . 'plugins/socialadslayout/' . $plugin . '/' . $plugin . '/layout.css';
		$document->addScript(JUri::root(true) . '/media/com_sa/js/render.js');

		if (JFile::exists($layout))
		{
			ob_start();
			include $layout;
			$html = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$html = '<!--div for preview ad-image-->
			<div><a id="preview-title" class="preview-title-lnk" href="#">';

			if ($addata->ad_title != '')
			{
				$html .= '' . $addata->ad_title;
			}
			else
			{
				$html .= '' . JText::_("COM_SOCIALADS_AD_SAMPLEAD_TITLE");
			}

			$html .= '</a>
			</div>
			<!--div for preview ad-image-->
			<div id="upload_area" >';

			if ($addata->ad_image != '')
			{
				$html .= '<img  src="' . $addata->ad_image . '">';
			}
			else
			{
				$html .= '<img  src="' . JUri::root(true) . '/media/com_sa/images/no_img_default.jpg">';
			}

			$html .= '
			</div>
			<!--div for preview ad-bodytext-->
			<div id="preview-bodytext">';

			if ($addata->ad_body != '')
			{
				$html .= '' . $addata->ad_body;
			}
			else
			{
				$html .= '' . JText::_('COM_SOCIALADS_AD_SAMPLEAD_BODY');
			}

			$html .= '</div>';
		}

		$js = '';

		// If it's 'text ad' don't send js
		if ($addata->ad_adtype_selected != 'text')
		{
			// @TODO
			// $js should be sent out only for video ads and flash ads
			$js = '
				flowplayer("div.vid_ad_preview",
				{
					src:"' . JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.18.swf",
					wmode:"opaque"
				},
				{
					canvas: {
						backgroundColor:"#000000",
						width:300,
						height:300
					},
					/*default settings for the play button*/
					play: {
						opacity: 0.0,
						label: null,
						replayLabel: null,
						fadeSpeed: 500,
						rotateSpeed: 50
					},
					plugins:{
						controls: {
							url:"' . JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer.controls-3.2.16.swf",
							height:25,
							timeColor: "#980118",
							all: false,
							play: true,
							scrubber: true,
							volume: true,
							time: false,
							mute: true,
							progressColor: "#FF0000",
							bufferColor: "#669900",
							volumeColor: "#FF0000"
						}
					}
				});
			';
		}

		$z = array(
		"html" => $html,
		"css" => $css,
		"js" => $js
		);
		echo json_encode($z);
		jexit();
	}

	/**
	 * This functon is used for the js promote pulgin which will get the data and pass it to the view
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getPromotePluginPreviewData()
	{
		$input = JFactory::getApplication()->input;

		// $post=$input->post;
		// $input->get
		if ($input->get('caller', '', 'STRING') == 'raw')
		{
			$previewdata[0]->title    = $input->get('title', '', 'STRING');
			$previewdata[0]->bodytext = $input->get('body', '', 'STRING');
			$previewdata[0]->image    = $input->get('image', '', 'STRING');
			$url                      = $input->get('url', '', 'get', 'PATH', JREQUEST_ALLOWRAW);
			$previewdata[0]->url      = urldecode($url);
		}
		else
		{
			$previewdata = $this->fetchPromotePluginPreviewData();
		}

		// $filename  = JPATH_SITE . '/images/socialads/' . basename(JPATH_SITE . $previewdata[0]->image);
		$filename  = COM_SA_CONST_MEDIA_ROOTPATH . '/' . basename(JPATH_SITE . $previewdata[0]->image);
		$mystring  = $previewdata[0]->image;
		$findifurl = 'http';
		$ifurl     = strpos($mystring, $findifurl);

		if ($ifurl === false)
		{
			$source1 = JPATH_SITE . '/' . $previewdata[0]->image;
		}
		else
		{
			$source1 = $previewdata[0]->image;
			$content = file_get_contents($previewdata[0]->image);

			// Store in the filesystem.
			$fp = fopen($filename, 'w');
			fwrite($fp, $content);
			fclose($fp);
		}

		if (!JFile::exists($filename))
		{
			JFile::copy($source1, $filename);
		}

		$previewdata[0]->imagesrc = COM_SA_CONST_MEDIA_ROOTURL . '/' . basename(JPATH_SITE . $previewdata[0]->image);
		$previewdata[0]->image = '<img width="100" src="' . COM_SA_CONST_MEDIA_ROOTURL . '/' . basename(JPATH_SITE . $previewdata[0]->image) . '" />';

		$url = explode("://", $previewdata[0]->url);
		$previewdata[0]->url1 = $url[0];
		$previewdata[0]->url2 = $url[1];

		// Data populate part
		if (!$input->get('caller'))
		{
			// Caller not set
			header('Content-type: application/json');

			// Pass array in json format
			echo json_encode(
					array(
					"url1" => $previewdata[0]->url1,
					"url2" => $previewdata[0]->url2,
					"title" => $previewdata[0]->title,
					"imagesrc" => $previewdata[0]->imagesrc,
					"image" => $previewdata[0]->image,
					"bodytext" => $previewdata[0]->bodytext
					)
			);
			jexit();
		}
		else
		{
			$buildadsession = JFactory::getSession();
			$ad_data = array();
			$ad_data[0]['ad_url1']  = $previewdata[0]->url1;
			$ad_data[1]['ad_url2']  = $previewdata[0]->url2;
			$ad_data[2]['ad_title'] = $previewdata[0]->title;
			$ad_data[3]['ad_body']  = $previewdata[0]->bodytext;
			$buildadsession->set('ad_data', $ad_data);
			$buildadsession->set('ad_image', $previewdata[0]->imagesrc);
			$link = JRoute::_('index.php?option=com_socialads&view=buildad&Itemid=' . $Itemid . '&frm=directad', false);
			$this->setRedirect($link);
		}

		// Data populate part
	}

	/**
	 * Function to fetch promote data via the plguin trigger
	 *
	 * @return  Array
	 *
	 * @since  1.6
	 */
	public function fetchPromotePluginPreviewData()
	{
		// Data fetch part
		$input = JFactory::getApplication()->input;
		$plgnameidstr = $input->get('id', '', 'STRING');
		$plgnameid = explode('|', $plgnameidstr);
		jimport('joomla.plugin.helper');

		// Trigger the Promot Plg Methods to get the preview data
		JPluginHelper::importPlugin('socialadspromote', $plgnameid[0]);
		$dispatcher = JDispatcher::getInstance();
		$previewdata = $dispatcher->trigger('onPromoteData', array($plgnameid[1]));
		$previewdata = $previewdata[0];

		// Data fetch part
		return $previewdata;
	}

	/**
	 * Save an ad details
	 *
	 * @return  Array
	 *
	 * @since  1.6
	 */
	public function saveMedia()
	{
		$model = $this->getModel('adform');

		if ($_REQUEST['filename'] != null)
		{
			$model->mediaUpload();
		}
	}

	/**
	 * Function to save ad data
	 *
	 * @return  Array
	 *
	 * @since  1.6
	 */
	public function autoSave()
	{
		$mainframe = JFactory::getApplication();
		$isAdmin = 0;
		$adminApproval = 0;

		if ($mainframe->isAdmin())
		{
			$isAdmin = 1;
		}

		$tmplData = new stdClass;
		$tmplData->sa_params = $this->sa_params = JComponentHelper::getParams('com_socialads');

		// Throw new Exception("Error message");
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$sa_params = JComponentHelper::getParams('com_socialads');
		$post = $input->post;
		$model = $this->getModel('adform');
		$stepId = $input->get('stepId', '', 'STRING');
		$returndata = array();
		$returndata['stepId'] = $stepId;
		$returndata['payAndReviewHtml'] = '';
		$returndata['adPreviewHtml'] = '';
		$returndata['billingDetail'] = '';
		$Itemid = SaCommonHelper::getSocialadsItemid('managead');
		$returndata['Itemid'] = $Itemid;

		if ($isAdmin == 1)
		{
			$adminApproval = 1;
		}

		// Save step-1 : design ad data
		if ($stepId == 'ad-design')
		{
			$response = $model->saveDesignAd($post, $adminApproval);

			if ($response === false)
			{
				return false;
			}
		}

		// Save step-2 : targeting ad data
		if ($stepId == 'ad-targeting')
		{
			$response = $model->saveTargetingData($post);

			if ($response === false)
			{
				return false;
			}
		}

		// Save ad pricing data
		// Pay per ad mode
		if ($stepId == 'ad-pricing')
		{
			$response = $model->savePricingData($post);

			if (isset($response['camp_id']))
			{
				$returndata['camp_id'] = $response['camp_id'];
			}

			if ($response['status'] === false)
			{
				return false;
			}

			require_once JPATH_SITE . '/components/com_tjfields/helpers/geo.php';
			$tjGeoHelper = TjGeoHelper::getInstance('TjGeoHelper');
			$tmplData->country       = (array) $tjGeoHelper->getCountryList('com_socialads');
			$tmplData->ad_creator_id = $ad_creator_id   = $post->get('ad_creator_id', 0);
			$tmplData->userbill      = $model->getbillDetails($ad_creator_id);

			if ($ad_creator_id)
			{
				// To call compulsory bootstrap 2 layout in the backed
				if ($isAdmin == 1)
				{
					$saLayout = new JLayoutFile('bs2.ad.ad_billing');
				}
				else
				{
					$saLayout = new JLayoutFile('ad.ad_billing');
				}

				$billingDetailHtml = $saLayout->render($tmplData);

				$returndata['billingDetail'] = $billingDetailHtml;
			}
		}

		// If billing tab is hide
		$sa_hide_billTab = $input->get('sa_hide_billTab', 0);

		// If 0 means billing details are not saved

		if ((!empty($sa_hide_billTab) && $stepId == 'ad-pricing') || $stepId == 'ad-billing')
		{
			$user = JFactory::getUser();

			// Set data for JLayouts in tmplData
			$ad_id         = $tmplData->ad_id         = $session->get('ad_id');
			$order_id      = $tmplData->order_id      = $model->getOrderId($ad_id);
			$billdata      = $tmplData->billdata      = $post->get('bill', array(), "ARRAY");
			$ad_creator_id = $tmplData->ad_creator_id = $input->get('ad_creator_id', 0);

			// Save billing detail
			if (!empty($billdata) && $ad_creator_id)
			{
				$model->billingaddr($ad_creator_id, $billdata);
			}

			$tmplData->showBillLink = $sa_hide_billTab;

			// To call compulsory bootstrap 2 layout in the backed
			if ($isAdmin == 1)
			{
				$saLayout = new JLayoutFile('bs2.ad.ad_adsummary');
			}
			else
			{
				$saLayout = new JLayoutFile('ad.ad_adsummary');
			}

			$payAndReviewHtml               = $saLayout->render($tmplData);
			$returndata['payAndReviewHtml'] = $payAndReviewHtml;
		}

		// If campaign mode is selected then get ad preview html
		if ($stepId == 'ad-pricing' && $sa_params->get('payment_mode') == 'wallet_mode')
		{
			$ad_id         = $session->get('ad_id');
			$AdPreviewData = $model->getAdPreviewData($ad_id);
			$ad_id         = $tmplData->ad_id         = $session->get('ad_id');
			$AdPreviewData = $tmplData->AdPreviewData = $model->getAdPreviewData($ad_id);

			// To call compulsory bootstrap 2 layout in the backed
			if ($isAdmin == 1)
			{
				$saLayout = new JLayoutFile('bs2.ad.ad_showadscamp');
			}
			else
			{
				$saLayout = new JLayoutFile('ad.ad_showadscamp');
			}

			$html          = $saLayout->render($tmplData);
			$returndata['adPreviewHtml'] = $html;
		}

		echo json_encode($returndata);
		jexit();
	}

	/**
	 * find the geo locations according the geo db
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function findGeolocations()
	{
		$input = JFactory::getApplication()->input;

		// $post=$input->post;
		// $input->get

		$geodata     = $_POST['geo'];
		$element     = $input->get('element');
		$element_val = $input->get('request_term');
		$model       = $this->getModel('adform');
		$response    = $model->getGeolocations($geodata, $element, $element_val);
		$data = array();

		if ($response)
		{
			foreach ($response as $row)
			{
				$json = array();

				// Id of the location
				// $json['value'] = $row['1'];

				// Name of the location
				$data[] = $row['0'];

				// $data[] = $json;
			}
		}

		echo json_encode($data);
		jexit();
	}

	/**
	 * Function to clean up the records
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function cleanup()
	{
		// Clear ad ID session
		$session = JFactory::getSession();
		$session->clear('ad_id');

		$msg = JText::_('COM_SOCIALADS_UPDATED_BILL_INFO');
		$mainframe = JFactory::getApplication();
		$isAdmin = 0;
		$adminApproval = 0;

		if ($mainframe->isAdmin())
		{
			$isAdmin = 1;
		}

		if ($isAdmin == 1)
		{
			$this->setRedirect(JUri::base() . 'index.php?option=com_socialads&view=forms', $msg);
		}
		else
		{
			$link = JRoute::_('index.php?option=com_socialads&view=ads', false);
			$this->setRedirect($link, $msg);
		}
	}

	/**
	 * Calculate Estimated No of Reach for each ad
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function calculatereach()
	{
		$plgdata = array();
		$aa = array();
		$aa = json_encode($_POST['mapdata']);

		if (isset($_POST['plgdata']))
		{
			$plgdata = json_encode($_POST['plgdata']);
		}

		$plg_target_field = array();
		$target_field = array();
		$plgmapdata_array = array();

		if (empty($aa) and empty($plgdata))
		{
			jexit();
		}

		if (!empty($aa))
		{
			$mapdata_array = json_decode($aa);

			if (empty($mapdata_array))
			{
				jexit();
			}

			$target_field = $this->calculatereach_parseArray($mapdata_array);
		}

		if (!empty($plgdata))
		{
			$plgmapdata_array = json_decode($plgdata);
			$plg_target_field = $this->calculatereach_parseArray($plgmapdata_array);
		}

		$reach = 0;

		// $adRetriever=new adRetriever();
		// $reach = $adRetriever->getEstimatedReach($target_field,$plg_target_field);

		$model = $this->getModel('adform');
		$reach = $model->getEstimatedReach($target_field, $plg_target_field);

		header('Content-type: application/json');
		echo json_encode(array("reach" => $reach));
		jexit();
	}

	/**
	 * Calculate reach parse array
	 *
	 * @param   array  $mapdata_array  Map data array
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function calculatereach_parseArray($mapdata_array)
	{
		$target_field = array();

		foreach ($mapdata_array as $mapdata_obj)
		{
			foreach ($mapdata_obj as $mapdata)
			{
				if ($mapdata != '')
				{
					$mapdata_arr = $this->parseObjectToArray($mapdata_obj);

					foreach ($mapdata_arr as $key => $value)
					{
						$target_key_arr = explode(',', $key);
						$target_key_arr1 = explode('|', $target_key_arr[0]);
						$target_key = $target_key_arr1[0];

						if (array_key_exists($target_key, $target_field))
						{
							$target_field[$target_key] = $target_field[$target_key] . "','" . $value;
						}
						else
						{
							$target_field[$target_key] = $value;
						}
					}
				}
			}
		}

		return $target_field;
	}

	/**
	 * Parse object array
	 *
	 * @param   array  $object  object of array
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function parseObjectToArray($object)
	{
		$array = array();

		if (is_object($object))
		{
			$array = get_object_vars($object);
		}

		return $array;
	}

	/**
	 * Method to get user campaigns
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function getUserCampaigns()
	{
		$input     = JFactory::getApplication()->input;
		$userId    = $input->get('userid', 0, 'INT');
		$model     = $this->getModel('adform');
		$campaigns = $model->getUserCampaigns($userId);

		header('Content-type: application/json');
		echo json_encode($campaigns);
		jexit();
	}

	/**
	 * Method to save ad
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function draftAd()
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			$model = $this->getModel('form');

			$redirectUrl = 'index.php?option=com_socialads&view=forms';
		}
		else
		{
			$model = $this->getModel('adform');

			$Itemid      = SaCommonHelper::getSocialadsItemid('ads');
			$redirectUrl = JRoute::_('index.php?option=com_socialads&view=ads&Itemid=' . $Itemid, false);
		}

		$model->draftAd();

		$msg = JText::_('COM_SOCIALADS_AD_SAVED_DRAFT');
		$app->redirect($redirectUrl, $msg);
	}

	/**
	 * Method to activate ad
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function activateAd()
	{
		$app = JFactory::getApplication();
		$sa_params = JComponentHelper::getParams('com_socialads');
		$user = JFactory::getUser();
		$adminApproval = 0;

		require_once JPATH_ADMINISTRATOR . '/components/com_socialads/helpers/socialads.php';
		$canDo = SocialadsHelper::getActions();

		/**
		 *  if (isset($user->groups['8']) || isset($user->groups['7']) || isset($user->groups['Super Users']) ||
		 * isset($user->groups['Administrator']) || $user->usertype == "Super Users" || isset($user->groups['Super Users']) ||
		 * isset($user->groups['Administrator']) || $user->usertype == "Super Administrator" || $user->usertype == "Administrator" )
		 */
		if ($canDo->get('core.edit'))
		{
			$adminApproval = 1;
		}

		if ($sa_params->get('approval_status') && $adminApproval == 0)
		{
			$msg = JText::_('COM_SOCIALADS_AD_CREATED') . ' ' . JText::_('COM_SOCIALADS_ADMIN_APPROVAL_NOTICE');
		}
		else
		{
			$msg = JText::_('COM_SOCIALADS_AD_CREATED');
		}

		if ($app->isAdmin())
		{
			$model = $this->getModel('form');

			$redirectUrl = 'index.php?option=com_socialads&view=forms';
		}
		else
		{
			$model = $this->getModel('adform');

			// $socialadshelper = new socialadshelper();
			// $Itemid = $socialadshelper->getSocialadsItemid('managead');
			$Itemid = SaCommonHelper::getSocialadsItemid('ads');

			// $this->setRedirect( 'index.php?option=com_socialads&view=managead&Itemid='.$Itemid, $msg);

			$redirectUrl = JRoute::_('index.php?option=com_socialads&view=ads&Itemid=' . $Itemid, false);
		}

		$model->activateAd();
		$app->redirect($redirectUrl, $msg);
	}
}
