CREATE TABLE `table_6_1` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ключ',
  `chk` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Признак',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Строка',
  `discr` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Текст',
  `date` date DEFAULT NULL COMMENT 'Дата',
  `sum` decimal(12,2) DEFAULT NULL COMMENT 'Сумма',
  `selectid` int DEFAULT NULL COMMENT 'Ссылка на список',
  `listid` int DEFAULT NULL COMMENT 'Ссылка на справочник',  
  `treeid` int DEFAULT NULL COMMENT 'Ссылка на справочник дерева',  
  `fname` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Признак',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;


insert into `table_6_1` ( `id`, `chk`, `name`, `discr`, `date`, `sum`, `selectid`, `listid`, `treeid`, `fname`) values(1, 'Y', 'name 1', 'discr 1', '2025-01-30', '10.23', '1', 1, 1, null);
insert into `table_6_1` ( `id`, `chk`, `name`, `discr`, `date`, `sum`, `selectid`, `listid`, `treeid`, `fname`) values(2, 'Y', 'name 1', 'discr 1', '2025-01-30', '10.23', '1', 1, 1, null);
insert into `table_6_1` ( `id`, `chk`, `name`, `discr`, `date`, `sum`, `selectid`, `listid`, `treeid`, `fname`) values(3, 'Y', 'name 1', 'discr 1', '2025-01-30', '10.23', '1', 1, 1, null);
insert into `table_6_1` ( `id`, `chk`, `name`, `discr`, `date`, `sum`, `selectid`, `listid`, `treeid`, `fname`) values(4, 'Y', 'name 1', 'discr 1', '2025-01-30', '10.23', '1', 1, 1, null);



CREATE TABLE `table_6_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

insert into `table_6_2` ( `id`,  `caption`) values(1, 'caption 1');
insert into `table_6_2` ( `id`,  `caption`) values(2, 'caption 2');
insert into `table_6_2` ( `id`,  `caption`) values(3, 'caption 3');


CREATE TABLE `table_6_3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `table_6_3_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

insert into `table_6_3` ( `id`,  `caption`, `table_6_3_id`) values(1, 'caption 1', null);
insert into `table_6_3` ( `id`,  `caption`, `table_6_3_id`) values(2, 'caption 2', 1);
insert into `table_6_3` ( `id`,  `caption`, `table_6_3_id`) values(3, 'caption 3', 2);


