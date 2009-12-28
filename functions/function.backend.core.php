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

function _rex488_add_pageheader()
{
  $page_header = "\n";
  $page_header .= '<link rel="stylesheet" type="text/css" href="include/addons/rexblog/files/css/backend.css" />' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/jquery.tablednd.js"></script>' . "\n";
  $page_header .= '<script type="text/javascript" src="include/addons/rexblog/files/js/backend.js"></script>' . "\n";

  return $page_header;
}

?>