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

class _rex488_content_plugin_tinymce
{
  const ID = 'tinymce';
  const NAME = '01.03 - Tinymce Editor';

  public static function getElement($index, $value, $settings)
  {
    ob_start();

    echo '<div class="rex488-form-row rex488-form-sortable">';
    echo '
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

    $checked = ($settings['excerpt'] == 'on') ? ' checked="checked"' : '';

    echo '<p class="rex488-form-checkbox">';
    echo '<label class="rex488-form-checkbox">Plugin als Einleitung</label>';
    echo '<input name="_rex488_settings[' . $index . '][index]" type="hidden" value="' . $index . '" />';
    echo '<input name="_rex488_settings[' . $index . '][excerpt]" type="checkbox"' . $checked . ' class="rex488-form-checkbox" />';
    echo '</p>';

    echo '<p class="rex488-form-textarea">';
    echo '<textarea id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" class="rex488-form-textarea rex488-form-tinymce" rows="5">' . $value . '</textarea>';
    echo '</p>';
    echo '</div>';

    echo '<script type="text/javascript">
    tinyMCE.init({
      mode    : "none",
      theme   : "simple",
      width   : "100%",
      height  : "250px"
    });
    tinyMCE.execCommand("mceAddControl", false, "_rex488_element_' . $index . '");
    </script>';

    $element = ob_get_contents();

    ob_end_clean();

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
