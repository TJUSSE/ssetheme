<?php

// Add sidebar classes so that we can apply the correct width in css.
if (($left && !$right) || ($right && !$left)) {
  $classes = 'intro-layout--one-col ' . $classes;
} else {
  $classes = 'intro-layout--two-col ' . $classes;
}
?>
<<?php print $layout_wrapper; print $layout_attributes; ?> class="<?php print $classes;?> clearfix">
  <?php if ($left): ?>
    <<?php print $left_wrapper ?> class="intro__left<?php print $left_classes; ?>">
      <div class="intro-col__inner">
        <?php print $left; ?>
      </div>
    </<?php print $left_wrapper ?>>
  <?php endif; ?>

  <?php if ($right): ?>
    <<?php print $right_wrapper ?> class="intro__right<?php print $right_classes; ?>">
      <div class="intro-col__inner">
        <?php print $right; ?>
      </div>
    </<?php print $right_wrapper ?>>
  <?php endif; ?>
</<?php print $layout_wrapper ?>>

<?php if (!empty($drupal_render_children)): ?>
  <?php print $drupal_render_children ?>
<?php endif; ?>
