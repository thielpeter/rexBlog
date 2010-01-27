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
  private static $the_article_title;
  private static $the_article_permlink;
  private static $the_article_post;
  private static $the_article_date;
  private static $the_article_user;
  private static $the_article_pathlist;
  private static $the_article_settings;

  public static function get_article_overview($the_page_amount)
  {
    self::$the_article_pathlist = parent::$article_path;

    parent::$the_page_amount  = $the_page_amount;
    parent::$the_page_current = rex_request('page', 'int');
    parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
    parent::$the_page_max     = ceil(count(parent::$article_path) / parent::$the_page_amount);

    $paginated_article = array_chunk(self::$the_article_pathlist, parent::$the_page_amount);

    if(is_array($paginated_article) && count($paginated_article) > 0)
    {
      foreach($paginated_article[parent::$the_page_current] as $key => $value)
      {
        foreach($value['categories'] as $category_key => $category_value)
        {
          if($category_value == parent::$category_id)
          {
            self::$the_article_title    = $value['title'];
            self::$the_article_date     = $value['create_date'];
            self::$the_article_user     = $value['create_user'];
            self::$the_article_post     = unserialize(stripslashes($value['article_post']));
            self::$the_article_settings = unserialize(stripslashes($value['article_settings']));
            self::$the_article_permlink = self::prepare_url($value['url'][$category_key]);

            include _rex488_PATH . 'templates/frontend/template.article.phtml';
          }
        }
      }
    } else
    {
      return '<p>' . _rex488_FrontendDesignator::category_no_article() . '</p>';
    }
  }

  public static function get_article_details()
  {
    foreach(parent::$article_path as $key => $value)
    {
      if($value['id'] == parent::$article_id)
      {
        foreach($value['categories'] as $category_key => $category_value)
        {
          if($category_value == parent::$category_id) $current_category_id = $category_key;
        }

        self::$the_article_title    = $value['title'];
        self::$the_article_date     = $value['create_date'];
        self::$the_article_user     = $value['create_user'];
        self::$the_article_post     = unserialize(stripslashes($value['article_post']));
        self::$the_article_settings = unserialize(stripslashes($value['article_settings']));
        self::$the_article_permlink = self::prepare_url($value['url'][$current_category_id]);

        include _rex488_PATH . 'templates/frontend/template.article.phtml';
      }
    }
  }

  public static function _rex488_the_article_title()
  {
    return self::$the_article_title;
  }

  public static function _rex488_the_article_permlink()
  {
    return self::$the_article_permlink;
  }

  public static function _rex488_the_article_post()
  {
    $the_article_content  = self::$the_article_post;
    $the_article_settings = self::$the_article_settings;

    ob_start();

    foreach($the_article_settings as $index => $the_plugin_settings)
    {
      $the_plugin_content = $the_article_content[$index];
        eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
          $the_article_buffer = ob_get_contents();
    }

    ob_end_clean();

    return $the_article_buffer;
  }

  public static function _rex488_the_article_date($date_format = 'd.m.Y')
  {
    $the_article_date = date($date_format, self::$the_article_date);

    return $the_article_date;
  }

  public static function _rex488_the_article_user()
  {
    return self::$the_article_user;
  }

  public static function _rex488_the_article_excerpt()
  {
    $the_article_content  = self::$the_article_post;
    $the_article_settings = self::$the_article_settings;

    if(self::is_excerpt($the_article_settings))
    {
      ob_start();

      foreach($the_article_settings as $index => $the_plugin_settings) {
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

      foreach($the_article_settings as $index => $the_plugin_settings) {
        $the_plugin_content = $the_article_content[$index];
          eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
            $the_article_buffer = ob_get_contents();
      }

      ob_end_clean();

      return $the_article_buffer;
    }
  }

  public static function is_excerpt($the_article_settings)
  {
    foreach($the_article_settings as $excerpt_settings) {
      if(!$excerpt_settings['excerpt'] == 'on') {
        continue;
      } else {
        return true;
      }
    }
  }

  private static function prepare_url($url)
  {
    if(!parent::$rewrite)
    {
      return rex_getUrl(rex_request('article_id', 'int')) . '&_rex488_uri=' . $url;
    } else
    {
      return parent::get_article_base() . '/' . $url;
    }
  }
}

?>