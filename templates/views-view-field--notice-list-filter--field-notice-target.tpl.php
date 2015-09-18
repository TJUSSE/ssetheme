<?php

foreach ($row->field_field_notice_target as &$target) {
  if ($target['raw']['taxonomy_term']->field_taxonomy_target_list_hide[LANGUAGE_NONE][0]['value'] == 0) {
    print render($target['rendered']);
  }
}
unset($target);

?>
