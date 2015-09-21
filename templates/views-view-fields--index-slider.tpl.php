<div class="index__slider--data-item" data-src="<?php print $fields['field_index_news_image']->content; ?>">
  <h2><a href="<?php print url(node_uri($row->field_field_index_news_dest[0]['raw']['entity'])['path']); ?>"><?php print $fields['title']->content; ?></a></h2>
  <?php print $fields['body']->content; ?>
</div>
