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

abstract class _rex488_content_plugin_textfield
{
  const ID = 'textfield';
  const NAME = 'textfield';

  public static function getElement($index, $value)
  {
    $element = '';

    $element .= '<div class="rex-form-row rex-form-sortable">';
    $element .= '<p class="rex-form-text">';
    $element .= '<label for="_rex488_element_' . $index . '">Textfeld<span class="_rex488_remove_element"></span><span class="_rex488_move_element"></span></label>';
    $element .= '<input id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" type="text" class="rex-form-text" value="' . $value . '" />';
    $element .= '</p>';
    $element .= '</div>';

    return $element;
  }

  public static function read_id()
  {
    return self::ID;
  }

  public static function read_name()
  {
    return self::NAME;
  }
}

?>
