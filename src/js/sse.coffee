jQuery(document).ready ->

  # 有内容页面，对内容页面添加空格
  if jQuery('.format--pangu').length > 0
    pangu.element_spacing('.format--pangu')
