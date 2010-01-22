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

class _rex488_content_plugin_textareafield
{
  const ID = 'textareafield';
  const NAME = 'textareafield';

  public static function getElement($index, $value)
  {
    $element = '';

    $element .= '<div class="rex-form-row rex-form-sortable">';
    $element .= '<p class="rex-form-textarea">';
    $element .= '<label for="_rex488_element_' . $index . '">' . self::NAME . '</label>';
    $element .= '<textarea id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" class="rex-form-textarea" rows="5">' . $value . '</textarea>';
    $element .= '<div class="_rex488_control_panel"><span class="_rex488_move_element">Element verschieben</span><span class="_rex488_remove_element">Element löschen</span></div>';
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
