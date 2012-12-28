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

if(defined('_rex717_PATH')) return;

///////////////////////////////////////////////////////////////////////////
// Addon-Pfad definieren

define('_rex717_PATH', $REX['INCLUDE_PATH'] . '/addons/rexblog/plugins/rexblog_tags/');

///////////////////////////////////////////////////////////////////////////
// Addon-Version

$REX['ADDON']['version']['rexblog_tags'] = file_get_contents(_rex717_PATH . 'version');

///////////////////////////////////////////////////////////////////////////
// Addon-Konfiguration

$REX['ADDON']['rxid']['rexblog_tags'] = '717';
$REX['ADDON']['page']['rexblog_tags'] = 'rexblog_tags';
$REX['ADDON']['name']['rexblog_tags'] = 'WAM_Rexblog_Tags';
$REX['ADDON']['perm']['rexblog_tags'] = "rexblog_tags[]";

///////////////////////////////////////////////////////////////////////////
// Addon-Auhtor-Informationen

$REX['ADDON']['author']['rexblog_tags']      = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog_tags'] = 'http://bitbucket.org/mediastuttgart/rexblog/wiki/';

///////////////////////////////////////////////////////////////////////////
// Addon-Permissions

$REX['PERM'][] = "rexblog_tags[]";

///////////////////////////////////////////////////////////////////////////
// Autoload für Klassen

function _rex717_autoload($params)
{
  static $classes = array(
    '_rex717_BackendTags'  => 'classes/backend/class.backend.tags.php',
    '_rex717_FrontendTags' => 'classes/frontend/class.frontend.tags.php'
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
    if(_rex717_autoload(array('subject' => $classname)) !== true)
    {
      rex_register_extension_point('__AUTOLOAD', $classname);
    }
  }
}
  else
{
  rex_register_extension('__AUTOLOAD', '_rex717_autoload');
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
  // check for existing tags pathlist cache file and include

  if(file_exists($REX['INCLUDE_PATH'] . '/generated/files/_rex717_tags.pathlist.inc.php')) {
    include $REX['INCLUDE_PATH'] . '/generated/files/_rex717_tags.pathlist.inc.php';
      _rex717_FrontendTags::$tags_cache_pathlist = $REX['ADDON']['rexblog']['tags']['pathlist'];
  } else {
    _rex717_FrontendTags::$tags_cache_pathlist = array();
  }
  ///////////////////////////////////////////////////////////////////////////
  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';

  ///////////////////////////////////////////////////////////////////////////
  // Frontend Extension Point setzen

  rex_register_extension('REX488_FRONTEND_VENDOR', '_rex717_frontend_vendor');
}