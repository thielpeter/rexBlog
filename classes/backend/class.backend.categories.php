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

class _rex488_BackendCategories extends _rex488_BackendBase implements _rex488_BackendCategoryInterface
{
  /*
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
	    parent::$parent_category_id)
    );

    return $categories;
  }

  /*
   * add
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

  /*
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

}

?>