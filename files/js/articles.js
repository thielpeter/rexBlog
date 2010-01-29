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
  // articles form validation
  
  jQuery.validator.messages.required = "";

  jQuery("#rex488-form-articles").validate(
  {
    rules : {
      title : {
        required : true
      },
      "rex488_article_categories[]" : {
        required : true
      }
    },
    errorPlacement : function(error, element)
    {
    },
    highlight : function(element, errorClass)
    {
      jQuery(element).parents('div.rex488-form-row').addClass(errorClass);
        jQuery('div.rex488-validate-message').css('display', 'block');
    },
    unhighlight : function(element, errorClass)
    {
      jQuery(element).parents('div.rex488-form-row').removeClass(errorClass);
        jQuery('div.rex488-validate-message').css('display', 'none');
    }
  });

  ///////////////////////////////////////////////////////////////
  // initiate rexblog core

  Rexblog.Init();

});

/////////////////////////////////////////////////////////////
// rexblog core

Rexblog =
{
  plugin_index : 0,

  /////////////////////////////////////////////////////////////
  // function to init rexblog core

  Init : function()
  {
    /////////////////////////////////////////////////////////////
    // define internal

    Rexblog.plugin_index        = jQuery('div.rex488-form-sortable').size();

    /////////////////////////////////////////////////////////////
    // define vars

    var permanent_link          = jQuery('input#_rex488_permanent_link');
    var permanent_link_readonly = jQuery('input#_rex488_permanent_link_readonly')

    /////////////////////////////////////////////////////////////
    // permanent link handling

    permanent_link.css('display', 'none');
    permanent_link_readonly.css('color', '#999');
    
    permanent_link_readonly.bind('focus', function() {
      permanent_link_readonly.css('display', 'none');
        permanent_link.css('display', 'block').focus();
    });

    permanent_link.bind('blur', function()
    {
      permanent_link_readonly.css('display', 'block');
      permanent_link.css('display', 'none');
     
     if(permanent_link.val() == "") {
        permanent_link_readonly.val(Rexblog.ParseArticleName(jQuery('input#title').val()) + '.html');
      } else {
        permanent_link_readonly.val(Rexblog.ParseArticleName(permanent_link.val()) + '.html');
      }
    });

    if(permanent_link.val() == "") {
      permanent_link_readonly.val(Rexblog.ParseArticleName(jQuery('input#title').val()) + '.html');
    }
  },

  /////////////////////////////////////////////////////////////
  // function to raise the plugin index

  RaisPluginIndex : function()
  {
    Rexblog.plugin_index++;
  },

  /////////////////////////////////////////////////////////////
  // function to return parsed article name

  ParseArticleName : function(name)
  {
    name = name.toLowerCase();

    name = name.replace(/\s+/g, '-');
    name = name.replace(/ä/g, 'ae');
    name = name.replace(/ö/g, 'oe');
    name = name.replace(/ü/g, 'ue');
    name = name.replace(/Ä/g, 'ae');
    name = name.replace(/Ö/g, 'oe');
    name = name.replace(/Ü/g, 'ue');
    name = name.replace(/ß/g, 'ss');
    name = name.replace(/&szlig;/g, 'ss');
    name = name.replace(/&/g, 'und');
    name = name.replace(/&amp;/g, 'und');

    return name;
  }
}

/////////////////////////////////////////////////////////////
// rexblog article core

Rexblog.Article =
{
  output_content : false,

  /////////////////////////////////////////////////////////////
  // function to toggle plugin settings

  Settings : function(element)
  {
    var container = jQuery(element).parents('div.rex488-form-row');

    if(jQuery(container).find('p.rex488-form-checkbox').is(':visible')) {
      jQuery(container).find('p.rex488-form-checkbox').slideUp(250);
        return false;
    }

    if(jQuery('p.rex488-form-checkbox:visible').size() > 0) {
      jQuery('p.rex488-form-checkbox:visible').slideUp(250);
      setTimeout(function(){
        jQuery(container).find('p.rex488-form-checkbox').slideDown(250);
      }, 250)
    } else {
      jQuery(container).find('p.rex488-form-checkbox').slideDown(250);
    }
    
    return false;
  },

  /////////////////////////////////////////////////////////////
  // function to toggle article extra settings
  
  ToggleExtraSettings: function(element, event)
  {
    event.preventDefault();

    var selector  = jQuery(element).attr('rel');
    var item      = jQuery('div.rex488_meta_' + selector);

    if(item.is(':visible')) {
      item.animate({opacity: 0, height: 'toggle'}, {duration: 500});
        return false;
    }

    if(jQuery('div.rex488_extra_settings:visible').size() > 0)
    {
      jQuery('div.rex488_extra_settings:visible').animate({opacity: 0, height: 'toggle'}, {duration: 500});
      setTimeout(function(){
        item.animate({opacity: 1, height: 'toggle'}, {duration: 500});
      }, 500)
    } else {
      item.animate({opacity: 1, height: 'toggle'}, {duration: 500});
    }

    return false;
  },
  
  /////////////////////////////////////////////////////////////
  // function to add plugin slice

  AddSlice : function(element)
  {
    var plugin  = jQuery(element).val();
    
    if(plugin == "") return false;

    Rexblog.RaisPluginIndex();

    jQuery.post("index.php?page=rexblog&subpage=articles",
    {
      output  : "false",
      func    : "plugin",
      element : plugin,
      index   : Rexblog.plugin_index
    },
    function(data)
    {
      jQuery(element).parents('div.rex488-form-plugin-select').after(data);
        jQuery(element).val('');
    });

    return false;
  },

  /////////////////////////////////////////////////////////////
  // function to delete plugin slice

  DeleteSlice : function(element)
  {
    if(confirm('Löschen?'))
      jQuery(element).parents('div.rex488-form-row').animate({ height : 'toggle', opacity : 0}, 500, function() { jQuery(this).remove(); });
  },

  /////////////////////////////////////////////////////////////
  // function to move plugin slice up

  MoveSliceUp : function(element)
  {
    var curr = jQuery(element).parents('div.rex488-form-row');
    var prev = jQuery(element).parents('div.rex488-form-row').prev();
    var move = jQuery(element).parents('div.rex488-form-row').prev('.rex488-form-sortable').length;

    if(prev.attr('class') == "") {
      prev = jQuery(element).parents('div.rex488-form-row').prev().prev().prev().prev().prev();
      move = jQuery(element).parents('div.rex488-form-row').prev().prev().prev().prev().prev('.rex488-form-sortable').length;
    }

    if(move)
    {
      jQuery('textarea.rex488-form-tinymce').each(function(){
        tinyMCE.execCommand('mceRemoveControl', false, jQuery(this).attr('id'));
      })

      jQuery(prev).insertAfter(curr);

      jQuery('textarea.rex488-form-tinymce').each(function(){
        tinyMCE.execCommand('mceAddControl', false, jQuery(this).attr('id'));
      })
    }
  },

  /////////////////////////////////////////////////////////////
  // function to move plugin slice down

  MoveSliceDown : function(element)
  {
    var curr = jQuery(element).parents('div.rex488-form-row');
    var next = jQuery(element).parents('div.rex488-form-row').next();
    var move = jQuery(element).parents('div.rex488-form-row').next('.rex488-form-sortable').length;

    if(next.attr('class') == "") {
      next = jQuery(element).parents('div.rex488-form-row').next().next().next().next().next();
        move = jQuery(element).parents('div.rex488-form-row').next().next().next().next().next('.rex488-form-sortable').length;
    }

    if(move)
    {
      jQuery('textarea.rex488-form-tinymce').each(function(){
        tinyMCE.execCommand('mceRemoveControl', false, jQuery(this).attr('id'));
      })

      jQuery(next).insertBefore(curr);

      jQuery('textarea.rex488-form-tinymce').each(function(){
        tinyMCE.execCommand('mceAddControl', false, jQuery(this).attr('id'));
      })

    }
  },

  /////////////////////////////////////////////////////////////
  // function to move plugin slice down

  ForceAsTeaser : function(element)
  {
    console.log(jQuery('input.rex488-form-checkbox:checked').size());
    
    if(jQuery('input.rex488-form-checkbox:checked').size() > 1)
    {
      alert('Es kann nur ein Plugin als Einleitung selektiert sein.')
        jQuery(element).removeAttr('checked');
          return false;
    }

    return false;
  }
}