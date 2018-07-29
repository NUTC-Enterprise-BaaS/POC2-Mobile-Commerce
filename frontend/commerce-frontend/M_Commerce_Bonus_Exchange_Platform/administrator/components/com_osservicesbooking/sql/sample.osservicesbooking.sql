DELETE FROM `#__app_sch_categories`;
INSERT INTO `#__app_sch_categories` (`id`, `category_name`, `category_photo`, `category_description`, `show_desc`, `published`) VALUES (1, 'Category 1','', '<p>This is the desc of category 1</p>', 1, 1),
(3, 'Category 2','', '<p>This is the description of category 2</p>', 1, 1);

DELETE FROM `#__app_sch_services` ;
INSERT INTO `#__app_sch_services` (`id`, `category_id`, `service_name`, `service_price`, `service_length`, `service_before`, `service_after`, `service_total`, `service_description`, `service_photo`, `service_time_type`, `step_in_minutes`, `repeat_day`, `repeat_week`, `repeat_month`, `ordering`, `published`, `access`) VALUES (2, 1, 'Tennis court', 75.00, 60, 10, 10, 80, 'A tennis court is where the game of tennis is played. It is a firm rectangular surface with a low net stretched across the center. The same surface can be used to play both doubles and singles.\r\nlay courts are made of crushed shale, stone, or brick. The French Open is the only Grand Slam tournament to use clay courts.', 'tennis.jpg', 1, 1, 1, 1,0, 1, 1,0),
(3, 3, 'Massage', 12.69, 59, 0, 0, 59, 'Massage is the manipulating of superficial and deeper layers of muscle and connective tissue using various techniques, to enhance function, aid in the healing process, and promote relaxation and well-being.', 'massage.jpg', 0, 1, 1, 1,0, 2, 1,0),
(5, 1, 'Beauty salon', 12.34, 90, 5, 5, 100, 'A beauty salon or beauty parlor (International spelling: beauty parlour) (or sometimes beauty shop) is an establishment dealing with cosmetic treatments for men and women.[1] Other variations of this type of business include hair salons and spas.', 'baby.jpg',  0, 1, 1, 1,0, 3, 1,0);

DELETE FROM `#__app_sch_custom_time_slots`;
INSERT INTO `#__app_sch_custom_time_slots` VALUES (34, 2, 11, 20, 12, 35, 4),
(33, 2, 0, 10, 9, 35, 3),
(32, 2, 10, 0, 11, 0, 2);

DELETE FROM `#__app_sch_employee` ;
INSERT INTO `#__app_sch_employee` (`id`, `user_id`, `employee_name`, `employee_email`, `employee_send_email`, `employee_phone`, `employee_notes`, `employee_photo`, `gusername`, `gpassword`, `gcalendarid`, `published`) VALUES
(1, 0, 'employee 1', 'test@sample.com', 1, '098757324', 'A person who is hired to provide services to a company on a regular basis in exchange for compensation and who does not provide these services as part of an independent business.', '', '', '', '', 1),
(2, 0, 'employee 2', 'test@sample.com', 1, '0987234', 'A person who is hired to provide services to a company on a regular basis in exchange for compensation and who does not provide these services as part of an independent business.', '', '', '', '', 1),
(3, 0, 'employee 3', 'test@sample.com', 1, '09983487', 'A person who is hired to provide services to a company on a regular basis in exchange for compensation and who does not provide these services as part of an independent business.', '', '', '', '', 1),
(4, 0, 'employee 4', 'test@sample.com', 1, '0094382758', 'A person who is hired to provide services to a company on a regular basis in exchange for compensation and who does not provide these services as part of an independent business.', '', '', '', '', 1),
(6, 0, 'employee 5', 'test@sample.com', 1, '09834852352', 'A person who is hired to provide services to a company on a regular basis in exchange for compensation and who does not provide these services as part of an independent business.', '', '', '', '', 1);

DELETE FROM `#__app_sch_employee_service`;
INSERT INTO `#__app_sch_employee_service` (`id`, `employee_id`, `service_id`,`vid`, `ordering`, `additional_price`, `mo`, `tu`, `we`, `th`, `fr`, `sa`, `su`) VALUES
(20, 2, 3,0, 2, 0.00, 1, 1, 1, 1, 1, 1, 1),
(15, 1, 5,0, 1, 0.00, 1, 1, 1, 1, 1, 1, 1),
(12, 3, 3,0, 1, 0.00, 1, 1, 1, 1, 1, 1, 0),
(16, 6, 2,0, 1, 0.00, 1, 1, 1, 1, 1, 1, 1),
(19, 2, 5,0, 2, 0.00, 1, 1, 1, 1, 1, 1, 1),
(21, 2, 2,1, 2, 0.00, 1, 1, 1, 1, 1, 1, 1);

DELETE FROM `#__app_sch_field_options` ;
INSERT INTO `#__app_sch_field_options` (`id`, `field_id`, `field_option`, `additional_price`) VALUES (1, 10, 'Option 1', 1.00),
(2, 10, 'Option 2', 2.00),
(3, 9, 'test 1', 1.00),
(4, 9, 'test 2', 2.00),
(5, 7, 'aaa 1', 0.00),
(6, 7, 'bbb 1', 1.00),
(7, 8, 'la la  la', 1.00),
(8, 8, 'lo lo  lo', 1.00),
(9, 8, 'ha ha', 1.00);

DELETE FROM `#__app_sch_fields` ;
INSERT INTO `#__app_sch_fields` (`id`, `field_area`, `field_type`, `field_label`, `field_options`, `ordering`, `published` ) VALUES (7, 0, 2, 'test', 'option1\r\noption2\r\noption3\r\noption4',1, 1),
(8, 1, 1, 'test2', '',2, 1),
(9, 0, 1, 'Services Field', '',3, 1),
(10, 1, 2, 'Booking form extra field', '',4, 1);

DELETE FROM `#__app_sch_service_fields` ;
INSERT INTO `#__app_sch_service_fields` VALUES (12, 2, 1),
(11, 3, 1),
(10, 5, 1),
(13, 2, 4),
(14, 5, 9),
(15, 3, 9),
(16, 2, 9),
(22, 2, 7),
(21, 3, 7),
(20, 5, 7);

DELETE FROM `#__app_sch_service_time_custom_slots` ;
INSERT INTO `#__app_sch_service_time_custom_slots` VALUES (33, 4, 5, 1),
(32, 4, 3, 1),
(31, 4, 2, 1);

DELETE FROM `#__app_sch_venues` ;
INSERT INTO `#__app_sch_venues` (`id`, `image`, `address`, `city`, `state`, `country`, `lat_add`, `long_add`, `contact_email`, `contact_name`, `contact_phone`, `disable_booking_before`, `number_date_before`, `disable_date_before`, `disable_booking_after`, `number_date_after`, `disable_date_after`, `published`) VALUES
(1, '', '152 Smith St, 11201', 'New York', 'New York', 'United States', '40.6865256', '-73.9907369', '', 'Dang Thuc Dam', '34234324', 1, 5, '2013-04-03', 2, 5, '0000-00-00', 1);

DELETE FROM `#__app_sch_venue_services` ;
INSERT INTO `#__app_sch_venue_services` (`id`, `vid`, `sid`) VALUES (3, 1, 2);