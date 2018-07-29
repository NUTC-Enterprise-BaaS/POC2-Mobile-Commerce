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


ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `google_map_key` VARCHAR(45) NULL;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `add_url_language` TINYINT(1) NULL DEFAULT 0;

ALTER TABLE `#__jbusinessdirectory_packages` 
ADD COLUMN `popular` TINYINT(1) NOT NULL DEFAULT 0;
