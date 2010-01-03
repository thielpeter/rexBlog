<?php

/**
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
 */

if(defined('_rex488_PATH')) return;

// Addon-Pfad definieren

define('_rex488_PATH', $REX['INCLUDE_PATH'] . '/addons/rexblog/');

// Sprach-Konfiguration

if($REX['REDAXO'])
  $I18N->appendFile(dirname(__FILE__) . '/lang/');

// Addon-Version

$REX['ADDON']['version']['rexblog']     = file_get_contents(_rex488_PATH . 'version');

// Addon-Konfiguration

$REX['ADDON']['rxid']['rexblog']        = '488';
$REX['ADDON']['page']['rexblog']        = 'rexblog';
$REX['ADDON']['name']['rexblog']        = 'rexblog';
$REX['ADDON']['perm']['rexblog']        = "rexblog[]";

// Addon-Auhtor-Informationen

$REX['ADDON']['author']['rexblog']      = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog'] = 'http://bitbucket.org/mediastuttgart/rexblog/wiki/';

// Addon-Permissions

$REX['PERM'][] = "rexblog[]";

// Autoload für Klassen

function _rex488_autoload($params)
{
  static $classes = array(
    '_rex488_BackendBase'               => 'backend/class.backend.base.php',
    '_rex488_BackendCache'              => 'backend/class.backend.cache.php',
    '_rex488_BackendException'          => 'backend/class.backend.exception.php',
    '_rex488_BackendErrorHandling'      => 'backend/class.backend.errorhandling.php',
    '_rex488_BackendCategoryInterface'  => 'backend/interface/interface.backend.categories.php',
    '_rex488_BackendPost'               => 'backend/class.backend.post.php',
    '_rex488_BackendCategories'         => 'backend/class.backend.categories.php',
    '_rex488_FrontendBase'              => 'frontend/class.frontend.base.php',
    '_rex488_FrontendDesignator'        => 'frontend/class.frontend.designator.php',
    '_rex488_FrontendCategories'        => 'frontend/class.frontend.categories.php',
    '_rex488_FrontendCategory'          => 'frontend/class.frontend.category.php',
    '_rex488_FrontendMetadata'          => 'frontend/class.frontend.metadata.php',
    '_rex488_FrontendMetadataCategory'  => 'frontend/class.frontend.metadata.category.php',
    '_rex488_FrontendMetadataPost'      => 'frontend/class.frontend.metadata.post.php',
    '_rex488_FrontendPost'              => 'frontend/class.frontend.post.php',
    '_rex488_FrontendPagination'        => 'frontend/class.frontend.pagination.php',
  );

  $classname = $params['subject'];

  if(isset($classes[$classname]))
  {
    // Angeforderte Klassen einbinden

    require_once dirname(__FILE__) . '/classes/' . $classes[$classname];

    // Liefert true zurück nachdem die Klasse erfolgreich eingebunden wurde

    return true;
  }

  return false;
}

// Prüft ob bereits eine autoload Funktion definiert wurde

if(!function_exists('__autoload'))
{
  function __autoload($classname)
  {
    if(_rex488_autoload(array('subject' => $classname)) === false)
    {
      rex_register_extension_point('__AUTOLOAD', $classname);
    }
  }
}
  else
{
  rex_register_extension('__AUTOLOAD', '_rex488_autoload');
}

// Backend und Frontend Eigenschaften, Funktionen und Assets

if($REX['REDAXO'])
{
  // Subpages definieren

  $REX['ADDON']['rexblog']['SUBPAGES'] = array(
          array('categories', 'Kategorien', '', array('clang' => $REX['CUR_CLANG']))
  );

  // Stylesheets und Javascript einbinden

  require dirname(__FILE__) . '/functions/function.backend.core.php';

  // Backend Funktionen inkludieren

  rex_register_extension('PAGE_HEADER', '_rex488_add_pageheader');
}
  else
{
  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';
}