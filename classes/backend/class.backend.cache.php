<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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

  public static function write_cache()
  {
    $result = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_rexblog_categories_id
												 						LEFT JOIN " . self::$prefix . "488_rexblog_categories
																		ON (" . self::$prefix . "488_rexblog_categories_id.id = " . self::$prefix . "488_rexblog_categories.cid)
																		ORDER BY priority ASC");

    $content = "<?php\n\n";
    $content .= "\$REX['ADDON']['rexblog']['categories'] = array (\n";

    foreach ($result as $value)
    {

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

      self::$sql->setQuery("SELECT * FROM " . self::$prefix . "488_rexblog_categories_id WHERE parent = " . $id . "");
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

    $file = dirname(__FILE__) . '/../../categories.inc.php';

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
    $result = self::$sql->getArray("SELECT * FROM " . self::$prefix . "488_rexblog_categories_id
								LEFT JOIN " . self::$prefix . "488_rexblog_categories
								ON (" . self::$prefix . "488_rexblog_categories.cid = " . self::$prefix . "488_rexblog_categories_id.id)
								WHERE " . self::$prefix . "488_rexblog_categories.cid = " . $parent . "
								ORDER BY priority ASC");
    self::$url[$result[0]['id']] = $result[0]['name'];

    if ($result[0]['parent'] > 0)
      self::get_parents($result[0]['parent']);
  }
}

?>
