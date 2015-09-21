  <script type="text/javascript" src="<?php print sse_asset_path() ?>/js/vendor/jquery.min.js"></script>
  <script type="text/javascript" src="<?php print sse_asset_path() ?>/js/sse-slider.js" async="true"></script>

  <header class="fp__header" style="z-index: 20000">
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
    <!-- <?php print render($page['content']); ?> -->
    <div class="sse-slider" id="slider-demo">
      <ul>
        <li>
          <div class="slide" data-src="<?php print sse_asset_path() ?>/img/sse-slide-demo-large-1.png">
            <div class="slide-content">
              <h2><a href="#">CTFC2015 圆满落幕1</a></h2>
              <p>由中国计算机协会主办, CCF容错计算专业委员会和同济大学承办的第十六届全国容错计算学术会议在同济大学中法中心召开。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="slide" data-src="<?php print sse_asset_path() ?>/img/sse-slide-demo-large-2.png">
            <div class="slide-content">
              <h2><a href="#">CTFC2015 圆满落幕2</a></h2>
              <p>由中国计算机协会主办, CCF容错计算专业委员会和同济大学承办的第十六届全国容错计算学术会议在同济大学中法中心召开。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="slide" data-src="<?php print sse_asset_path() ?>/img/sse-slide-demo-large-3.png">
            <div class="slide-content">
              <h2><a href="#">CTFC2015 圆满落幕3</a></h2>
              <p>由中国计算机协会主办, CCF容错计算专业委员会和同济大学承办的第十六届全国容错计算学术会议在同济大学中法中心召开。</p>
            </div>
          </div>
        </li>
        <li>
          <div class="slide" data-src="<?php print sse_asset_path() ?>/img/sse-slide-demo-large-4.png">
            <div class="slide-content">
              <h2><a href="#">CTFC2015 圆满落幕4</a></h2>
              <p>由中国计算机协会主办, CCF容错计算专业委员会和同济大学承办的第十六届全国容错计算学术会议在同济大学中法中心召开。</p>
            </div>
          </div>

        </li>
      </ul>
    </div>
  </section>
  
  <?php include __DIR__.'/footer.tpl.php' ?>
