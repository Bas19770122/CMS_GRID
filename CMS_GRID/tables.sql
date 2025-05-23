CREATE TABLE `table1` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ключ',
  `date` date DEFAULT NULL COMMENT 'Дата',
  `sum` decimal(12,2) DEFAULT NULL COMMENT 'Сумма',
  `selectid` int DEFAULT NULL COMMENT 'Ссылка на список',
  `listid` int DEFAULT NULL COMMENT 'Ссылка на справочник',
  `discr` varchar(45) CHARACTER SET cp1251 COLLATE cp1251_bin DEFAULT NULL,
  `chk` char(1) COLLATE cp1251_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin COMMENT='test table1'



CREATE TABLE `table2` (
  `id` int NOT NULL,
  `name` varchar(45) COLLATE cp1251_bin DEFAULT NULL,
  `caption` text COLLATE cp1251_bin,
  `fname` varchar(255) COLLATE cp1251_bin DEFAULT NULL,
  `parentid` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin COMMENT='Test Table2'



insert into `table1` (  `id`,  `date`,  `sum`,  `selectid`,  `listid`,  `discr`,  `chk`)
values (1, '2025-01-30', 11.1, 1, null, '1', 'N');
insert into `table1` (  `id`,  `date`,  `sum`,  `selectid`,  `listid`,  `discr`,  `chk`)
values (2, '2025-01-30', 11.1, 1, null, '1', 'N');



insert into `table2` ( `id`,  `name`,  `caption`,  `fname`,  `parentid`)
values(1, 'name 1', 'caption 1', null, null);
insert into `table2` ( `id`,  `name`,  `caption`,  `fname`,  `parentid`)
values(2, 'name 1', 'caption 1', null, 1);


