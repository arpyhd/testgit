--
-- Alter Statement for table `business`
--
ALTER TABLE `business` ADD INDEX ( `business_cat_id` ) ;
ALTER TABLE `business` CHANGE `business_cat_id` `business_cat_id` BIGINT( 20 ) NULL ;
ALTER TABLE `business` ADD FOREIGN KEY ( `business_cat_id` ) REFERENCES `nirbuydb`.`tbl_business_category` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `business` ADD INDEX ( `country_id` ) ;
ALTER TABLE `business` ADD FOREIGN KEY ( `country_id` ) REFERENCES `nirbuydb`.`countries` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `business` CHANGE `country_id` `country_id` INT( 11 ) NOT NULL AFTER `business_cat_id` ;
ALTER TABLE `business` CHANGE `currency` `currency` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `country_id` ;

--
-- Alter Statement for table `tbl_business_category`
--
RENAME TABLE `nirbuydb`.`tbl_business_category` TO `nirbuydb`.`business_category` ;