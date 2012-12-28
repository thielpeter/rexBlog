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

_rex488_BackendBase::get_instance();

/**
 * überprüft eventuelle fehler während einer eingabe
 * und liefert diese anschließend formatiert zurück.
 */

echo _rex488_BackendErrorHandling::error_handling();

/**
 * comment observer instance
 */

_rex488_BackendCommentsObserver::_rex488_observer_config();

/**
 * config page variables
 */

$id     = rex_request('id', 'int');
$func   = rex_request('func', 'string');
$action = rex_request('action', 'string');
$next   = rex_request('next', 'int');
$filter = rex_request('filter', 'string');
$type   = rex_request('type', 'string');

/**
 * comments pagination config
 */

if(isset($_POST['type']))
  $next = 1;

$next = ($next == 0) ? 1 : $next;

$total = _rex488_BackendComments::_rex488_comment_count($filter, $type);
$range = (filter_var($next, FILTER_VALIDATE_INT, array("options" => array('min_range' => 1, 'max_range' => ceil($total / $REX['ADDON']['comment']['rexblog']['pagelimit'])))) === false) ? false : true;

if((boolean) $range === false) {
  $next = $next - 1;
    $next = ($next <= 0) ? 1 : $next;
}

/**
 * comments pagination call
 */

_rex488_BackendPagination::_rex488_generate_pagination(array(
  'next'      => $next,
  'adjacents' => 2,
  'limit'     => $REX['ADDON']['comment']['rexblog']['pagelimit'],
  'matches'   => $total
  )
);

/**
 * handle multiple comment actions
 */

if(isset($_POST['multiple']) && $_POST['multiple'] == 'multiple' && count($_POST['comments']) > 0)
{
  if($action > 0)
  {
    foreach($_POST['comments'] as $comment_id)
    {
      switch($action)
      {
        case 1:
           _rex488_BackendCommentsObserver::_rex488_observer_learn($comment_id, 'ham');
        break;
        case 2:
           _rex488_BackendCommentsObserver::_rex488_observer_learn($comment_id, 'spam');
        break;
        case 3:
           _rex488_BackendCommentsObserver::_rex488_observer_unlearn($comment_id, 'ham');
        break;
        case 4:
            _rex488_BackendCommentsObserver::_rex488_observer_unlearn($comment_id, 'spam');
        break;
        case 5:
          _rex488_BackendComments::delete($comment_id);
        break;
      }
    }

    $redirect = true;
    $info     = '&info=7';
  }
}

/**
 * zunächst wird die übergebene funktionsvariable
 * geprüft und anhand dessen der gewünschte funktionsablauf
 * ausgeführt. sollte keine funktionsvariable übergeben werden,
 * wird standardmäßig die liste der artikel ausgegeben.
 */

switch($func)
{
  /**
   * führt die visualize anweisung aus, nach welcher
   * ein artikel einen neuen status erhält.
   */

  case 'visualize':
    _rex488_BackendComments::visualize($id);
      $redirect = true;
        $info = '&info=4';
  break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'delete':
    _rex488_BackendComments::delete($id);
      $redirect = true;
        $info = '&info=3';
  break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'ham':
    _rex488_BackendCommentsObserver::_rex488_observer_learn($id, 'ham');
      $redirect = true;
        $info = '&info=6';
  break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'spam':
    _rex488_BackendCommentsObserver::_rex488_observer_learn($id, 'spam');
      $redirect = true;
        $info = '&info=6';
 break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'unham':
    _rex488_BackendCommentsObserver::_rex488_observer_unlearn($id, 'ham');
      $redirect = true;
        $info = '&info=6';
 break;

  /**
   * führt die delete anweisung aus, nach welcher
   * ein artikel aus der datenbank gelöscht wird.
   */

  case 'unspam':
    _rex488_BackendCommentsObserver::_rex488_observer_unlearn($id, 'spam');
      $redirect = true;
        $info = '&info=6';
 break;

  /**
   * als standard wird das list template
   * für die auflistung der artikel inkludiert.
   */

  default:
    include _rex488_PATH . 'templates/backend/template.comments.list.phtml';
  break;
}

/**
 * redirect after operations
 */

if(isset($redirect) && (boolean) $redirect === true)
{
  $total = _rex488_BackendComments::_rex488_comment_count($filter, $type);
  $range = (filter_var($next, FILTER_VALIDATE_INT, array("options" => array('min_range' => 1, 'max_range' => ceil($total / $REX['ADDON']['comment']['rexblog']['pagelimit'])))) === false) ? false : true;

  if((boolean) $range === false) {
    $next = $next - 1;
      $next = ($next <= 0) ? 1 : $next;
  }

  header('location: index.php?page=' . _rex488_BackendBase::PAGE . '&subpage=' . _rex488_BackendComments::SUBPAGE . $info . '&filter=' . $filter . '&type=' . $type . '&next=' . $next);
    exit();
}
?>