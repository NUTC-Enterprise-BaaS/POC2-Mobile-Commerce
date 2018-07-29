

CREATE TABLE IF NOT EXISTS `#__vikbooking_adultsdiff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idroom` int(10) NOT NULL,
  `chdisc` tinyint(1) NOT NULL DEFAULT 1,
  `valpcent` tinyint(1) NOT NULL DEFAULT 1,
  `value` decimal(12,2) DEFAULT NULL,
  `adults` int(10) NOT NULL,
  `pernight` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_busy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idroom` int(10) NOT NULL,
  `checkin` int(11) DEFAULT NULL,
  `checkout` int(11) DEFAULT NULL,
  `realback` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_characteristics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `icon` varchar(128) DEFAULT NULL,
  `textimg` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `img` varchar(128) DEFAULT NULL,
  `idcat` varchar(128) DEFAULT NULL,
  `idcarat` varchar(128) DEFAULT NULL,
  `idopt` varchar(128) DEFAULT NULL,
  `info` text DEFAULT NULL,
  `avail` tinyint(1) NOT NULL DEFAULT 1,
  `units` int(10) NOT NULL DEFAULT 1,
  `moreimgs` varchar(1024) DEFAULT NULL,
  `fromadult` int(10) NOT NULL DEFAULT 1,
  `toadult` int(10) NOT NULL DEFAULT 1,
  `fromchild` int(10) NOT NULL DEFAULT 1,
  `tochild` int(10) NOT NULL DEFAULT 1,
  `smalldesc` varchar(512) DEFAULT NULL,
  `totpeople` int(10) NOT NULL DEFAULT 1,
  `mintotpeople` int(10) NOT NULL DEFAULT 1,
  `params` text DEFAULT NULL,
  `imgcaptions` varchar(1024) DEFAULT NULL,
  `alias` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT 'cat',
  `descr` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(128) NOT NULL,
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` char(64) DEFAULT NULL,
  `country_3_code` char(3) DEFAULT NULL,
  `country_2_code` char(2) DEFAULT NULL,
  `phone_prefix` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `percentot` tinyint(1) NOT NULL DEFAULT 1,
  `value` decimal(12,2) DEFAULT NULL,
  `datevalid` varchar(64) DEFAULT NULL,
  `allvehicles` tinyint(1) NOT NULL DEFAULT 1,
  `idrooms` varchar(512) DEFAULT NULL,
  `mintotord` decimal(12,2) DEFAULT NULL,
  `idcustomer` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_custfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `type` varchar(64) NOT NULL DEFAULT 'text',
  `choose` text DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `ordering` int(10) NOT NULL DEFAULT 1,
  `isemail` tinyint(1) NOT NULL DEFAULT 0,
  `poplink` varchar(256) DEFAULT NULL,
  `isnominative` tinyint(1) NOT NULL DEFAULT 0,
  `isphone` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `cfields` text DEFAULT NULL,
  `pin` int(5) NOT NULL DEFAULT 0,
  `ujid` int(5) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_customers_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcustomer` int(10) NOT NULL,
  `idorder` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_cronjobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(128) NOT NULL,
  `class_file` varchar(128) NOT NULL,
  `params` text DEFAULT NULL,
  `last_exec` int(11) DEFAULT NULL,
  `logs` text DEFAULT NULL,
  `flag_int` int(11) NOT NULL DEFAULT 0,
  `flag_char` varchar(512) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_dispcost` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idroom` int(10) NOT NULL,
  `days` int(10) NOT NULL,
  `idprice` int(10) NOT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `attrdata` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_gpayments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `file` varchar(64) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `charge` decimal(12,2) DEFAULT NULL,
  `setconfirmed` tinyint(1) NOT NULL DEFAULT 0,
  `shownotealw` tinyint(1) NOT NULL DEFAULT 0,
  `val_pcent` tinyint(1) NOT NULL DEFAULT 1,
  `ch_disc` tinyint(1) NOT NULL DEFAULT 1,
  `params` varchar(512) DEFAULT NULL,
  `ordering` int(5) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(16) NOT NULL,
  `file_name` varchar(128) DEFAULT NULL,
  `idorder` int(10) NOT NULL,
  `idcustomer` int(10) NOT NULL,
  `created_on` int(11) DEFAULT NULL,
  `for_date` int(11) DEFAULT NULL,
  `emailed` tinyint(1) NOT NULL DEFAULT 0,
  `emailed_to` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_iva` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `aliq` decimal(12,3) NOT NULL,
  `breakdown` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_optionals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `descr` text,
  `cost` decimal(12,2) DEFAULT NULL,
  `perday` tinyint(1) NOT NULL DEFAULT 0,
  `hmany` tinyint(1) NOT NULL DEFAULT 1,
  `img` varchar(128) DEFAULT NULL,
  `idiva` int(10) DEFAULT NULL,
  `maxprice` decimal(12,2) DEFAULT NULL,
  `forcesel` tinyint(1) NOT NULL DEFAULT 0,
  `forceval` varchar(32) DEFAULT NULL,
  `perperson` tinyint(1) NOT NULL DEFAULT 0,
  `ifchildren` tinyint(1) NOT NULL DEFAULT 0,
  `maxquant` int(10) DEFAULT NULL,
  `ordering` int(10) NOT NULL DEFAULT 1,
  `ageintervals` varchar(256) DEFAULT NULL,
  `is_citytax` tinyint(1) NOT NULL DEFAULT 0,
  `is_fee` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `custdata` text DEFAULT NULL,
  `ts` int(11) DEFAULT NULL,
  `status` varchar(128) DEFAULT NULL,
  `days` int(10) DEFAULT NULL,
  `checkin` int(10) DEFAULT NULL,
  `checkout` int(10) DEFAULT NULL,
  `custmail` varchar(128) DEFAULT NULL,
  `sid` varchar(128) DEFAULT NULL,
  `totpaid` decimal(12,2) DEFAULT NULL,
  `idpayment` varchar(128) DEFAULT NULL,
  `ujid` int(10) NOT NULL DEFAULT 0,
  `coupon` varchar(128) DEFAULT NULL,
  `roomsnum` int(10) NOT NULL DEFAULT 1,
  `total` decimal(12,2) DEFAULT NULL,
  `confirmnumber` varchar(64) DEFAULT NULL,
  `idorderota` varchar(64) DEFAULT NULL,
  `channel` varchar(64) DEFAULT NULL,
  `chcurrency` varchar(32) DEFAULT NULL,
  `paymentlog` text DEFAULT NULL,
  `paymcount` tinyint(2) NOT NULL DEFAULT 0,
  `adminnotes` text DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  `country` varchar(5) DEFAULT NULL,
  `tot_taxes` decimal(12,2) DEFAULT NULL,
  `tot_city_taxes` decimal(12,2) DEFAULT NULL,
  `tot_fees` decimal(12,2) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `pkg` int(10) DEFAULT NULL,
  `cmms` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_ordersbusy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idorder` int(10) NOT NULL,
  `idbusy` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_ordersrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idorder` int(10) NOT NULL,
  `idroom` int(10) NOT NULL,
  `adults` int(10) NOT NULL,
  `children` int(10) NOT NULL DEFAULT 0,
  `idtar` int(10) DEFAULT NULL,
  `optionals` varchar(128) DEFAULT NULL,
  `childrenage` varchar(256) DEFAULT NULL,
  `t_first_name` varchar(64) DEFAULT NULL,
  `t_last_name` varchar(64) DEFAULT NULL,
  `roomindex` int(5) DEFAULT NULL,
  `pkg_id` int(10) DEFAULT NULL,
  `pkg_name` varchar(128) DEFAULT NULL,
  `cust_cost` decimal(12,2) DEFAULT NULL,
  `cust_idiva` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT 'package',
  `alias` varchar(128) NOT NULL DEFAULT 'package',
  `img` varchar(128) DEFAULT NULL,
  `dfrom` int(11) NOT NULL,
  `dto` int(11) NOT NULL,
  `excldates` varchar(512) NOT NULL DEFAULT '',
  `minlos` tinyint(2) NOT NULL DEFAULT 1,
  `maxlos` tinyint(2) NOT NULL DEFAULT 0,
  `cost` decimal(12,3) NOT NULL,
  `idiva` int(10) DEFAULT NULL,
  `pernight_total` tinyint(1) NOT NULL DEFAULT 1,
  `perperson` tinyint(1) NOT NULL DEFAULT 1,
  `descr` text DEFAULT NULL,
  `shortdescr` varchar(512) DEFAULT NULL,
  `benefits` varchar(512) DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `showoptions` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_packages_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpackage` int(10) NOT NULL,
  `idroom` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT 'cost',
  `attr` varchar(128) DEFAULT NULL,
  `idiva` int(10) DEFAULT NULL,
  `breakfast_included` tinyint(1) DEFAULT 0,
  `free_cancellation` tinyint(1) DEFAULT 0,
  `canc_deadline` int(5) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_restrictions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT 'restriction',
  `month` tinyint(2) NOT NULL DEFAULT 7,
  `wday` tinyint(1) DEFAULT NULL,
  `minlos` tinyint(2) NOT NULL DEFAULT 1,
  `multiplyminlos` tinyint(1) NOT NULL DEFAULT 0,
  `maxlos` tinyint(2) NOT NULL DEFAULT 0,
  `dfrom` int(10) DEFAULT NULL,
  `dto` int(10) DEFAULT NULL,
  `wdaytwo` tinyint(1) DEFAULT NULL,
  `wdaycombo` varchar(28) DEFAULT NULL,
  `allrooms` tinyint(1) NOT NULL DEFAULT 1,
  `idrooms` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_seasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `diffcost` decimal(12,3) DEFAULT NULL,
  `idrooms` varchar(256) DEFAULT NULL,
  `spname` varchar(64) DEFAULT NULL,
  `wdays` varchar(16) DEFAULT NULL,
  `checkinincl` tinyint(1) NOT NULL DEFAULT 0,
  `val_pcent` tinyint(1) NOT NULL DEFAULT 2,
  `losoverride` varchar(512) DEFAULT NULL,
  `roundmode` varchar(32) DEFAULT NULL,
  `year` int(5) DEFAULT NULL,
  `idprices` varchar(256) DEFAULT NULL,
  `promo` tinyint(1) NOT NULL DEFAULT 0,
  `promotxt` text DEFAULT NULL,
  `promodaysadv` int(5) DEFAULT NULL,
  `promominlos` tinyint(1) NOT NULL DEFAULT 0,
  `occupancy_ovr` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(128) NOT NULL,
  `exp` text,
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_tmplock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idroom` int(10) NOT NULL,
  `checkin` int(11) NOT NULL,
  `checkout` int(11) NOT NULL,
  `until` int(11) NOT NULL,
  `realback` int(11) DEFAULT NULL,
  `idorder` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__vikbooking_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(64) NOT NULL,
  `lang` varchar(16) NOT NULL,
  `reference_id` int(10) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('showfooter','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('timeopenstore','43200-36000');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('hoursmorebookingback','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('hoursmoreroomavail','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('allowbooking','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('dateformat','%d/%m/%Y');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('showcategories','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('showchildren','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('fronttitletag','h3');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('fronttitletagclass','');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('searchbtnclass','button');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('ivainclusa','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('tokenform','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('ccpaypal','');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('paytotal','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('payaccpercent','50');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('minuteslock','20');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('sendjutility','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('currencyname','EUR');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('currencysymb','&euro;');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('currencycodepp','EUR');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('sitelogo','vikbooking.jpg');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('showpartlyreserved','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('numcalendars','3');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('requirelogin','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('loadjquery','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('calendar','jqueryui');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('enablecoupons','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('theme','default');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('numrooms','5');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('numadults','1-10');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('numchildren','0-4');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('autodefcalnights','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('numberformat','2:.:,');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('mindaysadvance','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('multipay','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('typedeposit','pcent');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('taxsummary','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smartsearch','automatic');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('maxdate','+2y');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('firstwday','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('todaybookings','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('multilang','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('bootstrap','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('enablepin','1');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('senderemail','');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('autoroomunit','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('closingdates','[]');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smsapi','');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smsautosend','0');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smssendto','[]');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smsadminphone','');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('smsparams','[]');
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('cronkey', FLOOR(1000 + (RAND() * 9000)));
INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES ('showcheckinoutonly','0');
INSERT INTO `#__vikbooking_config` (`param`, `setting`) VALUES('invoiceinum', '0');
INSERT INTO `#__vikbooking_config` (`param`, `setting`) VALUES('invoicesuffix', '/WEB');
INSERT INTO `#__vikbooking_config` (`param`, `setting`) VALUES('invcompanyinfo', '');

INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('disabledbookingmsg','Disabled Booking Message','');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('fronttitle','Page Title','VikBooking');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('searchbtnval','Search Button Text','');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('intromain','Main Page Introducing Text','');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('closingmain','Main Page Closing Text','Powered by VikBooking');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('paymentname','Paypal Transaction Name','Rooms Reservation');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('disclaimer','Disclaimer Text','');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('footerordmail','Footer Text Order eMail','');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('smsadmintpl','Administrator SMS Template','A new booking ({booking_id}) for {tot_guests} guests was confirmed by {customer_name} from {customer_country}.\nCheck-in on {checkin_date} for {num_nights} nights.');
INSERT INTO `#__vikbooking_texts` (`param`,`exp`,`setting`) VALUES ('smscustomertpl','Customer SMS Template','Dear {customer_name},\nYour booking for {num_nights} nights and {tot_guests} guests has been confirmed! Your PIN Code is {customer_pin}');

INSERT INTO `#__vikbooking_gpayments` (`name`,`file`,`published`,`note`,`charge`,`setconfirmed`,`shownotealw`,`val_pcent`,`ch_disc`,`ordering`) VALUES ('Bank Transfer','bank_transfer.php','0','<p>Bank Transfer Info...</p>','0.00','1','1','1','1', 1);
INSERT INTO `#__vikbooking_gpayments` (`name`,`file`,`published`,`note`,`charge`,`setconfirmed`,`shownotealw`,`val_pcent`,`ch_disc`,`ordering`) VALUES ('PayPal','paypal.php','0','<p></p>','0.00','0','0','1','1', 2);
INSERT INTO `#__vikbooking_gpayments` (`name`,`file`,`published`,`note`,`charge`,`setconfirmed`,`shownotealw`,`val_pcent`,`ch_disc`,`ordering`) VALUES ('Offline Credit Card','offline_credit_card.php','0','<p></p>','0.00','0','0','1','1', 3);

INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('VBSEPDRIVERD','separator','','0','1','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_NAME','text','','1','2','0','', 1, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_LNAME','text','','1','3','0','', 1, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_EMAIL','text','','1','4','1','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_PHONE','text','','0','5','0','', 0, 1);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_ADDRESS','text','','0','6','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_ZIP','text','','0','7','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_CITY','text','','0','8','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_STATE','country','','0','9','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_DBIRTH','text','','0','10','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_SPREQUESTS','textarea','','0','11','0','', 0, 0);
INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES ('ORDER_TERMSCONDITIONS','checkbox','','1','12','0','', 0, 0);

INSERT INTO `#__vikbooking_countries` (`country_name`, `country_3_code`, `country_2_code`, `phone_prefix`) VALUES
('Afghanistan', 'AFG', 'AF', '+93'),
('Aland', 'ALA', 'AX', '+358 18'),
('Albania', 'ALB', 'AL', '+355'),
('Algeria', 'DZA', 'DZ', '+213'),
('American Samoa', 'ASM', 'AS', '+1 684'),
('Andorra', 'AND', 'AD', '+376'),
('Angola', 'AGO', 'AO', '+244'),
('Anguilla', 'AIA', 'AI', '+1 264'),
('Antarctica', 'ATA', 'AQ', '+6721'),
('Antigua and Barbuda', 'ATG', 'AG', '+1 268'),
('Argentina', 'ARG', 'AR', '+54'),
('Armenia', 'ARM', 'AM', '+374'),
('Aruba', 'ABW', 'AW', '+297'),
('Ascension Island', 'ASC', 'AC', '+247'),
('Australia', 'AUS', 'AU', '+61'),
('Austria', 'AUT', 'AT', '+43'),
('Azerbaijan', 'AZE', 'AZ', '+994'),
('Bahamas', 'BHS', 'BS', '+1 242'),
('Bahrain', 'BHR', 'BH', '+973'),
('Bangladesh', 'BGD', 'BD', '+880'),
('Barbados', 'BRB', 'BB', '+1 246'),
('Belarus', 'BLR', 'BY', '+375'),
('Belgium', 'BEL', 'BE', '+32'),
('Belize', 'BLZ', 'BZ', '+501'),
('Benin', 'BEN', 'BJ', '+229'),
('Bermuda', 'BMU', 'BM', '+1 441'),
('Bhutan', 'BTN', 'BT', '+975'),
('Bolivia', 'BOL', 'BO', '+591'),
('Bosnia and Herzegovina', 'BIH', 'BA', '+387'),
('Botswana', 'BWA', 'BW', '+267'),
('Bouvet Island', 'BVT', 'BV', '+47'),
('Brazil', 'BRA', 'BR', '+55'),
('British Indian Ocean Territory', 'IOT', 'IO', '+246'),
('British Virgin Islands', 'VGB', 'VG', '+1 284'),
('Brunei', 'BRN', 'BN', '+673'),
('Bulgaria', 'BGR', 'BG', '+359'),
('Burkina Faso', 'BFA', 'BF', '+226'),
('Burundi', 'BDI', 'BI', '+257'),
('Cambodia', 'KHM', 'KH', '+855'),
('Cameroon', 'CMR', 'CM', '+237'),
('Canada', 'CAN', 'CA', '+1'),
('Cape Verde', 'CPV', 'CV', '+238'),
('Cayman Islands', 'CYM', 'KY', '+1 345'),
('Central African Republic', 'CAF', 'CF', '+236'),
('Chad', 'TCD', 'TD', '+235'),
('Chile', 'CHL', 'CL', '+56'),
('China', 'CHN', 'CN', '+86'),
('Christmas Island', 'CXR', 'CX', '+61 8964'),
('Cocos Islands', 'CCK', 'CC', '+61 8962'),
('Colombia', 'COL', 'CO', '+57'),
('Comoros', 'COM', 'KM', '+269'),
('Cook Islands', 'COK', 'CK', '+682'),
('Costa Rica', 'CRI', 'CR', '+506'),
('Cote d Ivoire', 'CIV', 'CI', '+225'),
('Croatia', 'HRV', 'HR', '+385'),
('Cuba', 'CUB', 'CU', '+53'),
('Cyprus', 'CYP', 'CY', '+357'),
('Czech Republic', 'CZE', 'CZ', '+420'),
('Democratic Republic of the Congo', 'COD', 'CD', '+243'),
('Denmark', 'DNK', 'DK', '+45'),
('Djibouti', 'DJI', 'DJ', '+253'),
('Dominica', 'DMA', 'DM', '+1 767'),
('Dominican Republic', 'DOM', 'DO', '+1 809'),
('East Timor', 'TLS', 'TL', '+670'),
('Ecuador', 'ECU', 'EC', '+593'),
('Egypt', 'EGY', 'EG', '+20'),
('El Salvador', 'SLV', 'SV', '+503'),
('Equatorial Guinea', 'GNQ', 'GQ', '+240'),
('Eritrea', 'ERI', 'ER', '+291'),
('Estonia', 'EST', 'EE', '+372'),
('Ethiopia', 'ETH', 'ET', '+251'),
('Falkland Islands', 'FLK', 'FK', '+500'),
('Faroe Islands', 'FRO', 'FO', '+298'),
('Fiji', 'FJI', 'FJ', '+679'),
('Finland', 'FIN', 'FI', '+358'),
('France', 'FRA', 'FR', '+33'),
('French Austral and Antarctic Territories', 'ATF', 'TF', '+33'),
('French Guiana', 'GUF', 'GF', '+594'),
('French Polynesia', 'PYF', 'PF', '+689'),
('Gabon', 'GAB', 'GA', '+241'),
('Gambia', 'GMB', 'GM', '+220'),
('Georgia', 'GEO', 'GE', '+995'),
('Germany', 'DEU', 'DE', '+49'),
('Ghana', 'GHA', 'GH', '+233'),
('Gibraltar', 'GIB', 'GI', '+350'),
('Greece', 'GRC', 'GR', '+30'),
('Greenland', 'GRL', 'GL', '+299'),
('Grenada', 'GRD', 'GD', '+1 473'),
('Guadeloupe', 'GLP', 'GP', '+590'),
('Guam', 'GUM', 'GU', '+1 671'),
('Guatemala', 'GTM', 'GT', '+502'),
('Guernsey', 'GGY', 'GG', '+44 1481'),
('Guinea', 'GIN', 'GN', '+224'),
('Guinea-Bissau', 'GNB', 'GW', '+245'),
('Guyana', 'GUY', 'GY', '+592'),
('Haiti', 'HTI', 'HT', '+509'),
('Heard and McDonald Islands', 'HMD', 'HM', '+61'),
('Honduras', 'HND', 'HN', '+504'),
('Hong Kong', 'HKG', 'HK', '+852'),
('Hungary', 'HUN', 'HU', '+36'),
('Iceland', 'ISL', 'IS', '+354'),
('India', 'IND', 'IN', '+91'),
('Indonesia', 'IDN', 'ID', '+62'),
('Iran', 'IRN', 'IR', '+98'),
('Iraq', 'IRQ', 'IQ', '+964'),
('Ireland', 'IRL', 'IE', '+353'),
('Isle of Man', 'IMN', 'IM', '+44 1624'),
('Israel', 'ISR', 'IL', '+972'),
('Italy', 'ITA', 'IT', '+39'),
('Jamaica', 'JAM', 'JM', '+1 876'),
('Japan', 'JPN', 'JP', '+81'),
('Jersey', 'JEY', 'JE', '+44 1534'),
('Jordan', 'JOR', 'JO', '+962'),
('Kazakhstan', 'KAZ', 'KZ', '+7'),
('Kenya', 'KEN', 'KE', '+254'),
('Kiribati', 'KIR', 'KI', '+686'),
('Kosovo', 'KV', 'KV', '+381'),
('Kuwait', 'KWT', 'KW', '+965'),
('Kyrgyzstan', 'KGZ', 'KG', '+996'),
('Laos', 'LAO', 'LA', '+856'),
('Latvia', 'LVA', 'LV', '+371'),
('Lebanon', 'LBN', 'LB', '+961'),
('Lesotho', 'LSO', 'LS', '+266'),
('Liberia', 'LBR', 'LR', '+231'),
('Libya', 'LBY', 'LY', '+218'),
('Liechtenstein', 'LIE', 'LI', '+423'),
('Lithuania', 'LTU', 'LT', '+370'),
('Luxembourg', 'LUX', 'LU', '+352'),
('Macau', 'MAC', 'MO', '+853'),
('Macedonia', 'MKD', 'MK', '+389'),
('Madagascar', 'MDG', 'MG', '+261'),
('Malawi', 'MWI', 'MW', '+265'),
('Malaysia', 'MYS', 'MY', '+60'),
('Maldives', 'MDV', 'MV', '+960'),
('Mali', 'MLI', 'ML', '+223'),
('Malta', 'MLT', 'MT', '+356'),
('Marshall Islands', 'MHL', 'MH', '+692'),
('Martinique', 'MTQ', 'MQ', '+596'),
('Mauritania', 'MRT', 'MR', '+222'),
('Mauritius', 'MUS', 'MU', '+230'),
('Mayotte', 'MYT', 'YT', '+262'),
('Mexico', 'MEX', 'MX', '+52'),
('Micronesia', 'FSM', 'FM', '+691'),
('Moldova', 'MDA', 'MD', '+373'),
('Monaco', 'MCO', 'MC', '+377'),
('Mongolia', 'MNG', 'MN', '+976'),
('Montenegro', 'MNE', 'ME', '+382'),
('Montserrat', 'MSR', 'MS', '+1 664'),
('Morocco', 'MAR', 'MA', '+212'),
('Mozambique', 'MOZ', 'MZ', '+258'),
('Myanmar', 'MMR', 'MM', '+95'),
('Namibia', 'NAM', 'NA', '+264'),
('Nauru', 'NRU', 'NR', '+674'),
('Nepal', 'NPL', 'NP', '+977'),
('Netherlands', 'NLD', 'NL', '+31'),
('Netherlands Antilles', 'ANT', 'AN', '+599'),
('New Caledonia', 'NCL', 'NC', '+687'),
('New Zealand', 'NZL', 'NZ', '+64'),
('Nicaragua', 'NIC', 'NI', '+505'),
('Niger', 'NER', 'NE', '+227'),
('Nigeria', 'NGA', 'NG', '+234'),
('Niue', 'NIU', 'NU', '+683'),
('Norfolk Island', 'NFK', 'NF', '+6723'),
('North Korea', 'PRK', 'KP', '+850'),
('Northern Mariana Islands', 'MNP', 'MP', '+1 670'),
('Norway', 'NOR', 'NO', '+47'),
('Oman', 'OMN', 'OM', '+968'),
('Pakistan', 'PAK', 'PK', '+92'),
('Palau', 'PLW', 'PW', '+680'),
('Palestine', 'PSE', 'PS', '+970'),
('Panama', 'PAN', 'PA', '+507'),
('Papua New Guinea', 'PNG', 'PG', '+675'),
('Paraguay', 'PRY', 'PY', '+595'),
('Peru', 'PER', 'PE', '+51'),
('Philippines', 'PHL', 'PH', '+63'),
('Pitcairn Islands', 'PCN', 'PN', '+649'),
('Poland', 'POL', 'PL', '+48'),
('Portugal', 'PRT', 'PT', '+351'),
('Puerto Rico', 'PRI', 'PR', '+1 787'),
('Qatar', 'QAT', 'QA', '+974'),
('Republic of the Congo', 'COG', 'CG', '+242'),
('Reunion', 'REU', 'RE', '+262'),
('Romania', 'ROM', 'RO', '+40'),
('Russia', 'RUS', 'RU', '+7'),
('Rwanda', 'RWA', 'RW', '+250'),
('Saint Helena', 'SHN', 'SH', '+290'),
('Saint Kitts and Nevis', 'KNA', 'KN', '+1 869'),
('Saint Lucia', 'LCA', 'LC', '+1 758'),
('Saint Pierre and Miquelon', 'SPM', 'PM', '+508'),
('Saint Vincent and the Grenadines', 'VCT', 'VC', '+1 784'),
('Samoa', 'WSM', 'WS', '+685'),
('San Marino', 'SMR', 'SM', '+378'),
('Sao Tome and Principe', 'STP', 'ST', '+239'),
('Saudi Arabia', 'SAU', 'SA', '+966'),
('Senegal', 'SEN', 'SN', '+221'),
('Serbia', 'SRB', 'RS', '+381'),
('Seychelles', 'SYC', 'SC', '+248'),
('Sierra Leone', 'SLE', 'SL', '+232'),
('Singapore', 'SGP', 'SG', '+65'),
('Sint Maarten', 'SXM', 'SX', '+1 721'),
('Slovakia', 'SVK', 'SK', '+421'),
('Slovenia', 'SVN', 'SI', '+386'),
('Solomon Islands', 'SLB', 'SB', '+677'),
('Somalia', 'SOM', 'SO', '+252'),
('South Africa', 'ZAF', 'ZA', '+27'),
('South Georgia and the South Sandwich Islands', 'SGS', 'GS', '+44'),
('South Korea', 'KOR', 'KR', '+82'),
('South Sudan', 'SSD', 'SS', '+211'),
('Spain', 'ESP', 'ES', '+34'),
('Sri Lanka', 'LKA', 'LK', '+94'),
('Sudan', 'SDN', 'SD', '+249'),
('Suriname', 'SUR', 'SR', '+597'),
('Svalbard and Jan Mayen Islands', 'SJM', 'SJ', '+47'),
('Swaziland', 'SWZ', 'SZ', '+268'),
('Sweden', 'SWE', 'SE', '+46'),
('Switzerland', 'CHE', 'CH', '+41'),
('Syria', 'SYR', 'SY', '+963'),
('Taiwan', 'TWN', 'TW', '+886'),
('Tajikistan', 'TJK', 'TJ', '+992'),
('Tanzania', 'TZA', 'TZ', '+255'),
('Thailand', 'THA', 'TH', '+66'),
('Togo', 'TGO', 'TG', '+228'),
('Tokelau', 'TKL', 'TK', '+690'),
('Tonga', 'TON', 'TO', '+676'),
('Trinidad and Tobago', 'TTO', 'TT', '+1 868'),
('Tunisia', 'TUN', 'TN', '+216'),
('Turkey', 'TUR', 'TR', '+90'),
('Turkmenistan', 'TKM', 'TM', '+993'),
('Turks and Caicos Islands', 'TCA', 'TC', '+1 649'),
('Tuvalu', 'TUV', 'TV', '+688'),
('U.S. Virgin Islands', 'VIR', 'VI', '+1 340'),
('Uganda', 'UGA', 'UG', '+256'),
('Ukraine', 'UKR', 'UA', '+380'),
('United Arab Emirates', 'ARE', 'AE', '+971'),
('United Kingdom', 'GBR', 'GB', '+44'),
('United States', 'USA', 'US', '+1'),
('Uruguay', 'URY', 'UY', '+598'),
('Uzbekistan', 'UZB', 'UZ', '+998'),
('Vanuatu', 'VUT', 'VU', '+678'),
('Vatican City', 'VAT', 'VA', '+379'),
('Venezuela', 'VEN', 'VE', '+58'),
('Vietnam', 'VNM', 'VN', '+84'),
('Wallis and Futuna', 'WLF', 'WF', '+681'),
('Western Sahara', 'ESH', 'EH', '+212 28'),
('Yemen', 'YEM', 'YE', '+967'),
('Zambia', 'ZMB', 'ZM', '+260'),
('Zimbabwe', 'ZWE', 'ZW', '+263');