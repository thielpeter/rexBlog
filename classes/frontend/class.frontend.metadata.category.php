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

abstract class _rex488_FrontendMetadataCategory extends _rex488_FrontendMetadata
{
  private static $category_title;
  private static $category_keywords;
  private static $category_description;

  /**
   * get_category_title
   *
   * erzeugt den meta title für die aktuelle kategorie. falls erwünscht,
   * kann mit hilfe des parameters der titel des startartikels vorangestellt werden.
   *
   * @param   boolean $prepend            artikletitel voranstellen
   * @param   string  $spacer             zeichenkette zwischen den titeln
   * @return  string  $category_keywords  meta keywords der kategorie
   */

  public static function get_category_title($prepend, $spacer)
  {
    self::$category_title = parent::$category_path[parent::$category_id]['title'];

    if($prepend === true) {
      self::$category_title = parent::get_blog_title() . $spacer . self::$category_title;
    }

    return self::$category_title;
  }

  /**
   * category keywords
   *
   * erzeugt die meta keywords für die aktuelle kategorie.
   * falls die aktuelle kategorie keine meta keywords enthält,
   * werden die meta keywords durch den startartikel des blogs erzeugt.
   *
   * @return  string $category_keywords kategorie keywords
   */

  public static function get_category_keywords()
  {
    self::$category_keywords = parent::$category_path[parent::$category_id]['keywords'];

    if(empty(self::$category_keywords)) {
      self::$category_keywords = parent::get_blog_keywords();
    }

    return self::$category_keywords;
  }

  /**
   * category description
   *
   * erzeugt die meta description für die aktuelle kategorie.
   * falls die aktuelle kategorie keine meta description enthält,
   * wird die meta description durch den startartikel des blogs erzeugt.
   *
   * @return  string  $category_description kategorie description
   */

  public static function get_category_description()
  {
    self::$category_description = parent::$category_path[parent::$category_id]['description'];

    if(empty(self::$category_description)) {
      self::$category_description = parent::get_blog_description();
    }

    return self::$category_description;
  }
}
?>