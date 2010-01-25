<?php
/**
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
 */

abstract class _rex488_BackendArticles extends _rex488_BackendBase implements _rex488_BackendArticleInterface
{
  // constant

  const SUBPAGE = 'articles';

  //protected

  protected static $mode = 'insert';
  protected static $entry_id = 0;
  protected static $next = 0;
  protected static $article_content = '';

  /**
   * write
   *
   * liest artikel aus der datenbank aus. als parameter
   * kann eine bestimmte id eines artikels angegeben
   * werden so dass nur dieser ausgelesen wird.
   *
   * @param int $id id eines bestimmten artikels
   * @return array mixed single or multiarray of articles
   * @throws
   */

  public static function read($id = null)
  {
    if(isset($id))
    {
      parent::$entry_id = $id;
      
      $result = parent::$sql->getArray(
        sprintf("SELECT * FROM %s WHERE ( %s = '%d' )",
          parent::$prefix . "488_articles", "id", parent::$entry_id
        )
      );

      foreach($result as $key => $value) {
        foreach($value as $k => $v) {
          $article[$k] = $v;
        }
      }

      return $article;

    } else {

      self::$next = rex_request('next', 'int');

      $articles = parent::$sql->getArray(
        sprintf("SELECT * FROM %s ORDER BY %s ASC LIMIT %d, 10",
          parent::$prefix . '488_articles', 'create_date', self::$next
        )
      );

      return $articles;
    }
  }

  /**
   * write
   *
   * schreibt oder aktualisiert einen artikel
   * der datenbank. die funktion entscheidet
   * anhand des übergebenen mode parameters.
   *
   * @param
   * @return
   * @throws
   */

  public static function write()
  {
    self::$mode = rex_request('mode', 'string');

    if(isset($_REQUEST['cancel'])) {
      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&info=5');
        exit();
    }

    $article_content = rex_request('_rex488_element', 'array');

    $content_index = 1;

    foreach($article_content as $key => $value) {
      foreach($value as $index => $content) {
        $content = str_replace("'", "\"", $content);
          $prepared_article[$content_index] = array($index => stripslashes($content));
            $content_index++;
      }
    }

    $article_settings = rex_request('_rex488_settings', 'array');

    $settings_index = 1;

    foreach($article_settings as $key => $value) {
      foreach($value as $k2 => $v2) {
        $prepared_settings[$settings_index][$k2] = stripslashes($v2);
      }
      $settings_index++;
    }

    $article_content  = serialize($prepared_article);
    $article_settings = serialize($prepared_settings);

    $permanent_link   = rex_request('_rex488_permanent_link', 'string') == "" ? rex_request('title', 'string') : rex_request('_rex488_permanent_link', 'string');
    $permanent_link   = strtolower(rex_parse_article_name($permanent_link));

    if(self::$mode == 'insert')
    {

      parent::$sql->table = parent::$prefix . '488_articles';
      parent::$sql->setValue('title', rex_request('title', 'string'));
      parent::$sql->setValue('categories', '1,5');
      parent::$sql->setValue('keywords' ,rex_request('_rex488_metadata_keywords', 'string'));
      parent::$sql->setValue('description' ,rex_request('_rex488_metadata_description', 'string'));
      parent::$sql->setValue('article_post', mysql_real_escape_string($article_content));
      parent::$sql->setValue('article_permlink', $permanent_link);
      parent::$sql->setValue('article_settings', $article_settings);
      parent::$sql->setValue('status', 0);
      parent::$sql->setValue('create_user', parent::$user);
      parent::$sql->setValue('create_date', time());

      /**
       * if the insert was successfull, we want
       * to connect an extension point to it.
       *
       * @param
       * @return
       * @throws
       */

      if(parent::$sql->insert())
      {
        $article = rex_register_extension_point('REX488_ART_ADDED', parent::$sql, array(
          'title'         => rex_request('title', 'string'),
          'article_post'  => mysql_real_escape_string($article_content)
        ));

        header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=1');
          exit();
      }
    }
      // update mode

      else if(self::$mode == 'update')
    {
      parent::$sql->table = parent::$prefix . '488_articles';
      parent::$sql->setValue('title', rex_request('title', 'string'));
      parent::$sql->setValue('keywords' ,rex_request('_rex488_metadata_keywords', 'string'));
      parent::$sql->setValue('description' ,rex_request('_rex488_metadata_description', 'string'));
      parent::$sql->setValue('article_post', mysql_real_escape_string($article_content));
      parent::$sql->setValue('article_permlink', $permanent_link);
      parent::$sql->setValue('article_settings', $article_settings);
      parent::$sql->setValue('update_user', parent::$user);
      parent::$sql->setValue('update_date', time());
      parent::$sql->wherevar = "WHERE ( id = '" . parent::$entry_id . "' )";

      /**
       * if the update was successfull, we want
       * to connect an extension point to it.
       *
       * @param
       * @return
       * @throws
       */

      if(parent::$sql->update())
      {
        $category = rex_register_extension_point('REX488_ART_UPDATED', parent::$sql, array(
          'id'            => parent::$entry_id,
          'title'         => rex_request('title', 'string'),
          'article_post'  => mysql_real_escape_string($article_content)
        ));

        // redirect to proper page

        if(isset($_REQUEST['update'])) {
          header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&func=edit&id=' . parent::$entry_id . '&parent=' . parent::$parent_id  . '&info=2');
        } else {
          header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=2');
        }

        exit();
      }
    }
  }

  /**
   * delete
   *
   * löscht einen artikel anhand der übergebenen id.
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function delete()
  {
    parent::$sql->table = parent::$prefix . '488_articles';
    parent::$sql->wherevar = "WHERE ( id = '" . parent::$entry_id . "' ) ";

    if(parent::$sql->delete())
    {
      $article = rex_register_extension_point('REX488_ART_DELETED', parent::$sql, array(
        'id' => parent::$entry_id
      ));

      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&info=3');
        exit();
    }
  }

  /**
   * visualize
   *
   * setzt den status eines artikels
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function visualize()
  {
    $visualization = parent::$sql->setQuery(
      sprintf("SELECT * FROM %s WHERE ( %s = '%d' )",
        parent::$prefix . "488_articles", "id", parent::$entry_id
      )
    );

    $visualization = parent::$sql->getValue('status');
    $visualization = ($visualization == 1) ? 0 : 1;

    parent::$sql->table = parent::$prefix . '488_articles';
    parent::$sql->setValue('status', $visualization);
    parent::$sql->wherevar = "WHERE ( id = '" . parent::$entry_id . "' )";

    if(parent::$sql->update())
    {
      $category = rex_register_extension_point('REX488_ART_STATUS', parent::$sql, array(
        'id'      => parent::$entry_id,
        'status'  => $visualization
      ));

      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&info=4');
        exit();
    }
  }

  /**
   * load
   *
   * load article content from database
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function load($id)
  {
    parent::$sql->setQuery("SELECT article_post, article_settings FROM " . parent::$prefix . "488_articles WHERE ( id = '" . $id . "' )");
    
    $article_post     = parent::$sql->getValue('article_post');
    $article_settings = parent::$sql->getValue('article_settings');
    $article_post     = unserialize($article_post);
    $article_settings = unserialize($article_settings);

    if(!is_array($article_post)) return;

    foreach($article_post as $key => $value) {
      foreach($value as $plugin => $content) {
        eval("echo _rex488_content_plugin_" . $plugin . "::getElement(" . $key . ", '" . $content . "', \$article_settings[\$key]);");
      }
    }
  }
}
?>