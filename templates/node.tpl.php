<?php
/**
 * @file
 * Returns the HTML for a node.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728164
 */

$node_rightbar_class = 'node-no-rightbar';
if (isset($content['group_sidebar'])) {
  $node_rightbar_class = 'node--has-rightbar';
}

?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> <?php print $node_rightbar_class; ?> clearfix"<?php print $attributes; ?>>
  
  <div class="node__content">
    <?php if ($title_prefix || $title_suffix || $display_submitted || $unpublished || !$page && $title): ?>
      <header>
        <?php print render($title_prefix); ?>
        <?php if (!$page && $title): ?>
          <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
        <?php endif; ?>
        <?php print render($title_suffix); ?>

        <?php if ($display_submitted): ?>
          <p class="submitted">
            <?php print $user_picture; ?>
            <?php print $submitted; ?>
          </p>
        <?php endif; ?>

        <?php if ($unpublished): ?>
          <mark class="unpublished"><?php print t('Unpublished'); ?></mark>
        <?php endif; ?>
      </header>
    <?php endif; ?>
    
    <div class="node__body">
    <?php
      hide($content['comments']);
      hide($content['links']);
      hide($content['group_sidebar']);
      print render($content);
    ?>
    </div>
  </div>

  <?php if (isset($content['group_sidebar'])): ?>
    <div class="node__rightbar">
      <?php print render($content['group_sidebar']); ?>
    </div>
  <?php endif; ?>

</article>
