<div class="relative-link__item">
  <i class="relative-link__icon icon-turned_in"></i> 
  <?php

  $terms = taxonomy_term_load_multiple(
    array_values(array_unique(
      array_map(function ($term) {
        return $term['tid'];
      }, array_merge(
        $variables['entity']->field_notice_target[LANGUAGE_NONE],
        $variables['entity']->field_notice_category[LANGUAGE_NONE]
      ))
    ))
  );

  // 隐藏的项
  $terms = array_filter($terms, function ($term) {
    if (!isset($term->field_taxonomy_target_hidden)) {
      return true;
    }
    return $term->field_taxonomy_target_hidden[LANGUAGE_NONE][0]['value'] == 0;
  });

  // 排序：先按照词汇表名称排序，再按照权重排序
  usort($terms, function ($a, $b) {
    if ($a->vocabulary_machine_name !== $b->vocabulary_machine_name) {
      return strcmp($a->vocabulary_machine_name, $b->vocabulary_machine_name);
    } else {
      return $a->weight - $b->weight;
    }
  });

  foreach ($terms as &$term) {
    print l($term->name, entity_uri('taxonomy_term', $term)['path'], [
      'attributes' => [
        'class' => 'notice__tag'
      ]
    ]);
  }
  unset($term);

  ?>
</div>
