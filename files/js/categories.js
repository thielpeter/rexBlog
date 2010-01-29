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

jQuery(document).ready(function()
{
  ///////////////////////////////////////////////////////////////
  // category form validation

  jQuery.validator.messages.required = "";

  jQuery("#rex488-form-categories").validate(
  {
    rules : {
      title : {
        required : true
      }
    },
    errorPlacement : function(error, element)
    {
    },
    highlight : function(element, errorClass)
    {
      jQuery(element).parents('div.rex-form-row').addClass(errorClass);
        jQuery('div.rex488-validate-message').css('display', 'block');
    },
    unhighlight : function(element, errorClass)
    {
      jQuery(element).parents('div.rex-form-row').removeClass(errorClass);
        jQuery('div.rex488-validate-message').css('display', 'none');
    }
  });

  ///////////////////////////////////////////////////////////////
  // categories sorting

  jQuery("#rex-categories").tableDnD(
  {
    onDragClass : "drag",
    dragHandle  : "handle",
    onDrop: function(table, row)
    {
      var rows       = table.tBodies[0].rows;
      var categories = "";
      var category   = "";

      for (var i = 0; i < rows.length; i++) {
        category   = rows[i].id;
        category   = category.replace('rex-category-', '');
        categories = categories + category + "~";
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
});