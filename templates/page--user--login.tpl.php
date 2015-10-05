<canvas class="page--login__bg"></canvas>
<div class="login__form">
  <div class="login__logo">
    <img src="<?php print sse_theme_asset_path() ?>/img/logo.png" alt="<?php print t('Home') ?>"/>
    <?php print $messages; ?>
  </div>
  <div class="login__dialog">
    <?php print render($page['content']); ?>
  </div>
</div>
