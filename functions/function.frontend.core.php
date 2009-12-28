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

	function _rex488_base_loader()
	{
		$base_loader = _rex488_FrontendBase::getInstance();
		$base_loader->set_base();
	}

	/**
	 * _rex488_the_category_id
	 * 
	 * liefert die id der aktuellen kategorie zurück.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_category_id()
	{
		$categories = _rex488_FrontendBase::getInstance();
		return $categories->get_category_id();
	}
	
	/**
	 * _rex488_the_categories
	 * 
	 * erzeugt die navigation als formatiertes listenelement.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_categories($opener = false, $show_post_count = false)
	{
		return _rex488_FrontendCategories::get_categories($opener, $show_post_count);
	}
	
	/**
	 * _rex488_the_meta_title
	 * 
	 * erzeugt den meta-title basierend auf dem aktuellen template state.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_meta_title($prepend = false, $spacer = ' | ')
	{
		if(_rex488_is_post()) {
			return _rex488_FrontendMetadataPost::get_post_title($prepend, $spacer);
		}
		else if(_rex488_is_category()) {
			return _rex488_FrontendMetadataCategory::get_category_title($prepend, $spacer);
		}
		else {
			return _rex488_FrontendMetadata::get_blog_title();
		}
	}
	
	/**
	 * _rex488_the_meta_keywords
	 * 
	 * erzeugt die meta-keywords basierend auf dem aktuellen template state.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_meta_keywords()
	{
		if(_rex488_is_post()) {
			return _rex488_FrontendMetadataPost::get_post_keywords();
		}
		else if(_rex488_is_category()) {
			return _rex488_FrontendMetadataCategory::get_category_keywords();
		}
		else {
			return _rex488_FrontendMetadata::get_blog_keywords();
		}
	}
	
	/**
	 * _rex488_meta_description
	 * 
	 * erzeugt die meta-description basierend auf dem aktuellen template state.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_meta_description()
	{
		if(_rex488_is_post()) {
			return _rex488_FrontendMetadataPost::get_post_description();
		}
		else if(_rex488_is_category()) {
			return _rex488_FrontendMetadataCategory::get_category_description();
		}
		else {
			return _rex488_FrontendMetadata::get_blog_description();
		}
	}
	
	/**
	 * _rex488_the_content
	 * 
	 * erzeugt die inhalte basierend auf dem aktuellen template state.
	 * einstellungen und formatierungen an der ausgabe können direkt in
	 * den einzelnen dateien im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_content($pagination = 4)
	{
		if(_rex488_is_post()) {
			return _rex488_FrontendPost::get_post_details();
		}
		else if(_rex488_is_category()) {
			return _rex488_FrontendPost::get_post_overview($pagination);
		}
	}
	
	/**
	 * _rex488_the_pagination
	 * 
	 * erzeugt die inhalte basierend auf dem aktuellen template state.
	 * einstellungen und formatierungen an der ausgabe können direkt in
	 * den einzelnen dateien im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_pagination()
	{
		return _rex488_FrontendPagination::get_pagination();
	}
	
	/**
	 * _rex488_the_post
	 * 
	 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
	 * und formatierungen an der ausgabe können direkt in der separaten
	 * post.inc.php datei im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_post()
	{
		return _rex488_FrontendPost::_rex488_the_post();
	}
	
	/**
	 * _rex488_the_excerpt
	 * 
	 * erzeugt den beitragstext basierend auf dem aktuellen state. einstellungen
	 * und formatierungen an der ausgabe können direkt in der separaten
	 * post.inc.php datei im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_excerpt($type = 'post', $length = 0, $clean = '')
	{
		return _rex488_FrontendPost::_rex488_the_excerpt($type, $length, $clean);
	}
	
	/**
	 * _rex488_the_post_date
	 * 
	 * erzeugt das datum des beitragstextes. einstellungen und
	 * formatierungen an der ausgabe können direkt in der separaten
	 * post.inc.php datei im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_post_date($date_format = 'd.m.Y')
	{
		return _rex488_FrontendPost::_rex488_the_post_date($date_format);
	}
	
	/**
	 * _rex488_the_title
	 * 
	 * erzeugt den beitragstitel basierend auf dem aktuellen state. einstellungen
	 * und formatierungen an der ausgabe können direkt in der separaten
	 * post.inc.php datei im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_title()
	{
		return _rex488_FrontendPost::_rex488_the_title();
	}
	
	/**
	 * _rex488_the_url
	 * 
	 * erzeugt die beitragsurl basierend auf dem aktuellen state. einstellungen
	 * und formatierungen an der ausgabe können direkt in der separaten
	 * post.inc.php datei im template verzeichnis gemacht werden.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_url()
	{
		return _rex488_FrontendPost::_rex488_the_url();
	}
	
	/**
	 * _rex488_is_category
	 * 
	 * prüft anhand der übergebenen url den status und setzt
	 * anhand des ergebnisses den neuen template state.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_is_category()
	{
		return _rex488_FrontendBase::$is_category;
	}
	
	/**
	 * _rex488_is_post
	 * 
	 * prüft anhand der übergebenen url den status und setzt
	 * anhand des ergebnisses den neuen template state.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_is_post()
	{
		return _rex488_FrontendBase::$is_post;
	}
	
	/**
	 * _rex488_the_debug_box
	 * 
	 * ausgabe einer debugbox mit verschiedenen information.
	 *
	 * @throws
	 * @global
	 * @param
	 * @return
	 */

	function _rex488_the_debug_box()
	{
		$content = '<div id="debug-information">';
		$content .= '<p><b>The Current Category-Id is</b>' .  _rex488_the_category_id() . '</p>';
		$content .= '<p><b>The Current URL is</b>' .  rex_request('_rex488_uri', 'string') . '</p>';
		$content .= '<p><b>The Meta-Title is</b>' .  _rex488_the_meta_title() . '</p>';
		$content .= '<p><b>The Meta-Keywords are</b>' .  _rex488_the_meta_keywords() . '</p>';
		$content .= '<p><b>The Meta-Description is</b>' .  _rex488_the_meta_description() . '</p>';
		$content .= '</div><div class="clearfix"></div>';
		
		return $content;
	}

?>