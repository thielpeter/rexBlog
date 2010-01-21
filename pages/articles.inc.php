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
 * wird standardmäßig die liste der artikel ausgegeben.
 */

switch(rex_request('func', 'string'))
{
  /**
   * führt die write anweisung aus, nach welcher
   * ein artikel in die datenbank geschrieben wird.
   */

  case 'write':
    _rex488_BackendArticles::write();
  break;

  /**
   * führt die visualize anweisung aus, nach welcher
   * ein artikel einen neuen status erhält.
   */

  case 'visualize':
    _rex488_BackendArticles::visualize();
  break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'delete':
    _rex488_BackendArticles::delete();
  break;

  /**
   * inkludiert das add template für das
   * hinzufügen eines artikels.
   */

  case 'add':
    include _rex488_PATH . 'templates/backend/template.articles.add.phtml';
  break;

  /**
   * inkludiert das edit template für das
   * bearbeiten eines artikels.
   */

  case 'edit':
    $article = _rex488_BackendArticles::read(rex_request('id', 'int'));
      include _rex488_PATH . 'templates/backend/template.articles.edit.phtml';
  break;

  /**
   * inkludiert das add template für das
   * hinzufügen eines artikels.
   */

  case 'plugin':
    $content_plugin_class = "echo _rex488_content_plugin_" . rex_request('element', 'string') . "::getElement(" . rex_request('index', 'int') . ", '');";
      eval($content_plugin_class);
        exit();
  break;

  /**
   * inkludiert das add template für das
   * hinzufügen eines artikels.
   */

  case 'load':
    _rex488_BackendArticles::load(rex_request('id', 'int'));
        exit();
  break;

  /**
   * als standard wird das list template
   * für die auflistung der artikel inkludiert.
   */

  default:
    include _rex488_PATH . 'templates/backend/template.articles.list.phtml';
  break;
}
?>