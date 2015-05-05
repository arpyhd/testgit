--
-- Alter Statement for table `regions`
--
ALTER TABLE `regions` CHANGE `ID` `id` INT( 11 ) NOT NULL ;

--
-- Alter Statement for table `cities`
--
ALTER TABLE `cities` CHANGE `ID` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
ALTER TABLE `cities` DROP INDEX `country`;
ALTER TABLE `cities` ADD INDEX ( `country` ) ;
ALTER TABLE `cities` ADD INDEX ( `region` ) ;

DELETE FROM `cities` WHERE `cities`.`id` = 104059;
DELETE FROM `cities` WHERE `cities`.`id` = 104060;
DELETE FROM `cities` WHERE `cities`.`id` = 103819;
DELETE FROM `cities` WHERE `cities`.`id` = 103820;

ALTER TABLE `cities` ADD FOREIGN KEY ( `country` ) REFERENCES `nirbuydb`.`countries` (
`code`
) ON DELETE CASCADE ON UPDATE CASCADE ;

--
-- Alter Statement for table `neighborhoods`
--
DELETE FROM `neighborhoods` WHERE `neighborhoods`.`id` = 1257 LIMIT 1;
DELETE FROM `neighborhoods` WHERE `neighborhoods`.`id` = 1331 LIMIT 1;

ALTER TABLE `neighborhoods` CHANGE `city_id` `city_id` INT( 11 ) UNSIGNED NOT NULL ;
ALTER TABLE `neighborhoods` ADD FOREIGN KEY ( `city_id` ) REFERENCES `nirbuydb`.`cities` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

--
-- Alter Statement for table `locations`
--
ALTER TABLE `locations` CHANGE `city_id` `city_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `locations` ADD FOREIGN KEY ( `city_id` ) REFERENCES `nirbuydb`.`cities` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `locations` ADD FOREIGN KEY ( `region_id` ) REFERENCES `nirbuydb`.`regions` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `locations` ADD FOREIGN KEY ( `neighborhood_id` ) REFERENCES `nirbuydb`.`neighborhoods` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
