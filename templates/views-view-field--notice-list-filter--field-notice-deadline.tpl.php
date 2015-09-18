<?php if ($row->field_field_notice_deadline_enabled[0]['raw']['value'] == 1) : ?>
  <?php
    $deadline = (int)$row->field_field_notice_deadline[0]['raw']['value'];
    if ($deadline >= time()) {
      $class = 'notice-list__deadline--not-passed';
      $remaining = floor(($deadline - time()) / 60 / 60 / 24);
    } else {
      $class = 'notice-list__deadline--passed';
      $remaining = -1;
    }
  ?>
  <span class="<?php print $class; ?>">
    截止时间: <?php print $output; ?>
    <?php if ($remaining >= 0): ?>
      <?php print t(", 剩余 @days 天", ['@days' => $remaining]); ?></span>
    <?php endif; ?>
  </span>
<?php endif; ?>
