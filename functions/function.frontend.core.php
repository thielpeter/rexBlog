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
  ///////////////////////////////////////////////////////////////////////////
  // create core instance

  _rex488_FrontendBase::_rex488_frontend_core_instance();

  ///////////////////////////////////////////////////////////////////////////
  // enable trackback receiving

  _rex488_FrontendTrackback::_rex488_trackback_receive();

  ///////////////////////////////////////////////////////////////////////////
  // enable comment observing

  _rex488_FrontendCommentObserver::_rex488_observer_config();

  ///////////////////////////////////////////////////////////////////////////
  // enable trackback sending

  rex_register_extension('REX488_FRONTEND_ART_DETAILS', array(_rex488_FrontendTrackback, '_rex488_trackback_allocate'));

  ///////////////////////////////////////////////////////////////////////////
  // set base properties

  _rex488_FrontendBase::_rex488_set_base_properties();
}

/**
 * _rex488_frontend_vendor
 *
 * extenstion to inject into the frontend set base process
 *
 * @param array $params array of params from set base function
 * @return
 * @throws
 */

function _rex488_frontend_vendor($params)
{
  ///////////////////////////////////////////////////////////////////////////
  // if nothing is registered with the extension point, output the index

  if(rex_extension_is_registered('REX488_ALTERNATE_CONTENT') === false && $params['category_id'] == "0")
  {
    ///////////////////////////////////////////////////////////////////////////
    // register index extension output function

    rex_register_extension('REX488_ALTERNATE_CONTENT', array(_rex488_FrontendArticle, 'the_article_index'));
  }
}

/**
 * _rex488_the_category_id
 *
 * liefert die id der aktuellen kategorie zurück.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_category_id()
{
  return _rex488_FrontendBase::get_category_id();
}

/**
 * _rex488_the_article_id
 *
 * liefert die id der aktuellen kategorie zurück.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_id()
{
  return _rex488_FrontendBase::get_article_id();
}

/**
 * _rex488_the_categories
 *
 * erzeugt die navigation als formatiertes listenelement.
 *
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
 */

function _rex488_the_meta_title($prepend = false, $spacer = ' | ')
{
  if(_rex488_is_article())
  {
    return htmlspecialchars(_rex488_FrontendMetadataArticle::get_article_title($prepend, $spacer));
  }
  else if(_rex488_is_category())
  {
    return htmlspecialchars(_rex488_FrontendMetadataCategory::get_category_title($prepend, $spacer));
  }
  else
  {
    return htmlspecialchars(_rex488_FrontendMetadata::get_blog_title());
  }
}

/**
 * _rex488_the_meta_keywords
 *
 * erzeugt die meta-keywords basierend auf dem aktuellen template state.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_meta_keywords()
{
  if(_rex488_is_article())
  {
    return htmlspecialchars(_rex488_FrontendMetadataArticle::get_article_keywords());
  }
  else if(_rex488_is_category())
  {
    return htmlspecialchars(_rex488_FrontendMetadataCategory::get_category_keywords());
  }
  else
  {
    return htmlspecialchars(_rex488_FrontendMetadata::get_blog_keywords());
  }
}

/**
 * _rex488_meta_description
 *
 * erzeugt die meta-description basierend auf dem aktuellen template state.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_meta_description()
{
  if(_rex488_is_article())
  {
    return htmlspecialchars(_rex488_FrontendMetadataArticle::get_article_description());
  }
  else if(_rex488_is_category())
  {
    return htmlspecialchars(_rex488_FrontendMetadataCategory::get_category_description());
  }
  else
  {
    return htmlspecialchars(_rex488_FrontendMetadata::get_blog_description());
  }
}

/**
 * _rex488_the_content
 *
 * erzeugt die inhalte basierend auf dem aktuellen template state.
 * einstellungen und formatierungen an der ausgabe können direkt in
 * den einzelnen dateien im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_content($pagination = 4)
{
  // Frontend Extension Points setzten

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

function _rex488_the_pagination()
{
  return _rex488_FrontendPagination::get_pagination();
}

/**
 * _rex488_the_comment_validation
 * 
 * @param
 * @return <type> mixed
 * @throws
 */

function _rex488_the_comment_validation()
{
  return _rex488_FrontendComment::the_comment_validation();
}

/**
 * _rex488_the_article_comments
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_comments()
{
  return _rex488_FrontendComment::read(_rex488_the_article_id());
}

/**
 * _rex488_the_comment_id
 *
 * @param
 * @return <type> string
 * @throws
 */

function _rex488_the_comment_id()
{
  return _rex488_FrontendComment::the_comment_id();
}

/**
 * _rex488_the_comment_author
 *
 * @param
 * @return <type> string
 * @throws
 */

function _rex488_the_comment_author()
{
  return _rex488_FrontendComment::the_comment_author();
}

/**
 * _rex488_the_comment_email
 *
 * @param
 * @return <type> string
 * @throws
 */

function _rex488_the_comment_email()
{
  return _rex488_FrontendComment::the_comment_email();
}

/**
 * _rex488_the_comment_website
 *
 * @param
 * @return <type> string
 * @throws
 */

function _rex488_the_comment_website()
{
  return _rex488_FrontendComment::the_comment_website();
}

/**
 * _rex488_the_comment_comment
 *
 * @param
 * @return <type> string
 * @throws
 */

function _rex488_the_comment_comment()
{
  return _rex488_FrontendComment::the_comment_comment();
}

/**
 * _rex488_the_comment_form
 *
 * @param
 * @return <type> mixed
 * @throws
 */

function _rex488_the_comment_form()
{
  return _rex488_FrontendComment::the_comment_form();
}

/**
 * _rex488_the_comment_date
 *
 * @param
 * @return <type> mixed
 * @throws
 */

function _rex488_the_comment_date($format = 'd.m.Y')
{
  return _rex488_FrontendComment::the_comment_date($format);
}

/**
 * _rex488_the_article_settings
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * article.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_permlink()
{
  return _rex488_FrontendArticle::_rex488_the_article_permlink();
}

/**
 * _rex488_the_url
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_categories()
{
  return _rex488_FrontendArticle::_rex488_the_article_categories();
}

/**
 * _rex488_the_url
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_category_title()
{
  return _rex488_FrontendArticle::_rex488_the_article_category_title();
}

/**
 * _rex488_the_url
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_category_permlink()
{
  return _rex488_FrontendArticle::_rex488_the_article_category_permlink();
}

/**
 * _rex488_the_article_tags
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_tags()
{
  return _rex488_FrontendArticle::_rex488_the_article_tags();
}

/**
 * _rex488_the_article_tags_title
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_tags_title()
{
  return _rex488_FrontendArticle::_rex488_the_article_tags_title();
}

/**
 * _rex488_the_article_tags_permlink
 *
 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex488_the_article_tags_permlink()
{
  return _rex488_FrontendArticle::_rex488_the_article_tags_permlink();
}

/**
 * _rex488_is_category
 *
 * prüft anhand der übergebenen url den status und setzt
 * anhand des ergebnisses den neuen template state.
 *
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
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
 * @param
 * @return
 * @throws
 */

function _rex488_is_alternate()
{
  return _rex488_FrontendBase::$is_alternate;
}

?>