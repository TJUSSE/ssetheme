<?php

// 按月分组，并三个一行

$sse_groups = [];

if ($GLOBALS['language']->language === 'zh-hans') {
  $date_format = 'Y 年 n 月';
} else {
  $date_format = 'F, Y';
}

foreach ($rows as $id => $row) {
  $sse_current_year_month = date($date_format, $view->result[$id]->node_created);
  if (!isset($sse_groups[$sse_current_year_month])) {
    $sse_groups[$sse_current_year_month] = [];
  }
  $sse_groups[$sse_current_year_month][] = $row;
}

foreach ($sse_groups as $group_name => $items) {
  $sse_groups[$group_name] = array_chunk($items, 3);
}

?>
<?php foreach ($sse_groups as $group_name => $chunks): ?>
  <h2 class="news-list__month"><?php print $group_name; ?></h2>
  <?php foreach ($chunks as $chunk): ?>
  <div class="news-list__row clearfix">
    <?php foreach ($chunk as $row): ?>
      <div class="news-list__col">
        <div class="news-list__col-inner clearfix">
          <?php print $row; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
<?php endforeach; ?>
