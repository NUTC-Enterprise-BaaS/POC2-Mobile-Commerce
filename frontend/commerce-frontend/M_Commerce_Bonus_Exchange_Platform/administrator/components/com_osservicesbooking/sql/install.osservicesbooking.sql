CREATE TABLE IF NOT EXISTS `#__app_sch_breaktime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `eid` int(11) NOT NULL DEFAULT '0',
  `start_from` time NOT NULL,
  `end_to` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_custom_breaktime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `bdate` date DEFAULT NULL,
  `bstart` varchar(5) DEFAULT NULL,
  `bend` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `category_photo` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `show_desc` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__app_sch_configuation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(225) DEFAULT NULL,
  `config_value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(100) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

REPLACE INTO `#__app_sch_countries` VALUES (1, 'Afghanistan', 'AF'),
(2, 'Aland islands', 'AX'),
(3, 'Albania', 'AL'),
(4, 'Algeria', 'DZ'),
(5, 'Andorra', 'AD'),
(6, 'Angola', 'AO'),
(7, 'Anguilla', 'AI'),
(8, 'Antigua and Barbuda', 'AG'),
(9, 'Argentina', 'AR'),
(10, 'Armenia', 'AM'),
(11, 'Aruba', 'AW'),
(12, 'Australia', 'AU'),
(13, 'Austria', 'AT'),
(14, 'Azerbaijan', 'AZ'),
(15, 'Bahamas', 'BS'),
(16, 'Bahrain', 'BH'),
(17, 'Bangladesh', 'BD'),
(18, 'Barbados', 'BB'),
(19, 'Belarus', 'BY'),
(20, 'Belgium', 'BE'),
(21, 'Belize', 'BZ'),
(22, 'Benin', 'BJ'),
(23, 'Bermuda', 'BM'),
(24, 'Bhutan', 'BT'),
(25, 'Bolivia', 'BO'),
(26, 'Bosnia and Herzegovina', 'BA'),
(27, 'Botswana', 'BW'),
(28, 'Brazil', 'BR'),
(29, 'Brunei Darussalam', 'BN'),
(30, 'Bulgaria', 'BG'),
(31, 'Burkina Faso', 'BF'),
(32, 'Burundi', 'BI'),
(33, 'Cambodia', 'KH'),
(34, 'Cameroon', 'CM'),
(35, 'Canada', 'CA'),
(36, 'Cape Verde', 'CV'),
(37, 'Central african republic', 'CF'),
(38, 'Chad', 'TD'),
(39, 'Chile', 'CL'),
(40, 'China', 'CN'),
(41, 'Colombia', 'CO'),
(42, 'Comoros', 'KM'),
(43, 'Republic of Congo', 'CG'),
(44, 'The Democratic Republic of the Congo', 'CD'),
(45, 'Costa Rica', 'CR'),
(46, 'Cote d''Ivoire', 'CI'),
(47, 'Croatia', 'HR'),
(48, 'Cuba', 'CU'),
(49, 'Cyprus', 'CY'),
(50, 'Czech Republic', 'CZ'),
(51, 'Denmark', 'DK'),
(52, 'Djibouti', 'DJ'),
(53, 'Dominica', 'DM'),
(54, 'Dominican Republic', 'DO'),
(55, 'Ecuador', 'EC'),
(56, 'Egypt', 'EG'),
(57, 'El salvador', 'SV'),
(58, 'Equatorial Guinea', 'GQ'),
(59, 'Eritrea', 'ER'),
(60, 'Estonia', 'EE'),
(61, 'Ethiopia', 'ET'),
(62, 'Faeroe Islands', 'FO'),
(63, 'Falkland Islands', 'FK'),
(64, 'Fiji', 'FJ'),
(65, 'Finland', 'FI'),
(66, 'France', 'FR'),
(67, 'French Guiana', 'GF'),
(68, 'Gabon', 'GA'),
(69, 'Gambia, the', 'GM'),
(70, 'Georgia', 'GE'),
(71, 'Germany', 'DE'),
(72, 'Ghana', 'GH'),
(73, 'Greece', 'GR'),
(74, 'Greenland', 'GL'),
(75, 'Grenada', 'GD'),
(76, 'Guadeloupe', 'GP'),
(77, 'Guatemala', 'GT'),
(78, 'Guinea', 'GN'),
(79, 'Guinea-Bissau', 'GW'),
(80, 'Guyana', 'GY'),
(81, 'Haiti', 'HT'),
(82, 'Honduras', 'HN'),
(83, 'Hong Kong', 'HK'),
(84, 'Hungary', 'HU'),
(85, 'Iceland', 'IS'),
(86, 'India', 'IN'),
(87, 'Indonesia', 'ID'),
(88, 'Iran', 'IR'),
(89, 'Iraq', 'IQ'),
(90, 'Ireland', 'IE'),
(91, 'Israel', 'IL'),
(92, 'Italy', 'IT'),
(93, 'Jamaica', 'JM'),
(94, 'Japan', 'JP'),
(95, 'Jordan', 'JO'),
(96, 'Kazakhstan', 'KZ'),
(97, 'Kenya', 'KE'),
(98, 'North Korea', 'KP'),
(99, 'South Korea', 'KR'),
(100, 'Kuwait', 'KW'),
(101, 'Kyrgyzstan', 'KG'),
(102, 'Lao People''s Democratic Republic', 'LA'),
(103, 'Latvia', 'LV'),
(104, 'Lebanon', 'LB'),
(105, 'Lesotho', 'LS'),
(106, 'Liberia', 'LR'),
(107, 'Libya', 'LY'),
(108, 'Liechtenstein', 'LI'),
(109, 'Lithuania', 'LT'),
(110, 'Luxembourg', 'LU'),
(111, 'Macedonia', 'MK'),
(112, 'Madagascar', 'MG'),
(113, 'Malawi', 'MW'),
(114, 'Malaysia', 'MY'),
(115, 'Mali', 'ML'),
(116, 'Malta', 'MT'),
(117, 'Martinique', 'MQ'),
(118, 'Mauritania', 'MR'),
(119, 'Mauritius', 'MU'),
(120, 'Mexico', 'MX'),
(121, 'Moldova', 'MD'),
(122, 'Mongolia', 'MN'),
(123, 'Montenegro', 'ME'),
(124, 'Montserrat', 'MS'),
(125, 'Morocco', 'MA'),
(126, 'Mozambique', 'MZ'),
(127, 'Myanmar', 'MM'),
(128, 'Namibia', 'NA'),
(129, 'Nepal', 'NP'),
(130, 'Netherlands', 'NL'),
(131, 'New Caledonia', 'NC'),
(132, 'New Zealand', 'NZ'),
(133, 'Nicaragua', 'NI'),
(134, 'Niger', 'NE'),
(135, 'Nigeria', 'NG'),
(136, 'Norway', 'NO'),
(137, 'Oman', 'OM'),
(138, 'Pakistan', 'PK'),
(139, 'Palau', 'PW'),
(140, 'Palestinian Territories', 'PS'),
(141, 'Panama', 'PA'),
(142, 'Papua New Guinea', 'PG'),
(143, 'Paraguay', 'PY'),
(144, 'Peru', 'PE'),
(145, 'Philippines', 'PH'),
(146, 'Poland', 'PL'),
(147, 'Portugal', 'PT'),
(148, 'Puerto rico', 'PR'),
(149, 'Qatar', 'QA'),
(150, 'Reunion', 'RE'),
(151, 'Romania', 'RO'),
(152, 'Russian Federation', 'RU'),
(153, 'Rwanda', 'RW'),
(154, 'Saint Kitts and Nevis', 'KN'),
(155, 'Saint Lucia', 'LC'),
(156, 'Samoa', 'WS'),
(157, 'Sao Tome and Principe', 'ST'),
(158, 'Saudi Arabia', 'SA'),
(159, 'Senegal', 'SN'),
(160, 'Serbia', 'RS'),
(161, 'Sierra Leone', 'SL'),
(162, 'Singapore', 'SG'),
(163, 'Slovakia', 'SK'),
(164, 'Slovenia', 'SI'),
(165, 'Solomon Islands', 'SB'),
(166, 'Somalia', 'SO'),
(167, 'South Africa', 'ZA'),
(168, 'South Georgia and the South Sandwich Islands', 'GS'),
(169, 'Spain', 'ES'),
(170, 'Sri Lanka', 'LK'),
(171, 'Sudan', 'SD'),
(172, 'Suriname', 'SR'),
(173, 'Svalbard and Jan Mayen', 'SJ'),
(174, 'Swaziland', 'SZ'),
(175, 'Sweden', 'SE'),
(176, 'Switzerland', 'CH'),
(177, 'Syrian Arab Republic', 'SY'),
(178, 'Taiwan', 'TW'),
(179, 'Tajikistan', 'TJ'),
(180, 'Tanzania', 'TZ'),
(181, 'Thailand', 'TH'),
(182, 'Timor-Leste', 'TL'),
(183, 'Togo', 'TG'),
(184, 'Tonga', 'TO'),
(185, 'Trinidad and Tobago', 'TT'),
(186, 'Tunisia', 'TN'),
(187, 'Turkey', 'TR'),
(188, 'Turkmenistan', 'TM'),
(189, 'Turks and Caicos Islands', 'TC'),
(190, 'Uganda', 'UG'),
(191, 'Ukraine', 'UA'),
(192, 'United Arab Emirates', 'AE'),
(193, 'United Kingdom', 'GB'),
(194, 'United States', 'US'),
(195, 'Uruguay', 'UY'),
(196, 'Uzbekistan', 'UZ'),
(197, 'Vanuatu', 'VU'),
(198, 'Venezuela', 'VE'),
(199, 'Viet nam', 'VN'),
(200, 'Virgin Islands, British', 'VG'),
(201, 'Western Sahara', 'EH'),
(202, 'Yemen', 'YE'),
(203, 'Zambia', 'ZM'),
(204, 'Zimbabwe', 'ZW');

CREATE TABLE IF NOT EXISTS `#__app_sch_coupons` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_coupon_used` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(100) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_symbol` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

REPLACE INTO `#__app_sch_currencies` VALUES (1, 'Argentina Peso', 'ARS', '&#36;'),
(2, 'Australia Dollar', 'AUD', '&#36;'),
(3, 'Bahamas Dollar', 'BSD', '&#36;'),
(4, 'Belarus Ruble', 'BYR', '&#112;&#46;'),
(5, 'Bolivia Boliviano', 'BOB', '&#36;&#98;'),
(6, 'Bulgaria Lev', 'BGN', '&#1083;&#1074;'),
(7, 'Brazil Real', 'BRL', '&#82;&#36;'),
(8, 'Brunei Darussalam Dollar', 'BND', '&#36;'),
(10, 'Canada Dollar', 'CAD', '&#36;'),
(11, 'Chile Peso', 'CLP', '&#36;'),
(12, 'China Yuan Renminbi', 'CNY', '&#165;'),
(13, 'Colombia Peso', 'COP', '&#36;'),
(14, 'Cuba Peso', 'CUP', '&#8369;'),
(15, 'Czech Republic Koruna', 'CZK', '&#75;&#269;'),
(16, 'Denmark Krone', 'DKK', '&#107;&#114;'),
(17, 'Egypt Pound', 'EGP', '&#163;'),
(18, 'Euro Member Countries', 'EUR', '&#8364;'),
(19, 'Falkland Islands (Malvinas) Pound', 'FKP', '&#163;'),
(20, 'Fiji Dollar', 'FJD', '&#36;'),
(21, 'Hong Kong Dollar', 'HKD', '&#36;'),
(22, 'Hungary Forint', 'HUF', '&#70;&#116;'),
(23, 'Iceland Krona', 'ISK', '&#107;&#114;'),
(24, 'India Rupee', 'INR', '&#8377;'),
(25, 'Indonesia Rupiah', 'IDR', '&#82;&#112;'),
(27, 'Israel Shekel', 'ILS', '&#8362;'),
(28, 'Japan Yen', 'JPY', '&#165;'),
(29, 'Korea (North) Won', 'KPW', '&#8361;'),
(30, 'Korea (South) Won', 'KRW', '&#8361;'),
(32, 'Malaysia Ringgit', 'MYR', '&#82;&#77;'),
(33, 'Mexico Peso', 'MXN', '&#36;'),
(34, 'Nepal Rupee', 'NPR', '&#8360;'),
(35, 'Netherlands Antilles Guilder', 'ANG', '&#402;'),
(36, 'New Zealand Dollar', 'NZD', '&#36;'),
(37, 'Nicaragua Cordoba', 'NIO', '&#67;&#36;'),
(38, 'Pakistan Rupee', 'PKR', '&#8360;'),
(39, 'Panama Balboa', 'PAB', '&#66;&#47;&#46;'),
(40, 'Paraguay Guarani', 'PYG', '&#71;&#115;'),
(41, 'Peru Nuevo Sol', 'PEN', '&#83;&#47;&#46;'),
(42, 'Philippines Peso', 'PHP', '&#8369;'),
(43, 'Romania New Leu', 'RON', '&#108;&#101;&#105;'),
(44, 'Russia Ruble', 'RUB', '&#1088;&#1091;&#1073'),
(45, 'Saint Helena Pound', 'SHP', '&#163;'),
(46, 'Saudi Arabia Riyal', 'SAR', '&#65020;'),
(47, 'Singapore Dollar', 'SGD', '&#36;'),
(48, 'Solomon Islands Dollar', 'SBD', '&#36;'),
(49, 'South Africa Rand', 'ZAR', '&#82;'),
(50, 'Sri Lanka Rupee', 'LKR', '&#8360;'),
(51, 'Sweden Krona', 'SEK', '&#107;&#114;'),
(52, 'Switzerland Franc', 'CHF', '&#67;&#72;&#70;'),
(53, 'Taiwan New Dollar', 'TWD', '&#78;&#84;&#36;'),
(54, 'Thailand Baht', 'THB', '&#3647;'),
(55, 'Turkey Lira', 'TRY', '&#84;&#76;'),
(56, 'United Kingdom Pound', 'GBP', '&#163;'),
(57, 'United States Dollar', 'USD', '&#36;'),
(58, 'Uzbekistan Som', 'UZS', '&#1083;&#1074;'),
(59, 'Venezuela Bolivar Fuerte', 'VEF', '&#66;&#115; '),
(60, 'Viet Nam Dong', 'VND', '&#8363; '),
(61, 'Zimbabwe Dollar', 'ZWD', '&#90;&#36;');

CREATE TABLE IF NOT EXISTS `#__app_sch_custom_time_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `start_hour` int(2) NOT NULL DEFAULT '0',
  `start_min` int(2) NOT NULL DEFAULT '0',
  `end_hour` int(2) NOT NULL,
  `end_min` int(2) NOT NULL,
  `nslots` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__app_sch_custom_time_slots_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_slot_id` int(11) NOT NULL DEFAULT '0',
  `date_in_week` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_key` varchar(255) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `email_content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `employee_name` varchar(255) DEFAULT NULL,
  `employee_email` varchar(255) DEFAULT NULL,
  `employee_send_email` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `employee_phone` varchar(255) DEFAULT NULL,
  `employee_notes` text,
  `employee_photo` varchar(255) NOT NULL,
  `gusername` varchar(50) NOT NULL DEFAULT '',
  `gcalendarid` varchar(250) NOT NULL DEFAULT '',
  `gpassword` varchar(50) NOT NULL DEFAULT '',
  `client_id` varchar(255) NOT NULL,
  `app_name` varchar(250) NOT NULL,
  `app_email_address` varchar(255) NOT NULL,
  `p12_key_filename` varchar(100) NOT NULL,
  `published` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_employee_extra_cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL DEFAULT '0',
  `start_time` varchar(10) NOT NULL,
  `end_time` varchar(10) NOT NULL,
  `extra_cost` decimal(6,2) NOT NULL,
  `week_date` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__app_sch_employee_rest_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL DEFAULT '0',
  `rest_date` date NOT NULL,
  `rest_date_to` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__app_sch_employee_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `vid` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  `additional_price` decimal(10,2) NOT NULL,
  `mo` tinyint(1) NOT NULL DEFAULT '0',
  `tu` tinyint(1) NOT NULL DEFAULT '0',
  `we` tinyint(1) NOT NULL DEFAULT '0',
  `th` tinyint(1) NOT NULL DEFAULT '0',
  `fr` tinyint(1) NOT NULL DEFAULT '0',
  `sa` tinyint(1) NOT NULL DEFAULT '0',
  `su` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_employee_service_breaktime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `date_in_week` tinyint(1) unsigned DEFAULT NULL,
  `break_from` time DEFAULT NULL,
  `break_to` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_service_custom_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `cstart` date DEFAULT NULL,
  `cend` date DEFAULT NULL,
  `amount` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_service_price_adjustment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `date_in_week` tinyint(1) unsigned DEFAULT NULL,
  `same_as_original` tinyint(1) unsigned DEFAULT '1',
  `price` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_area` tinyint(1) NOT NULL DEFAULT '0',
  `field_type` tinyint(1) NOT NULL DEFAULT '0',
  `field_label` varchar(255) NOT NULL,
  `field_options` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_field_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `fid` int(11) NOT NULL DEFAULT '0',
  `fvalue` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL DEFAULT '0',
  `field_option` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `additional_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_name` varchar(255) DEFAULT NULL,
  `order_email` varchar(255) DEFAULT NULL,
  `dial_code` varchar(10) NOT NULL,
  `order_phone` varchar(50) DEFAULT NULL,
  `order_country` varchar(255) DEFAULT NULL,
  `order_city` varchar(100) DEFAULT NULL,
  `order_state` varchar(100) DEFAULT NULL,
  `order_zip` varchar(20) DEFAULT NULL,
  `order_address` varchar(255) DEFAULT NULL,
  `order_payment` varchar(50) DEFAULT NULL,
  `order_total` decimal(10,2) DEFAULT NULL,
  `order_tax` decimal(10,2) DEFAULT NULL,
  `order_discount` decimal(12,2) NOT NULL,
  `order_final_cost` decimal(10,2) DEFAULT NULL,
  `order_upfront` decimal(10,2) DEFAULT NULL,
  `order_status` char(1) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_lang` varchar(20) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `order_notes` text NOT NULL,
  `order_card_number` varchar(50) NOT NULL,
  `order_card_type` varchar(50) NOT NULL,
  `order_card_expiry_month` int(2) NOT NULL,
  `order_card_expiry_year` int(4) NOT NULL,
  `order_card_holder` varchar(100) NOT NULL,
  `order_cvv_code` varchar(4) NOT NULL,
  `send_email` tinyint(1) NOT NULL DEFAULT '0',
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `bank_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_order_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0',
  `option_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__app_sch_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT '0',
  `end_time` int(11) DEFAULT '0',
  `booking_date` date DEFAULT NULL,
  `additional_information` text NOT NULL,
  `gcalendar_event_id` varchar(255) NOT NULL,
  `nslots` int(11) NOT NULL DEFAULT '0',
  `checked_in` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_order_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0',
  `option_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__app_sch_plugins` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__app_sch_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `service_name` varchar(255) DEFAULT NULL,
  `service_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_length` int(11) unsigned NOT NULL DEFAULT '0',
  `service_before` int(11) unsigned NOT NULL DEFAULT '0',
  `service_after` int(11) unsigned NOT NULL DEFAULT '0',
  `service_total` int(11) unsigned NOT NULL DEFAULT '0',
  `service_description` text,
  `service_photo` varchar(255) NOT NULL,
  `service_time_type` tinyint(1) NOT NULL,
  `early_bird_amount` decimal(5,2) NOT NULL,
  `early_bird_type` tinyint(1) NOT NULL DEFAULT '0',
  `early_bird_days` tinyint(3) NOT NULL,
  `discount_timeslots` tinyint(3) NOT NULL DEFAULT '0',
  `discount_type` tinyint(1) NOT NULL DEFAULT '0',
  `discount_amount` decimal(5,2) NOT NULL,
  `step_in_minutes` tinyint(3) NOT NULL DEFAULT '0',
  `repeat_day` tinyint(1) NOT NULL DEFAULT '0',
  `repeat_week` tinyint(1) NOT NULL DEFAULT '0',
  `repeat_month` tinyint(1) NOT NULL DEFAULT '0',
  `access` tinyint(1) NOT NULL DEFAULT '0',
  `acymailing_list_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_service_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_service_time_custom_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `service_slots` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_temp_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_cookie` varchar(50) DEFAULT NULL,
  `created_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_temp_order_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_temp_order_items` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_temp_temp_order_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_temp_temp_order_items` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_userprofiles` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_working_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worktime_date` varchar(255) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `default_date` tinyint(1) NOT NULL DEFAULT '0',
  `is_day_off` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_working_time_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` text NOT NULL,
  `worktime_date` date NOT NULL,
  `worktime_date_to` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_day_off` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_dialing_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) DEFAULT NULL,
  `dial_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_venues` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__app_sch_venue_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_service_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `avail_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5_key` text,
  `query` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_custom_time_slots_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_slot_id` int(11) NOT NULL DEFAULT '0',
  `date_in_week` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `menu_icon` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `menu_task` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__app_sch_user_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;