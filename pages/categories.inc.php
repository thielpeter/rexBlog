<?php

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

_rex488_BackendBase::get_instance();

// set new state for category

if(rex_request('func', 'string') == 'state')
{
  _rex488_BackendCategories::state();
}

// read categories from the current parent

$categories = _rex488_BackendCategories::read();

// echo rex_message('Die aktuelle Parent id lautet', 'rex-info', 'p');

if(rex_request('func', 'string') == "")
{

?>

<div class="rex-addon-output-v2">
  <form action="index.php?page=rexblog&subpage=categories" method="post">
    <table class="rex-table" id="rexblog-categories">
      <caption>Liste der Kategorien</caption>
      <colgroup>
        <col width="40" />
        <col width="40" />
        <col width="*" />
        <col width="40" />
        <col width="50" />
        <col width="50" />
        <col width="50" />
      </colgroup>
      <thead>
        <tr>
          <th class="rex-icon"><a class="rex-i-element rex-i-category-add" href="index.php?page=rexblog&subpage=categories&func=add&parent=<?php echo rex_request('parent', 'int'); ?>"><span class="rex-i-element-text">hinzufügen</span></a></th>
          <th>ID</th>
          <th>Kategorie</th>
          <th>Prio</th>
          <th colspan="3">Status/Funktion</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(is_array($categories) && count($categories) > 0)
        {
          foreach($categories as $key => $category)
          {
            $status_description = ($category['status'] == 1) ? 'online' : 'offline';
            $status_classname = ($category['status'] == 1) ? 'rex-online' : 'rex-offline';
        ?>
        <tr id="rex-category-<?php echo $category['id']; ?>">
          <td class="rex-icon"><a href="index.php?page=rexblog&subpage=categories&func=edit&id=<?php echo $category['id']; ?>&parent=<?php echo rex_request('parent', 'int'); ?>"><span class="rex-i-element rex-i-category"><span class="rex-i-element-text">Editieren</span></span></a></td>
          <td><?php echo $category['id']; ?></td>
          <td><a href="index.php?page=rexblog&subpage=categories&parent=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></td>
          <td class="priority-handle"></td>
          <td><a href="index.php?page=rexblog&subpage=categories&func=edit&id=<?php echo $category['id']; ?>&parent=<?php echo rex_request('parent', 'int'); ?>">ändern</a></td>
          <td><a href="index.php?page=rexblog&subpage=categories&delete=<?php echo $category['id']; ?>&parent=<?php echo rex_request('parent', 'int'); ?>">löschen</a></td>
          <td><a href="index.php?page=rexblog&subpage=categories&func=state&id=<?php echo $category['id']; ?>&parent=<?php echo rex_request('parent', 'int'); ?>" class="<?php echo $status_classname; ?>"><?php echo $status_description; ?></a></td>
        </tr>
        <?php
          }
        } else {
        ?>
        <tr>
          <td colspan="7" align="center">
            <p>In dieser Kategorie sind keine Unterkategorien vorhanden.</p>
          </td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </form>
</div>
<?php
}
?>