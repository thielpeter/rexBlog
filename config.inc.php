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
$REX['ADDON']['name']['rexblog']        = 'Rexblog';
$REX['ADDON']['perm']['rexblog']        = "rexblog[]";

// Addon-Auhtor-Informationen

$REX['ADDON']['author']['rexblog']      = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog'] = 'http://bitbucket.org/mediastuttgart/rexblog/wiki/';

// Barely legal run Configuration

$REX['ADDON']['frontend']['rexblog']['base']     = true;
$REX['ADDON']['comment']['rexblog']['status']    = 0;
$REX['ADDON']['comment']['rexblog']['pagelimit'] = 10;

// Addon-Permissions

$REX['PERM'][] = "rexblog[]";

// Autoload für Klassen

function _rex488_autoload($params)
{
  static $classes = array(
    '_rex488_BackendCategoryInterface'  => 'classes/backend/interface/interface.backend.categories.php',
    '_rex488_BackendArticleInterface'   => 'classes/backend/interface/interface.backend.articles.php',

    '_rex488_BackendBase'               => 'classes/backend/class.backend.base.php',
    '_rex488_BackendCache'              => 'classes/backend/class.backend.cache.php',
    '_rex488_BackendErrorHandling'      => 'classes/backend/class.backend.errorhandling.php',
    '_rex488_BackendCategories'         => 'classes/backend/class.backend.categories.php',
    '_rex488_BackendArticles'           => 'classes/backend/class.backend.articles.php',
    '_rex488_BackendTrackback'          => 'classes/backend/class.backend.trackback.php',
    '_rex488_BackendComments'           => 'classes/backend/class.backend.comments.php',
    '_rex488_BackendCommentsObserver'   => 'classes/backend/class.backend.comments.observer.php',
    '_rex488_BackendPagination'         => 'classes/backend/class.backend.pagination.php',
    
    '_rex488_FrontendBase'              => 'classes/frontend/class.frontend.base.php',
    '_rex488_FrontendDesignator'        => 'classes/frontend/class.frontend.designator.php',
    '_rex488_FrontendCategories'        => 'classes/frontend/class.frontend.categories.php',
    '_rex488_FrontendCategory'          => 'classes/frontend/class.frontend.category.php',
    '_rex488_FrontendMetadata'          => 'classes/frontend/class.frontend.metadata.php',
    '_rex488_FrontendMetadataCategory'  => 'classes/frontend/class.frontend.metadata.category.php',
    '_rex488_FrontendMetadataArticle'   => 'classes/frontend/class.frontend.metadata.article.php',
    '_rex488_FrontendArticle'           => 'classes/frontend/class.frontend.article.php',
    '_rex488_FrontendComment'           => 'classes/frontend/class.frontend.comment.php',
    '_rex488_FrontendCommentObserver'   => 'classes/frontend/class.frontend.comment.observer.php',
    '_rex488_FrontendTrackback'         => 'classes/frontend/class.frontend.trackback.php',
    '_rex488_FrontendPagination'        => 'classes/frontend/class.frontend.pagination.php',

    'b8'                                => 'external/b8/b8.php',
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
    if(_rex488_autoload(array('subject' => $classname)) !== true)
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
    array('articles', 'Artikel', '', array('clang' => $REX['CUR_CLANG'])),
    array('comments', 'Kommentare', '', array('clang' => $REX['CUR_CLANG']))
  );

  // Backend Funktionen einbinden

  require dirname(__FILE__) . '/functions/function.backend.core.php';

  // Contentplugins directory auslesen

  _rex488_read_plugin_directory();

  // Backend Pageheader einbinden

  if(rex_request('page', 'string') == 'rexblog')
    rex_register_extension('OUTPUT_FILTER', '_rex488_add_pageheader');

}
  else
{
  // Frontend Funktionen inkludieren

  require dirname(__FILE__) . '/functions/function.frontend.core.php';
}
?>