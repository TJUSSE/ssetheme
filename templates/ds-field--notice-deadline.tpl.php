<div class="relative-link__item">
  <i class="relative-link__icon icon-notifications"></i> 
  <?php
  print t('Deadline is @time', ['@time' => format_date($variables['entity']->field_notice_deadline[LANGUAGE_NONE][0]['value'], 'date_only')]);
  ?>
</div>
