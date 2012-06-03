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

abstract class _rex721_BackendSocial extends _rex488_BackendBase
{
  private static $vendors          = array();
  private static $vendors_selected = array();

  /**
   * _rex721_read_social_vendors
   * 
   * @return <type> $vendors
   */

  public static function _rex721_read_social_vendors()
  {
    $vendor_path = dir(_rex721_PATH . 'classes/vendors');

    // read vendor directory

    while(false !== ($vendor = $vendor_path->read()))
    {
      // continue on not wanted directories

      if($vendor == '.' || $vendor == '..') continue;

      // else include the vendor

      include_once _rex721_PATH . 'classes/vendors/' . $vendor;

      // allocate vendor ids

      preg_match("/\bvendor\.(.*)\.php\b/i", $vendor, $vendor_id);

      // call backend vendor

      self::$vendors[$vendor_id[1]] = $vendor_id[1];
    }

    // close vendor directory

    $vendor_path->close();

    // get selected vendors

    self::$vendors_selected = self::_rex721_read_tab_content();

    // prepare vendors

    foreach(self::$vendors as $key => $value) {
      $selected = (in_array($value, self::$vendors_selected)) ? 'selected="selected"' : '';
        $vendor_ouput .= call_user_func(array('_rex721_vendor_' . $value, '_rex721_vendor_backend'), $selected) . "\n";
    }

    // return vendors

    return $vendor_ouput;
  }

  /**
   * _rex721_write_tab_content
   * 
   * @param <type> $params
   */

  public static function _rex721_write_tab_content($params)
  {
    // define vars

    $article_id   = $params['id'];
    $social_links = rex_request('rex721_meta_social', 'array');
    $social_links = implode(';', $social_links);

    // execute database query

    parent::$sql->setQuery(
      sprintf("UPDATE %s SET %s WHERE ( %s )",
        parent::$prefix . "488_articles",
        "article_social_links = '" . $social_links . "'",
        "id = '" . $article_id . "'"
      )
    );
  }

  /**
   * _rex721_write_tab_content
   *
   * @param <type> $params
   */

  public static function _rex721_read_tab_content()
  {
    // only retrieve values on existing article

    if((boolean) parent::$entry_id  !== false) {
      parent::$sql->setQuery(
        sprintf("SELECT %s FROM %s WHERE ( %s )",
          "article_social_links",
          parent::$prefix . "488_articles",
          "id = '" . parent::$entry_id . "'"
        )
      );

      // build social links array

      $social_links = explode(';', parent::$sql->getValue('article_social_links'));

      // return social links array
      
      return $social_links;
    }
  }

  /**
   * _rex721_write_social_links_pathlist
   *
   * @param <type> $params
   */

  public static function _rex721_write_social_links_pathlist($params)
  {
    $query = sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s",
             "id, article_social_links",
             parent::$prefix . "488_articles",
             "( status = '1')",
             "id DESC");

    $social_links = parent::$sql->getArray($query);

    ///////////////////////////////////////////////////////////////////////////
    // create cache file header

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['social_links']['pathlist'] = array (\n";

    foreach($social_links as $key => $value)
    {
      $social_article_links = explode(';', $value['article_social_links']);
      $social_article_links = array_filter($social_article_links);

      if(!empty($social_article_links))
      {
        $content .= $value['id'] . " => array(\n";

        foreach($social_article_links as $vendor)
        {
          include_once _rex721_PATH . 'classes/vendors/vendor.' . $vendor . '.php';

          $vendor_key = $vendor;
          $vendor_url = call_user_func(array('_rex721_vendor_' . $vendor, '_rex721_vendor_frontend')) . "\n";

          $content .= "'" . $vendor_key . "' => '" . $vendor_url . "',\n";
        }

        $content .= "),\n";
      }
    }

    $content .= ");\n";
    $content .= "\n?>";
    
    ///////////////////////////////////////////////////////////////////////////
    // assign path for cache file

    $file = parent::$include_path . '/generated/files/_rex721_social.pathlist.inc.php';

    ///////////////////////////////////////////////////////////////////////////
    // write cache file

    rex_put_file_contents($file, $content);
  }

  /**
   * _rex721_delete_social_link_cache
   *
   * @param <type> $params
   */

  public static function _rex721_delete_social_link_cache($params)
  {
    $cache_file = _rex721_PATH . 'cache/_rex721_' . $params['id'] . '.social';

    if(file_exists($cache_file))
    {
      unlink($cache_file);
    }
  }
}