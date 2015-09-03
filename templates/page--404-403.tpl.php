<script>
  var error_url = <?php echo drupal_json_encode(substr(strtok($_SERVER['REQUEST_URI'], '?'), 1)); ?>;
</script>
<div class="page-error__shell">
  <div class="header">
    <div class="header__bullets">
      <span class="bullet bullet-red"></span>
      <span class="bullet bullet-yellow"></span>
      <span class="bullet bullet-green"></span>
    </div>
    <span class="header__title">同济大学软件学院</span>
  </div>
  <div class="shell-content">
    <div id="terminal"></div>
  </div>
</div>
