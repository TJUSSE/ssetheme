<?php

function sse_notice_filter_print_filters(&$items) {
  print '<div class="notice-filter__filters">';
  foreach ($items as &$item) {
    print '<div class="notice-filter__row"><div class="notice-filter__item">';
    print '<div class="notice-filter__item__text" data-id="' . $item->tid . '">' . check_plain($item->name) . '</div>';
    if (isset($item->children) && count($item->children) > 0) {
      sse_notice_filter_print_filters($item->children);
    }
    print '</div></div>';
  }
  unset($item);
  print '</div>';
}

?>
<div class="rightbar__module notice-filter-module">
<?php foreach ($variables['filters'] as &$filter): ?>
  <div class="notice-filter__block" data-id="<?php print check_plain($filter['meta']); ?>">
    <h3>筛选<?php print $filter['title'] ?></h3>
    <?php sse_notice_filter_print_filters($filter['tree']); ?>
  </div>
<?php endforeach; ?>
</div>
