<?php

/**
 * _rex670_url_vendor
 *
 * @param
 * @return
 * @throws
 */

function _rex670_frontend_vendor($params)
{
  if(preg_match('/archive\/([0-9]{1,4})\-([0-9]{1,2})\/archive.html/', $params['url'], $archive_resource) && $params['category_id'] == "0")
  {
    ///////////////////////////////////////////////////////////////////////////
    // register archive extension output function

    rex_register_extension('REX488_FRONTEND_CONTENT_VENDOR', array(_rex670_FrontendArchive, 'the_archive_overview'));

    ///////////////////////////////////////////////////////////////////////////
    // and set additional url params to out base class

    _rex488_FrontendBase::$resource_params = array('archive' => $archive_resource[1] . $archive_resource[2]);
  }
}

/**
 * _rex670_the_archive
 *
 * @param
 * @return
 * @throws
 */

function _rex670_the_archive()
{
  return _rex670_FrontendArchive::_rex670_the_archive();
}

/**
 * _rex670_the_archive_link
 *
 * @param
 * @return
 * @throws
 */

function _rex670_the_archive_permlink()
{
  return _rex670_FrontendArchive::_rex670_the_archive_permlink();
}

/**
 * _rex670_the_archive_date
 *
 * @param
 * @return
 * @throws
 */

function _rex670_the_archive_title()
{
  return _rex670_FrontendArchive::_rex670_the_archive_title();
}

/**
 * _rex670_the_archive_excerpt
 *
 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
 * und formatierungen an der ausgabe können direkt in der separaten
 * post.inc.php datei im template verzeichnis gemacht werden.
 *
 * @param
 * @return
 * @throws
 */

function _rex670_the_archive_excerpt()
{
  return _rex670_FrontendArchive::_rex670_the_archive_excerpt();
}

?>
