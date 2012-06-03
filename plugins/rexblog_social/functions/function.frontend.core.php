 <?php

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

function _rex721_append_social_links($params)
{
  return _rex721_FrontendSocial::_rex721_read_social_link($params);
}

function _rex721_the_social_links()
{
  return _rex721_FrontendSocial::_rex721_the_social_links();
}

?>