<?php

/*
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
*/

function _rex488_add_pageheader()
{
  $page_header = "\n";
  $page_header .= '<link rel="stylesheet" type="text/css" href="include/addons/rexblog/files/css/backend.css" />' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.tablednd.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.metadata.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.validate.pack.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.ui.core.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.ui.sortable.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/external/tiny_mce/tiny_mce.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/backend.js"></script>' . "\n";

  return $page_header;
}

// global extension points

rex_register_extension('ALL_GENERATED', '_rex488_write_cache_all');

// category extension points

rex_register_extension('REX488_CAT_ADDED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_PRIORITY', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_UPDATED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_DELETED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_STATUS', '_rex488_write_category_cache');

// article extension points

rex_register_extension('REX488_ART_ADDED', '_rex488_write_article_cache');
rex_register_extension('REX488_ART_UPDATED', '_rex488_write_article_cache');
rex_register_extension('REX488_ART_DELETED', '_rex488_write_article_cache');
rex_register_extension('REX488_ART_STATUS', '_rex488_write_article_cache');

function _rex488_write_category_cache() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_category_cache();
}

function _rex488_write_article_cache() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_article_cache();
  _rex488_BackendCache::write_article_pathlist();
}

function _rex488_write_cache_all() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_category_cache();
  _rex488_BackendCache::write_article_cache();
  _rex488_BackendCache::write_article_pathlist();
}

function _rex488_read_content_plugins() {
  global $REX;
  return $REX['ADDON']['rexblog']['plugins'];
}

/**
 * _rex488_push_user_page
 *
 * pushed eine subpage eines addons anhand der
 * übergebenen variable den reiter in die
 * hauptstruktur der addon navigation
 *
 * @param string $custom_permission custom permission to push
 * @param string $custom_tile custom title
 */

function _rex488_push_user_page($custom_permission, $custom_title)
{
  global $REX;

  preg_match('/(.*)\[(.*)\]/', $custom_permission, $custom_split);

  $pages = array();

  foreach($REX['USER']->pages as $k => $v)
  {
    $pages[$k] = $v;
  }

  unset($pages[$custom_split[1]]);

  $pages[$custom_split[1] . '&subpage=' . $custom_split[2]] = array(
    0 => $custom_title,
    1 => 1,
    2 => 1,
    3 => ''
  );

  $REX['USER']->pages = $pages;
}

?>