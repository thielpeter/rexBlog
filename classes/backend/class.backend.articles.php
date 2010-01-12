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

  public static function write()
  {
    self::$mode = rex_request('mode', 'string');

    if(isset($_REQUEST['cancel'])) {
      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&info=5');
        exit();
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
    parent::$sql->table = parent::$prefix . '488_article';
    parent::$sql->wherevar = "WHERE ( id = '" . parent::$entry_id . "' ) ";

    if(parent::$sql->delete())
    {
      $article = rex_register_extension_point('REX488_ART_DELETED', parent::$sql, array(
        'id'          => parent::$entry_id
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
        'id'          => parent::$entry_id,
        'status'      => $visualization
      ));

      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&info=4');
        exit();
    }
  }
}
?>
