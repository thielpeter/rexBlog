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

/**
 * überprüft eventuelle fehler während einer eingabe
 * und liefert diese anschließend formatiert zurück.
 */

echo _rex488_BackendErrorHandling::error_handling();

/**
 * zunächst wird die übergebene funktionsvariable
 * geprüft und anhand dessen der gewünschte funktionsablauf
 * ausgeführt. sollte keine funktionsvariable übergeben werden,
 * wird standardmäßig die liste der kategorien
 * der aktuellen parent ausgegeben.
 */

switch(rex_request('func', 'string'))
{
  /**
   * führt die write anweisung aus, nach welcher
   * eine kategorie in die datenbank geschrieben wird.
   */

  case 'write':
    _rex488_BackendCategories::write();
  break;

  /**
   * führt die state anweisung aus, nach welcher
   * die kategorie in der datenbank sortiert werden.
   */

  case 'sort':
    _rex488_BackendCategories::sort();
  break;

  /**
   * führt die visualize anweisung aus, nach welcher
   * eine kategorie einen neuen status erhält.
   */

  case 'visualize':
    _rex488_BackendCategories::visualize();
  break;

  /**
   * führt die delete anweisung aus, nach welcher
   * eine kategorie aus der datenbank gelöscht wird.
   */

  case 'delete':
    _rex488_BackendCategories::delete();
  break;

  /**
   * inkludiert das add template für das
   * hinzufügen einer kategorie.
   */

  case 'add':
    include _rex488_PATH . 'templates/backend/template.categories.add.phtml';
  break;

  /**
   * inkludiert das edit template für das
   * bearbeiten einer kategorie.
   */

  case 'edit':
    $category = _rex488_BackendCategories::read(rex_request('id', 'int'));
      include _rex488_PATH . 'templates/backend/template.categories.edit.phtml';
  break;

  /**
   * als standard wird das list template
   * für die auflistung der kategorien inkludiert.
   */

  default:
    include _rex488_PATH . 'templates/backend/template.categories.list.phtml';
  break;
}
?>