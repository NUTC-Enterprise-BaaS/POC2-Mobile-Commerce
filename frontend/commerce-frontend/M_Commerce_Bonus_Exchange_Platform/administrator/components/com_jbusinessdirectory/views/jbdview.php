<?php
/**
 * Main view responsable for creating the extension menu structure and admin template
 *
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/metisMenu.css');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryAdminView extends JViewLegacy{

	var $section_name="";
	var $section_description = "";

	function __construct($config = array()){
		parent::__construct($config);
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->section_name= JText::_("LNG_".strtoupper($this->_name));
		$this->section_description = JText::_("LNG_".strtoupper($this->_name)."_HEADER_DESCR");
	}

	/**
	 * Generate the main display for extension views
	 *
	 * @param unknown_type $tpl
	 */
	public function display($tpl = null)
	{
		$content = $this->loadTemplate($tpl);

		if ($content instanceof Exception)
		{
			return $content;
		}

		$input = JFactory::getApplication()->input;
		if($input->get('hidemainmenu')){
			echo $content;
			return;
		}

		$template = new stdClass();
		$template->content = $content;
		$template->menus = $this->generateMenu();
		$this->checkAccessRights($template->menus);
		$this->setActiveMenus($template->menus, $this->_name);

		//include the template and create the view
		$path = JPATH_ADMINISTRATOR . '/components/com_jbusinessdirectory/theme/template.php';
		$templateFileExists = JFile::exists($path);

		$templateContent = $content;

		if($templateFileExists){
			ob_start();

			// Include the requested template filename in the local scope
			// (this will execute the view logic).
			include $path;

			// Done with the requested template; get the buffer and
			// clear it.
			$templateContent = ob_get_contents();
			ob_end_clean();
		}

		echo $templateContent;
	}

	/**
	 * Check for selected menu and set it active
	 *
	 */
    private function setActiveMenus(&$menus, $view){
        foreach($menus as &$menu){
            if($menu["view"] == $view){
                $menu["active"] = true;
            }
            if(isset($menu["submenu"])){
                foreach($menu["submenu"] as &$submenu){
                    if($submenu["view"] == $view){
                        if(preg_match('/type=/', $submenu["link"])) {
							$type = JRequest::getVar('filter_type');
							if(!isset($type))
                            	$type = JRequest::getVar('filter_attribute_type');
                            switch ($type) {
                                case 2:
                                    $parent = 'offers';
                                    break;
                                case 3:
                                    $parent = 'events';
                                    break;
                                default:
                                    $parent = 'companies';
                            }
                            if($menu["view"] == $parent){
                                $menu["active"] = true;
                                $submenu["active"] = true;
                            }
                        }
                        else {
                            $submenu["active"] = true;
                            $menu["active"] = true;
                        }
                    }
                }
            }
        }
    }

	/**
	 * Check the access rights for the menu items
	 * @param unknown_type $menus
	 */
	private function checkAccessRights(&$menus){
		$actions = JBusinessDirectoryHelper::getActions();
		
		foreach($menus as $i=>$menu){
			if(!$actions->get($menu["access"])){
				unset($menus[$i]);
				continue;
			}
			if(isset($menu["submenu"])){
				foreach($menu["submenu"] as $j=>&$submenu){
					if(!$actions->get($submenu["access"])){
						unset($menu["submenu"][$j]);
						continue;
					}
				}
			}
		}

		return $menus;
	}
	
	/**
	 * Build the menu items with all subments
	 *
	 */
	private function generateMenu(){
		$menus = array();

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_DASHBOARD'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=jbusinessdirectory",
				"view" => "jbusinessdirectory",
				"icon" => "dir-icon-th-large");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SETTINGS'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=applicationsettings",
				"view" => "applicationsettings",
				"icon" => "dir-icon-cog");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_CATEGORIES'),
				"access"=> "directory.access.categories",
				"link" => "index.php?option=com_jbusinessdirectory&view=categories",
				"view" => "categories",
				"icon" => "dir-icon-sitemap");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANIES'),
				"access"=> "directory.access.listings",
				"link" => "#",
				"view" => "companies",
				"icon" => "dir-icon-tasks");

		$submenu = array();
		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANIES'),
				"access"=> "directory.access.listings",
				"link" => "index.php?option=com_jbusinessdirectory&view=companies",
				"view" => "companies");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANY_CATEGORIES'),
				"access"=> "directory.access.companies",
				"link" => "index.php?option=com_jbusinessdirectory&view=categories&filter_type=".CATEGORY_TYPE_BUSINESS,
				"view" => "categories",
				"icon" => "dir-icon-certificate");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ATTRIBUTES'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=attributes&filter_attribute_type=1",
				"view" => "attributes");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANY_TYPES'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=companytypes",
				"view" => "companytypes");
		$submenu[] = $smenuItem;

		$smenuItem = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANY_MESSAGES'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=companymessages",
				"view" => "companymessages");
		$submenu[] = $smenuItem;

		$menuItem["submenu"] = $submenu;
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_OFFERS'),
				"access"=> "directory.access.offers",
				"link" => "#",
				"view" => "offers",
				"icon" => "dir-icon-certificate");

		$submenu = array();
		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_OFFERS'),
				"access"=> "directory.access.offers",
				"link" => "index.php?option=com_jbusinessdirectory&view=offers",
				"view" => "offers",
				"icon" => "dir-icon-certificate");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_OFFER_CATEGORIES'),
				"access"=> "directory.access.offers",
				"link" => "index.php?option=com_jbusinessdirectory&view=categories&filter_type=".CATEGORY_TYPE_OFFER,
				"view" => "categories",
				"icon" => "dir-icon-certificate");
		$submenu[] = $smenuItem;

        $smenuItem  = array(
            "title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ATTRIBUTES'),
            "access"=> "directory.access.directory.management",
            "link" => "index.php?option=com_jbusinessdirectory&view=attributes&filter_attribute_type=2",
            "view" => "attributes");
        $submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_OFFERCOUPONS'),
				"access"=> "directory.access.offercoupons",
				"link" => "index.php?option=com_jbusinessdirectory&view=offercoupons",
				"view" => "offercoupons",
				"icon" => "dir-icon-ticket");
		$submenu[] = $smenuItem;
		$menuItem["submenu"] = $submenu;
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENTS'),
				"access"=> "directory.access.events",
				"link" => "#",
				"view" => "events",
				"icon" => "dir-icon-calendar");

		$submenu = array();
		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENTS'),
				"access"=> "directory.access.events",
				"link" => "index.php?option=com_jbusinessdirectory&view=events",
				"view" => "events",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENT_CATEGORIES'),
				"access"=> "directory.access.events",
				"link" => "index.php?option=com_jbusinessdirectory&view=categories&filter_type=".CATEGORY_TYPE_EVENT,
				"view" => "categories",
				"icon" => "dir-icon-certificate");
		$submenu[] = $smenuItem;

        $smenuItem  = array(
            "title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ATTRIBUTES'),
            "access"=> "directory.access.directory.management",
            "link" => "index.php?option=com_jbusinessdirectory&view=attributes&filter_attribute_type=3",
            "view" => "attributes");
        $submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENT_TYPES'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=eventtypes",
				"view" => "eventtypes",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;
		$menuItem["submenu"] = $submenu;
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_PACKAGES'),
				"access"=> "directory.access.packages",
				"link" => "index.php?option=com_jbusinessdirectory&view=packages",
				"view" => "packages",
				"icon" => "dir-icon-database");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_DISCOUNTS'),
				"access"=> "directory.access.discounts",
				"link" => "index.php?option=com_jbusinessdirectory&view=discounts",
				"view" => "discounts",
				"icon" => "dir-icon-ticket");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ORDERS'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=orders",
				"view" => "orders",
				"icon" => "dir-icon-shopping-cart");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_PAYMENT_PROCESSORS'),
				"access"=> "directory.access.payment.config",
				"link" => "index.php?option=com_jbusinessdirectory&view=paymentprocessors",
				"view" => "paymentprocessors",
				"icon" => "dir-icon-money");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COUNTRIES'),
				"access"=> "directory.access.countries",
				"link" => "index.php?option=com_jbusinessdirectory&view=countries",
				"view" => "countries",
				"icon" => "dir-icon-globe");
		$menus[] = $menuItem;


		if($this->appSettings->limit_cities==1){
			$menuItem  = array(
					"title" => JText::_('LNG_MANAGE_CITIES'),
					"access"=> "directory.access.cities",
					"link" => "index.php?option=com_jbusinessdirectory&view=cities",
					"view" => "cities",
					"icon" => "dir-icon-cog");
			$menus[] = $menuItem;
		}

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_AND_RATING'),
				"access"=> "directory.access.reviews",
				"link" => "#",
				"view" => "ratings",
				"icon" => "dir-icon-comment");

		$submenu = array();
		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_RATING'),
				"access"=> "directory.access.reviews",
				"link" => "index.php?option=com_jbusinessdirectory&view=ratings",
				"view" => "ratings",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW'),
				"access"=> "directory.access.reviews",
				"link" => "index.php?option=com_jbusinessdirectory&view=reviews",
				"view" => "reviews",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_CRITERIAS'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=reviewcriterias",
				"view" => "reviewcriterias",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_RESPONSE'),
				"access"=> "directory.access.reviews",
				"link" => "index.php?option=com_jbusinessdirectory&view=reviewresponses",
				"view" => "reviewresponses",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;

		$smenuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_ABUSE'),
				"access"=> "directory.access.reviews",
				"link" => "index.php?option=com_jbusinessdirectory&view=reviewabuses",
				"view" => "reviewabuses",
				"icon" => "dir-icon-cog");
		$submenu[] = $smenuItem;
		$menuItem["submenu"] = $submenu;
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REPORTS'),
				"access"=> "directory.access.reports",
				"link" => "index.php?option=com_jbusinessdirectory&view=reports",
				"view" => "reports",
				"icon" => "dir-icon-bar-chart");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EMAILS_TEMPLATES'),
				"access"=> "directory.access.emails",
				"link" => "index.php?option=com_jbusinessdirectory&view=emailtemplates",
				"view" => "emailtemplates",
				"icon" => "dir-icon-envelope");
		$menus[] = $menuItem;

		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_jbusinessdirectory/models/conference.php')){
			$menuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_CONFERENCES'),
					"access"=> "directory.access.conferences",
					"link" => "index.php?option=com_jbusinessdirectory&view=conferences",
					"view" => "conferences",
					"icon" => "dir-icon-graduation-cap");
			$menus[] = $menuItem;

			$menuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SPEAKERS'),
					"access"=> "directory.access.conferences",
					"link" => "#",
					"view" => "speakers",
					"icon" => "dir-icon-user");

			$submenu = array();
			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SPEAKERS'),
					"access"=> "directory.access.conferences",
					"link" => "index.php?option=com_jbusinessdirectory&view=speakers",
					"view" => "speakers",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;

			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SPEAKER_TYPES'),
					"access"=> "directory.access.directory.management",
					"link" => "index.php?option=com_jbusinessdirectory&view=speakertypes",
					"view" => "speakertypes",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;
			$menuItem["submenu"] = $submenu;
			$menus[] = $menuItem;

			$menuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SESSIONS'),
					"access"=> "directory.access.conferences",
					"link" => "#",
					"view" => "sessions",
					"icon" => "dir-icon-language");

			$submenu = array();
			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SESSIONS'),
					"access"=> "directory.access.conferences",
					"link" => "index.php?option=com_jbusinessdirectory&view=sessions",
					"view" => "sessions",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;

			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SESSION_TYPES'),
					"access"=> "directory.access.directory.management",
					"link" => "index.php?option=com_jbusinessdirectory&view=sessiontypes",
					"view" => "sessiontypes",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;

			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SESSION_LOCATIONS'),
					"access"=> "directory.access.directory.management",
					"link" => "index.php?option=com_jbusinessdirectory&view=sessionlocations",
					"view" => "sessionlocations",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;

			$smenuItem  = array(
					"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SESSION_LEVELS'),
					"access"=> "directory.access.directory.management",
					"link" => "index.php?option=com_jbusinessdirectory&view=sessionlevels",
					"view" => "sessionlevels",
					"icon" => "dir-icon-cog");
			$submenu[] = $smenuItem;
			$menuItem["submenu"] = $submenu;
			$menus[] = $menuItem;
		}
		$menuItem  = array(
				"title" => JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_UPDATE'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=updates",
				"view" => "updates",
				"icon" => "dir-icon-download");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('會員管理'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=users",
				"view" => "members",
				"icon" => "dir-icon-user");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('點數管理'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=points",
				"view" => "userpoints",
				"icon" => "dir-icon-cog");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('特約發點'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=spepoints",
				"view" => "spepoints",
				"icon" => "dir-icon-cog");
		$menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('優惠收點'),
				"access"=> "directory.access.directory.management",
				"link" => "index.php?option=com_jbusinessdirectory&view=prepoints",
				"view" => "prepoints",
				"icon" => "dir-icon-cog");
		$menus[] = $menuItem;

		// $menuItem  = array(
		// 		"title" => JText::_('業務PV'),
		// 		"access"=> "directory.access.directory.management",
		// 		"link" => "index.php",
		// 		"view" => "pvpoints",
		// 		"icon" => "dir-icon-money");
		// $menus[] = $menuItem;

		// $menuItem  = array(
		// 		"title" => JText::_('特殊活動'),
		// 		"access"=> "directory.access.directory.management",
		// 		"link" => "index.php?option=com_jbusinessdirectory&view=points",
		// 		"view" => "specialevents",
		// 		"icon" => "dir-icon-calendar");
		// $menus[] = $menuItem;

		// $menuItem  = array(
		// 		"title" => JText::_('獎金計算'),
		// 		"access"=> "directory.access.directory.management",
		// 		"link" => "index.php",
		// 		"view" => "bonus",
		// 		"icon" => "dir-icon-money");
		// $menus[] = $menuItem;

		$menuItem  = array(
				"title" => JText::_('推播通知'),
				"access"=> "directory.access.directory.management",
				"link" => "http://106.184.6.69:8080",
				"view" => "pushNotification",
				"icon" => "dir-icon-microphone");
		$menus[] = $menuItem;

		return $menus;
	}

	public function setSectionDetails($name, $description){
		$this->section_name = $name;
		$this->section_description = $description;
	}
}