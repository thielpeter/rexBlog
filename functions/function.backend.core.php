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
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/backend.js"></script>' . "\n";

  return $page_header;
}

rex_register_extension('REX488_CAT_ADDED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_UPDATED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_DELETED', '_rex488_write_category_cache');
rex_register_extension('REX488_CAT_STATUS', '_rex488_write_category_cache');

function _rex488_write_category_cache() {
  _rex488_BackendCache::write_category_cache();
}

?>