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

abstract class _rex717_FrontendTags extends _rex488_FrontendBase
{
  public static $tags_cache_strength;
  public static $tags_cache_pathlist;

  private static $the_tags_article_id;
  private static $the_tags_cache;
  private static $the_tag_strength = array();
  private static $the_tags_title;
  private static $the_tags_permlink;
  private static $the_tags_post;
  private static $the_tags;
  private static $the_tags_tag_title;
  private static $the_tags_tag_permlink;
  private static $the_tags_plugin_settings;

  /**
   * _rex717_append_tags
   */

  public static function _rex717_append_tags($params)
  {
    self::$the_tags_article_id = $params['id'];
    
    if(!empty(self::$tags_cache_pathlist[self::$the_tags_article_id]))
      include _rex717_PATH . 'templates/frontend/template.article.tags.phtml';
  }

  /**
   * _rex717_the_article_tags
   */

  public static function _rex717_the_article_tags()
  {
    self::$the_tags = explode(' ', self::$tags_cache_pathlist[self::$the_tags_article_id]);

    foreach(self::$the_tags as $tag)
    {
      // declare the variables
      self::$the_tags_tag_title    = $tag;
      self::$the_tags_tag_permlink = parent::parse_article_resource('tags/' . $tag) . '/' . $tag . '.html';

      // include the template
      include _rex717_PATH . 'templates/frontend/template.article.tag.single.phtml';
    }
  }

  /**
   * _rex717_the_tagcloud
   *
   * @params
   * @return <string> $the_tagcloud
   * @throws
   */

  public static function _rex717_the_tagcloud()
  {
    $calculated_tags = array();

    if(count(self::$tags_cache_strength) > 0) {

      foreach(self::$tags_cache_strength as $key => $value)
      {
        $calculated_tags[$key] = $value['strength'];
      }

      self::_rex717_calculate_strength($calculated_tags);
      
      ksort(self::$the_tag_strength);

      foreach(self::$the_tag_strength as $tag => $strength)
      {
        $the_tags_url   = parent::parse_article_resource('tags/' . strtolower($tag)) . '/' . strtolower($tag) . '.html';
        $the_tags_class = $strength;

        $the_tagcloud .= '<li><a class="_rex717_tagstrength_' . $the_tags_class . '" href="' . $the_tags_url . '">' . strtolower($tag) . '</a></li>';
      }

      return $the_tagcloud;

    } else {

      return '<li><p>Es sind keine Schlagwörter vorhanden.</p></li>';
      
    }
  }

  /**
   * _rex717_calculate_strength
   *
   * @param <type> $tags
   */

  private function _rex717_calculate_strength($tags)
  {
    arsort($tags);

    $max_size = 5;
    $min_size = 1;

    $max_qty = max(array_values($tags));
    $min_qty = min(array_values($tags));

    $spread = ($max_qty - $min_qty);

    if ($spread == 0) {
      $spread = 1;
    }

    $step = ($max_size - $min_size) / ($spread);

    foreach ($tags as $key => $value)
    {
      $size = round($min_size + (($value - $min_qty) * $step));
      self::$the_tag_strength[$key] = $size;
    }
  }

  /**
   * the_tags_overview
   *
   * wertet die übergebenen tags variable aus
   * und bindet die daraus resultierenden artikel
   * cachefiles ein. anschließend wird die ausgabe
   * der artikelübersicht im tagsmodus generiert.
   *
   * @params array $params array der vom extension point übergebenen variablen
   * @return
   * @throws
   */

  public static function the_tags_overview($params)
  {
    foreach(self::$tags_cache_pathlist as $key => $value)
    {
      $search_tags = explode(' ', $value);

      if(in_array(rex_request('tag', 'string'), $search_tags))
      {
        if(file_exists(parent::$include_path . '/generated/files/_rex488_article.' . $key . '.inc.php'))
        {
          require parent::$include_path . '/generated/files/_rex488_article.' . $key . '.inc.php';
          self::$the_tags_cache = $REX['ADDON']['rexblog']['article'];
        }
      }
    }

    parent::$the_page_amount  = $params['pagination'];
    parent::$the_page_current = rex_request('page', 'int');
    parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
    parent::$the_page_max     = ceil(count(self::$the_tags_cache) / parent::$the_page_amount);
    parent::$the_page_count   = count(self::$the_tags_cache);

    $paginated_article = array_chunk(self::$the_tags_cache, parent::$the_page_amount);

    foreach($paginated_article[parent::$the_page_current] as $key => $value)
    {
      self::$the_tags_title           = $value['title'];
      self::$the_tags_permlink        = parent::parse_article_resource($value['url'][0], null, true);
      self::$the_tags_post            = unserialize(stripslashes($value['article_post']));
      self::$the_tags_plugin_settings = unserialize(stripslashes($value['article_plugin_settings']));

      rex_register_extension_point('REX488_FRONTEND_ARTICLE_SHOW_BEFORE', '', array(
              'id' => $value['id'],
              'title' => self::$the_tags_title,
              'article_permlink' => self::$the_tags_permlink
      ));

      include _rex717_PATH . 'templates/frontend/template.article.phtml';

      rex_register_extension_point('REX488_FRONTEND_ARTICLE_SHOW_AFTER', '', array(
              'id' => $value['id'],
              'title' => self::$the_tags_title,
              'article_permlink' => self::$the_tags_permlink
      ));
    }
  }

  ///////////////////////////////////////////////////////////////////////////
  // frontend getters and setters

  public static function _rex717_the_tags_permlink()
  {
    return self::$the_tags_permlink;
  }

  public static function _rex717_the_tags_title()
  {
    return self::$the_tags_title;
  }

  public static function _rex717_the_tags_tag_permlink()
  {
    return self::$the_tags_tag_permlink;
  }

  public static function _rex717_the_tags_tag_title()
  {
    return self::$the_tags_tag_title;
  }

  /**
   * _rex717_the_tags_excerpt
   *
   * handhabt die excerpt ausgabe eines
   * tags artikels im frontend. sollte
   * kein plugin des artikels als excerpt
   * markiert sein, werden alle plugins
   * des artikels ausgegeben.
   *
   * @params
   * @return mixed ausgabe des excerpts oder des ganzen artikels
   * @throws
   */

  public static function _rex717_the_tags_excerpt()
  {
    $the_tags_content         = self::$the_tags_post;
    $the_tags_plugin_settings = self::$the_tags_plugin_settings;

    if(self::is_excerpt($the_tags_plugin_settings))
    {
      ob_start();

      foreach($the_tags_plugin_settings as $index => $the_plugin_settings)
      {
        if($the_plugin_settings['excerpt'] == 'on')
        {
          $the_plugin_content = $the_tags_content[$index];
          eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
          $the_excerpt_buffer = ob_get_contents();
        }
      }

      ob_end_clean();

      return $the_excerpt_buffer;
    } else
    {
      ob_start();

      foreach($the_tags_plugin_settings as $index => $the_plugin_settings)
      {
        $the_plugin_content = $the_tags_content[$index];
        eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
        $the_tags_buffer = ob_get_contents();
      }

      ob_end_clean();

      return $the_tags_buffer;
    }
  }

  /**
   * is_excerpt
   *
   * prüft ob die übergebene einstellungs
   * variable excerpt eines artikels auf
   * aktiv gestellt wurde.
   *
   * @params array $the_tags_settings array der tags settings eines artikels.
   * @return boolean liefert true zurück wenn excerpt eingeschaltet ist.
   * @throws
   */

  public static function is_excerpt($the_tags_settings)
  {
    foreach($the_tags_settings as $excerpt_settings)
    {
      if(!$excerpt_settings['excerpt'] == 'on')
      {
        continue;
      } else
      {
        return true;
      }
    }
  }
}
?>