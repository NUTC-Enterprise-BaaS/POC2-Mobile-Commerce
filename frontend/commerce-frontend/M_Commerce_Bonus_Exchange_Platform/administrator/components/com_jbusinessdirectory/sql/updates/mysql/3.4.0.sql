ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `metric` VARCHAR(1) NOT NULL DEFAULT '1'  AFTER `limit_cities` , ADD COLUMN `user_location` VARCHAR(1) NOT NULL DEFAULT '1'  AFTER `metric` , ADD COLUMN `search_type` VARCHAR(1) NOT NULL DEFAULT 0  AFTER `user_location` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `activity_radius` FLOAT(5) NULL  AFTER `longitude` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `zipcode_search_type` VARCHAR(1) NULL DEFAULT 0  AFTER `search_type` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `map_auto_show` VARCHAR(1) NULL DEFAULT 0  AFTER `zipcode_search_type` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `menu_item_id` VARCHAR(10) NULL  AFTER `nr_images_slide` ;

INSERT INTO `#__jbusinessdirectory_emails` (`email_id`, `email_subject`, `email_name`, `email_type`, `email_content`, `is_default`) VALUES
(10, 'Payment details', 'Payment details', 'Payment Details Email', 0x3c703e44656172205b637573746f6d65725f6e616d655d2c3c6272202f3e3c6272202f3e596f7572206861766520706c6163656420616e206f7264657220666f72205b736572766963655f6e616d655d206f6e205b736974655f616464726573735d206f6e205b6f726465725f646174655d2e3c2f703e0d0a3c703e506c656173652066696e6420746865207061796d656e742064657461696c732062656c6c6f772e3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0205041594d454e542044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c2f703e0d0a3c703e5b7061796d656e745f64657461696c735d3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0204f4e4c494e45204f524445522044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e576562736974653a205b736974655f616464726573735d3c6272202f3e4f72646572207265666572656e6365206e6f2e3a205b6f726465725f69645d3c6272202f3e5061796d656e74206d6574686f643a205b7061796d656e745f6d6574686f645d3c6272202f3e446174652f74696d653a5b6f726465725f646174655d3c6272202f3e4f726465722047656e6572616c20546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e50726f647563742f53657276696365206e616d653a5b736572766963655f6e616d655d3c6272202f3e50726963652f756e69743a205b756e69745f70726963655d3c6272202f3e54617865732028564154293a205b7461785f616d6f756e745d3c6272202f3e546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e3c6272202f3e4f7264657220737562746f74616c3a205b746f74616c5f70726963655d3c6272202f3e4f7264657220746f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e42696c6c696e6720696e666f726d6174696f6e2069733a3c6272202f3e5b62696c6c696e675f696e666f726d6174696f6e5d3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0);
