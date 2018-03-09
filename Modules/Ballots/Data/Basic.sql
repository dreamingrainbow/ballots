CREATE TABLE `Ballots` (
  `id` serial AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` tinytext,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Votes` (
     id serial auto_increment NOT NULL,
     ballot_id bigint(20) unsigned NOT NULL,
     abstain ENUM('true', 'false') NOT NULL default 'true',
     yea ENUM('true', 'false') NOT NULL default 'false',
     nea ENUM('true', 'false') NOT NULL default 'false',
     created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
     KEY `ballot_id` (`ballot_id`),
  CONSTRAINT `ballots_x_votes_ibfk_1` FOREIGN KEY (`ballot_id`) REFERENCES `Ballots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
