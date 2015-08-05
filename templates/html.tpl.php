<?php
/**
 * @file
 * Returns the HTML for the basic html structure of a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728208
 */
?><!DOCTYPE html>
<html>
<head>
  <?php print $head; ?>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="theme-color" content="#9FC1E3">
  <meta http-equiv="cleartype" content="on">
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <script src="<?php print $base_path . $path_to_sse; ?>/js/vendor/modernizr-2.8.3.min.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php print $base_path . $path_to_sse; ?>/js/vendor/selectivizr-1.0.3b.min.js"></script>
    <script src="<?php print $base_path . $path_to_sse; ?>/js/vendor/respond-1.4.2.min.js"></script>
    <script src="<?php print $base_path . $path_to_sse; ?>/js/vendor/calc.min.js"></script>
  <![endif]-->
  <script>
    WebFontConfig = {
      custom: {
        families: ['Open Sans:n3,i3,n6,i6'],
        urls: ['<?php print $base_path . $path_to_sse; ?>/css/webfont.css']
      }
    };
  </script>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php if ($skip_link_text && $skip_link_anchor): ?>
    <p id="skip-link">
      <a href="#<?php print $skip_link_anchor; ?>" class="element-invisible element-focusable"><?php print $skip_link_text; ?></a>
    </p>
  <?php endif; ?>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  <script src="<?php print $base_path . $path_to_sse; ?>/js/vendor/webfont-1.5.10.js" async="true"></script>
  <?php print $scripts; ?>
</body>
</html>
