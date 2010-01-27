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
    $content .= "\$REX['ADDON']['rexblog']['categories'] = array (";

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

      if ($category_parent_id > 0) {
        self::get_parents($category_parent_id);
      }

      ///////////////////////////////////////////////////////////////////////////
      // reverse category array for proper sorting

      self::$category_url = array_reverse(self::$category_url);

      ///////////////////////////////////////////////////////////////////////////
      // loop through categories to generate proper url

      foreach(self::$category_url as $k => $v) {
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

    rex_put_file_contents($REX['INCLUDE_PATH'] . '/generated/files/_rex488_categories.inc.php', $content);
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
    if(file_exists(parent::$include_path . '/generated/files/_rex488_categories.inc.php')) {
      require parent::$include_path . '/generated/files/_rex488_categories.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['categories'];
    }
    
    $categories = self::$sql->getArray("SELECT id FROM " . self::$prefix . "488_categories WHERE ( status = '1' ) ");

    // loop through all categories and create corresponding postfiles

    foreach($categories as $key => $category)
    {
      // fetch posts corresponding to their categorie id

      $post_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_articles WHERE ( FIND_IN_SET(" . $category['id'] . ", REPLACE(categories, ',', ',')) AND status = '1' )");

      // create header for cache file

      $content = "<?php\n\n";
      $content .= "\$REX['ADDON']['rexblog']['article'][" . $category['id'] . "] = array (\n";

      // loop through post_array

      foreach($post_array as $post_key => $post_value)
      {
	// preassign post variables

	$post_id	  = $post_value['id'];
        $post_categories  = explode(',', addslashes($post_value['categories']));
	$post_title	  = addslashes($post_value['title']);
	$post_keywords    = addslashes($post_value['keywords']);
	$post_description = addslashes($post_value['description']);
	$post_article	  = addslashes($post_value['article_post']);
	$post_settings	  = addslashes($post_value['article_settings']);
	$post_permlink	  = addslashes($post_value['article_permlink']);
	$create_date	  = addslashes($post_value['create_date']);
	$create_user	  = addslashes($post_value['create_user']);

	// create post content for file

	$content .= $post_id . " => ";
	$content .= "array (\n";
	$content .= "	'id' => " . $post_id . ",\n";

	// create post categories content for file

	$content .= "	'categories' => array(\n";

	foreach($post_categories as $post_category_key => $post_category_value)
	{
	  $content .= "		" . $post_category_key . " => " . $post_category_value . ",\n";
	}

	// create post content for file

	$content .= "	),\n";
	$content .= "	'title' => '" . $post_title . "',\n";
	$content .= "	'keywords' => '" . $post_keywords . "',\n";
	$content .= "	'description' => '" . $post_description . "',\n";
	$content .= "	'article_post' => '" . $post_article . "',\n";
	$content .= "	'article_settings' => '" . $post_settings . "',\n";
	$content .= "	'article_permlink' => '" . $post_permlink . "',\n";
	$content .= "	'create_date' => '" . $create_date . "',\n";
	$content .= "	'create_user' => '" . $create_user . "',\n";

	// create post url content for file

	$content .= "	'url' => array(\n";

	foreach($post_categories as $post_category_url_key => $post_category_url_value)
	{
	  $post_category_url = self::$category_path[$post_category_url_value]['url'];
	  $post_category_url = substr($post_category_url, 0, strrpos($post_category_url, '/') + 1);
	  $post_category_url = $post_category_url . self::prepare_url($post_permlink) . '.html';

	  $content .= "		" . $post_category_url_key . " => '" . $post_category_url . "',\n";
	}

	$content .= ")),\n";
      }

      // create footer for cache file

      $content .= ")\n";
      $content .= "\n?>";

      // assign path for cache file

      $file = parent::$include_path . '/generated/files/_rex488_article.' . $category['id'] . '.inc.php';

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
    if(file_exists(parent::$include_path . '/generated/files/_rex488_categories.inc.php')) {
      require parent::$include_path . '/generated/files/_rex488_categories.inc.php';
        self::$category_path = $REX['ADDON']['rexblog']['categories'];
    }

      // fetch posts corresponding to their categorie id

      $post_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_articles WHERE ( status = '1' ) ORDER BY id ASC");

      // create header for cache file

      $content = "<?php\n\n";
      $content .= "\$REX['ADDON']['rexblog']['article']['pathlist'] = array (\n";

      // loop through post_array

      foreach($post_array as $post_key => $post_value)
      {
	// preassign post variables

	$post_id	  = $post_value['id'];
        $post_categories  = explode(',', addslashes($post_value['categories']));
	$post_title	  = addslashes($post_value['title']);
	$post_permlink	  = $post_value['article_permlink'];

	// create post content for file

	$content .= $post_id . " => ";
	$content .= "array (\n";
	$content .= "	'id' => " . $post_id . ",\n";

	// create post categories content for file

	$content .= "	'categories' => array(\n";

	foreach($post_categories as $post_category_key => $post_category_value)
	{
	  $content .= "		" . $post_category_key . " => " . $post_category_value . ",\n";
	}

	// create post content for file

	$content .= "	),\n";

	// create post url content for file

	$content .= "	'url' => array(\n";

	foreach($post_categories as $post_category_url_key => $post_category_url_value)
	{
	  $post_category_url = self::$category_path[$post_category_url_value]['url'];
	  $post_category_url = substr($post_category_url, 0, strrpos($post_category_url, '/') + 1);
	  $post_category_url = $post_category_url . self::prepare_url($post_permlink) . '.html';

	  $content .= "		" . $post_category_url_key . " => '" . $post_category_url . "',\n";
	}

	$content .= ")),\n";
      }

      // create footer for cache file

      $content .= ")\n";
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