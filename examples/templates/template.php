<?php
	
	// set locale
	setlocale(LC_TIME, 'de_DE.UTF-8');
	
	// start session
	session_start();
	
	// load rexblog base settings
	_rex488_base_loader();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $REX['SERVER']; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo _rex488_the_meta_title(true, ' / '); ?></title>
<meta name="Keywords" content="<?php echo _rex488_the_meta_keywords(); ?>" />
<meta name="Description" content="<?php echo _rex488_the_meta_description(); ?>" />
<link rel="stylesheet" href="<?php echo $REX['SERVER']; ?>files/application.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>
<body>
<!-- wrapper -->
<div id="_rex488_wrapper">
  
  <!-- navigation -->
  <ul id="page-categories">
  <?php
	
		$categories = OOCategory::getRootCategories();
		
		foreach($categories as $category) {
			if($category->isOnline())
				echo '<li><a href="' . $category->getUrl() . '">' . $category->getName() . '</a></li>';
		}
		
	?>
  </ul>
  <!-- /navigation -->
  
  <!-- clearfix -->
  <div class="clearfix"></div>
  <!-- /clearfix -->
  
  <!-- sidebar -->
  <div id="_rex488_sidebar">
    <!-- categories -->
	  <h1>Kategorien</h1>
	  <ul id="_rex488_categories">
	    <?php echo _rex488_the_categories(false, true); ?>
	  </ul>
	  <!-- /categories -->
	  <!-- archive -->
	  <h1>Archiv</h1>
	  <ul id="_rex670_archive">
  	  <?php echo _rex670_the_archive(); ?>
	  </ul>
  	<!-- /archive -->
	  <!-- tagcloud -->
	  <h1>Schlagwörter</h1>
	  <ul id="_rex717_tagcloud">
  	  <?php echo _rex717_the_tagcloud(); ?>
	  </ul>
  	<!-- /tagcloud -->
  </div>
  <!-- /sidebar -->
  
  <!-- content -->
  <div id="_rex488_content">
		<?php echo _rex488_the_content(3); ?>
    <!-- pagination -->
    <?php echo _rex488_the_pagination(); ?>
    <!-- /pagination -->
  </div>
  <!-- /content -->
  
  <!-- clearfix -->
  <div class="clearfix"></div>
  <!-- /clearfix -->
  
</div>
<!-- /wrapper -->
</body>
</html>