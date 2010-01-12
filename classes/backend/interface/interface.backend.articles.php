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

interface _rex488_BackendArticleInterface
{
  /*
   * write
   *
   * @param
   * @return
   * @throws
   */

  public static function read();

  /*
   * sort
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function write();

  /*
   * visualize
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function visualize();

  /*
   * delete
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function delete();

  }

?>
