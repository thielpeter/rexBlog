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

interface _rex488_BackendCategoryInterface
{
  /*
   * write
   *
   * fügt eine kategorie in die datenbank ein
   * oder aktualisiert eine vorhandene
   *
   * @param string $mode modus für update oder insert
   * @return boolean $success liefert bei erfolg true zurück
   * @throws
   */

  public static function write($mode = 'update');

  /*
   * read
   *
   * liefert die unterkategorien der aktuellen kategorie zurück
   *
   * @param
   * @return array $categories array der kategorien
   * @throws
   */

  public static function read();

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

  public static function sort($priorities);

  /*
   * state
   *
   * setzt den status der kategorie
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function state();

  }

?>
