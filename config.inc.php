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
    '_rex488_BackendBase'               => 'classes/backend/class.backend.base.php',
    '_rex488_BackendCache'              => 'classes/backend/class.backend.cache.php',
    '_rex488_BackendException'          => 'classes/backend/class.backend.exception.php',
    '_rex488_BackendErrorHandling'      => 'classes/backend/class.backend.errorhandling.php',
    '_rex488_BackendCategoryInterface'  => 'classes/backend/interface/interface.backend.categories.php',
    '_rex488_BackendArticleInterface'   => 'classes/backend/interface/interface.backend.articles.php',
    '_rex488_BackendCategories'         => 'classes/backend/class.backend.categories.php',
    '_rex488_BackendArticles'           => 'classes/backend/class.backend.articles.php',
    
    '_rex488_FrontendBase'              => 'classes/frontend/class.frontend.base.php',
    '_rex488_FrontendDesignator'        => 'classes/frontend/class.frontend.designator.php',
    '_rex488_FrontendCategories'        => 'classes/frontend/class.frontend.categories.php',
    '_rex488_FrontendCategory'          => 'classes/frontend/class.frontend.category.php',
    '_rex488_FrontendMetadata'          => 'classes/frontend/class.frontend.metadata.php',
    '_rex488_FrontendMetadataCategory'  => 'classes/frontend/class.frontend.metadata.category.php',
    '_rex488_FrontendMetadataArticle'   => 'classes/frontend/class.frontend.metadata.article.php',
    '_rex488_FrontendArticle'           => 'classes/frontend/class.frontend.article.php',
    '_rex488_FrontendArchive'           => 'classes/frontend/class.frontend.archive.php',
    '_rex488_FrontendPagination'        => 'classes/frontend/class.frontend.pagination.php',
  );

  $classname = $params['subject'];

  if(isset($classes[$classname]))
  {
    // Angeforderte Klassen einbinden

    require_once dirname(__FILE__) . '/' . $classes[$classname];

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
    array('categories', 'Kategorien', '', array('clang' => $REX['CUR_CLANG'])),
    array('articles', 'Artikel', '', array('clang' => $REX['CUR_CLANG']))
  );

  // content plugin directory auslesen

  $plugin_direcoty = dir(dirname(__FILE__) . '/classes/plugins');

  while (false !== ($classfile = $plugin_direcoty->read()))
  {
    if($classfile == '.' || $classfile == '..' || $classfile == 'templates' || $classfile == 'files') continue;
    require_once dirname(__FILE__) . '/classes/plugins/' . $classfile;

    $classname = str_replace('plugin.', '', $classfile);
    $classname = str_replace('.php', '', $classname);

    $plugin_id = "return _rex488_content_plugin_" . $classname . '::read_id();';
    $plugin_name = "return _rex488_content_plugin_" . $classname . '::read_name();';
    
    $REX['ADDON']['rexblog']['plugins'][eval($plugin_id)] = eval($plugin_name);
    
  }

  $plugin_direcoty->close();

  // Stylesheets und Javascript einbinden

  require dirname(__FILE__) . '/functions/function.backend.core.php';
  
  // Backend Funktionen inkludieren

  if(rex_request('page', 'string') == 'rexblog')
    rex_register_extension('PAGE_HEADER', '_rex488_add_pageheader');

}
  else
{
  // Frontend Extension Points setzten
    
  rex_register_extension('REX488_SET_BASE', '_rex488_frontend_setbase');

  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';
}