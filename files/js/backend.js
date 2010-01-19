/**
 * Copyright (c) 2009, mediastuttgart werbeagentur, http://www.mediastuttgart.de
 *
 * Diese Datei steht unter der MIT-Lizenz. Der Lizenztext befindet sich in der
 * beiliegenden Lizenz Datei. Alternativ kann der Lizenztext auch unter
 * folgenden Web-Adressen eingesehen werden.
 *
 * http://www.opensource.org/licenses/mit-license.php
 * http://de.wikipedia.org/wiki/MIT-Lizenz
 */

jQuery(document).ready(function()
{
  ///////////////////////////////////////////////////////////////
  // categories form validation
 
  var category_validator = jQuery("#rex-form-categories").validate(
  {
    rules: {
      title: {
        required: true,
        minlength: 3
      }
    },
    messages: {
      title: {
        required: "Die Bezeichnung der Kategorie darf nicht leer sein.",
        minlength: jQuery.format("Die Bezeichnung der Kategorie muss mindestens {0} Zeichen lang sein.")
      }
    },
    highlight: function(element, errorClass)
    {
      jQuery(element).addClass(errorClass);
      jQuery(element.form).find("label[for=" + element.id + "]").addClass('label-error');
      jQuery(element.form).find("label[for=" + element.id + "]").parent().parent().addClass(errorClass);
      jQuery('.rex-validate-message').css('display', 'block');
    },
    unhighlight: function(element, errorClass)
    {
      jQuery(element).removeClass(errorClass);
      jQuery(element.form).find("label[for=" + element.id + "]").removeClass('label-error');
      jQuery(element.form).find("label[for=" + element.id + "]").parent().parent().removeClass(errorClass);
      jQuery('.rex-validate-message').css('display', 'none');
    }
  });

  jQuery('.reset').click(function()
  {
    category_validator.resetForm();
    jQuery('.rex-validate-message').css('display', 'none');
    jQuery('#rex-form-categories').find('*').removeClass('error');
    jQuery('#rex-form-categories').find('*').removeClass('label-error');
  });


  ///////////////////////////////////////////////////////////////
  // categories sorting
	
  jQuery("#rex-categories").tableDnD(
  {
    onDragClass: "drag",
    dragHandle: "handle",
    onDrop: function(table, row)
    {
      var rows = table.tBodies[0].rows;
      var categories = "";
      var category = "";
			
      for (var i = 0; i < rows.length; i++) {
        category = rows[i].id;
        category = category.replace('rex-category-', '');
        categories += category + "~";
      }

      jQuery("#rex-categories").addClass('processing');
      jQuery.post('index.php?page=rexblog&subpage=categories', {
        output: "false",
        func: "sort",
        categories: categories
      },
      function(data) {
        setTimeout(function() {
          jQuery("#rex-categories").removeClass('processing');
        }, 1000);
      })
    }
  });

  ///////////////////////////////////////////////////////////////
  // articles form validation

  var article_validator = jQuery("#rex-form-articles").validate(
  {
    rules: {
      title: {
        required: true,
        minlength: 3
      },
      article_post: {
        required: true
      }
    },
    messages: {
      title: {
        required: "Die Bezeichnung des Artikels darf nicht leer sein.",
        minlength: jQuery.format("Die Bezeichnung des Artikels muss mindestens {0} Zeichen lang sein.")
      },
      article_post: {
        required: "Der Beitrag des Artikels darf nicht leer sein."
      }
    },
    highlight: function(element, errorClass)
    {
      jQuery(element).addClass(errorClass);
      jQuery(element.form).find("label[for=" + element.id + "]").addClass('label-error');
      jQuery(element.form).find("label[for=" + element.id + "]").parent().parent().addClass(errorClass);
      jQuery('.rex-validate-message').css('display', 'block');
    },
    unhighlight: function(element, errorClass)
    {
      jQuery(element).removeClass(errorClass);
      jQuery(element.form).find("label[for=" + element.id + "]").removeClass('label-error');
      jQuery(element.form).find("label[for=" + element.id + "]").parent().parent().removeClass(errorClass);
      jQuery('.rex-validate-message').css('display', 'none');
    }
  });

  jQuery('.reset').click(function()
  {
    article_validator.resetForm();
    jQuery('.rex-validate-message').css('display', 'none');
    jQuery('#rex-form-articles').find('*').removeClass('error');
    jQuery('#rex-form-articles').find('*').removeClass('label-error');
  });

});