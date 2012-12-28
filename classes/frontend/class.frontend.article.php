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

abstract class _rex488_FrontendArticle extends _rex488_FrontendBase
{
  ///////////////////////////////////////////////////////////////////////////
  // declare internal variables

  private static $the_article_index;
  private static $the_article_title;
  private static $the_article_permlink;
  private static $the_article_post;
  private static $the_article_tags;
  private static $the_article_tags_title;
  private static $the_article_tags_permlink;
  private static $the_article_date;
  private static $the_article_user;
  private static $the_article_categories;
  private static $the_article_category_id;
  private static $the_article_category_title;
  private static $the_article_category_permlink;
  private static $the_article_cache;
  private static $the_article_meta_settings;
  private static $the_article_plugin_settings;

  /**
   * the_overview_content
   *
   * generates the article overview for
   * the current category.
   *
   * @param <int> $the_page_amount
   * @return <mixed>
   */

  public static function the_overview_content($the_page_amount)
  {
    ///////////////////////////////////////////////////////////////////////////
    // set internal article cache from base

    self::$the_article_cache = parent::$article_cache;

    ///////////////////////////////////////////////////////////////////////////
    // define pagination variables

    parent::$the_page_amount  = $the_page_amount;
    parent::$the_page_current = rex_request('page', 'int');
    parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
    parent::$the_page_max     = ceil(count(parent::$article_cache) / parent::$the_page_amount);
    parent::$the_page_count   = count(parent::$article_cache);

    ///////////////////////////////////////////////////////////////////////////
    // split all corresponding articles by the page amount

    $paginated_article = array_chunk(self::$the_article_cache, parent::$the_page_amount);

    ///////////////////////////////////////////////////////////////////////////
    // if we have articles for this category, loop through affected

    if(is_array($paginated_article) && count($paginated_article) > 0)
    {
      foreach($paginated_article[parent::$the_page_current] as $key => $value)
      {
        foreach($value['categories'] as $category_key => $category_value)
        {
          if($category_value == parent::$category_id)
          {
            self::$the_article_title           = $value['title'];
            self::$the_article_date            = $value['create_date'];
            self::$the_article_user            = $value['create_user'];
            self::$the_article_categories      = $value['categories'];
            self::$the_article_post            = unserialize(stripslashes($value['article_post']));
            self::$the_article_tags            = $value['article_tags'];
            self::$the_article_meta_settings   = unserialize(stripslashes($value['article_meta_settings']));
            self::$the_article_plugin_settings = unserialize(stripslashes($value['article_plugin_settings']));
            self::$the_article_permlink        = parent::parse_article_resource($value['url'][$category_key]);

            include _rex488_PATH . 'templates/frontend/template.article.phtml';
          }
        }
      }

    ///////////////////////////////////////////////////////////////////////////
    // else tell the frontend user this category has no articles

    } else
    {
      return '<p>' . _rex488_FrontendDesignator::category_no_article() . '</p>';
    }
  }

  /**
   * the_detail_content
   *
   * prepare detailed output for
   * the requested article
   *
   */

  public static function the_detail_content()
  {
    foreach(parent::$article_cache as $key => $value)
    {
      if($value['id'] == parent::$article_id)
      {
        foreach($value['categories'] as $category_key => $category_value)
        {
          if($category_value == parent::$category_id) $current_category_id = $category_key;
        }

        self::$the_article_title           = $value['title'];
        self::$the_article_date            = $value['create_date'];
        self::$the_article_user            = $value['create_user'];
        self::$the_article_categories      = $value['categories'];
        self::$the_article_post            = unserialize(stripslashes($value['article_post']));
        self::$the_article_tags            = $value['article_tags'];
        self::$the_article_meta_settings   = unserialize(stripslashes($value['article_meta_settings']));
        self::$the_article_plugin_settings = unserialize(stripslashes($value['article_plugin_settings']));
        self::$the_article_permlink        = parent::parse_article_resource($value['url'][$current_category_id]);

        $article_details = rex_register_extension_point('REX488_FRONTEND_ART_DETAILS', '', array(
          'id' => $value['id'],
            'title' => self::$the_article_title,
              'article_permlink' => self::$the_article_permlink
        ));

        include _rex488_PATH . 'templates/frontend/template.article.phtml';
      }
    }
  }

  /**
   * the_article_index
   *
   * the article index is only shown
   * on the root page of the blog,
   * and is also injected by the
   * frontend_vendor extension
   * point.
   *
   * @param <array> $params array of params supplied by the frontend_vendor extension point
   */

  public static function the_article_index($params)
  {
    ///////////////////////////////////////////////////////////////////////////
    // create empty array in case no articles are created after setup

    self::$the_article_index = array();

    ///////////////////////////////////////////////////////////////////////////
    // loop through article cache pathlist to get index articles

    foreach(parent::$article_cache_pathlist as $k => $v)
    {
      if(file_exists(parent::$include_path . '/generated/files/_rex488_article.' . $k . '.inc.php')) {
        include parent::$include_path . '/generated/files/_rex488_article.' . $k . '.inc.php';
          self::$the_article_index = $REX['ADDON']['rexblog']['article'];
      }
    }

    ///////////////////////////////////////////////////////////////////////////
    // sort index articles descending

    arsort(self::$the_article_index);

    ///////////////////////////////////////////////////////////////////////////
    // define pagination variables

    parent::$the_page_amount  = $params['pagination'];
    parent::$the_page_current = rex_request('page', 'int');
    parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
    parent::$the_page_max     = ceil(count(self::$the_article_index) / parent::$the_page_amount);
    parent::$the_page_count   = count(self::$the_article_index);

    ///////////////////////////////////////////////////////////////////////////
    // split all corresponding articles by the page amount

    $paginated_article = array_chunk(self::$the_article_index, parent::$the_page_amount);

    ///////////////////////////////////////////////////////////////////////////
    // if we have articles for the index view, loop through affected

    if(is_array($paginated_article) && count($paginated_article) > 0)
    {
      foreach($paginated_article[parent::$the_page_current] as $key => $value)
      {
        self::$the_article_title           = $value['title'];
        self::$the_article_date            = $value['create_date'];
        self::$the_article_user            = $value['create_user'];
        self::$the_article_categories      = $value['categories'];
        self::$the_article_post            = unserialize(stripslashes($value['article_post']));
        self::$the_article_tags            = $value['article_tags'];
        self::$the_article_meta_settings   = unserialize(stripslashes($value['article_meta_settings']));
        self::$the_article_plugin_settings = unserialize(stripslashes($value['article_plugin_settings']));
        self::$the_article_permlink        = parent::parse_article_resource($value['url'][0], null, true);

        include _rex488_PATH . 'templates/frontend/template.article.phtml';
      }

    ///////////////////////////////////////////////////////////////////////////
    // else tell the frontend user this category has no articles

    } else
    {
      return '<p>' . _rex488_FrontendDesignator::category_no_article() . '</p>';
    }
  }

  /**
   * _rex488_the_article_title
   *
   * @param
   * @return <string> $the_article_title
   * @throws
   */

  public static function _rex488_the_article_title()
  {
    return self::$the_article_title;
  }

  /**
   * _rex488_the_article_permlink
   *
   * @param
   * @return <string> $the_article_permlink
   * @throws
   */

  public static function _rex488_the_article_permlink()
  {
    return self::$the_article_permlink;
  }

  /**
   * _rex488_the_article_settings
   *
   * @param
   * @return <string> $the_article_meta_settings
   * @throws
   */

  public static function _rex488_the_article_settings()
  {
    return self::$the_article_meta_settings;
  }

  /**
   * _rex488_the_article_date
   *
   * @param <string> $date_format
   * @return <string> $the_article_date
   * @throws
   */

  public static function _rex488_the_article_date($date_format = 'd.m.Y')
  {
    $the_article_date = date($date_format, self::$the_article_date);

    return $the_article_date;
  }

  /**
   * _rex488_the_article_user
   *
   * @param
   * @return <string> $the_article_user
   * @throws
   */

  public static function _rex488_the_article_user()
  {
    return self::$the_article_user;
  }

  /**
   * _rex488_the_article_category_title
   *
   * @return <type>
   */

  public static function _rex488_the_article_category_title()
  {
    return self::$the_article_category_title;
  }

  /**
   * _rex488_the_article_category_permlink
   *
   * @return <type>
   */

  public static function _rex488_the_article_category_permlink()
  {
    return self::$the_article_category_permlink;
  }

  /**
   * _rex488_the_article_categories
   *
   * @return <type>
   */

  public static function _rex488_the_article_categories()
  {
    foreach(self::$the_article_categories as $category_id)
    {
      // declare the variables
      self::$the_article_category_id       = $category_id;
      self::$the_article_category_title    = _rex488_FrontendCategories::get_category_title_by_id($category_id);
      self::$the_article_category_permlink = parent::parse_article_resource(_rex488_FrontendCategories::get_category_permlink_by_id($category_id));
      
      // include the template
      include _rex488_PATH . 'templates/frontend/template.article.categories.phtml';
    }
  }

  /**
   * _rex488_the_article_tags_title
   *
   * @return <type>
   */

  public static function _rex488_the_article_tags_title()
  {
    return self::$the_article_tags_title;
  }

  /**
   * _rex488_the_article_tags_permlink
   *
   * @return <type>
   */

  public static function _rex488_the_article_tags_permlink()
  {
    return self::$the_article_tags_permlink;
  }

  /**
   * _rex488_the_article_tags
   *
   * @return <type>
   */

  public static function _rex488_the_article_tags()
  {
    self::$the_article_tags = explode(' ', self::$the_article_tags);

    foreach(self::$the_article_tags as $tag)
    {
      // declare the variables
      self::$the_article_tags_title    = $tag;
      self::$the_article_tags_permlink = parent::parse_article_resource('tags/' . $tag) . '/' . $tag . '.html';

      // include the template
      include _rex488_PATH . 'templates/frontend/template.article.tags.phtml';
    }
  }

  /**
   * _rex488_the_article_excerpt
   *
   * @param
   * @return <string> $the_article_buffer
   * @throws
   */

 public static function _rex488_the_article_excerpt()
 {
    $the_article_content         = self::$the_article_post;
    $the_article_plugin_settings = self::$the_article_plugin_settings;

    if(self::_rex488_is_excerpt($the_article_plugin_settings))
    {
      ob_start();

      foreach($the_article_plugin_settings as $index => $the_plugin_settings) {
        if($the_plugin_settings['excerpt'] == 'on') {
          $the_plugin_content = $the_article_content[$index];
            eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
              $the_excerpt_buffer = ob_get_contents();
        }
      }

      ob_end_clean();

      return $the_excerpt_buffer;
    } else
    {
      ob_start();

      foreach($the_article_plugin_settings as $index => $the_plugin_settings) {
        $the_plugin_content = $the_article_content[$index];
          eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
            $the_article_buffer = ob_get_contents();
      }

      ob_end_clean();

      return $the_article_buffer;
    }
  }

   /**
   * _rex488_the_article_post
   *
   * @param
   * @return <string> $the_article_buffer
   * @throws
   */

 public static function _rex488_the_article_post()
  {
    $the_article_content         = self::$the_article_post;
    $the_article_plugin_settings = self::$the_article_plugin_settings;

    ob_start();

    foreach($the_article_plugin_settings as $index => $the_plugin_settings)
    {
      $the_plugin_content = $the_article_content[$index];
        eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
          $the_article_buffer = ob_get_contents();
    }

    ob_end_clean();

    return $the_article_buffer;
  }

 /**
   * _rex488_is_excerpt
   *
   * @param
   * @return <boolean>
   * @throws
   */

  public static function _rex488_is_excerpt($the_article_settings)
  {
    foreach($the_article_settings as $excerpt_settings) {
      if(!$excerpt_settings['excerpt'] == 'on') {
        continue;
      } else {
        return true;
      }
    }
  }
}
?>