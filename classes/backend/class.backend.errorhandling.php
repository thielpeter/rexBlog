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

abstract class _rex488_BackendErrorHandling extends _rex488_BackendBase
{
  // define private variables

  private static $mode = null;
  private static $pool = array();

  /**
   * returns the requested message from a
   * specific array if the message key exists.
   *
   * @return <type> requested message
   */

  public static function error_handling()
  {
    // define errorhandling pool

    self::$pool = array(
      'categories' => array(
        'info' => array(
          1 => 'Die Kategorie wurde erfolgreich angelegt.',
          2 => 'Die Kategorie wurde erfolgreich gespeichert.',
          3 => 'Die Kategorie wurde erfolgreich gelöscht.',
          4 => 'Der Status der Kategorie wurde erfolgreich geändert.',
          5 => 'Die Eingabe wurde erfolgreich abgebrochen.'
        ),
        'warning' => array(
          1 => 'Die Kategorie konnte nicht angelegt werden. Es fehlen Pflichtangaben.',
          2 => 'Die Kategorie konnte nicht gespeichert werden. Es fehlen Pflichtangaben.',
          3 => 'Die Kategorie konnte nicht gelöscht werden, da sie noch Unterkategorien enthält.',
          4 => 'Der Status der Kategorie konnte nicht geändert werden.'
        )
      ),
      'articles' => array(
        'info' => array(
          1 => 'Der Artikel wurde erfolgreich angelegt.',
          2 => 'Der Artikel wurde erfolgreich gespeichert.',
          3 => 'Der Artikel wurde erfolgreich gelöscht.',
          4 => 'Der Status des Artikels wurde erfolgreich geändert.',
          5 => 'Die Eingabe wurde erfolgreich abgebrochen.'
        ),
        'warning' => array(
          1 => 'Der Artikel konnte nicht angelegt werden. Es fehlen Pflichtangaben.',
          2 => 'Der Artikel konnte nicht gespeichert werden. Es fehlen Pflichtangaben.',
          3 => '',
          4 => 'Der Status des Artikels konnte nicht geändert werden.'
        )
      )

    );

    // define which mode should be used

    self::$mode = array_key_exists('info', $_REQUEST) ? 'info' : (array_key_exists('warning', $_REQUEST) ? 'warning' : null);

    // if a mode was set

    if(isset(self::$mode))
    {
      // and the requested message key exists

      if(array_key_exists(rex_request(self::$mode, 'int'), self::$pool[parent::$subpage][self::$mode]))

        // return corresponding message

        return rex_message(self::$pool[parent::$subpage][self::$mode][rex_request(self::$mode, 'int')], 'rex-' . self::$mode, 'p');
    }
  }
}

?>