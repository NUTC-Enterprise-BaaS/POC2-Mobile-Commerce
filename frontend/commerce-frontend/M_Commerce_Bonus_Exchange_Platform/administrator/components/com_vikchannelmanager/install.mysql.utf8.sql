
CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_roomsxref` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idroomvb` int(10) NOT NULL,
  `idroomota` int(10) NOT NULL,
  `idchannel` int(10) NOT NULL DEFAULT 0,
  `channel` varchar(64) DEFAULT 'expedia',
  `otaroomname` varchar(128) DEFAULT NULL,
  `otapricing` text DEFAULT NULL,
  `prop_name` varchar(128) NOT NULL DEFAULT '',
  `prop_params` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(32) NOT NULL DEFAULT 'false',
  `setting` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `param` (`param`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idordervb` int(10) NOT NULL,
  `ts` int(11) DEFAULT NULL,
  `orderdata` text DEFAULT NULL,
  `channel` varchar(64) DEFAULT 'expedia',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `from` varchar(64) DEFAULT 'e4jconnect',
  `cont` text,
  `idordervb` int(10) DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_notification_child` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `cont` text,
  `channel` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idordervb` int(10) NOT NULL DEFAULT 0,
  `key` int(10) NOT NULL DEFAULT '1717',
  `id_notification` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `params` text NOT NULL DEFAULT '',
  `uniquekey` varchar(16) NOT NULL DEFAULT '0xFF',
  `av_enabled` tinyint(1) DEFAULT 0,
  `settings` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_hotel_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL DEFAULT 'false',
  `value` text,
  `required` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `param` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_tac_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `desc` varchar(1000) NOT NULL DEFAULT '',
  `img` varchar(256) DEFAULT '',
  `url` varchar(512) NOT NULL DEFAULT '',
  `cost` decimal(6, 2) DEFAULT 0.0,
  `amenities` text DEFAULT '',
  `codes` varchar(32) DEFAULT '',
  `id_vb_room` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_tri_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `desc` varchar(1000) NOT NULL DEFAULT '',
  `img` varchar(256) DEFAULT '',
  `url` varchar(512) NOT NULL DEFAULT '',
  `cost` decimal(6, 2) DEFAULT 0.0,
  `codes` varchar(32) DEFAULT '',
  `id_vb_room` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_listings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `retrieval_url` varchar(256) NOT NULL, 
  `id_vb_room` int(10) NOT NULL,
  `channel` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_call_stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(16) NOT NULL,
  `call` varchar(64) NOT NULL,
  `min_exec_time` decimal(12, 4) DEFAULT 999999,
  `max_exec_time` decimal(12, 4) DEFAULT 0,
  `last_exec_time` decimal(12, 4) DEFAULT 0,
  `last_visit` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikchannelmanager_rar_updates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(16) NOT NULL,
  `date` varchar(32) NOT NULL,
  `room_type_id` varchar(32) NOT NULL,
  `data` text DEFAULT NULL,
  `last_update` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('dateformat', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('currencysymb', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('currencyname', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('defaultpayment', '-1');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('vikbookingsynch', '1');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('emailadmin', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('apikey', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('moduleactive', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('account_status', '0');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('version', '1.0');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('to_update', '0');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('block_program', '0');

INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('tri_partner_id', '');

INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('tac_partner_id', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('tac_account_id', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('tac_api_key', '');
INSERT INTO `#__vikchannelmanager_config` (`param`,`setting`) VALUES('pro_level', '1');

INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('name', '', 1);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('street', '', 1);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('city', '', 1);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('zip', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('state', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('country', '', 1);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('latitude', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('longitude', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('description', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('amenities', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('url', '', 1);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('email', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('phone', '', 0);
INSERT INTO `#__vikchannelmanager_hotel_details` (`key`,`value`,`required`) VALUES('fax', '', 0);
