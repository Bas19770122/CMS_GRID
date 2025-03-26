CREATE TABLE `table_4_1` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `table_4_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `table_4_1_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;


insert into `table_4_1` ( `id`,  `name`) values(1, 'name 1');
insert into `table_4_1` ( `id`,  `name`) values(2, 'name 2');
insert into `table_4_1` ( `id`,  `name`) values(3, 'name 3');

insert into `table_4_2` ( `id`,  `caption`, `table_4_1_id`) values(1, 'caption 1', 1);
insert into `table_4_2` ( `id`,  `caption`, `table_4_1_id`) values(2, 'caption 2', 3);
insert into `table_4_2` ( `id`,  `caption`, `table_4_1_id`) values(3, 'caption 3', 2);