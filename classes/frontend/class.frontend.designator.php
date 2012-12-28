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

abstract class _rex488_FrontendDesignator
{
  protected static $next_button         = 'Nächste Seite';
  protected static $prev_button         = 'Vorherige Seite';
  protected static $category_no_article = 'In dieser Kategorie sind keine Beiträge vorhanden.';
  protected static $form_auhtor         = 'Das Eingabefeld Author darf nicht leer sein.';
  protected static $form_email_empty    = 'Das Eingabefeld E-Mailadresse darf nicht leer sein.';
  protected static $form_email_valid    = 'Das Eingabefeld E-Mailadresse enthält Fehler.';
  protected static $form_website_valid  = 'Das Eingabefeld Homepage enthält Fehler.';
  protected static $form_comment        = 'Das Eingabefeld Kommentar darf nicht leer sein.';

  public static function next_button()
  {
    return self::$next_button;
  }

  public static function prev_button()
  {
    return self::$prev_button;
  }

  public static function category_no_article()
  {
    return self::$category_no_article;
  }

  public static function form_auhtor()
  {
    return self::$form_auhtor;
  }

  public static function form_email_empty()
  {
    return self::$form_email_empty;
  }

  public static function form_email_valid()
  {
    return self::$form_email_valid;
  }

  public static function form_website_valid()
  {
    return self::$form_website_valid;
  }

  public static function form_comment()
  {
    return self::$form_comment;
  }
}
?>