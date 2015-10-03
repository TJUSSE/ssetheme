<div class="subscription__page">
  <div class="intro-col__inner">
    <?php $subscription_current = 0; ?>
    <?php include __DIR__.'/inc--subscription--step.tpl.php'; ?>
  </div>
  <div class="subscription__form subscription__form--enter">
    <form action="<?php print $variables['post_action'] ?>" method="POST">
    <p>我们提供基于邮箱的通知订阅：<br>每天晚上6点左右，当天新通知将一起发送到您的邮箱中。</p>
    <p>请留下您的邮箱，我们将发送给您一封订阅确认邮件。</p>
    <div class="form__line">
      <input name="email" required type="email" class="sse_textbox" size="80" placeholder="您的邮箱">
      <input type="submit" class="sse_button" value="发送确认邮件">
    </div>
    </form>
  </div>
</div>
