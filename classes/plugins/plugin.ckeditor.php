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

class _rex488_content_plugin_ckeditor
{
  const ID = 'ckeditor';
  const NAME = 'ckeditor';

  public static function getElement($index, $value)
  {
    ob_start();

    echo '<div class="rex-form-row rex-form-sortable">';
    echo '<p class="rex-form-textarea">';
    echo '<label for="_rex488_element_' . $index . '">CKEditor<span class="_rex488_remove_element"></span><span class="_rex488_move_element"></span></label>';
    echo '<textarea id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" class="rex-form-textarea" rows="5">' . $value . '</textarea>';
    echo '</p>';
    echo '</div>';

    echo '<script type="text/javascript">';
    echo 'CKEDITOR.config.width = \'462px\';';
    echo 'CKEDITOR.config.uiColor = \'#DFE9E9\';';
    echo 'CKEDITOR.config.resize_enabled = false;';
    echo 'CKEDITOR.config.toolbarCanCollapse = false;';
    echo 'CKEDITOR.replace(\'_rex488_element_' . $index . '\');';
    echo '</script>';

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
