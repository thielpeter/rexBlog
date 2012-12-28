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
  // initiate rexblog core

  Rexblog.Init();

});

/////////////////////////////////////////////////////////////
// rexblog core

Rexblog =
{
  CommentIndex : 0,

  /////////////////////////////////////////////////////////////
  // function to init rexblog core

  Init : function()
  {
    Rexblog.CheckboxToggle = false;
  },

  /////////////////////////////////////////////////////////////
  // function to toggle checkboxes

  ToggleCommentCheckbox : function(event)
  {
    if(Rexblog.CheckboxToggle) {
      jQuery("input[type=checkbox]").removeAttr("checked");
        Rexblog.CheckboxToggle = false;
    } else {
      jQuery("input[type=checkbox]").attr("checked", "checked");
        Rexblog.CheckboxToggle = true;
    }
  }
}
