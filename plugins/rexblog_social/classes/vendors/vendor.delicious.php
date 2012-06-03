<?php

/**
 * Copyright (c) 2010, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
 */

abstract class _rex721_vendor_delicious
{
  private static $vendor_id   = 'delicious';
  private static $vendor_name = "Delicious";

  public static function _rex721_vendor_backend($selected = "")
  {
    return '<option ' . $selected . ' value="' . self::$vendor_id . '">' . self::$vendor_name . '</option>';
  }

  public static function _rex721_vendor_frontend()
  {
    return '<li><p><a class="_rex721_vendor_' . self::$vendor_id . ' _rex721_social_link" href="http://del.icio.us/post?url=%URL%&amp;title=%TITLE%"><span>' . self::$vendor_name . '</span></a></p></li>';
  }
}