<?php

/**
 * Copyright (c) 2010, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz 
 */

function _rex488_add_pageheader($params)
{
  ///////////////////////////////////////////////////////////////////////////
  // define subpage for filehandling
  
  $subpage          = rex_request('subpage', 'string');
  $subpage_filename = !in_array($subpage, array('articles', 'categories', 'comments')) ? 'categories' : $subpage;
  
  ///////////////////////////////////////////////////////////////////////////
  // create pageheader injection
  
  $content = "\n";
  $content .= '<link rel="stylesheet" type="text/css" href="include/addons/rexblog/files/css/' . $subpage_filename . '.css" />' . "\n";
  $content .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.tablednd.js"></script>' . "\n";
  $content .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.validate.pack.js"></script>' . "\n";
  $content .= '<script type="text/javascript" src="include/addons/rexblog/files/js/' . $subpage_filename . '.js"></script>' . "\n";

  ///////////////////////////////////////////////////////////////////////////
  // create pageheader injection

  $page_header = str_replace('</head>', $content . '</head>', $params['subject']);

  ///////////////////////////////////////////////////////////////////////////
  // return pageheader injection

  return $page_header;
  
}

function _rex488_read_plugin_directory()
{
  global $REX;

  $plugin_direcoty = dir(dirname(__FILE__) . '/../classes/plugins');

  while (false !== ($classfile = $plugin_direcoty->read()))
  {
    if($classfile == '.' || $classfile == '..' || $classfile == 'templates' || $classfile == 'files') continue;

    require_once dirname(__FILE__) . '/../classes/plugins/' . $classfile;

    $classname = str_replace('plugin.', '', $classfile);
    $classname = str_replace('.php', '', $classname);

    $plugin_id   = call_user_func(array('_rex488_content_plugin_'.$classname, 'read_id'));
    $plugin_name = call_user_func(array('_rex488_content_plugin_'.$classname, 'read_name'));

    $REX['ADDON']['rexblog']['plugins'][$plugin_id] = $plugin_name;
  }

  asort($REX['ADDON']['rexblog']['plugins']);

  $plugin_direcoty->close();
}

// global extension points

rex_register_extension('ALL_GENERATED', '_rex488_write_cache_all');

// category extension points

rex_register_extension('REX488_CATEGORY_ADDED', '_rex488_write_cache_category');
rex_register_extension('REX488_CATEGORY_PRIORITY', '_rex488_write_cache_category');
rex_register_extension('REX488_CATEGORY_UPDATED', '_rex488_write_cache_category');
rex_register_extension('REX488_CATEGORY_DELETED', '_rex488_write_cache_category');
rex_register_extension('REX488_CATEGORY_STATUS', '_rex488_write_cache_category');

// article extension points

rex_register_extension('REX488_ARTICLE_ADDED', '_rex488_write_cache_article');
rex_register_extension('REX488_ARTICLE_UPDATED', '_rex488_write_cache_article');
rex_register_extension('REX488_ARTICLE_DELETED', '_rex488_write_cache_article');
rex_register_extension('REX488_ARTICLE_STATUS', '_rex488_write_cache_article');

function _rex488_write_cache_all() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_category_cache();
  _rex488_BackendCache::write_article_cache();
  _rex488_BackendCache::write_article_pathlist();
}

function _rex488_write_cache_category() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_category_cache();
}

function _rex488_write_cache_article() {
  _rex488_BackendBase::get_instance();
  _rex488_BackendCache::write_article_cache();
  _rex488_BackendCache::write_article_pathlist();
}

function _rex488_read_content_plugins() {
  global $REX;
    return $REX['ADDON']['rexblog']['plugins'];
}

?>