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

abstract class _rex488_FrontendDesignator
{
	public static $next_button         = 'Nächste Seite';
	public static $prev_button         = 'Vorherige Seite';
	public static $category_no_article = 'In dieser Kategorie sind keine Beiträge vorhanden.';
	
	public static function next_button() {
		return self::$next_button;
	}
	
	public static function prev_button() {
		return self::$prev_button;
	}
	
	public static function category_no_article() {
		return self::$category_no_article;
	}
}

?>