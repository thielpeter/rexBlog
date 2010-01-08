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

_rex488_BackendBase::get_instance();

// errorhandling

echo _rex488_BackendErrorHandling::error_handling();

/**
 * zunächst wird die übergebene funktionsvariable
 * geprüft und anhand dessen der gewünschte funktionsablauf
 * ausgeführt. sollte keine funktionsvariable übergeben worden
 * sein, wird standardmäßig die liste der kategorien
 * der aktuellen parent ausgegeben.
 */

switch(rex_request('func', 'string'))
{
  /**
   * führt die write anweisung aus, nach welcher
   * eine kategorie in die datenbank gepsichert wird.
   */

  case 'write':
    _rex488_BackendCategories::write();
    break;

  /**
   * führt die delete anweisung aus, nach welcher
   * eine kategorie anhand der überlieferten id
   * aus der datenbank gelöscht wird.
   */

  case 'delete':
    _rex488_BackendCategories::delete();
    break;

  /**
   * führt die state anweisung aus, nach welcher
   * einer kategorie anhand der überlieferten id
   * einer neue status zugewiesen wird.
   */

  case 'state':
    _rex488_BackendCategories::state();
    break;

  /**
   * führt die state anweisung aus, nach welcher
   * einer kategorie anhand der überlieferten id
   * einer neue status zugewiesen wird.
   */

  case 'sort':
    _rex488_BackendCategories::sort();
    break;

  /**
   * führt die add anweisung aus, welche das
   * benötigte template zum hinzufügen einer
   * kategorie lädt.
   */

  case 'add':
    include _rex488_PATH . 'templates/backend/template.categories.add.phtml';
    break;

  /**
   * führt die add anweisung aus, welche das
   * benötigte template zum hinzufügen einer
   * kategorie lädt.
   */

  case 'edit':
    $category = _rex488_BackendCategories::read(rex_request('id', 'int'));
    include _rex488_PATH . 'templates/backend/template.categories.edit.phtml';
    break;

  /**
   * als default funktion wird einfach nur die liste
   * der kategorien der aktuellen parent angezeigt.
   */

  default:
    include _rex488_PATH . 'templates/backend/template.categories.list.phtml';
    break;
}
?>