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

  protected static $category_path;
  protected static $article_path;
  protected static $category_pathlist;
  protected static $article_pathlist;
  protected static $archive_pathlist;
  protected static $category_id = 0;
  protected static $article_id;
  public static $article_base;

  protected static $rewrite;
  protected static $include_path = '';
  protected static $prefix = 'rex_';
  protected static $sql;

  public static $resource_params = null;

  protected static $the_page_amount;
  protected static $the_page_current;
  protected static $the_page_max;
  protected static $the_page_count;

  public static $is_category = false;
  public static $is_article = false;
  public static $is_alternate = false;

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
    ///////////////////////////////////////////////////////////////////////////
    // global redaxo var

    global $REX;

    ///////////////////////////////////////////////////////////////////////////
    // set base parameters

    self::$rewrite      = $REX['MOD_REWRITE'];
    self::$include_path = $REX['INCLUDE_PATH'];
    self::$prefix       = $REX['TABLE_PREFIX'];
    self::$sql          = rex_sql::getInstance();

    self::$url = rex_request('_rex488_uri', 'string');
    self::$url = str_replace('%20', '+', self::$url);
    self::$url = str_replace(' ', '+', self::$url);

    ///////////////////////////////////////////////////////////////////////////
    // set base article

    $article = OOArticle::getArticleById(rex_request('article_id', 'int'));
    self::$article_base = rex_parse_article_name($article->getName());
    self::$article_base = strtolower(self::$article_base);

    ///////////////////////////////////////////////////////////////////////////
    // check for existing category cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_categories.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_categories.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['categories'];
    } else {
      self::$category_path = array();
    }

    ///////////////////////////////////////////////////////////////////////////
    // check for existing category path cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_category.pathlist.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_category.pathlist.inc.php';
        self::$category_pathlist = $REX['ADDON']['rexblog']['category_pathlist'];
    } else {
      self::$category_pathlist = array();
    }

    ///////////////////////////////////////////////////////////////////////////
    // check for existing article path cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_article.pathlist.inc.php';
        self::$article_pathlist = $REX['ADDON']['rexblog']['pathlist'];
    } else {
      self::$article_pathlist = array();
    }

    ///////////////////////////////////////////////////////////////////////////
    // check for existing archive path cache file and include

    if(file_exists(self::$include_path . '/generated/files/_rex488_archive.pathlist.inc.php')) {
      require self::$include_path . '/generated/files/_rex488_archive.pathlist.inc.php';
        self::$archive_pathlist = $REX['ADDON']['rexblog']['archive']['pathlist'];
    } else {
      self::$archive_pathlist = array();
    }
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

  public static function set_is_category()
  {
    global $REX;

    /*
    print '<pre>';
    print_r(self::$category_path);
    print '</pre>';
    */

    foreach(self::$category_path as $key => $value)
    {
      if($value['url'] == self::$url)
      {
        self::$category_id = $value['id'];
        self::$is_category = true;

        foreach($value['articles'] as $article)
        {
          if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . $article. '.inc.php'))
            require self::$include_path . '/generated/files/_rex488_article.' . $article . '.inc.php';
        }

        if(!isset($REX['ADDON']['rexblog']['article']))
          $REX['ADDON']['rexblog']['article'] = array();

        self::$article_path = $REX['ADDON']['rexblog']['article'];
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

    foreach(self::$article_pathlist as $key => $value)
    {
      foreach($value['url'] as $url_key => $url_value)
      {
        if($url_value == self::$url)
        {
          self::$category_id = $value['categories'][$url_key];
          self::$article_id  = $value['id'];
          self::$is_article  = true;

          if(file_exists(self::$include_path . '/generated/files/_rex488_article.' . self::$article_id . '.inc.php'))
          {
            require self::$include_path . '/generated/files/_rex488_article.' . self::$article_id . '.inc.php';
            self::$article_path = $REX['ADDON']['rexblog']['article'];
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
    global $REX;

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

  public static function set_base()
  {
    self::set_is_category();
    self::set_is_article();
    self::set_is_alternate();

    rex_register_extension_point('REX488_SET_BASE', '', array('url' => self::$url, 'category_id' => self::$category_id, 'article_id' => self::$article_id), true);
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
      $parsed_resource = self::$article_base . '/' . $resource;
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