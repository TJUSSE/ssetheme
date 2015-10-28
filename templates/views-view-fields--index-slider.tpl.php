<?php
$sse_index_has_hyperlink = isset($row->field_field_index_news_dest[0]) && !is_null($row->field_field_index_news_dest[0]);
$sse_index_has_body = isset($row->field_body) && count($row->field_body) > 0 && strlen($row->field_body[0]['raw']['value']) > 0;
?>
<div class="index__slider--data-item<?php if (!$sse_index_has_body) print ' without--body'; ?>" data-src="<?php print $fields['field_index_news_image']->content; ?>">
  <h2>
    <?php if ($sse_index_has_hyperlink) : ?><a href="<?php print url(node_uri($row->field_field_index_news_dest[0]['raw']['entity'])['path']); ?>"><?php endif; ?>
    <?php print $fields['title']->content; ?>
    <?php if ($sse_index_has_hyperlink) : ?></a><?php endif; ?>
  </h2>
  <?php if ($sse_index_has_body) print $fields['body']->content; ?>
</div>
