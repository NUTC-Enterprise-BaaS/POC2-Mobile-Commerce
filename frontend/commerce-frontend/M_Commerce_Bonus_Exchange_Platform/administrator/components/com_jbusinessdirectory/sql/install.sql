DROP TABLE IF EXISTS `#__jbusinessdirectory_applicationsettings`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_categories`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_companies`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_category`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_contact`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_claim`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_images`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_contact`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offers`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offer_category`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offer_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_ratings`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_review_abuses`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_review_responses`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_criteria`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_user_criteria`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_videos`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_countries`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_currencies`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_date_formats`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_emails`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_packages`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_package_fields`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_orders`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payment_processors`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payment_processor_fields`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attribute_options`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attribute_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payments`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_events`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_event_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_event_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_default_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_cities`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_activity_city`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_reports`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_locations`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_discounts`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_language_translations`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_attachments`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_billing_details`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_bookmarks`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conferences`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_sessions`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_attachments`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_categories`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_companies`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_levels`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_locations`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_speakers`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_session_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_speakers`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_conference_speaker_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_news`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_event_category`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_messages`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_event_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_offer_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offer_coupons`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_question`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_question_answer`;

--
-- Table structure for table `#__jbusinessdirectory_applicationsettings`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_applicationsettings` (
  `applicationsettings_id` int(10) NOT NULL DEFAULT '1',
  `company_name` char(255) NOT NULL,
  `company_email` char(255) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `country_ids` varchar(255) DEFAULT NULL,
  `css_style` char(255) NOT NULL,
  `css_module_style` char(255) NOT NULL,
  `show_frontend_language` tinyint(1) NOT NULL DEFAULT '1',
  `default_frontend_language` char(50) NOT NULL DEFAULT 'en-GB',
  `date_format_id` int(5) NOT NULL,
  `enable_packages` tinyint(1) NOT NULL DEFAULT '0',
  `enable_ratings` tinyint(1) NOT NULL DEFAULT '1',
  `enable_reviews` tinyint(1) NOT NULL DEFAULT '1',
  `enable_offers` tinyint(1) NOT NULL DEFAULT '1',
  `enable_offer_coupons` tinyint(1) NOT NULL DEFAULT '1',
  `enable_events` tinyint(1) NOT NULL DEFAULT '1',
  `enable_seo` tinyint(1) NOT NULL DEFAULT '1',
  `enable_rss` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter` tinyint(1) NOT NULL DEFAULT '1',
  `enable_reviews_users` tinyint(4) NOT NULL DEFAULT '0',
  `enable_socials` tinyint(1) NOT NULL DEFAULT '1',
  `enable_numbering` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter_offers` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter_events` tinyint(1) NOT NULL DEFAULT '1',
  `show_search_map` tinyint(1) NOT NULL DEFAULT '1',
  `show_search_description` tinyint(1) NOT NULL DEFAULT '1',
  `show_details_user` tinyint(1) NOT NULL DEFAULT '0',
  `company_view` tinyint(1) NOT NULL DEFAULT '1',
  `category_view` tinyint(1) NOT NULL DEFAULT '2',
  `search_result_view` tinyint(1) NOT NULL DEFAULT '1',
  `captcha` tinyint(1) NOT NULL DEFAULT '0',
  `nr_images_slide` tinyint(4) NOT NULL DEFAULT '5',
  `show_pending_approval` tinyint(1) NOT NULL DEFAULT '0',
  `allow_multiple_companies` tinyint(1) NOT NULL DEFAULT '1',
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description_facebook` varchar(255) DEFAULT NULL,
  `limit_cities` varchar(1) NOT NULL DEFAULT '0',
  `metric` varchar(1) NOT NULL DEFAULT '1',
  `user_location` varchar(1) NOT NULL DEFAULT '1',
  `search_type` varchar(1) NOT NULL DEFAULT '0',
  `zipcode_search_type` varchar(1) DEFAULT '0',
  `map_auto_show` varchar(1) DEFAULT '0',
  `menu_item_id` varchar(10) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `order_email` varchar(255) DEFAULT NULL,
  `claim_business` varchar(1) DEFAULT '1',
  `terms_conditions` blob,
  `vat` tinyint(4) DEFAULT '0',
  `expiration_day_notice` tinyint(2) DEFAULT NULL,
  `show_cat_description` tinyint(1) DEFAULT NULL,
  `direct_processing` tinyint(1) DEFAULT NULL,
  `max_video` tinyint(2) DEFAULT '10',
  `max_pictures` tinyint(2) DEFAULT '15',
  `show_secondary_locations` tinyint(1) DEFAULT '0',
  `search_view_mode` tinyint(1) DEFAULT '0',
  `address_format` tinyint(1) NOT NULL DEFAULT '1',
  `offer_search_results_grid_view` tinyint(1) DEFAULT '0',
  `enable_multilingual` tinyint(1) NOT NULL DEFAULT '0',
  `offers_view_mode` TINYINT(1) NOT NULL DEFAULT 0,
  `enable_geolocation` tinyint(1) NOT NULL DEFAULT '0',
  `enable_google_map_clustering` tinyint(1) NOT NULL DEFAULT '0',
  `add_url_id` tinyint(1) NOT NULL DEFAULT '0',
  `add_url_language` TINYINT(1) NULL DEFAULT 0,
  `currency_display` tinyint(1) NOT NULL DEFAULT '1',
  `amount_separator` tinyint(1) NOT NULL DEFAULT '1',
  `currency_location` tinyint(1) DEFAULT '1',
  `currency_symbol` varchar(45) DEFAULT NULL,
  `show_email` TINYINT(1) NOT NULL DEFAULT 0,
  `enable_attachments` TINYINT(1) NULL DEFAULT 1,
  `order_search_listings` varchar(45) DEFAULT NULL,
  `order_search_offers` varchar(45) DEFAULT NULL,
  `order_search_events` varchar(45) DEFAULT NULL,
  `events_search_view` tinyint(1) DEFAULT '2',
  `enable_bookmarks` tinyint(1) NOT NULL DEFAULT '1',
  `max_attachments` TINYINT(4) NOT NULL DEFAULT '5',
  `max_categories` TINYINT(4) NOT NULL DEFAULT '10',
  `max_offers` smallint(6) NOT NULL DEFAULT '20',
  `max_events` smallint(6) NOT NULL DEFAULT '20',
  `max_business` smallint(6) NOT NULL DEFAULT '20',
  `time_format` varchar(45) NOT NULL DEFAULT 'H:i:s',
  `front_end_acl` tinyint(1) NOT NULL DEFAULT '0',
  `listing_url_type` TINYINT NOT NULL DEFAULT '1',
  `show_secondary_map_locations` TINYINT(1) NOT NULL DEFAULT 0,
  `search_result_grid_view` TINYINT(1) NOT NULL DEFAULT 1,
  `facebook` VARCHAR(75) DEFAULT NULL,
  `twitter` VARCHAR(75) DEFAULT NULL,
  `googlep` VARCHAR(75) DEFAULT NULL,
  `linkedin` VARCHAR(75) DEFAULT NULL,
  `youtube` VARCHAR(75) DEFAULT NULL,
  `logo` VARCHAR(145) DEFAULT NULL,
  `map_latitude` varchar(45) DEFAULT NULL,
  `map_longitude` varchar(45) DEFAULT NULL,
  `map_zoom` TINYINT(4) NOT NULL DEFAULT '15',
  `map_enable_auto_locate` tinyint(1) NOT NULL DEFAULT '1',
  `map_apply_search` tinyint(1) NOT NULL DEFAULT '0',
  `google_map_key` VARCHAR(45) NULL,
  `submit_method` varchar(5) DEFAULT 'post',
  `add_country_address` tinyint(1) NOT NULL DEFAULT '1',
  `usergroup` int(11) NOT NULL DEFAULT '2',
  `category_url_type` tinyint(1) NOT NULL DEFAULT '1',
  `enable_menu_alias_url` tinyint(1) NOT NULL DEFAULT '0',
  `adaptive_height_gallery` tinyint(1) NOT NULL DEFAULT '0',
  `autoplay_gallery` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_company_name` varchar(100) NOT NULL,
  `invoice_company_address` varchar(75) DEFAULT NULL,
  `invoice_company_phone` varchar(75) DEFAULT NULL,
  `invoice_company_email` varchar(75) NOT NULL,
  `invoice_vat` varchar(75) DEFAULT '0',
  `invoice_details` text,
  `show_total_business_count`tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`applicationsettings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_applicationsettings`
--

INSERT INTO `#__jbusinessdirectory_applicationsettings` (`applicationsettings_id`, `company_name`, `company_email`, `currency_id`, `css_style`, `css_module_style`, `show_frontend_language`, `default_frontend_language`, `date_format_id`, `enable_packages`, `enable_ratings`, `enable_reviews`, `enable_offers`, `enable_events`, `enable_seo`, `enable_search_filter`, `enable_reviews_users`, `enable_numbering`, `enable_search_filter_offers`, `enable_search_filter_events`, `show_search_map`, `show_search_description`, `show_details_user`, `company_view`, `category_view`, `search_result_view`, `captcha`, `nr_images_slide`, `show_pending_approval`, `allow_multiple_companies`, `meta_description`, `meta_keywords`, `meta_description_facebook`, `limit_cities`, `metric`, `user_location`, `search_type`, `zipcode_search_type`, `map_auto_show`, `menu_item_id`, `order_id`, `order_email`, `claim_business`, `terms_conditions`, `vat`, `expiration_day_notice`, `show_cat_description`, `direct_processing`, `max_video`, `max_pictures`, `show_secondary_locations`, `search_view_mode`, `address_format`, `offer_search_results_grid_view`, `enable_multilingual`, `offers_view_mode`, `enable_geolocation`, `add_url_id`, `currency_display`, `amount_separator`, `currency_location`, `currency_symbol`, `show_email`, `enable_attachments`, `add_country_address`, `facebook`, `twitter`, `googlep`, `linkedin`, `youtube`, `logo`) VALUES
(1, 'JBusinessDirectory', 'office@site.com', 143, '', 'style.css', 1, 'en-GB', 2, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 3, 3, 1, 0, 5, 0, 1, '', '', '', '0', '1', '1', '0', '0', '0', '', NULL, NULL, '1', '', 0, 0, 1, 0, 10, 15, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, '', 0, 1, 1, 'http://www.facebook.com', 'http://www.twiter.com', 'http://www.googleplus.com', 'http://www.linkedin.com', 'http://www.youtube.com', '/companies/mydirectory-logo-1444760776.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `is_mandatory` int(1) NOT NULL DEFAULT '0',
  `show_in_filter` int(1) NOT NULL DEFAULT '1',
  `show_in_front` tinyint(1) NOT NULL DEFAULT '0',
  `show_on_search` tinyint(1) NOT NULL,
  `ordering` tinyint(4) NOT NULL DEFAULT '0',
  `attribute_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_options`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_types` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_attribute_types`
--

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES
(1, 'input', 'Input'),
(2, 'select_box', 'Select Box'),
(3, 'checkbox', 'Checkbox(Multiple Select)'),
(4, 'radio', 'Radio(Single Select)'),
(5, 'header', 'Header'),
(6, 'textarea', 'Textarea'),
(7, 'link', 'Link');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_billing_details`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_billing_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `company_name` varchar(55) DEFAULT NULL,
  `address` varchar(55) DEFAULT NULL,
  `postal_code` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_bookmarks`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;


--
-- Table structure for table `#__jbusinessdirectory_categories`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT NULL,
  `published` tinyint(4) NOT NULL,
  `imageLocation` varchar(250) DEFAULT NULL,
  `markerLocation` varchar(250) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `clickCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_alias` (`alias`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_name` (`name`),
  KEY `idx_state` (`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_categories`
--

INSERT INTO `#__jbusinessdirectory_categories` (`id`, `parent_id`, `lft`, `rgt`, `level`, `type`, `name`, `alias`, `description`, `published`, `imageLocation`, `markerLocation`, `color`, `icon`, `path`, `clickCount`) VALUES
(1, 0, 0, 410, 0, 0, 'root', '', '1', 0, '', '', NULL, '0', NULL, 0),
(12, 1, 31, 32, 1, 1, 'Books', 'books-1-1', '', 1, '/categories/image5-1427100316.jpg', '/categories/marker_book-1411754028.png', '', 'book', 'books-1-1', 0),
(34, 7, 83, 84, 2, 1, 'Software', 'software', '', 1, '', NULL, NULL, NULL, 'electronics-1/software', 0),
(35, 8, 129, 130, 2, 1, 'Women', 'women', '', 1, '', NULL, NULL, NULL, 'fashion-1/women', 0),
(36, 8, 125, 126, 2, 1, 'Man', 'man', '', 1, '', NULL, NULL, NULL, 'fashion-1/man', 0),
(37, 8, 123, 124, 2, 1, 'Kids & Baby', 'kids-&-baby', '', 1, '', NULL, NULL, NULL, 'fashion-1/kids-&-baby', 0),
(38, 8, 127, 128, 2, 1, 'Shoes', 'shoes', '', 1, '', NULL, NULL, NULL, 'fashion-1/shoes', 0),
(39, 29, 153, 154, 2, 1, 'Grocery & Gourmet Food', 'grocery-&-gourmet-food', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty-1/grocery-&-gourmet-food', 0),
(40, 29, 159, 160, 2, 1, 'Wine', 'wine', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty-1/wine', 0),
(41, 29, 157, 158, 2, 1, 'Natural & Organic', 'natural-&-organic', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty-1/natural-&-organic', 0),
(42, 29, 155, 156, 2, 1, 'Health & Personal Care', 'health-&-personal-care', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty-1/health-&-personal-care', 0),
(43, 11, 189, 190, 2, 1, 'Kitchen & Dining', 'kitchen-&-dining', '', 1, '', NULL, NULL, NULL, 'home-garden-1/kitchen-&-dining', 0),
(44, 11, 187, 188, 2, 1, 'Furniture & D', 'furniture-&-d', '', 1, '', NULL, NULL, NULL, 'home-garden-1/furniture-&-d', 0),
(45, 11, 185, 186, 2, 1, 'Bedding & Bath', 'bedding-&-bath', '', 1, '', NULL, NULL, NULL, 'home-garden-1/bedding-&-bath', 0),
(46, 11, 191, 192, 2, 1, 'Patio, Lawn & Garden', 'patio,-lawn-&-garden', '', 1, '', NULL, NULL, NULL, 'home-garden-1/patio,-lawn-&-garden', 0),
(47, 11, 183, 184, 2, 1, 'Arts, Crafts & Sewing', 'arts,-crafts-&-sewing', '', 1, '', NULL, NULL, NULL, 'home-garden-1/arts,-crafts-&-sewing', 0),
(48, 30, 227, 228, 2, 1, 'Watches', 'watches', '', 1, '', NULL, NULL, NULL, 'jewelry/watches', 0),
(49, 30, 225, 226, 2, 1, 'Fine Jewelry', 'fine-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry/fine-jewelry', 0),
(51, 30, 221, 222, 2, 1, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry/fashion-jewelry', 0),
(52, 30, 223, 224, 2, 1, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry/fashion-jewelry', 0),
(53, 30, 219, 220, 2, 1, 'Engagement & Wedding', 'engagement-&-wedding', '', 1, '', NULL, NULL, NULL, 'jewelry/engagement-&-wedding', 0),
(54, 10, 259, 260, 2, 1, 'Movies & TV', 'movies-&-tv', '', 1, '', NULL, NULL, NULL, 'movies-music-games-1/movies-&-tv', 0),
(55, 10, 255, 256, 2, 1, 'Blu-ray', 'blu-ray', '', 1, '', NULL, NULL, NULL, 'movies-music-games-1/blu-ray', 0),
(30, 1, 217, 218, 1, 1, 'Jewelry', 'jewelry', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/watch-1427100687.jpg', '/categories/marker_electronics-1411754433.png', '', 'heart', 'jewelry', 0),
(5, 1, 337, 338, 1, 1, 'Sports & Outdors', 'sports-outdors-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image3-1411751531.jpg', '/categories/marker_sport-1411754446.png', '', 'male', 'sports-outdors-1', 0),
(31, 7, 75, 76, 2, 1, 'Cell Phones & Accessories', 'cell-phones-&-accessories', '', 1, '', NULL, NULL, NULL, 'electronics-1/cell-phones-&-accessories', 0),
(7, 1, 73, 74, 1, 1, 'Electronics', 'electronics-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image6-1427100355.jpg', '/categories/marker_electronics-1411754102.png', '', 'laptop', 'electronics-1', 0),
(8, 1, 121, 122, 1, 1, 'Fashion', 'fashion-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/slide-image-8-1427100458.jpg', '/categories/marker_mask-1411754410.png', '', 'gift', 'fashion-1', 0),
(9, 1, 373, 374, 1, 1, 'Toy,Kids & Babies', 'toy-kids-babies-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image1-1427100760.jpg', '/categories/marker_electronics-1411754456.png', '', 'cab', 'toy-kids-babies-1', 0),
(10, 1, 253, 254, 1, 1, 'Movies, Music & Games', 'movies-music-games-1', '', 1, '/categories/image2-1427100712.jpg', '/categories/marker_music-1411754173.png', '', 'music', 'movies-music-games-1', 0),
(11, 1, 181, 182, 1, 1, 'Home & Garden', 'home-garden-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100616.jpg', '/categories/marker_home-1411754161.png', '', 'home', 'home-garden-1', 0),
(32, 7, 87, 88, 2, 1, 'Video Games', 'video-games', '', 1, '', NULL, NULL, NULL, 'electronics-1/video-games', 0),
(33, 7, 77, 78, 2, 1, 'Computer Parts & Components', 'computer-parts-&-components', '', 1, '', NULL, NULL, NULL, 'electronics-1/computer-parts-&-components', 0),
(28, 7, 79, 80, 2, 1, 'Electronics Accessories', 'electronics-accessories', '', 1, '', NULL, NULL, NULL, 'electronics-1/electronics-accessories', 0),
(26, 7, 81, 82, 2, 1, 'Home, Audio & Theater', 'home,-audio-&-theater', '', 1, '', NULL, NULL, NULL, 'electronics-1/home,-audio-&-theater', 0),
(25, 7, 85, 86, 2, 1, 'TV ', 'tv-', '', 1, '', NULL, NULL, NULL, 'electronics-1/tv-', 0),
(24, 13, 59, 60, 2, 1, 'Photography', 'photography-1', '', 1, '', '', '', 'camera-retro', 'camera-photography-1/photography-1', 0),
(23, 13, 57, 58, 2, 1, 'Camera', 'camera', '', 1, '', NULL, NULL, NULL, 'camera-photography-1/camera', 0),
(21, 12, 37, 38, 2, 1, 'Textbooks', 'textbooks', '', 1, '', NULL, NULL, NULL, 'books-1-1/textbooks', 0),
(22, 12, 35, 36, 2, 1, 'Children''s Books', 'children''s-books', '', 1, '', NULL, NULL, NULL, 'books-1-1/children''s-books', 0),
(19, 12, 33, 34, 2, 1, 'Books', 'books', '', 1, '', NULL, NULL, NULL, 'books-1-1/books', 0),
(18, 74, 9, 10, 2, 1, 'Tires ', 'tires-', '', 1, '', NULL, NULL, NULL, 'automotive-motors-1/tires-', 0),
(17, 74, 7, 8, 2, 1, 'Car Electronics', 'car-electronics', '', 1, '', NULL, NULL, NULL, 'automotive-motors-1/car-electronics', 0),
(16, 74, 5, 6, 2, 1, 'Automotive Tools', 'automotive-tools', '', 1, '', NULL, NULL, NULL, 'automotive-motors-1/automotive-tools', 0),
(14, 74, 3, 4, 2, 1, 'Automotive Parts', 'automotive-parts', '', 1, '', NULL, NULL, NULL, 'automotive-motors-1/automotive-parts', 0),
(13, 1, 55, 56, 1, 1, 'Camera & Photography', 'camera-photography-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100335.jpg', '/categories/marker_photo-1411754091.png', '', 'camera', 'camera-photography-1', 0),
(56, 10, 263, 264, 2, 1, 'Musical Instruments', 'musical-instruments', '', 1, '', NULL, NULL, NULL, 'movies-music-games-1/musical-instruments', 0),
(57, 10, 261, 262, 2, 1, 'MP3 Downloads', 'mp3-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games-1/mp3-downloads', 0),
(58, 10, 257, 258, 2, 1, 'Game Downloads', 'game-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games-1/game-downloads', 0),
(59, 5, 341, 342, 2, 1, 'Exercise & Fitness', 'exercise-&-fitness', '', 1, '', NULL, NULL, NULL, 'sports-outdors-1/exercise-&-fitness', 0),
(60, 5, 345, 346, 2, 1, 'Outdoor Recreation', 'outdoor-recreation', '', 1, '', NULL, NULL, NULL, 'sports-outdors-1/outdoor-recreation', 0),
(61, 5, 343, 344, 2, 1, 'Hunting & Fishing', 'hunting-&-fishing', '', 1, '', NULL, NULL, NULL, 'sports-outdors-1/hunting-&-fishing', 0),
(62, 5, 339, 340, 2, 1, 'Cycling', 'cycling', '', 1, '', NULL, NULL, NULL, 'sports-outdors-1/cycling', 0),
(63, 5, 347, 348, 2, 1, 'Team Sports', 'team-sports', '', 1, '', NULL, NULL, NULL, 'sports-outdors-1/team-sports', 0),
(64, 9, 381, 382, 2, 1, 'Toys & Games', 'toys-&-games', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies-1/toys-&-games', 0),
(65, 9, 375, 376, 2, 1, 'Baby', 'baby', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies-1/baby', 0),
(66, 9, 379, 380, 2, 1, 'Clothing (Kids & Baby)', 'clothing-(kids-&-baby)', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies-1/clothing-(kids-&-baby)', 0),
(67, 9, 383, 384, 2, 1, 'Video Games for Kids', 'video-games-for-kids', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies-1/video-games-for-kids', 0),
(68, 9, 377, 378, 2, 1, 'Baby Registry', 'baby-registry', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies-1/baby-registry', 0),
(69, 3, 323, 324, 2, 1, 'Services', 'services', '', 1, '', NULL, NULL, NULL, 'services-1/services', 0),
(70, 3, 321, 322, 2, 1, 'IT Services', 'it-services', '', 1, '', NULL, NULL, NULL, 'services-1/it-services', 0),
(29, 1, 151, 152, 1, 1, 'Health & Beauty', 'grocery-health-beauty-1', '', 1, '/categories/slide1-the-health-and-beauty-world-1427100497.jpg', '/categories/marker_health-1411754146.png', '', 'heart', 'grocery-health-beauty-1', 0),
(3, 1, 319, 320, 1, 1, 'Services', 'services-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image9-1411751440.png', '/categories/marker_service-1411754187.png', '', 'area-chart', 'services-1', 0),
(74, 1, 1, 2, 1, 1, 'Automotive & Motors', 'automotive-motors-1', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image5-1427100860.jpg', '/categories/marker_auto-1411754020.png', '', 'car', 'automotive-motors-1', 0),
(75, 1, 295, 296, 1, 1, 'Restaurants', 'restaurants-1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur. Mauris c', 1, '/categories/image3-1427100807.jpg', '', '', 'cutlery', 'restaurants-1', 0),
(76, 75, 297, 298, 2, 1, 'Asian Restaurants', 'asian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants-1/asian-restaurants', 0),
(77, 75, 299, 300, 2, 1, 'French Restaurants', 'french-restaurants', '', 1, '', '', NULL, NULL, 'restaurants-1/french-restaurants', 0),
(78, 75, 301, 302, 2, 1, 'Italian Restaurants', 'italian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants-1/italian-restaurants', 0),
(79, 1, 289, 290, 1, 1, 'Real Estate', 'real-estate-1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '/categories/image9-1427100841.jpg', '', '', 'building', 'real-estate-1', 0),
(80, 1, 39, 40, 1, 2, 'Books', 'books-1', '', 1, '/categories/image5-1427100316.jpg', '/categories/marker_book-1411754028.png', NULL, NULL, 'books-1', 0),
(81, 105, 99, 100, 2, 2, 'Software', 'software', '', 1, '', NULL, NULL, NULL, 'electronics/software', 0),
(82, 106, 139, 140, 2, 2, 'Women', 'women', '', 1, '', NULL, NULL, NULL, 'fashion/women', 0),
(83, 106, 135, 136, 2, 2, 'Man', 'man', '', 1, '', NULL, NULL, NULL, 'fashion/man', 0),
(84, 106, 133, 134, 2, 2, 'Kids & Baby', 'kids-&-baby', '', 1, '', NULL, NULL, NULL, 'fashion/kids-&-baby', 0),
(85, 106, 137, 138, 2, 2, 'Shoes', 'shoes', '', 1, '', NULL, NULL, NULL, 'fashion/shoes', 0),
(86, 140, 163, 164, 2, 2, 'Grocery & Gourmet Food', 'grocery-&-gourmet-food', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/grocery-&-gourmet-food', 0),
(87, 140, 169, 170, 2, 2, 'Wine', 'wine', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/wine', 0),
(88, 140, 167, 168, 2, 2, 'Natural & Organic', 'natural-&-organic', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/natural-&-organic', 0),
(89, 140, 165, 166, 2, 2, 'Health & Personal Care', 'health-&-personal-care', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/health-&-personal-care', 0),
(90, 109, 201, 202, 2, 2, 'Kitchen & Dining', 'kitchen-&-dining', '', 1, '', NULL, NULL, NULL, 'home-garden/kitchen-&-dining', 0),
(91, 109, 199, 200, 2, 2, 'Furniture & D', 'furniture-&-d', '', 1, '', NULL, NULL, NULL, 'home-garden/furniture-&-d', 0),
(92, 109, 197, 198, 2, 2, 'Bedding & Bath', 'bedding-&-bath', '', 1, '', NULL, NULL, NULL, 'home-garden/bedding-&-bath', 0),
(93, 109, 203, 204, 2, 2, 'Patio, Lawn & Garden', 'patio,-lawn-&-garden', '', 1, '', NULL, NULL, NULL, 'home-garden/patio,-lawn-&-garden', 0),
(94, 109, 195, 196, 2, 2, 'Arts, Crafts & Sewing', 'arts,-crafts-&-sewing', '', 1, '', NULL, NULL, NULL, 'home-garden/arts,-crafts-&-sewing', 0),
(95, 102, 239, 240, 2, 2, 'Watches', 'watches', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/watches', 0),
(96, 102, 237, 238, 2, 2, 'Fine Jewelry', 'fine-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fine-jewelry', 0),
(97, 102, 233, 234, 2, 2, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fashion-jewelry', 0),
(98, 102, 235, 236, 2, 2, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fashion-jewelry', 0),
(99, 102, 231, 232, 2, 2, 'Engagement & Wedding', 'engagement-&-wedding', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/engagement-&-wedding', 0),
(100, 108, 271, 272, 2, 2, 'Movies & TV', 'movies-&-tv', '', 1, '', NULL, NULL, NULL, 'movies-music-games/movies-&-tv', 0),
(101, 108, 267, 268, 2, 2, 'Blu-ray', 'blu-ray', '', 1, '', NULL, NULL, NULL, 'movies-music-games/blu-ray', 0),
(102, 1, 229, 230, 1, 2, 'Jewelry & Watches', 'jewelry-watches', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/watch-1427100687.jpg', '/categories/marker_electronics-1411754433.png', NULL, NULL, 'jewelry-watches', 0),
(103, 1, 349, 350, 1, 2, 'Sports & Outdors', 'sports-outdors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image3-1411751531.jpg', '/categories/marker_sport-1411754446.png', NULL, NULL, 'sports-outdors', 0),
(104, 105, 91, 92, 2, 2, 'Cell Phones & Accessories', 'cell-phones-&-accessories', '', 1, '', NULL, NULL, NULL, 'electronics/cell-phones-&-accessories', 0),
(105, 1, 89, 90, 1, 2, 'Electronics', 'electronics', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image6-1427100355.jpg', '/categories/marker_electronics-1411754102.png', NULL, NULL, 'electronics', 0),
(106, 1, 131, 132, 1, 2, 'Fashion', 'fashion', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/slide-image-8-1427100458.jpg', '/categories/marker_mask-1411754410.png', NULL, NULL, 'fashion', 0),
(107, 1, 385, 386, 1, 2, 'Toy,Kids & Babies', 'toy-kids-babies', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image1-1427100760.jpg', '/categories/marker_electronics-1411754456.png', NULL, NULL, 'toy-kids-babies', 0),
(108, 1, 265, 266, 1, 2, 'Movies, Music & Games', 'movies-music-games', '', 1, '/categories/image2-1427100712.jpg', '/categories/marker_music-1411754173.png', NULL, NULL, 'movies-music-games', 0),
(109, 1, 193, 194, 1, 2, 'Home & Garden', 'home-garden', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100616.jpg', '/categories/marker_home-1411754161.png', NULL, NULL, 'home-garden', 0),
(110, 105, 103, 104, 2, 2, 'Video Games', 'video-games', '', 1, '', NULL, NULL, NULL, 'electronics/video-games', 0),
(111, 105, 93, 94, 2, 2, 'Computer Parts & Components', 'computer-parts-&-components', '', 1, '', NULL, NULL, NULL, 'electronics/computer-parts-&-components', 0),
(112, 105, 95, 96, 2, 2, 'Electronics Accessories', 'electronics-accessories', '', 1, '', NULL, NULL, NULL, 'electronics/electronics-accessories', 0),
(113, 105, 97, 98, 2, 2, 'Home, Audio & Theater', 'home,-audio-&-theater', '', 1, '', NULL, NULL, NULL, 'electronics/home,-audio-&-theater', 0),
(114, 105, 101, 102, 2, 2, 'TV ', 'tv-', '', 1, '', NULL, NULL, NULL, 'electronics/tv-', 0),
(115, 124, 65, 66, 2, 2, 'Photography', 'photography', '', 1, '', NULL, NULL, NULL, 'camera-photography/photography', 0),
(116, 124, 63, 64, 2, 2, 'Camera', 'camera', '', 1, '', NULL, NULL, NULL, 'camera-photography/camera', 0),
(117, 80, 45, 46, 2, 2, 'Textbooks', 'textbooks', '', 1, '', NULL, NULL, NULL, 'books-1/textbooks', 0),
(118, 80, 43, 44, 2, 2, 'Children''s Books', 'children''s-books', '', 1, '', NULL, NULL, NULL, 'books-1/children''s-books', 0),
(119, 80, 41, 42, 2, 2, 'Books', 'books', '', 1, '', NULL, NULL, NULL, 'books-1/books', 0),
(120, 142, 19, 20, 2, 2, 'Tires ', 'tires-', '', 1, '', NULL, NULL, NULL, 'automotive-motors/tires-', 0),
(121, 142, 17, 18, 2, 2, 'Car Electronics', 'car-electronics', '', 1, '', NULL, NULL, NULL, 'automotive-motors/car-electronics', 0),
(122, 142, 15, 16, 2, 2, 'Automotive Tools', 'automotive-tools', '', 1, '', NULL, NULL, NULL, 'automotive-motors/automotive-tools', 0),
(123, 142, 13, 14, 2, 2, 'Automotive Parts', 'automotive-parts', '', 1, '', NULL, NULL, NULL, 'automotive-motors/automotive-parts', 0),
(124, 1, 61, 62, 1, 2, 'Camera & Photography', 'camera-photography', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100335.jpg', '/categories/marker_photo-1411754091.png', NULL, NULL, 'camera-photography', 0),
(125, 108, 275, 276, 2, 2, 'Musical Instruments', 'musical-instruments', '', 1, '', NULL, NULL, NULL, 'movies-music-games/musical-instruments', 0),
(126, 108, 273, 274, 2, 2, 'MP3 Downloads', 'mp3-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games/mp3-downloads', 0),
(127, 108, 269, 270, 2, 2, 'Game Downloads', 'game-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games/game-downloads', 0),
(128, 103, 353, 354, 2, 2, 'Exercise & Fitness', 'exercise-&-fitness', '', 1, '', NULL, NULL, NULL, 'sports-outdors/exercise-&-fitness', 0),
(129, 103, 357, 358, 2, 2, 'Outdoor Recreation', 'outdoor-recreation', '', 1, '', NULL, NULL, NULL, 'sports-outdors/outdoor-recreation', 0),
(130, 103, 355, 356, 2, 2, 'Hunting & Fishing', 'hunting-&-fishing', '', 1, '', NULL, NULL, NULL, 'sports-outdors/hunting-&-fishing', 0),
(131, 103, 351, 352, 2, 2, 'Cycling', 'cycling', '', 1, '', NULL, NULL, NULL, 'sports-outdors/cycling', 0),
(132, 103, 359, 360, 2, 2, 'Team Sports', 'team-sports', '', 1, '', NULL, NULL, NULL, 'sports-outdors/team-sports', 0),
(133, 107, 393, 394, 2, 2, 'Toys & Games', 'toys-&-games', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/toys-&-games', 0),
(134, 107, 387, 388, 2, 2, 'Baby', 'baby', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/baby', 0),
(135, 107, 391, 392, 2, 2, 'Clothing (Kids & Baby)', 'clothing-(kids-&-baby)', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/clothing-(kids-&-baby)', 0),
(136, 107, 395, 396, 2, 2, 'Video Games for Kids', 'video-games-for-kids', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/video-games-for-kids', 0),
(137, 107, 389, 390, 2, 2, 'Baby Registry', 'baby-registry', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/baby-registry', 0),
(138, 141, 329, 330, 2, 2, 'Services', 'services', '', 1, '', NULL, NULL, NULL, 'services/services', 0),
(139, 141, 327, 328, 2, 2, 'IT Services', 'it-services', '', 1, '', NULL, NULL, NULL, 'services/it-services', 0),
(140, 1, 161, 162, 1, 2, 'Health & Beauty', 'grocery-health-beauty', '', 1, '/categories/slide1-the-health-and-beauty-world-1427100497.jpg', '/categories/marker_health-1411754146.png', NULL, NULL, 'grocery-health-beauty', 0),
(141, 1, 325, 326, 1, 2, 'Services', 'services', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image9-1411751440.png', '/categories/marker_service-1411754187.png', NULL, NULL, 'services', 0),
(142, 1, 11, 12, 1, 2, 'Automotive & Motors', 'automotive-motors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image5-1427100860.jpg', '/categories/marker_auto-1411754020.png', NULL, NULL, 'automotive-motors', 0),
(143, 1, 303, 304, 1, 2, 'Restaurants', 'restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur. Mauris c', 1, '/categories/image3-1427100807.jpg', '', NULL, NULL, 'restaurants', 0),
(144, 143, 305, 306, 2, 2, 'Asian Restaurants', 'asian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants/asian-restaurants', 0),
(145, 143, 307, 308, 2, 2, 'French Restaurants', 'french-restaurants', '', 1, '', '', NULL, NULL, 'restaurants/french-restaurants', 0),
(146, 143, 309, 310, 2, 2, 'Italian Restaurants', 'italian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants/italian-restaurants', 0),
(147, 1, 291, 292, 1, 2, 'Real Estate', 'real-estate', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '/categories/image9-1427100841.jpg', '', NULL, NULL, 'real-estate', 0),
(148, 1, 47, 48, 1, 3, 'Books', 'books-1', '', 1, '/categories/image5-1427100316.jpg', '/categories/marker_book-1411754028.png', NULL, NULL, 'books-1', 0),
(149, 173, 115, 116, 2, 3, 'Software', 'software', '', 1, '', NULL, NULL, NULL, 'electronics/software', 0),
(150, 174, 149, 150, 2, 3, 'Women', 'women', '', 1, '', NULL, NULL, NULL, 'fashion/women', 0),
(151, 174, 145, 146, 2, 3, 'Man', 'man', '', 1, '', NULL, NULL, NULL, 'fashion/man', 0),
(152, 174, 143, 144, 2, 3, 'Kids & Baby', 'kids-&-baby', '', 1, '', NULL, NULL, NULL, 'fashion/kids-&-baby', 0),
(153, 174, 147, 148, 2, 3, 'Shoes', 'shoes', '', 1, '', NULL, NULL, NULL, 'fashion/shoes', 0),
(154, 208, 173, 174, 2, 3, 'Grocery & Gourmet Food', 'grocery-&-gourmet-food', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/grocery-&-gourmet-food', 0),
(155, 208, 179, 180, 2, 3, 'Wine', 'wine', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/wine', 0),
(156, 208, 177, 178, 2, 3, 'Natural & Organic', 'natural-&-organic', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/natural-&-organic', 0),
(157, 208, 175, 176, 2, 3, 'Health & Personal Care', 'health-&-personal-care', '', 1, '', NULL, NULL, NULL, 'grocery-health-beauty/health-&-personal-care', 0),
(158, 177, 213, 214, 2, 3, 'Kitchen & Dining', 'kitchen-&-dining', '', 1, '', NULL, NULL, NULL, 'home-garden/kitchen-&-dining', 0),
(159, 177, 211, 212, 2, 3, 'Furniture & D', 'furniture-&-d', '', 1, '', NULL, NULL, NULL, 'home-garden/furniture-&-d', 0),
(160, 177, 209, 210, 2, 3, 'Bedding & Bath', 'bedding-&-bath', '', 1, '', NULL, NULL, NULL, 'home-garden/bedding-&-bath', 0),
(161, 177, 215, 216, 2, 3, 'Patio, Lawn & Garden', 'patio,-lawn-&-garden', '', 1, '', NULL, NULL, NULL, 'home-garden/patio,-lawn-&-garden', 0),
(162, 177, 207, 208, 2, 3, 'Arts, Crafts & Sewing', 'arts,-crafts-&-sewing', '', 1, '', NULL, NULL, NULL, 'home-garden/arts,-crafts-&-sewing', 0),
(163, 170, 251, 252, 2, 3, 'Watches', 'watches', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/watches', 0),
(164, 170, 249, 250, 2, 3, 'Fine Jewelry', 'fine-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fine-jewelry', 0),
(165, 170, 245, 246, 2, 3, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fashion-jewelry', 0),
(166, 170, 247, 248, 2, 3, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/fashion-jewelry', 0),
(167, 170, 243, 244, 2, 3, 'Engagement & Wedding', 'engagement-&-wedding', '', 1, '', NULL, NULL, NULL, 'jewelry-watches/engagement-&-wedding', 0),
(168, 176, 283, 284, 2, 3, 'Movies & TV', 'movies-&-tv', '', 1, '', NULL, NULL, NULL, 'movies-music-games/movies-&-tv', 0),
(169, 176, 279, 280, 2, 3, 'Blu-ray', 'blu-ray', '', 1, '', NULL, NULL, NULL, 'movies-music-games/blu-ray', 0),
(170, 1, 241, 242, 1, 3, 'Jewelry & Watches', 'jewelry-watches', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/watch-1427100687.jpg', '/categories/marker_electronics-1411754433.png', NULL, NULL, 'jewelry-watches', 0),
(171, 1, 361, 362, 1, 3, 'Sports & Outdors', 'sports-outdors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image3-1411751531.jpg', '/categories/marker_sport-1411754446.png', NULL, NULL, 'sports-outdors', 0),
(172, 173, 107, 108, 2, 3, 'Cell Phones & Accessories', 'cell-phones-&-accessories', '', 1, '', NULL, NULL, NULL, 'electronics/cell-phones-&-accessories', 0),
(173, 1, 105, 106, 1, 3, 'Electronics', 'electronics', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image6-1427100355.jpg', '/categories/marker_electronics-1411754102.png', NULL, NULL, 'electronics', 0),
(174, 1, 141, 142, 1, 3, 'Fashion', 'fashion', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/slide-image-8-1427100458.jpg', '/categories/marker_mask-1411754410.png', NULL, NULL, 'fashion', 0),
(175, 1, 397, 398, 1, 3, 'Toy,Kids & Babies', 'toy-kids-babies', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image1-1427100760.jpg', '/categories/marker_electronics-1411754456.png', NULL, NULL, 'toy-kids-babies', 0),
(176, 1, 277, 278, 1, 3, 'Movies, Music & Games', 'movies-music-games', '', 1, '/categories/image2-1427100712.jpg', '/categories/marker_music-1411754173.png', NULL, NULL, 'movies-music-games', 0),
(177, 1, 205, 206, 1, 3, 'Home & Garden', 'home-garden', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100616.jpg', '/categories/marker_home-1411754161.png', NULL, NULL, 'home-garden', 0),
(178, 173, 119, 120, 2, 3, 'Video Games', 'video-games', '', 1, '', NULL, NULL, NULL, 'electronics/video-games', 0),
(179, 173, 109, 110, 2, 3, 'Computer Parts & Components', 'computer-parts-&-components', '', 1, '', NULL, NULL, NULL, 'electronics/computer-parts-&-components', 0),
(180, 173, 111, 112, 2, 3, 'Electronics Accessories', 'electronics-accessories', '', 1, '', NULL, NULL, NULL, 'electronics/electronics-accessories', 0),
(181, 173, 113, 114, 2, 3, 'Home, Audio & Theater', 'home,-audio-&-theater', '', 1, '', NULL, NULL, NULL, 'electronics/home,-audio-&-theater', 0),
(182, 173, 117, 118, 2, 3, 'TV ', 'tv-', '', 1, '', NULL, NULL, NULL, 'electronics/tv-', 0),
(183, 192, 71, 72, 2, 3, 'Photography', 'photography', '', 1, '', NULL, NULL, NULL, 'camera-photography/photography', 0),
(184, 192, 69, 70, 2, 3, 'Camera', 'camera', '', 1, '', NULL, NULL, NULL, 'camera-photography/camera', 0),
(185, 148, 53, 54, 2, 3, 'Textbooks', 'textbooks', '', 1, '', NULL, NULL, NULL, 'books-1/textbooks', 0),
(186, 148, 51, 52, 2, 3, 'Children''s Books', 'children''s-books', '', 1, '', NULL, NULL, NULL, 'books-1/children''s-books', 0),
(187, 148, 49, 50, 2, 3, 'Books', 'books', '', 1, '', NULL, NULL, NULL, 'books-1/books', 0),
(188, 210, 29, 30, 2, 3, 'Tires ', 'tires-', '', 1, '', NULL, NULL, NULL, 'automotive-motors/tires-', 0),
(189, 210, 27, 28, 2, 3, 'Car Electronics', 'car-electronics', '', 1, '', NULL, NULL, NULL, 'automotive-motors/car-electronics', 0),
(190, 210, 25, 26, 2, 3, 'Automotive Tools', 'automotive-tools', '', 1, '', NULL, NULL, NULL, 'automotive-motors/automotive-tools', 0),
(191, 210, 23, 24, 2, 3, 'Automotive Parts', 'automotive-parts', '', 1, '', NULL, NULL, NULL, 'automotive-motors/automotive-parts', 0),
(192, 1, 67, 68, 1, 3, 'Camera & Photography', 'camera-photography', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100335.jpg', '/categories/marker_photo-1411754091.png', NULL, NULL, 'camera-photography', 0),
(193, 176, 287, 288, 2, 3, 'Musical Instruments', 'musical-instruments', '', 1, '', NULL, NULL, NULL, 'movies-music-games/musical-instruments', 0),
(194, 176, 285, 286, 2, 3, 'MP3 Downloads', 'mp3-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games/mp3-downloads', 0),
(195, 176, 281, 282, 2, 3, 'Game Downloads', 'game-downloads', '', 1, '', NULL, NULL, NULL, 'movies-music-games/game-downloads', 0),
(196, 171, 365, 366, 2, 3, 'Exercise & Fitness', 'exercise-&-fitness', '', 1, '', NULL, NULL, NULL, 'sports-outdors/exercise-&-fitness', 0),
(197, 171, 369, 370, 2, 3, 'Outdoor Recreation', 'outdoor-recreation', '', 1, '', NULL, NULL, NULL, 'sports-outdors/outdoor-recreation', 0),
(198, 171, 367, 368, 2, 3, 'Hunting & Fishing', 'hunting-&-fishing', '', 1, '', NULL, NULL, NULL, 'sports-outdors/hunting-&-fishing', 0),
(199, 171, 363, 364, 2, 3, 'Cycling', 'cycling', '', 1, '', NULL, NULL, NULL, 'sports-outdors/cycling', 0),
(200, 171, 371, 372, 2, 3, 'Team Sports', 'team-sports', '', 1, '', NULL, NULL, NULL, 'sports-outdors/team-sports', 0),
(201, 175, 405, 406, 2, 3, 'Toys & Games', 'toys-&-games', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/toys-&-games', 0),
(202, 175, 399, 400, 2, 3, 'Baby', 'baby', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/baby', 0),
(203, 175, 403, 404, 2, 3, 'Clothing (Kids & Baby)', 'clothing-(kids-&-baby)', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/clothing-(kids-&-baby)', 0),
(204, 175, 407, 408, 2, 3, 'Video Games for Kids', 'video-games-for-kids', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/video-games-for-kids', 0),
(205, 175, 401, 402, 2, 3, 'Baby Registry', 'baby-registry', '', 1, '', NULL, NULL, NULL, 'toy-kids-babies/baby-registry', 0),
(206, 209, 335, 336, 2, 3, 'Services', 'services', '', 1, '', NULL, NULL, NULL, 'services/services', 0),
(207, 209, 333, 334, 2, 3, 'IT Services', 'it-services', '', 1, '', NULL, NULL, NULL, 'services/it-services', 0),
(208, 1, 171, 172, 1, 3, 'Health & Beauty', 'grocery-health-beauty', '', 1, '/categories/slide1-the-health-and-beauty-world-1427100497.jpg', '/categories/marker_health-1411754146.png', NULL, NULL, 'grocery-health-beauty', 0),
(209, 1, 331, 332, 1, 3, 'Services', 'services', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image9-1411751440.png', '/categories/marker_service-1411754187.png', NULL, NULL, 'services', 0),
(210, 1, 21, 22, 1, 3, 'Automotive & Motors', 'automotive-motors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image5-1427100860.jpg', '/categories/marker_auto-1411754020.png', NULL, NULL, 'automotive-motors', 0),
(211, 1, 311, 312, 1, 3, 'Restaurants', 'restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur. Mauris c', 1, '/categories/image3-1427100807.jpg', '', NULL, NULL, 'restaurants', 0),
(212, 211, 313, 314, 2, 3, 'Asian Restaurants', 'asian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants/asian-restaurants', 0),
(213, 211, 315, 316, 2, 3, 'French Restaurants', 'french-restaurants', '', 1, '', '', NULL, NULL, 'restaurants/french-restaurants', 0),
(214, 211, 317, 318, 2, 3, 'Italian Restaurants', 'italian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', NULL, NULL, 'restaurants/italian-restaurants', 0),
(215, 1, 293, 294, 1, 3, 'Real Estate', 'real-estate', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '/categories/image9-1427100841.jpg', '', NULL, NULL, 'real-estate', 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_cities`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_cities`
--

INSERT INTO `#__jbusinessdirectory_cities` (`id`, `name`) VALUES
(1, 'Toronto'),
(2, 'Montreal');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_companies`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `comercialName` varchar(120) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text,
  `street_number` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(60) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `keywords` varchar(150) DEFAULT NULL,
  `registrationCode` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1',
  `typeId` int(11) NOT NULL,
  `logoLocation` varchar(245) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` DATETIME  DEFAULT NULL ,
  `mainSubcategory` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `activity_radius` float DEFAULT NULL,
  `userId` int(11) NOT NULL DEFAULT '0',
  `averageRating` float NOT NULL DEFAULT '0',
  `review_score` float DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `websiteCount` int(11) NOT NULL DEFAULT '0',
  `contactCount` int(11) NOT NULL DEFAULT '0',
  `taxCode` varchar(45) DEFAULT NULL,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `googlep` varchar(100) DEFAULT NULL,
  `skype` VARCHAR(100) DEFAULT NULL,
  `linkedin` VARCHAR(100)DEFAULT NULL,
  `youtube` VARCHAR(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `pinterest` VARCHAR(100) DEFAULT NULL,
  `postalCode` varchar(55) DEFAULT NULL,
  `mobile` varchar(55) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `publish_only_city` tinyint(1) DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `business_hours` varchar(255) DEFAULT NULL,
  `custom_tab_name` varchar(100) DEFAULT NULL,
  `custom_tab_content` text,
  `business_cover_image` VARCHAR(145) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`typeId`),
  KEY `idx_user` (`userId`),
  KEY `idx_state` (`state`),
  KEY `idx_approved` (`approved`),
  KEY `idx_country` (`countryId`),
  KEY `idx_package` (`package_id`),
  KEY `idx_name` (`name`),
  KEY `idx_keywords` (`keywords`),
  KEY `idx_description` (`description`(100)),
  KEY `idx_city` (`city`),
  KEY `idx_county` (`county`),
  KEY `idx_maincat` (`mainSubcategory`),
  KEY `idx_zipcode` (`latitude`,`longitude`),
  KEY `idx_phone` (`phone`),
  KEY `idx_alis` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `#__jbusinessdirectory_companies`
--

INSERT INTO `#__jbusinessdirectory_companies` (`id`, `name`, `alias`, `comercialName`, `short_description`, `description`, `meta_title`, `meta_description`, `street_number`, `address`, `city`, `county`, `countryId`, `website`, `keywords`, `registrationCode`, `phone`, `email`, `fax`, `state`, `typeId`, `logoLocation`, `creationDate`, `modified`, `mainSubcategory`, `latitude`, `longitude`, `activity_radius`, `userId`, `averageRating`, `review_score`, `approved`, `viewCount`, `websiteCount`, `contactCount`, `taxCode`, `package_id`, `facebook`, `twitter`, `googlep`, `skype`, `linkedin`, `youtube`, `instagram`, `pinterest`, `postalCode`, `mobile`, `slogan`, `publish_only_city`, `featured`, `business_hours`, `custom_tab_name`, `custom_tab_content`, `business_cover_image`) VALUES
(1, 'Wedding Venue', 'wedding-venue', 'Home & Gardem', '', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '', NULL, '123', 'Old San Francisco Rd', 'Sunnyvale', 'California', 226, 'http://www.garden.com', 'wedding, planning, venue', '342423422', '34242123123', 'email@decoration.com', '434312312321', 1, 6, '/companies/1/image1-1426883774.jpg', '2015-12-20 18:37:45', '2015-12-20 20:37:45', 8, '37.3681865', '-122.031385', 0, 0, 4, NULL, 2, 21, 0, 0, '123123', 0, '', '', '', NULL, NULL, NULL, NULL, NULL, '94086', '', '', 0, 0, NULL, NULL, NULL, NULL),
(4, 'Water Sports', 'water-sports', 'Rent a car', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut laoreet feugiat lectus id ornare. Nulla ut odio eget justo faucibus consectetur. Ut faucibus ultrices accumsan. Aenean leo neque, accumsan ac eleifend vel, pulvinar id urna. Phasellus non malesuada augue. Maecenas id egestas quam, at molestie tortor. Sed quis dictum eros.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut laoreet feugiat lectus id ornare. Nulla ut odio eget justo faucibus consectetur. Ut faucibus ultrices accumsan. Aenean leo neque, accumsan ac eleifend vel, pulvinar id urna. Phasellus non malesuada augue. Maecenas id egestas quam, at molestie tortor. Sed quis dictum eros.</p>', '', '', '11', 'Young Street', 'Toronto', 'Ontario', 36, 'http://www.cmsjunkie.com', '', 'JBD-5512312', '0010727321321', 'office@email.com', '0010269/220123', 1, 6, '/companies/4/image3-1469610244.jpg', '2015-12-25 18:37:45', '2016-08-17 08:56:04', 5, '43.64208175305137', '-79.3842687603319', 0, 0, 5, NULL, 2, 7, 0, 0, '123123', 0, '', '', '', '', '', '', '', '', '23123213', '001072744333', 'Live your life at maximum.', 0, 0, '', '', '', ''),
(5, 'Yoga Club', 'yoga-club', 'AQUACON PROJECT', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '', '', '44', 'Young Street', 'Toronto', 'Ontario', 36, 'http://www.cmsjunkie.com', '', 'YG-12312', '0727321321', 'office@site.com', '0269/220123', 1, 6, '/companies/5/iamge3-1469609893.jpg', '2015-12-22 03:30:40', '2016-08-17 08:55:08', 29, '43.650081332730466', '-79.37849521636963', 15, 0, 3.75, NULL, 2, 10, 1, 0, '123', 0, '', '', '', '', '', '', '', '', '1312312', '', 'Get in harmony with your body and soul!', 0, 0, '10:00 AM,05:00 PM,10:00 AM,05:00 PM,10:00 AM,05:00 PM,10:00 AM,05:00 PM,10:00 AM,05:00 PM,10:00 AM,05:00 PM,closed,', '', '', ''),
(7, 'Professional Photos', 'professional-photos', 'FINE JEWELERY', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '', '', '33', 'Richmong', 'Toronto', 'Ontario', 36, 'http://www.cmsjunkie.com', 'keywords1', 'JBD-343243', '123123', 'office@shopping.com', '213123', 1, 6, '/companies/7/image1-1469609373.jpg', '2011-11-24 03:31:39', '2016-07-27 08:50:35', 24, '43.649677652720534', '-79.37798023223877', 30, 0, 4.25, NULL, 2, 16, 0, 0, '123123', 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '', '', '', '', '', '23213', '', 'We save the special moments for eternity. ', 0, 0, '', '', '', ''),
(8, 'Vintage photography', 'vintage-photography', 'Contruction Company', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc.', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '', '', '22', 'Lawrance', 'Toronto', 'Ontario', 36, 'http://google.com', '', 'JBD-343412', '0727321321', 'office@site.com', '0269/220123', 1, 6, '/companies/8/image7-1469608618.jpg', '2011-11-24 03:32:07', '2016-08-17 08:59:40', 24, '43.65057816594119', '-79.37493324279785', 0, 0, 4.5, NULL, 2, 37, 0, 0, '12312', 0, '', '', '', '', '', '', '', '', '23123123', '', 'Good old day are coming back.', 0, 0, '08:00 AM,08:00 PM,08:00 AM,08:00 PM,08:00 AM,08:00 PM,08:00 AM,08:00 PM,08:00 AM,08:00 PM,08:00 AM,05:00 PM,closed,', '', '', ''),
(9, 'Flower Shop', 'flower-shop', 'IT Services', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc.', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '', '', '32', 'Queen Street', 'Toronto', 'Ontario', 36, 'http://google.com', '', '3424234221212', '0727321321', 'office@company.com', '0010269/220123', 1, 5, '/companies/9/image11-1469561156.jpg', '2011-12-01 08:24:29', '2016-08-17 08:58:55', 11, '43.650391853968806', '-79.38038349151611', 20, 0, 1.5, NULL, 2, 19, 0, 0, '123123', 0, '', '', '', '', '', '', '', '', '123213123', '', 'One flow for one happy person. ', 0, 0, '', '', '', ''),
(12, 'Tea Shop', 'tea-shop', 'Almedia', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc.', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '', '', '74444', 'Peg Keller Rd', 'Abita Springs', 'Louisiana', 226, 'http://www.cmsjunkie.com', '', 'RT545412SD', '001010727321321', 'directory@director.com', '', 1, 1, '/companies/12/image5-1469560881.jpg', '2011-12-02 09:32:19', '2016-08-17 08:58:17', 29, '43.65538688599313', '-79.35828778892756', 20, 0, 4, NULL, 2, 99, 0, 0, '123123123', 0, 'http://https://www.facebook.com/cmsjunkie', 'http://https://twitter.com/cmsjunkie', 'http://https://plus.google.com/100376620356699373069/posts', '', '', '', '', '', '70420', '', 'Enjoy our best tea for a great day!', 0, 0, '09:30 AM,06:00 PM,09:30 AM,06:00 PM,09:30 AM,06:00 PM,09:30 AM,06:00 PM,09:30 AM,06:00 PM,10:00 AM,02:00 PM,closed,', '', '', ''),
(29, 'Yacht Club', 'yacht-club', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '', '', '77877', 'Mosby Creek Rd', 'Cottage Grove', 'Oregon', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 6, '/companies/29/image3-1469555619.jpg', '2015-12-20 16:44:58', '2016-08-17 08:57:57', 5, '43.77616', '-123.00627400000002', 0, 0, 0, NULL, 2, 12, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '', '', '', '', '', '97424', '+1 555 883 9932', 'We provide any yacht you want.', 0, 0, '', '', '', ''),
(30, 'Real Property', 'real-property', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '', '', '1123', 'New York Ave', 'New York', 'New York', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 6, '/companies/30/image9-1469555370.jpg', '2016-01-20 17:12:27', '2016-07-26 17:50:05', 79, '40.645796', '-73.94583599999999', 0, 0, 0, NULL, 2, 8, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '', '', '', '', '', '11203', '+1 555 883 9932', 'We can sell it for you', 0, 0, '', '', '', ''),
(31, 'Restaurant One', 'restaurant-one', NULL, 'Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '', '', '12', 'New Hartford St', 'Wolcott', 'New York', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 1, '/companies/31/image11-1469554921.jpg', '2016-01-20 18:30:06', '2016-08-17 08:57:32', 75, '43.2157525', '-76.81465800000001', 0, 0, 0, NULL, 2, 6, 0, 0, NULL, 0, '', '', '', '', '', '', '', '', '14590', '+1 555 883 9932', 'The food taste like never before.', 0, 0, '', '', '', ''),
(32, 'Fashion Inc.', 'fashion-inc', NULL, 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitu', '<p>Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius.</p>\r\n<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '', '', '1', 'Rue Sherbrooke Est', 'Montréal', 'Québec', 36, '', '', '', '+1 555 888 9932', 'office@site.com', '', 1, 2, '/companies/32/fashiongirl-1469554561.jpg', '2015-12-22 06:36:50', '2016-07-26 17:37:15', 8, '45.5125554', '-73.56943790000003', 0, 0, 0, NULL, 2, 15, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '', '', '', '', '', 'H2X 3V8', '+1 555 883 9932', 'Fashion is art!', 0, 0, '10:00:00,17:00:00,10:00:00,17:00:00,10:00:00,17:00:00,10:00:00,17:00:00,10:00:00,17:00:00,10:00:00,13:00:00,closed,', '', '', ''),
(33, 'Amusement Park', 'amusement-park', NULL, 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus.', '<p>Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius.</p>\r\n<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '', '', '12', 'Hopkins Ave', 'Jersey City', 'New Jersey', 226, '', '', '', '+1 444 777 9999', 'office@site.com', '', 1, 6, '/companies/33/image2-1469554130.jpg', '2016-01-23 06:41:43', '2016-08-17 08:57:10', 3, '40.7343489', '-74.05115409999996', 0, 0, 0, NULL, 2, 19, 0, 0, NULL, 0, '', '', '', '', '', '', '', '', '07306', '+1 555 883 9932', 'Our main concern is your entertainment ', 0, 0, '09:00 AM,05:00 PM,09:00 AM,05:00 PM,09:00 AM,05:00 PM,09:00 AM,05:00 PM,09:00 AM,05:00 PM,09:00 AM,02:00 PM,closed,', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_activity_city`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_activity_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IND_UNQ` (`company_id`,`city_id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_city` (`city_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_activity_city`
--

INSERT INTO `#__jbusinessdirectory_company_activity_city` (`id`, `company_id`, `city_id`) VALUES
(24, 1, -1),
(27, 4, -1),
(23, 8, -1),
(26, 9, -1),
(25, 12, -1);

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_attachments`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `path` varchar(155) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_object` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_attachments`
--

INSERT INTO `#__jbusinessdirectory_company_attachments` (`id`, `type`, `object_id`, `name`, `path`, `status`) VALUES
(22, 2, 13, 'Treatment instructions', '/offers/13/SPA_Woman_Face_1280_770_d-1426864490.jpg', 1),
(23, 1, 12, 'Healthcare catalog', '/companies/12/natural-health-1426863987.jpg', 1);

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_UNIQUE` (`company_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_category`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_category` (
  `companyId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`companyId`,`categoryId`),
  KEY `idx_category` (`companyId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_category`
--
INSERT INTO `#__jbusinessdirectory_company_category` (`companyId`, `categoryId`) VALUES
(1, 8),
(1, 35),
(4, 5),
(4, 60),
(5, 29),
(5, 42),
(5, 60),
(6, 5),
(6, 17),
(7, 13),
(7, 24),
(8, 13),
(8, 24),
(9, 11),
(9, 46),
(12, 29),
(12, 41),
(12, 42),
(20, 290),
(20, 292),
(29, 5),
(29, 60),
(29, 63),
(30, 79),
(31, 75),
(31, 77),
(31, 78),
(32, 8),
(32, 35),
(32, 38),
(33, 3);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_claim`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) DEFAULT NULL,
  `firstName` varchar(55) DEFAULT NULL,
  `lastName` varchar(55) DEFAULT NULL,
  `function` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_contact`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `contact_name` varchar(50) DEFAULT NULL,
  `contact_function` varchar(50) DEFAULT NULL,
  `contact_department` varchar(100) DEFAULT NULL,
  `contact_email` varchar(60) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_fax` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`companyId`),
  KEY `R_13` (`companyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_contact`
--

INSERT INTO `#__jbusinessdirectory_company_contact` (`id`, `companyId`, `contact_name`, `contact_function`, `contact_department`, `contact_email`, `contact_phone`, `contact_fax`) VALUES
(1, 8, '', NULL, NULL, '', '', ''),
(2, 1, '', NULL, NULL, '', '', ''),
(3, 12, 'Joanne Smith', NULL, NULL, 'joaan@joann.com', '+1 323 999 672', '+1 323 231 754'),
(4, 9, 'John rice', NULL, NULL, 'john@organic.co', '+1 221 359 888', ''),
(5, 4, '', NULL, NULL, '', '', ''),
(6, 7, 'John Doe', NULL, NULL, 'john@john.com', '01 232 495 999', ''),
(7, 5, '', NULL, NULL, '', '', ''),
(8, 29, 'John Smith', NULL, NULL, 'john@smith.com', '+1 221 359 888', ''),
(9, 30, '', NULL, NULL, '', '', ''),
(10, 31, 'Chef Michael', NULL, NULL, 'joaan@joann.com', '', ''),
(11, 32, '', NULL, NULL, '', '', ''),
(12, 33, 'Brian Lindow', NULL, NULL, 'office@site.com', '+1 323 999 672', '');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_events`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(110) DEFAULT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `short_description` varchar(245) DEFAULT NULL,
  `description` text,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `price` float DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `county` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `view_count` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `recurring_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_search` (`start_date`,`end_time`,`end_date`,`state`,`approved`),
  KEY `idx_alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_events`
--

INSERT INTO `#__jbusinessdirectory_company_events` (`id`, `company_id`, `name`, `alias`, `short_description`, `description`, `meta_title`, `meta_description`, `meta_keywords`, `price`, `type`, `start_date`, `start_time`, `end_date`, `end_time`, `address`, `city`, `county`, `location`, `latitude`, `longitude`, `featured`, `created`, `view_count`, `approved`, `state`, `recurring_id`) VALUES
(9, 33, 'Celebration Party', 'celebration-party', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan enim dignissim consectetur viverra. Vestibulum a erat vitae quam pellentesque varius vel at ipsum. Pellentesque ultricies.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan enim dignissim consectetur viverra. Vestibulum a erat vitae quam pellentesque varius vel at ipsum. Pellentesque ultricies porttitor bibendum. Donec et justo quis tortor egestas rhoncus hendrerit ac massa. Sed tempus iaculis mi at sollicitudin. Etiam a ligula eget magna condimentum consectetur non pulvinar purus. Phasellus quis lobortis mauris. Nullam eleifend iaculis sem, nec hendrerit quam molestie vitae. Cras condimentum, augue at pretium pellentesque, ipsum arcu ullamcorper erat, eu laoreet libero erat id erat. Vestibulum ut dolor commodo, condimentum purus in, egestas purus. In hac habitasse platea dictumst. Nam rutrum sapien quam, in viverra libero interdum a. Vivamus sollicitudin dolor eget tincidunt faucibus. Cras pretium justo neque, quis imperdiet nulla vehicula a. Nulla facilisi. Aliquam justo ligula, fringilla vel orci in, imperdiet aliquam elit. Vivamus ac lorem blandit, tempor felis eget, placerat lectus.</p>', '', '', '', 0, 4, '2016-08-14', '15:00:00', '2016-11-22', '19:00:00', ' Scarborough Heights Boulevard', 'Toronto', 'Ontario', 'Toronto, Canada', '43.72347870000001', '-79.22839210000001', 0, '0000-00-00 00:00:00', 5, 1, 1, 0),
(10, 12, 'Bike Adventure', 'bike-adventure', 'Nulla consectetur magna et cursus sagittis. Quisque ac consectetur elit. Ut volutpat tellus non orci fermentum, sit amet tincidunt quam scelerisque. Integer eleifend congue eros.', '<p>Nulla consectetur magna et cursus sagittis. Quisque ac consectetur elit. Ut volutpat tellus non orci fermentum, sit amet tincidunt quam scelerisque. Integer eleifend congue eros pellentesque pharetra. Integer sed diam lectus. Donec ultricies, arcu a vulputate fringilla, nisi quam vestibulum libero, faucibus bibendum nunc justo sed ante. Etiam luctus quis nisl nec ornare. Fusce urna leo, tincidunt at commodo non, vestibulum et erat. In faucibus posuere purus, at egestas dolor dictum ac. Maecenas volutpat lectus eget purus hendrerit, sit amet hendrerit diam mattis. Nulla imperdiet metus ac metus molestie, sed imperdiet leo eleifend. Fusce non tellus porta risus convallis vehicula. Donec quis convallis ligula.</p>', '', '', '', 0, 5, '2016-09-21', '17:00:00', '2016-11-21', '20:00:00', ' Gamble Avenue', 'Toronto', 'Ontario', 'Gamble Avenue, Toronto, Canada', '43.6903736', '-79.34918140000002', 0, '0000-00-00 00:00:00', 6, 1, 1, 0),
(11, 1, 'Violin concert', 'violin-concert', 'Nulla sagittis pretium sagittis. Aliquam tincidunt sodales dui, a facilisis nisi sollicitudin quis. Sed nec mattis augue. Sed hendrerit odio non mauris fermentum semper. ', '<p>Nulla sagittis pretium sagittis. Aliquam tincidunt sodales dui, a facilisis nisi sollicitudin quis. Sed nec mattis augue. Sed hendrerit odio non mauris fermentum semper. Praesent vehicula nec libero a imperdiet. Proin posuere nibh libero, ac euismod nulla tincidunt in. Mauris est nunc, fringilla ac facilisis a, ornare a leo. Nam lobortis tortor fringilla, lobortis nisl sit amet, cursus dolor. In at lectus massa. Integer ut nulla dapibus, volutpat nisi vitae, laoreet tellus. Quisque hendrerit blandit leo at dapibus.</p>', '', '', '', 0, 5, '2016-11-22', '15:00:00', '2016-11-21', '12:00:00', '#24 St. John', 'New York', 'New York', 'Gamble Avenue, Toronto, Canada', '40.72644570551446', '-74.11376953125', 0, '0000-00-00 00:00:00', 2, 1, 1, 0),
(12, 7, 'Photography Course', 'photography-course', 'Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus.', '<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '', '', '', 0, 3, '2016-09-23', '10:00:00', '2016-09-23', '19:30:00', '29895 Minnesota 34', 'Detroit Lakes', 'Minnesota', 'London, UK', '46.828117', '-95.77565600000003', 0, '0000-00-00 00:00:00', 3, 1, 1, 0),
(13, 32, 'Fashion Presentation', 'fashion-presentation', 'Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus.', '<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '', '', '', 0, 5, '2016-12-17', '17:00:00', '2017-09-23', '19:30:00', '9291 Lee Highway', 'Warrenton', 'Virginia', 'Paris, France', '38.694513', '-77.89013299999999', 0, '0000-00-00 00:00:00', 1, 1, 1, 0),
(14, 31, 'Wine testing', 'wine-testing', 'Cras eget lorem libero. Nulla facilisi. Aliquam ac volutpat erat. Etiam vulputate pellentesque maximus. Nunc id metus nunc. Phasellus finibus ante et finibus viverra. Sed nec mattis augue.', '<p>Aliquam dignissim sagittis urna eu ultrices. Curabitur hendrerit mi leo, sed tincidunt libero venenatis eu. Curabitur non feugiat diam. Proin pharetra, leo ut pellentesque dignissim, orci libero tempus odio, congue volutpat arcu eros consectetur tortor. Cras eget volutpat felis. Ut lobortis lectus eget ligula condimentum hendrerit. Curabitur et justo nunc. Duis malesuada, est vel pellentesque viverra, nulla lorem ornare dui, eget elementum sapien elit et sapien. Nulla placerat laoreet arcu, eu ullamcorper massa viverra quis. Cras quis faucibus leo, ut iaculis sem. Cras et egestas odio, quis mollis mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus</p>', '', '', '', 0, 5, '2017-01-29', '02:00:00', '2016-09-23', '02:00:00', '#34 west street', 'New York', 'New York', 'Burgundy, France', '40.759078', '-73.986201', 0, '0000-00-00 00:00:00', 2, 1, 1, 0),
(15, 30, 'Design Trends', 'design-trends', 'Mauris quis finibus tellus, eget dignissim tellus. Cras eget lorem libero. Nulla facilisi. Aliquam ac volutpat erat. Nunc id metus nunc. Phasellus finibus ante et finibus viverra.', '<p>Mauris quis finibus tellus, eget dignissim tellus. Cras eget lorem libero. Nulla facilisi. Aliquam ac volutpat erat. Nunc id metus nunc. Phasellus finibus ante et finibus viverra. Mauris scelerisque dignissim mauris, sit amet congue nisi sagittis vel. Etiam lacinia sapien in nulla ultricies eleifend. Aliquam feugiat vitae magna id aliquet. Nam sem ligula, sollicitudin in placerat quis, scelerisque elementum tortor. Aenean imperdiet dictum lorem. Praesent sit amet arcu id mi hendrerit scelerisque. Integer tincidunt massa eget lectus laoreet porttitor. Fusce quis luctus orci.</p>', '', '', '', 0, 5, '2016-09-11', '11:00:00', '2017-09-23', '18:30:00', '7777 Hollywood Boulevard', 'Los Angeles', 'California', 'New York, US', '34.10199989999999', '-118.35907500000002', 0, '0000-00-00 00:00:00', 8, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_category`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_category` (
  `eventId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`eventId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_event_category`
--

INSERT INTO `#__jbusinessdirectory_company_event_category` (`eventId`, `categoryId`) VALUES
(9, 206),
(10, 171),
(10, 199),
(11, 209),
(12, 192),
(13, 174),
(14, 211),
(15, 215);


-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_event_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_event_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_UNIQUE` (`event_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_event_pictures`
--
INSERT INTO `#__jbusinessdirectory_company_event_pictures` (`id`, `eventId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(65, 12, '', '/events/12/image2.jpg', 1),
(64, 12, '', '/events/12/image5.jpg', 1),
(71, 11, '', '/events/11/image1.jpg', 1),
(63, 12, '', '/events/12/image13.jpg', 1),
(62, 14, '', '/events/14/image1-1427103268.jpg', 1),
(66, 12, '', '/events/12/image7.jpg', 1),
(58, 15, '', '/events/15/w1-1427103368.jpg', 1),
(57, 15, '', '/events/15/image1-1427103376.jpeg', 1),
(56, 15, '', '/events/15/image4.jpg', 1),
(80, 9, '', '/events/9/image5.jpg', 1),
(79, 9, '', '/events/9/image3.jpg', 1),
(78, 9, '', '/events/9/image4.jpg', 1),
(61, 14, '', '/events/14/image3-1427103263.jpg', 1),
(60, 14, '', '/events/14/image1.jpg', 1),
(51, 13, '', '/events/13/image1.jpg', 1),
(52, 13, '', '/events/13/image7.jpg', 1),
(53, 13, '', '/events/13/image2.jpg', 1),
(54, 13, '', '/events/13/image5.jpg', 1),
(55, 13, '', '/events/13/image6.jpg', 1),
(59, 15, '', '/events/15/image3-1427103371.jpg', 1),
(77, 10, '', '/events/10/image2.jpeg', 1),
(76, 10, '', '/events/10/image3.jpg', 1),
(75, 10, '', '/events/10/image1.jpg', 1),
(72, 11, '', '/events/11/image4.jpg', 1),
(73, 11, '', '/events/11/iamge3.jpg', 1),
(74, 11, '', '/events/11/image2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_event_types`
--

INSERT INTO `#__jbusinessdirectory_company_event_types` (`id`, `name`, `ordering`) VALUES
(1, 'Seminar', NULL),
(2, 'Training', NULL),
(3, 'Workshop', NULL),
(4, 'Party', NULL),
(5, 'Presentation', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_images`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_images` (
  `companyId` char(18) NOT NULL,
  `id` char(18) NOT NULL,
  `imagePath` char(18) DEFAULT NULL,
  `typeId` char(18) NOT NULL,
  PRIMARY KEY (`companyId`,`id`,`typeId`),
  KEY `R_9` (`companyId`,`typeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_locations`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `street_number` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(60) DEFAULT NULL,
  `postalCode` varchar(45) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `phone` VARCHAR(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_messages`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `surname` varchar(145) DEFAULT NULL,
  `email` char(255) NOT NULL,
  `message` text DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_messages`
--
--
-- Table structure for table `#__jbusinessdirectory_company_offers`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `currencyId` int(11) NOT NULL,
  `subject` varchar(110) NOT NULL,
  `description` text,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `price` float DEFAULT NULL,
  `specialPrice` float DEFAULT NULL,
  `total_coupons` int(11) NOT NULL DEFAULT '0',
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `offerOfTheDay` tinyint(1) NOT NULL DEFAULT '0',
  `viewCount` int(10) DEFAULT '0',
  `alias` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `county` varchar(45) DEFAULT NULL,
  `publish_start_date` date DEFAULT NULL,
  `publish_end_date` date DEFAULT NULL,
  `view_type` tinyint(2) NOT NULL DEFAULT '1',
  `url` varchar(145) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `show_time` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_alias` (`alias`),
  KEY `idx_company` (`companyId`),
  KEY `idx_search` (`state`,`endDate`,`startDate`,`publish_end_date`,`publish_start_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_offers`
--

INSERT INTO `#__jbusinessdirectory_company_offers` (`id`, `companyId`, `currencyId`, `subject`, `description`, `meta_title`, `meta_description`, `meta_keywords`, `price`, `specialPrice`, `total_coupons`, `startDate`, `endDate`, `state`, `approved`, `offerOfTheDay`, `viewCount`, `alias`, `address`, `city`, `short_description`, `county`, `publish_start_date`, `publish_end_date`, `view_type`, `url`, `article_id`, `latitude`, `longitude`, `featured`, `created`, `show_time`) VALUES
(3, 12, 0, 'Garden arrangements', '<p>Etiam eget urna est. Nullam turpis magna, pharetra id venenatis id, adipiscing at velit. In lobortis ornare congue. Sed vitae neque lacus, et rutrum lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque quis rhoncus felis. Sed adipiscing tellus laoreet neque adipiscing ac euismod felis gravida. Aenean fermentum, nulla non adipiscing tristique, lacus justo ornare nunc, eu aliquam nunc massa non justo. Sed at sapien vitae eros luctus condimentum non at libero. Morbi id arcu nec mi suscipit molestie. Integer ullamcorper suscipit erat, quis convallis quam interdum convallis. Sed lectus justo, vehicula et euismod rhoncus, tempus vel magna. Pellentesque laoreet, odio id iaculis bibendum, erat quam mollis urna, ac pretium neque mi vitae nisl. Fusce euismod bibendum risus vel suscipit. Suspendisse sapien tortor, vehicula sed lobortis tempus, pellentesque ut lectus.</p>', '', '', '', 120, 90, 0, '2015-02-01', '2017-12-10', 1, 1, 1, 17, 'garden-arrangements', '7777 Forest Blvd', 'Dallas', 'Etiam eget urna est. Nullam turpis magna, pharetra id venenatis id, adipiscing at velit. In lobortis ornare congue. Sed vitae neque lacus, et rutrum lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '32.9113547', '-96.77428509999999', 0, '2015-03-20 05:04:23', 0),
(13, 5, 0, 'Yoga meditation day', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et mag</p>', '', '', '', 69.99, 49.99, 0, '2015-01-03', '2018-01-04', 1, 1, 1, 28, 'yoga-meditation-day', 'Country Hills Blvd NW', 'Beaumont', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '43.70957890823799', '-79.50366217643023', 0, '2015-03-20 05:04:23', 0),
(14, 8, 0, 'Camera time', '<p>Duis faucibus odio quis sapien imperdiet, nec congue turpis pellentesque. Integer mi turpis, eleifend et mollis eu, dapibus quis elit. Pellentesque at turpis urna. Sed scelerisque Diam scelerisque fermentum finibus. Mauris elementum euismod erat sed condimentum. Nulla imperdiet mattis massa, at fermentum erat tristique ac. Praesent eget velit maximus, blandit nisi at, porta ligula. Etiam quis libero nisl. Vestibulum quis ornare dui. Suspendisse quis lobortis nunc. Pellentesque quis pharetra metus. Phasellus vulputate orci in pharetra feugiat. Etiam vehicula lacus augue, et lacinia turpis mollis id. Phasellus sed feugiat nunc, sed pharetra risus. Etiam eleifend quis lectus et gravida. Nunc pretium nisi id mi maximus mollis. Aliquam tempus dictum mi. Donec cursus pharetra neque, at gravida dolor vestibulum sit amet. Donec quam urna, molestie pharetra venenatis in, tincidunt quis elit. Praesent pharetra eget metus vitae vestibulum. Mauris gravida turpis lorem, aliquam semper justo auc.</p>', '', '', '', 88, 55, 20, '2015-05-25', '2019-09-25', 1, 1, 0, 6, 'camera-time', 'Chopin Ave', 'Toronto', 'Diam scelerisque fermentum finibus. Mauris elementum euismod erat sed condimentum. Nulla imperdiet mattis massa, at fermentum erat tristique ac. Praesent eget velit maximus, blandit nisi at, porta ligula.', 'Ontario', '0000-00-00', '0000-00-00', 1, '', 0, '43.737594787503966', '-79.27854752537314', 0, '2015-03-20 05:04:23', 0),
(15, 1, 0, 'Book now and get 20 % off', '<p>Morbi porta luctus enim at scelerisque. Cras imperdiet nibh eget commodo blandit. Aliquam nec commodo lectus. Donec pellentesque, massa quis porta aliquet, massa metus accumsan metus, nec dignissim tortor mi eu erat. Phasellus pulvinar metus a tortor eleifend, a hendrerit tortor rutrum. Aliquam in tellus gravida, varius sem quis, interdum elit. Pellentesque nec egestas augue. Donec ullamcorper ante eu libero hendrerit, vel tempus dolor dapibus. Quisque finibus nisi eu sem venenatis porta. Praesent tempor nisi urna. Integer convallis dolor id ullamcorper consectetur. Morbi sodales mi et orci sollicitudin, sit amet pretium ante vulputate. Nullam ultrices vehicula urna in condimentum. Nulla lacus tortor, lobortis pulvinar turpis vitae, hendrerit gravida enim. Vestibulum eros magna, elementum ut pulvinar eget, placerat et augue. Vestibulum eget sapien vitae dui facilisis maximus a vel ligula. Nunc urna tortor, lobortis eu interdum vitae, mattis sit amet libero. Phasellus quis dapibus arcu, vulputate hendrerit est. Ut mattis bibendum gravida. Ut molestie ornare sapien nec dictum.</p>', '', '', '', 90, 70, 0, '2016-05-26', '2017-12-31', 1, 1, 0, 3, 'book-now-and-get-20-off', 'Chopin Ave', 'Toronto', 'Morbi porta luctus enim at scelerisque. Cras imperdiet nibh eget commodo blandit. Aliquam nec commodo lectus. Donec pellentesque, massa quis porta aliquet, massa metus accumsan metus, nec dignissim tortor mi eu erat. Phasellus pulvinar metus a torto', 'Ontario', '0000-00-00', '0000-00-00', 1, '', 0, '43.737032009283475', '-79.27838659283225', 0, '2016-01-20 05:04:23', 0),
(16, 29, 0, 'Yacht Final Sale', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed egestas maximus arcu a posuere. Phasellus eget tellus ac purus vulputate auctor. Donec nec semper elit, quis iaculis purus. Praesent vitae facilisis enim. Vestibulum laoreet tristique velit quis porttitor. Nam venenatis vestibulum est ut aliquam. Vivamus placerat sollicitudin est ut aliquet. Fusce imperdiet auctor felis, ut egestas sem condimentum sed. Pellentesque porta sit amet justo non imperdiet. Mauris leo lorem, ultricies eu consectetur eu, laoreet nec tortor. In hac habitasse platea dictumst. Donec facilisis nulla vitae est vulputate feugiat. Nam consequat orci elit, quis condimentum massa aliquet id. Sed tortor est, dictum ac viverra a, aliquam vel lectus. Ut non ipsum sodales, cursus neque id, sollicitudin nunc. Duis vitae placerat lacus. Vestibulum sit amet neque congue, euismod diam id, sagittis felis. Aenean fringilla tempor velit sit amet pretium. Praesent sollicitudin libero in quam semper, ac vestibulum libero tempus. Integer euismod ipsum et varius mollis.</p>', '', '', '', 80000, 65000, 0, '2016-06-26', '2017-09-26', 1, 1, 0, 7, 'yacht-final-sale', '130 Yorkland Boulevard', 'Beaumont', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed egestas maximus arcu a posuere. Phasellus eget tellus ac purus vulputate auctor. Donec nec semper elit, quis iaculis purus.', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '29.874468564024614', '-94.1099853347987', 0, '2015-03-20 05:04:23', 0),
(17, 30, 0, 'Travel to Rome', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam. Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. P</p>', '', '', '', 1100, 890, 10, '2015-03-20', '2015-10-20', 1, 1, 0, 2, 'travel-to-rome', '24 Viale Leonardo da Vinci', 'Rome', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Lazio', '0000-00-00', '0000-00-00', 1, '', 0, '41.8546831', '12.48038459999998', 0, '2015-12-20 08:12:33', 0),
(18, 31, 0, 'Chinese night', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam. Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. P</p>', '', '', '', 95, 75, 0, '2015-03-20', '2015-12-20', 1, 1, 0, 4, 'chinese-night', 'Coon Creek Rd', 'Armada', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Michigan', '0000-00-00', '0000-00-00', 1, '', 0, '42.870074', '-82.92174699999998', 0, '2015-12-20 10:01:35', 0),
(19, 7, 0, 'Photograpy course', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam. Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus.</p>', '', '', '', 100, 80, 50, '2015-03-20', '2017-12-20', 1, 1, 0, 1, 'photograpy-course', 'Mont Steet', 'Loretto', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Minnesota', '0000-00-00', '0000-00-00', 1, '', 0, '45.11', '-93.67000000000002', 0, '2016-01-20 10:46:55', 0),
(20, 9, 0, 'Flower bucket', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam. Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus.</p>', '', '', '', 55, 45, 30, '2015-03-20', '2017-03-20', 1, 1, 0, 8, 'real-estate-offer', 'U.S. 101', 'Florence', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Oregon', '0000-00-00', '0000-00-00', 1, '', 0, '44.244779', '-124.11095399999999', 0, '2015-03-20 11:02:25', 0),
(21, 33, 0, 'Roller Coaster', '<p>Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius. Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec.</p>', '', '', '', 50, 30, 10, '2015-03-23', '2017-03-23', 1, 1, 0, 5, 'roller-coaster', 'Hopkins Ave', 'Jersey City', 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. ', 'New Jersey', '0000-00-00', '0000-00-00', 1, '', 0, '40.7367335', '-74.05566350000004', 0, '2015-12-23 01:01:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_offer_category`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_category` (
  `offerId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`offerId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_offer_category`
--

INSERT INTO `#__jbusinessdirectory_company_offer_category` (`offerId`, `categoryId`) VALUES
(3, 93),
(3, 109),
(13, 140),
(14, 115),
(15, 99),
(16, 103),
(17, 147),
(18, 86),
(18, 88),
(19, 115),
(19, 124),
(20, 147),
(21, 121),
(21, 142);


--
-- Table structure for table `#__jbusinessdirectory_company_offer_coupons`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `generated_time` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_offer_coupons`
--

-- --------------------------------------------------------

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_offer_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_offer_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_UNIQUE` (`offer_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_offer_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `offerId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_offer` (`offerId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_offer_pictures`
--

INSERT INTO `#__jbusinessdirectory_company_offer_pictures` (`id`, `offerId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(187, 19, '', '/offers/19/image4.jpg', 1),
(188, 19, '', '/offers/19/image8.jpg', 1),
(189, 19, '', '/offers/19/image12.jpg', 1),
(150, 15, '', '/offers/15/image4.jpg', 1),
(154, 14, '', '/offers/14/image2.jpg', 1),
(155, 14, '', '/offers/14/image5.jpg', 1),
(153, 14, '', '/offers/14/image8.jpg', 1),
(145, 18, '', '/offers/18/image3.jpg', 1),
(183, 3, '', '/offers/3/image2.jpg', 1),
(144, 18, '', '/offers/18/image1.jpg', 1),
(186, 19, '', '/offers/19/image9.jpg', 1),
(185, 13, '', '/offers/13/image1.png', 1),
(184, 13, '', '/offers/13/image4.jpg', 1),
(143, 18, '', '/offers/18/image2.jpg', 1),
(152, 15, '', '/offers/15/image5.jpg', 1),
(149, 16, '', '/offers/16/image1.jpg', 1),
(151, 15, '', '/offers/15/image6.jpg', 1),
(174, 17, '', '/offers/17/image8.jpg', 1),
(173, 17, '', '/offers/17/image1.jpg', 1),
(172, 17, '', '/offers/17/image4.jpg', 1),
(138, 20, '', '/offers/20/image1.jpg', 1),
(136, 20, '', '/offers/20/image9.jpg', 1),
(137, 20, '', '/offers/20/iamge10.jpg', 1),
(135, 21, '', '/offers/21/image1.jpg', 1),
(134, 21, '', '/offers/21/image14.jpg', 1);



-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `companyId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_pictures`
--

INSERT INTO `#__jbusinessdirectory_company_pictures` (`id`, `companyId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(503, 4, '', '/companies/4/image3.jpg', 1),
(512, 31, '', '/companies/31/image8.jpg', 1),
(527, 9, '', '/companies/9/image1.jpg', 1),
(485, 7, '', '/companies/7/image4.jpg', 1),
(452, 30, '', '/companies/30/image1.jpg', 1),
(534, 8, '', '/companies/8/image8.jpg', 1),
(533, 8, '', '/companies/8/image7.jpg', 1),
(434, 32, '', '/companies/32/image7.jpg', 1),
(433, 32, '', '/companies/32/image6.jpg', 1),
(432, 32, '', '/companies/32/image5.jpg', 1),
(431, 32, '', '/companies/32/image2.jpg', 1),
(430, 32, '', '/companies/32/image1.jpg', 1),
(532, 8, '', '/companies/8/image2.jpg', 1),
(531, 8, '', '/companies/8/image11.jpg', 1),
(530, 8, '', '/companies/8/image1.jpg', 1),
(528, 9, '', '/companies/9/image7.jpeg', 1),
(511, 31, '', '/companies/31/image3-1426883370.jpg', 1),
(507, 33, '', '/companies/33/image4.jpg', 1),
(506, 33, '', '/companies/33/image3.jpg', 1),
(505, 33, '', '/companies/33/image2.jpg', 1),
(529, 8, '', '/companies/8/image5.jpg', 1),
(451, 30, '', '/companies/30/image6.jpg', 1),
(450, 30, '', '/companies/30/image11.jpg', 1),
(449, 30, '', '/companies/30/image5.jpg', 1),
(522, 12, '', '/companies/12/image2.jpg', 1),
(521, 12, '', '/companies/12/iamge4.jpg', 1),
(518, 12, '', '/companies/12/iamge3.jpg', 1),
(519, 12, '', '/companies/12/image1.jpg', 1),
(520, 12, '', '/companies/12/image5.jpg', 1),
(348, 1, '', '/companies/1/image3-1426883856.jpg', 1),
(347, 1, '', '/companies/1/image2-1426883853.jpg', 1),
(346, 1, '', '/companies/1/image1-1426883851.jpg', 1),
(517, 29, '', '/companies/29/image2.jpg', 1),
(516, 29, '', '/companies/29/image3.jpg', 1),
(497, 5, '', '/companies/5/iamge3.jpg', 1),
(495, 5, '', '/companies/5/image4.jpg', 1),
(496, 5, '', '/companies/5/iamge2.jpg', 1),
(349, 1, '', '/companies/1/image4-1426883859.jpg', 1),
(350, 1, '', '/companies/1/image5-1426883862.jpg', 1),
(526, 9, '', '/companies/9/iamge5.jpg', 1),
(525, 9, '', '/companies/9/image9.jpg', 1),
(524, 9, '', '/companies/9/image2.jpg', 1),
(523, 9, '', '/companies/9/iamge10.jpg', 1),
(484, 7, '', '/companies/7/image11.jpg', 1),
(483, 7, '', '/companies/7/image8.jpg', 1),
(482, 7, '', '/companies/7/image6.jpg', 1),
(481, 7, '', '/companies/7/image2.jpg', 1),
(480, 7, '', '/companies/7/iamge5.jpg', 1),
(504, 33, '', '/companies/33/image1.jpg', 1),
(502, 4, '', '/companies/4/iamge5.jpg', 1),
(501, 4, '', '/companies/4/image4.jpg', 1),
(500, 4, '', '/companies/4/image1.jpg', 1),
(499, 4, '', '/companies/4/image2.jpg', 1),
(510, 31, '', '/companies/31/image1-1426883363.jpg', 1),
(509, 31, '', '/companies/31/image9.jpg', 1),
(508, 31, '', '/companies/31/image10.jpg', 1),
(448, 30, '', '/companies/30/image7.jpg', 1),
(435, 32, '', '/companies/32/image4.jpg', 1),
(515, 29, '', '/companies/29/image1.jpg', 1),
(514, 29, '', '/companies/29/image4.jpg', 1),
(513, 31, '', '/companies/31/image2-1426883367.jpg', 1);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_ratings`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `rating` float NOT NULL,
  `ipAddress` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`companyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_ratings`
--

INSERT INTO `#__jbusinessdirectory_company_ratings` (`id`, `companyId`, `rating`, `ipAddress`) VALUES
(1, 8, 4, '5.15.238.52'),
(2, 12, 4, '5.15.238.52'),
(3, 5, 3, '5.15.238.52'),
(4, 4, 5, '5.15.238.52'),
(5, 1, 3, '5.15.238.52'),
(6, 7, 3.5, '5.15.238.52'),
(7, 9, 1.5, '5.15.238.52'),
(8, 8, 5, '127.0.0.1'),
(9, 1, 5, '127.0.0.1'),
(10, 7, 5, '127.0.0.1');


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_reviews`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `description` text,
  `userId` int(11) NOT NULL,
  `likeCount` smallint(6) DEFAULT '0',
  `dislikeCount` smallint(6) DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `companyId` int(11) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aproved` tinyint(1) NOT NULL DEFAULT '0',
  `ipAddress` varchar(45) DEFAULT NULL,
  `abuseReported` tinyint(1) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_reviews`
--

INSERT INTO `#__jbusinessdirectory_company_reviews` (`id`, `name`, `subject`, `description`, `userId`, `likeCount`, `dislikeCount`, `state`, `companyId`, `creationDate`, `aproved`, `ipAddress`, `abuseReported`, `rating`) VALUES
(8, 'Kelly', 'The best experience ever', 'Ut scelerisque eget mi eget porttitor. Nunc risus enim, volutpat et tempor eu, pretium et est. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam turpis nisl, laoreet varius mauris ac, porta pulvinar felis. Vestibulum placerat, velit eleifend facilisis cursus, turpis nisl ullamcorper eros, eu tristique est nisl a dui. Vestibulum elementum diam sed iaculis porttitor.', 439, 0, 0, 1, 12, '2015-03-24 11:03:37', 0, '127.0.0.1', 0, 4.17),
(9, 'Sam', 'A happy customer', 'Sed non risus erat. Cras ac dapibus augue. Pellentesque non purus at massa viverra tempus vel vestibulum elit. Quisque in libero in diam consectetur convallis at sed dolor. Nunc finibus arcu sed maximus lacinia. Vestibulum et eleifend lectus, ut laoreet elit. ', 439, 0, 0, 1, 12, '2015-03-24 11:09:42', 0, '127.0.0.1', 0, 4.67),
(4, 'Loren Jonson', 'Love the products', 'I had such a good experience on this store.', 0, 0, 0, 1, 9, '2015-03-20 16:09:48', 0, '127.0.0.1', 0, 5),
(5, 'John', 'This is what I was looking for', 'Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras interdum ut ante non porta. Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien.', 439, 0, 0, 1, 8, '2015-03-24 09:59:05', 0, '127.0.0.1', 0, 5),
(6, 'Michael', 'Greate store', 'Praesent id leo ex. Donec condimentum tincidunt metus at auctor. Nulla facilisis orci vitae ipsum volutpat pharetra. Proin eleifend lobortis nunc, in fringilla justo. Ut sollicitudin lacinia ex eget dapibus. Cras pharetra diam eu malesuada sagittis. Mauris eget ligula gravida, imperdiet ligula a, dictum ex. ', 439, 0, 0, 1, 29, '2015-03-24 10:49:41', 0, '127.0.0.1', 0, 4.33),
(7, 'Kevin', 'The best experience ever', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras interdum ut ante non porta. ', 439, 0, 0, 1, 8, '2015-03-24 10:58:42', 0, '127.0.0.1', 0, 4.83),
(10, 'John', 'Great food, great service', 'Maecenas convallis malesuada iaculis. Nullam non maximus velit, id molestie est. Pellentesque lobortis sodales tortor. Proin non sollicitudin felis, aliquam ornare massa. Fusce vel turpis est. Nam tempus turpis at orci rutrum tincidunt quis ac odio. Maecenas nec molestie arcu. Suspendisse potenti. Donec gravida diam urna, rutrum malesuada nisi tempor in. Vivamus vel ligula sed ante mattis venenatis. Cras ultricies ornare elit nec blandit. Morbi convallis tellus laoreet, egestas sapien non, condimentum ante. Donec egestas scelerisque est ut aliquam. Nam vulputate felis eu massa imperdiet facilisis. Interdum et malesuada fames ac ante ipsum primis in faucibus. ', 439, 0, 0, 1, 31, '2015-03-24 14:16:10', 0, '127.0.0.1', 0, 3.83);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_reviews_criteria`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(77) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


INSERT INTO `#__jbusinessdirectory_company_reviews_criteria` (`id`, `name`, `ordering`, `published`) VALUES
(1, 'Service', 1, NULL),
(2, 'Quality', 2, NULL),
(3, 'Staff', 3, NULL);
-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_reviews_user_criteria`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_user_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `score` decimal(2,1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

INSERT INTO `#__jbusinessdirectory_company_reviews_user_criteria` (`id`, `review_id`, `criteria_id`, `score`) VALUES
(28, 3, 1, 5),
(29, 3, 2, 4),
(30, 3, 3, 5),
(31, 4, 1, 5),
(32, 4, 2, 5),
(33, 4, 3, 5),
(34, 5, 1, 5),
(35, 5, 2, 5),
(36, 5, 3, 5),
(37, 6, 1, 4),
(38, 6, 2, 4),
(39, 6, 3, 5),
(40, 7, 1, 5),
(41, 7, 2, 5),
(42, 7, 3, 5),
(43, 8, 1, 4),
(44, 8, 2, 5),
(45, 8, 3, 4),
(46, 9, 1, 5),
(47, 9, 2, 5),
(48, 9, 3, 5),
(49, 10, 1, 4),
(50, 10, 2, 4),
(51, 10, 3, 4);


--
-- Table structure for table `#__jbusinessdirectory_company_review_abuses`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_review_abuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewId` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_review_responses`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_review_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `reviewId` int(11) NOT NULL,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `response` text,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`reviewId`),
  KEY `R_19` (`reviewId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_types`
--

INSERT INTO `#__jbusinessdirectory_company_types` (`id`, `name`, `ordering`) VALUES
(1, 'Manufacturer/producer', 1),
(2, 'Distributor ', 2),
(4, 'Wholesaler ', 3),
(5, 'Retailer', 4),
(6, 'Service Provider', 5),
(7, 'Subcontractor', 6),
(8, 'Agent/Representative', 7);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_videos`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) DEFAULT NULL,
  `url` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conferences`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT NULL,
  `alias` varchar(125) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `place` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT NULL,
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `featured` tinyint(1) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_sessions`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) DEFAULT NULL,
  `alias` varchar(125) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `locationId` int(11) DEFAULT NULL,
  `sessiontypeId` int(11) DEFAULT NULL,
  `sessionlevelId` int(11) DEFAULT NULL,
  `short_description` varchar(355) DEFAULT NULL,
  `description` text,
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '1',
  `conferenceId` int(11) DEFAULT NULL,
  `video` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_attachments`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `path` varchar(155) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_object` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_categories`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_categories` (
  `sessionId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_companies`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_companies` (
  `sessionId` int(11) NOT NULL,
  `companyId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`companyId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_levels`
--


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_locations`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_speakers`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_speakers` (
  `sessionId` int(11) NOT NULL,
  `speakerId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`speakerId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_session_types`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `clickCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_speakers`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_speakers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL,
  `alias` varchar(125) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company_name` varchar(55) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `speaker_language` varchar(100) DEFAULT NULL,
  `biography` text,
  `sessionId` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `speakertypeId` int(11) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `googlep` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `short_biography` text,
  `additional_info_link` varchar(100) DEFAULT NULL,
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_conference_speaker_types`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_speaker_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Table structure for table `#__jbusinessdirectory_countries`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_countries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_name` char(255) NOT NULL,
  `country_code` varchar(4) DEFAULT NULL,
  `country_currency` char(255) NOT NULL,
  `country_currency_short` char(50) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=244 ;

--
-- Dumping data for table `#__jbusinessdirectory_countries`
--

INSERT INTO `#__jbusinessdirectory_countries` (`id`, `country_name`, `country_code`, `country_currency`, `country_currency_short`, `logo`, `description`) VALUES
(1, 'Andorra', 'AD', 'Euro', 'EUR', '/flags/andorra.png', NULL),
(2, 'United Arab Emirates', 'AE', 'UAE Dirham', 'AED', '/flags/united-arab-emirates.png', NULL),
(3, 'Afghanistan', 'AF', 'Afghani', 'AFA', '/flags/afghanistan.png', NULL),
(4, 'Antigua and Barbuda', 'AG', 'East Caribbean Dollar', 'XCD', '/flags/antigua-and-barbuda.png', NULL),
(5, 'Anguilla', 'AI', 'East Caribbean Dollar', 'XCD', '/flags/anguilla.png', NULL),
(6, 'Albania', 'AL', 'Lek', 'ALL', '/flags/albania.png', NULL),
(7, 'Armenia', 'AM', 'Armenian Dram', 'AMD', '/flags/armenia.png', NULL),
(8, 'Netherlands Antilles', 'AN', 'Netherlands Antillean guilder', 'ANG', '/flags/netherlands-antilles.png', NULL),
(9, 'Angola', 'AO', 'Kwanza', 'AOA', '/flags/angola.png', NULL),
(11, 'Argentina', 'AR', 'Argentine Peso', 'ARS', '/flags/argentina.png', NULL),
(12, 'American Samoa', 'AS', 'US Dollar', 'USD', '/flags/american-samoa.png', NULL),
(13, 'Austria', 'AT', 'Euro', 'EUR', '/flags/austria.png', NULL),
(14, 'Australia', 'AU', 'Australian dollar', 'AUD', '/flags/australia.png', NULL),
(15, 'Aruba', 'AW', 'Aruban Guilder', 'AWG', '/flags/aruba.png', NULL),
(16, 'Azerbaijan', 'AZ', 'Azerbaijani Manat', 'AZM', '/flags/azerbaijan.png', NULL),
(17, 'Bosnia and Herzegovina', 'BA', 'Convertible Marka', 'BAM', '/flags/bosnia-and-herzegovina.png', NULL),
(18, 'Barbados', 'BB', 'Barbados Dollar', 'BBD', '/flags/barbados.png', NULL),
(19, 'Bangladesh', 'BD', 'Taka', 'BDT', '/flags/bangladesh.png', NULL),
(20, 'Belgium', 'BE', 'Euro', 'EUR', '/flags/belgium.png', NULL),
(21, 'Burkina Faso', 'BF', 'CFA Franc BCEAO', 'XOF', '/flags/burkina-faso.png', NULL),
(22, 'Bulgaria', 'BG', 'Lev', 'BGL', '/flags/bulgaria.png', NULL),
(23, 'Bahrain', 'BH', 'Bahraini Dinar', 'BHD', '/flags/bahrain.png', NULL),
(24, 'Burundi', 'BI', 'Burundi Franc', 'BIF', '/flags/burundi.png', NULL),
(25, 'Benin', 'BJ', 'CFA Franc BCEAO', 'XOF', '/flags/benin.png', NULL),
(26, 'Bermuda', 'BM', 'Bermudian Dollar', 'BMD', '/flags/bermuda.png', NULL),
(27, 'Brunei Darussalam', 'BN', 'Brunei Dollar', 'BND', '/flags/brunei-darussalam.png', NULL),
(28, 'Bolivia', 'BO', 'Boliviano', 'BOB', '/flags/bolivia.png', NULL),
(29, 'Brazil', 'BR', 'Brazilian Real', 'BRL', '/flags/brazil.png', NULL),
(30, 'The Bahamas', 'BS', 'Bahamian Dollar', 'BSD', '/flags/the-bahamas.png', NULL),
(31, 'Bhutan', 'BT', 'Ngultrum', 'BTN', '/flags/bhutan.png', NULL),
(32, 'Bouvet Island', 'BV', 'Norwegian Krone', 'NOK', '/flags/bouvet-island.png', NULL),
(33, 'Botswana', 'BW', 'Pula', 'BWP', '/flags/botswana.png', NULL),
(34, 'Belarus', 'BY', 'Belarussian Ruble', 'BYR', '/flags/belarus.png', NULL),
(35, 'Belize', 'BZ', 'Belize Dollar', 'BZD', '/flags/belize.png', NULL),
(36, 'Canada', 'CA', 'Canadian Dollar', 'CAD', '/flags/canada.png', NULL),
(37, 'Cocos (Keeling) Islands', 'CC', 'Australian Dollar', 'AUD', '/flags/cocos-(keeling)-islands.png', NULL),
(39, 'Central African Republic', 'CF', 'CFA Franc BEAC', 'XAF', '/flags/central-african-republic.png', NULL),
(41, 'Switzerland', 'CH', 'Swiss Franc', 'CHF', '/flags/switzerland.png', NULL),
(42, 'Cote d''Ivoire', 'CI', 'CFA Franc BCEAO', 'XOF', '/flags/cote-d''ivoire.png', NULL),
(43, 'Cook Islands', 'CK', 'New Zealand Dollar', 'NZD', '/flags/cook-islands.png', NULL),
(44, 'Chile', 'CL', 'Chilean Peso', 'CLP', '/flags/chile.png', NULL),
(45, 'Cameroon', 'CM', 'CFA Franc BEAC', 'XAF', '/flags/cameroon.png', NULL),
(46, 'China', 'CN', 'Yuan Renminbi', 'CNY', '/flags/china.png', NULL),
(47, 'Colombia', 'CO', 'Colombian Peso', 'COP', '/flags/colombia.png', NULL),
(48, 'Costa Rica', 'CR', 'Costa Rican Colon', 'CRC', '/flags/costa-rica.png', NULL),
(49, 'Cuba', 'CU', 'Cuban Peso', 'CUP', '/flags/cuba.png', NULL),
(50, 'Cape Verde', 'CV', 'Cape Verdean Escudo', 'CVE', '/flags/cape-verde.png', NULL),
(51, 'Christmas Island', 'CX', 'Australian Dollar', 'AUD', '/flags/christmas-island.png', NULL),
(52, 'Cyprus', 'CY', 'Cyprus Pound', 'CYP', '/flags/cyprus.png', NULL),
(53, 'Czech Republic', 'CZ', 'Czech Koruna', 'CZK', '/flags/czech-republic.png', NULL),
(54, 'Germany', 'DE', 'Euro', 'EUR', '/flags/germany.png', NULL),
(55, 'Djibouti', 'DJ', 'Djibouti Franc', 'DJF', '/flags/djibouti.png', NULL),
(56, 'Denmark', 'DK', 'Danish Krone', 'DKK', '/flags/denmark.png', NULL),
(57, 'Dominica', 'DM', 'East Caribbean Dollar', 'XCD', '/flags/dominica.png', NULL),
(58, 'Dominican Republic', 'DO', 'Dominican Peso', 'DOP', '/flags/dominican-republic.png', NULL),
(59, 'Algeria', 'DZ', 'Algerian Dinar', 'DZD', '/flags/algeria.png', NULL),
(60, 'Ecuador', 'EC', 'US dollar', 'USD', '/flags/ecuador.png', NULL),
(61, 'Estonia', 'EE', 'Kroon', 'EEK', '/flags/estonia.png', NULL),
(62, 'Egypt', 'EG', 'Egyptian Pound', 'EGP', '/flags/egypt.png', NULL),
(63, 'Western Sahara', 'EH', 'Moroccan Dirham', 'MAD', '/flags/western-sahara.png', NULL),
(64, 'Eritrea', 'ER', 'Nakfa', 'ERN', '/flags/eritrea.png', NULL),
(65, 'Spain', 'ES', 'Euro', 'EUR', '/flags/spain.png', NULL),
(66, 'Ethiopia', 'ET', 'Ethiopian Birr', 'ETB', '/flags/ethiopia.png', NULL),
(67, 'Finland', 'FI', 'Euro', 'EUR', '/flags/finland.png', NULL),
(68, 'Fiji', 'FJ', 'Fijian Dollar', 'FJD', '/flags/fiji.png', NULL),
(69, 'Falkland Islands (Islas Malvinas)', 'FK', 'Falkland Islands Pound', 'FKP', '/flags/falkland-islands-(islas-malvinas).png', NULL),
(71, 'Faroe Islands', 'FO', 'Danish Krone', 'DKK', '/flags/faroe-islands.png', NULL),
(72, 'France', 'FR', 'Euro', 'EUR', '/flags/france.png', NULL),
(74, 'Gabon', 'GA', 'CFA Franc BEAC', 'XAF', '/flags/gabon.png', NULL),
(75, 'Grenada', 'GD', 'East Caribbean Dollar', 'XCD', '/flags/grenada.png', NULL),
(76, 'Georgia', 'GE', 'Lari', 'GEL', '/flags/georgia.png', NULL),
(77, 'French Guiana', 'GF', 'Euro', 'EUR', '/flags/french-guiana.png', NULL),
(78, 'Guernsey', 'GG', 'Pound Sterling', 'GBP', '/flags/guernsey.png', NULL),
(79, 'Ghana', 'GH', 'Cedi', 'GHC', '/flags/ghana.png', NULL),
(80, 'Gibraltar', 'GI', 'Gibraltar Pound', 'GIP', '/flags/gibraltar.png', NULL),
(81, 'Greenland', 'GL', 'Danish Krone', 'DKK', '/flags/greenland.png', NULL),
(82, 'The Gambia', 'GM', 'Dalasi', 'GMD', '/flags/the-gambia.png', NULL),
(83, 'Guinea', 'GN', 'Guinean Franc', 'GNF', '/flags/guinea.png', NULL),
(84, 'Guadeloupe', 'GP', 'Euro', 'EUR', '/flags/guadeloupe.png', NULL),
(85, 'Equatorial Guinea', 'GQ', 'CFA Franc BEAC', 'XAF', '/flags/equatorial-guinea.png', NULL),
(86, 'Greece', 'GR', 'Euro', 'EUR', '/flags/greece.png', NULL),
(87, 'South Georgia and the South Sandwich Islands', 'GS', 'Pound Sterling', 'GBP', '/flags/south-georgia-and-the-south-sandwich-islands.png', NULL),
(88, 'Guatemala', 'GT', 'Quetzal', 'GTQ', '/flags/guatemala.png', NULL),
(89, 'Guam', 'GU', 'US Dollar', 'USD', '/flags/guam.png', NULL),
(90, 'Guinea-Bissau', 'GW', 'CFA Franc BCEAO', 'XOF', '/flags/guinea-bissau.png', NULL),
(91, 'Guyana', 'GY', 'Guyana Dollar', 'GYD', '/flags/guyana.png', NULL),
(92, 'Hong Kong (SAR)', 'HK', 'Hong Kong Dollar', 'HKD', '/flags/hong-kong-(sar).png', NULL),
(93, 'Heard Island and McDonald Islands', 'HM', 'Australian Dollar', 'AUD', '/flags/heard-island-and-mcdonald-islands.png', NULL),
(94, 'Honduras', 'HN', 'Lempira', 'HNL', '/flags/honduras.png', NULL),
(95, 'Croatia', 'HR', 'Kuna', 'HRK', '/flags/croatia.png', NULL),
(96, 'Haiti', 'HT', 'Gourde', 'HTG', '/flags/haiti.png', NULL),
(97, 'Hungary', 'HU', 'Forint', 'HUF', '/flags/hungary.png', NULL),
(98, 'Indonesia', 'ID', 'Rupiah', 'IDR', '/flags/indonesia.png', NULL),
(99, 'Ireland', 'IE', 'Euro', 'EUR', '/flags/ireland.png', NULL),
(100, 'Israel', 'IL', 'New Israeli Sheqel', 'ILS', '/flags/israel.png', NULL),
(102, 'India', 'IN', 'Indian Rupee', 'INR', '/flags/india.png', NULL),
(103, 'British Indian Ocean Territory', 'IO', 'US Dollar', 'USD', '/flags/british-indian-ocean-territory.png', NULL),
(104, 'Iraq', 'IQ', 'Iraqi Dinar', 'IQD', '/flags/iraq.png', NULL),
(105, 'Iran', 'IR', 'Iranian Rial', 'IRR', '/flags/iran.png', NULL),
(106, 'Iceland', 'IS', 'Iceland Krona', 'ISK', '/flags/iceland.png', NULL),
(107, 'Italy', 'IT', 'Euro', 'EUR', '/flags/italy.png', NULL),
(108, 'Jersey', 'JE', 'Pound Sterling', 'GBP', '/flags/jersey.png', NULL),
(109, 'Jamaica', 'JM', 'Jamaican dollar', 'JMD', '/flags/jamaica.png', NULL),
(110, 'Jordan', 'JO', 'Jordanian Dinar', 'JOD', '/flags/jordan.png', NULL),
(111, 'Japan', 'JP', 'Yen', 'JPY', '/flags/japan.png', NULL),
(112, 'Kenya', 'KE', 'Kenyan shilling', 'KES', '/flags/kenya.png', NULL),
(113, 'Kyrgyzstan', 'KG', 'Som', 'KGS', '/flags/kyrgyzstan.png', NULL),
(114, 'Cambodia', 'KH', 'Riel', 'KHR', '/flags/cambodia.png', NULL),
(115, 'Kiribati', 'KI', 'Australian dollar', 'AUD', '/flags/kiribati.png', NULL),
(116, 'Comoros', 'KM', 'Comoro Franc', 'KMF', '/flags/comoros.png', NULL),
(117, 'Saint Kitts and Nevis', 'KN', 'East Caribbean Dollar', 'XCD', '/flags/saint-kitts-and-nevis.png', NULL),
(118, 'Korea North', 'KP', 'North Korean Won', 'KPW', '/flags/korea-north.png', NULL),
(119, 'Korea South', 'KR', 'Won', 'KRW', '/flags/korea-south.png', NULL),
(120, 'Kuwait', 'KW', 'Kuwaiti Dinar', 'KWD', '/flags/kuwait.png', NULL),
(121, 'Cayman Islands', 'KY', 'Cayman Islands Dollar', 'KYD', '/flags/cayman-islands.png', NULL),
(122, 'Kazakhstan', 'KZ', 'Tenge', 'KZT', '/flags/kazakhstan.png', NULL),
(123, 'Laos', 'LA', 'Kip', 'LAK', '/flags/laos.png', NULL),
(124, 'Lebanon', 'LB', 'Lebanese Pound', 'LBP', '/flags/lebanon.png', NULL),
(125, 'Saint Lucia', 'LC', 'East Caribbean Dollar', 'XCD', '/flags/saint-lucia.png', NULL),
(126, 'Liechtenstein', 'LI', 'Swiss Franc', 'CHF', '/flags/liechtenstein.png', NULL),
(127, 'Sri Lanka', 'LK', 'Sri Lanka Rupee', 'LKR', '/flags/sri-lanka.png', NULL),
(128, 'Liberia', 'LR', 'Liberian Dollar', 'LRD', '/flags/liberia.png', NULL),
(129, 'Lesotho', 'LS', 'Loti', 'LSL', '/flags/lesotho.png', NULL),
(130, 'Lithuania', 'LT', 'Lithuanian Litas', 'LTL', '/flags/lithuania.png', NULL),
(131, 'Luxembourg', 'LU', 'Euro', 'EUR', '/flags/luxembourg.png', NULL),
(132, 'Latvia', 'LV', 'Latvian Lats', 'LVL', '/flags/latvia.png', NULL),
(133, 'Libya', 'LY', 'Libyan Dinar', 'LYD', '/flags/libya.png', NULL),
(134, 'Morocco', 'MA', 'Moroccan Dirham', 'MAD', '/flags/morocco.png', NULL),
(135, 'Monaco', 'MC', 'Euro', 'EUR', '/flags/monaco.png', NULL),
(136, 'Moldova', 'MD', 'Moldovan Leu', 'MDL', '/flags/moldova.png', NULL),
(137, 'Madagascar', 'MG', 'Malagasy Franc', 'MGF', '/flags/madagascar.png', NULL),
(138, 'Marshall Islands', 'MH', 'US dollar', 'USD', '/flags/marshall-islands.png', NULL),
(140, 'Mali', 'ML', 'CFA Franc BCEAO', 'XOF', '/flags/mali.png', NULL),
(141, 'Burma', 'MM', 'kyat', 'MMK', '/flags/burma.png', NULL),
(142, 'Mongolia', 'MN', 'Tugrik', 'MNT', '/flags/mongolia.png', NULL),
(143, 'Macao', 'MO', 'Pataca', 'MOP', '/flags/macao.png', NULL),
(144, 'Northern Mariana Islands', 'MP', 'US Dollar', 'USD', '/flags/northern-mariana-islands.png', NULL),
(145, 'Martinique', 'MQ', 'Euro', 'EUR', '/flags/martinique.png', NULL),
(146, 'Mauritania', 'MR', 'Ouguiya', 'MRO', '/flags/mauritania.png', NULL),
(147, 'Montserrat', 'MS', 'East Caribbean Dollar', 'XCD', '/flags/montserrat.png', NULL),
(148, 'Malta', 'MT', 'Maltese Lira', 'MTL', '/flags/malta.png', NULL),
(149, 'Mauritius', 'MU', 'Mauritius Rupee', 'MUR', '/flags/mauritius.png', NULL),
(150, 'Maldives', 'MV', 'Rufiyaa', 'MVR', '/flags/maldives.png', NULL),
(151, 'Malawi', 'MW', 'Kwacha', 'MWK', '/flags/malawi.png', NULL),
(152, 'Mexico', 'MX', 'Mexican Peso', 'MXN', '/flags/mexico.png', NULL),
(153, 'Malaysia', 'MY', 'Malaysian Ringgit', 'MYR', '/flags/malaysia.png', NULL),
(154, 'Mozambique', 'MZ', 'Metical', 'MZM', '/flags/mozambique.png', NULL),
(155, 'Namibia', 'NA', 'Namibian Dollar', 'NAD', '/flags/namibia.png', NULL),
(156, 'New Caledonia', 'NC', 'CFP Franc', 'XPF', '/flags/new-caledonia.png', NULL),
(157, 'Niger', 'NE', 'CFA Franc BCEAO', 'XOF', '/flags/niger.png', NULL),
(158, 'Norfolk Island', 'NF', 'Australian Dollar', 'AUD', '/flags/norfolk-island.png', NULL),
(159, 'Nigeria', 'NG', 'Naira', 'NGN', '/flags/nigeria.png', NULL),
(160, 'Nicaragua', 'NI', 'Cordoba Oro', 'NIO', '/flags/nicaragua.png', NULL),
(161, 'Netherlands', 'NL', 'Euro', 'EUR', '/flags/netherlands.png', NULL),
(162, 'Norway', 'NO', 'Norwegian Krone', 'NOK', '/flags/norway.png', NULL),
(163, 'Nepal', 'NP', 'Nepalese Rupee', 'NPR', '/flags/nepal.png', NULL),
(164, 'Nauru', 'NR', 'Australian Dollar', 'AUD', '/flags/nauru.png', NULL),
(165, 'Niue', 'NU', 'New Zealand Dollar', 'NZD', '/flags/niue.png', NULL),
(166, 'New Zealand', 'NZ', 'New Zealand Dollar', 'NZD', '/flags/new-zealand.png', NULL),
(167, 'Oman', 'OM', 'Rial Omani', 'OMR', '/flags/oman.png', NULL),
(168, 'Panama', 'PA', 'balboa', 'PAB', '/flags/panama.png', NULL),
(169, 'Peru', 'PE', 'Nuevo Sol', 'PEN', '/flags/peru.png', NULL),
(170, 'French Polynesia', 'PF', 'CFP Franc', 'XPF', '/flags/french-polynesia.png', NULL),
(171, 'Papua New Guinea', 'PG', 'Kina', 'PGK', '/flags/papua-new-guinea.png', NULL),
(172, 'Philippines', 'PH', 'Philippine Peso', 'PHP', '/flags/philippines.png', NULL),
(173, 'Pakistan', 'PK', 'Pakistan Rupee', 'PKR', '/flags/pakistan.png', NULL),
(174, 'Poland', 'PL', 'Zloty', 'PLN', '/flags/poland.png', NULL),
(175, 'Saint Pierre and Miquelon', 'PM', 'Euro', 'EUR', '/flags/saint-pierre-and-miquelon.png', NULL),
(176, 'Pitcairn Islands', 'PN', 'New Zealand Dollar', 'NZD', '/flags/pitcairn-islands.png', NULL),
(177, 'Puerto Rico', 'PR', 'US dollar', 'USD', '/flags/puerto-rico.png', NULL),
(179, 'Portugal', 'PT', 'Euro', 'EUR', '/flags/portugal.png', NULL),
(180, 'Palau', 'PW', 'US dollar', 'USD', '/flags/palau.png', NULL),
(181, 'Paraguay', 'PY', 'Guarani', 'PYG', '/flags/paraguay.png', NULL),
(182, 'Qatar', 'QA', 'Qatari Rial', 'QAR', '/flags/qatar.png', NULL),
(184, 'Romania', 'RO', 'Leu', 'RON', '/flags/romania.png', NULL),
(185, 'Russia', 'RU', 'Russian Ruble', 'RUB', '/flags/russia.png', NULL),
(186, 'Rwanda', 'RW', 'Rwanda Franc', 'RWF', '/flags/rwanda.png', NULL),
(187, 'Saudi Arabia', 'SA', 'Saudi Riyal', 'SAR', '/flags/saudi-arabia.png', NULL),
(188, 'Solomon Islands', 'SB', 'Solomon Islands Dollar', 'SBD', '/flags/solomon-islands.png', NULL),
(189, 'Seychelles', 'SC', 'Seychelles Rupee', 'SCR', '/flags/seychelles.png', NULL),
(190, 'Sudan', 'SD', 'Sudanese Dinar', 'SDD', '/flags/sudan.png', NULL),
(191, 'Sweden', 'SE', 'Swedish Krona', 'SEK', '/flags/sweden.png', NULL),
(192, 'Singapore', 'SG', 'Singapore Dollar', 'SGD', '/flags/singapore.png', NULL),
(193, 'Saint Helena', 'SH', 'Saint Helenian Pound', 'SHP', '/flags/saint-helena.png', NULL),
(194, 'Slovenia', 'SI', 'Tolar', 'SIT', '/flags/slovenia.png', NULL),
(195, 'Svalbard', 'SJ', 'Norwegian Krone', 'NOK', '/flags/svalbard.png', NULL),
(196, 'Slovakia', 'SK', 'Slovak Koruna', 'SKK', '/flags/slovakia.png', NULL),
(197, 'Sierra Leone', 'SL', 'Leone', 'SLL', '/flags/sierra-leone.png', NULL),
(198, 'San Marino', 'SM', 'Euro', 'EUR', '/flags/san-marino.png', NULL),
(199, 'Senegal', 'SN', 'CFA Franc BCEAO', 'XOF', '/flags/senegal.png', NULL),
(200, 'Somalia', 'SO', 'Somali Shilling', 'SOS', '/flags/somalia.png', NULL),
(201, 'Suriname', 'SR', 'Suriname Guilder', 'SRG', '/flags/suriname.png', NULL),
(203, 'El Salvador', 'SV', 'El Salvador Colon', 'SVC', '/flags/el-salvador.png', NULL),
(204, 'Syria', 'SY', 'Syrian Pound', 'SYP', '/flags/syria.png', NULL),
(205, 'Swaziland', 'SZ', 'Lilangeni', 'SZL', '/flags/swaziland.png', NULL),
(206, 'Turks and Caicos Islands', 'TC', 'US Dollar', 'USD', '/flags/turks-and-caicos-islands.png', NULL),
(207, 'Chad', 'TD', 'CFA Franc BEAC', 'XAF', '/flags/chad.png', NULL),
(208, 'French Southern and Antarctic Lands', 'TF', 'Euro', 'EUR', '/flags/french-southern-and-antarctic-lands.png', NULL),
(209, 'Togo', 'TG', 'CFA Franc BCEAO', 'XOF', '/flags/togo.png', NULL),
(210, 'Thailand', 'TH', 'Baht', 'THB', '/flags/thailand.png', NULL),
(211, 'Tajikistan', 'TJ', 'Somoni', 'TJS', '/flags/tajikistan.png', NULL),
(212, 'Tokelau', 'TK', 'New Zealand Dollar', 'NZD', '/flags/tokelau.png', NULL),
(213, 'Turkmenistan', 'TM', 'Manat', 'TMM', '/flags/turkmenistan.png', NULL),
(214, 'Tunisia', 'TN', 'Tunisian Dinar', 'TND', '/flags/tunisia.png', NULL),
(215, 'Tonga', 'TO', 'Pa''anga', 'TOP', '/flags/tonga.png', NULL),
(216, 'East Timor', 'TL', 'Timor Escudo', 'TPE', '/flags/east-timor.png', NULL),
(217, 'Turkey', 'TR', 'Turkish Lira', 'TRL', '/flags/turkey.png', NULL),
(218, 'Trinidad and Tobago', 'TT', 'Trinidad and Tobago Dollar', 'TTD', '/flags/trinidad-and-tobago.png', NULL),
(219, 'Tuvalu', 'TV', 'Australian Dollar', 'AUD', '/flags/tuvalu.png', NULL),
(220, 'Taiwan', 'TW', 'New Taiwan Dollar', 'TWD', '/flags/taiwan.png', NULL),
(221, 'Tanzania', 'TZ', 'Tanzanian Shilling', 'TZS', '/flags/tanzania.png', NULL),
(222, 'Ukraine', 'UA', 'Hryvnia', 'UAH', '/flags/ukraine.png', NULL),
(223, 'Uganda', 'UG', 'Uganda Shilling', 'UGX', '/flags/uganda.png', NULL),
(224, 'United Kingdom', 'GB', 'Pound Sterling', 'GBP', '/flags/united-kingdom.png', NULL),
(225, 'United States Minor Outlying Islands', 'UM', 'US Dollar', 'USD', '/flags/united-states-minor-outlying-islands.png', NULL),
(226, 'United States', 'US', 'US Dollar', 'USD', '/flags/united-states.png', NULL),
(227, 'Uruguay', 'UY', 'Peso Uruguayo', 'UYU', '/flags/uruguay.png', NULL),
(228, 'Uzbekistan', 'UZ', 'Uzbekistan Sum', 'UZS', '/flags/uzbekistan.png', NULL),
(229, 'Holy See (Vatican City)', 'VA', 'Euro', 'EUR', '/flags/holy-see-(vatican-city).png', NULL),
(230, 'Saint Vincent and the Grenadines', 'VC', 'East Caribbean Dollar', 'XCD', '/flags/saint-vincent-and-the-grenadines.png', NULL),
(231, 'Venezuela', 'VE', 'Bolivar', 'VEB', '/flags/venezuela.png', NULL),
(232, 'British Virgin Islands', 'VG', 'US dollar', 'USD', '/flags/british-virgin-islands.png', NULL),
(233, 'Virgin Islands', 'VI', 'US Dollar', 'USD', '/flags/virgin-islands.png', NULL),
(234, 'Vietnam', 'VN', 'Dong', 'VND', '/flags/vietnam.png', NULL),
(235, 'Vanuatu', 'VU', 'Vatu', 'VUV', '/flags/vanuatu.png', NULL),
(236, 'Wallis and Futuna', 'WF', 'CFP Franc', 'XPF', '/flags/wallis-and-futuna.png', NULL),
(237, 'Samoa', 'WS', 'Tala', 'WST', '/flags/samoa.png', NULL),
(238, 'Yemen', 'YE', 'Yemeni Rial', 'YER', '/flags/yemen.png', NULL),
(239, 'Mayotte', 'YT', 'Euro', 'EUR', '/flags/mayotte.png', NULL),
(240, 'Yugoslavia', 'YU', 'Yugoslavian Dinar', 'YUM', '/flags/yugoslavia.png', NULL),
(241, 'South Africa', 'ZA', 'Rand', 'ZAR', '/flags/south-africa.png', NULL),
(242, 'Zambia', 'ZM', 'Kwacha', 'ZMK', '/flags/zambia.png', NULL),
(243, 'Zimbabwe', 'ZW', 'Zimbabwe Dollar', 'ZWD', '/flags/zimbabwe.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_currencies`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_currencies` (
  `currency_id` int(10) NOT NULL AUTO_INCREMENT,
  `currency_name` char(10) NOT NULL,
  `currency_description` varchar(70) DEFAULT NULL,
  `currency_symbol` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=171 ;

--
-- Dumping data for table `#__jbusinessdirectory_currencies`
--

INSERT INTO `#__jbusinessdirectory_currencies` (`currency_id`, `currency_name`, `currency_description`, `currency_symbol`) VALUES
(2, 'AED', 'UAE Dirham', '#'),
(3, 'AFN', 'Afghani', '?'),
(4, 'ALL', 'Lek', 'Lek'),
(5, 'AMD', 'Armenian Dram', '#'),
(6, 'ANG', 'Netherlands Antillian Guilder', 'f'),
(7, 'AOA', 'Kwanza', '#'),
(8, 'ARS', 'Argentine Peso', '$'),
(9, 'AUD', 'Australian Dollar', '$'),
(10, 'AWG', 'Aruban Guilder', 'f'),
(11, 'AZN', 'Azerbaijanian Manat', '???'),
(12, 'BAM', 'Convertible Marks', 'KM'),
(13, 'BBD', 'Barbados Dollar', '$'),
(14, 'BDT', 'Taka', '#'),
(15, 'BGN', 'Bulgarian Lev', '??'),
(16, 'BHD', 'Bahraini Dinar', '#'),
(17, 'BIF', 'Burundi Franc', '#'),
(18, 'BMD', 'Bermudian Dollar (customarily known as Bermuda Dollar)', '$'),
(19, 'BND', 'Brunei Dollar', '$'),
(20, 'BOB BOV', 'Boliviano Mvdol', '$b'),
(21, 'BRL', 'Brazilian Real', 'R$'),
(22, 'BSD', 'Bahamian Dollar', '$'),
(23, 'BWP', 'Pula', 'P'),
(24, 'BYR', 'Belarussian Ruble', 'p.'),
(25, 'BZD', 'Belize Dollar', 'BZ$'),
(26, 'CAD', 'Canadian Dollar', '$'),
(27, 'CDF', 'Congolese Franc', '#'),
(28, 'CHF', 'Swiss Franc', 'CHF'),
(29, 'CLP CLF', 'Chilean Peso Unidades de fomento', '$'),
(30, 'CNY', 'Yuan Renminbi', 'Y'),
(31, 'COP COU', 'Colombian Peso Unidad de Valor Real', '$'),
(32, 'CRC', 'Costa Rican Colon', '?'),
(33, 'CUP CUC', 'Cuban Peso Peso Convertible', '?'),
(34, 'CVE', 'Cape Verde Escudo', '#'),
(35, 'CZK', 'Czech Koruna', 'Kč'),
(36, 'DJF', 'Djibouti Franc', '#'),
(37, 'DKK', 'Danish Krone', 'kr'),
(38, 'DOP', 'Dominican Peso', 'RD$'),
(39, 'DZD', 'Algerian Dinar', '#'),
(40, 'EEK', 'Kroon', '#'),
(41, 'EGP', 'Egyptian Pound', 'L'),
(42, 'ERN', 'Nakfa', '#'),
(43, 'ETB', 'Ethiopian Birr', '#'),
(44, 'EUR', 'Euro', '€'),
(45, 'FJD', 'Fiji Dollar', '$'),
(46, 'FKP', 'Falkland Islands Pound', 'L'),
(47, 'GBP', 'Pound Sterling', 'L'),
(48, 'GEL', 'Lari', '#'),
(49, 'GHS', 'Cedi', '#'),
(50, 'GIP', 'Gibraltar Pound', 'L'),
(51, 'GMD', 'Dalasi', '#'),
(52, 'GNF', 'Guinea Franc', '#'),
(53, 'GTQ', 'Quetzal', 'Q'),
(54, 'GYD', 'Guyana Dollar', '$'),
(55, 'HKD', 'Hong Kong Dollar', '$'),
(56, 'HNL', 'Lempira', 'L'),
(57, 'HRK', 'Croatian Kuna', 'kn'),
(58, 'HTG USD', 'Gourde US Dollar', '#'),
(59, 'HUF', 'Forint', 'Ft'),
(60, 'IDR', 'Rupiah', 'Rp'),
(61, 'ILS', 'New Israeli Sheqel', '?'),
(62, 'INR', 'Indian Rupee', '#'),
(63, 'INR BTN', 'Indian Rupee Ngultrum', '#'),
(64, 'IQD', 'Iraqi Dinar', '#'),
(65, 'IRR', 'Iranian Rial', '?'),
(66, 'ISK', 'Iceland Krona', 'kr'),
(67, 'JMD', 'Jamaican Dollar', 'J$'),
(68, 'JOD', 'Jordanian Dinar', '#'),
(69, 'JPY', 'Yen', 'Y'),
(70, 'KES', 'Kenyan Shilling', '#'),
(71, 'KGS', 'Som', '??'),
(72, 'KHR', 'Riel', '?'),
(73, 'KMF', 'Comoro Franc', '#'),
(74, 'KPW', 'North Korean Won', '?'),
(75, 'KRW', 'Won', '?'),
(76, 'KWD', 'Kuwaiti Dinar', '#'),
(77, 'KYD', 'Cayman Islands Dollar', '$'),
(78, 'KZT', 'Tenge', '??'),
(79, 'LAK', 'Kip', '?'),
(80, 'LBP', 'Lebanese Pound', 'L'),
(81, 'LKR', 'Sri Lanka Rupee', '?'),
(82, 'LRD', 'Liberian Dollar', '$'),
(83, 'LTL', 'Lithuanian Litas', 'Lt'),
(84, 'LVL', 'Latvian Lats', 'Ls'),
(85, 'LYD', 'Libyan Dinar', '#'),
(86, 'MAD', 'Moroccan Dirham', '#'),
(87, 'MDL', 'Moldovan Leu', '#'),
(88, 'MGA', 'Malagasy Ariary', '#'),
(89, 'MKD', 'Denar', '???'),
(90, 'MMK', 'Kyat', '#'),
(91, 'MNT', 'Tugrik', '?'),
(92, 'MOP', 'Pataca', '#'),
(93, 'MRO', 'Ouguiya', '#'),
(94, 'MUR', 'Mauritius Rupee', '?'),
(95, 'MVR', 'Rufiyaa', '#'),
(96, 'MWK', 'Kwacha', '#'),
(97, 'MXN MXV', 'Mexican Peso Mexican Unidad de Inversion (UDI)', '$'),
(98, 'MYR', 'Malaysian Ringgit', 'RM'),
(99, 'MZN', 'Metical', 'MT'),
(100, 'NGN', 'Naira', '?'),
(101, 'NIO', 'Cordoba Oro', 'C$'),
(102, 'NOK', 'Norwegian Krone', 'kr'),
(103, 'NPR', 'Nepalese Rupee', '?'),
(104, 'NZD', 'New Zealand Dollar', '$'),
(105, 'OMR', 'Rial Omani', '?'),
(106, 'PAB USD', 'Balboa US Dollar', 'B/.'),
(107, 'PEN', 'Nuevo Sol', 'S/.'),
(108, 'PGK', 'Kina', '#'),
(109, 'PHP', 'Philippine Peso', 'Php'),
(110, 'PKR', 'Pakistan Rupee', '?'),
(111, 'PLN', 'Zloty', 'zł'),
(112, 'PYG', 'Guarani', 'Gs'),
(113, 'QAR', 'Qatari Rial', '?'),
(114, 'RON', 'New Leu', 'lei'),
(115, 'RSD', 'Serbian Dinar', '???.'),
(116, 'RUB', 'Russian Ruble', '???'),
(117, 'RWF', 'Rwanda Franc', '#'),
(118, 'SAR', 'Saudi Riyal', '?'),
(119, 'SBD', 'Solomon Islands Dollar', '$'),
(120, 'SCR', 'Seychelles Rupee', '?'),
(121, 'SDG', 'Sudanese Pound', '#'),
(122, 'SEK', 'Swedish Krona', 'kr'),
(123, 'SGD', 'Singapore Dollar', '$'),
(124, 'SHP', 'Saint Helena Pound', 'L'),
(125, 'SLL', 'Leone', '#'),
(126, 'SOS', 'Somali Shilling', 'S'),
(127, 'SRD', 'Surinam Dollar', '$'),
(128, 'STD', 'Dobra', '#'),
(129, 'SVC USD', 'El Salvador Colon US Dollar', '$'),
(130, 'SYP', 'Syrian Pound', 'L'),
(131, 'SZL', 'Lilangeni', '#'),
(132, 'THB', 'Baht', '?'),
(133, 'TJS', 'Somoni', '#'),
(134, 'TMT', 'Manat', '#'),
(135, 'TND', 'Tunisian Dinar', '#'),
(136, 'TOP', 'Pa''anga', '#'),
(137, 'TRY', 'Turkish Lira', 'TL'),
(138, 'TTD', 'Trinidad and Tobago Dollar', 'TT$'),
(139, 'TWD', 'New Taiwan Dollar', 'NT$'),
(140, 'TZS', 'Tanzanian Shilling', '#'),
(141, 'UAH', 'Hryvnia', '?'),
(142, 'UGX', 'Uganda Shilling', '#'),
(143, 'USD', 'US Dollar', '$'),
(144, 'UYU UYI', 'Peso Uruguayo Uruguay Peso en Unidades Indexadas', '$U'),
(145, 'UZS', 'Uzbekistan Sum', '??'),
(146, 'VEF', 'Bolivar Fuerte', 'Bs'),
(147, 'VND', 'Dong', '?'),
(148, 'VUV', 'Vatu', '#'),
(149, 'WST', 'Tala', '#'),
(150, 'XAF', 'CFA Franc BEAC', '#'),
(151, 'XAG', 'Silver', '#'),
(152, 'XAU', 'Gold', '#'),
(153, 'XBA', 'Bond Markets Units European Composite Unit (EURCO)', '#'),
(154, 'XBB', 'European Monetary Unit (E.M.U.-6)', '#'),
(155, 'XBC', 'European Unit of Account 9(E.U.A.-9)', '#'),
(156, 'XBD', 'European Unit of Account 17(E.U.A.-17)', '#'),
(157, 'XCD', 'East Caribbean Dollar', '$'),
(158, 'XDR', 'SDR', '#'),
(159, 'XFU', 'UIC-Franc', '#'),
(160, 'XOF', 'CFA Franc BCEAO', '#'),
(161, 'XPD', 'Palladium', '#'),
(162, 'XPF', 'CFP Franc', '#'),
(163, 'XPT', 'Platinum', '#'),
(164, 'XTS', 'Codes specifically reserved for testing purposes', '#'),
(165, 'YER', 'Yemeni Rial', '?'),
(166, 'ZAR', 'Rand', 'R'),
(167, 'ZAR LSL', 'Rand Loti', '#'),
(168, 'ZAR NAD', 'Rand Namibia Dollar', '#'),
(169, 'ZMK', 'Zambian Kwacha', '#'),
(170, 'ZWL', 'Zimbabwe Dollar', '#');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_date_formats`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_date_formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `dateFormat` varchar(45) DEFAULT NULL,
  `calendarFormat` varchar(45) NOT NULL,
  `defaultDateValue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_date_formats`
--

INSERT INTO `#__jbusinessdirectory_date_formats` (`id`, `name`, `dateFormat`, `calendarFormat`, `defaultDateValue`) VALUES
(1, 'y-m-d', 'Y-m-d', '%Y-%m-%d', '0000-00-00'),
(2, 'd-m-y', 'd-m-Y', '%d-%m-%Y', '00-00-0000'),
(3, 'm/d/y', 'm/d/Y', '%m/%d/%Y', '00-00-0000');


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_default_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_default_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `config` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `#__jbusinessdirectory_default_attributes`
--

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES
(2, 'comercial_name', 3),
(3, 'tax_code', 3),
(4, 'registration_code', 2),
(5, 'website', 2),
(6, 'company_type', 1),
(7, 'slogan', 2),
(8, 'description', 1),
(9, 'keywords', 2),
(10, 'category', 1),
(11, 'logo', 1),
(12, 'street_number', 1),
(13, 'address', 1),
(14, 'city', 1),
(15, 'region', 1),
(16, 'country', 1),
(17, 'postal_code', 1),
(18, 'map', 1),
(20, 'phone', 1),
(21, 'mobile_phone', 2),
(22, 'fax', 2),
(23, 'email', 1),
(24, 'pictures', 2),
(25, 'video', 2),
(26, 'social_networks', 2),
(27, 'short_description', 2),
(28, 'contact_person', 2),
(29, 'attachments', 2),
(30, 'custom_tab', 2),
(31, 'cover_image', 2),
(32, 'opening_hours', 2);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_discounts`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_discounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `value` float(6,2) NOT NULL,
  `percent` tinyint(1) NOT NULL DEFAULT '0',
  `price_type` tinyint(1) NOT NULL DEFAULT '1',
  `package_ids` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `uses_per_coupon` int(11) DEFAULT NULL,
  `coupon_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__jbusinessdirectory_emails`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_emails` (
  `email_id` int(10) NOT NULL AUTO_INCREMENT,
  `email_subject` char(255) NOT NULL,
  `email_name` char(255) NOT NULL,
  `email_type` varchar(255) NOT NULL,
  `email_content` blob NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `send_to_admin` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`email_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `#__jbusinessdirectory_emails`
--
INSERT INTO `#__jbusinessdirectory_emails` (`email_id`, `email_subject`, `email_name`, `email_type`, `email_content`, `status`, `send_to_admin`) VALUES
(2, 'A new review has been posted for your business listing', 'Review Email', 'Review Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577207265766965772077617320706f7374656420666f7220627573696e657373206c697374696e67205b627573696e6573735f6e616d655d3c6272202f3e596f752063616e207669657720746865207265766965772061743a205b7265766965775f6c696e6b5d203c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(3, 'Your review has received a response', 'Review Response Email', 'Review Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20236666666666663b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f752068617665207265636569766564206120726573706f6e736520666f72207468652072657669657720706f7374656420666f7220636f6d70616e79203c623e5b627573696e6573735f6e616d655d3c2f623e2e203c6272202f3e596f752063616e2076696577207468652072657669657720726573706f6e73652061743a205b7265766965775f6c696e6b5d2e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(4, 'Payment Receipt from [company_name]', 'Order E-mail', 'Order Email', 0x3c703e44656172205b637573746f6d65725f6e616d655d2c3c6272202f3e3c6272202f3e596f7572207061796d656e7420666f7220796f7572206f6e6c696e65206f7264657220706c61636564206f6e3c6272202f3e5b736974655f616464726573735d206f6e205b6f726465725f646174655d20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e596f7572207061796d656e742069732063757272656e746c79206265696e672070726f6365737365642e204f726465722070726f63657373696e6720757375616c6c793c6272202f3e74616b6573206120666577206d696e757465732e3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0204f4e4c494e45204f52444552202d205041594d454e542044455441494c5320285041594d454e542052454345495054293c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e576562736974653a205b736974655f616464726573735d3c6272202f3e4f72646572207265666572656e6365206e6f2e3a205b6f726465725f69645d3c6272202f3e5061796d656e74206d6574686f643a205b7061796d656e745f6d6574686f645d3c6272202f3e446174652f74696d653a5b6f726465725f646174655d3c6272202f3e4f726465722047656e6572616c20546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e50726f647563742f53657276696365206e616d653a5b736572766963655f6e616d655d3c6272202f3e50726963652f756e69743a205b756e69745f70726963655d3c6272202f3e54617865732028564154293a205b7461785f616d6f756e745d3c6272202f3e546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e3c6272202f3e4f7264657220737562746f74616c3a205b746f74616c5f70726963655d3c6272202f3e4f7264657220746f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e42696c6c696e6720696e666f726d6174696f6e2069733a3c6272202f3e5b62696c6c696e675f696e666f726d6174696f6e5d3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 1, 1),
(5, 'You have been contacted on JBusinessDirectory', 'Contact E-Mail', 'Contact Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e204e616d653a5b66697273745f6e616d655d205b6c6173745f6e616d655d3c6272202f3e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e3c6272202f3e5b636f6e746163745f656d61696c5f636f6e74656e745d3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(6, 'A new review abuse was reported', 'Report Abuse', 'Report Abuse Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e657720616275736520776173207265706f7274656420666f722074686520726576696577c2a03c7374726f6e673e5b7265766965775f6e616d655d3c2f7374726f6e673e2c20666f7220746865205b627573696e6573735f6e616d655d2e3c6272202f3e20596f752063616e207669657720746865207265766965772061743a205b7265766965775f6c696e6b5d3c6272202f3e20452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e3c6272202f3e3c623e4162757365206465736372697074696f6e3a3c2f623e3c6272202f3e5b61627573655f6465736372697074696f6e5d203c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(7, 'Your business listing is about to expire', 'Expiration Notification', 'Expiration Notification Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373206c697374696e672077697468206e616d65205b627573696e6573735f6e616d655d2069732061626f757420746f2065787069726520696e205b6578705f646179735d20646179732e3c6272202f3e596f752063616e20657874656e642074686520627573696e657373206c697374696e6720627920636c69636b696e672022457874656e6420706572696f6422206f6e20796f757220627573696e657373206c697374696e672064657461696c732e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(8, 'New Business Listing', 'New Business Listing Notification', 'New Company Notification Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20323570783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e657720627573696e657373206c697374696e67203c623e205b627573696e6573735f6e616d655d203c2f623e20776173206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e0d0a3c7461626c65207374796c653d2270616464696e673a203570783b22206267636f6c6f723d2223464146394641223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a203070783b2070616464696e672d72696768743a20313070783b2220726f777370616e3d2235222076616c69676e3d226d6964646c65223e5b627573696e6573735f6c6f676f5d3c2f74643e0d0a3c74643e3c623e205b627573696e6573735f6e616d655d203c2f623e3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f616464726573735d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f63617465676f72795d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f776562736974655d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f636f6e746163745f706572736f6e5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(9, 'Your business listing was approved', 'Business Listing Approval', 'Approve Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373206c697374696e672077697468206e616d65203c7374726f6e673e5b627573696e6573735f6e616d655d3c2f7374726f6e673e2077617320617070726f7665642062792061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(10, 'Payment details', 'Payment details', 'Payment Details Email', 0x3c703e44656172205b637573746f6d65725f6e616d655d2c3c6272202f3e3c6272202f3e596f7572206861766520706c6163656420616e206f7264657220666f72205b736572766963655f6e616d655d206f6e205b736974655f616464726573735d206f6e205b6f726465725f646174655d2e3c2f703e0d0a3c703e506c656173652066696e6420746865207061796d656e742064657461696c732062656c6c6f772e3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0205041594d454e542044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c2f703e0d0a3c703e5b7061796d656e745f64657461696c735d3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0204f4e4c494e45204f524445522044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e576562736974653a205b736974655f616464726573735d3c6272202f3e4f72646572207265666572656e6365206e6f2e3a205b6f726465725f69645d3c6272202f3e5061796d656e74206d6574686f643a205b7061796d656e745f6d6574686f645d3c6272202f3e446174652f74696d653a5b6f726465725f646174655d3c6272202f3e4f726465722047656e6572616c20546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e50726f647563742f53657276696365206e616d653a5b736572766963655f6e616d655d3c6272202f3e50726963652f756e69743a205b756e69745f70726963655d3c6272202f3e54617865732028564154293a205b7461785f616d6f756e745d3c6272202f3e546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e3c6272202f3e42696c6c696e6720696e666f726d6174696f6e2069733a3c6272202f3e5b62696c6c696e675f696e666f726d6174696f6e5d3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 1, 1),
(11, 'A new quote request was posted', 'Request Quote', 'Request Quote Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e65772071756f746520726571756573742077617320706f73746564206f6e205b6469726563746f72795f776562736974655d2e3c6272202f3e4e616d653a3c623e5b66697273745f6e616d655d205b6c6173745f6e616d655d3c2f623e3c6272202f3e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e43617465676f72793a205b63617465676f72795d3c6272202f3e3c6272202f3e3c623e5265717565737420636f6e74656e743c2f623e3c6272202f3e5b636f6e746163745f656d61696c5f636f6e74656e745d3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c703ec2a03c2f703e, 1, 1),
(12, 'Your business was added on our directory', 'Listing creation notification', 'Listing Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a2031707820736f6c696420236666666666663b20636f6c6f723a20233434343434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20323570783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373203c623e205b627573696e6573735f6e616d655d203c2f623e20776173206164646564206f6e206f7572206469726563746f72792e3c6272202f3e3c6272202f3e0d0a3c7461626c65207374796c653d2270616464696e673a203570783b22206267636f6c6f723d2223464146394641223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a203070783b2070616464696e672d72696768743a20313070783b2220726f777370616e3d2235222076616c69676e3d226d6964646c65223e5b627573696e6573735f6c6f676f5d3c2f74643e0d0a3c74643e3c623e205b627573696e6573735f6e616d655d203c2f623e3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f616464726573735d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f63617465676f72795d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f776562736974655d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f636f6e746163745f706572736f6e5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(13, 'Your claim was approved', 'Positive claim response', 'Claim Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20436f6e67726174756c6174696f6e732c20796f757220636c61696d20666f72206c697374696e67205b636c61696d65645f636f6d70616e795f6e616d655d20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(14, 'You claim was not approved', 'Negative claim response', 'Claim Negative Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220636c61696d20666f72206c697374696e67203c623e5b636c61696d65645f636f6d70616e795f6e616d655d3c2f623e20776173206e6f7420617070726f7665642e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(15, 'A new offer was added on your directory', 'Offer Creation', 'Offer Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577206f666665723c7374726f6e673e205b6f666665725f6e616d655d203c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c703ec2a03c2f703e, 1, 1),
(16, 'Your new offer was  approved', 'Offer Approval', 'Offer Approval Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f7572206f66666572203c623e5b6f666665725f6e616d655d3c2f623e2077617320617070726f766564206279207468652061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(17, 'A new event has been added to your directory', 'Event Creation Notification', 'Event Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577206576656e74203c7374726f6e673e5b6576656e745f6e616d655d3c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1),
(18, 'Your new event was published', 'Event Approval Notificaiton', 'Event Approval Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b206261636b67726f756e642d636f6c6f723a20236666666666663b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2077696474683a20313030253b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f7572206576656e74203c623e5b6576656e745f6e616d655d3c2f623e2077617320617070726f766564206279207468652061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_language_translations`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_language_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `language_tag` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `content_short` varchar(355) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `idx_object` (`object_id`),
  KEY `ids_langauge` (`language_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_news`
--
CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `description` text,
  `publish_date` DATETIME DEFAULT NULL,
  `retrieve_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__jbusinessdirectory_orders`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(145) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `initial_amount` decimal(8,2) DEFAULT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `amount_paid` decimal(8,2) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_at` datetime DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `transaction_id` varchar(145) DEFAULT NULL,
  `user_name` varchar(145) DEFAULT NULL,
  `service` varchar(145) DEFAULT NULL,
  `description` varchar(145) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(4) DEFAULT NULL,
  `expiration_email_date` datetime DEFAULT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(6,2) DEFAULT '0.00',
  `vat_amount` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_package` (`package_id`),
  KEY `idx_date` (`start_date`),
  KEY `idx_order` (`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#__jbusinessdirectory_orders`
--

INSERT INTO `#__jbusinessdirectory_orders` (`id`, `order_id`, `company_id`, `package_id`, `amount`, `amount_paid`, `created`, `paid_at`, `state`, `transaction_id`, `user_name`, `service`, `description`, `start_date`, `type`, `currency`, `expiration_email_date`) VALUES
(1, 'Upgrade-Package: Premium Package', 8, 4, '99.99', NULL, '2014-09-26 13:46:35', '2014-09-26 00:00:00', 1, '', NULL, 'It Company', 'Upgrade-Package: Premium Package', '2014-09-26', 1, 'USD', NULL),
(2, 'Upgrade-Package: Premium Package', 1, 4, '99.99', NULL, '2014-09-26 13:46:47', '2014-09-26 00:00:00', 1, '', NULL, 'Wedding company', 'Upgrade-Package: Premium Package', '2014-09-26', 1, 'USD', NULL),
(3, 'Upgrade-Package: Gold Package', 12, 3, '59.99', NULL, '2014-09-26 13:46:57', '2014-09-26 00:00:00', 1, '', NULL, 'Better Health', 'Upgrade-Package: Gold Package', '2014-09-26', 1, 'USD', NULL),
(4, 'Upgrade-Package: Silver Package', 9, 1, '49.99', NULL, '2014-09-26 14:17:51', '2014-09-26 00:00:00', 1, '', NULL, 'Coffe delights', 'Upgrade-Package: Silver Package', '2014-09-26', 1, 'USD', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_packages`
--


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_packages`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `special_price` decimal(8,2) DEFAULT NULL,
  `special_from_date` date DEFAULT NULL,
  `special_to_date` date DEFAULT NULL,
  `days` smallint(6) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` tinyint(4) NOT NULL,
  `time_unit` varchar(10) NOT NULL DEFAULT 'D',
  `time_amount` mediumint(9) NOT NULL DEFAULT '1',
  `max_pictures` tinyint(4) NOT NULL DEFAULT '15',
  `max_videos` tinyint(4) NOT NULL DEFAULT '5',
  `max_attachments` tinyint(4) NOT NULL DEFAULT '5',
  `max_categories` tinyint(4) NOT NULL DEFAULT '10',
  `popular` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#__jbusinessdirectory_packages`
--

INSERT INTO `#__jbusinessdirectory_packages` (`id`, `name`, `description`, `price`, `special_price`, `special_from_date`, `special_to_date`, `days`, `status`, `ordering`, `time_unit`, `time_amount`, `max_pictures`, `max_videos`, `max_attachments`, `max_categories`, `popular`) VALUES
(1, 'Silver Package', 'Silver Package', 49.99, 12.00, '1970-01-01', '1970-01-01', 70, 1, 2, 'W', 10, 15, 5, 5, 10, 0),
(2, 'Basic', 'Basic Package', 0.00, 12.00, '1970-01-01', '1970-01-01', 0, 1, 1, 'D', 0, 15, 5, 5, 10, 0),
(3, 'Gold Package', 'Gold Package', 59.99, 0.00, '1970-01-01', '1970-01-01', 180, 1, 3, 'M', 6, 15, 5, 5, 10, 0),
(4, 'Premium Package', 'Premium Package', 99.99, 0.00, '1970-01-01', '1970-01-01', 365, 1, 4, 'Y', 1, 15, 5, 5, 10, 0);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_package_fields`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_package_fields` (
  `int` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) DEFAULT NULL,
  `feature` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`int`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Dumping data for table `#__jbusinessdirectory_package_fields`
--

INSERT INTO `#__jbusinessdirectory_package_fields` (`int`, `package_id`, `feature`) VALUES
(142, 1, 'image_upload'),
(141, 1, 'html_description'),
(122, 3, 'website_address'),
(121, 3, 'company_logo'),
(120, 3, 'html_description'),
(138, 4, 'company_offers'),
(137, 4, 'contact_form'),
(136, 4, 'google_map'),
(135, 4, 'videos'),
(134, 4, 'image_upload'),
(133, 4, 'website_address'),
(132, 4, 'company_logo'),
(131, 4, 'featured_companies'),
(130, 4, 'html_description'),
(123, 3, 'image_upload'),
(124, 3, 'videos'),
(125, 3, 'google_map'),
(126, 3, 'contact_form'),
(127, 3, 'company_offers'),
(128, 3, 'company_events'),
(129, 3, 'social_networks'),
(139, 4, 'company_events'),
(140, 4, 'social_networks'),
(143, 1, 'website_address'),
(144, 1, 'videos'),
(145, 1, 'contact_form'),
(146, 1, 'google_map'),
(147, 4, 'phone'),
(148, 4, 'attachments'),
(149, 4, 'custom_tab');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payments`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `processor_type` varchar(100) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(80) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` char(5) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `message` blob,
  `payment_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `NewIndex` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payment_processors`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payment_processors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `mode` enum('live','test') NOT NULL DEFAULT 'live',
  `timeout` int(7) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` tinyint(4) DEFAULT NULL,
  `displayfront` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `#__jbusinessdirectory_payment_processors`
--

INSERT INTO `#__jbusinessdirectory_payment_processors` (`id`, `name`, `type`, `mode`, `timeout`, `status`, `ordering`, `displayfront`) VALUES
(1, 'Paypal', 'Paypal', 'test', NULL, 1, NULL, 1),
(2, 'Bank Transfer', 'wiretransfer', 'live', 0, 1, 2, 1),
(3, 'Cash', 'cash', 'live', 0, 1, 3, 0),
(4, 'Buckaroo', 'buckaroo', 'test', 60, 1, NULL, 1),
(6, 'Cardsave', 'cardsave', 'test', 15, 1, NULL, 1),
(7, 'EWay', 'eway', 'live', 10, 1, NULL, 1),
(8, 'Authorize', 'authorize', 'test', 10, 1, NULL, 1),
(9, '2checkout', 'twocheckout', 'test', 10, 1, NULL, 1),
(10, 'PayFast', 'payfast', 'test', 10, 1, NULL, 1),
(11, 'Mollie', 'mollie', 'test', 10, 1, NULL, 1);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payment_processor_fields`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payment_processor_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_name` varchar(100) DEFAULT NULL,
  `column_value` varchar(255) DEFAULT NULL,
  `processor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `#__jbusinessdirectory_payment_processor_fields`
--

INSERT INTO `#__jbusinessdirectory_payment_processor_fields` (`id`, `column_name`, `column_value`, `processor_id`) VALUES
(17, 'paypal_email', '', 1),
(88, 'bank_name', 'Bank Name', 2),
(86, 'bank_city', 'City', 2),
(87, 'bank_address', 'Address', 2),
(85, 'bank_country', 'Country', 2),
(84, 'swift_code', 'SW1321', 2),
(83, 'iban', 'BR213 123 123 123', 2),
(82, 'bank_account_number', '123123123123 ', 2),
(81, 'bank_holder_name', 'Account holder name', 2),
(89, 'secretKey', '', 4),
(90, 'merchantId', '', 4),
(100, 'merchantId', '', 6),
(98, 'preSharedKey', '', 6),
(99, 'password', '1M75C4R8', 6),
(116, 'user_name', '', 7),
(115, 'customer_id', '87654321', 7),
(120, 'transaction_key', '9eD5LC7e6h68jFxY', 8),
(119, 'api_login_id', '2bd3DEG6JZ', 8),
(123, 'account_number', '901265403', 9),
(124, 'secret_word', 'tango', 9),
(125, 'merchant_id', '10001965', 10),
(126, 'merchant_key', 'hz7almlp6ma90', 10),
(127, 'api_key', '', 11);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_reports`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `description` text,
  `selected_params` text,
  `custom_params` text,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `#__jbusinessdirectory_reports`
--

INSERT INTO `#__jbusinessdirectory_reports` (`id`, `name`, `description`, `selected_params`, `custom_params`) VALUES
(1, 'Simple Report', 'Simple Report', 'name,short_description,website,email,averageRating,viewCount,contactCount,websiteCount', NULL);


-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_reviews_question`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `is_mandatory` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_reviews_question_answer`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_question_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;
