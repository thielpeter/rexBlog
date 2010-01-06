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

class _rex488_FrontendBase
{
  private static $instance = null;

  protected static $url;
  protected static $category_path;
  protected static $post_path;
  protected static $post_pathlist;
  protected static $category_id = 0;
  protected static $post_id;

  protected static $prefix = 'rex_';
  protected static $sql;

  protected static $the_page_amount;
  protected static $the_page_current;
  protected static $the_page_max;

  public static $is_category = false;
  public static $is_post = false;

  /**
   * singleton
   *
   * initializes the class as singleton
   *
   * @param
   * @return
   * @throws
   */

  public static function getInstance()
  {
    if(empty(self::$instance))
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * __construct
   *
   * @param
   * @return
   * @throws
   */

  public function __construct()
  {
    global $REX;

    self::$prefix = $REX['TABLE_PREFIX'];
    self::$sql = rex_sql::getInstance();

    self::$url = rex_request('_rex488_uri', 'string');
    self::$url = str_replace('%20', '+', self::$url);
    self::$url = str_replace(' ', '+', self::$url);

    require $REX['INCLUDE_PATH'] . '/generated/files/_rex488_categories.inc.php';
    self::$category_path = $REX['ADDON']['rexblog']['categories'];

    require $REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.pathlist.inc.php';
    self::$post_pathlist = $REX['ADDON']['rexblog']['post']['pathlist'];
  }

  /**
   * __clone
   *
   * @param
   * @return
   * @throws
   */

  public function __clone()
  {

  }

  /**
   * set category id
   *
   * sets the current category id
   *
   * @param
   * @return
   * @throws
   */

  public static function set_category_id()
  {
    global $REX;

    foreach(self::$category_path as $key => $value)
    {
      if($value['url'] == self::$url)
      {
        self::$category_id = $value['id'];
        self::set_is_category(true);

        if(file_exists($REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.' . self::$category_id . '.inc.php'))
          require $REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.' . self::$category_id . '.inc.php';

        if(!isset($REX['ADDON']['rexblog']['post'][self::$category_id]))
        {
          $REX['ADDON']['rexblog']['post'][self::$category_id] = array();
        }

        self::$post_path = $REX['ADDON']['rexblog']['post'][self::$category_id];
      }
    }
  }

  /**
   * set post id
   *
   * sets the current post id
   *
   * @param
   * @return
   * @throws
   */

  public static function set_post_id()
  {
    global $REX;

    foreach(self::$post_pathlist as $key => $value)
    {
      foreach($value['url'] as $url_key => $url_value)
      {
        if($url_value == self::$url)
        {
          self::$category_id = $value['categories'][$url_key];
          self::$post_id = $value['id'];
          self::set_is_post(true);

          require $REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.' . self::$category_id . '.inc.php';
          self::$post_path = $REX['ADDON']['rexblog']['post'][self::$category_id];
        }
      }
    }
  }

  /**
   * set category state
   *
   * sets the current category state
   *
   * @param
   * @return
   * @throws
   */

  public static function set_is_category($state = false)
  {
    self::$is_category = $state;
  }

  /**
   * set post state
   *
   * sets the current post state
   *
   * @param
   * @return
   * @throws
   */

  public static function set_is_post($state = false)
  {
    self::$is_post = $state;
  }

  /**
   * get category id
   *
   * returns the current category id
   *
   * @param
   * @return
   * @throws
   */

  static public function get_category_id()
  {
    return self::$category_id;
  }

  /**
   * get post id
   *
   * returns the post category id
   *
   * @param
   * @return
   * @throws
   */

  static public function get_post_id()
  {
    return self::$post_id;
  }

  /**
   * set_template_state
   *
   * setzt den aktuellen status des templates.
   *
   * @param
   * @return
   * @throws
   */

  public static function set_base()
  {
    self::set_category_id();
    self::set_post_id();
  }

  /**
   * get_article_base
   *
   * liefert den startartikel des blogs zur�ck.
   *
   * @param
   * @return string $article_name formatierter artikelname
   * @throws
   */

  public static function get_article_base()
  {
    $article = OOArticle::getArticleById(rex_request('article_id', 'int'));
    $article_name = rex_parse_article_name($article->getName());
    $article_name = strtolower($article_name);
    return $article_name;
  }
}

?>