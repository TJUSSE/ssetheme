<div class="relative-link__item">
  <i class="relative-link__icon icon-schedule"></i> 
  <?php
  print t('Published in @time', ['@time' => format_date($variables['entity']->created, 'date_only')]);
  ?>
</div>
