<div class="calendar-style-list__date">
  <?php print $fields['created']->content; ?>
</div>
<div class="calendar-style-list__main">
  <h4 class="notice-list__title"><?php print $fields['title']->content; ?></h4>
  <div class="notice-list__extra">
  <?php
  foreach (['field_notice_deadline', 'field_notice_category', 'field_notice_target'] as $id) {
    if (!isset($fields[$id])) {
      continue;
    }
    $field = $fields[$id];
    
    print $field->wrapper_prefix;
    print $field->label_html;
    print $field->content;
    print $field->wrapper_suffix;
  }
  ?>
  </div>
</div>
