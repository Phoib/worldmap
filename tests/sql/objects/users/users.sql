CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(64) NOT NULL,
  `password` char(128) DEFAULT NULL,
  `salt` char(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `users` VALUES (1,'admin','bd6ef2f4d5f80b2a246953030e683db347e6a208207917962adb36d332ce2f081809e1a5202e07c8260c85d2e3dfa19ba80cdaaec92d5413c995bb83b030c544','861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1');
