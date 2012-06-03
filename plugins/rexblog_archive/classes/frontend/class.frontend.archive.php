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

abstract class _rex670_FrontendArchive extends _rex488_FrontendBase
{
  public static $archive_cache_pathlist;

  private static $the_archive_cache;
  private static $the_archive_title;
  private static $the_archive_permlink;
  private static $the_archive_post;
  private static $the_archive_plugin_settings;

  /**
   * _rex670_the_archive
   *
   * generiert die archive navigation und
   * und setzt den extension point zur
   * alternativen ausgabe für die archive
   * artikel übersicht.
   *
   * @params
   * @return string $archive archive navigation
   * @throws
   */

public static function _rex670_the_archive()
{
  if(count(self::$archive_cache_pathlist) > 0) {

    foreach(self::$archive_cache_pathlist as $key => $value)
    {
      $the_archive_url          = parent::parse_article_resource($value['url']);
      $the_archive_date         = strftime('%B %Y', $value['archive_date']);
      $the_archive_class        = (rex_request('archive', 'string') == $key) ? ' class="current"' : '';

      $archive .= '<li><a' . $the_archive_class . ' href="' . $the_archive_url . '">' . $the_archive_date . '</a></li>';
    }

    return $archive;

  } else {

    return '<li><p>Es sind keine Archiveinträge vorhanden.</p></li>';

  }
}

  /**
   * the_archive_overview
   *
   * wertet die übergebenen archive variable aus
   * und bindet die daraus resultierenden artikel
   * cachefiles ein. anschließend wird die ausgabe
   * der artikelübersicht im archivmodus generiert.
   *
   * @params array $params array der vom extension point übergebenen variablen
   * @return
   * @throws
   */

public static function the_archive_overview($params)
{
  foreach(self::$archive_cache_pathlist as $key => $value)
  {
    if(rex_request('archive', 'string') == $key)
    {
      foreach($value['articles'] as $article)
      {
        if(file_exists(parent::$include_path . '/generated/files/_rex488_article.' . $article . '.inc.php'))
        {
          require parent::$include_path . '/generated/files/_rex488_article.' . $article . '.inc.php';
          self::$the_archive_cache = $REX['ADDON']['rexblog']['article'];
        }
      }

      parent::$the_page_amount  = $params['pagination'];
      parent::$the_page_current = rex_request('page', 'int');
      parent::$the_page_current = (parent::$the_page_current > 1) ? parent::$the_page_current - 1 : parent::$the_page_current;
      parent::$the_page_max     = ceil(count(self::$the_archive_cache) / parent::$the_page_amount);
      parent::$the_page_count   = count(self::$the_archive_cache);

      $paginated_article = array_chunk(self::$the_archive_cache, parent::$the_page_amount);

      foreach($paginated_article[parent::$the_page_current] as $key => $value)
      {
        self::$the_archive_title           = $value['title'];
        self::$the_archive_permlink        = parent::parse_article_resource($value['url'][0], null, true);
        self::$the_archive_post            = unserialize(stripslashes($value['article_post']));
        self::$the_archive_plugin_settings = unserialize(stripslashes($value['article_plugin_settings']));

        rex_register_extension_point('REX488_FRONTEND_ARTICLE_SHOW_BEFORE', '', array(
          'id' => $value['id'],
          'title' => self::$the_archive_title,
          'article_permlink' => self::$the_archive_permlink
        ));

        include _rex670_PATH . 'templates/frontend/template.archive.phtml';

        rex_register_extension_point('REX488_FRONTEND_ARTICLE_SHOW_AFTER', '', array(
          'id' => $value['id'],
          'title' => self::$the_archive_title,
          'article_permlink' => self::$the_archive_permlink
        ));
      }
    }
  }
}

  ///////////////////////////////////////////////////////////////////////////
  // frontend getters and setters

  public static function _rex670_the_archive_permlink()
  {
    return self::$the_archive_permlink;
  }

  public static function _rex670_the_archive_title()
  {
    return self::$the_archive_title;
  }

  /**
   * _rex670_the_archive_excerpt
   *
   * handhabt die excerpt ausgabe eines
   * archive artikels im frontend. sollte
   * kein plugin des artikels als excerpt
   * markiert sein, werden alle plugins
   * des artikels ausgegeben.
   *
   * @params
   * @return mixed ausgabe des excerpts oder des ganzen artikels
   * @throws
   */

  public static function _rex670_the_archive_excerpt()
  {
    $the_archive_content         = self::$the_archive_post;
    $the_archive_plugin_settings = self::$the_archive_plugin_settings;

    if(self::is_excerpt($the_archive_plugin_settings))
    {
      ob_start();

      foreach($the_archive_plugin_settings as $index => $the_plugin_settings)
      {
        if($the_plugin_settings['excerpt'] == 'on') {
          $the_plugin_content = $the_archive_content[$index];
            eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
              $the_excerpt_buffer = ob_get_contents();
        }
      }

      ob_end_clean();

      return $the_excerpt_buffer;
    } else
    {
      ob_start();

      foreach($the_archive_plugin_settings as $index => $the_plugin_settings) {
        $the_plugin_content = $the_archive_content[$index];
          eval("include _rex488_PATH . 'classes/plugins/templates/frontend/template." . $the_plugin_settings['type'] . ".phtml';");
            $the_archive_buffer = ob_get_contents();
      }

      ob_end_clean();

      return $the_archive_buffer;
    }
  }

  /**
   * is_excerpt
   *
   * prüft ob die übergebene einstellungs
   * variable excerpt eines artikels auf
   * aktiv gestellt wurde.
   *
   * @params array $the_archive_settings array der archive settings eines artikels.
   * @return boolean liefert true zurück wenn excerpt eingeschaltet ist.
   * @throws
   */

  public static function is_excerpt($the_archive_settings)
  {
    foreach($the_archive_settings as $excerpt_settings) {
      if(!$excerpt_settings['excerpt'] == 'on') {
        continue;
      } else {
        return true;
      }
    }
  }
}
?>
