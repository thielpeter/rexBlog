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

abstract class _rex488_BackendCommentsObserver extends _rex488_BackendBase
{
  private static $config_storage;
  private static $config_db;
  private static $observer;

  /**
   * _rex488_observer_config
   *
   * @global <type> $REX
   */

  public static function _rex488_observer_config()
  {
    global $REX;

    self::$config_storage = array(
      'storage' => 'mysql'
    );

    self::$config_db = array(
      'database'   => $REX['DB']['1']['NAME'],
      'table_name' => parent::$prefix . '488_observer',
      'host'       => $REX['DB']['1']['HOST'],
      'user'       => $REX['DB']['1']['LOGIN'],
      'pass'       => $REX['DB']['1']['PSW']
    );

    self::$observer = new b8(self::$config_storage, self::$config_db);
  }

  /**
   * _rex488_observer_learn
   *
   * @param <type> $text
   * @param <type> $action
   */

  public static function _rex488_observer_learn($id, $action)
  {
    parent::$sql->setQuery("SELECT comment_comment FROM " . parent::$prefix . "488_comments WHERE ( id = '" . $id . "' )");

    $comment_comment = parent::$sql->getValue('comment_comment');

    self::$observer->learn($comment_comment, $action);

    if($action == 'spam') {
      $rating = '2';
      $status = '0';
    } else if($action == 'ham') {
      $rating = '1';
      $status = '1';
    }

    parent::$sql->setQuery("UPDATE " . parent::$prefix . "488_comments SET rating = '" . $rating . "', status = '" . $status . "' WHERE ( id = '" . $id . "' )");
 }

  /**
   * _rex488_observer_unlearn
   *
   * @param <type> $text
   * @param <type> $action
   */

  public static function _rex488_observer_unlearn($id, $action)
  {
    parent::$sql->setQuery("SELECT comment_comment FROM " . parent::$prefix . "488_comments WHERE ( id = '" . $id . "' )");

    $comment_comment = parent::$sql->getValue('comment_comment');

    self::$observer->unlearn($comment_comment, $action);

    parent::$sql->setQuery("UPDATE " . parent::$prefix . "488_comments SET rating = '0' WHERE ( id = '" . $id . "' )");
 }
  
  /**
   * _rex488_observer_classify
   * 
   * @param <type> $text 
   */
  
  public static function _rex488_observer_classify($text)
  {
    return self::$observer->classify($text);
  }

  /**
   * _rex488_observer_format_rating
   *
   * @param <type> $rating
   * @return <type>
   */

  public static function _rex488_observer_format_rating($rating)
  {
    if($rating === FALSE)
      return "<span style=\"color:red\">could not calculate spaminess</span>";

    $red   = floor(255 * $rating);
    $green = floor(255 * (1 - $rating));

    return "<span style=\"color:rgb($red, $green, 0);\"><b>" . sprintf('%5f', $rating) . "</b></span>";
  }

}

?>