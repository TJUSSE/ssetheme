<?php if (!empty($title)) : ?>
  <h2><?php print $title; ?></h2>
<?php endif; ?>
<table class="table--peoplelist <?php print $class; ?>"<?php print $attributes; ?>>
  <?php if (!empty($caption)) : ?>
    <caption><?php print $caption; ?></caption>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_number => $columns): ?>
      <tr <?php if ($row_classes[$row_number]) { print 'class="' . $row_classes[$row_number] .'"';  } ?>>
        <?php foreach ($columns as $column_number => $item): ?>
          <td <?php if ($column_classes[$row_number][$column_number]) { print 'class="' . $column_classes[$row_number][$column_number] .'"';  } ?>>
            <?php print $item; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>