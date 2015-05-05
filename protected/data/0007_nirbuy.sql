--
-- Alter Statement for table `business_category`
--
ALTER TABLE `business_category` CHANGE `status` `status` TINYINT( 1 ) NULL DEFAULT NULL ;
ALTER TABLE `business_category` CHANGE `status` `disabled` TINYINT( 1 ) NULL DEFAULT NULL ;

ALTER TABLE `business_category` CHANGE `createdon` `created_on` BIGINT( 20 ) NULL DEFAULT NULL ,
CHANGE `updatedon` `updated_on` BIGINT( 20 ) NULL DEFAULT NULL ,
CHANGE `createdby` `created_by` BIGINT( 20 ) NULL DEFAULT NULL ,
CHANGE `updatedby` `updated_by` BIGINT( 20 ) NULL DEFAULT NULL ;

ALTER TABLE `business_category` CHANGE `disabled` `disabled` TINYINT( 1 ) NULL DEFAULT NULL AFTER `updated_by` ;
UPDATE business_category SET disabled = NULL WHERE disabled = 1