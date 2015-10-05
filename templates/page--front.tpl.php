  <header class="fp__header">
    <div class="content-container clearfix">
      <section class="fp__header__logo-container">
        <a href="<?php print $front_page ?>" class="header__logo"><img src="<?php print sse_theme_asset_path() ?>/img/logo.png" alt="<?php print t('Home') ?>"/></a>
      </section>
      <nav class="fp__header__nav">
        <?php print sse_theme_navigation_main_output(); ?>
      </nav>
    </div>
  </header>

  <section class="fp__slider">
    <div class="index__slider">
      <div class="index__slider__spinner index__slider__spinner--show">
        <div class="sk-folding-cube">
          <div class="sk-cube1 sk-cube"></div>
          <div class="sk-cube2 sk-cube"></div>
          <div class="sk-cube4 sk-cube"></div>
          <div class="sk-cube3 sk-cube"></div>
        </div>
      </div>
      <div class="index__slider__stage">
        <div class="index__slider__image-area"></div>
        <div class="index__slider__desc">
          <div class="content-container">
            <div class="index__slider__content-area"></div>
            <div class="index__slider__control-area"></div>
          </div>
        </div>
      </div>
      <div class="index__slider__move index__slider__role-prev"><i class="icon-chevron_left"></i></div>
      <div class="index__slider__move index__slider__role-next"><i class="icon-chevron_right"></i></div>
    </div>
    <?php print render($page['content']); ?>
  </section>
  
  <?php include __DIR__.'/inc--footer.tpl.php' ?>
