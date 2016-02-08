DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  `key` char(32) DEFAULT NULL,
  `permission` int(11) DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
LOCK TABLES `game` WRITE;
INSERT INTO `game` VALUES (-3,'Development','devel',1),(-2,'Admin','admin',2),(-1,'Selection','',3),(1,'Test Worldmap','test',3);
UNLOCK TABLES;
