CREATE TABLE IF NOT EXISTS `#__ad_archive_stats` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ad_id` INT(11)  NOT NULL COMMENT 'FK to #__ad_data',
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Record date',
`impression` INT(11)  NOT NULL COMMENT 'Count of impressions from #__ad_stats selected days',
`click` INT(11)  NOT NULL COMMENT 'Count of clicks from #__ad_stats selected days',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_campaign` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`campaign` VARCHAR(255)  NOT NULL COMMENT 'Name of campaign',
`daily_budget` INT(11)  NOT NULL COMMENT 'Daily budget assigned for campaign',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_wallet_transc` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`time` DOUBLE(15,2)  NOT NULL COMMENT 'Time at which transaction made',
`user_id` INT(11)  NOT NULL COMMENT 'userid who added a money in wallet',
`spent` DOUBLE(15,2)  NOT NULL COMMENT 'Amount debited from users wallet',
`earn` DOUBLE(11,2)  NOT NULL COMMENT 'Amount credited to users wallet',
`balance` DOUBLE(11,2)  NOT NULL COMMENT 'Remaining balance in users wallet',
`type` VARCHAR(255)  NOT NULL COMMENT 'Type of transaction O is transaction for adding money in a wallet, migrate to wallet mode to pay per ad mode and vice versa and C is click and impression deduction from wallet',
`type_id` INT(11) NULL COMMENT 'Order Id or a campaign Id',
`comment` VARCHAR(50) NULL COMMENT 'Lanuage constant of a comment for a transaction type',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_contextual_target` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ad_id` INT(11)  NOT NULL COMMENT 'FK to #__ad_data',
`keywords` TEXT NOT NULL COMMENT 'Meta keywords for contextual targeting',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_contextual_terms` (
`link_id` INT(10)  NOT NULL ,
`term_id` INT(10)  NOT NULL ,
`weight` FLOAT NOT NULL ,
`term` VARCHAR(75)  NOT NULL ,
`indexdate` DATE NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_coupon` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`name` VARCHAR(100)  NOT NULL COMMENT 'Coupon name',
`code` VARCHAR(100)  NOT NULL COMMENT 'Unique code for coupon',
`value` INT(11)  NOT NULL COMMENT 'Amount given for coupon',
`val_type` TINYINT(4)  NOT NULL COMMENT '0 - coupon applied for flat discount, 1 - coupon applied on percentage discount',
`max_use` INT(11)  NOT NULL COMMENT 'Max number of coupon usage',
`max_per_user` INT(11)  NOT NULL COMMENT 'Max number of time one user can use single coupon',
`description` TEXT NOT NULL COMMENT 'Coupon description',
`params` TEXT NOT NULL COMMENT 'For extra details',
`from_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Coupon valid date',
`exp_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Coupon expires on',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_data` (
`ad_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`ad_url1` MEDIUMTEXT NOT NULL COMMENT 'User want to use http or https for a ad link',
`ad_url2` MEDIUMTEXT NOT NULL COMMENT 'After clicking on ad on which page advertiser wants to link',
`ad_title` VARCHAR(100)  NOT NULL COMMENT 'Title of a ad',
`ad_body` MEDIUMTEXT NOT NULL COMMENT 'Content of a ad',
`ad_image` VARCHAR(200)  NOT NULL COMMENT 'Image for a ad',
`ad_startdate` DATE NOT NULL DEFAULT '0000-00-00' COMMENT 'Date on which user wants to start ad displaying',
`ad_enddate` DATE NOT NULL DEFAULT '0000-00-00' COMMENT 'Date on which user want to stop ad displaying',
`ad_noexpiry` TINYINT(2)  NOT NULL COMMENT 'Unlimited ads',
`ad_payment_type` TINYINT(2)  NOT NULL COMMENT 'Payment type selected  for ad',
`ad_credits` INT(10)  NOT NULL COMMENT 'Number of credits avilable for a ad.',
`ad_credits_balance` INT(10)  NOT NULL COMMENT 'Number of credits remaining for a ad',
`ad_created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Ad creation date.',
`ad_modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Ad modification date.',
`ad_approved` TINYINT(4)  NOT NULL COMMENT 'Payment of ad is done or not',
`ad_alternative` TINYINT(4)  NOT NULL COMMENT 'If no ad is matching. show alternative ad',
`ad_guest` TINYINT(4)  NOT NULL COMMENT 'Show ad to a guest user',
`ad_affiliate` TINYINT(4)  NOT NULL COMMENT 'Ad created from google adsence',
`ad_zone` TINYINT(4)  NOT NULL COMMENT 'Ad created in this zone',
`layout` VARCHAR(200)  NOT NULL COMMENT 'Zone layout selcted for this ad',
`camp_id` INT(11)  NOT NULL COMMENT 'If Ad wallet mode then campaign id from a wallet',
`bid_value` DOUBLE(11,2)  NOT NULL COMMENT 'Bid value for charging the ad',
`clicks` float NOT NULL COMMENT 'for number of clicks of perticular ad',
`impressions` float NOT NULL COMMENT 'For number of impressions of perticular ad',
PRIMARY KEY (`ad_id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_fields_mapping` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`mapping_id` INT(11)  NOT NULL ,
`mapping_fieldid` INT(11)  NOT NULL COMMENT 'Mapping field id from social sites',
`mapping_fieldtype` VARCHAR(50)  NOT NULL COMMENT 'Field type like date text radio button',
`mapping_label` VARCHAR(100)  NOT NULL COMMENT 'Label for a field',
`mapping_fieldname` VARCHAR(200)  NOT NULL COMMENT 'Name of a mapping field',
`mapping_options` TEXT NOT NULL COMMENT '',
`mapping_category` INT(11)  NOT NULL ,
`mapping_publish` TINYINT(4)  NOT NULL ,
`mapping_check` TINYINT(4)  NOT NULL ,
`mapping_match` TINYINT(4)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_geo_target` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ad_id` INT(11)  NOT NULL COMMENT 'FK to #__ad_data',
`country` TEXT NOT NULL COMMENT 'Country selected for a geo targeting',
`region` TEXT NOT NULL COMMENT 'Region selected for a geo targeting',
`city` TEXT NOT NULL COMMENT 'City selected for a geo targeting',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_ignore` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`adid` INT(11)  NOT NULL COMMENT 'FK to #__ad_data',
`userid` INT(11)  NOT NULL COMMENT 'User who ignore a ad',
`ad_feedback` TEXT NOT NULL COMMENT 'User seleced feedback option to ignore a ad',
`idate` TIMESTAMP NOT NULL COMMENT 'Date on which ad is ignored',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prefix_oid` VARCHAR( 23 ) NOT NULL,
  `cdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Order creation date',
  `mdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Order modification date',
  `payment_info_id` int(11) NOT NULL COMMENT 'Payment id',
  `transaction_id` varchar(100) NOT NULL COMMENT 'Payment transaction id',
  `payee_id` varchar(100) NOT NULL COMMENT 'User who did a payment',
  `amount` float NOT NULL COMMENT 'Amount of a payment',
  `status` varchar(100) NOT NULL COMMENT 'Payment status like confirmed pending, etc',
  `extras` text NOT NULL COMMENT 'Fileds like url from which payment is did, order id, payment status, payment value etc',
  `processor` varchar(100) NOT NULL COMMENT 'Payment gateway',
  `ip_address` varchar(100) NOT NULL COMMENT 'Ip address from which payment is did',
  `comment` varchar(255) NOT NULL COMMENT 'Comment added by user while doing payment',
  `original_amount` float NOT NULL COMMENT 'Amount needs to paid by a user',
  `coupon` varchar(100) NOT NULL COMMENT 'Coupon Id',
  `tax` float(10,2) NOT NULL COMMENT 'Tax if applied',
  `tax_details` text NOT NULL COMMENT 'Infromation about a tax',
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_payment_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT 'FK to #__ad_orders',
  `is_recurring` tinyint(1) NOT NULL COMMENT 'Recurring payment',
  `ad_id` int(11) NOT NULL COMMENT 'FK to #__ad_data',
  `recurring_frequency` varchar(100) DEFAULT NULL COMMENT 'Duration of payment like daily, monthly, etc',
  `recurring_count` int(11) DEFAULT NULL COMMENT 'How many times payment will br done',
  `subscr_id` varchar(100) DEFAULT NULL COMMENT 'Subscription ID of a user',
  `ad_credits_qty` int(11) NOT NULL COMMENT 'COunt of ad credits',
  `comment` text NOT NULL COMMENT 'Comment added for ad payment',
  `cdate` datetime NOT NULL COMMENT 'Payment date',
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_stats` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ad_id` INT(11)  NOT NULL COMMENT 'FK to #__ad_data',
`user_id` INT(11)  NOT NULL COMMENT 'FK to #__ad_users',
`display_type` TINYINT(4)  NOT NULL COMMENT 'Impression - 0 or Click - 1',
`time` TIMESTAMP NOT NULL COMMENT 'Time on which click or impression is done',
`ip_address` VARCHAR(100)  NOT NULL COMMENT 'IP address of a machine from where click or impression is done',
`spent` DECIMAL(11,2)  NOT NULL COMMENT 'Advertisee spent how much time on ad',
`referer` VARCHAR(150)  NOT NULL COMMENT 'Site name where ad is displayed',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_users` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`orderid` INT(11)  NOT NULL COMMENT 'FK to #__ad_orders',
`user_id` INT(11)  NOT NULL COMMENT 'User id in a joomla users table',
`user_email` VARCHAR(255)  NOT NULL COMMENT 'Email ID of user',
`firstname` VARCHAR(250)  NOT NULL COMMENT 'First name of user',
`lastname` VARCHAR(250)  NOT NULL COMMENT 'Last name of user',
`vat_number` VARCHAR(250)  NOT NULL COMMENT 'vat number of user',
`tax_exempt` TINYINT(4)  NOT NULL ,
`country_code` VARCHAR(51)  NOT NULL COMMENT 'Country code of user',
`address` VARCHAR(255)  NOT NULL COMMENT 'Address of user',
`city` VARCHAR(50)  NOT NULL COMMENT 'City of user',
`state_code` VARCHAR(50)  NOT NULL COMMENT 'State code of user',
`zipcode` VARCHAR(255)  NOT NULL COMMENT 'Zip code of user',
`phone` VARCHAR(50)  NOT NULL COMMENT 'Phone number of user',
`approved` TINYINT(1)  NOT NULL COMMENT 'Users state',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__ad_zone` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`zone_name` VARCHAR(100)  NOT NULL,
`orientation` TINYINT(2)  NOT NULL COMMENT 'Orientation for a specific zone Horizontal or Vertical',
`ad_type` VARCHAR(100)  NOT NULL COMMENT 'Type of ad text media or text and media and if zone supports affliate ad',
`max_title` INT(11)  NOT NULL COMMENT 'Maximum letters in ad title',
`max_des` INT(11)  NOT NULL COMMENT 'Maximum letter in description of ad',
`img_width` INT(11)  NOT NULL COMMENT 'Width of ad image',
`img_height` INT(11)  NOT NULL COMMENT 'Height of ad image',
`per_click` FLOAT NOT NULL COMMENT 'Rate for per click',
`per_imp` FLOAT NOT NULL COMMENT 'Rate for per impression',
`per_day` FLOAT NOT NULL COMMENT 'Rate for per day',
`num_ads` INT(11)  NOT NULL COMMENT 'Number of ads in zone',
`layout` VARCHAR(250)  NOT NULL COMMENT 'Layout selected for ad',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
