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

abstract class _rex721_FrontendSocial extends _rex488_FrontendBase
{
  public static $social_links;

  private static $the_article_id;
  private static $the_article_title;
  private static $the_article_permlink;

  /**
   * _rex721_read_social_link
   *
   * @param <type> $params
   */

  public static function _rex721_read_social_link($params)
  {
    // define article params

    self::$the_article_id       = $params['id'];
    self::$the_article_title    = $params['title'];
    self::$the_article_permlink = $params['article_permlink'];

    // include social template file
    
    if(!empty(self::$social_links[self::$the_article_id]))
      include _rex721_PATH . 'templates/frontend/template.article.social.phtml';
  }

  /**
   * _rex721_the_social_links
   * 
   * @global <type> $REX
   * @return <type>
   */

  public static function _rex721_the_social_links()
  {
    global $REX;

    // check if shortened links are enabled
    
    if((boolean) $REX['ADDON']['rexblog_social']['shortener']['enabled'] === true)
    {
      // define cache file location

      $cache_file = _rex721_PATH . 'cache/_rex721_' . self::$the_article_id . '.social';
      
      // loop through social links
      
      foreach(self::$social_links[self::$the_article_id] as $social_link)
      {
        if(!file_exists($cache_file))
        {
          // define api url and request

          $vendor  = $REX['ADDON']['rexblog_social']['shortener']['vendor'];
          $request = $REX['ADDON']['rexblog_social']['shortener']['request'];

          // build api request

          $request = preg_replace('/%USERNAME%/', $REX['ADDON']['rexblog_social']['shortener']['username'], $request);
          $request = preg_replace('/%APIKEY%/', $REX['ADDON']['rexblog_social']['shortener']['apikey'], $request);
          $request = preg_replace('/%URL%/', $REX['SERVER'] . self::$the_article_permlink, $request);

          // create curl handle

          $handle = curl_init($vendor);

          // set curl options

          curl_setopt($handle, CURLOPT_POST, 1);
          curl_setopt($handle, CURLOPT_POSTFIELDS, $request);
          curl_setopt($handle, CURLOPT_HEADER, 0);
          curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

          // receive the response

          $response = curl_exec($handle);

          // close curl handle

          curl_close($handle);

          // build shortened social link

          $social_link = preg_replace('/%URL%/', $response, $social_link);
          $social_link = preg_replace('/%TITLE%/', self::$the_article_title, $social_link);

          // save social link to cache file

          rex_put_file_contents($cache_file, $response);

          // put things together

          $output .= $social_link;
          
        } else {

          // read cache file

          $cached_link = rex_get_file_contents($cache_file);

          // build shortened social link

          $social_link = preg_replace('/%URL%/', $cached_link, $social_link);
          $social_link = preg_replace('/%TITLE%/', self::$the_article_title, $social_link);

          // put things together

          $output .= $social_link;
        }
      }
    } else {

      // loop through social links

      foreach(self::$social_links[self::$the_article_id] as $social_link)
      {
        // replace link vars
        
        $social_link = preg_replace('/%URL%/', $REX['SERVER'] . self::$the_article_permlink, $social_link);
        $social_link = preg_replace('/%TITLE%/', self::$the_article_title, $social_link);
        
        // put things together
        
        $output .= $social_link;
      }
    }
    
    // return the ouput
    
    return $output;
  }
}
?>