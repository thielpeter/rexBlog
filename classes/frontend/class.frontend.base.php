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

  public static $url;
  public static $article_base;
  public static $resource_params = null;
  public static $is_category = false;
  public static $is_article = false;
  public static $is_alternate = false;

  protected static $category_cache;
  protected static $article_cache;
  protected static $article_cache_pathlist;
  protected static $category_id = 0;
  protected static $article_id;
  protected static $rewrite;
  protected static $include_path = '';
  protected static $prefix = 'rex_';
  protected static $sql;
  protected static $the_page_amount;
  protected static $the_page_current;
  protected static $the_page_max;
  protected static $the_page_count;

  /**
   * singleton
   *
   * initializes the class as singleton
   *
   * @param
   * @return
   * @throws
   */

  public static function _rex488_frontend_core_instance()
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

    ///////////////////////////////////////////////////////////////////////////
    // global redaxo var

    global $REX;

    ///////////////////////////////////////////////////////////////////////////
    // set base parameters

    self::$rewrite       = $REX['MOD_REWRITE'];
    self::$include_path  = $REX['INCLUDE_PATH'];
    self::$prefix        = $REX['TABLE_PREFIX'];
    self::$sql           = rex_sql::getInstance();
    self::$sql->debugsql = 0;
    self::$url           = rex_request('_rex488_uri', 'string');

    ///////////////////////////////////////////////////////////////////////////
    // set base article

    $article = OOArticle::getArticleById(rex_request('article_id', 'int'));

    self::$article_base = rex_parse_article_name($article->getName());
    self::$article_base = strtolower(self::$article_base);

    ///////////////////////////////////////////////////////////////////////////
    // check for existing category cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_category.inc.php')) {
      include self::$include_path . '/generated/files/_rex488_category.inc.php';
        self::$category_cache = $REX['ADDON']['rexblog']['category'];
    } else {
      self::$category_cache = array();
    }

    ///////////////////////////////////////////////////////////////////////////
    // check for existing article pathlist cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php')) {
      include self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php';
        self::$article_cache_pathlist = $REX['ADDON']['rexblog']['pathlist']['article'];
    } else {
      self::$article_cache_pathlist = array();
    }

    ///////////////////////////////////////////////////////////////////////////
    // register frontend vendor extension

    rex_register_extension('REX488_FRONTEND_VENDOR', '_rex488_frontend_vendor');

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

  public static function set_is_category()
  {
    global $REX;

    foreach(self::$category_cache as $key => $value)
    {
      if($value['url'] == self::$url) {
        self::$category_id = $value['id'];
          self::$is_category = true;

        foreach($value['articles'] as $article) {
          if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . $article. '.inc.php'))
            include self::$include_path . '/generated/files/_rex488_article.' . $article . '.inc.php';
        }

        if(!isset($REX['ADDON']['rexblog']['article'])) {
          $REX['ADDON']['rexblog']['article'] = array();
        }

        self::$article_cache = $REX['ADDON']['rexblog']['article'];
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

  public static function set_is_article()
  {
    global $REX;

    foreach(self::$article_cache_pathlist as $key => $value)
    {
      foreach($value['url'] as $url_key => $url_value)
      {
        if($url_value == self::$url)
        {
          self::$category_id = $value['categories'][$url_key];
          self::$article_id  = $value['id'];
          self::$is_article  = true;

          if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . self::$article_id . '.inc.php')) {
            include self::$include_path . '/generated/files/_rex488_article.' . self::$article_id . '.inc.php';
              self::$article_cache = $REX['ADDON']['rexblog']['article'];
          }
        }
      }
    }
  }

  /**
   * set_is_alternate
   *
   * sets the current category id
   *
   * @param
   * @return
   * @throws
   */

  public static function set_is_alternate()
  {
    if((boolean) self::$category_id === false)
    {
      self::$is_alternate = true;
    }   
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

  public static function _rex488_set_base_properties()
  {
    self::set_is_category();
    self::set_is_article();
    self::set_is_alternate();

    rex_register_extension_point('REX488_FRONTEND_VENDOR', '', array('url' => self::$url, 'category_id' => self::$category_id, 'article_id' => self::$article_id), true);
  }

  /**
   * get_article_base
   *
   * liefert den startartikel des blogs zurück.
   *
   * @param
   * @return string $article_name formatierter artikelname
   * @throws
   */

  public static function get_article_base()
  {
    global $REX;

    if((boolean) $REX['ADDON']['frontend']['rexblog']['base'] === true)
      return self::$article_base;
  }

  /**
   * parse_article_resource
   *
   * parses a given resource to fit current rewrite environment
   *
   * @param string $resource given resource
   * @param array $extended_params params to extend resource
   * @return string $parsed_resource parsed resource
   * @throws
   */

  protected static function parse_article_resource($resource, $extended_params = null, $eliminate = false)
  {
    if((boolean) self::$rewrite === true)
    {
      $parsed_resource = self::get_article_base() . '/' . $resource;
    } else
    {
      if(isset(self::$resource_params) && count(self::$resource_params) > 0 && $eliminate === false)
      {
        foreach(self::$resource_params as $param => $value)
        {
          $extend .= '&' . $param . "=" . $value;
        }
      }

      $parsed_resource = rex_getUrl(rex_request('article_id', 'int')) . '&_rex488_uri=' . $resource . $extend;
    }

    return $parsed_resource;
  }
}
?>