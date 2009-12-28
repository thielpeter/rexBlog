/*
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
  jQuery('a').removeAttr('accesskey');
  jQuery('a').removeAttr('tabindex');
	
  jQuery("#rexblog-categories").tableDnD(
  {
    onDragClass: "category-drag",
    dragHandle: "priority-handle",
    onDragStart: function(table, row)
    {
      //jQuery("#rexblog-categories td").css('opacity', 0.25);
    },
    onDragMove: function(table, row)
    {
      //jQuery("#rexblog-categories tr.category-drag td").css('opacity', 1);
    },
    onDrop: function(table, row)
    {
      var rows = table.tBodies[0].rows;
      var categories = "";
			
      for (var i = 0; i < rows.length; i++)
      {
        category = rows[i].id;
        category = category.replace('rex-category-', '');
        categories += category + "~";
      }

      //jQuery("#rexblog-categories td").fadeTo(250, 0.25);
      jQuery("#rexblog-categories td.priority-handle").addClass('processing');
      jQuery.post('index.php?page=rexblog&subpage=categories', {
        sort_categories: "sort",
        categories_array: categories
      },
      function(data)
      {
        setTimeout(function()
        {
          //jQuery("#rexblog-categories td").fadeTo(250, 1);
          jQuery("#rexblog-categories td.priority-handle").removeClass('processing');
        }, 1000);
      })
    }
  });
});