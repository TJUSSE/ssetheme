<footer class="footer">
  <div class="content-container clearfix">
    <section class="footer__navi clearfix"><?php print sse_navigation_footer_output(); ?></section>
    <section class="footer__info">
      <?php $footer_block = module_invoke('panels_mini', 'block_view', 'footer_info'); print $footer_block['content']; ?>
    </section>
  </div>
</footer>
