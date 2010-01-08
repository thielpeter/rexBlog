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

abstract class _rex488_FrontendPost extends _rex488_FrontendBase
{
  private static $the_title;
  private static $the_url;
  private static $the_post;
  private static $the_post_date;
  private static $the_excerpt;
  private static $the_pathlist;

  public static function get_post_overview($the_page_amount)
  {
    self::$the_pathlist = parent::$post_path;

    parent::$the_page_amount  = $the_page_amount;
    parent::$the_page_current = rex_request('page', 'int');
    parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
    parent::$the_page_max     = ceil(count(parent::$post_path) / parent::$the_page_amount);

    $paginated_post = array_chunk(self::$the_pathlist, parent::$the_page_amount);

    if(is_array($paginated_post) && count($paginated_post) > 0)
    {
      foreach($paginated_post[parent::$the_page_current] as $key => $value)
      {
        foreach($value['categories'] as $category_key => $category_value)
        {
          if($category_value == parent::$category_id)
          {
            self::$the_title      = $value['title'];
            self::$the_url        = self::prepare_url($value['url'][$category_key]);
            self::$the_post       = $value['post'];
            self::$the_excerpt    = $value['excerpt'];
            self::$the_post_date  = $value['date'];

            include _rex488_PATH . 'templates/frontend/template.post.phtml';
          }
        }
      }
    } else
    {
      return '<p>' . _rex488_FrontendDesignator::category_no_post() . '</p>';
    }
  }

  public static function get_post_details()
  {
    foreach(parent::$post_path as $key => $value)
    {
      if($value['id'] == parent::$post_id)
      {
        foreach($value['categories'] as $category_key => $category_value) {
          if($category_value == parent::$category_id) $current_category_id = $category_key;
        }

        self::$the_title      = $value['title'];
        self::$the_url        = self::prepare_url($value['url'][$current_category_id]);
        self::$the_post       = $value['post'];
        self::$the_post_date  = $value['date'];

        include _rex488_PATH . 'templates/frontend/template.post.phtml';
      }
    }
  }

  public static function _rex488_the_title()
  {
    return self::$the_title;
  }

  public static function _rex488_the_url()
  {
    return self::$the_url;
  }

  public static function _rex488_the_post()
  {
    return self::$the_post;
  }

  public static function _rex488_the_post_date($date_format = 'd.m.Y')
  {
    $the_date = strtotime(self::$the_post_date);
    $the_date = date($date_format, $the_date);

    return $the_date;
  }

  public static function _rex488_the_excerpt($type, $length, $clean)
  {
    $the_excerpt_post = ($type == 'excerpt') ? self::$the_excerpt : self::$the_post;

    if(empty($the_excerpt_post))
      $the_excerpt_post = self::$the_post;

    if($clean == 'all') {
      $the_excerpt_post = strip_tags($the_excerpt_post);
    } else if(!empty($clean)) {
      $the_excerpt_post = strip_tags($the_excerpt_post, $clean);
    }

    if($length == 'full' || $length == 0)
      return $the_excerpt_post;

    return self::prepare_excerpt($the_excerpt_post, $length);
  }

  private static function prepare_excerpt($string, $length)
  {
    $explode  = explode(' ', $string);
    $string   = '';
    $dots     = '...';

    if(count($explode) <= $length) {
      $dots = '';
    }

    for($i = 0; $i < $length; $i++) {
      $string .= $explode[$i] . " ";
    }

    if($dots) {
      $string = substr($string, 0, strlen($string));
    }

    return $string . $dots;
  }

  private static function prepare_url($url)
  {
    if(!parent::$rewrite) {
      return rex_getUrl(rex_request('article_id', 'int')) . '&_rex488_uri=' . $url;
    } else {
      return parent::get_article_base() . '/' . $url;
    }
  }
}

?>