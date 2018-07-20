ALTER TABLE `#__jbusinessdirectory_companies`
ADD COLUMN `business_cover_image` VARCHAR(145) DEFAULT NULL;

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES
(31, 'cover_image', 2),
(32, 'opening_hours', 2);

ALTER TABLE `#__jbusinessdirectory_applicationsettings`
ADD COLUMN `show_total_business_count` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
CHANGE COLUMN `invoice_vat` `invoice_vat` VARCHAR(75) NULL DEFAULT '0';
