<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for edit
 *
 * @since  1.6
 */
class SocialadsViewAdform extends JViewLegacy
{
	/**
	 * Display the view
	 *
	 * @param   array  $tpl  An optional associative array.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	public function display($tpl = null)
	{
		// Init vars
		$this->app       = JFactory::getApplication();
		$this->input     = JFactory::getApplication()->input;
		$this->user      = JFactory::getUser();
		$this->session   = JFactory::getSession();
		$this->sitename  = JFactory::getApplication()->getCfg('sitename');
		$this->sa_params = JComponentHelper::getParams('com_socialads');
		$this->root_url  = JUri::root();

		require_once JPATH_ADMINISTRATOR . '/components/com_socialads/helpers/socialads.php';
		$canDo = SocialadsHelper::getActions();

		// Load common lang. file
		$lang = JFactory::getLanguage();
		$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);

		// Check whether user logged in or not
		if (!$this->user->id)
		{
			$msg = JText::_('COM_SOCIALADS_LOGIN_MSG');

			if ($this->sa_params->get('registration_form', 1))
			{
				$itemid = $this->input->get('Itemid', 0, 'INT');
				$this->session->set('socialadsbackurl', $_SERVER["REQUEST_URI"]);
				$this->app->redirect(JRoute::_('index.php?option=com_socialads&view=registration&Itemid=' . $itemid, false), $msg);
			}
			else
			{
				$uri = $this->input->server->get('REQUEST_URI', '', 'STRING');
				$url = urlencode(base64_encode($uri));
				$this->app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url, false), $msg);
			}

			return false;
		}

		// Load common lang. file
		$lang = JFactory::getLanguage();
		$lang->load('com_socialads_common', JPATH_SITE, $lang->getTag(), true);

		// Load Cb/JS lang if needed
		if ($this->sa_params->get('social_integration') == 'JomSocial')
		{
			if (!SaIntegrationsHelper::loadJomsocialLang())
			{
				JError::raiseNotice(100, JText::_('COM_SOCIALADS_SOCIAL_TARGETING_JS_IS_NOTINSTALL'));
				$this->app->redirect('index.php?option=com_socialads&view=ads');

				return false;
			}
		}
		elseif ($this->sa_params->get('social_integration') == 'Community Builder')
		{
			if (!SaIntegrationsHelper::loadCbLang())
			{
				JError::raiseNotice(100, JText::_('COM_SOCIALADS_SOCIAL_TARGETING_CB_IS_NOTINSTALL'));
				$this->app->redirect('index.php?option=com_socialads&view=ads');

				return false;
			}
		}

		// Check for balance, if wallet mode is set
		if ($this->user->id && $this->sa_params->get('payment_mode') == 'wallet_mode')
		{
			$init_balance = SaWalletHelper::getBalance();

			if ($init_balance != 1.00)
			{
				$itemid       = SaCommonHelper::getSocialadsItemid('payment');
				$not_msg      = JText::_('COM_SOCIALADS_WALLET_MIN_BALANCE');
				$not_msg      = str_replace(
				'{clk_pay_link}',
				'<a href="' . JRoute::_('index.php?option=com_socialads&view=payment&Itemid=' . $itemid) . '">' . JText::_('COM_SOCIALADS_CLKHERE') . '</a>',
				$not_msg
				);
				JError::raiseNotice(100, $not_msg);
			}
		}

		// Get model
		$model = $this->getModel('adform');
		$GeoTargeting = $this->sa_params->get('geo_targeting');

		// Various variables we need
		$this->showTargeting = 1;

		// Decide hide / or show targeting tab
		if ($GeoTargeting == "0" && $this->sa_params->get('social_integration') == "Joomla" && $this->sa_params->get('contextual_targeting') == "0")
		{
			$this->showTargeting = 0;
		}

		$this->userbill = array();

		if (!empty($user->id))
		{
			$this->userbill = $model->getbillDetails($user->id);
		}
		// +manoj

		$this->showBilltab = 1;

		if (!empty($this->userbill))
		{
			$this->showBilltab = 0;
		}

		$this->country = $this->get('Country');
		$this->zone_adtype_disabled = '';
		$this->ad_type              = "text_media";
		$this->url1_edit = '';
		$this->url2_edit = '';
		$this->ad_title_edit = '';
		$this->ad_body_edit = '';
		$this->ad_image = '';
		$this->context_target_data_keywordtargeting = '';
		$this->edit_ad_id = 0;
		$this->camp_id          = '';
		$this->cname            = '';
		$this->ad_payment_type  = '';
		$this->allowWholeAdEdit = 1;
		$this->addMoreCredit    = 0;

		// @TODO - manoj - get rid of this
		if (isset($this->user->groups['8']) || isset($this->user->groups['7'])
			|| isset($this->user->groups['Super Users']) || isset($this->user->groups['Administrator'])
			|| isset($this->user->groups['Super Users'])
			|| isset($this->user->groups['Administrator']))

		// A if ($canDo->get('core.edit'))
		{
			$this->special_access = 1;
		}
		else
		{
			$this->special_access = 0;
		}

		$this->ad_types = SaZonesHelper::getAllowedAdTypes($this->special_access);

		if (empty($this->ad_types))
		{
			$this->app ->enqueueMessage(JText::_("COM_SOCIALADS_AD_NO_MODULE_PUBLISHED"), 'error');

			return;
		}

		// Ad url select list for http or https
		$ad_url = array();
		$ad_url[] = JHtml::_('select.option', 'http', JText::_("COM_SOCIALADS_ADDEST_URLHTTP"));
		$ad_url[] = JHtml::_('select.option', 'https', JText::_("COM_SOCIALADS_ADDEST_URLHTTPS"));
		$this->ad_url = $ad_url;

		// Get social targetting fields
		$fields = SaIntegrationsHelper::getFields();
		$this->fields = $fields;

		// Ad campaign + manoj v3.1 start
		// Get user campaigns
		$this->camp_dd = $model->getUserCampaigns($this->user->id);

		// Ad campaign + manoj v3.1 end

		// Get ad_id for edit
		$this->managead_adid = $ad_id = $this->input->get('ad_id', 0, 'INT');

		if (!$ad_id)
		{
			$ad_id = $this->session->get('ad_id');
		}

		$this->session->set('ad_id', $ad_id);

		$this->ad_id               = $ad_id;

		// Edit ad case
		if ($this->ad_id)
		{
			if (!$canDo->get('core.edit.own'))
			{
				// JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
				$msg = JText::_('JERROR_ALERTNOAUTHOR');
				$this->app->redirect(JRoute::_('index.php?option=com_socialads&view=ads&Itemid=' . $itemid, false), $msg);

				return false;
			}

			$this->allowWholeAdEdit                     = $model->allowWholeAdEdit($ad_id);
			$this->addMoreCredit                        = $model->getMoreCredit($ad_id);
			$this->edit_ad_id                           = $this->ad_id;
			$addata_for_adsumary_edit                   = $model->getData($ad_id);
			$this->addata_for_adsumary_edit             = $addata_for_adsumary_edit[1];
			$this->url1_edit                            = $this->addata_for_adsumary_edit->ad_url1;
			$this->url2_edit                            = $this->addata_for_adsumary_edit->ad_url2;
			$this->ad_title_edit                        = $this->addata_for_adsumary_edit->ad_title;
			$this->ad_body_edit                         = $this->addata_for_adsumary_edit->ad_body;
			$this->ad_image                             = $this->addata_for_adsumary_edit->ad_image;
			$this->zone                                 = $model->getzone($ad_id);
			$this->social_target                        = $addata_for_adsumary_edit[0];
			$this->geo_target                           = $model->getData_geo_target($ad_id);
			$Data_context_target                        = $model->getData_context_target($ad_id);
			$this->context_target_data_keywordtargeting = $Data_context_target;

			// Ad campaign + manoj v3.1 start
			$this->camp_id = $this->addata_for_adsumary_edit->camp_id;

			// @TODO - manoj - needs to review if cname is really needed else get rid of it
			$this->cname   = '';

			if ($campid = $this->input->get('campid', 0, 'INT'))
			{
				$this->cname = $model->getCampaignName($campid);
			}
			elseif ($this->camp_id)
			{
				$this->cname = $model->getCampaignName($campid);
			}

			$this->ad_payment_type = $this->addata_for_adsumary_edit->ad_payment_type;

			// Ad campaign + manoj v3.1 end

			if ($this->addata_for_adsumary_edit->ad_affiliate == '1')
			{
				$this->ad_type = 'affiliate';
			}
			else
			{
				$this->zone->ad_type = str_replace('||', ',', $this->zone->ad_type);
				$this->zone->ad_type = str_replace('|', '', $this->zone->ad_type);
				$ad_type1            = explode(",", $this->zone->ad_type);
				$this->ad_type       = $ad_type1[0];
			}

			$this->pricingData = $model->getpricingData($ad_id);
		}
		else
		{
			if (!$canDo->get('core.create'))
			{
				// JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
				$msg = JText::_('JERROR_ALERTNOAUTHOR');
				$this->app->redirect(JRoute::_('index.php?option=com_socialads&view=ads&Itemid=' . $itemid, false), $msg);

				return false;
			}
		}

		$this->adfieldsTableColumn = SaCommonHelper::getTableColumns('ad_fields');

		// Check if zone field is editable
		$this->managead_adid = $ad_id = $this->input->get('ad_id', 0, 'INT');

		if ($this->managead_adid)
		{
			$this->zone_adtype_disabled = 'disabled="disabled"';
		}

		// Get ad type and zone from URL
		if ($this->input->get('adtype') || $this->input->get('adzone'))
		{
			$ad_type_val   = $this->input->get('adtype');
			$ad_type_val   = str_replace("||", ",", $ad_type_val);
			$ad_type_val   = str_replace("|", "", $ad_type_val);
			$ad_type_arry  = explode(",", $ad_type_val);

			$this->ad_type = $ad_type_arry[0];

			// Don't allow editing zone, if zone id is passed from URL
			if ($this->sa_params->get('editable_adtype_zone'))
			{
				$this->zone_adtype_disabled = 'disabled="disabled"';
			}
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		parent::display($tpl);
	}
}
