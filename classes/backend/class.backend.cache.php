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

abstract class _rex488_BackendCache extends _rex488_BackendBase
{
  private static $category_url;

  /**
   * write_category_cache
   *
   * initializes needed class objects
   *
   * @param
   * @return
   * @throws
   */

  public static function write_category_cache()
  {
    $result = parent::$sql->getArray("SELECT * FROM " . parent::$prefix . "488_categories ORDER BY priority ASC");

    ///////////////////////////////////////////////////////////////////////////
    // prepare cache file header for categories

    $content = "<?php ";
    $content .= "\$REX['ADDON']['rexblog']['categories'] = array (";

    foreach ($result as $key => $value)
    {
      global $REX;

      ///////////////////////////////////////////////////////////////////////////
      // unset category_url variables

      self::$category_url = "";
      $category_url = "";

      ///////////////////////////////////////////////////////////////////////////
      // assign category variables

      $category_id                = $value['id'];
      $category_category_id       = $value['category_id'];
      $category_parent_id         = $value['parent_id'];
      $category_title             = $value['title'];
      $category_keywords          = $value['keywords'];
      $category_description       = $value['description'];
      $category_priority          = $value['priority'];
      $category_status            = $value['status'];

      ///////////////////////////////////////////////////////////////////////////
      // assign category url value

      self::$category_url[$category_id] = $value['title'];

      ///////////////////////////////////////////////////////////////////////////
      // assign category url value

      if ($category_parent_id > 0) {
        self::get_parents($category_parent_id);
      }

      ///////////////////////////////////////////////////////////////////////////
      // reverse category array for proper sorting

      self::$category_url = array_reverse(self::$category_url);

      ///////////////////////////////////////////////////////////////////////////
      // loop through categories to generate proper url

      foreach(self::$category_url as $k => $v) {
        $v = self::prepare_url($v);
          $category_url .= $v . '/';
      }

      ///////////////////////////////////////////////////////////////////////////
      // append the category title to the end

      $category_url .= self::prepare_url($category_title) . '.html';

      ///////////////////////////////////////////////////////////////////////////
      // query database for potential childrens

      parent::$sql->setQuery("SELECT * FROM " . parent::$prefix . "488_categories WHERE parent_id = " . $category_id . "");

      ///////////////////////////////////////////////////////////////////////////
      // if the category has children assign var

      $category_children = (self::$sql->getRows() > 0) ? 1 : 0;

      ///////////////////////////////////////////////////////////////////////////
      // generate proper cachefile content

      $content .= $category_id . " => ";
      $content .= "array (";
      $content .= "'id' => " . $category_id . ", ";
      $content .= "'category_id' => " . $category_category_id . ", ";
      $content .= "'parent_id' => " . $category_parent_id . ", ";
      $content .= "'children' => " . $category_children . ", ";
      $content .= "'title' => '" . $category_title . "', ";
      $content .= "'keywords' => '" . $category_keywords . "', ";
      $content .= "'description' => '" . $category_description . "', ";
      $content .= "'priority' => '" . $category_priority . "', ";
      $content .= "'status' => '" . $category_status . "', ";
      $content .= "'url' => '" . $category_url . "'";
      $content .= "), ";
    }

    ///////////////////////////////////////////////////////////////////////////
    // add cache file footer

    $content .= "); ?>";

    ///////////////////////////////////////////////////////////////////////////
    // write the cache file into redaxos generated folder

    rex_put_file_contents($REX['INCLUDE_PATH'] . '/generated/files/_rex488_categories.inc.php', $content);
  }

  /**
   * prepare_url
   *
   * formatiert die übergebene variable als url
   *
   * @param string $url string to format
   * @return string $url formated string
   * @throws
   */

  private static function prepare_url($url)
  {
    $url = rex_parse_article_name($url);
    $url = strtolower($url);
    return $url;
  }

  /**
   * get_parents
   *
   * erweitert das url array um die elternkategorien
   *
   * @param int $parent id for the parent category
   * @return
   * @throws
   */

  private static function get_parents($parent)
  {
    $result = parent::$sql->getArray("
      SELECT * FROM " . parent::$prefix . "488_categories
      WHERE category_id = " . $parent . "
      ORDER BY priority ASC
    ");

    self::$category_url[$result[0]['id']] = $result[0]['title'];

    if ($result[0]['parent_id'] > 0)
      self::get_parents($result[0]['parent_id']);
  }
}

?>