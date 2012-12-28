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

rex_register_extension('ALL_GENERATED', '_rex717_write_tags_cache');

// category extension points

rex_register_extension('REX488_CATEGORY_ADDED', '_rex717_write_tags_cache');
rex_register_extension('REX488_CATEGORY_PRIORITY', '_rex717_write_tags_cache');
rex_register_extension('REX488_CATEGORY_UPDATED', '_rex717_write_tags_cache');
rex_register_extension('REX488_CATEGORY_DELETED', '_rex717_write_tags_cache');
rex_register_extension('REX488_CATEGORY_STATUS', '_rex717_write_tags_cache');

// article extension points

rex_register_extension('REX488_ARTICLE_ADDED', '_rex717_write_tags_cache');
rex_register_extension('REX488_ARTICLE_UPDATED', '_rex717_write_tags_cache');
rex_register_extension('REX488_ARTICLE_DELETED', '_rex717_write_tags_cache');
rex_register_extension('REX488_ARTICLE_STATUS', '_rex717_write_tags_cache');

// function to write archive cache file

function _rex717_write_tags_cache($params) {
  _rex717_BackendTags::write_tags_pathlist();
}

?>