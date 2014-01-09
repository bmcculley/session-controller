# Database to keep track of sessions

CREATE TABLE IF NOT EXISTS `sessioncontrol` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) DEFAULT NULL,
  `ip_address` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entry_id`),
  KEY `visitor_id` (`visitor_id`,`timestamp`)
);