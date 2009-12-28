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

class _rex488_BackendPost
{

  // static private vars

  static private $instance = null;
  static private $prefix = 'rex_';
  static private $sql;
  static private $category_path;

  /**
   * singleton
   *
   * instantiate the class as singleton
   *
   * @param
   * @return
   * @throws
   */

  public static function getInstance()
  {
    if (self::$instance === NULL)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * protected constructor function
   *
   * der constructor dient dazu als erstes den tableprefix
   * und eine rex_sql instanz zu erzeugen. zus�tzlich wird
   * der komplette kategorien pfad des frontends zugewiesen.
   *
   * @param
   * @return
   * @throws
   */

  private function __construct()
  {
    global $REX;

    self::$prefix = $REX['TABLE_PREFIX'];
    self::$sql = rex_sql::getInstance();
    self::$sql->debugsql = 0;
    self::$category_path = $REX['ADDON']['rexblog']['categories'];
  }

  /**
   * protected clone function
   *
   * @param
   * @return
   * @throws
   */

  private function __clone()
  {

  }

  /**
   * write_post_cache
   *
   * schreibt alle beiträge basierend auf ihren dazugehörigen
   * kategorie-identifikatoren in separate cachefiles. die dateien
   * werden auf dem server im include/generated/files ordner
   * von redaxo nach folgendem schema abgelegt:
   *
   * _rex488_post.KATEGORIEID.inc.php
   *
   * @param
   * @return
   * @throws
   */

  public static function write_post_cache()
  {
    global $REX;

    // fetch all categories

    $categories = self::$sql->getArray("SELECT id FROM " . self::$prefix . "488_rexblog_categories_id");

    // loop through all categories and create corresponding postfiles

    foreach($categories as $key => $category)
    {
      $category_id = $category['id'];

      // fetch posts corresponding to their categorie id

      $post_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_rexblog_postings WHERE ( FIND_IN_SET( " . $category_id . ", REPLACE(cat, '|+|', ',') ) ) ");

      // create header for cache file

      $content = "<?php\n\n";
      $content .= "\$REX['ADDON']['rexblog']['post'][" . $category_id . "] = array (\n";

      // loop through post_array

      foreach($post_array as $post_key => $post_value)
      {
	// preassign post variables

	$post_id	  = $post_value['id'];
	$post_categories  = explode('|+|', addslashes($post_value['cat']));
	$post_title	  = addslashes($post_value['title']);
	$post_keywords	  = addslashes($post_value['meta_keywords']);
	$post_description = addslashes($post_value['meta_description']);
	$post_content	  = addslashes($post_value['post']);
	$post_excerpt	  = addslashes($post_value['teaser']);
	$post_date	  = addslashes($post_value['date']);

	// format post url

	$post_url = self::$category_path[$id]['url'];
	$post_url = substr($post_url, 0, (strrpos($post_url, '/') + 1));
	$post_url = $post_url . self::format_name_to_url($title) . '.html';

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
	$content .= "	'post' => '" . $post_content . "',\n";
	$content .= "	'excerpt' => '" . $post_excerpt . "',\n";
	$content .= "	'date' => '" . $post_date . "',\n";

	// create post url content for file

	$content .= "	'url' => array(\n";

	foreach($post_categories as $post_category_url_key => $post_category_url_value)
	{
	  $post_category_url = self::$category_path[$post_category_url_value]['url'];
	  $post_category_url = substr($post_category_url, 0, strrpos($post_category_url, '/') + 1);
	  $post_category_url = $post_category_url . self::format_name_to_url($post_title) . '.html';

	  $content .= "		" . $post_category_url_key . " => '" . $post_category_url . "',\n";
	}

	$content .= ")),\n";
      }

      // create footer for cache file

      $content .= ")\n";
      $content .= "\n?>";

      // assign path for cache file

      $file = $REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.' . $category_id . '.inc.php';

      // write cache file

      rex_put_file_contents($file, $content);
    }
  }

  /**
   * write_categorie_cache
   *
   * schreibt alle beitr�ge basierend auf ihren dazugeh�rigen
   * kategorie-identifikatoren in separate cachefiles. die dateien
   * werden auf dem server im include/generated/files ordner
   * von redaxo nach folgendem schema abgelegt:
   *
   * _rex488_post.KATEGORIEID.inc.php
   *
   * @param
   * @return
   * @throws
   */

  public static function write_post_pathlist_cache()
  {
    global $REX;

    // fetch all posts

    $post_array = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_rexblog_postings ORDER BY id ASC");

    // create header for cache file

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['post']['pathlist'] = array (\n";

    // loop through post_array

    foreach($post_array as $post_key => $post_value)
    {
      // preassign post variables

      $post_id 					= $post_value['id'];
      $post_title 			= addslashes($post_value['title']);
      $post_categories 	= explode('|+|', addslashes($post_value['cat']));

      // format post url

      $post_url = self::$category_path[$id]['url'];
      $post_url = substr($post_url, 0, (strrpos($post_url, '/') + 1));
      $post_url = $post_url . self::format_name_to_url($title) . '.html';

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
	$post_category_url = $post_category_url . self::format_name_to_url($post_title) . '.html';

	$content .= "		" . $post_category_url_key . " => '" . $post_category_url . "',\n";
      }

      $content .= ")),\n";
    }

    // create footer for cache file

    $content .= ")\n";
    $content .= "\n?>";

    // assign path for cache file

    $file = $REX['INCLUDE_PATH'] . '/generated/files/_rex488_post.pathlist.inc.php';

    // write cache file

    rex_put_file_contents($file, $content);
  }

  /**
   * format_name_to_url
   *
   * formatiert den �bergebenen wert als url.
   *
   * @param string $name
   * @return
   * @throws
   */

  protected static function format_name_to_url($name)
  {
    $format_url = $name;
    $format_url = rex_parse_article_name($format_url);
    $format_url = strtolower($format_url);

    return $format_url;
  }
}

?>