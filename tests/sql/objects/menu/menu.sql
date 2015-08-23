DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game` int(11) NOT NULL,
  `key` char(64) NOT NULL,
  `name` char(128) NOT NULL,
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
INSERT INTO `menu` VALUES (1,0,'logout','Logout',100),(2,-3,'test','Testsuite',1),(3,-3,'install','Installer creator',2),(4,-2,'game','Game creator',1),(5,-2,'user','Users editor',2);
