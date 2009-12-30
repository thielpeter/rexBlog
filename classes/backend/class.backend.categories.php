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

abstract class _rex488_BackendCategories extends _rex488_BackendBase implements _rex488_BackendCategoryInterface
{
  // constant

  const SUBPAGE = 'categories';

  //protected

  protected static $entry_id = 0;

  /**
   * read
   *
   * liefert auf basis von parent die
   * unterkategorien der aktuellen kategorie zurück
   *
   * @param
   * @return array $categories array der kategorien
   * @throws
   */

  public static function read()
  {
    $categories = parent::$sql->getArray(
      sprintf("
      SELECT * FROM %s
      LEFT JOIN %s
      ON ( %s = %s )
      WHERE ( %s = '%d' )
      ORDER BY priority ASC",
      parent::$prefix . "488_rexblog_categories_id",
      parent::$prefix . "488_rexblog_categories",
      parent::$prefix . "488_rexblog_categories.cid",
      parent::$prefix . "488_rexblog_categories_id.id",
      parent::$prefix . "488_rexblog_categories_id.parent",
      parent::$parent_id)
    );

    return $categories;
  }

  /**
   * write
   *
   * liefert die kategorien der aktuellen parent zurück
   *
   * @param
   * @return array $categories array der aktuellen kategorien
   * @throws
   *
   */

  public static function write($mode = 'update')
  {

  }

  /**
   * sort
   *
   * sortiert die kategorien nach ihrer priorität
   *
   * @param array $priorities array der sortierreihenfolge
   * @return
   * @throws
   *
   */

  public static function sort($priorities)
  {

  }

  /**
   * state
   *
   * setzt den status der kategorie
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function state()
  {
    self::$entry_id = rex_request('id', 'int');

    $state = parent::$sql->setQuery(sprintf(" SELECT * FROM %s WHERE ( %s = '%d' )", parent::$prefix . "488_rexblog_categories", "cid", self::$entry_id));
    $state = parent::$sql->getValue('status');
    $state = ($state == 1) ? 0 : 1;

    parent::$sql->table = parent::$prefix . '488_rexblog_categories';
    parent::$sql->setValue('status', $state);
    parent::$sql->wherevar = "WHERE ( cid = '" . self::$entry_id . "' )";

    if(parent::$sql->update() === true)
    {
      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&notify=success');
    }
  }
}

?>