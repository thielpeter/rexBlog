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

if(defined('_rex721_PATH')) return;

// Addon-Pfad definieren

define('_rex721_PATH', $REX['INCLUDE_PATH'] . '/addons/rexblog/plugins/rexblog_social/');

// Addon-Version

$REX['ADDON']['version']['rexblog_social'] = file_get_contents(_rex721_PATH . 'version');

// Addon-Konfiguration

$REX['ADDON']['rxid']['rexblog_social'] = '721';
$REX['ADDON']['page']['rexblog_social'] = 'rexblog_social';
$REX['ADDON']['name']['rexblog_social'] = 'Rexblog Social Links';
$REX['ADDON']['perm']['rexblog_social'] = "rexblog_social[]";

// URL-Shortener Konfiguration

$REX['ADDON']['rexblog_social']['shortener']['enabled']  = false;
$REX['ADDON']['rexblog_social']['shortener']['vendor']   = "http://api.bit.ly/v3/shorten";
$REX['ADDON']['rexblog_social']['shortener']['request']  = "login=%USERNAME%&apiKey=%APIKEY%&uri=%URL%&format=txt";
$REX['ADDON']['rexblog_social']['shortener']['username'] = "";
$REX['ADDON']['rexblog_social']['shortener']['apikey']   = "";

// Addon-Auhtor-Informationen

$REX['ADDON']['author']['rexblog_social']      = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog_social'] = 'http://bitbucket.org/mediastuttgart/rexblog/wiki/';

// Addon-Permissions

$REX['PERM'][] = "rexblog_social[]";

// Autoload für Klassen

function _rex721_autoload($params)
{
  static $classes = array(
    '_rex721_BackendSocial'  => 'classes/backend/class.backend.social.php',
    '_rex721_FrontendSocial' => 'classes/frontend/class.frontend.social.php'
  );

  $classname = $params['subject'];

  if(isset($classes[$classname]))
  {
    // Angeforderte Klassen einbinden

    require_once dirname(__FILE__) . '/' . $classes[$classname];

    // Liefert true zurück nachdem die Klasse erfolgreich eingebunden wurde

    return true;
  }
}

// Prüft ob bereits eine autoload Funktion definiert wurde

if(!function_exists('__autoload'))
{
  function __autoload($classname)
  {
    if(_rex721_autoload(array('subject' => $classname)) !== true)
    {
      rex_register_extension_point('__AUTOLOAD', $classname);
    }
  }
}
  else
{
  rex_register_extension('__AUTOLOAD', '_rex721_autoload');
}

// Backend und Frontend Eigenschaften, Funktionen und Assets

if($REX['REDAXO'])
{
  // Backend Funktionen einbinden

  require dirname(__FILE__) . '/functions/function.backend.core.php';
}
  else
{
  // include the cache file

  if(file_exists($REX['INCLUDE_PATH'] . '/generated/files/_rex721_social.pathlist.inc.php')) {
    include $REX['INCLUDE_PATH'] . '/generated/files/_rex721_social.pathlist.inc.php';
      _rex721_FrontendSocial::$social_links = $REX['ADDON']['rexblog']['social_links']['pathlist'];
  } else {
    _rex721_FrontendSocial::$social_links = array();
  }

  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';
}