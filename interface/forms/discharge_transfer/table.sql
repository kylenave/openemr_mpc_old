--
-- Table structure for table `form_discharge_transfer`
--

CREATE TABLE IF NOT EXISTS `form_discharge_transfer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `transfer_to` varchar(255) DEFAULT NULL,
  `reason_for_admission` text,
  `reason_for_discharge` text,
  `diagnosis` text,
  `progress` varchar(10) DEFAULT NULL,
  `comment_on_progress` text,
  `areas_of_concern` text,
  `family_participation` varchar(10) DEFAULT NULL,
  `family_areas_of_growth` text,
  `placement` varchar(255) DEFAULT NULL,
  `ongoing_services` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

