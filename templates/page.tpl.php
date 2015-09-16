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
    </header>
  </section>

  <section class="page-row page-row-expanded">
    <div class="content<?php if (sse_has_sidenav()) print ' content-has-sidenav'; ?>">
      <div class="content-container clearfix">
        <?php if (sse_has_sidenav()) { print sse_sidenav_output(); } ?>
        <section class="main-content">
          <?php print sse_breadcrumb_output(); ?>
          <a id="main-content"></a>
          <div class="typo">
            <?php print render($title_prefix); ?>
<?php if ($title): ?>
            <h1 class="page__title title" id="page-title">
              <?php print $title; ?>
              <?php if (isset($node) && $node->type === 'teacher_content' && !empty($node->field_teacher_pinyin)): ?>
                <small class="teacher__pinyin"><?php print $node->field_teacher_pinyin[LANGUAGE_NONE][0]['safe_value']; ?></small>
              <?php endif; ?>
            </h1>
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
    <?php include __DIR__.'/footer.tpl.php' ?>
  </section>

  <?php print render($page['bottom']); ?>
