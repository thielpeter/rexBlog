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

if(!rex_request('output', 'boolean'))
  require $REX['INCLUDE_PATH'] . '/layout/top.php';

// Title definieren

if(!rex_request('output', 'boolean'))
  rex_title('rexblog (v' . $REX['ADDON']['version']['rexblog'] . ')', $REX['ADDON']['rexblog']['SUBPAGES']);

// Subpages auswerten

switch(rex_request('subpage', 'string'))
{
  case 'articles':
    require _rex488_PATH . 'pages/articles.inc.php';
    break;
  case 'categories':
    require _rex488_PATH . 'pages/categories.inc.php';
    break;
  default:
    require _rex488_PATH . 'pages/categories.inc.php';
    break;
}

// Fusszeile einbinden

if(!rex_request('output', 'boolean'))
  require $REX['INCLUDE_PATH'] . '/layout/bottom.php';

?>

