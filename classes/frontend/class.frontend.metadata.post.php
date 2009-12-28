<?php

abstract class _rex488_FrontendMetadataPost extends _rex488_FrontendMetadata
{
	private static $post_title;
	private static $post_keywords;
	private static $post_description;
	
	/**
	 * post keywords
	 * 
	 * erzeugt die meta keywords für den aktuellen beitrag.
	 * falls der aktuelle beitrag keine meta keywords enthält,
	 * werden die meta keywords durch den startartikel des blogs erzeugt.
	 * 
	 * @return	$post_title	<string>	meta titel des beitrags
	 */
	
	public static function get_post_title($prepend, $spacer)
	{
		self::$post_title = parent::$post_path[parent::$post_id]['title'];
		
		 // falls erwünscht, kann mit hilfe des parameters der
		 // titel des startartikels vorangestellt werden.
		
		if($prepend === true)
		{
			self::$post_title = parent::get_blog_title() . $spacer . self::$post_title;
		}
		
		return self::$post_title;
	}
	
	/**
	 * post keywords
	 * 
	 * erzeugt die meta keywords für den aktuellen beitrag.
	 * falls der aktuelle beitrag keine meta keywords enthält,
	 * werden die meta keywords durch den startartikel des blogs erzeugt.
	 * 
	 * @return	$post_keywords	<string>	beitrag meta keywords
	 */
	
	public static function get_post_keywords()
	{
		self::$post_keywords = parent::$post_path[parent::$post_id]['keywords'];
		
		// falls die meta keywords keine informationen enthalten,
		// werden die daten durch den startartikel erzeugt.
		
		if(empty(self::$post_keywords))
		{
			self::$post_keywords = parent::get_blog_keywords();
		}
		
		return self::$post_keywords;
	}
	
	/**
	 * post description
	 * 
	 * erzeugt die meta description für den aktuellen beitrag.
	 * falls der aktuelle beitrag keine meta description enthält,
	 * wird die meta description durch den startartikel des blogs erzeugt.
	 * 
	 * @return	$post_description	<string>	beitrag meta description
	 */
	
	public static function get_post_description()
	{
		self::$post_description = parent::$post_path[parent::$post_id]['description'];
		
		// falls die meta description keine informationen enthalten,
		// werden die daten durch den startartikel erzeugt.
		
		if(empty(self::$post_description))
		{
			self::$post_description = parent::get_blog_description();
		}
		
		return self::$post_description;
	}
	
}
?>