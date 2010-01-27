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

class _rex488_content_plugin_lightbox
{
  const ID = 'lightbox';
  const NAME = '01.03 - Lightbox Galerie';

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
    $wrap     = ($settings['wrap'] == 'on') ? ' checked="checked"' : '';
    $items    = explode(',', $value);

    $element .= '<p class="rex488-form-checkbox">';
    $element .= '<input name="_rex488_settings[' . $index . '][index]" type="hidden" value="' . $index . '" />';
    $element .= '<input name="_rex488_settings[' . $index . '][type]" type="hidden" value="' . self::ID . '" />';
    $element .= '<input name="_rex488_settings[' . $index . '][excerpt]" type="checkbox"' . $excerpt . ' class="rex488-form-checkbox" />';
    $element .= '<label class="rex488-form-checkbox">Plugin als Einleitung</label>';
    $element .= '</p>';

    $element .= '<div class="rex-widget rex488-widget">
      <div class="rex-widget-medialist">
        <input type="hidden" name="_rex488_element[' . $index . '][' . self::ID . ']" id="REX_MEDIALIST_1" value="' . $value . '" />
        <p class="rex-widget-field rex488-widget-field">
          <select id="REX_MEDIALIST_SELECT_1" name="MEDIALIST_SELECT[1]" size="10" class="rex488-widget-select">';

          if(sizeof($items) > 0) {
            foreach($items as $item) {
              if($item == "") continue;
                $element .= '<option value="' . $item . '">' . $item . '</option>';
            }
          }
    
        $element .= '</select>
        </p>
        <p class="rex-widget-icons rex488-widget-icons">
          <a href="#" class="rex-icon-file-top" onclick="moveREXMedialist(1,\'top\');return false;" tabindex="36"><img src="media/file_top.gif" width="16" height="16" title="Ausgewähltes Medium an den Anfang verschieben" alt="Ausgewähltes Medium an den Anfang verschieben" /></a>
          <a href="#" class="rex-icon-file-open" onclick="openREXMedialist(1);return false;" tabindex="37"><img src="media/file_open.gif" width="16" height="16" title="Medium auswählen" alt="Medium auswählen" /></a><br />
          <a href="#" class="rex-icon-file-up" onclick="moveREXMedialist(1,\'up\');return false;" tabindex="38"><img src="media/file_up.gif" width="16" height="16" title="Ausgewähltes Medium nach oben verschieben" alt="Ausgewähltes Medium an den Anfang verschieben" /></a>
          <a href="#" class="rex-icon-file-add" onclick="addREXMedialist(1);return false;" tabindex="39"><img src="media/file_add.gif" width="16" height="16" title="Neues Medium hinzufügen" alt="Neues Medium hinzufügen" /></a><br />
          <a href="#" class="rex-icon-file-down" onclick="moveREXMedialist(1,\'down\');return false;" tabindex="40"><img src="media/file_down.gif" width="16" height="16" title="Ausgewähltes Medium nach unten verschieben" alt="Ausgewähltes Medium nach unten verschieben" /></a>
          <a href="#" class="rex-icon-file-delete" onclick="deleteREXMedialist(1);return false;" tabindex="41"><img src="media/file_del.gif" width="16" height="16" title="Ausgewähltes Medium löschen" alt="Ausgewähltes Medium löschen" /></a><br />
          <a href="#" class="rex-icon-file-bottom" onclick="moveREXMedialist(1,\'bottom\');return false;" tabindex="42"><img src="media/file_bottom.gif" width="16" height="16" title="Ausgewähltes Medium an das Ende verschieben" alt="Ausgewähltes Medium an das Ende verschieben" /></a>
        </p>
        <div class="rex-media-preview"></div>
      </div>

    </div>
';

    //$element .= '<textarea id="_rex488_element_' . $index . '" name="_rex488_element[' . $index . '][' . self::ID . ']" class="rex488-form-textarea" rows="5" cols="10">' . $value . '</textarea>';
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
