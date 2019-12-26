CREATE TABLE `disburse` ( 
`id` BIGINT NOT NULL , 
`bank_code` VARCHAR(25) NOT NULL , 
`amount` FLOAT NOT NULL ,
`status` VARCHAR(10) NOT NULL,
`receipt` TEXT NOT NULL, 
`create_by` DATETIME NOT NULL ,
`time_received` DATETIME NULL, 
UNIQUE (`id`)
) ENGINE = InnoDB;