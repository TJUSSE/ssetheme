<?php

/**
 * 这边，子 term 的 parent tid 是父 term 的 tid
 * 特别地，第一层 term 的 parent tid 是 _machine_name
 * 且，"全部" term 的 tid 也是 _machine_name
 * 即，第一层 term 是“全部” term 的子元素
 * 从而在前端实现通用的父子级联选择
 */
function sse_subscription_print_items(&$items, $parent_tid = 0, $print_check_all = false) {
  static $checkbox_id = 0;
  print '<div class="subscription__topic-region">';
  if ($print_check_all) {
    $checkbox_id++;
    print '
      <span class="subscription__topic-item">
        <input type="checkbox" class="role-term" id="term_'.$checkbox_id.'" data-tid="'.$parent_tid.'" data-parent-tid="0" data-is-all>
        <label for="term_'.$checkbox_id.'">'.t('全部').'</label>
      </span>
    ';
  }
  foreach ($items as &$item) {
    $checkbox_id++;
    print '
      <span class="subscription__topic-item">
        <input type="checkbox" class="role-term" id="term_'.$checkbox_id.'" data-tid="'.$item->tid.'" data-parent-tid="'.$parent_tid.'"'.(count($item->children) > 0 ? ' data-is-all' : '').'>
        <label for="term_'.$checkbox_id.'">'.(count($item->children) > 0 ? t('全部') : '').check_plain($item->name).'</label>
      </span>
    ';
    if (count($item->children) > 0) {
      sse_subscription_print_items($item->children, $item->tid);
    }
  }
  unset($item);
  print '</div>';
}

?>
<div class="subscription__page">
  <div class="intro-col__inner">
    <?php $subscription_current = 2; ?>
    <?php include __DIR__.'/inc--subscription--step.tpl.php'; ?>
  </div>
  <div class="subscription__form subscription__form--sub">
    <form action="<?php print $variables['post_action'] ?>" method="POST">
    <p>正在为 <?php print check_plain($variables['email']); ?> 设置订阅，请选择群体和分类：</p>
    <?php foreach ($variables['topics'] as $topic): ?>
    <div class="subscription__form--section" data-topic="<?php print $topic['machine_name']; ?>">
      <h3><?php print check_plain($topic['title']); ?></h3>
      <?php print sse_subscription_print_items($topic['tree'], '_'.$topic['machine_name'], $topic['machine_name'] === 'notice_category'); ?>
    </div>
    <?php endforeach; ?>
    <div class="form__line center">
      <input type="submit" class="sse_button" value="保存订阅选项">
      <?php if ($variables['cancel_action']) : ?>
      <a href="<?php print $variables['cancel_action']; ?>" class="sse_action_link">取消订阅</a>
      <?php endif; ?>
    </div>
    </form>
  </div>
</div>
