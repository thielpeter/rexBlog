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

if(defined('_rex670_PATH')) return;

///////////////////////////////////////////////////////////////////////////
// Addon-Pfad definieren

define('_rex670_PATH', $REX['INCLUDE_PATH'] . '/addons/rexblog/plugins/rexblog_archive/');

///////////////////////////////////////////////////////////////////////////
// Addon-Version

$REX['ADDON']['version']['rexblog_archive'] = file_get_contents(_rex670_PATH . 'version');

///////////////////////////////////////////////////////////////////////////
// Addon-Konfiguration

$REX['ADDON']['rxid']['rexblog_archive'] = '670';
$REX['ADDON']['page']['rexblog_archive'] = 'rexblog_archive';
$REX['ADDON']['name']['rexblog_archive'] = 'WAM_Rexblog_Archive';
$REX['ADDON']['perm']['rexblog_archive'] = "rexblog_archive[]";

///////////////////////////////////////////////////////////////////////////
// Addon-Auhtor-Informationen

$REX['ADDON']['author']['rexblog_archive']      = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog_archive'] = 'http://bitbucket.org/mediastuttgart/rexblog/wiki/';

///////////////////////////////////////////////////////////////////////////
// Addon-Permissions

$REX['PERM'][] = "rexblog_archive[]";

///////////////////////////////////////////////////////////////////////////
// Autoload für Klassen

function _rex670_autoload($params)
{
  static $classes = array(
    '_rex670_BackendArchive'  => 'classes/backend/class.backend.archive.php',
    '_rex670_FrontendArchive' => 'classes/frontend/class.frontend.archive.php'
  );

  $classname = $params['subject'];

  if(isset($classes[$classname]))
  {
    ///////////////////////////////////////////////////////////////////////////
    // Angeforderte Klassen einbinden

    require_once dirname(__FILE__) . '/' . $classes[$classname];

    ///////////////////////////////////////////////////////////////////////////
    // Liefert true zurück nachdem die Klasse erfolgreich eingebunden wurde

    return true;
  }
}

///////////////////////////////////////////////////////////////////////////
// Prüft ob bereits eine autoload Funktion definiert wurde

if(!function_exists('__autoload'))
{
  function __autoload($classname)
  {
    if(_rex670_autoload(array('subject' => $classname)) !== true)
    {
      rex_register_extension_point('__AUTOLOAD', $classname);
    }
  }
}
  else
{
  rex_register_extension('__AUTOLOAD', '_rex670_autoload');
}

///////////////////////////////////////////////////////////////////////////
// Backend und Frontend Eigenschaften, Funktionen und Assets

if($REX['REDAXO'])
{
  ///////////////////////////////////////////////////////////////////////////
  // Backend Funktionen einbinden

  require dirname(__FILE__) . '/functions/function.backend.core.php';
}
  else
{
  ///////////////////////////////////////////////////////////////////////////
  // check for existing archive pathlist cache file and include

  if(file_exists($REX['INCLUDE_PATH'] . '/generated/files/_rex488_archive.pathlist.inc.php')) {
    include $REX['INCLUDE_PATH'] . '/generated/files/_rex488_archive.pathlist.inc.php';
      _rex670_FrontendArchive::$archive_cache_pathlist = $REX['ADDON']['rexblog']['archive']['pathlist'];
  } else {
    _rex670_FrontendArchive::$archive_cache_pathlist = array();
  }

  ///////////////////////////////////////////////////////////////////////////
  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';

  ///////////////////////////////////////////////////////////////////////////
  // Frontend Extension Point setzen

  rex_register_extension('REX488_FRONTEND_VENDOR', '_rex670_frontend_vendor');
}