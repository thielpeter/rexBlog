<?php

/*
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
*/

class _rex488_BackendException extends Exception
{
  public $message = '';
  
  public function __construct($message = '')
  {
    $this->message = $message;
    set_exception_handler(array($this, 'custom_exception'));
  }

  public function custom_exception($exception)
  {
    $file = $exception->getFile();
    $file = substr($file, strrpos($file, '/') + 1, strlen($file));

    $error_message .= '<div class="rex-addon-output-v2">';
    $error_message .= '<div class="rex-message">';
    $error_message .= '<div class="rex-warning">';

    $error_message .= '<p style="font-weight:normal;line-height:150%;">';
    $error_message .= '<span>';
    $error_message .= '<b>Fehler</b> Zeile ';
    $error_message .= $exception->getLine();
    $error_message .= ', ';
    $error_message .= $file;
    $error_message .= ', ';
    $error_message .= $this->message;
    $error_message .= '</span>';
    $error_message .= '</p>';

    $error_message .= '</div>';
    $error_message .= '</div>';
    $error_message .= '</div>';

    echo $error_message;
  }
}

?>
