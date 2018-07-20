CREATE TABLE IF NOT EXISTS `#__acpush_users` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`user_id` INT(11)  NOT NULL ,
`device_id` TEXT NOT NULL ,
`type` VARCHAR(255)  NOT NULL ,
`created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`active` TINYINT(4)  NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__acpush_config` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`type` VARCHAR(11)  NOT NULL ,
`name` VARCHAR(1)  NOT NULL ,
`active` TINYINT(11)  NOT NULL ,
`is_prod` TINYINT(4)  NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

