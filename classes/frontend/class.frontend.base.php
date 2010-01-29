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

class _rex488_FrontendBase
{
  private static $instance = null;

  protected static $url;
  protected static $category_path;
  protected static $article_path;
  protected static $article_pathlist;
  protected static $category_id = 0;
  protected static $article_id;

  protected static $rewrite;
  protected static $include_path = '';
  protected static $prefix = 'rex_';
  protected static $sql;

  protected static $the_page_amount;
  protected static $the_page_current;
  protected static $the_page_max;

  public static $is_category = false;
  public static $is_article = false;

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

    self::$rewrite = $REX['MOD_REWRITE'];
    self::$include_path = $REX['INCLUDE_PATH'];
    self::$prefix = $REX['TABLE_PREFIX'];
    self::$sql = rex_sql::getInstance();

    self::$url = rex_request('_rex488_uri', 'string');
    self::$url = str_replace('%20', '+', self::$url);
    self::$url = str_replace(' ', '+', self::$url);

    if(file_exists(self::$include_path . '/generated/files/_rex488_categories.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_categories.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['categories'];
    } else {
      self::$category_path = array();
    }

    if(file_exists(self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php';
        self::$article_pathlist = $REX['ADDON']['rexblog']['article']['pathlist'];
    } else {
      self::$article_pathlist = array();
    }
  }

  /**
   * __clone
   *
   * @param
   * @return
   * @throws
   */
  
  public function __clone() {}

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

        if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . self::$category_id . '.inc.php'))
          require self::$include_path . '/generated/files/_rex488_article.' . self::$category_id . '.inc.php';

        if(!isset($REX['ADDON']['rexblog']['article'][self::$category_id]))
        {
          $REX['ADDON']['rexblog']['article'][self::$category_id] = array();
        }

        self::$article_path = $REX['ADDON']['rexblog']['article'][self::$category_id];
      }
    }
  }

  /**
   * set article id
   *
   * sets the current article id
   *
   * @param
   * @return
   * @throws
   */

  public static function set_article_id()
  {
    global $REX;

    foreach(self::$article_pathlist as $key => $value)
    {
      foreach($value['url'] as $url_key => $url_value)
      {
        if($url_value == self::$url)
        {
          self::$category_id = $value['categories'][$url_key];
          self::$article_id = $value['id'];
          self::set_is_article(true);

          if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . self::$category_id . '.inc.php')) {
            require self::$include_path . '/generated/files/_rex488_article.' . self::$category_id . '.inc.php';
              self::$article_path = $REX['ADDON']['rexblog']['article'][self::$category_id];
          }
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
   * set article state
   *
   * sets the current article state
   *
   * @param
   * @return
   * @throws
   */

  public static function set_is_article($state = false)
  {
    self::$is_article = $state;
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
   * get article id
   *
   * returns the article category id
   *
   * @param
   * @return
   * @throws
   */

  static public function get_article_id()
  {
    return self::$article_id;
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
    self::set_article_id();
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