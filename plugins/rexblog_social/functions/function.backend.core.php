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

rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_TAB', '_rex721_add_tab_option');
rex_register_extension('REX488_BACKEND_ARTICLE_OPTIONS_CONTENT', '_rex721_add_tab_content');

// add extension points to write content

rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex721_write_tab_content');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex721_write_tab_content');

// cache extension points

rex_register_extension('ALL_GENERATED', '_rex721_write_social_links_pathlist');

// cache extension points

rex_register_extension('REX488_BACKEND_ARTICLE_ADD', '_rex721_write_social_links_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_UPDATE', '_rex721_write_social_links_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_DELETE', '_rex721_write_social_links_pathlist');
rex_register_extension('REX488_BACKEND_ARTICLE_STATUS', '_rex721_write_social_links_pathlist');

// delete shortened social link cache

rex_register_extension('REX488_BACKEND_ARTICLE_DELETE', '_rex721_delete_social_link_cache');

// function to add option tab

function _rex721_add_tab_option($params) {
  echo '<a href="#" class="rex488_extra_settings_tab" rel="social" onclick="Rexblog.Article.ToggleExtraSettings(this, event);">Social Links</a>&nbsp;|&nbsp;';
}

// function to add option content

function _rex721_add_tab_content($params) {
  include _rex721_PATH . 'templates/backend/template.article.options.content.phtml';
}

// function to write tab content

function _rex721_write_tab_content($params) {
  _rex721_BackendSocial::_rex721_write_tab_content($params);
}

// function to write cache files

function _rex721_write_social_links_pathlist($params) {
  _rex721_BackendSocial::_rex721_write_social_links_pathlist($params);
}

// function to delete social link cache

function _rex721_delete_social_link_cache($params) {
  _rex721_BackendSocial::_rex721_delete_social_link_cache($params);
}