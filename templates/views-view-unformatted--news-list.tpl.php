<?php

// 按月分组，并三个一行

$sse_news_groups = [];

foreach ($rows as $id => $row) {
  $sse_current_year_month = date('Y 年 n 月', $view->result[$id]->node_created);
  if (!isset($sse_news_groups[$sse_current_year_month])) {
    $sse_news_groups[$sse_current_year_month] = [];
  }
  $sse_news_groups[$sse_current_year_month][] = ['id' => $id, 'row' => $row];
}

foreach ($sse_news_groups as $group_name => $items) {
  $sse_news_groups[$group_name] = array_chunk($items, 3);
}

?>
<?php foreach ($sse_news_groups as $group_name => $rows): ?>
  <h2 class="news-list__month"><?php print $group_name; ?></h2>
  <?php foreach ($rows as $row): ?>
  <div class="news-list__row clearfix">
    <?php foreach ($row as $item): ?>
      <div class="news-list__col">
        <div class="news-list__col-inner">
          <?php print $item['row']; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
<?php endforeach; ?>
