CREATE TABLE `lhc_lhcchatbot_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `context_id` int(11) NOT NULL,
  `was_used` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `context_id` (`context_id`),
  KEY `was_used` (`was_used`),
  KEY `confirmed` (`confirmed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lhc_lhcchatbot_context` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lhc_lhcchatbot_context_link_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `context_id` (`context_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;