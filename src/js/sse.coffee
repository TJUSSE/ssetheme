jQuery(document).ready =>

  # 有内容页面，对内容页面添加空格
  if jQuery('.main-content').length > 0
    pangu.element_spacing('.main-content')