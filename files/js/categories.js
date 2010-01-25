/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
});