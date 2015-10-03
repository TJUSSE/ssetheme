<div class="subscription__step-container clearfix">
<?php foreach (sse_get_subscription_steps() as $index => $step): ?>
  <div class="subscription__step <?php
if ($subscription_current === $index) {
  print 'subscription__step--current';
} else if ($subscription_current > $index) {
  print 'subscription__step--passed';
} else {
  print 'subscription__step--todo';
}
  ?>">
    <div class="subscription__step__progress"><div class="subscription__step__progress-inner"></div></div>
    <div class="subscription__step__dot"><div class="subscription__step__dot-inner"></div></div>
    <div class="subscription__step__text"><?php print $step; ?></div>
  </div>
<?php endforeach; ?>
</div>
