<?php

/**
 * _rex717_append_tags
 *
 * @param
 * @return
 * @throws
 */

function _rex717_append_tags($params)
{
  return _rex717_FrontendTags::_rex717_append_tags($params);
}

/**
 * _rex717_url_vendor
 *
 * @param
 * @return
 * @throws
 */

function _rex717_frontend_vendor($params)
{
  if(preg_match('/tags\/(.*)\/(.*).html/', $params['url'], $archive_resource) && $params['category_id'] == "0")
  {
    ///////////////////////////////////////////////////////////////////////////
    // register archive extension output function

    rex_register_extension('REX488_FRONTEND_CONTENT_VENDOR', array(_rex717_FrontendTags, 'the_tags_overview'));

    ///////////////////////////////////////////////////////////////////////////
    // and set additional url params to out base class

    _rex488_FrontendBase::$resource_params = array('tags' => $archive_resource[1]);
  }
}


/**
 * _rex717_the_article_tags
 *
 * @param
 * @return
 * @throws
 *
 */

function _rex717_the_article_tags()
{
  return _rex717_FrontendTags::_rex717_the_article_tags();
}

/**
 * _rex717_the_tagcloud
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tagcloud()
{
  return _rex717_FrontendTags::_rex717_the_tagcloud();
}

/**
 * _rex717_the_tags_permlink
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tags_permlink()
{
  return _rex717_FrontendTags::_rex717_the_tags_permlink();
}

/**
 * _rex717_the_tags_title
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tags_title()
{
  return _rex717_FrontendTags::_rex717_the_tags_title();
}

/**
 * _rex717_the_tags_excerpt
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tags_excerpt()
{
  return _rex717_FrontendTags::_rex717_the_tags_excerpt();
}

/**
 * _rex717_the_tags_permlink
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tags_tag_permlink()
{
  return _rex717_FrontendTags::_rex717_the_tags_tag_permlink();
}

/**
 * _rex717_the_tags_title
 *
 * @param
 * @return
 * @throws
 */

function _rex717_the_tags_tag_title()
{
  return _rex717_FrontendTags::_rex717_the_tags_tag_title();
}


?>
