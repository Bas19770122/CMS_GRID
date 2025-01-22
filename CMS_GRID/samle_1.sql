CREATE TABLE `table_1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET cp1251 COLLATE cp1251_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin

INSERT INTO `table_1` (`id`,`name`) VALUES (1,'name 1');
INSERT INTO `table_1` (`id`,`name`) VALUES (2,'name 2');
INSERT INTO `table_1` (`id`,`name`) VALUES (3,'name 3');
