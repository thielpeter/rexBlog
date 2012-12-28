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

abstract class _rex670_BackendArchive extends _rex488_BackendBase
{
  /**
   * write_archive_pathlist
   */

  public static function write_archive_pathlist()
  {
    $query = sprintf("SELECT %s FROM %s WHERE %s GROUP BY %s ORDER BY %s",
             "create_date, DATE_FORMAT( FROM_UNIXTIME( create_date ), '%Y' ) AS year, DATE_FORMAT( FROM_UNIXTIME( create_date ), '%m' ) AS month",
             parent::$prefix . "488_articles",
             "( status = '1')",
             "year, month",
             "year DESC, month DESC");

    $archive = parent::$sql->getArray($query);

    ///////////////////////////////////////////////////////////////////////////
    // create cache file header

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['archive']['pathlist'] = array (";

    ///////////////////////////////////////////////////////////////////////////
    // loop through archive dates

    foreach($archive as $key => $value)
    {
      $content .= $value['year'] . $value['month'] . " => array (";
      $content .= "'archive_date' => '" . $value['create_date'] . "',";
      $content .= "'articles' => array(";

      ///////////////////////////////////////////////////////////////////////////
      // get all articles for the current archive date

      $articles = parent::$sql->getArray("SELECT id
                                          FROM " . parent::$prefix . "488_articles
                                          WHERE ( DATE_FORMAT( FROM_UNIXTIME( create_date ), '%Y%m' ) = '" . $value['year'] . $value['month'] . "' )
                                          ORDER BY create_date DESC");

      ///////////////////////////////////////////////////////////////////////////
      // loop through archive article ids

      foreach($articles as $key2 => $value2) {
         $content .= $key2 . " => '" . $value2['id'] . "',";
      }

      ///////////////////////////////////////////////////////////////////////////
      // close categories and add date and url

      $content .= "),";
      $content .= "'date' => '" . mktime(0, 0, 0, date('m', $value['create_date']), 0, date('Y', $value['create_date'])) . "', ";
      $content .= "'url' => 'archive/" . $value['year'] . "-" . $value['month'] . "/archive.html'";
      $content .= "),\n";
    }

    ///////////////////////////////////////////////////////////////////////////
    // create cache file footer

    $content .= ")\n";
    $content .= "\n?>";

    ///////////////////////////////////////////////////////////////////////////
    // assign path for cache file

    $file = parent::$include_path . '/generated/files/_rex488_archive.pathlist.inc.php';

    ///////////////////////////////////////////////////////////////////////////
    // write cache file

    rex_put_file_contents($file, $content);
  }
}
?>