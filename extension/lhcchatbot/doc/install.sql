CREATE TABLE `lhc_lhcchatbot_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `context_id` int(11) NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `was_used` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `context_id` (`context_id`),
  KEY `was_used` (`was_used`),
  KEY `hash` (`hash`),
  KEY `confirmed` (`confirmed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lhc_lhcchatbot_context` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `host` varchar(150) NOT NULL,
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


CREATE TABLE `lhc_lhcchatbot_invalid` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`question` text NOT NULL,
`answer` text NOT NULL,
`counter` int(11) NOT NULL,
`hash` varchar(40) NOT NULL,
`chat_id` bigint(20) NOT NULL,
`context_id` bigint(20) NOT NULL,
PRIMARY KEY (`id`), KEY `hash` (`hash`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `lhc_lhcchatbot_use` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `context_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `context_id` (`context_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

CREATE TABLE `lhc_lhcchatbot_index` (  `chat_id` bigint(20) unsigned NOT NULL, UNIQUE KEY `chat_id` (`chat_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `lhc_lhcchatbot_used` (  `chat_id` bigint(20) unsigned NOT NULL, UNIQUE KEY `chat_id` (`chat_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
