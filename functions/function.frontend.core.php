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

function _rex488_base_loader()
{
  $base_loader = _rex488_FrontendBase::getInstance();
  $base_loader->set_base();
}

/**
 * _rex488_frontend_base
 *
 * extenstion to inject into the frontend set base process
 *
 * @param array $params array of params from set base function
 * @return
 * @throws
 */

function _rex488_frontend_setbase($params)
{
  if(preg_match('/archive\/([0-9]{1,4})\-([0-9]{1,2})\/archive.html/', $params['url'], $archive_resource))
  {
    rex_register_extension('REX488_ALTERNATE_CONTENT', array(_rex488_FrontendArchive, 'the_archive_overview'));
      _rex488_FrontendBase::$resource_params  = array('archive' => $archive_resource[1] . $archive_resource[2]);
  }
}

/**
 * _rex488_the_category_id
 *
 * liefert die id der aktuellen kategorie zurück.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_category_id()
{
  return _rex488_FrontendBase::get_category_id();
}

/**
 * _rex488_the_categories
 *
 * erzeugt die navigation als formatiertes listenelement.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_categories($opener = false, $show_post_count = false)
{
  return _rex488_FrontendCategories::get_categories($opener, $show_post_count);
}

/**
 * _rex488_the_meta_title
 *
 * erzeugt den meta-title basierend auf dem aktuellen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_meta_title($prepend = false, $spacer = ' | ')
{
  if(_rex488_is_article())
  {
    return _rex488_FrontendMetadataArticle::get_article_title($prepend, $spacer);
  }
  else if(_rex488_is_category())
  {
    return _rex488_FrontendMetadataCategory::get_category_title($prepend, $spacer);
  }
  else
  {
    return _rex488_FrontendMetadata::get_blog_title();
  }
}

/**
 * _rex488_the_meta_keywords
 *
 * erzeugt die meta-keywords basierend auf dem aktuellen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_meta_keywords()
{
  if(_rex488_is_article())
  {
    return _rex488_FrontendMetadataArticle::get_article_keywords();
  }
  else if(_rex488_is_category())
  {
    return _rex488_FrontendMetadataCategory::get_category_keywords();
  }
  else
  {
    return _rex488_FrontendMetadata::get_blog_keywords();
  }
}

/**
 * _rex488_meta_description
 *
 * erzeugt die meta-description basierend auf dem aktuellen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_meta_description()
{
  if(_rex488_is_article())
  {
    return _rex488_FrontendMetadataArticle::get_article_description();
  }
  else if(_rex488_is_category())
  {
    return _rex488_FrontendMetadataCategory::get_category_description();
  }
  else
  {
    return _rex488_FrontendMetadata::get_blog_description();
  }
}

/**
 * _rex488_the_content
 *
 * erzeugt die inhalte basierend auf dem aktuellen template state.
 * einstellungen und formatierungen an der ausgabe können direkt in
 * den einzelnen dateien im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_content($pagination = 4)
{
  if(_rex488_is_article())
  {
    return _rex488_FrontendArticle::the_detail_content();
  }
  else if(_rex488_is_category())
  {
    return _rex488_FrontendArticle::the_overview_content($pagination);
  }
  else if(_rex488_is_alternate())
  {
    return rex_register_extension_point('REX488_ALTERNATE_CONTENT', '', array('pagination' => $pagination), true);
  }
}

/**
 * _rex488_the_archive
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_archive()
{
  return _rex488_FrontendArchive::_rex488_the_archive();
}

/**
 * _rex488_the_archive_link
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_archive_permlink()
{
  return _rex488_FrontendArchive::_rex488_the_archive_permlink();
}

/**
 * _rex488_the_archive_date
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_archive_title()
{
  return _rex488_FrontendArchive::_rex488_the_archive_title();
}

/**
 * _rex488_the_archive_excerpt
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_archive_excerpt()
{
  return _rex488_FrontendArchive::_rex488_the_archive_excerpt();
}

/**
 * _rex488_the_pagination
 *
 * erzeugt die inhalte basierend auf dem aktuellen template state.
 * einstellungen und formatierungen an der ausgabe können direkt in
 * den einzelnen dateien im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_pagination()
{
  return _rex488_FrontendPagination::get_pagination();
}

/**
 * _rex488_the_article_settings
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * article.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_settings()
{
  return _rex488_FrontendArticle::_rex488_the_article_settings();
}

/**
 * _rex488_the_article
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * article.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_post()
{
  return _rex488_FrontendArticle::_rex488_the_article_post();
}

/**
 * _rex488_the_excerpt
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_excerpt()
{
  return _rex488_FrontendArticle::_rex488_the_article_excerpt();
}

/**
 * _rex488_the_post_date
 *
 * erzeugt das datum des beitragstextes. einstellungen und
 * formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_date($date_format = 'd.m.Y')
{
  return _rex488_FrontendArticle::_rex488_the_article_date($date_format);
}

/**
 * _rex488_the_post_user
 *
 * erzeugt den ersteller des beitragstextes. einstellungen und
 * formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_user()
{
  return _rex488_FrontendArticle::_rex488_the_article_user();
}

/**
 * _rex488_the_title
 *
 * erzeugt den beitragstitel basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_title()
{
  return _rex488_FrontendArticle::_rex488_the_article_title();
}

/**
 * _rex488_the_url
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_the_article_permlink()
{
  return _rex488_FrontendArticle::_rex488_the_article_permlink();
}

/**
 * _rex488_is_category
 *
 * prüft anhand der übergebenen url den status und setzt
 * anhand des ergebnisses den neuen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_is_category()
{
  return _rex488_FrontendBase::$is_category;
}

/**
 * _rex488_is_post
 *
 * prüft anhand der übergebenen url den status und setzt
 * anhand des ergebnisses den neuen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_is_article()
{
  return _rex488_FrontendBase::$is_article;
}

/**
 * _rex488_is_alternate
 *
 * prüft anhand der übergebenen url den status und setzt
 * anhand des ergebnisses den neuen template state.
 *
 * @throws
 * @global
 * @param
 * @return
 */

function _rex488_is_alternate()
{
  return _rex488_FrontendBase::$is_alternate;
}

?>