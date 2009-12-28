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

class _rex488_BackendBase
{
  private static $instance = null;
  
  protected static $parent_category_id = 0;
  protected static $sql;
  protected static $prefix = 'rex_';

  /*
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

  /*
   * __construct
   *
   * geschützte konstruktor funktion
   *
   */

  private function  __construct()
  {
    global $REX;

    self::$sql = rex_sql::getInstance();
    self::$sql->debugsql = 1;
    self::$prefix = $REX['TABLE_PREFIX'];
    self::$parent_category_id = rex_request('parent', 'int');
  }

  /*
   * __clone
   *
   * geschützte klone funktion
   *
   */

  private function  __clone() {}
}

?>
