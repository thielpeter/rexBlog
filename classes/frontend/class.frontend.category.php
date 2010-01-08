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

abstract class _rex488_FrontendCategory extends _rex488_FrontendCategories
{
  /**
   * format_category
   *
   * formatiert die übergebenen kategoriedaten als linkelement.
   *
   * @param array $value enthält alle eigenschaften der kategorie
   * @param string $selector current oder parent css selektor der kategorie
   * @param boolean $show_post_count anzahl an beiträgen der kategorie anzeigen
   * @return string $category formatierte kategorie als linkelement
   * @throws
   */

  public static function format_category($value = array(), $selector = "", $show_post_count = false)
  {
    $category  .= '<a' . $selector . ' href="' . self::prepare_url($value['url']) . '">';
    $category  .= $value['title'];
    $category  .= '</a>';

    if($show_post_count === true) {
      $category .=  '<span> (' . _rex488_FrontendCategories::get_category_post_count($value['id']) . ')</span>';
    }

    return $category;
  }

  /**
   * prepare_url
   *
   * bereitet die übergebenen daten für die ausgabe vor.
   *
   * @param string $unprepared_url zeichenkette der url ohne mod_rewrite formatierung
   * @return string $article_name mod_rewrite formatierter artikelname
   * @throws
   */

  private static function prepare_url($unprepared_url)
  {
    if(!parent::$rewrite) {
      $prepared_url = rex_getUrl(rex_request('article_id', 'int')) . '&_rex488_uri=' . $unprepared_url;
    } else {
      $prepared_url = parent::get_article_base() . '/' . $unprepared_url;
    }

    return $prepared_url;
  }
}

?>