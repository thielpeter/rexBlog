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

class _rex488_BackendBase
{
  // const

  const PAGE = 'rexblog';

  // private

  private static $instance = null;

  //protected

  protected static $entry_id = 0;
  protected static $parent_id = 0;
  protected static $user = '';
  protected static $sql;
  protected static $prefix = 'rex_';
  protected static $subpage = '';
  protected static $include_path = '';

  /**
   * get_instance
   *
   * instantiiert die basisklasse mit hilfe der
   * singleton methode und liefert diese zurück
   *
   * @param
   * @return object self::$instance das klassenobjekt
   * @throws
   */

  public static function get_instance()
  {
    if (self::$instance === NULL)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * __construct
   *
   * geschützte konstruktor funktion
   *
   */

  private function  __construct()
  {
    global $REX;

    self::$sql            = rex_sql::getInstance();
    self::$sql->debugsql  = 0;
    self::$prefix         = $REX['TABLE_PREFIX'];
    self::$user           = $REX['USER']->getValue('name');
    self::$include_path   = $REX['INCLUDE_PATH'];
    self::$entry_id       = rex_request('id', 'int');
    self::$parent_id      = rex_request('parent', 'int');
    self::$subpage        = rex_request('subpage', 'string');
  }

  /**
   * __clone
   *
   * geschützte clone funktion
   *
   */

  private function  __clone() {}

}
?>
