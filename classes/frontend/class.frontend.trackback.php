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

abstract class _rex488_FrontendTrackback extends _rex488_FrontendBase
{
  private static $vendor_url;
  private static $vendor_title;
  private static $vendor_name;
  private static $vendor_excerpt;
  private static $trackback_query;
  private static $trackback_host;
  private static $trackback_port;
  private static $trackback_path;
  private static $trackback_socket;
  private static $trackback_request;
  private static $trackback_url;
  private static $trackback_title;
  private static $trackback_name;
  private static $trackback_excerpt;
  private static $trackback_aid;
  private static $trackback_type;
  private static $trackback_time;
  private static $trackback_ip;
  private static $trackback_send;

   /**
   * _rex488_trackback_allocate
   *
   * @global <type> $REX
   * @param <type> $params
   */

  public static function _rex488_trackback_allocate($params)
  {  
    global $REX;

    parent::$sql->setQuery("SELECT article_trackbacks_send FROM " . parent::$prefix . "488_articles WHERE ( id = '" . $params['id'] ."' )");
    
    self::$trackback_send = parent::$sql->getValue('article_trackbacks_send');
    self::$trackback_send = unserialize(self::$trackback_send);

    if(!is_array(self::$trackback_send) || count(self::$trackback_send) == 0)
      self::$trackback_send = array();

    parent::$sql->setQuery("SELECT article_trackbacks, article_trackbacks_excerpt FROM " . parent::$prefix . "488_articles WHERE ( id = '" . $params['id'] ."' )");
    
    $article_trackbacks         = parent::$sql->getValue('article_trackbacks');
    $article_trackbacks         = unserialize(stripslashes($article_trackbacks));
    $article_trackbacks_excerpt = parent::$sql->getValue('article_trackbacks_excerpt');

    if(is_array($article_trackbacks) && count($article_trackbacks) > 0)
    {
      foreach($article_trackbacks as $article_trackback)
      {
        self::$vendor_url      = rawurldecode($REX['SERVER'] . $params['article_permlink']);
        self::$vendor_title    = rawurldecode($params['title']);
        self::$vendor_name     = rawurlencode($REX['SERVERNAME']);
        self::$vendor_excerpt  = rawurlencode($article_trackbacks_excerpt);

        if(self::_rex488_trackback_send($article_trackback)) {
          $trackback_response = '1';
        } else {
          $trackback_response = '0';
        }

        array_push(self::$trackback_send, array("trackback" => $article_trackback, "response" => $trackback_response));
      }

      parent::$sql->setQuery("UPDATE " . parent::$prefix . "488_articles SET article_trackbacks_send = '" . serialize(self::$trackback_send) . "' WHERE ( id = '" . $params['id'] ."' )");
      parent::$sql->setQuery("UPDATE " . parent::$prefix . "488_articles SET article_trackbacks = '' WHERE ( id = '" . $params['id'] ."' )");
    }
  }

  /**
   * _rex488_send_trackback
   *
   * @param <string> $trackback_url
   * @param <string> $vendor_url
   * @param <string> $vendor_title
   * @param <string> $vendor_name
   * @param <string> $vendor_excerpt
   * @return <type>
   */

  private static function _rex488_trackback_send($article_trackback_url)
  {
    ///////////////////////////////////////////////////////////////////////////
    // parse $trackback_url

    self::$trackback_url   = parse_url($article_trackback_url);

    ///////////////////////////////////////////////////////////////////////////
    // set trackback values

    self::$trackback_query = self::$trackback_url["query"];
    self::$trackback_host  = self::$trackback_url["host"];
    self::$trackback_port  = self::$trackback_url["port"];
    self::$trackback_path  = self::$trackback_url["path"];

    ///////////////////////////////////////////////////////////////////////////
    // additional params

    if(isset(self::$trackback_query) && !empty(self::$trackback_query)) {
      self::$trackback_query = "?" . self::$trackback_query;
    } else {
      self::$trackback_query = "";
    }

    ///////////////////////////////////////////////////////////////////////////
    // set port value

    if ((isset(self::$trackback_port) && !is_numeric(self::$trackback_port)) || (!isset(self::$trackback_port) || (empty(self::$trackback_port)))) {
      self::$trackback_port = 80;
    }

    ///////////////////////////////////////////////////////////////////////////
    // verify trackback socket

    //echo self::$trackback_host;

    //if(!preg_match('/\b([a-z]{2,3})(:[0-9]{1,5})?(\/[^ ]*)?\b/i', self::$trackback_host))
      //return false;
        //exit();

    ///////////////////////////////////////////////////////////////////////////
    // open trackback socket

    self::$trackback_socket = @fsockopen(self::$trackback_host, self::$trackback_port);

    ///////////////////////////////////////////////////////////////////////////
    // check if trackback target exists

    if (!is_resource(self::$trackback_socket)) {
      return false;
        exit();
    }

    ///////////////////////////////////////////////////////////////////////////
    // create trackback query

    self::$trackback_request = sprintf("url=%s&title=%s&blog_name=%s&excerpt=%s", self::$vendor_url, self::$vendor_title, self::$vendor_name, self::$vendor_excerpt);

    ///////////////////////////////////////////////////////////////////////////
    // send trackback

    fputs(self::$trackback_socket, "POST " . self::$trackback_path . self::$trackback_query . " HTTP/1.1\r\n");
    fputs(self::$trackback_socket, "Host: " . self::$trackback_host . "\r\n");
    fputs(self::$trackback_socket, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs(self::$trackback_socket, "Content-length: " . strlen(self::$trackback_request) . "\r\n");
    fputs(self::$trackback_socket, "Connection: close\r\n\r\n");
    fputs(self::$trackback_socket, self::$trackback_request);

    ///////////////////////////////////////////////////////////////////////////
    // gather results

    while (!feof(self::$trackback_socket)) {
      $response .= fgets(self::$trackback_socket, 128);
    }

    ///////////////////////////////////////////////////////////////////////////
    // close socket

    fclose(self::$trackback_socket);

    ///////////////////////////////////////////////////////////////////////////
    // verify response

    strpos($response, '<error>0</error>') ? $send = true : $send = false;

    ///////////////////////////////////////////////////////////////////////////
    // return

    return $send;
  }

 /**
   * _rex488_trackback_receive
   *
   * @return <type> boolean on false
   */

  public static function _rex488_trackback_receive()
  {
    ///////////////////////////////////////////////////////////////////////////
    // return if no possible trackback values are in post value
    
    if(!isset($_POST['url'])       || empty($_POST['url']))       return false;
    if(!isset($_POST['title'])     || empty($_POST['title']))     return false;
    if(!isset($_POST['blog_name']) || empty($_POST['blog_name'])) return false;
    if(!isset($_POST['excerpt'])   || empty($_POST['excerpt']))   return false;

    ///////////////////////////////////////////////////////////////////////////
    // read trackback to allocate

    $trackback_article = $_GET['trackback'];
      if(!isset($trackback_article) || empty($trackback_article))
        _rex488_FrontendTrackback::_rex488_trackback_response(1, 'Unable to allocate Trackback resource.');

    ///////////////////////////////////////////////////////////////////////////
    // read article id for corresponding trackback

    foreach(self::$article_cache_pathlist as $key => $article) {
      if(in_array($trackback_article, $article['url']))
        $trackback_aid = $key;
    }

    ///////////////////////////////////////////////////////////////////////////
    // exit if no article id could be allocated for this trackback

    if(!isset($trackback_aid) || empty($trackback_aid))
      _rex488_FrontendTrackback::_rex488_trackback_response(1, 'Unable to find corresponding aid for this trackback. Disconnecting.');

    ///////////////////////////////////////////////////////////////////////////
    // otherwise assign approved values

    self::$trackback_url     = stripslashes($_POST['url']);
    self::$trackback_title   = stripslashes($_POST['title']);
    self::$trackback_name    = stripslashes($_POST['blog_name']);
    self::$trackback_excerpt = stripslashes($_POST['excerpt']);
    self::$trackback_aid     = $trackback_aid;
    self::$trackback_type    = 'trackback';
    self::$trackback_time    = time();
    self::$trackback_ip      = $_SERVER['REMOTE_ADDR'];

    ///////////////////////////////////////////////////////////////////////////
    // try to write trackback and send success response

    if((boolean) self::_rex488_trackback_write() === true) {
      _rex488_FrontendTrackback::_rex488_trackback_response();
    } else {
      _rex488_FrontendTrackback::_rex488_trackback_response(1, 'Trackback already exists. Disconnecting.');
    }
  }

  /**
   * _rex488_trackback_write
   *
   * @return <type> boolean true on write success
   */

  private static function _rex488_trackback_write()
  {
    ///////////////////////////////////////////////////////////////////////////
    // dupecheck select existing trackback

    parent::$sql->setQuery(sprintf("SELECT %s FROM %s WHERE ( %s )",
      'comment_author, comment_website, comment_comment, comment_article',
        parent::$prefix . '488_comments',
          "comment_author = '" . self::$trackback_name . "' AND comment_website = '" . self::$trackback_url . "' AND comment_comment = '" . self::$trackback_excerpt . "' AND comment_article = '" . self::$trackback_aid . "'"));

    ///////////////////////////////////////////////////////////////////////////
    // if the trackback already exists return false

    if((int) parent::$sql->getRows() > 0)
      return false;

    ///////////////////////////////////////////////////////////////////////////
    // else write the trackback to database

    parent::$sql->table = parent::$prefix . '488_comments';
    parent::$sql->setValue('comment_author', self::$trackback_name);
    parent::$sql->setValue('comment_website', self::$trackback_url);
    parent::$sql->setValue('comment_comment', self::$trackback_excerpt);
    parent::$sql->setValue('comment_article', self::$trackback_aid);
    parent::$sql->setValue('comment_type', self::$trackback_type);
    parent::$sql->setValue('create_date', self::$trackback_time);
    parent::$sql->setValue('create_ip', self::$trackback_ip);
    parent::$sql->setValue('status', 1);

    ///////////////////////////////////////////////////////////////////////////
    // if trackback was successfully written register extension point

    if(parent::$sql->insert() === true)
    {
      $trackback = rex_register_extension_point('REX488_TRACKBACK_ADDED', parent::$sql, array(
        'comment_author'  => self::$trackback_name,
        'comment_website' => self::$trackback_url,
        'comment_comment' => self::$trackback_excerpt,
        'comment_article' => self::$trackback_aid,
        'comment_type'    => self::$trackback_type,
        'create_date'     => self::$trackback_time,
        'create_ip'       => self::$trackback_ip
      ));

      return true;
    }
  }

  /**
   * _rex488_trackback_response
   *
   * @param <type> $error
   * @param <type> $message
   */

  private static function _rex488_trackback_response($error = 0, $message = '')
  {
    ///////////////////////////////////////////////////////////////////////////
    // create xml header

    header('Content-Type: text/xml; charset=utf-8');

    ///////////////////////////////////////////////////////////////////////////
    // check if we have an error or not

    if((boolean) $error) {
      echo '<?xml version="1.0" encoding="utf-8"?' . ">\n";
      echo "<response>\n";
      echo "<error>1</error>\n";
      echo "<message>" . $message . "</message>\n";
      echo "</response>";
    } else {
    	echo '<?xml version="1.0" encoding="utf-8"?' . ">\n";
    	echo "<response>\n";
    	echo "<error>0</error>\n";
    	echo "</response>";
    }

    ///////////////////////////////////////////////////////////////////////////
    // and exit after

    exit();
  }
}
?>