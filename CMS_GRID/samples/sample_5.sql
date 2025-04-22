CREATE TABLE `table_5_1` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `parentid` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE `table_5_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `table_5_1_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;


insert into `table_5_1` ( `id`,  `name`, `parentid`) values(1, 'name 1', null);
insert into `table_5_1` ( `id`,  `name`, `parentid`) values(2, 'name 2', 1);
insert into `table_5_1` ( `id`,  `name`, `parentid`) values(3, 'name 3', 2);

insert into `table_5_2` ( `id`,  `caption`, `table_5_1_id`) values(1, 'caption 1', 1);
insert into `table_5_2` ( `id`,  `caption`, `table_5_1_id`) values(2, 'caption 2', 3);
insert into `table_5_2` ( `id`,  `caption`, `table_5_1_id`) values(3, 'caption 3', 2);