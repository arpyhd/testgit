
ALTER TABLE `countries` DROP INDEX `url`;

ALTER TABLE `regions` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `cities` CHANGE `region` `region` VARCHAR( 20 ) NOT NULL ;

UPDATE cities city JOIN (SELECT r.id,r.country,r.region FROM regions r) t ON t.region=city.region AND t.country=city.country SET city.region=t.id;

UPDATE `nirbuydb`.`currency` SET `country_id` = '108' WHERE `currency`.`id` =109;