<?php

/*
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
*/

if (defined('_rex488_PATH')) return;
define('_rex488_PATH', $REX['INCLUDE_PATH'] . '/addons/rexblog/');

// Sprach-Konfiguration

if($REX['REDAXO']) {
  $I18N->appendFile(dirname(__FILE__) . '/lang/');
}

// Addon-Konfiguration

$REX['ADDON']['rxid']['rexblog'] = '488';
$REX['ADDON']['page']['rexblog'] = 'rexblog';
$REX['ADDON']['name']['rexblog'] = 'Rexblog';
$REX['ADDON']['perm']['rexblog'] = "rexblog[]";
$REX['ADDON']['version']['rexblog'] = file_get_contents(_rex488_PATH . 'version');
$REX['ADDON']['author']['rexblog'] = 'mediastuttgart werbeagentur';
$REX['ADDON']['supportpage']['rexblog'] = 'http://www.mediastuttgart.de';

$REX['PERM'][] = "rexblog[]";

/**
 * define subpages based on perms
 *
 * @global
 * @param
 * @throws
 * @return
 */

if($REX['REDAXO'])
{
  $REX['ADDON'][$rex_addon_page]['SUBPAGES'] = array();

  if((isset($REX["LOGIN"]) && $REX['USER']->hasPerm('rexblog[categories]')) || (isset($REX["LOGIN"]) && $REX['USER']->isAdmin()))
  {
    array_push($REX['ADDON'][$rex_addon_page]['SUBPAGES'], array('categories', $I18N->msg('categories'), '', array('clang' => $REX['CUR_CLANG'])));
  }

  if((isset($REX["LOGIN"]) && $REX['USER']->hasPerm('rexblog[postings]')) || (isset($REX["LOGIN"]) && $REX['USER']->isAdmin()))
  {
    array_push($REX['ADDON'][$rex_addon_page]['SUBPAGES'], array('postings', $I18N->msg('postings'), '', array('clang' => $REX['CUR_CLANG'])));
  }

  if((isset($REX["LOGIN"]) && $REX['USER']->hasPerm('rexblog[comments]')) || (isset($REX["LOGIN"]) && $REX['USER']->isAdmin()))
  {
    array_push($REX['ADDON'][$rex_addon_page]['SUBPAGES'], array('comments', $I18N->msg('comments'), '', array('clang' => $REX['CUR_CLANG'])));
  }

  if((isset($REX["LOGIN"]) && $REX['USER']->hasPerm('rexblog[trackbacks]')) || (isset($REX["LOGIN"]) && $REX['USER']->isAdmin()))
  {
    array_push($REX['ADDON'][$rex_addon_page]['SUBPAGES'], array('trackbacks', $I18N->msg('trackbacks'), '', array('clang' => $REX['CUR_CLANG'])));
  }

  if((isset($REX["LOGIN"]) && $REX['USER']->hasPerm('rexblog[settings]')) || (isset($REX["LOGIN"]) && $REX['USER']->isAdmin()))
  {
    array_push($REX['ADDON'][$rex_addon_page]['SUBPAGES'], array('settings', $I18N->msg('settings'), '', array('clang' => $REX['CUR_CLANG'])));
  }
}

/**
 * include required functions
 *
 * @global
 * @param
 * @throws
 * @return
 */

require dirname(__FILE__) . '/functions/function.rexblog.clang.inc.php';
require dirname(__FILE__) . '/functions/function.rexblog.breadcrumb.inc.php';
require dirname(__FILE__) . '/functions/function.rexblog.frontend.inc.php';
require dirname(__FILE__) . '/functions/function.rexblog.template.inc.php';

/**
 * include required classes
 *
 * @global
 * @param
 * @throws
 * @return
 */

require dirname(__FILE__) . '/settings.inc.php';
require dirname(__FILE__) . '/categories.inc.php';

/**
 * include required classes
 *
 * @global
 * @param
 * @throws
 * @return
 */

require dirname(__FILE__) . '/classes/class.rexblog.settings.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.categories.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.postings.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.comments.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.trackbacks.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.languages.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.paging.inc.php';
require dirname(__FILE__) . '/classes/class.rexblog.template.inc.php';

/**
 * autoload classes
 *
 * @global
 * @param
 * @throws
 * @return
 */

function __autoload($classname)
{
  static $classes = array(
  '_rex488_BackendBase' => 'backend/class.backend.base.php',
  '_rex488_BackendException' => 'backend/class.backend.exception.php',
  '_rex488_BackendCategoryInterface' => 'backend/interface/interface.backend.categories.php',
  '_rex488_BackendPost' => 'backend/class.backend.post.php',
  '_rex488_BackendCategories' => 'backend/class.backend.categories.php',
  '_rex488_FrontendBase' => 'frontend/class.frontend.base.php',
  '_rex488_FrontendDesignator' => 'frontend/class.frontend.designator.php',
  '_rex488_FrontendCategories' => 'frontend/class.frontend.categories.php',
  '_rex488_FrontendCategory' => 'frontend/class.frontend.category.php',
  '_rex488_FrontendMetadata' => 'frontend/class.frontend.metadata.php',
  '_rex488_FrontendMetadataCategory' => 'frontend/class.frontend.metadata.category.php',
  '_rex488_FrontendMetadataPost' => 'frontend/class.frontend.metadata.post.php',
  '_rex488_FrontendPost' => 'frontend/class.frontend.post.php',
  '_rex488_FrontendPagination' => 'frontend/class.frontend.pagination.php',
  );

  if(isset($classes[$classname]))
  {
    require_once dirname(__FILE__) . '/classes/' . $classes[$classname];
  }
}

/**
 * include b8 class
 *
 * @global
 * @param
 * @throws
 * @return
 */

require dirname(__FILE__) . '/extensions/b8/b8.php';

/**
 * include trackback class
 *
 * @global
 * @param
 * @throws
 * @return
 */

require dirname(__FILE__) . '/extensions/trackback/trackback_cls.php';

/**
 * process trackbacks before any html output for correct xml header
 *
 * @global
 * @param
 * @throws
 * @return
 */

preg_match('/(\btrackback\b)\/([0-9]{1,3})\/index.html/', $_SERVER['REQUEST_URI'], $_TRACKBACKS);

if (is_array($_TRACKBACKS) && count($_TRACKBACKS) > 0 && $_POST['url'] != "" && $_POST['excerpt'] != "" && $_POST['title'] != "")
{
  header('Content-Type: text/xml');

  $trackback = new Trackback(rexblog_settings::get_blog_name(), rexblog_settings::get_blog_name(), 'UTF-8');

  $sql = new rex_sql();

  $sql->setQuery("SELECT * FROM " . $REX['TABLE_PREFIX'] . "488_rexblog_trackbacks_in
						WHERE ( id = '" . $_TRACKBACKS[2] . "'
						AND url = '" . $_POST['url'] . "'
						AND text = '" . $_POST['excerpt'] . "'
						AND title = '" . $_POST['title'] . "' )");

  if($sql->getRows() == 0)
  {
    $sql->table = $REX['TABLE_PREFIX'] . '488_rexblog_trackbacks_in';
    $sql->setValue('id', $_TRACKBACKS[2]);
    $sql->setValue('name', $_POST['blog_name']);
    $sql->setValue('title', $_POST['title']);
    $sql->setValue('text', $_POST['excerpt']);
    $sql->setValue('url', $_POST['url']);
    $sql->setValue('timestamp', date('Y-m-d H:i:s'));
    $sql->setValue('status', '0');

    if($sql->insert())
    {
      echo $trackback->recieve(true);
    }
    else
    {
      echo $trackback->recieve(false);
    }
  }
  else
  {
    echo $trackback->recieve(false);
  }

  exit();
}
else if (is_array($_TRACKBACKS) && count($_TRACKBACKS) > 0)
{
  exit();
}	

/**
 * create new b8 instance
 *
 * @global
 * @param
 * @throws
 * @return
 */

if(OOAddon::isAvailable('rexblog'))
{
  $config_database = array(
	  'database'   => $REX['DB']['1']['NAME'],
	  'table_name' => $REX['TABLE_PREFIX'] . '488_rexblog_b8',
	  'host'       => $REX['DB']['1']['HOST'],
	  'user'       => $REX['DB']['1']['LOGIN'],
	  'pass'       => $REX['DB']['1']['PSW']
  );

  $config_b8 = array(
	  'storage' => 'mysql'
  );

  $b8 = new b8($config_database, $config_b8);
}

/**
 * load backend configuration
 *
 * @global
 * @param
 * @throws
 * @return
 */

if(OOAddon::isAvailable('rexblog'))
{
  rexblog_settings::read_settings();
}

/**
 * frontend edits
 *
 * @global
 * @param
 * @throws
 * @return
 */

if(rex_request('admin_edit_comment', 'string') == 'true')
{
  if(!is_numeric($_SESSION[$REX['INSTNAME']]['UID']))
    return;

  rexblog_comments::feedit_update_comment(rex_request('id', 'string'), rex_request('value', 'string'));
  print rex_request('value', 'string');
  exit();
}

if(rex_request('admin_delete_comment', 'string') == 'true')
{
  if(!is_numeric($_SESSION[$REX['INSTNAME']]['UID']))
    return;

  rexblog_comments::feedit_delete_comment(rex_request('id', 'string'));
  exit();
}

if(rex_request('admin_spam_comment', 'string') == 'true')
{
  if(!is_numeric($_SESSION[$REX['INSTNAME']]['UID']))
    return;

  rexblog_comments::feedit_spam_comment(rex_request('value', 'string'));
  exit();
}

if(rex_request('admin_ham_comment', 'string') == 'true')
{
  if(!is_numeric($_SESSION[$REX['INSTNAME']]['UID']))
    return;

  rexblog_comments::feedit_ham_comment(rex_request('value', 'string'));
  exit();
}

/**
 * datatables handler
 *
 * @global
 * @param
 * @throws
 * @return
 */

if($REX['REDAXO'] && rex_request('datatables', 'string') == "comments")
{
  require dirname(__FILE__) . '/extensions/datatables/extensions/extension.datatables.comments.inc.php';
  exit();
}

if($REX['REDAXO'] && rex_request('datatables', 'string') == "postings")
{
  require dirname(__FILE__) . '/extensions/datatables/extensions/extension.datatables.postings.inc.php';
  exit();
}

if($REX['REDAXO'] && rex_request('datatables', 'string') == "trackbacks")
{
  require dirname(__FILE__) . '/extensions/datatables/extensions/extension.datatables.trackbacks.inc.php';
  exit();
}

/**
 * rexblog backend injections
 *
 * @global
 * @param
 * @throws
 * @return
 */

if(rex_request('rexblog_sendfile', 'string') != "")
{
  rex_send_file($REX['INCLUDE_PATH'] . '/addons/rexblog/' . rex_request('rexblog_sendfile', 'string'), rex_request('type', 'string'));
  exit;
}

/**
 * rexblog backend injections
 *
 * @global
 * @param
 * @throws
 * @return
 */

if($REX['REDAXO'])
{
  rex_register_extension('OUTPUT_FILTER', 'A488_DATATABLES');

  if(OOPlugin::isAvailable('be_style', 'agk_skin'))
  {
    $A488_agk_skin_datatables = '<link rel="stylesheet" type="text/css" href="index.php?rexblog_sendfile=extensions/datatables/files/css/agk_skin/rexblog_table.css&type=text/css" />';
  }
  else
  {
    $A488_agk_skin_datatables = '<link rel="stylesheet" type="text/css" href="index.php?rexblog_sendfile=extensions/datatables/files/css/rexblog_table.css&type=text/css" />';
  }

  function A488_DATATABLES($params)
  {
    global $A488_agk_skin_datatables;

    if(rex_request('page', 'string') == 'rexblog')
    {
      return str_replace('</head>',  '
			<!-- extension: datatables :: start -->
			' . $A488_agk_skin_datatables . '
			<script src="index.php?rexblog_sendfile=extensions/datatables/files/js/jquery.dataTables.min.js&type=text/javascript" type="text/javascript"></script>
			<script src="index.php?rexblog_sendfile=extensions/datatables/files/js/comments.js&type=text/javascript" type="text/javascript"></script>
			<script src="index.php?rexblog_sendfile=extensions/datatables/files/js/postings.js&type=text/javascript" type="text/javascript"></script>
			<script src="index.php?rexblog_sendfile=extensions/datatables/files/js/trackbacks.js&type=text/javascript" type="text/javascript"></script>
			<!-- extension: datatables :: end -->
	</head>'. "\n", $params['subject']);
    }
  }

  if(OOPlugin::isAvailable('be_style', 'agk_skin'))
  {
    $A488_agk_skin_backend = '<link rel="stylesheet" type="text/css" href="index.php?rexblog_sendfile=files/css/agk_skin/rexblog.css&type=text/css" />';
  }
  else
  {
    $A488_agk_skin_backend = '<link rel="stylesheet" type="text/css" href="index.php?rexblog_sendfile=files/css/rexblog.css&type=text/css" />';
  }

  rex_register_extension('OUTPUT_FILTER', 'A488_REXBLOG');

  function A488_REXBLOG($params)
  {
    global $A488_agk_skin_backend;

    if(rex_request('page', 'string') == 'rexblog')
    {
      return str_replace('</head>',  '
    	<!-- addon: rexblog :: start -->
			' . $A488_agk_skin_backend . '
		<script src="index.php?rexblog_sendfile=files/js/jquery.tablednd_0_5.js&type=text/javascript" type="text/javascript"></script>
    <script src="index.php?rexblog_sendfile=files/js/jquery.cookie.js&type=text/javascript" type="text/javascript"></script>
		<script src="index.php?rexblog_sendfile=files/js/rexblog.js&type=text/javascript" type="text/javascript"></script>
    	<!-- addon: rexblog :: end -->
	</head>'. "\n", $params['subject']);
    }
  }
}
?>