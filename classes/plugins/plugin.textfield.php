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
  const NAME = '01.01 - Einfaches Textfeld';

  public static function getElement($index, $value, $settings)
  {
    $element = '';

    $element .= '<div class="rex488-form-row rex488-form-sortable">';
    $element .= '
      <div class="rex-content-editmode-module-name">
      <h3 class="rex-hl4">' . self::NAME . '</h3>
        <div class="rex-navi-slice">
          <ul>
            <li class="rex-navi-first"><a href="#" onclick="Rexblog.Article.Settings(this); return false;" class="rex-tx3">Einstellungen<span>' . self::NAME . '</span></a></li>
            <li class="rex-navi-first"><a href="#" onclick="Rexblog.Article.DeleteSlice(this); return false;" class="rex-tx2">Löschen<span>' . self::NAME . '</span></a></li>
            <li><a href="#" onclick="Rexblog.Article.MoveSliceUp(this); return false;" title="Nach oben verschieben" class="rex-slice-move-up"><span>' . self::NAME . '</span></a></li>
            <li><a href="#" onclick="Rexblog.Article.MoveSliceDown(this); return false;"title="Nach unten verschieben" class="rex-slice-move-down"><span>' . self::NAME . '</span></a></li>
          </ul>
        </div>
      </div>';
    
    $excerpt  = ($settings['excerpt'] == 'on') ? ' checked="checked"' : '';

    $element .= '<p class="rex488-form-checkbox">';
    $element .= '<input name="_rex488_settings[' . $index . '][index]" type="hidden" value="' . $index . '" />';
    $element .= '<input name="_rex488_settings[' . $index . '][type]" type="hidden" value="' . self::ID . '" />';
    $element .= '<input name="_rex488_settings[' . $index . '][excerpt]" type="checkbox"' . $excerpt . ' class="rex488-form-checkbox" />';
    $element .= '<label class="rex488-form-checkbox">Plugin als Einleitung</label>';
    $element .= '</p>';

    $element .= '<p class="rex488-form-text">';
    $element .= '<input id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" type="text" class="rex488-form-text" value="' . $value . '" />';
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
