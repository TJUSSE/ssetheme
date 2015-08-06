<!DOCTYPE html>
<html>
<head>
  <?php print $head; ?>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="theme-color" content="<?php print sse_get_section_color(sse_get_current_section()) ?>">
  <meta http-equiv="cleartype" content="on">
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <script src="<?php print sse_asset_path() ?>/js/vendor/modernizr-2.8.3.min.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php print sse_asset_path() ?>/js/vendor/selectivizr-1.0.3b.min.js"></script>
    <script src="<?php print sse_asset_path() ?>/js/vendor/respond-1.4.2.min.js"></script>
    <script src="<?php print sse_asset_path() ?>/js/vendor/calc.min.js"></script>
  <![endif]-->
  <script>
    WebFontConfig = {
      custom: {
        families: ['Open Sans:n3,i3,n6,i6'],
        urls: ['<?php print sse_asset_path() ?>/css/webfont.css']
      }
    };
  </script>
</head>
<body class="<?php print $classes; ?> body-section-<?php print sse_get_current_section() ?>" <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  <script src="<?php print sse_asset_path() ?>/js/vendor/webfont-1.5.10.js" async="true"></script>
  <?php print $scripts; ?>
</body>
</html>
