<?php

/**
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
 */

abstract class _rex488_BackendCategories extends _rex488_BackendBase implements _rex488_BackendCategoryInterface
{
  // constant

  const SUBPAGE = 'categories';

  //protected

  protected static $mode = 'insert';
  protected static $entry_id = 0;
  protected static $breadcrumb = array();

  /**
   * read
   *
   * liefert auf basis von parent die
   * unterkategorien der aktuellen kategorie zurück
   *
   * @param int $id id for a single category
   * @return array $categories array der kategorien
   * @throws
   */

  public static function read($id = null)
  {
    if(isset($id))
    {
      self::$entry_id = $id;
      $result = parent::$sql->getArray(
        sprintf("SELECT * FROM %s WHERE ( %s = '%d' ) ORDER BY priority ASC",
          parent::$prefix . "488_categories", "category_id", self::$entry_id
        )
      );

      foreach($result as $key => $value) {
        foreach($value as $k => $v) {
          $category[$k] = $v;
        }
      }

      return $category;

    } else {
      $categories = parent::$sql->getArray(
        sprintf("SELECT * FROM %s WHERE ( %s = '%d' ) ORDER BY priority ASC",
          parent::$prefix . "488_categories", "parent_id", parent::$parent_id
        )
      );

      return $categories;
    }
  }

  /**
   * write
   *
   * schreibt die übergebenen daten für die kategorie
   * in die datenbank. hierbei unterscheidet man, ob
   * die kategorie neu angelegt wird, oder eine
   * vorhandene kategorie erneuert wird.
   *
   * @param string $mode insert or update
   * @return
   * @throws
   *
   */

  public static function write()
  {
    self::$mode = rex_request('mode', 'string');
    self::$entry_id = rex_request('id', 'int');

    if(isset($_REQUEST['cancel'])) {
      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=5');
        exit();
    }

    if(self::$mode == 'insert')
    {
      parent::$sql->table = parent::$prefix . '488_categories';
      parent::$sql->setValue('parent_id', parent::$parent_id);
      parent::$sql->insert();

      $category_id = parent::$sql->last_insert_id;

      parent::$sql->table = parent::$prefix . '488_categories';
      parent::$sql->setValue('category_id', $category_id);
      parent::$sql->setValue('clang', 0);
      parent::$sql->setValue('title', rex_request('title', 'string'));
      parent::$sql->setValue('keywords', rex_request('keywords', 'string'));
      parent::$sql->setValue('description', rex_request('description', 'string'));
      parent::$sql->setValue('priority', 1000000 + $category_id);
      parent::$sql->setValue('status', 0);
      parent::$sql->wherevar = "WHERE ( id = '" . $category_id . "' )";

      if(parent::$sql->update())
      {
        $category = rex_register_extension_point('REX488_CAT_ADDED', parent::$sql, array(
          'id'          => $category_id,
          'category_id' => $category_id,
          'clang'       => 0,
          'title'       => rex_request('title', 'string'),
          'keywords'    => rex_request('keywords', 'string'),
          'description' => rex_request('description', 'string'),
          'priority'    => 1000000 + $category_id,
          'status'      => 0)
        );

        header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=1');
          exit();
      }
    } else if(self::$mode == 'update')
    {
      parent::$sql->table = parent::$prefix . '488_categories';
      parent::$sql->setValue('title', rex_request('title', 'string'));
      parent::$sql->setValue('keywords', rex_request('keywords', 'string'));
      parent::$sql->setValue('description', rex_request('description', 'string'));
      parent::$sql->wherevar = "WHERE ( category_id = '" . self::$entry_id . "' )";

      if(parent::$sql->update())
      {
        $category = rex_register_extension_point('REX488_CAT_UPDATED', parent::$sql, array(
          'category_id' => self::$entry_id,
          'title'       => rex_request('title', 'string'),
          'keywords'    => rex_request('keywords', 'string'),
          'description' => rex_request('description', 'string'))
        );

        if(isset($_REQUEST['update'])) {
          header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&func=edit&id=' . self::$entry_id . '&parent=' . parent::$parent_id  . '&info=2');
        } else {
          header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=2');
        }

        exit();
      }
    }
  }

  /**
   * sort
   *
   * sortiert die kategorien nach ihrer priorität
   *
   * @param array $priorities array der sortierreihenfolge
   * @return
   * @throws
   *
   */

  public static function sort($priorities)
  {

  }

  /**
   * delete
   *
   * löscht eine kategorien anhand der übergebenen id.
   * zugleich wird überprüft, ob die zu löschende kategorie
   * noch unterkategorien enthält.
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function delete()
  {
    self::$entry_id = rex_request('id', 'int');
    
    parent::$sql->setQuery("SELECT id, parent_id FROM " . parent::$prefix . "488_categories WHERE ( parent_id = '" . self::$entry_id . "' )");

    if(parent::$sql->getRows() > 0) {
      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&warning=3');
        exit();
    }

    parent::$sql->table = parent::$prefix . '488_categories';
    parent::$sql->wherevar = "WHERE ( id = '" . self::$entry_id . "' ) ";

    if(parent::$sql->delete())
    {
      $category = rex_register_extension_point('REX488_CAT_DELETED', parent::$sql, array(
        'id'          => self::$entry_id,
        'category_id' => self::$entry_id)
      );

      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=3');
        exit();
    }
  }

  /**
   * state
   *
   * setzt den status der kategorie
   *
   * @param
   * @return
   * @throws
   *
   */

  public static function state()
  {
    self::$entry_id = rex_request('id', 'int');

    $state = parent::$sql->setQuery(
      sprintf("SELECT * FROM %s WHERE ( %s = '%d' )",
        parent::$prefix . "488_categories", "category_id", self::$entry_id
      )
    );
    
    $state = parent::$sql->getValue('status');
    $state = ($state == 1) ? 0 : 1;

    parent::$sql->table = parent::$prefix . '488_categories';
    parent::$sql->setValue('status', $state);
    parent::$sql->wherevar = "WHERE ( category_id = '" . self::$entry_id . "' )";

    if(parent::$sql->update() === true)
    {
      $category = rex_register_extension_point('REX488_CAT_STATUS', parent::$sql, array(
        'id'          => self::$entry_id,
        'category_id' => self::$entry_id,
        'status'      => $state)
      );

      header('location: index.php?page=' . parent::PAGE . '&subpage=' . self::SUBPAGE . '&parent=' . parent::$parent_id  . '&info=4');
        exit();
    }
  }

  /**
   * erzeugt ein array aller elternkategorien
   * basierend auf der aktuellen parent kategorie
   *
   * @return array $breadcrumb array of parent elements
   */

  public static function breadcrumb($home = 'Home')
  {
    $parent = parent::$parent_id;

    while($parent > 0)
    {
      parent::$sql->setQuery(
        sprintf("SELECT %s, %s, %s, %s FROM %s WHERE ( %s = %d )",
          "id", "category_id", "parent_id", "title", parent::$prefix . "488_categories", "category_id", $parent
        )
      );
      
      self::$breadcrumb[parent::$sql->getValue('id')]['title'] = parent::$sql->getValue('title');
      self::$breadcrumb[parent::$sql->getValue('id')]['parent_id'] = parent::$sql->getValue('id');
      
      $parent = parent::$sql->getValue('parent_id');
    }
    
    self::$breadcrumb[0]['title'] = $home;
    self::$breadcrumb[0]['parent_id'] = 0;

    self::$breadcrumb = array_reverse(self::$breadcrumb);

    return self::$breadcrumb;
  }
}
?>