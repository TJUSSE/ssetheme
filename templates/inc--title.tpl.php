<?php print sse_breadcrumb_output(); ?>
<?php if ($title): ?>
<h1 class="page__title title" id="page-title">
  <?php print $title; ?>
  <?php if (isset($node) && $node->type === 'teacher_content' && !empty($node->field_teacher_pinyin) && $GLOBALS['language']->language === 'zh-hans'): ?>
    <small class="teacher__pinyin"><?php print $node->field_teacher_pinyin[LANGUAGE_NONE][0]['safe_value']; ?></small>
  <?php endif; ?>
</h1>
<?php endif; ?>
