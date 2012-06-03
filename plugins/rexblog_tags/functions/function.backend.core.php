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

// add tab to article options

/**
 * falls nur bestimmte user tags ändern dürfen
 * müssen bei den nachfolgenden zeilen die klammern
 * entfernt werden und die anschießenden extension
 * points ohne perm ausgeklammert werden.
 */

/*
if($REX['USER']->hasPerm('rexblog_tags')) {
  rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_TAB', '_rex717_add_tab_option');
  rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_CONTENT', '_rex717_add_tab_content');
} else {
  rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_CONTENT', '_rex717_add_hidden_tab_content');
}
*/

rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_TAB', '_rex717_add_tab_option');
rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_CONTENT', '_rex717_add_tab_content');

// add extension to write additonal values to database

rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex717_write_tab_content');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex717_write_tab_content');

// cache extension points

rex_register_extension('ALL_GENERATED', '_rex717_write_tags_strength');
rex_register_extension('ALL_GENERATED', '_rex717_write_tags_pathlist');

// cache extension points

rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex717_write_tags_strength');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex717_write_tags_strength');
rex_register_extension('REX488_BACKEND_ARTICLE_DELETE', '_rex717_write_tags_strength');
rex_register_extension('REX488_BACKEND_ARTICLE_STATUS', '_rex717_write_tags_strength');
rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex717_write_tags_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex717_write_tags_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_DELETE', '_rex717_write_tags_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_STATUS', '_rex717_write_tags_pathlist');

// function to write cache file

function _rex717_write_tags_strength($params) {
  _rex717_BackendTags::_rex717_write_tags_strength();
}

// function to write cache file

function _rex717_write_tags_pathlist($params) {
  _rex717_BackendTags::_rex717_write_tags_pathlist();
}

// function to add option tab

function _rex717_add_tab_option($params) {
  echo '<a href="#" class="rex488_extra_settings_tab" rel="tags" onclick="Rexblog.Article.ToggleExtraSettings(this, event);">Schlagwörter</a>&nbsp;|&nbsp;';
}

// function to add option content

function _rex717_add_tab_content($params) {
  include _rex717_PATH . 'templates/backend/template.article.options.content.phtml';
}

// function to add option content

function _rex717_add_hidden_tab_content($params) {
  echo '<p><input type="hidden" name="rex717_meta_tags" value="' . htmlspecialchars(_rex717_read_tab_content()) . '" /></p>' . "\n";
}

// function to write tab content

function _rex717_write_tab_content($params) {
  _rex717_BackendTags::_rex717_write_tab_content($params);
}

// function to read tab content

function _rex717_read_tab_content() {
  return _rex717_BackendTags::_rex717_read_tab_content();
}

?>