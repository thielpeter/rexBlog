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

abstract class _rex488_FrontendPagination extends _rex488_FrontendBase
{
  private static $url_prepend_part;
  private static $url_append_part;

  public static function get_pagination()
  {
    $pagination = '';
    
    self::$url_prepend_part = substr(parent::$url, 0, strrpos(parent::$url, '/'));
    self::$url_append_part  = substr(parent::$url, strrpos(parent::$url, '/'), strlen(parent::$url));

    if(parent::$the_page_current > 0)
    {
      $pagination .= '<li class="prev"><a href="' . self::prepare_prev_url() . '">' . _rex488_FrontendDesignator::prev_button() . '</a></li>' . "\n";
    }

    if(parent::$the_page_count > parent::$the_page_amount)
    {
      if((parent::$the_page_current + 1) < parent::$the_page_max)
      {
        $pagination .= '<li class="next"><a href="' . self::prepare_next_url() . '">' . _rex488_FrontendDesignator::next_button() . '</a></li>' . "\n";
      }
    }

    if(parent::$the_page_count > parent::$the_page_amount)
    {
      $pagination = '<ul id="_rex488_pagination">' . "\n" . $pagination . '</ul>' . "\n" . '<div class="clearfix"></div>' . "\n";
    }

    return $pagination;
  }

  private static function prepare_prev_url()
  {
    if(parent::$rewrite === false)
    {
      if(parent::$the_page_current > 1)
      {
        parent::$resource_params['page'] = parent::$the_page_current;
        return parent::parse_article_resource(parent::$url);
      } else
      {
        return parent::parse_article_resource(parent::$url);
      }
    } else
    {
      if(parent::$the_page_current > 1)
      {
        return parent::get_article_base() . '/' . self::$url_prepend_part . '/page/' . (parent::$the_page_current) . self::$url_append_part;
      } else
      {
        return parent::get_article_base() . '/' . parent::$url;
      }
    }
  }

  private static function prepare_next_url()
  {
    if(parent::$rewrite === false)
    {
      if(parent::$the_page_current >= 1)
      {
        parent::$resource_params['page'] = (parent::$the_page_current + 2);
        return parent::parse_article_resource(parent::$url);
      } else
      {
        parent::$resource_params['page'] = '2';
        return parent::parse_article_resource(parent::$url);
      }
    } else
    {
      if(parent::$the_page_current >= 1)
      {
        return parent::get_article_base() . '/' . self::$url_prepend_part . '/page/' . (parent::$the_page_current + 2) . self::$url_append_part;
      } else
      {
        return parent::get_article_base() . '/' . self::$url_prepend_part . '/page/2' . self::$url_append_part;
      }
    }
  }
}
?>