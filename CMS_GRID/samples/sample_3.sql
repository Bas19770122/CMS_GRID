CREATE TABLE `table_3_1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

CREATE TABLE `table_3_2` (
  `id` int NOT NULL,
  `caption` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;


insert into `table_3_1` ( `id`,  `name`) values(1, 'name 1');
insert into `table_3_1` ( `id`,  `name`) values(2, 'name 2');
insert into `table_3_1` ( `id`,  `name`) values(3, 'name 3');

insert into `table_3_2` ( `id`,  `caption`) values(1, 'caption 1');
insert into `table_3_2` ( `id`,  `caption`) values(2, 'caption 2');
insert into `table_3_2` ( `id`,  `caption`) values(3, 'caption 3');