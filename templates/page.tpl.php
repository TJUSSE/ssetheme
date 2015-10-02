  <section class="page-row">
    <div class="body__ribbon"></div>
  </section>

  <section class="page-row">
    <header class="header">
      <div class="header__background"></div>
      <div class="content-container clearfix">
        <section class="header__logo-container">
          <a href="<?php print $front_page ?>" class="header__logo"><img src="<?php print sse_asset_path() ?>/img/logo.png" alt="<?php print t('Home') ?>"/></a>
        </section>
        <nav class="header__nav">
          <?php print sse_navigation_main_output(); ?>
        </nav>
      </div>
      <?php if (!sse_has_sidenav()): ?>
        <div class="title-section title-section--wide"><div class="content-container clearfix">
          <?php include __DIR__.'/title.tpl.php'; ?>
        </div></div>
      <?php endif; ?>
    </header>
  </section>

  <section class="page-row page-row-expanded">
    <div class="content<?php print (sse_has_sidenav() ? ' content-has-sidenav' : ' content-no-sidenav'); ?>">
      <div class="content-container clearfix">
        <?php if (sse_has_sidenav()) { print sse_sidenav_output(); } ?>
        <section class="main-content">
          <a id="main-content"></a>
          <?php if (sse_has_sidenav()): ?>
          <div class="title-section title-section--box">
            <?php include __DIR__.'/title.tpl.php'; ?>
          </div>
          <?php endif; ?>
          <div class="typo">
            <?php print $messages; ?>
            <?php print render($page['content']); ?>
          </div>
        </section>
      </div>
    </div>
  </section>

  <section class="page-row">
    <?php include __DIR__.'/footer.tpl.php'; ?>
  </section>

  <?php print render($page['bottom']); ?>
