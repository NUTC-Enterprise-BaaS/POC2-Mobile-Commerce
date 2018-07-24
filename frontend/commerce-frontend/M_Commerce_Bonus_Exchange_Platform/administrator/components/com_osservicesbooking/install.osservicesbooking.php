<?php
/*------------------------------------------------------------------------
# install.osservicesbooking.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die('Restricted access');
error_reporting(0);

class com_osservicesbookingInstallerScript {
	function install($parent)
	{
		com_install() ;
	}
	
	function update($parent)
	{
		com_install();
	}
}

function com_install() {
	jimport('joomla.filesystem.file') ;
    jimport('joomla.filesystem.folder') ;
    $db = & JFactory::getDBO(); 		
    define('DS',DIRECTORY_SEPARATOR);
    $config = new JConfig();
    $dbname = $config->db;
    $prefix = $config->dbprefix;
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_configuation'");
    $count = $db->loadResult();
    if($count == 0){
    	$configSql = JPATH_ADMINISTRATOR.'/components/com_osservicesbooking/sql/install.osservicesbooking.sql' ;
    	$sql = JFile::read($configSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
    }
    
    //Check categories table
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_categories'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_categories` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `category_name` varchar(255) NOT NULL,
					  `category_photo` varchar(255) NOT NULL,
					  `category_description` text NOT NULL,
					  `show_desc` tinyint(1) NOT NULL DEFAULT '0',
					  `published` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_categories");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('category_photo',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_categories` ADD `category_photo` varchar(255) NOT NULL DEFAULT '' AFTER `category_name`;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_employee_extra_cost'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_employee_extra_cost` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `eid` int(11) NOT NULL DEFAULT '0',
					  `start_time` varchar(10) NOT NULL,
					  `end_time` varchar(10) NOT NULL,
					  `extra_cost` decimal(6,2) NOT NULL,
					  `week_date` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
    	$db->query();
    }

	$db->setQuery("SHOW COLUMNS FROM #__app_sch_employee_extra_cost");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('week_date',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_extra_cost` ADD `week_date` tinyint(1) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_breaktime'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_breaktime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `sid` int(11) NOT NULL DEFAULT '0',
					  `eid` int(11) NOT NULL DEFAULT '0',
					  `start_from` time NOT NULL,
					  `end_to` time NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_employee_service'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_employee_service` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) DEFAULT NULL,
					  `service_id` int(11) DEFAULT NULL,
					  `ordering` int(11) DEFAULT NULL,
					  `vid` int(11) NOT NULL DEFAULT '0',
					  `additional_price` decimal(10,2) NOT NULL,
					  `mo` tinyint(1) NOT NULL DEFAULT '0',
					  `tu` tinyint(1) NOT NULL DEFAULT '0',
					  `we` tinyint(1) NOT NULL DEFAULT '0',
					  `th` tinyint(1) NOT NULL DEFAULT '0',
					  `fr` tinyint(1) NOT NULL DEFAULT '0',
					  `sa` tinyint(1) NOT NULL DEFAULT '0',
					  `su` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_custom_time_slots_relation'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_custom_time_slots_relation` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `time_slot_id` int(11) NOT NULL DEFAULT '0',
						  `date_in_week` tinyint(1) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_employee_rest_days'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_employee_rest_days` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `eid` int(11) NOT NULL DEFAULT '0',
					  `rest_date` date NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_fields'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_service_fields` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `service_id` int(11) NOT NULL DEFAULT '0',
					  `field_id` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_custom_time_slots'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_custom_time_slots` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `sid` int(11) NOT NULL DEFAULT '0',
					  `start_hour` int(2) NOT NULL DEFAULT '0',
					  `start_min` int(2) NOT NULL DEFAULT '0',
					  `end_hour` int(2) NOT NULL,
					  `end_min` int(2) NOT NULL,
					  `nslots` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_custom_breaktime'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_custom_breaktime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `eid` int(11) DEFAULT NULL,
					  `sid` int(11) DEFAULT NULL,
					  `bdate` date DEFAULT NULL,
					  `bstart` varchar(5) DEFAULT NULL,
					  `bend` varchar(5) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_price_adjustment'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_service_price_adjustment` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `sid` int(11) DEFAULT NULL,
						  `date_in_week` tinyint(1) unsigned DEFAULT NULL,
						  `same_as_original` tinyint(1) unsigned DEFAULT '1',
						  `price` decimal(7,2) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1  ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_custom_prices'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_service_custom_prices` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `sid` int(11) DEFAULT NULL,
						  `cstart` date DEFAULT NULL,
						  `cend` date DEFAULT NULL,
						  `amount` decimal(7,2) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_custom_prices'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_service_custom_prices` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `sid` int(11) DEFAULT NULL,
						  `cstart` date DEFAULT NULL,
						  `cend` date DEFAULT NULL,
						  `amount` decimal(7,2) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_custom_time_slots");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('nslots',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_custom_time_slots` ADD `nslots` INT( 11 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_employee_rest_days");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('rest_date_to',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_rest_days` ADD `rest_date_to` date NOT NULL AFTER `rest_date`");
    		$db->query();
    	}
    }

	$db->setQuery("SHOW COLUMNS FROM #__app_sch_fields");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
		if(!in_array('required',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_fields` ADD `required` tinyint(1) NOT NULL DEFAULT '0' AFTER `field_options`;");
    		$db->query();
    	}
    	if(!in_array('ordering',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_fields` ADD `ordering` INT( 11 ) NOT NULL DEFAULT '0' AFTER `field_options`;");
    		$db->query();
    	}
    }

	$db->setQuery("SHOW COLUMNS FROM #__app_sch_field_options");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('ordering',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_field_options` ADD `ordering` INT(11) NOT NULL DEFAULT '0' AFTER `field_option`;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_employee");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('gusername',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `gusername` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `employee_photo` ;");
    		$db->query();
    	}
    	if(!in_array('gpassword',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `gpassword` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `gusername` ;");
    		$db->query();
    	}
		if(!in_array('client_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD client_id VARCHAR( 250 ) NOT NULL DEFAULT '' AFTER `gpassword` ;");
    		$db->query();
    	}
		if(!in_array('app_name',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `app_name` VARCHAR( 250 ) NOT NULL DEFAULT '' AFTER `client_id` ;");
    		$db->query();
    	}
		if(!in_array('app_email_address',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `app_email_address` VARCHAR( 200 ) NOT NULL DEFAULT '' AFTER `app_name` ;");
    		$db->query();
    	}
		if(!in_array('p12_key_filename',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `p12_key_filename` VARCHAR( 100 ) NOT NULL DEFAULT '' AFTER `app_email_address` ;");
    		$db->query();
    	}
    	if(!in_array('gcalendarid',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `gcalendarid` VARCHAR( 250 ) NOT NULL DEFAULT '' AFTER `gusername` ;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_order_items");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
		if(!in_array('nslots',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_order_items` ADD `nslots` INT( 11 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('gcalendar_event_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_order_items` ADD `gcalendar_event_id` varchar(255) NOT NULL AFTER `additional_information`;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_time_custom_slots'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE `#__app_sch_service_time_custom_slots` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `custom_id` int(11) NOT NULL DEFAULT '0',
					  `sid` int(11) NOT NULL DEFAULT '0',
					  `service_slots` tinyint(3) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_order_options'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_order_options` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `order_id` int(11) NOT NULL DEFAULT '0',
					  `field_id` int(11) NOT NULL DEFAULT '0',
					  `option_id` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_order_field_options'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_order_field_options` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `order_item_id` int(11) NOT NULL DEFAULT '0',
					  `field_id` int(11) NOT NULL DEFAULT '0',
					  `option_id` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_field_options'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_field_options` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `field_id` int(11) NOT NULL DEFAULT '0',
					  `field_option` varchar(255) NOT NULL,
					  `additional_price` decimal(10,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    //check plugin table
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_plugins'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_plugins` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) DEFAULT NULL,
					  `title` varchar(100) DEFAULT NULL,
					  `author` varchar(250) DEFAULT NULL,
					  `creation_date` datetime DEFAULT NULL,
					  `copyright` varchar(255) DEFAULT NULL,
					  `license` varchar(255) DEFAULT NULL,
					  `author_email` varchar(50) DEFAULT NULL,
					  `author_url` varchar(50) DEFAULT NULL,
					  `version` varchar(50) DEFAULT NULL,
					  `description` varchar(255) DEFAULT NULL,
					  `params` text,
					  `ordering` int(11) DEFAULT NULL,
					  `published` tinyint(3) unsigned DEFAULT NULL,
					  `support_recurring_subscription` tinyint(4) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    //import plugins 
    $db->setQuery("Select count(id) from #__app_sch_plugins");
    $count = $db->loadResult();
    if($count == 0){
    	$configSql = JPATH_ADMINISTRATOR.'/components/com_osservicesbooking/sql/plugin.osservicesbooking.sql' ;
    	$sql = JFile::read($configSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
    }
    
    $db->setQuery("Select count(id) from #__app_sch_plugins where name like 'os_sagepay'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("Insert into #__app_sch_plugins VALUES (NULL, 'os_sagepay', 'Sagepay', 'Tuan Pham Ngoc', '0000-00-00 00:00:00', 'Copyright 2007-2013 Ossolution Team', 'http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2', 'contact@joomdonation.com', 'www.joomdonation.com', '1.0', 'Sagepay Payment Plugin For OS Services Booking Extension', '', 6, 1, 0)");
    	$db->query();
    }
    $db->setQuery("Select count(id) from #__app_sch_plugins where name like 'os_worldpay'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("Insert into #__app_sch_plugins VALUES (NULL, 'os_worldpay', 'Worldpay', 'Tuan Pham Ngoc', '0000-00-00 00:00:00', 'Copyright 2007-2013 Ossolution Team', 'http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2', 'contact@joomdonation.com', 'www.joomdonation.com', '1.0', 'Worldpay Payment Plugin For OS Services Booking Extension', NULL, 8, 0, 0)");
    	$db->query();
    }
    $db->setQuery("Select count(id) from #__app_sch_plugins where name like 'os_eway'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("Insert into #__app_sch_plugins VALUES (NULL, 'os_eway', 'Eway', 'Tuan Pham Ngoc', '0000-00-00 00:00:00', 'Copyright 2007-2013 Ossolution Team', 'http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2', 'contact@joomdonation.com', 'www.joomdonation.com', '1.0', 'Eway Payment Plugin For OS Services Booking Extension', '', 7, 1, 0)");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_userprofiles'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_userprofiles` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) DEFAULT NULL,
					  `order_name` varchar(50) DEFAULT NULL,
					  `order_email` varchar(50) DEFAULT NULL,
					  `order_phone` varchar(50) DEFAULT NULL,
					  `order_country` varchar(50) DEFAULT NULL,
					  `order_city` varchar(100) DEFAULT NULL,
					  `order_state` varchar(50) DEFAULT NULL,
					  `order_zip` varchar(50) DEFAULT NULL,
					  `order_address` varchar(100) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_temp_orders'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_temp_orders` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `unique_cookie` varchar(50) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_temp_orders");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('created_on',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_temp_orders` ADD `created_on` INT( 11 ) NOT NULL AFTER `unique_cookie` ;");
    		$db->query();
    	}
    }
    
    
     $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_temp_order_field_options'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_temp_order_field_options` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `order_item_id` int(11) DEFAULT NULL,
					  `field_id` int(11) DEFAULT NULL,
					  `option_id` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_temp_order_items'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_temp_order_items` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `order_id` int(11) DEFAULT NULL,
					  `sid` int(11) DEFAULT NULL,
					  `eid` int(11) DEFAULT NULL,
					  `start_time` int(11) DEFAULT NULL,
					  `end_time` int(11) DEFAULT NULL,
					  `booking_date` date DEFAULT NULL,
					  `nslots` int(11) NOT NULL DEFAULT '0',
					  `params` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_temp_temp_order_field_options'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_temp_temp_order_field_options` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `order_item_id` int(11) DEFAULT NULL,
						  `field_id` int(11) DEFAULT NULL,
						  `option_id` int(11) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_temp_temp_order_items'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_temp_temp_order_items` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `parent_id` int(11) NOT NULL DEFAULT '0',
						  `unique_cookie` varchar(50) DEFAULT NULL,
						  `sid` int(11) DEFAULT NULL,
						  `eid` int(11) DEFAULT NULL,
						  `start_time` int(11) DEFAULT NULL,
						  `end_time` int(11) DEFAULT NULL,
						  `booking_date` date DEFAULT NULL,
						  `nslots` int(11) NOT NULL DEFAULT '0',
						  `params` varchar(100) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }

	$db->setQuery("SHOW COLUMNS FROM #__app_sch_employee_service");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('vid',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `vid` INT( 0 ) NOT NULL AFTER `service_id` ;");
    		$db->query();
    	}
    	if(!in_array('additional_price',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `additional_price` DECIMAL( 6, 2 ) NOT NULL AFTER `ordering` ;");
    		$db->query();
    	}
    	if(!in_array('mo',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `mo` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('tu',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `tu` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('we',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `we` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('th',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `th` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('fr',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `fr` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('sa',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `sa` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('su',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee_service` ADD `su` TINYINT( 1 ) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	
    }
    //check category id field 
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_services");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('category_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `category_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `id` ;");
    		$db->query();
    	}
		if(!in_array('repeat_day',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `repeat_day` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `service_time_type`  ;");
    		$db->query();
    	}
		if(!in_array('repeat_week',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `repeat_week` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `repeat_day`  ;");
    		$db->query();
    	}
		if(!in_array('repeat_month',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `repeat_month` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `repeat_week`  ;");
    		$db->query();
    	}
    	if(!in_array('step_in_minutes',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `step_in_minutes` TINYINT( 3 ) NOT NULL DEFAULT '0' AFTER `repeat_month`  ;");
    		$db->query();
    	}
		if(!in_array('early_bird_amount',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `early_bird_amount` decimal(5,2) NOT NULL AFTER `service_time_type`  ;");
    		$db->query();
    	}
		if(!in_array('early_bird_type',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `early_bird_type` tinyint(1) NOT NULL DEFAULT '0' AFTER `early_bird_amount`  ;");
    		$db->query();
    	}
		if(!in_array('early_bird_days',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `early_bird_days` tinyint(3) NOT NULL AFTER `early_bird_type`  ;");
    		$db->query();
    	}
		if(!in_array('discount_timeslots',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `discount_timeslots` tinyint(3) NOT NULL DEFAULT '0' AFTER `early_bird_days`  ;");
    		$db->query();
    	}
		if(!in_array('discount_type',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `discount_type` tinyint(1) NOT NULL DEFAULT '0' AFTER `discount_timeslots`  ;");
    		$db->query();
    	}
		if(!in_array('discount_amount',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `discount_amount` decimal(5,2) NOT NULL AFTER `discount_type`  ;");
    		$db->query();
    	}
		if(!in_array('access',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `access` tinyint(1) NOT NULL DEFAULT '0' AFTER `published`  ;");
    		$db->query();
    	}
		if(!in_array('acymailing_list_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `acymailing_list_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `access`  ;");
    		$db->query();
    	}
    }
    
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_dialing_codes'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_dialing_codes` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `country` varchar(255) DEFAULT NULL,
						  `dial_code` varchar(20) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_venues'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_venues` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `image` varchar(100) NOT NULL,
						  `address` varchar(255) DEFAULT NULL,
						  `city` varchar(50) DEFAULT NULL,
						  `state` varchar(50) DEFAULT NULL,
						  `country` varchar(50) DEFAULT NULL,
						  `lat_add` varchar(50) NOT NULL,
						  `long_add` varchar(50) NOT NULL,
						  `contact_email` varchar(50) DEFAULT NULL,
						  `contact_name` varchar(50) DEFAULT NULL,
						  `contact_phone` varchar(50) DEFAULT NULL,
						  `disable_booking_before` tinyint(1) unsigned DEFAULT NULL,
						  `number_date_before` int(11) DEFAULT NULL,
						  `number_hour_before` int(11) NOT NULL,
						  `disable_date_before` date DEFAULT NULL,
						  `disable_booking_after` tinyint(1) unsigned DEFAULT NULL,
						  `number_date_after` int(11) DEFAULT NULL,
						  `disable_date_after` date DEFAULT NULL,
						  `published` tinyint(1) unsigned DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_venues");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(in_array('sid',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_venues` DROP `sid`");
    		$db->query();
    	}
    	if(!in_array('number_hour_before',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_venues` ADD `number_hour_before` INT( 11 ) NOT NULL DEFAULT '0' AFTER `number_date_before` ");
    		$db->query();
    	}
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_venue_services'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_venue_services` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `vid` int(11) DEFAULT NULL,
						  `sid` int(11) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_employee_service_breaktime'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_employee_service_breaktime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `eid` int(11) DEFAULT NULL,
					  `sid` int(11) DEFAULT NULL,
					  `date_in_week` tinyint(1) unsigned DEFAULT NULL,
					  `break_from` time DEFAULT NULL,
					  `break_to` time DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_coupons'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_coupons` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `coupon_name` varchar(255) DEFAULT NULL,
					  `coupon_code` varchar(255) DEFAULT NULL,
					  `discount` decimal(12,2) DEFAULT NULL,
					  `discount_type` tinyint(1) unsigned DEFAULT NULL,
					  `max_total_use` int(11) DEFAULT NULL,
					  `max_user_use` int(11) DEFAULT NULL,
					  `expiry_date` datetime DEFAULT NULL,
					  `published` tinyint(1) unsigned DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_coupon_used'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_coupon_used` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) DEFAULT NULL,
					  `coupon_id` int(11) DEFAULT NULL,
					  `order_id` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_user_balance'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_user_balance` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) DEFAULT NULL,
					  `amount` decimal(12,2) DEFAULT NULL,
					  `created_date` date DEFAULT NULL,
					  `note` text,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_service_availability'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_service_availability` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `sid` int(11) DEFAULT NULL,
					  `avail_date` date DEFAULT NULL,
					  `start_time` time DEFAULT NULL,
					  `end_time` time DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }
    
    $db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_urls'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_urls` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `md5_key` text,
					  `query` text,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
    }

	$db->setQuery("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = '".$prefix."app_sch_menus'");
    $count = $db->loadResult();
    if($count == 0){
    	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__app_sch_menus` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `menu_name` varchar(255) NOT NULL,
					  `menu_icon` varchar(255) NOT NULL,
					  `parent_id` int(11) NOT NULL DEFAULT '0',
					  `menu_task` varchar(255) NOT NULL,
					  `ordering` int(11) NOT NULL DEFAULT '0',
					  `published` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    	$db->query();
		$db->setQuery("INSERT INTO `#__app_sch_menus` (`id`, `menu_name`, `menu_icon`, `parent_id`, `menu_task`, `ordering`, `published`) VALUES
						(1, 'OS_DASHBOARD', 'icon-home', 0, 'cpanel_list', 1, 1),
						(2, 'OS_SETUP', 'icon-list', 0, '', 1, 1),
						(3, 'OS_MANAGE_VENUES', '', 2, 'venue_list', 1, 1),
						(4, 'OS_MANAGE_CATEGORIES', '', 2, 'category_list', 2, 1),
						(5, 'OS_MANAGE_SERVICES', '', 2, 'service_list', 3, 1),
						(6, 'OS_MANAGE_EMPLOYEES', '', 2, 'employee_list', 4, 1),
						(7, 'OS_OTHER', 'icon-wrench', 0, '', 2, 1),
						(8, 'OS_CUSTOM_FIELDS_MANAGEMENT', '', 7, 'fields_list', 0, 1),
						(9, 'OS_MANAGE_ORDERS', '', 7, 'orders_list', 1, 1),
						(10, 'OS_MANAGE_WORKTIME', '', 7, 'worktime_list', 2, 1),
						(11, 'OS_MANAGE_WORKTIMECUSTOM', '', 7, 'worktimecustom_list', 3, 1),
						(12, 'OS_MANAGE_PAYMENT_PLUGINS', '', 7, 'plugin_list', 4, 1),
						(13, 'OS_MANAGE_COUPONS', '', 7, 'coupon_list', 5, 1),
						(14, 'OS_MANAGE_TRANSLATION_LIST', '', 7, 'translation_list', 6, 1),
						(15, 'OS_CONFIGURATION_CONFIGURATION', 'icon-cog', 0, 'configuration_list', 4, 1),
						(16, 'OS_TOOLS', 'icon-tools', 0, '', 5, 1),
						(17, 'OS_FIX_DATABASE_SCHEMA', '', 16, 'cpanel_optimizedatabase', 1, 1),
						(18, 'OS_MANAGE_EMAIL_TEMPLATES', '', 7, 'emails_list', 7, 1);");
    	$db->query();
    }
    

	$db->setQuery("Select count(id) from #__app_sch_menus");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO `#__app_sch_menus` (`id`, `menu_name`, `menu_icon`, `parent_id`, `menu_task`, `ordering`, `published`) VALUES
						(1, 'OS_DASHBOARD', 'icon-home', 0, 'cpanel_list', 1, 1),
						(2, 'OS_SETUP', 'icon-list', 0, '', 1, 1),
						(3, 'OS_MANAGE_VENUES', '', 2, 'venue_list', 1, 1),
						(4, 'OS_MANAGE_CATEGORIES', '', 2, 'category_list', 2, 1),
						(5, 'OS_MANAGE_SERVICES', '', 2, 'service_list', 3, 1),
						(6, 'OS_MANAGE_EMPLOYEES', '', 2, 'employee_list', 4, 1),
						(7, 'OS_OTHER', 'icon-wrench', 0, '', 2, 1),
						(8, 'OS_CUSTOM_FIELDS_MANAGEMENT', '', 7, 'fields_list', 0, 1),
						(9, 'OS_MANAGE_ORDERS', '', 7, 'orders_list', 1, 1),
						(10, 'OS_MANAGE_WORKTIME', '', 7, 'worktime_list', 2, 1),
						(11, 'OS_MANAGE_WORKTIMECUSTOM', '', 7, 'worktimecustom_list', 3, 1),
						(12, 'OS_MANAGE_PAYMENT_PLUGINS', '', 7, 'plugin_list', 4, 1),
						(13, 'OS_MANAGE_COUPONS', '', 7, 'coupon_list', 5, 1),
						(14, 'OS_MANAGE_TRANSLATION_LIST', '', 7, 'translation_list', 6, 1),
						(15, 'OS_CONFIGURATION_CONFIGURATION', 'icon-cog', 0, 'configuration_list', 4, 1),
						(16, 'OS_TOOLS', 'icon-tools', 0, '', 5, 1),
						(17, 'OS_FIX_DATABASE_SCHEMA', '', 16, 'cpanel_optimizedatabase', 1, 1),
						(18, 'OS_MANAGE_EMAIL_TEMPLATES', '', 7, 'emails_list', 7, 1);");
    	$db->query();
	}

    $sql = 'SELECT COUNT(id) FROM #__app_sch_configuation';
	$db->setQuery($sql) ;	
	$total = $db->loadResult();
	if (!$total) {		
		$configSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osservicesbooking'.DS.'sql'.DS.'config.osservicesbooking.sql' ;
		$sql = JFile::read($configSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
	}
	
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_orders");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
		if(!in_array('user_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `user_id` INT(11) NOT NULL DEFAULT '0';");
    		$db->query();
    	}
    	if(!in_array('order_notes',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_notes` TEXT NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_card_number',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_card_number` VARCHAR(50) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_card_type',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_card_type` VARCHAR(50) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_card_expiry_month',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_card_expiry_month` INT(2) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_card_expiry_year',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_card_expiry_year` INT(4) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_card_holder',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_card_holder` VARCHAR(100) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('order_cvv_code',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_cvv_code` VARCHAR(4) NOT NULL ;");
    		$db->query();
    	}
    	if(!in_array('dial_code',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `dial_code` VARCHAR(10) NOT NULL AFTER `order_email`;");
    		$db->query();
    	}
    	if(!in_array('send_email',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `send_email` TINYINT(1) NOT NULL DEFAULT '0' AFTER `order_cvv_code`;");
    		$db->query();
    	}
    	if(!in_array('order_discount',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_discount` DECIMAL(12,2) NOT NULL DEFAULT '0' AFTER `order_tax`;");
    		$db->query();
    	}
    	if(!in_array('coupon_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `coupon_id` INT(11) NOT NULL DEFAULT '0' AFTER `send_email`;");
    		$db->query();
    	}
		if(!in_array('bank_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `bank_id` VARCHAR( 100 ) NOT NULL;");
    		$db->query();
    	}
		if(!in_array('order_lang',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_orders` ADD `order_lang` VARCHAR( 20 ) NOT NULL AFTER `order_date`;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_employee");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('user_id',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_employee` ADD `user_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `id` ;");
    		$db->query();
    	}
    }
    
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_working_time_custom");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('reason',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_working_time_custom` ADD `reason` text NOT NULL AFTER `id` ;");
    		$db->query();
    	}
    	if(!in_array('worktime_date_to',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_working_time_custom` ADD `worktime_date_to` date NOT NULL AFTER `worktime_date` ;");
    		$db->query();
    	}
    }
    
    //ALTER TABLE `#__app_sch_orders` ADD `notes` TEXT ;
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_services");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('service_time_type',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_services` ADD `service_time_type` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `service_photo ;");
    		$db->query();
    	}
    }
	
	$sql = 'SELECT COUNT(*) FROM #__app_sch_working_time';
	$db->setQuery($sql) ;	
	$total = $db->loadResult();
	if (!$total) {		
		$timeSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osservicesbooking'.DS.'sql'.DS.'time.osservicesbooking.sql' ;
		$sql = JFile::read($timeSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
	}
	
    $db->setQuery("SHOW COLUMNS FROM #__app_sch_order_items");
    $fields = $db->loadObjectList();
    if(count($fields) > 0){
    	$fieldArr = array();
    	for($i=0;$i<count($fields);$i++){
    		$field = $fields[$i];
    		$fieldname = $field->Field;
    		$fieldArr[$i] = $fieldname;
    	}
    	if(!in_array('additional_information',$fieldArr)){
    		$db->setQuery("ALTER TABLE `#__app_sch_order_items` ADD `additional_information` TEXT NOT NULL ;");
    		$db->query();
    	}
    }
	
	$sql = 'SELECT COUNT(*) FROM #__app_sch_emails';
	$db->setQuery($sql) ;	
	$total = $db->loadResult();
	if (!$total) {		
		$timeSql = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osservicesbooking'.DS.'sql'.DS.'emails.osservicesbooking.sql' ;
		$sql = JFile::read($timeSql) ;
		$queries = $db->splitSql($sql);
		if (count($queries)) {
			foreach ($queries as $query) {
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					$db->query();						
				}	
			}
		}
	}
	
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osservicesbooking")){
		JFolder::create(JPATH_ROOT.DS."images".DS."osservicesbooking");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."index.html");
	}
	if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."category")){
		JFolder::create(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."category");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."category".DS."index.html");
	}
	if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services")){
		JFolder::create(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services".DS."index.html");
	}
	if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."employee")){
		JFolder::create(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."employee");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."employee".DS."index.html");
	}
	if(!JFolder::exists(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."venue")){
		JFolder::create(JPATH_ROOT.DS."images".DS."osservicesbooking".DS."venue");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."venue".DS."index.html");
	}
	if(!JFolder::exists(JPATH_ROOT.DS."media".DS."com_osservicesbooking".DS."invoices")){
		JFolder::create(JPATH_ROOT.DS."media".DS."com_osservicesbooking".DS."invoices");
		JFile::copy(JPATH_COMPONENT.DS."index.html",JPATH_ROOT.DS."media".DS."com_osservicesbooking".DS."invoices".DS."index.html");
	}
	
	//copy file
	if(!file_exists(JPATH_ROOT.DS."images".DS."logo.jpg")){
		JFile::copy(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."style".DS."images".DS."logo.jpg",JPATH_ROOT.DS."images".DS."logo.jpg");
	}
	JFile::copy(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."style".DS."images".DS."massage.jpg",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services".DS."massage.jpg");
	JFile::copy(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."style".DS."images".DS."tennis.jpg",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services".DS."tennis.jpg");
	JFile::copy(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."style".DS."images".DS."baby.jpg",JPATH_ROOT.DS."images".DS."osservicesbooking".DS."services".DS."baby.jpg");
	
	
	$db->setQuery("Select count(id) from #__app_sch_configuation where config_key like 'invoice_format'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_configuation (id,config_key,config_value) VALUES (NULL,'invoice_format','&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;100%&quot;&gt;&lt;br /&gt;&lt;table width=&quot;100%&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td width=&quot;100%&quot;&gt;&lt;br /&gt;&lt;table style=&quot;width: 100%;&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; valign=&quot;top&quot; width=&quot;50%&quot;&gt;&lt;br /&gt;&lt;table style=&quot;width: 100%;&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Company Name:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;Ossolution Team&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;URL:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;http://www.joomdonation.com&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Phone:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;84-972409994&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;E-mail:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;contact@joomdonation.com&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Address:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;Lang Ha - Ba Dinh - Ha Noi&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/td&gt;&lt;td align=&quot;right&quot; valign=&quot;middle&quot; width=&quot;50%&quot;&gt;&lt;img src=&quot;images/logo.jpg&quot; width=&quot;129&quot; height=&quot;120&quot; /&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td colspan=&quot;2&quot; align=&quot;left&quot; width=&quot;100%&quot;&gt;&lt;table style=&quot;width: 100%;&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; valign=&quot;top&quot; width=&quot;50%&quot;&gt;&lt;br /&gt;&lt;table width=&quot;589&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;2&quot;&gt;&lt;tbody&gt;&lt;tr&gt;&lt;td style=&quot;background-color: #d6d6d6;&quot; colspan=&quot;2&quot; align=&quot;left&quot;&gt;&lt;br /&gt;&lt;h4 style=&quot;margin: 0px;&quot;&gt;Customer Information&lt;/h4&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Name:&lt;/td&gt;&lt;td align=&quot;left&quot;&gt;[NAME]&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Phone:&lt;/td&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;[PHONE]&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;Email:&lt;/td&gt;&lt;td align=&quot;left&quot; width=&quot;50%&quot;&gt;[EMAIL]&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td style=&quot;background-color: #d6d6d6;&quot; colspan=&quot;2&quot; align=&quot;left&quot;&gt;&lt;br /&gt;&lt;h4 style=&quot;margin: 0px;&quot;&gt;Details&lt;/h4&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td colspan=&quot;2&quot; align=&quot;left&quot;&gt;[DETAILS]&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/tbody&gt;&lt;/table&gt;')");
		$db->query();
	}
	
	
	$db->setQuery("Select count(id) from #__app_sch_configuation where config_key like 'hidetabs'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_configuation (id,config_key,config_value) VALUES (NULL,'hidetabs','1')");
		$db->query();
	}

	$db->setQuery("Select count(id) from #__app_sch_configuation where config_key like 'employee_bar'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_configuation (id,config_key,config_value) VALUES (NULL,'employee_bar','1')");
		$db->query();
	}

	//dial code
	$db->setQuery("Select count(id) from #__app_sch_dialing_codes");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO `#__app_sch_dialing_codes` (`id`, `country`, `dial_code`) VALUES
						(1, 'Afganistan', '93'),
						(2, 'Albania', '355'),
						(3, 'Algeria', '213'),
						(4, 'Andorra', '376'),
						(5, 'Angola', '244'),
						(6, 'Antilles Netherland', '599'),
						(7, 'Antiqua', '1'),
						(8, 'Argentina', '54'),
						(9, 'Armenia', '374'),
						(10, 'Aruba', '297'),
						(11, 'Australia', '61'),
						(12, 'Austria', '43'),
						(13, 'Azerbaijan', '994'),
						(14, 'Bahamas', '1'),
						(15, 'Bahrain', '973'),
						(16, 'Bangladesh', '880'),
						(17, 'Barbados', '1'),
						(18, 'Belarus', '375'),
						(19, 'Belgium', '32'),
						(20, 'Belize', '501'),
						(21, 'Benin', '229'),
						(22, 'Bermuda', '1'),
						(23, 'Bhutan', '975'),
						(24, 'Bolivia', '591'),
						(25, 'Bosnia Herzegovina', '387'),
						(26, 'Botswana', '267'),
						(27, 'Brazil', '55'),
						(28, 'British Virgin', '1'),
						(29, 'Brunei Darussalam', '673'),
						(30, 'Bulgaria', '359'),
						(31, 'Burkina Faso', '226'),
						(32, 'Cameroon', '237'),
						(33, 'Canada', '1'),
						(34, 'Canary Islands', '0'),
						(35, 'Cayman Islands', '1'),
						(36, 'Central African', '236'),
						(37, 'Chad', '235'),
						(38, 'Chile', '56'),
						(39, 'China', '86'),
						(40, 'Chinese Taipei', '0'),
						(41, 'Colombia', '57'),
						(42, 'Congo Republic', '242'),
						(43, 'Cook Islands', '682'),
						(44, 'Costa Rica', '506'),
						(45, 'Croatia', '385'),
						(46, 'Cuba', '53'),
						(47, 'Cyprus', '357'),
						(48, 'Czech Republic', '420'),
						(49, 'Denmark', '45'),
						(50, 'Djibouti', '253'),
						(51, 'Dominican Republic', '1'),
						(52, 'Ecuador', '593'),
						(53, 'Egypt', '20'),
						(54, 'El Salvador', '503'),
						(55, 'Equatorial Guinea', '240'),
						(56, 'Estonia', '372'),
						(57, 'Ethiopia', '251'),
						(58, 'Faeroe Islands', '298'),
						(59, 'Fiji', '679'),
						(60, 'Finland', '358'),
						(61, 'France', '33'),
						(62, 'French Guiana', '594'),
						(63, 'Gabon Republic', '241'),
						(64, 'Georgia', '995'),
						(65, 'Germany', '49'),
						(66, 'Ghana', '233'),
						(67, 'Gibraltar', '350'),
						(68, 'Greece', '30'),
						(69, 'Greenland', '299'),
						(70, 'Grenada', '1'),
						(71, 'Guadeloupe', '590'),
						(72, 'Guatemala', '502'),
						(73, 'Guinea (PRP)', '224'),
						(74, 'Guinea - Bissau', '245'),
						(75, 'Guyana', '592'),
						(76, 'Haiti', '509'),
						(77, 'Honduras', '504'),
						(78, 'Hong Kong', '852'),
						(79, 'Hungary', '36'),
						(80, 'Iceland', '354'),
						(81, 'India', '91'),
						(82, 'Indonesia', '62'),
						(83, 'Iran', '98'),
						(84, 'Iraq', '964'),
						(85, 'Ireland', '353'),
						(86, 'Israel', '972'),
						(87, 'Italy', '39'),
						(88, 'Ivory Coast', '225'),
						(89, 'Jamaica', '1'),
						(90, 'Japan', '81'),
						(91, 'Jordan', '962'),
						(92, 'Kazakhstan', '7'),
						(93, 'Kenya', '254'),
						(94, 'Kuwait', '965'),
						(95, 'Kyrghyzstan', '996'),
						(96, 'Laos', '856'),
						(97, 'Latvia', '371'),
						(98, 'Lebanon', '961'),
						(99, 'Lesotho', '266'),
						(100, 'Liberia', '231'),
						(101, 'Libya', '218'),
						(102, 'Liechtenstein', '423'),
						(103, 'Lithuania', '370'),
						(104, 'Luxembourg', '352'),
						(105, 'Macau', '853'),
						(106, 'Macedonia', '389'),
						(107, 'Madagascar', '261'),
						(108, 'Malawi', '265'),
						(109, 'Malaysia', '60'),
						(110, 'Maldives', '960'),
						(111, 'Mali', '223'),
						(112, 'Malta', '356'),
						(113, 'Martinique', '596'),
						(114, 'Mauritania', '222'),
						(115, 'Mauritius', '230'),
						(116, 'Mexico', '52'),
						(117, 'Moldova', '373'),
						(118, 'Monaco', '377'),
						(119, 'Mongolia', '976'),
						(120, 'Montenegro', '381'),
						(121, 'Morocco', '212'),
						(122, 'Mozambique', '258'),
						(123, 'Namibia', '264'),
						(124, 'Nauru', '674'),
						(125, 'Nepal', '977'),
						(126, 'Netherlands', '31'),
						(127, 'New Caledonia', '687'),
						(128, 'New Zealand', '64'),
						(129, 'Nicaragua', '505'),
						(130, 'Niger', '227'),
						(131, 'Nigeria', '234'),
						(132, 'North Korea', '850'),
						(133, 'Norway', '47'),
						(134, 'Oman', '968'),
						(135, 'Pakistan', '92'),
						(136, 'Panama', '507'),
						(137, 'Paraguay', '595'),
						(138, 'Peru', '51'),
						(139, 'Philippines', '63'),
						(140, 'Poland', '48'),
						(141, 'Portugal', '351'),
						(142, 'Qatar', '974'),
						(143, 'Reunion Is.', '262'),
						(144, 'Romania', '40'),
						(145, 'Russia', '7'),
						(146, 'Rwanda', '250'),
						(147, 'Samoa', '685'),
						(148, 'San Marino', '378'),
						(149, 'Saudi Arabia', '966'),
						(150, 'Senegal', '221'),
						(151, 'Serbia', '381'),
						(152, 'Seychelles', '248'),
						(153, 'Sierra Leone', '232'),
						(154, 'Singapore', '65'),
						(155, 'Slovakia', '421'),
						(156, 'Slovenia', '386'),
						(157, 'Solomon Islands', '677'),
						(158, 'Somalia', '252'),
						(159, 'South Africa', '27'),
						(160, 'South Africa', '27'),
						(161, 'South Korea', '82'),
						(162, 'Spain', '34'),
						(163, 'Sri Lanka', '94'),
						(164, 'St. Kitts', '1'),
						(165, 'St. Lucia', '1'),
						(166, 'St. Pierre', '508'),
						(167, 'St. Vincent', '1'),
						(168, 'Sudan', '249'),
						(169, 'Surinam', '597'),
						(170, 'Swaziland', '268'),
						(171, 'Sweden', '46'),
						(172, 'Switzerland', '41'),
						(173, 'Syria', '963'),
						(174, 'Tadjikistan', '992'),
						(175, 'Tahiti', '689'),
						(176, 'Taiwan ROC', '886'),
						(177, 'Tanzania', '255'),
						(178, 'Thailand', '66'),
						(179, 'Togo', '228'),
						(180, 'Tonga', '676'),
						(181, 'Trinidad', '868'),
						(182, 'Tunisia', '216'),
						(183, 'Turkey', '90'),
						(184, 'Uganda', '256'),
						(185, 'Ukraine', '380'),
						(186, 'United Arab Emirates', '971'),
						(187, 'United Kingdom', '44'),
						(188, 'Uruguay', '598'),
						(189, 'USA', '1'),
						(190, 'Vanuatu', '678'),
						(191, 'Vatican City', '39'),
						(192, 'Venezuela', '58'),
						(193, 'Vietnam', '84'),
						(194, 'Yemen', '967'),
						(195, 'Yugoslavia', '11'),
						(196, 'Zaire', '243'),
						(197, 'Zambia', '260'),
						(198, 'Zimbabwe', '263');");
		$db->query();
	}
	
	
	$db->setQuery("SELECT COUNT(id) FROM #__app_sch_emails WHERE `email_key` like 'admin_order_cancelled'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_emails (id,email_key,email_subject,email_content) VALUES (NULL,','admin_order_cancelled', 'Order has been removed', '<p>An order has been cancelled<br /><br />Personal details:<br />Name: {Name}<br />E-Mail: {Email}<br />Phone: {Phone}<br />Country: {Country}<br />City: {City}<br />State: {State}<br />Zip: {Zip}<br />Address: {Address}<br />Notes: {Notes}<br /><br />Booking details:<br />Booking ID: {BookingID}<br />Services: {Services}<br />Deposit: {Deposit}<br />Total: {Total}<br />Tax: {Tax}</p>'),");
		$db->query();
	}
	
	$db->setQuery("SELECT COUNT(id) FROM #__app_sch_emails WHERE `email_key` like 'employee_order_cancelled'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_emails (id,email_key,email_subject,email_content) VALUES (NULL,'employee_order_cancelled', 'Order has been removed', '<p>One customer has cancelled his(her) booking for one service<br /><br />Personal details:<br />Name: {Name}<br />E-Mail: {Email}<br />Phone: {Phone}<br />Country: {Country}<br />State: {State}<br />Zip: {Zip}<br />Address: {Address}<br /><br />Booking details:<br />Services: {Services}<br />Start time: {Starttime}<br />End time: {Endtime}</p>\r\n<p>Booking date: {Bookingdate}</p>');");
		$db->query();
	}

	$db->setQuery("SELECT COUNT(id) FROM #__app_sch_emails WHERE `email_key` like 'order_status_changed_to_customer'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_emails (id,email_key,email_subject,email_content) VALUES (NULL,'order_status_changed_to_customer', 'Your Order status has been changed', '<p>Your Order status has been changed to {new_status}</p>\r\n<p>Booking details:<br />Booking ID: {BookingID}<br />Services: {Services}</p>\r\n<p>Thanks</p>\r\n<p>Dam</p>');");
		$db->query();
	}

	$db->setQuery("SELECT COUNT(id) FROM #__app_sch_emails WHERE `email_key` like 'order_status_changed_to_employee'");
	$count = $db->loadResult();
	if($count == 0){
		$db->setQuery("INSERT INTO #__app_sch_emails (id,email_key,email_subject,email_content) VALUES (NULL,'order_status_changed_to_employee', 'Order status has been changed', '<p>Order status has been changed<br /><br />Personal details:<br />Name: {Name}<br />E-Mail: {Email}<br />Phone: {Phone}<br />Country: {Country}<br />State: {State}<br />Zip: {Zip}<br />Address: {Address}<br /><br />Booking details:<br />Services: {Services}<br />Start time: {Starttime}<br />End time: {Endtime}</p>\r\n<p>Booking date: {Bookingdate}</p>\r\n<p>New status: {newstatus}</p>\r\n<p>Thanks</p>');");
		$db->query();
	}
	
	$db->setQuery("Update #__app_sch_plugins set published = '0' where `name` like 'os_eway'");
	$db->query();
	?>
	<script language="javascript">
	function installSampleData(){
		location.href = "index.php?option=com_osservicesbooking&task=install_list";
	}
	</script>
	<div style="width:95%;padding:10px;border:1px solid #55F489;background-color:#D3FFE1;">
		<center>
			<b>Do you want to install sample data?</b>
			<BR>
			<input type="button" class="btn btn-info" value="INSTALL SAMPLE DATA" onclick="javascript:installSampleData();">
		</center>
	</div>
	<?php
}
?>