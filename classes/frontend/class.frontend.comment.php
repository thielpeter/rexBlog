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

abstract class _rex488_FrontendComment extends _rex488_FrontendBase
{
  ///////////////////////////////////////////////////////////////////////////
  // declare internal variables

  protected static $the_comment_id;
  protected static $the_comment_author;
  protected static $the_comment_email;
  protected static $the_comment_website;
  protected static $the_comment_comment;
  protected static $the_comment_date;
  protected static $the_comment_errors;
  protected static $the_comment_article;
  protected static $the_comment_rating;
  protected static $the_comment_status;

  /**
   * the_comment_form
   * @param
   * @return
   * @throws
   */

  public static function the_comment_form()
  {
    global $REX;

    ///////////////////////////////////////////////////////////////////////////
    // unset variables before showing the form

    self::$the_comment_id      = null;
    self::$the_comment_author  = null;
    self::$the_comment_website = null;
    self::$the_comment_comment = null;
    self::$the_comment_date    = null;

    ///////////////////////////////////////////////////////////////////////////
    // trigger backend login

    $REX["LOGIN"] = new rex_backend_login(parent::$prefix . 'user');

    ///////////////////////////////////////////////////////////////////////////
    // if admin is logged in

    if($REX["LOGIN"]->checkLogin() === true)
    {
      self::$the_comment_author  = $REX["LOGIN"]->USER->getValue('name');
      self::$the_comment_email   = $REX['ERROR_EMAIL'];
      self::$the_comment_website = $REX['SERVER'];
    }

    ///////////////////////////////////////////////////////////////////////////
    // check if form template exists and include

    if(file_exists(_rex488_PATH . 'templates/frontend/template.comment.form.phtml'))
      include _rex488_PATH . 'templates/frontend/template.comment.form.phtml';
  }

  /**
   * the_comment_validation
   * @param
   * @return
   * @throws
   */

  public static function the_comment_validation()
  {
    ///////////////////////////////////////////////////////////////////////////
    // return if form is not posted

    if(!isset($_POST['the_comment_article'])) return;
    
    ///////////////////////////////////////////////////////////////////////////
    // prepare post values

    self::$the_comment_author  = (!empty($_POST['the_comment_author']))  ? $_POST['the_comment_author']  : '';
    self::$the_comment_email   = (!empty($_POST['the_comment_email']))   ? $_POST['the_comment_email']   : '';
    self::$the_comment_website = (!empty($_POST['the_comment_website'])) ? $_POST['the_comment_website'] : '';
    self::$the_comment_comment = (!empty($_POST['the_comment_comment'])) ? $_POST['the_comment_comment'] : '';
    self::$the_comment_article = (!empty($_POST['the_comment_article'])) ? $_POST['the_comment_article'] : '';

    ///////////////////////////////////////////////////////////////////////////
    // validate post values

    try
    {
      if(empty(self::$the_comment_author))
      {
        throw new Exception(_rex488_FrontendDesignator::form_auhtor());
      }
      if(empty(self::$the_comment_email))
      {
        throw new Exception(_rex488_FrontendDesignator::form_email_empty());
      }
      if(self::the_email_validation(self::$the_comment_email) === false)
      {
        throw new Exception(_rex488_FrontendDesignator::form_email_valid());
      }
      if(self::the_website_validation(self::$the_comment_website) === false)
      {
        throw new Exception(_rex488_FrontendDesignator::form_website_valid());
      }
      if(empty(self::$the_comment_comment))
      {
        throw new Exception(_rex488_FrontendDesignator::form_comment());
      }

      ///////////////////////////////////////////////////////////////////////////
      // when everything looks fine, write the comment to database

      self::write();

    }

    ///////////////////////////////////////////////////////////////////////////
    // catch exception on false

    catch(Exception $exception)
    {
      echo self::the_comment_form_error($exception->getMessage());
    }
  }

  /**
   * write
   * @param
   * @return
   * @throws
   */

  private static function write()
  {
    global $REX;

    $the_comment_rating = _rex488_FrontendCommentObserver::_rex488_observer_classify(self::$the_comment_comment);

    if($the_comment_rating == '0.5') {
      self::$the_comment_rating = '0';
    } else if($the_comment_rating < '0.5') {
      self::$the_comment_rating = '1';
    } else if($the_comment_rating > '0.5') {
      self::$the_comment_rating = '2';
    }

    if((int) $REX['ADDON']['comment']['rexblog']['status'] === 1) {
      if($the_comment_rating == '0.5') {
        self::$the_comment_status = '0';
      } else if($the_comment_rating < '0.5') {
        self::$the_comment_status = '1';
      } else if($the_comment_rating > '0.5') {
        self::$the_comment_status = '0';
      }
    } else {
      if($the_comment_rating == '0.5') {
        self::$the_comment_rating = '0';
      } else if($the_comment_rating < '0.5') {
        self::$the_comment_rating = '0';
      } else if($the_comment_rating > '0.5') {
        self::$the_comment_rating = '2';
      }
      self::$the_comment_status = '0';
    }

    parent::$sql->table = parent::$prefix . '488_comments';
    parent::$sql->setValue('comment_author', self::$the_comment_author);
    parent::$sql->setValue('comment_email', self::$the_comment_email);
    parent::$sql->setValue('comment_website', self::$the_comment_website);
    parent::$sql->setValue('comment_comment', self::$the_comment_comment);
    parent::$sql->setValue('comment_article', self::$the_comment_article);
    parent::$sql->setValue('comment_type', 'comment');
    parent::$sql->setValue('create_date', time());
    parent::$sql->setValue('create_ip', $_SERVER['REMOTE_ADDR']);
    parent::$sql->setValue('rating', self::$the_comment_rating);
    parent::$sql->setValue('status', self::$the_comment_status);

    if($REX["LOGIN"]->checkLogin() === true) {
      parent::$sql->setValue('create_admin', 1);
    }

    if(parent::$sql->insert() === true)
    {
      $comment = rex_register_extension_point('REX488_COMMENT_ADDED', parent::$sql, array(
        'comment_author'  => self::$the_comment_author,
        'comment_email'   => self::$the_comment_email,
        'comment_website' => self::$the_comment_website,
        'comment_comment' => self::$the_comment_comment,
        'comment_article' => self::$the_comment_article,
        'create_date'     => time(),
        'create_ip'       => $_SERVER['REMOTE_ADDR']
      ));

      header('location: ' . $REX['SERVER'] . parent::parse_article_resource(parent::$url) . '#comment-' . parent::$sql->last_insert_id);
    }
  }

  /**
   * read
   * @param
   * @return
   * @throws
   */

  public static function read($article)
  {
    $comments = parent::$sql->getArray(sprintf(
      "SELECT %s FROM %s WHERE ( %s ) ORDER BY %s",
      'id, comment_author, comment_website, comment_comment, create_date',
      parent::$prefix . '488_comments',
      "comment_article = '" . $article . "' AND status = '1'  AND rating < '2'",
      "create_date ASC"
    ));

    if(is_array($comments) && sizeof($comments) > 0)
    {
      foreach($comments as $key => $comment)
      {
        self::$the_comment_id      = $comment['id'];
        self::$the_comment_author  = $comment['comment_author'];
        self::$the_comment_website = $comment['comment_website'];
        self::$the_comment_comment = $comment['comment_comment'];
        self::$the_comment_date    = $comment['create_date'];

        include _rex488_PATH . 'templates/frontend/template.comment.phtml';
      }
    } else {
      return '<div class="_rex488_comment"><p>Dieser Artikel wurde noch nicht kommentiert.</p></div>';
    }
  }

   /**
   * the_comment_id
   * @param
   * @return
   * @throws
   */

  public static function the_comment_id()
  {
    return self::$the_comment_id;
  }

 /**
   * the_comment_author
   * @param
   * @return
   * @throws
   */

  public static function the_comment_author()
  {
    return self::$the_comment_author;
  }

  /**
   * the_comment_email
   * @param
   * @return
   * @throws
   */

  public static function the_comment_email()
  {
    return self::$the_comment_email;
  }

  /**
   * the_comment_date
   * @param
   * @return
   * @throws
   */

  public static function the_comment_date($format)
  {
    return date($format, self::$the_comment_date);
  }

  /**
   * the_comment_website
   * @param
   * @return
   * @throws
   */

  public static function the_comment_website()
  {
    return self::$the_comment_website;
  }

  /**
   * the_comment_comment
   * @param
   * @return
   * @throws
   */

  public static function the_comment_comment()
  {
    return self::$the_comment_comment;
  }

  /**
   * the_comment_form_error
   * @param
   * @return
   * @throws
   */

  public static function the_comment_form_error($error)
  {
    return sprintf('<%1$s class="error">%2$s</%1$s>', 'p', $error);
  }

  /**
   * the_email_validation
   * @param
   * @return
   * @throws
   */

  private static function the_email_validation()
  {
    $pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';

    if(preg_match($pattern, self::$the_comment_email)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * the_website_validation
   * @param
   * @return
   * @throws
   */

  private static function the_website_validation()
  {
    if(empty(self::$the_comment_website))
      return true;

    $pattern = '/\b(https?:\/\/(([0-9a-z-]+\.)+([a-z]{2,3}))(:[0-9]{1,5})?(\/[^ ]*)?)\b/i';

    if(preg_match($pattern, self::$the_comment_website)) {
      return true;
    } else {
      return false;
    }
  }
}
?>