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

rex_register_extension('ALL_GENERATED', '_rex670_write_archive_cache');

// category extension points

rex_register_extension('REX488_BACKEND_CATEGORY_ADD', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_CATEGORY_SORT', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_CATEGORY_UPDATE', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_CATEGORY_DELETE', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_CATEGORY_STATUS', '_rex670_write_archive_cache');

// article extension points

rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_ARTICLE_DELETE', '_rex670_write_archive_cache');
rex_register_extension('REX488_BACKEND_ARTICLE_STATUS', '_rex670_write_archive_cache');

// function to write archive cache file

function _rex670_write_archive_cache($params) {
  _rex670_BackendArchive::write_archive_pathlist();
}

?>