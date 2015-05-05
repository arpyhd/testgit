ALTER TABLE `countries` ADD `order` INT( 11 ) NOT NULL DEFAULT '1';

ALTER TABLE `regions` ADD `order` INT( 11 ) NOT NULL DEFAULT '1';

ALTER TABLE `cities` ADD `order` INT( 11 ) NOT NULL DEFAULT '1';

ALTER TABLE `currency` ADD `order` INT( 11 ) NOT NULL DEFAULT '1';

ALTER TABLE `neighborhoods` ADD `order` INT( 11 ) NOT NULL DEFAULT '1';