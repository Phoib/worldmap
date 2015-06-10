CREATE TABLE dogs (
  id int NOT NULL AUTO_INCREMENT,
  name CHAR(30) NOT NULL,
  eyes TINYINT(1) NOT NULL,
  birth DATE NOT NULL,
  lastSeen TIMESTAMP NOT NULL,
  weight double NOT NULL,
  quote TEXT,
  PRIMARY KEY (id)
);
