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

require $REX['INCLUDE_PATH'] . '/layout/top.php';

// Title definieren

rex_title('rexblog V' . $REX['ADDON']['version']['rexblog'], $REX['ADDON']['rexblog']['SUBPAGES']);

// Subpages auswerten

switch($subpage)
{
	case 'categories':
		require _rex488_PATH . 'pages/categories.inc.php';
		break;
	default:
		require _rex488_PATH . 'pages/categories.inc.php';
		break;
}

// Fusszeile einbinden

require $REX['INCLUDE_PATH'] . '/layout/bottom.php';

?>

