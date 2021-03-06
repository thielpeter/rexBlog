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

abstract class _rex717_BackendTags extends _rex488_BackendBase
{
  private static $the_article_tags = array();

  /**
   * write_tags_strength
   */

  public static function _rex717_write_tags_strength()
  {
    $query = sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s",
             "id, article_tags",
             parent::$prefix . "488_articles",
             "( status = '1')",
             "id DESC");

    $tags = parent::$sql->getArray($query);

    ///////////////////////////////////////////////////////////////////////////
    // create cache file header

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['tags']['strength'] = array (";

    ///////////////////////////////////////////////////////////////////////////
    // loop through tags

    foreach($tags as $key => $value)
    {
      $article_tags   = explode(' ', $value['article_tags']);
      $empty_elements = array_keys($article_tags, "");

      foreach ($empty_elements as $e)
        unset($article_tags[$e]);

      foreach($article_tags as $tag)
        array_push(self::$the_article_tags, $tag);
    }

    ///////////////////////////////////////////////////////////////////////////
    // calculate tags strength

    $strength = array_count_values(self::$the_article_tags);

    ///////////////////////////////////////////////////////////////////////////
    // remove duplicate tags

    self::$the_article_tags = array_unique(self::$the_article_tags);

    ///////////////////////////////////////////////////////////////////////////
    // loop through tags

    foreach(self::$the_article_tags as $key => $value)
    {
      $articles = parent::$sql->getArray(sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s",
                  "id",
                  parent::$prefix . "488_articles",
                  "( status = '1' AND article_tags LIKE '%" . $value . "%' )",
                  "id DESC"));

      $content .= "'" . $value . "'" . " => array (";
      $content .= "'strength' => " . $strength[$value] . ",";
      $content .= "'articles' => array(";

      foreach($articles as $article_id)
      {
        $content .= $article_id['id'] . ",";
      }

      $content .= ")";
      $content .= "),\n";
    }

    $content .= ")\n";
    $content .= "\n?>";

    ///////////////////////////////////////////////////////////////////////////
    // assign path for cache file

    $file = parent::$include_path . '/generated/files/_rex717_tags.strength.inc.php';

    ///////////////////////////////////////////////////////////////////////////
    // write cache file

    rex_put_file_contents($file, $content);
  }

  /**
   * _rex717_write_tags_pathlist
   */

  public static function _rex717_write_tags_pathlist()
  {
    $query = sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s",
             "id, article_tags",
             parent::$prefix . "488_articles",
             "( status = '1')",
             "id DESC");

    $tags = parent::$sql->getArray($query);

    ///////////////////////////////////////////////////////////////////////////
    // create cache file header

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['tags']['pathlist'] = array (";

    foreach($tags as $key => $value)
    {
      $content .= $value['id'] . " => '" . $value['article_tags'] . "',";
    }

    $content .= ");\n";
    $content .= "\n?>";

    ///////////////////////////////////////////////////////////////////////////
    // assign path for cache file

    $file = parent::$include_path . '/generated/files/_rex717_tags.pathlist.inc.php';

    ///////////////////////////////////////////////////////////////////////////
    // write cache file

    rex_put_file_contents($file, $content);
  }

  /**
   * _rex717_write_tab_content
   *
   * @param <type> $params
   */

  public static function _rex717_write_tab_content($params)
  {
    $article_id = $params['id'];
    $tags       = strtolower(rex_request('rex717_meta_tags', 'string'));

    parent::$sql->setQuery(
      sprintf("UPDATE %s SET %s WHERE ( %s )",
        parent::$prefix . "488_articles",
        "article_tags = '" . $tags . "'",
        "id = '" . $article_id . "'"
      )
    );
  }

  /**
   * _rex717_read_tab_content
   *
   * @return <type>
   */

  public static function _rex717_read_tab_content()
  {
    if((boolean) parent::$entry_id  !== false) {
      parent::$sql->setQuery(
        sprintf("SELECT %s FROM %s WHERE ( %s )",
          "article_tags",
          parent::$prefix . "488_articles",
          "id = '" . parent::$entry_id . "'"
        )
      );

      return parent::$sql->getValue('article_tags');
    }
  }
}
?>