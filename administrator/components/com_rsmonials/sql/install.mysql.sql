CREATE TABLE IF NOT EXISTS `#__rsmonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `about` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `ip` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rsmonials_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(100) NOT NULL,
  `param_description` text NOT NULL,
  `param_value` longtext NOT NULL,
  `ordering` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__rsmonials_param` (`id`, `param_name`, `param_description`, `param_value`, `ordering`) VALUES('', 'new_installation', '', 'true', 999);

CREATE TABLE IF NOT EXISTS `#__rsmonials_param_style` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(100) NOT NULL,
  `param_description` text NOT NULL,
  `param_value` longtext NOT NULL,
  `ordering` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;