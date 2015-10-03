$ = jQuery

goto_step = (step) ->
  clsPassed = 'subscription__step--passed'
  clsCurrent = 'subscription__step--current'
  clsTodo = 'subscription__step--todo'

  $steps = $('.subscription__step');
  $steps.removeClass([clsPassed, clsCurrent, clsTodo].join(' '))
  $steps.eq(i).addClass(clsPassed) for i in [0...step]
  $steps.eq(step).addClass(clsCurrent)
  $steps.eq(i).addClass(clsTodo) for i in [step + 1...$steps.length]

normalize_subscription_options = (target) ->
  $form = $('.subscription__form--sub form')
  $form.find('.role-term[data-is-all]').each ->
    tid = $(this).attr('data-tid')
    if this is target
      console.log tid, '.role-term[data-parent-tid="' + tid + '"]'
      if this.checked
        $form.find('.role-term[data-parent-tid="' + tid + '"]').prop 'checked', true
      else
        $form.find('.role-term[data-parent-tid="' + tid + '"]').prop 'checked', false
    else
      this.checked = ($form.find('.role-term[data-parent-tid="' + tid + '"]:not(:checked)').length is 0)
    return

# 填写邮箱界面
init_subscribe_enter = ->
  $form = $('.subscription__form--enter form')

  $form.on 'submit', (e) ->
    e.preventDefault()
    $form.find('input').prop 'disabled', true
    $.ajax
      method: 'post'
      url: $form.attr 'action'
      data:
        email: $form.find('[name="email"]').val()
    .always ->
      $form.find('input').prop 'disabled', false
    .fail ->
      alert '发送邮件失败'
    .done (data) ->
      if not data.ok
        alert data.message
        return
      $('.subscription__form--enter').html("
        <p>验证邮件已发送至：#{data.email}，请点击邮件中的链接完成验证。</p>
        <p><i class=\"icon-chevron_left\"></i> <a href=\"javascript:window.location.reload();\">重新填写邮箱</a></p>
      ")
      goto_step(1)

# 选择订阅项界面
init_subscribe_sub = ->
  $form = $('.subscription__form--sub form')

  # 用户点击「全部」时候，更新子项
  inChangeProcess = false
  $form.on 'change', '.role-term', (e) ->
    return if inChangeProcess
    inChangeProcess = true
    normalize_subscription_options e.target
    inChangeProcess = false
    true

  $form.on 'submit', (e) ->
    e.preventDefault()
    $form.find('input').prop 'disabled', true
    # build options
    options = {}
    $form.find('.subscription__form--section').each ->
      $section = $(this)
      terms = $section.find('.role-term:checked')
      # === 特别地，对于「面向群体」，若父项（如所有本科生）选中了，那么子项不需要选中
      $all = $section.find('.role-term[data-is-all]')
      if ($all.length > 0 and $all.attr('data-parent-tid') isnt '0' and $all.prop('checked') is true)
        tid = $all.attr('data-tid')
        terms = terms.filter -> $(this).attr('data-parent-tid') isnt tid
      # ===
      terms = [].map.call terms, (term) -> $(term).attr('data-tid')
      terms = terms.filter (id) -> id.match(/^\d+$/)
      options[$section.attr('data-topic')] = terms

    $.ajax
      method: 'post'
      url: $form.attr 'action'
      data:
        options: JSON.stringify(options)
    .always ->
      $form.find('input').prop 'disabled', false
    .fail ->
      alert '保存订阅选项失败'
    .done (data) ->
      if not data.ok
        alert data.message
        return
      $('.subscription__form--sub').html("
        <p>#{data.email} 的订阅选项已保存。</p>
        <p><i class=\"icon-chevron_left\"></i> <a href=\"javascript:window.location.reload();\">修改订阅选项或退订</a></p>
      ")
      goto_step(3)

$(document).ready ->

  if ($('.subscription__form--enter').length > 0)
    init_subscribe_enter()
    return

  if ($('.subscription__form--sub').length > 0)
    init_subscribe_sub()
    return
