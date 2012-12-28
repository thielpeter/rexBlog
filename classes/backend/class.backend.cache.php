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

abstract class _rex488_BackendCache extends _rex488_BackendBase
{
  private static $category_url;
  private static $category_path;

  /**
   * write_category_cache
   *
   * function to write category cache
   *
   * @param
   * @return
   * @throws
   */

  public static function write_category_cache()
  {
    $result = parent::$sql->getArray("SELECT * FROM " . parent::$prefix . "488_categories WHERE ( status = '1' ) ORDER BY priority ASC");

    ///////////////////////////////////////////////////////////////////////////
    // prepare cache file header for categories

    $content = "<?php ";
    $content .= "\$REX['ADDON']['rexblog']['category'] = array (";

    foreach ($result as $key => $value)
    {
      global $REX;

      ///////////////////////////////////////////////////////////////////////////
      // unset category_url variables

      self::$category_url = "";
      $category_url = "";

      ///////////////////////////////////////////////////////////////////////////
      // assign category variables

      $category_id                = $value['id'];
      $category_category_id       = $value['category_id'];
      $category_parent_id         = $value['parent_id'];
      $category_title             = $value['title'];
      $category_keywords          = $value['keywords'];
      $category_description       = $value['description'];
      $category_priority          = $value['priority'];
      $category_status            = $value['status'];

      ///////////////////////////////////////////////////////////////////////////
      // assign category url value

      self::$category_url[$category_id] = $value['title'];

      ///////////////////////////////////////////////////////////////////////////
      // assign category url value

      if ($category_parent_id > 0)
      {
        self::get_parents($category_parent_id);
      }

      ///////////////////////////////////////////////////////////////////////////
      // reverse category array for proper sorting

      self::$category_url = array_reverse(self::$category_url);

      ///////////////////////////////////////////////////////////////////////////
      // loop through categories to generate proper url

      foreach(self::$category_url as $k => $v)
      {
        $v = self::prepare_url($v);
        $category_url .= $v . '/';
      }

      ///////////////////////////////////////////////////////////////////////////
      // append the category title to the end

      $category_url .= self::prepare_url($category_title) . '.html';

      ///////////////////////////////////////////////////////////////////////////
      // query database for potential childrens

      parent::$sql->setQuery("SELECT * FROM " . parent::$prefix . "488_categories WHERE ( parent_id = " . $category_id . " AND status = '1' )");

      ///////////////////////////////////////////////////////////////////////////
      // if the category has children assign var

      $category_children = (self::$sql->getRows() > 0) ? 1 : 0;

      ///////////////////////////////////////////////////////////////////////////
      // generate proper cachefile content

      $content .= $category_id . " => ";
      $content .= "array (";
      $content .= "'id' => " . $category_id . ", ";
      $content .= "'category_id' => " . $category_category_id . ", ";

      $articles = self::$sql->getArray("SELECT id FROM " . self::$prefix . "488_articles WHERE ( FIND_IN_SET(" . $category_id . ", REPLACE(categories, ',', ',')) AND status = '1' ) ORDER BY create_date DESC");

      $content .= "'articles' => array(";

      foreach($articles as $key => $article)
      {
        $content .= $key . " => " . $article['id'] . ", ";
      }

      $content .= "),";

      $content .= "'parent_id' => " . $category_parent_id . ", ";
      $content .= "'children' => " . $category_children . ", ";
      $content .= "'title' => '" . $category_title . "', ";
      $content .= "'keywords' => '" . $category_keywords . "', ";
      $content .= "'description' => '" . $category_description . "', ";
      $content .= "'priority' => '" . $category_priority . "', ";
      $content .= "'status' => '" . $category_status . "', ";
      $content .= "'url' => '" . $category_url . "'";
      $content .= "), ";
    }

    ///////////////////////////////////////////////////////////////////////////
    // add cache file footer

    $content .= "); ?>";

    ///////////////////////////////////////////////////////////////////////////
    // write the cache file into redaxos generated folder

    rex_put_file_contents($REX['INCLUDE_PATH'] . '/generated/files/_rex488_category.inc.php', $content);
  }

  /**
   * write_article_cache
   *
   * function to write article cache
   *
   * @param
   * @return
   * @throws
   */

  public static function write_article_cache()
  {
    if(file_exists(parent::$include_path . '/generated/files/_rex488_category.inc.php')) {
      include parent::$include_path . '/generated/files/_rex488_category.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['category'];
    }

    // fetch posts corresponding to their categorie id

    $article_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_articles WHERE ( status = '1' )");

    // loop through post_array

    foreach($article_array as $article_key => $article_value)
    {
      // create header for cache file

      $content = "<?php\n\n";
      $content .= "\$REX['ADDON']['rexblog']['article'][".$article_value['id']."] = array (";

      // preassign post variables

      $article_id                 = $article_value['id'];
      $article_categories         = explode(',', addslashes($article_value['categories']));
      $article_title              = addslashes($article_value['title']);
      $article_keywords           = addslashes($article_value['keywords']);
      $article_description        = addslashes($article_value['description']);
      $article_post               = addslashes($article_value['article_post']);
      $article_tags               = addslashes($article_value['article_tags']);
      $article_meta_settings      = addslashes($article_value['article_meta_settings']);
      $article_plugin_settings    = addslashes($article_value['article_plugin_settings']);
      $article_permlink           = addslashes($article_value['article_permlink']);
      $create_date                = addslashes($article_value['create_date']);
      $create_user                = addslashes($article_value['create_user']);

      // create post content for file

      $content .= "'id' => " . $article_id . ",";

      // create post categories content for file

      $content .= "'categories' => array(";

      foreach($article_categories as $article_category_key => $article_category_value)
      {
        $content .= $article_category_key . " => " . $article_category_value . ",";
      }

      // create post content for file

      $content .= "),";
      $content .= "'title' => '" . $article_title . "',";
      $content .= "'keywords' => '" . $article_keywords . "',";
      $content .= "'description' => '" . $article_description . "',";
      $content .= "'article_post' => '" . $article_post . "',";
      $content .= "'article_tags' => '" . $article_tags . "',";
      $content .= "'article_meta_settings' => '" . $article_meta_settings . "',";
      $content .= "'article_plugin_settings' => '" . $article_plugin_settings . "',";
      $content .= "'article_permlink' => '" . $article_permlink . "',";
      $content .= "'create_date' => '" . $create_date . "',";
      $content .= "'create_user' => '" . $create_user . "',";

      // create post url content for file

      $content .= "'url' => array(";

      foreach($article_categories as $article_category_url_key => $article_category_url_value)
      {
        $article_category_url = self::$category_path[$article_category_url_value]['url'];
        $article_category_url = substr($article_category_url, 0, strrpos($article_category_url, '/') + 1);
        $article_category_url = $article_category_url . self::prepare_url($article_permlink) . '.html';

        $content .= $article_category_url_key . " => '" . $article_category_url . "',";
      }

      $content .= "))";

      // create footer for cache file

      $content .= "\n?>";

      // assign path for cache file

      $file = parent::$include_path . '/generated/files/_rex488_article.' . $article_id . '.inc.php';

      // write cache file

      rex_put_file_contents($file, $content);
    }

  }

  /**
   * write_article_pathlist
   *
   * function to write article cache
   *
   * @param
   * @return
   * @throws
   */

  public static function write_article_pathlist()
  {
    if(file_exists(parent::$include_path . '/generated/files/_rex488_category.inc.php'))
    {
      include parent::$include_path . '/generated/files/_rex488_category.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['category'];
    }

    // fetch posts corresponding to their categorie id

    $article_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_articles WHERE ( status = '1' ) ORDER BY id ASC");

    // create header for cache file

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['pathlist']['article'] = array (";

    // loop through post_array

    foreach($article_array as $article_key => $article_value)
    {
      // preassign post variables

      $article_id         = $article_value['id'];
      $article_categories = explode(',', addslashes($article_value['categories']));
      $article_title      = addslashes($article_value['title']);
      $article_permlink   = $article_value['article_permlink'];

      // create post content for file

      $content .= $article_id . " => ";
      $content .= "array (";
      $content .= "'id' => " . $article_id . ",";

      // create post categories content for file

      $content .= "'categories' => array(";

      foreach($article_categories as $article_category_key => $article_category_value)
      {
        $content .= $article_category_key . " => " . $article_category_value . ",";
      }

      // create post content for file

      $content .= "	),";

      // create post url content for file

      $content .= "'url' => array(";

      foreach($article_categories as $article_category_url_key => $article_category_url_value)
      {
        $article_category_url = self::$category_path[$article_category_url_value]['url'];
        $article_category_url = substr($article_category_url, 0, strrpos($article_category_url, '/') + 1);
        $article_category_url = $article_category_url . self::prepare_url($article_permlink) . '.html';

        $content .= $article_category_url_key . " => '" . $article_category_url . "',";
      }

      $content .= ")),";
    }

    // create footer for cache file

    $content .= ")";
    $content .= "\n?>";

    // assign path for cache file

    $file = parent::$include_path . '/generated/files/_rex488_article.pathlist.inc.php';

    // write cache file

    rex_put_file_contents($file, $content);
  }

  /**
   * prepare_url
   *
   * formatiert die übergebene variable als url
   *
   * @param string $url string to format
   * @return string $url formated string
   * @throws
   */

  private static function prepare_url($url)
  {
    $url = rex_parse_article_name($url);
    $url = strtolower($url);

    return $url;
  }

  /**
   * get_parents
   *
   * erweitert das url array um die elternkategorien
   *
   * @param int $parent id for the parent category
   * @return
   * @throws
   */

  private static function get_parents($parent)
  {
    $result = parent::$sql->getArray("
      SELECT * FROM " . parent::$prefix . "488_categories
      WHERE category_id = " . $parent . "
      AND status = '1'
      ORDER BY priority ASC
    ");

    self::$category_url[$result[0]['id']] = $result[0]['title'];

    if ($result[0]['parent_id'] > 0)
      self::get_parents($result[0]['parent_id']);
  }
}
?>