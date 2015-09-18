<?php

// 按月分组

$sse_groups = [];

foreach ($rows as $id => $row) {
  $sse_current_year_month = date('Y 年 n 月', $view->result[$id]->node_created);
  if (!isset($sse_groups[$sse_current_year_month])) {
    $sse_groups[$sse_current_year_month] = [];
  }
  $sse_groups[$sse_current_year_month][] = $row;
}

?>
<?php foreach ($sse_groups as $group_name => $rows): ?>
  <h2 class="notice-list__month"><?php print $group_name; ?></h2>
  <?php foreach ($rows as $row): ?>
  <div class="notice-list__row clearfix"><?php print $row; ?></div>
  <?php endforeach; ?>
<?php endforeach; ?>
