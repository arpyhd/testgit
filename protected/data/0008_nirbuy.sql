ALTER TABLE `locations` CHANGE `catalogue_id` `catalogue_id` INT( 11 ) NOT NULL AFTER `business_id` ;
ALTER TABLE `products` ADD INDEX ( `added_by` ) ;
ALTER TABLE `products` ADD FOREIGN KEY ( `added_by` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `product_detail` ADD INDEX ( `modified_by` ) ;

ALTER TABLE `product_detail` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;