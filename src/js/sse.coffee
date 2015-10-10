$ = jQuery

for status in ['active', 'inactive']
  do (status) ->
    WebFontConfig[status] = -> $(document).trigger 'webfontStatusChanged', status

# 有内容页面，对内容页面添加空格
letterSpacing =
  init: ->
    $(document).ready @enable.bind(@)
  enable: ->
    return if $('.format--pangu').length is 0
    pangu.element_spacing '.format--pangu'

# Sticky sidebar and rightbar
stickys = 
  init: ->
    $(document).on 'webfontStatusChanged', @enable.bind(@)
  enable: ->
    # 左侧边栏
    $('.sidenav').stick_in_parent
      offset_top: 20
      parent: '.content-container'
    # 右侧边栏
    $('.intro-layout--two-col > .intro__right > .intro-col__inner').stick_in_parent
      offset_top: 20
      parent: '.intro-layout--two-col'

# remove first element margin-top
removeMarginTop =
  init: ->
    $(document).ready @enable.bind(@)
  process: ($dom) ->
    $dom.css 'margin-top', 0
    $children = $dom.children()
    @process $children.eq(0) if $children.length > 0
  enable: ->
    return if $('.format--pangu').length is 0
    return if $('.title-section--box .page__title').length is 0
    @process $('.format--pangu')

# 通知过滤
noticeFilter =
  prefix: 'notice'
  topics: ['target', 'category']
  selections: {}
  init: ->
    $(document).ready @check.bind(@)
  
  # 检查是否包含过滤器
  check: ->
    url = window.location.pathname.substr (Drupal.settings.basePath + Drupal.settings.pathPrefix).length
    return if url.indexOf(@prefix) isnt 0
    @url = url.substr(@prefix.length)
    @enable()
  
  # 从 url 获得当前选项
  retriveUrlSelections: ->
    parts = @url.substr(1).split('/').slice(1)  # 0 == filter
    for topic in @topics
      pos = parts.indexOf topic
      @selections[topic] = []
      @selections[topic] = parts[pos + 1].split '+' if pos > -1
  
  # 根据选项更新 DOM 选择状态
  updateSelectionToDOM: ->
    $('.notice-filter__item__text.is--highlight').removeClass('is--highlight')
    for topic, selections of @selections
      $('.notice-filter__item__text[data-id="' + id + '"]').addClass('is--highlight') for id in selections
    # 若子项选择，则父项也选择
    $('.notice-filter__item .notice-filter__filters .notice-filter__item__text.is--highlight')
      .closest('.notice-filter__filters')
      .closest('.notice-filter__item')
      .children('.notice-filter__item__text')
      .addClass('is--highlight')

  # 根据选项更新 URL
  updateSelectionToUrl: (redirect = true) ->
    url = Drupal.settings.basePath + Drupal.settings.pathPrefix + @prefix
    filter = ''
    for topic in @topics
      filter += '/' + topic + '/' + @selections[topic].join '+' if @selections[topic].length > 0
    url += '/filter' + filter if filter.length > 0
    window.location.href = url if redirect
    return url

  # 是否已选择
  _is_selected: (topic, id) ->
    pos = @selections[topic].indexOf(id)
    return pos > -1

  # 取消选择
  _deselect: (topic, id) ->
    pos = @selections[topic].indexOf(id)
    @selections[topic].splice pos, 1 if pos > -1

  # 选择
  _select: (topic, id) ->
    pos = @selections[topic].indexOf(id)
    @selections[topic].push id if pos is -1

  # 点击选项
  onItemClick: (ev) ->
    self = @
    $item = $(ev.target)
    itemTopic = $item.closest('.notice-filter__block').attr 'data-id'
    itemId = $item.attr 'data-id'

    $childSelections = $item.siblings('.notice-filter__filters').find('.is--highlight')
    if $childSelections.length > 0
      # 若有子项选中，则无论当前选项是否选中，都应该将子项清除
      $childSelections.each ->
        console.log $(@)
        self._deselect itemTopic, $(@).attr 'data-id'
      @_deselect itemTopic, itemId
    else
      # 没有子项时，toggle
      if @_is_selected(itemTopic, itemId)
        @_deselect itemTopic, itemId
      else
        @_select itemTopic, itemId

    @updateSelectionToDOM()
    @updateSelectionToUrl()
  enable: ->
    @retriveUrlSelections()
    @updateSelectionToDOM()
    $(document).on 'click', '.notice-filter__item__text', @onItemClick.bind(@)

letterSpacing.init()
stickys.init()
removeMarginTop.init()
noticeFilter.init()
