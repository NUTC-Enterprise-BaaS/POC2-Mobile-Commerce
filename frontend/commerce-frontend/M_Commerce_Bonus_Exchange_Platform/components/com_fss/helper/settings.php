<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

define("FSS_IT_KB",1);
define("FSS_IT_FAQ",2);
define("FSS_IT_TEST",3);
define("FSS_IT_NEWTICKET",4);
define("FSS_IT_VIEWTICKETS",5);
define("FSS_IT_ANNOUNCE",6);
define("FSS_IT_LINK",7);
define("FSS_IT_GLOSSARY",8);
define("FSS_IT_ADMIN",9);
define("FSS_IT_GROUPS",10);
define("FSS_IT_MAINMENU",11);
define("FSS_IT_MENUITEM",99);


jimport( 'joomla.version' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

class FSS_Settings 
{
	static $fss_view_settings;
	
	static function _GetSettings()
	{
		global $fss_settings;
		
		if (empty($fss_settings))
		{
			FSS_Settings::_GetDefaults();
			
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_settings';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$fss_settings[$data['setting']] = $data['value'];
				}
			}

			$query = 'SELECT * FROM #__fss_settings_big';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$fss_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	static function _Get_View_Settings()
	{
		if (empty(FSS_Settings::$fss_view_settings))
		{
			FSS_Settings::_View_Defaults();
			
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__fss_settings_view';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					FSS_Settings::$fss_view_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	static function _GetDefaults()
	{
		global $fss_settings;
		
		if (empty($fss_settings))
		{
			$fss_settings = array();
			
			$fss_settings['version'] = 0;
			$fss_settings['fsj_username'] = '';
			$fss_settings['fsj_apikey'] = '';
			
			$fss_settings['jquery_include'] = "auto";
			
			$fss_settings['captcha_type'] = 'none';

			$fss_settings['recaptcha_public'] = '';
			$fss_settings['recaptcha_private'] = '';
			$fss_settings['comments_moderate'] = 'none';
			$fss_settings['comments_hide_add'] = 1;
			$fss_settings['email_on_comment'] = '';
			$fss_settings['comments_who_can_add'] = 'anyone';
			
			$fss_settings['test_use_email'] = 1;
			$fss_settings['test_use_website'] = 1;
			$fss_settings['commnents_use_email'] = 1;
			$fss_settings['commnents_use_website'] = 1;
			
			$fss_settings['hide_powered'] = 0;
			$fss_settings['announce_use_content_plugins'] = 0;
			$fss_settings['announce_use_content_plugins_list'] = 0;
			$fss_settings['announce_comments_allow'] = 1;
			$fss_settings['announce_comments_per_page'] = 0;
			$fss_settings['announce_per_page'] = 10;
			
			$fss_settings['kb_rate'] = 1;
			$fss_settings['kb_comments'] = 1;
			$fss_settings['kb_view_top'] = 0;
			
			$fss_settings['kb_show_views'] = 1;
			$fss_settings['kb_show_recent'] = 1;
			$fss_settings['kb_show_recent_stats'] = 1;
			$fss_settings['kb_show_viewed'] = 1;
			$fss_settings['kb_show_viewed_stats'] = 1;
			$fss_settings['kb_show_rated'] = 1;
			$fss_settings['kb_show_rated_stats'] = 1;
			$fss_settings['kb_show_dates'] = 1;
			$fss_settings['kb_use_content_plugins'] = 0;
			$fss_settings['kb_show_art_related'] = 1;
			$fss_settings['kb_show_art_products'] = 1;
			$fss_settings['kb_show_art_attach'] = 1;
			$fss_settings['kb_show_art_attach_filenames'] = 1;
			
			$fss_settings['kb_contents_auto'] = 0;
			$fss_settings['kb_smaller_subcat_images'] = 0;
			$fss_settings['kb_comments_per_page'] = 0;
			$fss_settings['kb_prod_per_page'] = 5;
			$fss_settings['kb_art_per_page'] = 10;
			$fss_settings['kb_print'] = 1;
			$fss_settings['kb_auto_open_single_cat'] = 0;
			$fss_settings['kb_popup_width'] = 820;
			
			$fss_settings['test_moderate'] = 'none';
			$fss_settings['test_email_on_submit'] = '';
			$fss_settings['test_allow_no_product'] = 1;
			$fss_settings['test_who_can_add'] = 'anyone';
			$fss_settings['test_hide_empty_prod'] = 1;
			$fss_settings['test_comments_per_page'] = 0;

			$fss_settings['skin_style'] = 0;
			$fss_settings['support_entire_row'] = 0;
			$fss_settings['support_autoassign'] = 0;
			$fss_settings['support_handler_fallback'] = '';
			
			//$fss_settings['support_assign_open'] = 0;
			$fss_settings['support_assign_reply'] = 0;
			$fss_settings['support_user_attach'] = 1;
			$fss_settings['support_lock_time'] = 30;
			$fss_settings['support_show_msg_counts'] = 1;
			$fss_settings['support_captcha_type'] = 'none';
			$fss_settings['support_access_level'] = 1;
			$fss_settings['support_open_access_level'] = 1;
			$fss_settings['support_reference'] = "{4L}-{4L}-{4L}";
			$fss_settings['support_list_template'] = "classic";
			$fss_settings['support_user_template'] = "classic";
			$fss_settings['support_custom_register'] = "";
			$fss_settings['support_custom_lost_username'] = "";
			$fss_settings['support_custom_lost_password'] = "";
			$fss_settings['support_no_logon'] = 0;
			$fss_settings['support_no_register'] = 0;
			$fss_settings['support_info_cols'] = 1;
			$fss_settings['support_info_cols_user'] = 1;
			$fss_settings['support_choose_handler'] = 'none';
			$fss_settings['support_assign_for_user'] = 0;
			$fss_settings['support_dont_check_dupe'] = 1;
			$fss_settings['support_admin_refresh'] = 0;
			$fss_settings['support_only_admin_open'] = 0;
			$fss_settings['allow_raw_html_messages'] = 0;
			$fss_settings['support_attach_max_size'] = '';
			$fss_settings['support_attach_max_size_admins'] = '';
			$fss_settings['support_attach_types'] = '';
			$fss_settings['support_attach_types_admins'] = '';
			$fss_settings['support_attach_use_old_system'] = 0;
			$fss_settings['support_update_satatus_on_draft'] = 0;

			$fss_settings['support_user_reply_width'] = 56;
			$fss_settings['support_user_reply_height'] = 10;
			$fss_settings['support_admin_reply_width'] = 56;
			$fss_settings['support_admin_reply_height'] = 10;
			$fss_settings['ticket_label_width'] = 200;
			$fss_settings['support_subject_size'] = 35;			
			$fss_settings['support_subject_message_hide'] = '';	
			$fss_settings['support_subject_format'] = '';
			$fss_settings['support_subject_format_blank'] = 1;		
			$fss_settings['support_filename'] = 0;			
			$fss_settings['support_unreg_password_highlight'] = 0;	
			
			$fss_settings['support_open_accord'] = 1;		
			$fss_settings['support_open_cat_prefix'] = '<i class="icon-circle" style="position: relative;top: -1px;margin-right: 2px;margin-left: 3px;font-size: 50%;"></i>';		
			
			$fss_settings['support_subject_at_top'] = 0;
			$fss_settings['support_sel_prod_dept'] = 1;
			
			$fss_settings['support_tabs_allopen'] = 0;	
			$fss_settings['support_tabs_allclosed'] = 0;
			$fss_settings['support_tabs_all'] = 0;			
			$fss_settings['ticket_prod_per_page'] = 20;
			$fss_settings['ticket_per_page'] = 10;
			$fss_settings['support_hide_super_users'] = 1;	
			$fss_settings['support_no_admin_for_user_open'] = 0;
			$fss_settings['support_profile_itemid'] = '';
			
			$fss_settings['support_restrict_prod'] = 0;
			$fss_settings['support_restrict_prod_view'] = 0;
			
			$fss_settings['display_head'] = '';
			$fss_settings['display_foot'] = '';
			$fss_settings['use_joomla_page_title_setting'] = 0;
			$fss_settings['title_prefix'] = 1;
			$fss_settings['browser_prefix'] = -1;
			
			$fss_settings['page_headingout'] = 1;
			
			$fss_settings['support_email_link_unreg'] = '';
			$fss_settings['support_email_link_reg'] = '';
			$fss_settings['support_email_link_admin'] = '';
			$fss_settings['support_email_link_pending'] = '';
			$fss_settings['support_email_no_domain'] = '';
			$fss_settings['support_email_include_autologin'] = 0;
			$fss_settings['support_email_include_autologin_handler'] = 0;
			
			// these 3 are not needed anymore, but are still used in some legacy code for some reason
			// They need all references to them removing
			$fss_settings['css_hl'] = '#f0f0f0';
			$fss_settings['css_tb'] = '#ffffff';
			$fss_settings['css_bo'] = '#e0e0e0';

			$fss_settings['display_h1'] = '<h1>$1</h1>';
			$fss_settings['display_h2'] = '<h2>$1</h2>';
			$fss_settings['display_h3'] = '<h3>$1</h3>';
			
			$fss_settings['display_style'] = '';
			$fss_settings['display_popup_style'] = '';
			$fss_settings['display_module_style'] = '';

			$fss_settings['support_email_on_create'] = 0;
			$fss_settings['support_email_handler_on_create'] = 0;
			$fss_settings['support_email_on_reply'] = 0;
			$fss_settings['support_email_handler_on_reply'] = 0;
			$fss_settings['support_email_handler_on_forward'] = 0;
			$fss_settings['support_email_handler_on_private'] = 0;
			$fss_settings['support_email_handler_on_pending'] = 0;
			$fss_settings['support_email_on_close'] = 0;
			$fss_settings['support_email_on_close_no_dropdown'] = 0;
			
			$fss_settings['support_email_all_admins'] = 0;
			$fss_settings['support_email_all_admins_only_unassigned'] = 0;
			$fss_settings['support_email_all_admins_ignore_auto'] = 0;
			$fss_settings['support_email_all_admins_can_view'] = 0;
			
			$fss_settings['support_user_can_close'] = 1;
			$fss_settings['support_user_can_reopen'] = 1;
			$fss_settings['support_user_can_change_status'] = 0;
			$fss_settings['support_user_show_close_reply'] = 0;
			$fss_settings['support_advanced_department'] = 1;
			$fss_settings['support_advanced_search'] = 1;
			$fss_settings['support_product_manual_category_order'] = 0;
			$fss_settings['support_allow_unreg'] = 0;
			$fss_settings['support_unreg_type'] = 0;
			$fss_settings['support_unreg_domain_restrict'] = 0;
			$fss_settings['support_unreg_domain_list'] = '';
			$fss_settings['support_delete'] = 1;
			$fss_settings['support_advanced_default'] = 0;
			$fss_settings['support_sceditor'] = 1;
			$fss_settings['support_altcat'] = 0;
			$fss_settings['support_insertpopup'] = 0;
			$fss_settings['support_simple_userlist_tabs'] = 0;
			$fss_settings['support_simple_userlist_search'] = 0;
			$fss_settings['support_user_show_reply_always'] = 0;
			$fss_settings['support_user_reply_under'] = 0;
			$fss_settings['support_user_reverse_messages'] = 0;
			
			$fss_settings['ticket_link_target'] = 1;
			
			$fss_settings['support_cronlog_keep'] = 5;
			$fss_settings['support_emaillog_keep'] = 365;

			$fss_settings['support_hide_priority'] = 0;
			$fss_settings['support_default_priority'] = '';
			$fss_settings['support_hide_handler'] = 0;
			$fss_settings['support_hide_category'] = 0;
			$fss_settings['support_hide_users_tickets'] = 0;
			$fss_settings['support_hide_tags'] = 0;
			$fss_settings['support_email_unassigned'] = '';
			$fss_settings['support_email_admincc'] = '';
			$fss_settings['messages_at_top'] = 0;
			$fss_settings['time_tracking'] = '';
			$fss_settings['time_tracking_require_note'] = 1;
			$fss_settings['time_tracking_type'] = '';
			$fss_settings['absolute_last_open'] = 0;
			
			$fss_settings['reports_separator'] = ',';


			$fss_settings['support_email_from_name'] = '';
			$fss_settings['support_email_from_address'] = '';
			$fss_settings['support_email_site_name'] = '';
			
			$fss_settings['support_email_file_user'] = 1;
			$fss_settings['support_email_file_handler'] = 0;
			$fss_settings['support_email_bcc_handler'] = 0;
			$fss_settings['support_email_send_empty_handler'] = 0;

			$fss_settings['support_ea_check'] = 0;
			$fss_settings['support_ea_all'] = 0;
			$fss_settings['support_ea_reply'] = 0;
			$fss_settings['support_ea_type'] = 0;
			$fss_settings['support_ea_host'] = '';
			$fss_settings['support_ea_port'] = '';
			$fss_settings['support_ea_username'] = '';
			$fss_settings['support_ea_password'] = '';
			$fss_settings['support_ea_mailbox'] = '';

			$fss_settings['support_basic_name'] = '';
			$fss_settings['support_basic_username'] = '';
			$fss_settings['support_basic_email'] = '';
			$fss_settings['support_basic_messages'] = '';

			$fss_settings['glossary_faqs'] = 1;
			$fss_settings['glossary_kb'] = 1;
			$fss_settings['glossary_announce'] = 1;
			$fss_settings['glossary_support'] = 1;
			$fss_settings['glossary_link'] = 1;
			$fss_settings['glossary_title'] = 0;
			$fss_settings['glossary_use_content_plugins'] = 0;
			$fss_settings['glossary_ignore'] = '';
			$fss_settings['glossary_exclude'] = "a,script,pre,h1,h2,h3,h4,h5,h6";
			$fss_settings['glossary_show_read_more'] = 1;
			$fss_settings['glossary_all_letters'] = 0;
			
			$fss_settings['glossary_read_more_text'] = "Click for more info";
			$fss_settings['glossary_word_limit'] = 0;
			$fss_settings['glossary_case_sensitive'] = 0;
			
			$fss_settings['faq_popup_width'] = 650;
			$fss_settings['faq_popup_height'] = 375;
			$fss_settings['faq_use_content_plugins'] = 0;
			$fss_settings['faq_use_content_plugins_list'] = 0;
			$fss_settings['faq_per_page'] = 10;
			$fss_settings['faq_cat_prefix'] = 1;
			$fss_settings['faq_multi_col_responsive'] = 0;
			
			// 1.9 comments stuff
			$fss_settings['comments_announce_use_custom'] = 0;
			$fss_settings['comments_kb_use_custom'] = 0;
			$fss_settings['comments_test_use_custom'] = 0;	
			$fss_settings['comments_general_use_custom'] = 0;		
			$fss_settings['comments_testmod_use_custom'] = 0;	
			
			$fss_settings['announce_use_custom'] = 0;		
			$fss_settings['announcemod_use_custom'] = 0;		
			$fss_settings['announcesingle_use_custom'] = 0;		
			
			// date format stuff
			$fss_settings['date_dt_short'] = '';
			$fss_settings['date_dt_long'] = '';
			$fss_settings['date_d_short'] = '';
			$fss_settings['date_d_long'] = '';
			$fss_settings['timezone_offset'] = 0;
			
			$fss_settings['mainmenu_moderate'] = 1;
			$fss_settings['mainmenu_support'] = 1;
			
			$fss_settings['prodimg_size'] = 1;
			$fss_settings['prodimg_width'] = 64;
			$fss_settings['prodimg_height'] = 64;
			
			$fss_settings['prodimg_sm_size'] = 1;
			$fss_settings['prodimg_sm_width'] = 24;
			$fss_settings['prodimg_sm_height'] = 24;
			
			$fss_settings['use_sef_compat'] = 1;
			$fss_settings['css_indirect'] = 0;
			$fss_settings['hide_warnings'] = 0;
			$fss_settings['attach_location'] = 'components'.DS.'com_fss'.DS.'files';
			$fss_settings['attach_storage_filename'] = 0;
			$fss_settings['debug_reports'] = 0;
			$fss_settings['search_extra_like'] = 0;
			
			$fss_settings['popup_js'] = "";
			$fss_settings['popup_css'] = "";
			
			$fss_settings['bootstrap_template'] = "";
			$fss_settings['bootstrap_css'] = 'fssonly';
			$fss_settings['bootstrap_js'] = 'yes';
			$fss_settings['bootstrap_textcolor'] = 0;
			$fss_settings['bootstrap_icomoon'] = 0;
			$fss_settings['bootstrap_modal'] = 0;
			$fss_settings['bootstrap_border'] = '#ccc';
			$fss_settings['artisteer_fixes'] = 0;
			$fss_settings['bootstrap_variables'] = '';
			$fss_settings['bootstrap_v3'] = 0;
			$fss_settings['bootstrap_pribtn'] = 'btn-primary';
			
			$fss_settings['sceditor_theme'] = 'default';
			$fss_settings['sceditor_content'] = 'default';
			$fss_settings['sceditor_emoticons'] = 0;
			$fss_settings['sceditor_buttonhide'] = '';
			$fss_settings['sceditor_paste_user'] = '';
			$fss_settings['sceditor_paste_admin'] = '';
			
			// user simple view optiosn
			$fss_settings['user_hide_all_details'] = 0;
			$fss_settings['user_hide_title'] = 0;
			$fss_settings['user_hide_id'] = 0;
			$fss_settings['user_hide_user'] = 0;
			$fss_settings['user_hide_cc'] = 0;
			$fss_settings['user_hide_product'] = 0;
			$fss_settings['user_hide_department'] = 0;
			$fss_settings['user_hide_category'] = 0;
			$fss_settings['user_hide_updated'] = 0;
			$fss_settings['user_hide_handler'] = 0;
			$fss_settings['user_hide_status'] = 0;
			$fss_settings['user_hide_priority'] = 0;
			$fss_settings['user_hide_custom'] = 0;
			$fss_settings['user_hide_print'] = 0;
			$fss_settings['user_hide_key'] = 0;
			

			$fss_settings['email_send_multiple'] = 'multi';			
			$fss_settings['email_send_override'] = 0;
			$fss_settings['email_send_mailer'] = 'mail';
			$fss_settings['email_send_from_email'] = '';
			$fss_settings['email_send_from_name'] = '';
			$fss_settings['email_send_smtp_auth'] = 0;
			$fss_settings['email_send_smtp_security'] = 'none';
			$fss_settings['email_send_smtp_port'] = 0;
			$fss_settings['email_send_smtp_username'] = '';
			$fss_settings['email_send_smtp_password'] = '';
			$fss_settings['email_send_smtp_host'] = 'localhost';
			$fss_settings['email_send_sendmail_path'] = '/usr/sbin/sendmail';
			
			
			$fss_settings['sticky_menus_type'] = '';
			$fss_settings['sticky_menus'] = '';

			$fss_settings['allow_edit_no_audit'] = false;
			$fss_settings['forward_product_handler'] = 'auto';
			$fss_settings['forward_handler_handler'] = 'unchanged';
			
			$fss_settings['suport_dont_cc_handler'] = 0;
			
			$fss_settings['ratings_per_message'] = 0;
			$fss_settings['ratings_per_message_change'] = 0;
			$fss_settings['ratings_per_message_admin_overview'] = 0;
			$fss_settings['ratings_ticket'] = 0;
			$fss_settings['ratings_ticket_change'] = 0;
			
			$fss_settings['open_search_live'] = 0;
			$fss_settings['open_search_enabled'] = 0;
		}	
	}

	// return a list of settings that are used on the templates section
	static function GetTemplateList()
	{
		$template = array();
		//$template[] = "display_style";
		//$template[] = "display_popup_style";
		//$template[] = "display_h1";
		//$template[] = "display_h2";
		//$template[] = "display_h3";
		//$template[] = "display_head";
		//$template[] = "display_foot";
		//$template[] = "display_popup";
		$template[] = "support_list_template";
		$template[] = "support_user_template";
		
		$template[] = "comments_announce_use_custom";
		$template[] = "comments_test_use_custom";
		$template[] = "comments_kb_use_custom";
		$template[] = "comments_general_use_custom";
		$template[] = "comments_testmod_use_custom";
		$template[] = "announce_use_custom";
		$template[] = "announcemod_use_custom";
		$template[] = "announcesingle_use_custom";
		
		$res = array();
		foreach($template as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	static function StoreInTemplateTable()
	{
		$intpl = array();
		$intpl[] = "comments_general";	
		$intpl[] = "comments_announce";	
		$intpl[] = "comments_kb";	
		$intpl[] = "comments_test";	
		$intpl[] = "comments_testmod";	
		$intpl[] = "announce";	
		$intpl[] = "announcemod";	
		$intpl[] = "announcesingle";	
		
		$res = array();
		foreach($intpl as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
		
	static function GetLargeList()
	{
		$large = array();
		$large[] = "display_style";
		$large[] = "display_popup_style";
		$large[] = "display_module_style";
		$large[] = "display_h1";
		$large[] = "display_h2";
		$large[] = "display_h3";
		$large[] = "display_head";
		$large[] = "display_foot";
		$large[] = "popup_js";
		$large[] = "popup_css";
		$large[] = "bootstrap_variables";
		
		$res = array();
		foreach($large as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	static function get($setting)
	{
		global $fss_settings;
		FSS_Settings::_GetSettings();
		return $fss_settings[$setting];	
	}
	
	static function set($setting, $value)
	{
		global $fss_settings;
		FSS_Settings::_GetSettings();
		$fss_settings[$setting] = $value;	
	}
	
	static function reload()
	{
		global $fss_settings;
		$fss_settings = null;
		FSS_Settings::_GetSettings();
	}
	
	static function &GetAllSettings()
	{
		global $fss_settings;
		FSS_Settings::_GetSettings();
		return $fss_settings;	
	}
	
	static function &GetAllViewSettings()
	{
		FSS_Settings::_Get_View_Settings();
		return FSS_Settings::$fss_view_settings;	
	}
	
	static function _View_Defaults()
	{
		// FAQS
		
		// When Showing list of Categories
		FSS_Settings::$fss_view_settings['faqs_hide_allfaqs'] = 0;
		FSS_Settings::$fss_view_settings['faqs_hide_tags'] = 0;
		FSS_Settings::$fss_view_settings['faqs_hide_search'] = 0;
		FSS_Settings::$fss_view_settings['faqs_show_featured'] = 0;
		FSS_Settings::$fss_view_settings['faqs_num_cat_colums'] = 1;
		FSS_Settings::$fss_view_settings['faqs_view_mode_cat'] = 'accordian';
		FSS_Settings::$fss_view_settings['faqs_view_mode_incat'] = 'accordian';
		
		// When Showing list of FAQs
		FSS_Settings::$fss_view_settings['faqs_view_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['faqs_enable_pages'] = 1;
		
		// Glossary
		FSS_Settings::$fss_view_settings['glossary_use_letter_bar'] = 0;
		FSS_Settings::$fss_view_settings['glossary_show_search'] = 0;
		FSS_Settings::$fss_view_settings['glossary_long_desc'] = 0;
		
		// Testimonials
		FSS_Settings::$fss_view_settings['test_test_show_prod_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['test_test_pages'] = 1;
		FSS_Settings::$fss_view_settings['test_test_always_prod_select'] = 0;
		
		
		// KB
		
		// Main Page
		FSS_Settings::$fss_view_settings['kb_main_show_prod'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_show_cat'] = 0;
		FSS_Settings::$fss_view_settings['kb_main_show_sidebyside'] = 0;
		FSS_Settings::$fss_view_settings['kb_main_show_search'] = 0;
		
		// Main Page - Products List Settings		
		FSS_Settings::$fss_view_settings['kb_main_prod_colums'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_prod_search'] = 1;
		FSS_Settings::$fss_view_settings['kb_main_prod_pages'] = 0;
		
		// Main Page - Category List Settings
		FSS_Settings::$fss_view_settings['kb_main_cat_mode'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_main_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_main_cat_colums'] = 1;
		
		// When Product Selected
		FSS_Settings::$fss_view_settings['kb_prod_cat_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['kb_prod_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_prod_cat_colums'] = 1;
		FSS_Settings::$fss_view_settings['kb_prod_search'] = 1;
		
		// When Product and Category Selected
		FSS_Settings::$fss_view_settings['kb_cat_cat_mode'] = 'accordian';
		FSS_Settings::$fss_view_settings['kb_cat_cat_arts'] = 'normal';
		FSS_Settings::$fss_view_settings['kb_cat_art_pages'] = 1;
		FSS_Settings::$fss_view_settings['kb_cat_search'] = 1;		
		FSS_Settings::$fss_view_settings['kb_cat_desc'] = 1;		
	}
	
	static function GetViewSettingsObj($view)
	{
		// return a view setting object that can be used in place of the getPageParameters object
		// needs info about what view we are in, and access to the view settings
		FSS_Settings::_Get_View_Settings();
			
		return new FSS_View_Settings($view, FSS_Settings::$fss_view_settings);
	}
}

class FSS_View_Settings
{
	var $view;
	var $settings;
	var $mainframe;
	
	function __construct($view, $settings)
	{
		$this->view = $view;
		$this->settings = $settings;
		
		$this->mainframe = JFactory::getApplication();
		$this->params = $this->mainframe->getPageParameters('com_fss');
		
		//print_p($this->settings);
		//print_p($this->params);
	}
	
	function get($var, $default = '')
	{
		$key = $this->view . "_" . $var;
		
		//echo "Get : $key (Def: $default) = ";

		$value = $this->params->get($var,"XXXXXXXX");
		if ($value != "XXXXXXXX")
		{
			if (!array_key_exists($key, $this->settings))
			{
				//echo $value . " (missing)<br>";
				return $value;
			}
		
			if ($value != -1)
			{
				//echo $value . " (set)<br>";
				return $value;
			}
		}
		
		//echo $this->settings[$key] . " (global)<br>";
		return $this->settings[$key];
	}
}

function FSS_GetAllMenus()
{
	static $getmenus;
	
	if (empty($getmenus))
	{
		$where = array();
		$where[] = 'menutype != "main"';
		$where[] = 'type = "component"';
		$where[] = 'link LIKE "%option=com_fss%"';
		$where[] = 'published = 1';
		
		$query = 'SELECT title, id, link FROM #__menu';
		$query .= ' WHERE ' . implode(" AND ", $where);
		
		$db    = JFactory::getDBO();
		$db->setQuery($query);
		$getmenus = $db->loadObjectList();
	}
	//print_p($getmenus);
	
	return $getmenus;
}

function FSS_GetMenus($menutype)
{
	$getmenus = FSS_GetAllMenus();
	
	//echo "<br>Menu Type : $menutype<br>-<br>";
	$have = array();
	$not = array();
	
	switch ($menutype)
	{
	case FSS_IT_KB:
		$have['view'] = "kb";
		$not['layout'] = "";
		break;						
	case FSS_IT_FAQ:
		$have['view'] = "faq";
		$not['layout'] = "";
		break;						
	case FSS_IT_TEST:
		$have['view'] = "test";
		$not['layout'] = "";
		break;						
	case FSS_IT_NEWTICKET:
		$have['view'] = "ticket";
		$have['layout'] = "open";
		break;
	case FSS_IT_VIEWTICKETS:
		$have['view'] = "ticket";
		$not['layout'] = "";
		break;						
	case FSS_IT_ANNOUNCE:
		$have['view'] = "announce";
		$not['layout'] = "";
		break;						
	case FSS_IT_GLOSSARY:
		$have['view'] = "glossary";
		$not['layout'] = "";
		break;						
	case FSS_IT_ADMIN:
		$have['view'] = "admin";
		$not['layout'] = "";
		break;						
	default:
		return array();							
	}
	
	$results = array();
	
	if (count($getmenus) > 0)
	{
		foreach ($getmenus as $object)
		{ 
			$linkok = 1;
		
			$link = strtolower(substr($object->link,strpos($object->link,"?")+1));
			//echo $link."<br>";
			$parts = explode("&",$link);
		
			$inlink = array();
		
			foreach($parts as $part)
			{
				list($key,$value) = explode("=",$part);
				$inlink[$key] = $value;
			
				if (array_key_exists($key,$not))
				{
					//echo "Has ".$key."<br>";
					$linkok = 0;
				}
			}
				
			foreach ($have as $key => $value)
			{		
				if (!array_key_exists($key,$inlink))
				{
					//echo "Doesnt have ".$key."<br>";
					$linkok = 0;	
				} else {
					if ($inlink[$key] != $value)
					{
						//echo "Value mismatch for ".$key." - " . $value . " should be " . $inlink[$key] . "<br>";
						$linkok = 0;
					}
				}				
			}
		
			if ($linkok)
			{
				$results[] = $object;
				//echo "VALID : " . $link . "<br>";	
			}	
		}
	}
	
	return $results;
}
