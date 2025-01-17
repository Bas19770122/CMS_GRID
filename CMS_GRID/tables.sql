CREATE TABLE `new_schema`.`table1` (
  `id` INT NOT NULL COMMENT 'ключ',
  `date` DATE NULL COMMENT 'Дата',
  `discr` VARCHAR(45) NULL COMMENT 'Текст',
  `sum` DECIMAL(12,2) NULL COMMENT 'Сумма',
  `selectid` INT NULL COMMENT 'Ссылка на список',
  `listid` INT NULL COMMENT 'Ссылка на справочник',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = cp1251
COLLATE = cp1251_bin
COMMENT = 'test table1';

insert into `new_schema`.`table1` (`id`, `date`, `discr`, `sum`, `selectid`, `listid`)
values(1, null, '', 0, 1, 1);

insert into `new_schema`.`table1` (`id`, `date`, `discr`, `sum`, `selectid`, `listid`)
values(2, null, '', 0.5, 2, 2);


CREATE TABLE `new_schema`.`table2` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `caption` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = cp1251
COLLATE = cp1251_bin
COMMENT = 'Test Table2';



insert into `new_schema`.`table2` (`id`, `name`, `caption`)
values(1, 'имя 1', 'описание 1');


insert into `new_schema`.`table2` (`id`, `name`, `caption`)
values(2, 'имя 2', 'описание 2');