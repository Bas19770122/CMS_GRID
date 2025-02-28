CREATE TABLE `table_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parentid` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `caption` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3

INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (1,null,'name 1','text 1');
INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (2,null,'name 2','text 2');
INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (3,1,'name 3','text 3');
INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (4,2,'name 4','text 4');
INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (5,3,'name 5','text 5');
INSERT INTO `table_2` (`id`,`parentid`,`name`,`caption`) VALUES (6,5,'name 6','text 6');
