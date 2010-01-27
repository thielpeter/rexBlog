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

abstract class _rex488_FrontendCategories extends _rex488_FrontendBase
{
  private static $categories;
  private static $parents;

  /**
   * get categories
   *
   * erzeugt die root kategorien des blogs. mit hilfe des parameters
   * kann entschieden werden, ob nur die children der aktuellen kategorie
   * oder die children aller kategorien geöffnet werden sollen.
   *
   * @param boolean $opener alle kategorien oder nur die children der aktuellen anzeigen
   * @param boolean $show_post_count anzahl an beiträgen der kategorien anzeigen
   * @return string self::$categories formatierte liste aller kategorien
   * @throws
   */

  public static function get_categories($opener = false, $show_post_count = false)
  {
    // read parents for the current category

    self::get_parents(parent::$category_id);

    // loop through the category pathlist

    foreach(parent::$category_path as $key => $value)
    {
      if($value['parent_id'] === 0 && $value['status'] == '1')
      {
        if($value['id'] == parent::$category_id)
        {
          $selector = ' class="current"';
        } else if(is_array(self::$parents) && in_array($value['id'], self::$parents) === true)
        {
          $selector = ' class="parent"';
        } else
        {
          $selector = '';
        }

        // open the list tag

        self::$categories .= '<li>';

        // create the link element with its corresponding configuration

        self::$categories .= _rex488_FrontendCategory::format_category($value, $selector, $show_post_count);

        // if possible, fetch the children of the current category

        if($value['children'] > 0 && (self::$parents[$key] == $value['id'] || $opener === true))
        {
          self::get_children($value['id'], $opener, $show_post_count);
        }

        // close the list tag

        self::$categories .= '</li>' . "\n";
      }
    }

    // return all categories after completed

    return self::$categories;
  }

  /**
   * get_children
   *
   * erzeugt die children einer kategorie.
   *
   * @param
   * @return
   * @throws
   */

  private static function get_children($id = 0, $opener = false, $show_post_count = false)
  {
    self::$categories .=  '<ul>' . "\n";

    foreach(parent::$category_path as $key => $value)
    {
      if($value['parent_id'] == $id && $value['status'] == '1')
      {
        if($value['id'] == parent::$category_id)
        {
          $selector = ' class="current"';
        } else if(is_array(self::$parents) && in_array($value['id'], self::$parents) === true)
        {
          $selector = ' class="parent"';
        } else
        {
          $selector = '';
        }

        self::$categories .= '<li>';
        self::$categories .= _rex488_FrontendCategory::format_category($value, $selector, $show_post_count);

        if($value['children'] > 0 && (self::$parents[$key] == $value['id'] || $opener == true))
        {
          self::get_children($value['id'], $opener, $show_post_count);
        }

        self::$categories .= '</li>' . "\n";
      }
    }

    self::$categories .= '</ul>' . "\n";
  }

  /**
   * get_parents
   *
   * erzeugt ein array für eine kategorie
   * mit ihren dazugehörigen parents
   *
   * @param
   * @return
   * @throws
   */

  private static function get_parents($id)
  {
    foreach(parent::$category_path as $key => $value)
    {
      if($key == $id)
      {
        self::$parents[$key] = $id;

        if($id > 0)
        {
          self::get_parents($value['parent_id']);
        }
      }
    }
  }

  /**
   * get_category_post_count
   *
   * returns article count for the current category
   *
   * @param
   * @return
   * @throws
   */

  public static function get_category_post_count($category_id)
  {
    parent::$sql->setQuery("SELECT * FROM " . parent::$prefix . "488_articles WHERE ( FIND_IN_SET(" . $category_id . ", REPLACE(categories, ',', ',')) AND status = '1' )");
    return parent::$sql->getRows();
  }
}

?>