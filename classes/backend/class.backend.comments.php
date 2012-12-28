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

abstract class _rex488_BackendComments extends _rex488_BackendBase
{
  // constant

  const SUBPAGE = 'comments';

  //protected

  protected static $entry_id = 0;

  /**
   * read
   *
   * liest kommentare aus der datenbank aus
   *
   * @param int $id id eines bestimmten artikels
   * @return array mixed single or multiarray of articles
   * @throws
   */

  public static function read($limit = 2, $next = 0)
  {
    // handle comment filter

    if(isset($_REQUEST['filter']) && !empty($_REQUEST['filter']))
    {
      switch($_REQUEST['filter'])
      {
        case 'open':
          $filter = "WHERE ( rating = '0' )";
          break;
        case 'approved':
          $filter = "WHERE ( rating = '1' )";
          break;
        case 'denied':
          $filter = "WHERE ( rating = '2' )";
          break;
        case 'all':
          $filter = "";
          break;
      }
    } else
    {
      $filter = "WHERE ( rating = '0' )";
    }

    // handle comment type

    if(isset($_REQUEST['type']) && !empty($_REQUEST['type']))
    {
      if(strpos($filter, 'WHERE') !== false) {
        $type_prefix = 'AND';
      } else {
        $type_prefix = 'WHERE';
      }

      switch($_REQUEST['type'])
      {
        case 'comments':
          $type = $type_prefix . " ( comment_type = 'comment' )";
          break;
        case 'trackbacks':
          $type = $type_prefix . " ( comment_type = 'trackback' )";
          break;
        case 'all':
          $type = "";
          break;
      }
    }

    // handle comment search

    if(isset($_REQUEST['search']) && isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword']))
    {
      $filter = "";
      $type   = "";
      $search = "WHERE ( comment_comment LIKE '%" . $_REQUEST['keyword'] . "%' ) ";
    }

    $offset = (($next * $limit) - $limit);
    $offset = ($offset < 0) ? 0 : $offset;

    $comments = parent::$sql->getArray(
      sprintf("SELECT * FROM %s %s %s %s ORDER BY %s DESC LIMIT " . $offset . ", " . $limit,
        parent::$prefix . '488_comments', $filter, $type, $search, 'create_date, id'
      )
    );

    return $comments;
  }

  /**
   * visualize
   *
   * setzt den status eines kommentars
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function visualize($id)
  {
    $visualization = parent::$sql->setQuery(
            sprintf("SELECT * FROM %s WHERE ( %s = '%d' )",
            parent::$prefix . "488_comments", "id", $id
            )
    );

    $visualization = parent::$sql->getValue('status');
    $visualization = ($visualization == 1) ? 0 : 1;

    parent::$sql->table = parent::$prefix . '488_comments';
    parent::$sql->setValue('status', $visualization);
    parent::$sql->wherevar = "WHERE ( id = '" . $id . "' )";

    if(parent::$sql->update())
    {
      $comment = rex_register_extension_point('REX488_COMMENT_STATUS', parent::$sql, array(
              'id'      => $id,
              'status'  => $visualization
      ));
    }
  }

  /**
   * delete
   *
   * löscht einen kommentar anhand der übergebenen id.
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function delete($id)
  {
    parent::$sql->table = parent::$prefix . '488_comments';
    parent::$sql->wherevar = "WHERE ( id = '" . $id . "' ) ";

    if(parent::$sql->delete())
    {
      $comment = rex_register_extension_point('REX488_COMMENT_DELETED', parent::$sql, array(
        'id' => $id
      ));
    }
  }

  /**
   * _rex488_comment_count
   * 
   * @param <type> $filter
   * @return <type>
   */

  public function _rex488_comment_count($filter = '', $type = '')
  {
    switch($filter)
    {
      case 'open':
        $filter = "WHERE ( rating = '0' )";
        break;
      case 'approved':
        $filter = "WHERE ( rating = '1' )";
        break;
      case 'denied':
        $filter = "WHERE ( rating = '2' )";
        break;
      case 'all':
        $filter = "";
        break;
      default:
        $filter = "WHERE ( rating = '0' )";
        break;
    }

    if(strpos($filter, 'WHERE') !== false) {
      $type_prefix = 'AND';
    } else {
      $type_prefix = 'WHERE';
    }

    switch($type)
    {
      case 'comments':
        $type = $type_prefix . " ( comment_type = 'comment' )";
        break;
      case 'trackbacks':
        $type = $type_prefix . " ( comment_type = 'trackback' )";
        break;
      default:
        $type = "";
        break;
    }

    $comments = parent::$sql->setQuery(
      sprintf("SELECT count(id) AS total FROM %s %s %s ORDER BY %s DESC",
        parent::$prefix . '488_comments', $filter, $type, 'create_date'
      )
    );

    return parent::$sql->getValue('total');
  }

  /**
   * _rex488_article_title_by_id
   * 
   * @param <type> $id
   * @return <type>
   */

  public static function _rex488_article_title_by_id($id)
  {
    $title = parent::$sql->setQuery(
      sprintf("SELECT title FROM %s %s",
        parent::$prefix . "488_articles", "WHERE ( id = '" . $id . "' )"
      )
    );

    return parent::$sql->getValue('title');
  }

  /**
   * _rex488_article_id_by_id
   *
   * @param <type> $id
   * @return <type>
   */

  public static function _rex488_article_id_by_id($id)
  {
    $title = parent::$sql->setQuery(
      sprintf("SELECT id FROM %s %s",
        parent::$prefix . "488_articles", "WHERE ( id = '" . $id . "' )"
      )
    );

    return parent::$sql->getValue('id');
  }
}
?>