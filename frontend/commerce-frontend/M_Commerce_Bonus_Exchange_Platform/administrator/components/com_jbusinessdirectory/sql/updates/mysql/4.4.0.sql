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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `color` varchar(10) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_speaker_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_speakers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `alias` varchar(125) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company_name` varchar(55) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `speaker_language` varchar(100) DEFAULT NULL,
  `biography` text,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `conferenceId` int(11) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_categories` (
  `sessionId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_companies` (
  `sessionId` int(11) NOT NULL,
  `companyId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`companyId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_conference_session_speakers` (
  `sessionId` int(11) NOT NULL,
  `speakerId` int(11) NOT NULL,
  PRIMARY KEY (`sessionId`,`speakerId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_reports` ADD COLUMN `type` tinyint(1) DEFAULT NULL;
ALTER TABLE `#__jbusinessdirectory_conference_session_types` ADD COLUMN `clickCount` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `clickCount` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `map_latitude` varchar(45) DEFAULT NULL,
ADD COLUMN `map_longitude` varchar(45) DEFAULT NULL,
ADD COLUMN `map_zoom` TINYINT(4) NOT NULL DEFAULT '15',
ADD COLUMN `map_enable_auto_locate` tinyint(1) NOT NULL DEFAULT '1',
ADD COLUMN `map_apply_search` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `#__jbusinessdirectory_conference_sessions` ADD COLUMN `video` VARCHAR(255) NULL;

ALTER TABLE `#__jbusinessdirectory_companies` 
ADD COLUMN `custom_tab_name` VARCHAR(100) NULL COMMENT '',
ADD COLUMN `custom_tab_content` TEXT NULL COMMENT '';

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES ('7', 'link', 'Link');

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `description` text,
  `publish_date` DATETIME DEFAULT NULL,
  `retrieve_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_company_reviews_user_criteria` 
CHANGE COLUMN `score` `score` DECIMAL(2,1) NULL DEFAULT NULL COMMENT '' ;

ALTER TABLE `#__jbusinessdirectory_emails` 
CHANGE COLUMN `is_default` `status` TINYINT(1) NOT NULL DEFAULT 1,
ADD COLUMN `send_to_admin` TINYINT(1) NULL DEFAULT 1;

UPDATE `#__jbusinessdirectory_emails`  set status =1;

ALTER TABLE `#__jbusinessdirectory_companies` 
CHANGE COLUMN `keywords` `keywords` VARCHAR(150) NULL DEFAULT NULL;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `country_ids` varchar(255) DEFAULT NULL;

ALTER TABLE `#__jbusinessdirectory_countries` ADD COLUMN `country_code` varchar(4) DEFAULT NULL;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `instagram` VARCHAR(100) NULL;


DELETE FROM `#__jbusinessdirectory_countries` WHERE id=183;
DELETE FROM `#__jbusinessdirectory_countries` WHERE id=202;

UPDATE `#__jbusinessdirectory_countries` set country_code='AD' where id=1;
UPDATE `#__jbusinessdirectory_countries` set country_code='AE' where id=2;
UPDATE `#__jbusinessdirectory_countries` set country_code='AF' where id=3;
UPDATE `#__jbusinessdirectory_countries` set country_code='AG' where id=4;
UPDATE `#__jbusinessdirectory_countries` set country_code='AI' where id=5;
UPDATE `#__jbusinessdirectory_countries` set country_code='AL' where id=6;
UPDATE `#__jbusinessdirectory_countries` set country_code='AM' where id=7;
UPDATE `#__jbusinessdirectory_countries` set country_code='AN' where id=8;
UPDATE `#__jbusinessdirectory_countries` set country_code='AO' where id=9;
UPDATE `#__jbusinessdirectory_countries` set country_code='AR' where id=11;
UPDATE `#__jbusinessdirectory_countries` set country_code='AS' where id=12;
UPDATE `#__jbusinessdirectory_countries` set country_code='AT' where id=13;
UPDATE `#__jbusinessdirectory_countries` set country_code='AU' where id=14;
UPDATE `#__jbusinessdirectory_countries` set country_code='AW' where id=15;
UPDATE `#__jbusinessdirectory_countries` set country_code='AZ' where id=16;
UPDATE `#__jbusinessdirectory_countries` set country_code='BA' where id=17;
UPDATE `#__jbusinessdirectory_countries` set country_code='BB' where id=18;
UPDATE `#__jbusinessdirectory_countries` set country_code='BD' where id=19;
UPDATE `#__jbusinessdirectory_countries` set country_code='BE' where id=20;
UPDATE `#__jbusinessdirectory_countries` set country_code='BF' where id=21;
UPDATE `#__jbusinessdirectory_countries` set country_code='BG' where id=22;
UPDATE `#__jbusinessdirectory_countries` set country_code='BH' where id=23;
UPDATE `#__jbusinessdirectory_countries` set country_code='BI' where id=24;
UPDATE `#__jbusinessdirectory_countries` set country_code='BJ' where id=25;
UPDATE `#__jbusinessdirectory_countries` set country_code='BM' where id=26;
UPDATE `#__jbusinessdirectory_countries` set country_code='BN' where id=27;
UPDATE `#__jbusinessdirectory_countries` set country_code='BO' where id=28;
UPDATE `#__jbusinessdirectory_countries` set country_code='BR' where id=29;
UPDATE `#__jbusinessdirectory_countries` set country_code='BS' where id=30;
UPDATE `#__jbusinessdirectory_countries` set country_code='BT' where id=31;
UPDATE `#__jbusinessdirectory_countries` set country_code='BV' where id=32;
UPDATE `#__jbusinessdirectory_countries` set country_code='BW' where id=33;
UPDATE `#__jbusinessdirectory_countries` set country_code='BY' where id=34;
UPDATE `#__jbusinessdirectory_countries` set country_code='BZ' where id=35;
UPDATE `#__jbusinessdirectory_countries` set country_code='CA' where id=36;
UPDATE `#__jbusinessdirectory_countries` set country_code='CC' where id=37;
UPDATE `#__jbusinessdirectory_countries` set country_code='CF' where id=39;
UPDATE `#__jbusinessdirectory_countries` set country_code='CH' where id=41;
UPDATE `#__jbusinessdirectory_countries` set country_code='CI' where id=42;
UPDATE `#__jbusinessdirectory_countries` set country_code='CK' where id=43;
UPDATE `#__jbusinessdirectory_countries` set country_code='CL' where id=44;
UPDATE `#__jbusinessdirectory_countries` set country_code='CM' where id=45;
UPDATE `#__jbusinessdirectory_countries` set country_code='CN' where id=46;
UPDATE `#__jbusinessdirectory_countries` set country_code='CO' where id=47;
UPDATE `#__jbusinessdirectory_countries` set country_code='CR' where id=48;
UPDATE `#__jbusinessdirectory_countries` set country_code='CU' where id=49;
UPDATE `#__jbusinessdirectory_countries` set country_code='CV' where id=50;
UPDATE `#__jbusinessdirectory_countries` set country_code='CX' where id=51;
UPDATE `#__jbusinessdirectory_countries` set country_code='CY' where id=52;
UPDATE `#__jbusinessdirectory_countries` set country_code='CZ' where id=53;
UPDATE `#__jbusinessdirectory_countries` set country_code='DE' where id=54;
UPDATE `#__jbusinessdirectory_countries` set country_code='DJ' where id=55;
UPDATE `#__jbusinessdirectory_countries` set country_code='DK' where id=56;
UPDATE `#__jbusinessdirectory_countries` set country_code='DM' where id=57;
UPDATE `#__jbusinessdirectory_countries` set country_code='DO' where id=58;
UPDATE `#__jbusinessdirectory_countries` set country_code='DZ' where id=59;
UPDATE `#__jbusinessdirectory_countries` set country_code='EC' where id=60;
UPDATE `#__jbusinessdirectory_countries` set country_code='EE' where id=61;
UPDATE `#__jbusinessdirectory_countries` set country_code='EG' where id=62;
UPDATE `#__jbusinessdirectory_countries` set country_code='EH' where id=63;
UPDATE `#__jbusinessdirectory_countries` set country_code='ER' where id=64;
UPDATE `#__jbusinessdirectory_countries` set country_code='ES' where id=65;
UPDATE `#__jbusinessdirectory_countries` set country_code='ET' where id=66;
UPDATE `#__jbusinessdirectory_countries` set country_code='FI' where id=67;
UPDATE `#__jbusinessdirectory_countries` set country_code='FJ' where id=68;
UPDATE `#__jbusinessdirectory_countries` set country_code='FK' where id=69;
UPDATE `#__jbusinessdirectory_countries` set country_code='FO' where id=71;
UPDATE `#__jbusinessdirectory_countries` set country_code='FR' where id=72;
UPDATE `#__jbusinessdirectory_countries` set country_code='GA' where id=74;
UPDATE `#__jbusinessdirectory_countries` set country_code='GD' where id=75;
UPDATE `#__jbusinessdirectory_countries` set country_code='GE' where id=76;
UPDATE `#__jbusinessdirectory_countries` set country_code='GF' where id=77;
UPDATE `#__jbusinessdirectory_countries` set country_code='GG' where id=78;
UPDATE `#__jbusinessdirectory_countries` set country_code='GH' where id=79;
UPDATE `#__jbusinessdirectory_countries` set country_code='GI' where id=80;
UPDATE `#__jbusinessdirectory_countries` set country_code='GL' where id=81;
UPDATE `#__jbusinessdirectory_countries` set country_code='GM' where id=82;
UPDATE `#__jbusinessdirectory_countries` set country_code='GN' where id=83;
UPDATE `#__jbusinessdirectory_countries` set country_code='GP' where id=84;
UPDATE `#__jbusinessdirectory_countries` set country_code='GQ' where id=85;
UPDATE `#__jbusinessdirectory_countries` set country_code='GR' where id=86;
UPDATE `#__jbusinessdirectory_countries` set country_code='GS' where id=87;
UPDATE `#__jbusinessdirectory_countries` set country_code='GT' where id=88;
UPDATE `#__jbusinessdirectory_countries` set country_code='GU' where id=89;
UPDATE `#__jbusinessdirectory_countries` set country_code='GW' where id=90;
UPDATE `#__jbusinessdirectory_countries` set country_code='GY' where id=91;
UPDATE `#__jbusinessdirectory_countries` set country_code='HK' where id=92;
UPDATE `#__jbusinessdirectory_countries` set country_code='HM' where id=93;
UPDATE `#__jbusinessdirectory_countries` set country_code='HN' where id=94;
UPDATE `#__jbusinessdirectory_countries` set country_code='HR' where id=95;
UPDATE `#__jbusinessdirectory_countries` set country_code='HT' where id=96;
UPDATE `#__jbusinessdirectory_countries` set country_code='HU' where id=97;
UPDATE `#__jbusinessdirectory_countries` set country_code='ID' where id=98;
UPDATE `#__jbusinessdirectory_countries` set country_code='IE' where id=99;
UPDATE `#__jbusinessdirectory_countries` set country_code='IL' where id=100;
UPDATE `#__jbusinessdirectory_countries` set country_code='IN' where id=102;
UPDATE `#__jbusinessdirectory_countries` set country_code='IO' where id=103;
UPDATE `#__jbusinessdirectory_countries` set country_code='IQ' where id=104;
UPDATE `#__jbusinessdirectory_countries` set country_code='IR' where id=105;
UPDATE `#__jbusinessdirectory_countries` set country_code='IS' where id=106;
UPDATE `#__jbusinessdirectory_countries` set country_code='IT' where id=107;
UPDATE `#__jbusinessdirectory_countries` set country_code='JE' where id=108;
UPDATE `#__jbusinessdirectory_countries` set country_code='JM' where id=109;
UPDATE `#__jbusinessdirectory_countries` set country_code='JO' where id=110;
UPDATE `#__jbusinessdirectory_countries` set country_code='JP' where id=111;
UPDATE `#__jbusinessdirectory_countries` set country_code='KE' where id=112;
UPDATE `#__jbusinessdirectory_countries` set country_code='KG' where id=113;
UPDATE `#__jbusinessdirectory_countries` set country_code='KH' where id=114;
UPDATE `#__jbusinessdirectory_countries` set country_code='KI' where id=115;
UPDATE `#__jbusinessdirectory_countries` set country_code='KM' where id=116;
UPDATE `#__jbusinessdirectory_countries` set country_code='KN' where id=117;
UPDATE `#__jbusinessdirectory_countries` set country_code='KP' where id=118;
UPDATE `#__jbusinessdirectory_countries` set country_code='KR' where id=119;
UPDATE `#__jbusinessdirectory_countries` set country_code='KW' where id=120;
UPDATE `#__jbusinessdirectory_countries` set country_code='KY' where id=121;
UPDATE `#__jbusinessdirectory_countries` set country_code='KZ' where id=122;
UPDATE `#__jbusinessdirectory_countries` set country_code='LA' where id=123;
UPDATE `#__jbusinessdirectory_countries` set country_code='LB' where id=124;
UPDATE `#__jbusinessdirectory_countries` set country_code='LC' where id=125;
UPDATE `#__jbusinessdirectory_countries` set country_code='LI' where id=126;
UPDATE `#__jbusinessdirectory_countries` set country_code='LK' where id=127;
UPDATE `#__jbusinessdirectory_countries` set country_code='LR' where id=128;
UPDATE `#__jbusinessdirectory_countries` set country_code='LS' where id=129;
UPDATE `#__jbusinessdirectory_countries` set country_code='LT' where id=130;
UPDATE `#__jbusinessdirectory_countries` set country_code='LU' where id=131;
UPDATE `#__jbusinessdirectory_countries` set country_code='LV' where id=132;
UPDATE `#__jbusinessdirectory_countries` set country_code='LY' where id=133;
UPDATE `#__jbusinessdirectory_countries` set country_code='MA' where id=134;
UPDATE `#__jbusinessdirectory_countries` set country_code='MC' where id=135;
UPDATE `#__jbusinessdirectory_countries` set country_code='MD' where id=136;
UPDATE `#__jbusinessdirectory_countries` set country_code='MG' where id=137;
UPDATE `#__jbusinessdirectory_countries` set country_code='MH' where id=138;
UPDATE `#__jbusinessdirectory_countries` set country_code='ML' where id=140;
UPDATE `#__jbusinessdirectory_countries` set country_code='MM' where id=141;
UPDATE `#__jbusinessdirectory_countries` set country_code='MN' where id=142;
UPDATE `#__jbusinessdirectory_countries` set country_code='MO' where id=143;
UPDATE `#__jbusinessdirectory_countries` set country_code='MP' where id=144;
UPDATE `#__jbusinessdirectory_countries` set country_code='MQ' where id=145;
UPDATE `#__jbusinessdirectory_countries` set country_code='MR' where id=146;
UPDATE `#__jbusinessdirectory_countries` set country_code='MS' where id=147;
UPDATE `#__jbusinessdirectory_countries` set country_code='MT' where id=148;
UPDATE `#__jbusinessdirectory_countries` set country_code='MU' where id=149;
UPDATE `#__jbusinessdirectory_countries` set country_code='MV' where id=150;
UPDATE `#__jbusinessdirectory_countries` set country_code='MW' where id=151;
UPDATE `#__jbusinessdirectory_countries` set country_code='MX' where id=152;
UPDATE `#__jbusinessdirectory_countries` set country_code='MY' where id=153;
UPDATE `#__jbusinessdirectory_countries` set country_code='MZ' where id=154;
UPDATE `#__jbusinessdirectory_countries` set country_code='NA' where id=155;
UPDATE `#__jbusinessdirectory_countries` set country_code='NC' where id=156;
UPDATE `#__jbusinessdirectory_countries` set country_code='NE' where id=157;
UPDATE `#__jbusinessdirectory_countries` set country_code='NF' where id=158;
UPDATE `#__jbusinessdirectory_countries` set country_code='NG' where id=159;
UPDATE `#__jbusinessdirectory_countries` set country_code='NI' where id=160;
UPDATE `#__jbusinessdirectory_countries` set country_code='NL' where id=161;
UPDATE `#__jbusinessdirectory_countries` set country_code='NO' where id=162;
UPDATE `#__jbusinessdirectory_countries` set country_code='NP' where id=163;
UPDATE `#__jbusinessdirectory_countries` set country_code='NR' where id=164;
UPDATE `#__jbusinessdirectory_countries` set country_code='NU' where id=165;
UPDATE `#__jbusinessdirectory_countries` set country_code='NZ' where id=166;
UPDATE `#__jbusinessdirectory_countries` set country_code='OM' where id=167;
UPDATE `#__jbusinessdirectory_countries` set country_code='PA' where id=168;
UPDATE `#__jbusinessdirectory_countries` set country_code='PE' where id=169;
UPDATE `#__jbusinessdirectory_countries` set country_code='PF' where id=170;
UPDATE `#__jbusinessdirectory_countries` set country_code='PG' where id=171;
UPDATE `#__jbusinessdirectory_countries` set country_code='PH' where id=172;
UPDATE `#__jbusinessdirectory_countries` set country_code='PK' where id=173;
UPDATE `#__jbusinessdirectory_countries` set country_code='PL' where id=174;
UPDATE `#__jbusinessdirectory_countries` set country_code='PM' where id=175;
UPDATE `#__jbusinessdirectory_countries` set country_code='PN' where id=176;
UPDATE `#__jbusinessdirectory_countries` set country_code='PR' where id=177;
UPDATE `#__jbusinessdirectory_countries` set country_code='PT' where id=179;
UPDATE `#__jbusinessdirectory_countries` set country_code='PW' where id=180;
UPDATE `#__jbusinessdirectory_countries` set country_code='PY' where id=181;
UPDATE `#__jbusinessdirectory_countries` set country_code='QA' where id=182;
UPDATE `#__jbusinessdirectory_countries` set country_code='RO' where id=184;
UPDATE `#__jbusinessdirectory_countries` set country_code='RU' where id=185;
UPDATE `#__jbusinessdirectory_countries` set country_code='RW' where id=186;
UPDATE `#__jbusinessdirectory_countries` set country_code='SA' where id=187;
UPDATE `#__jbusinessdirectory_countries` set country_code='SB' where id=188;
UPDATE `#__jbusinessdirectory_countries` set country_code='SC' where id=189;
UPDATE `#__jbusinessdirectory_countries` set country_code='SD' where id=190;
UPDATE `#__jbusinessdirectory_countries` set country_code='SE' where id=191;
UPDATE `#__jbusinessdirectory_countries` set country_code='SG' where id=192;
UPDATE `#__jbusinessdirectory_countries` set country_code='SH' where id=193;
UPDATE `#__jbusinessdirectory_countries` set country_code='SI' where id=194;
UPDATE `#__jbusinessdirectory_countries` set country_code='SJ' where id=195;
UPDATE `#__jbusinessdirectory_countries` set country_code='SK' where id=196;
UPDATE `#__jbusinessdirectory_countries` set country_code='SL' where id=197;
UPDATE `#__jbusinessdirectory_countries` set country_code='SM' where id=198;
UPDATE `#__jbusinessdirectory_countries` set country_code='SN' where id=199;
UPDATE `#__jbusinessdirectory_countries` set country_code='SO' where id=200;
UPDATE `#__jbusinessdirectory_countries` set country_code='SR' where id=201;
UPDATE `#__jbusinessdirectory_countries` set country_code='SV' where id=203;
UPDATE `#__jbusinessdirectory_countries` set country_code='SY' where id=204;
UPDATE `#__jbusinessdirectory_countries` set country_code='SZ' where id=205;
UPDATE `#__jbusinessdirectory_countries` set country_code='TC' where id=206;
UPDATE `#__jbusinessdirectory_countries` set country_code='TD' where id=207;
UPDATE `#__jbusinessdirectory_countries` set country_code='TF' where id=208;
UPDATE `#__jbusinessdirectory_countries` set country_code='TG' where id=209;
UPDATE `#__jbusinessdirectory_countries` set country_code='TH' where id=210;
UPDATE `#__jbusinessdirectory_countries` set country_code='TJ' where id=211;
UPDATE `#__jbusinessdirectory_countries` set country_code='TK' where id=212;
UPDATE `#__jbusinessdirectory_countries` set country_code='TM' where id=213;
UPDATE `#__jbusinessdirectory_countries` set country_code='TN' where id=214;
UPDATE `#__jbusinessdirectory_countries` set country_code='TO' where id=215;
UPDATE `#__jbusinessdirectory_countries` set country_code='TL' where id=216;
UPDATE `#__jbusinessdirectory_countries` set country_code='TR' where id=217;
UPDATE `#__jbusinessdirectory_countries` set country_code='TT' where id=218;
UPDATE `#__jbusinessdirectory_countries` set country_code='TV' where id=219;
UPDATE `#__jbusinessdirectory_countries` set country_code='TW' where id=220;
UPDATE `#__jbusinessdirectory_countries` set country_code='TZ' where id=221;
UPDATE `#__jbusinessdirectory_countries` set country_code='UA' where id=222;
UPDATE `#__jbusinessdirectory_countries` set country_code='UG' where id=223;
UPDATE `#__jbusinessdirectory_countries` set country_code='GB' where id=224;
UPDATE `#__jbusinessdirectory_countries` set country_code='UM' where id=225;
UPDATE `#__jbusinessdirectory_countries` set country_code='US' where id=226;
UPDATE `#__jbusinessdirectory_countries` set country_code='UY' where id=227;
UPDATE `#__jbusinessdirectory_countries` set country_code='UZ' where id=228;
UPDATE `#__jbusinessdirectory_countries` set country_code='VA' where id=229;
UPDATE `#__jbusinessdirectory_countries` set country_code='VC' where id=230;
UPDATE `#__jbusinessdirectory_countries` set country_code='VE' where id=231;
UPDATE `#__jbusinessdirectory_countries` set country_code='VG' where id=232;
UPDATE `#__jbusinessdirectory_countries` set country_code='VI' where id=233;
UPDATE `#__jbusinessdirectory_countries` set country_code='VN' where id=234;
UPDATE `#__jbusinessdirectory_countries` set country_code='VU' where id=235;
UPDATE `#__jbusinessdirectory_countries` set country_code='WF' where id=236;
UPDATE `#__jbusinessdirectory_countries` set country_code='WS' where id=237;
UPDATE `#__jbusinessdirectory_countries` set country_code='YE' where id=238;
UPDATE `#__jbusinessdirectory_countries` set country_code='YT' where id=239;
UPDATE `#__jbusinessdirectory_countries` set country_code='YU' where id=240;
UPDATE `#__jbusinessdirectory_countries` set country_code='ZA' where id=241;
UPDATE `#__jbusinessdirectory_countries` set country_code='ZM' where id=242;
UPDATE `#__jbusinessdirectory_countries` set country_code='ZW' where id=243;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `max_offers` SMALLINT NOT NULL DEFAULT '10',
ADD COLUMN `max_events` SMALLINT NOT NULL DEFAULT '10';

ALTER TABLE `#__jbusinessdirectory_categories` 
ADD COLUMN `icon` varchar(50) DEFAULT NULL;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `submit_method` varchar(5) NULL DEFAULT "post";

CREATE TABLE `#__jbusinessdirectory_company_event_category` (
  `eventId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`eventId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `#__jbusinessdirectory_company_offers` 
ADD COLUMN `show_time` TINYINT(1) NULL DEFAULT 0 COMMENT '' AFTER `created`;

ALTER TABLE `#__jbusinessdirectory_company_events` CHANGE `created` `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;