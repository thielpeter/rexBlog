DROP TABLE IF EXISTS `%TABLE_PREFIX%488_articles`;

CREATE TABLE `%TABLE_PREFIX%488_articles` (
  `id` int(11) NOT NULL auto_increment,
  `categories` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `article_post` text NOT NULL,
  `article_tags` text NOT NULL,
  `article_trackbacks` text NOT NULL,
  `article_permlink` varchar(255) NOT NULL,
  `article_meta_settings` text NOT NULL,
  `article_plugin_settings` text NOT NULL,
  `clang` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_date` int(11) NOT NULL,
  `update_date` int(11) NOT NULL,
  `create_user` varchar(255) NOT NULL,
  `update_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `%TABLE_PREFIX%488_categories`;

CREATE TABLE `%TABLE_PREFIX%488_categories` (
  `id` int(10) NOT NULL auto_increment,
  `category_id` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `clang` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `priority` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_date` int(11) NOT NULL,
  `update_date` int(11) NOT NULL,
  `create_user` varchar(255) NOT NULL,
  `update_user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;