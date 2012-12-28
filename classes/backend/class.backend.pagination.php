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

abstract class _rex488_BackendPagination extends _rex488_BackendBase
{
  // protected internals
  protected static $currpage;
  protected static $nextpage;
  protected static $prevpage;
  protected static $lastpage;
  protected static $pagination;

  // protected configuration
  protected static $config = array(
    'adjacents' => 2,
    'limit' => 2
  );

  /**
   * _rex488_render_pagination
   *
   * @param <type> $config
   */

  public static function _rex488_generate_pagination($config = array())
  {
    // load external configuration
    if(count($config) > 0) {
      foreach($config as $name => $value) {
        $config[$name] = $value;
      }
    }

    // prevent zero prevpage
    $config['next'] = ($config['next'] == 0) ? 1 : $config['next'];

    // setup page properties
    self::$currpage = $config['next'];
    self::$prevpage = $config['next'] - 1;
    self::$nextpage = $config['next'] + 1;
    self::$lastpage = ceil($config['matches'] / $config['limit']);

    // setup pagination baseurl
    $baseurl = 'index.php?page=' . rex_request('page', 'string')
                                 . '&amp;subpage=' . rex_request('subpage', 'string')
                                 . '&amp;filter=' . rex_request('filter', 'string')
                                 . '&amp;type=' . rex_request('type', 'string');

    // generate pagination output
    if(self::$lastpage > 1)
    {
      // prevpage constructor
      if(self::$currpage > 1) {
        self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&next=' . self::$prevpage . '">vorherige</a></p></li>';
      } else {
        self::$pagination .= '<li><p class="rex-button"><a class="rex-button disabled">vorherige</a></p></li>';
      }

      // base constructor less than
      if(self::$lastpage < 5 + ($config['adjacents'] * 2))
      {
        for($counter = 1; $counter <= self::$lastpage; $counter++)
        {
          if($counter == self::$currpage) {
             self::$pagination .= '<li><p class="rex-button"><a class="rex-button current">' . $counter . '</a></p></li>';
          } else {
             self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . $counter . '">' . $counter . '</a></p></li>';
          }
        }
      }

      // base constructor more than
      elseif(self::$lastpage > 3 + ($config['adjacents'] * 2))
      {
        // pagination beginning part clickable
        if(self::$currpage < 1 + ($config['adjacents'] * 2))
        {
          for($counter = 1; $counter < 2 + ($config['adjacents'] * 2); $counter++)
          {
            if($counter == self::$currpage) {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button current">' . $counter . '</a></p></li>';
            } else {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . $counter . '">' . $counter . '</a></p></li>';
            }
          }

          self::$pagination .= '<li>...</li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . (self::$lastpage - 1) . '">' . (self::$lastpage - 1) . '</a></p></li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . self::$lastpage . '">' . self::$lastpage . '</a></p></li>';
        }

        // pagination beginning part static
        elseif(self::$lastpage - ($config['adjacents'] * 2) > self::$currpage && self::$currpage > ($config['adjacents'] * 2))
        {
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=1">1</a></p></li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=2">2</a></p></li>';
          self::$pagination .= '<li>...</li>';

          for ($counter = self::$currpage - $config['adjacents']; $counter <= self::$currpage + $config['adjacents']; $counter++)
          {
            if ($counter == self::$currpage) {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button current">' . $counter . '</a></p></li>';
            } else {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . $counter . '">' . $counter . '</a></p></li>';
            }
          }

          self::$pagination .= '<li>...</li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . (self::$lastpage - 1) . '">' . (self::$lastpage - 1) . '</a></p></li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . self::$lastpage . '">' . self::$lastpage . '</a></p></li>';
        }

        // pagination beginning part static less than
        else
        {
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=1">1</a></p></li>';
          self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=2">2</a></p></li>';
          self::$pagination .= '<li>...</li>';

          for ($counter = self::$lastpage - (2 + ($config['adjacents'] * 2)); $counter <= self::$lastpage; $counter++)
          {
            if ($counter == self::$currpage) {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button current">' . $counter . '</a></p></li>';
            } else {
              self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . $counter . '">' . $counter . '</a></p></li>';
            }
          }
        }
      }

      // nextpage constructor
      if (self::$currpage < $counter - 1)
        self::$pagination .= '<li><p class="rex-button"><a class="rex-button" href="' . $baseurl . '&amp;next=' . self::$nextpage . '">nächste</a></p></li>';
      else
        self::$pagination .= '<li><p class="rex-button"><a class="rex-button disabled">nächste</a></p></li>';
    }
  }

  /**
   * _rex488_show_pagination
   * 
   * @return <type>
   */

  public static function _rex488_show_pagination()
  {
    return self::$pagination;
  }
}
?>