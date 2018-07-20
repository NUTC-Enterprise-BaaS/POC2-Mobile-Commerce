CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `generated_time` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_company_offers` 
ADD COLUMN `total_coupons` int(11) NOT NULL DEFAULT '0',
ADD COLUMN `currencyId` int(11) NOT NULL,
ADD COLUMN `meta_title` varchar(100) NOT NULL,
ADD COLUMN `meta_description` varchar(255),
ADD COLUMN `meta_keywords` varchar(255);

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES
(29, 'attachments', 2),
(30, 'custom_tab', 2);

ALTER TABLE `#__jbusinessdirectory_company_events`
ADD COLUMN `meta_title` varchar(100) NOT NULL,
ADD COLUMN `meta_description` text,
ADD COLUMN `meta_keywords` text;

ALTER TABLE `#__jbusinessdirectory_companies`
ADD COLUMN `meta_title` varchar(100) NOT NULL,
ADD COLUMN `meta_description` text;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `surname` varchar(145) DEFAULT NULL,
  `email` char(255) NOT NULL,
  `message` text DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_applicationsettings`
ADD COLUMN `enable_offer_coupons` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `enable_rss` tinyint(1) NOT NULL DEFAULT '1',
MODIFY COLUMN `address_format` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `enable_socials` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `add_country_address` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `usergroup` int(11) NOT NULL DEFAULT '2',
ADD COLUMN `category_url_type` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `adaptive_height_gallery` tinyint(1) NOT NULL DEFAULT '0',
ADD COLUMN `autoplay_gallery` tinyint(1) NOT NULL DEFAULT '0',
ADD COLUMN `enable_menu_alias_url` tinyint(1) NOT NULL DEFAULT '0',
ADD COLUMN `invoice_company_name` varchar(100) NOT NULL,
ADD COLUMN `invoice_company_address` varchar(75) DEFAULT NULL,
ADD COLUMN `invoice_company_phone` varchar(75) DEFAULT NULL,
ADD COLUMN `invoice_company_email` varchar(75) NOT NULL,
ADD COLUMN `invoice_vat`  varchar(75) DEFAULT '0',
ADD COLUMN `invoice_details` text,
ADD COLUMN `max_business` SMALLINT NOT NULL DEFAULT '20';

ALTER TABLE `#__jbusinessdirectory_categories`
ADD COLUMN `type` tinyint(1) NOT NULL DEFAULT '1';

ALTER TABLE `#__jbusinessdirectory_attributes`
ADD COLUMN `attribute_type` tinyint(1) NOT NULL DEFAULT '1';

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_event_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_UNIQUE` (`event_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_offer_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_UNIQUE` (`offer_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

UPDATE `#__jbusinessdirectory_categories` SET `type` = 0 WHERE `id` = 1;

ALTER TABLE `#__jbusinessdirectory_companies` 
ADD COLUMN `pinterest` VARCHAR(100) NULL COMMENT '';
