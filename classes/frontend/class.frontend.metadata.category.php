<?php

abstract class _rex488_FrontendMetadataCategory extends _rex488_FrontendMetadata
{
	private static $category_title;
	private static $category_keywords;
	private static $category_description;
	
	/**
	 * get_category_title
	 * 
	 * erzeugt den meta title für die aktuelle kategorie. falls erwünscht,
	 * kann mit hilfe des parameters der titel des startartikels vorangestellt werden.
	 * 
	 * @param		$prepend						<boolean>		artikletitel voranstellen 
	 * @param		$spacer							<string>		zeichenkette zwischen den titeln 
	 * @return	$category_keywords	<string>		meta keywords der kategorie
	 */
	
	public static function get_category_title($prepend, $spacer)
	{
		self::$category_title = parent::$category_path[parent::$category_id]['name'];
		
		 // falls erwünscht, kann mit hilfe des parameters der
		 // titel des startartikels vorangestellt werden.
		
		if($prepend === true)
		{
			self::$category_title = parent::get_blog_title() . $spacer . self::$category_title;
		}
		
		return self::$category_title;
	}
	
	/**
	 * category keywords
	 * 
	 * erzeugt die meta keywords für die aktuelle kategorie.
	 * falls die aktuelle kategorie keine meta keywords enthält,
	 * werden die meta keywords durch den startartikel des blogs erzeugt.
	 * 
	 * @return	$category_keywords	<string>	kategorie meta keywords
	 */
	
	public static function get_category_keywords()
	{
		self::$category_keywords = parent::$category_path[parent::$category_id]['keywords'];
		
		// falls die meta keywords keine informationen enthalten,
		// werden die daten durch den startartikel erzeugt.
		
		if(empty(self::$category_keywords))
		{
			self::$category_keywords = parent::get_blog_keywords();
		}
		
		return self::$category_keywords;
	}
	
	/**
	 * category description
	 * 
	 * erzeugt die meta description für die aktuelle kategorie.
	 * falls die aktuelle kategorie keine meta description enthält,
	 * wird die meta description durch den startartikel des blogs erzeugt.
	 * 
	 * @return	$category_description	<string>	kategorie meta description
	 */
	
	public static function get_category_description()
	{
		self::$category_description = parent::$category_path[parent::$category_id]['description'];
		
		// falls die meta description keine informationen enthalten,
		// werden die daten durch den startartikel erzeugt.
		
		if(empty(self::$category_description))
		{
			self::$category_description = parent::get_blog_description();
		}
		
		return self::$category_description;
	}
	
}

?>