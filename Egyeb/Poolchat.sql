CREATE TABLE IF NOT EXISTS `Felhasznalo` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`email` VARCHAR(255),
	`jelszo` VARCHAR(255),
	`neve` VARCHAR(255),
	`om_azonosito` INTEGER,
	`neme` VARCHAR(255),
	`eletkor` INTEGER,
	`iskola` VARCHAR(255),
	`mikor_keszult` DATE COMMENT 'fiok letrehozas idopont',
	PRIMARY KEY(`id`)
);


CREATE TABLE IF NOT EXISTS `Szerepkor` (
	`szerepkor_ID` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`megnevezes` VARCHAR(255),
	PRIMARY KEY(`szerepkor_ID`)
);


CREATE TABLE IF NOT EXISTS `Uzenetek` (
	`uzenet_id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`tartalom` TEXT(65535),
	`mikor_keszult` DATE,
	PRIMARY KEY(`uzenet_id`)
);


CREATE TABLE IF NOT EXISTS `Velemeny` (
	`velemeny_id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`tartalom` TEXT(65535),
	`mikor_keszult` DATE,
	PRIMARY KEY(`velemeny_id`)
);


CREATE TABLE IF NOT EXISTS `reakciok` (
	`reakcio_id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`emoji` VARCHAR(255),
	`mikor_keszult` DATE,
	PRIMARY KEY(`reakcio_id`)
);


CREATE TABLE IF NOT EXISTS `irasjelzes` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`uzenet_id` INTEGER,
	`mikor_keszult` DATE,
	PRIMARY KEY(`id`)
);


CREATE TABLE IF NOT EXISTS `mod naplo` (
	`action_id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`mikor_keszult` DATE,
	`target_user_id(FK)` INTEGER,
	`action_type` VARCHAR(255),
	`indok` TEXT(65535),
	`expired_at` DATE,
	`performed_by_user_id(FK)` VARCHAR(255),
	`room_id(FK)` INTEGER,
	PRIMARY KEY(`action_id`)
);


