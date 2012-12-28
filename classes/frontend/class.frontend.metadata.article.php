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

abstract class _rex488_FrontendMetadataArticle extends _rex488_FrontendMetadata
{
  private static $article_title;
  private static $article_keywords;
  private static $article_description;

  /**
   * post keywords
   *
   * erzeugt die meta keywords für den aktuellen beitrag.
   * falls der aktuelle beitrag keine meta keywords enthält,
   * werden die meta keywords durch den startartikel des blogs erzeugt.
   *
   * @return string $article_title meta titel des beitrags
   */

  public static function get_article_title($prepend, $spacer)
  {
    self::$article_title = parent::$article_cache_pathlist[parent::$article_id]['title'];

    if($prepend === true) {
      self::$article_title = parent::get_blog_title() . $spacer . self::$article_title;
    }

    return self::$article_title;
  }

  /**
   * post keywords
   *
   * erzeugt die meta keywords für den aktuellen beitrag.
   * falls der aktuelle beitrag keine meta keywords enthält,
   * werden die meta keywords durch den startartikel des blogs erzeugt.
   *
   * @return string $article_keywords beitrag meta keywords
   */

  public static function get_article_keywords()
  {
    self::$article_keywords = parent::$article_cache_pathlist[parent::$article_id]['keywords'];

    if(empty(self::$article_keywords)) {
      self::$article_keywords = parent::get_blog_keywords();
    }

    return self::$article_keywords;
  }

  /**
   * post description
   *
   * erzeugt die meta description für den aktuellen beitrag.
   * falls der aktuelle beitrag keine meta description enthält,
   * wird die meta description durch den startartikel des blogs erzeugt.
   *
   * @return string $article_description beitrag meta description
   */

  public static function get_article_description()
  {
    self::$article_description = parent::$article_cache_pathlist[parent::$article_id]['description'];

    if(empty(self::$article_description)) {
      self::$article_description = parent::get_blog_description();
    }

    return self::$article_description;
  }
}
?>