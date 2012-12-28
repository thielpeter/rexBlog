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

abstract class _rex488_FrontendMetadata extends _rex488_FrontendBase
{
  private static $blog_title;
  private static $blog_keywords;
  private static $blog_description;

  /**
   * blog title
   *
   * erzeugt den meta title des startartikels.
   * diesee funktion wird auch dazu verwendet um dem
   * kategorie oder beitrags titel den starttitel voranzustellen.
   *
   * @return string $blog_title meta title des startartikels
   */

  public static function get_blog_title()
  {
    self::$blog_title = OOCategory::getCategoryById(rex_request('article_id', 'int'));
    self::$blog_title = self::$blog_title->getValue('name');

    return self::$blog_title;
  }

  /**
   * get_blog_keywords
   *
   * erzeugt die meta keywords des startartikels.
   *
   * @return string $blog_keywords meta keywords des startartikels
   */

  public static function get_blog_keywords()
  {
    self::$blog_keywords = OOCategory::getCategoryById(rex_request('article_id', 'int'));
    self::$blog_keywords = self::$blog_keywords->getValue('art_keywords');

    return self::$blog_keywords;
  }

  /**
   * get_blog_description
   *
   * erzeugt die meta description des startartikels.
   *
   * @return string $blog_description meta description des startartikels
   */

  public static function get_blog_description()
  {
    self::$blog_description = OOCategory::getCategoryById(rex_request('article_id', 'int'));
    self::$blog_description = self::$blog_description->getValue('art_description');

    return self::$blog_description;
  }
}
?>