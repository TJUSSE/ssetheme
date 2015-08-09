  <header class="fp__header">
    <div class="content-container clearfix">
      <section class="fp__header__logo-container">
        <a href="<?php print $front_page ?>" class="header__logo"><img src="<?php print sse_asset_path() ?>/img/logo.png" alt="<?php print t('Home') ?>"/></a>
      </section>
      <nav class="fp__header__nav">
        <?php print sse_navigation_main_output(); ?>
      </nav>
    </div>
  </header>

  <section class="fp__slider">
    <?php print render($page['content']); ?>
  </section>
  
  <?php include __DIR__.'/footer.tpl.php' ?>
