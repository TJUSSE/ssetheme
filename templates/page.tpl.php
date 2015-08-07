  <section class="page-row">
    <header class="header">
      <div class="header__background"></div>
      <div class="content-container clearfix">
        <section class="header__logo-container">
          <a href="<?php print $front_page ?>" class="header__logo"><img src="<?php print sse_asset_path() ?>/img/logo.png" alt="<?php print t('Home') ?>"/></a>
        </section>
        <?php print render($page['header']); ?>
      </div>
    </header>
  </section>
  
  <section class="page-row page-row-expanded">
    <div class="content<?php if (sse_has_sidenav()) print ' content-has-sidenav'; ?>">
      <div class="content-container clearfix">
        <?php if (sse_has_sidenav()) { print sse_sidenav_output(); } ?>
        <section class="main-content">
          <?php sse_get_breadcrumb(); ?>
          <a id="main-content"></a>
          <div class="typo">
            <?php print render($title_prefix); ?>
            <?php if ($title): ?>
              <h1 class="page__title title" id="page-title"><?php print $title; ?></h1>
            <?php endif; ?>
            <?php print render($title_suffix); ?>
            <?php print $messages; ?>
            <?php print render($page['content']); ?>
          </div>
        </section>
      </div>
    </div>
  </section>

  <section class="page-row">
    <footer class="footer">
      <div class="content-container clearfix">
        <section class="footer__navi clearfix"><?php print sse_footer_navigation_output(); ?></section>
        <section class="footer__info">
          <?php $footer_block = module_invoke('panels_mini', 'block_view', 'footer_info'); print $footer_block['content']; ?>
        </section>
      </div>
    </footer>
  </section>

<?php print render($page['bottom']); ?>