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

class _rex488_BackendCache extends _rex488_BackendBase
{
  private static $url;

    /**
   * initialize class objects
   *
   * initializes needed class objects
   *
   * @throws
   * @global	$REX
   * @param
   * @return
   */

  public static function write_category_cache()
  {
    $result = parent::$sql->getArray("SELECT * FROM " . parent::$prefix . "488_rexblog_categories_id
												 						LEFT JOIN " . parent::$prefix . "488_rexblog_categories
																		ON (" . parent::$prefix . "488_rexblog_categories_id.id = " . parent::$prefix . "488_rexblog_categories.cid)
																		ORDER BY priority ASC");
		
    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['categories'] = array (\n";

    foreach ($result as $value)
    {
			global $REX;
			
      $url = "";
      self::$url = "";

      $id = $value['id'];
      $parent = $value['parent'];
      $name = $value['name'];
      $keywords = $value['meta_keywords'];
      $description = $value['meta_description'];
      self::$url[$id] = $value['name'];

      if ($parent > 0)
			self::get_parents($parent);

      self::$url = array_reverse(self::$url);

      $append_url = self::prepare_url($name);

      foreach(self::$url as $value)
      {
	$value = self::prepare_url($value);
	$url .= $value . '/';
      }

      $url .= $append_url . '.html';

      parent::$sql->setQuery("SELECT * FROM " . parent::$prefix . "488_rexblog_categories_id WHERE parent = " . $id . "");
      $children = (self::$sql->getRows() > 0) ? 1 : 0;

      $content .= $id . " => ";
      $content .= "array (\n";
      $content .= "	'id' => " . $id . ",\n";
      $content .= "	'parent' => " . $parent . ",\n";
      $content .= "	'children' => " . $children . ",\n";
      $content .= "	'name' => '" . $name . "',\n";
      $content .= "	'keywords' => '" . $keywords . "',\n";
      $content .= "	'description' => '" . $description . "',\n";
      $content .= "	'url' => '" . $url . "'\n";
      $content .= "),\n";
    }

    $content .= ")\n";
    $content .= "\n?>";

    $file = $REX['INCLUDE_PATH'] . '/generated/files/_rex488_categories.inc.php';

    rex_put_file_contents($file, $content);
  }

  private function prepare_url($url)
  {
    $url = rex_parse_article_name($url);
    $url = strtolower($url);
    return $url;
  }

  private function get_parents($parent)
  {
    $result = parent::$sql->getArray("SELECT * FROM " . parent::$prefix . "488_rexblog_categories_id
								LEFT JOIN " . parent::$prefix . "488_rexblog_categories
								ON (" . parent::$prefix . "488_rexblog_categories.cid = " . parent::$prefix . "488_rexblog_categories_id.id)
								WHERE " . parent::$prefix . "488_rexblog_categories.cid = " . $parent . "
								ORDER BY priority ASC");
    self::$url[$result[0]['id']] = $result[0]['name'];

    if ($result[0]['parent'] > 0)
      self::get_parents($result[0]['parent']);
  }
}

?>
