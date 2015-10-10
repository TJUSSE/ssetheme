$ = jQuery

for status in ['active', 'inactive']
  do (status) ->
    WebFontConfig[status] = -> $(document).trigger 'webfontStatusChanged', status

# 有内容页面，对内容页面添加空格
letterSpacing =
  init: ->
    $(document).ready @enable
  enable: ->
    return if $('.format--pangu').length is 0
    pangu.element_spacing '.format--pangu'

# Sticky sidebar and rightbar
stickys = 
  init: ->
    $(document).on 'webfontStatusChanged', @enable
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
    $(document).ready @enable
  process: ($dom) ->
    $dom.css 'margin-top', 0
    $children = $dom.children()
    removeMarginTop.process $children.eq(0) if $children.length > 0
  enable: ->
    return if $('.format--pangu').length is 0
    return if $('.title-section--box .page__title').length is 0
    removeMarginTop.process $('.format--pangu')

letterSpacing.init()
stickys.init()
removeMarginTop.init()

$(document).ready ->

  noticeFilter = ->
    # notice filter
    url = window.location.pathname.substr(Drupal.settings.basePath.length + Drupal.settings.pathPrefix.length)
    
    if not (url.indexOf('notice/filter') is 0) then return
    url = url.substr('notice/filter'.length)
    
    if (url is '')
      null
    else
      # Function to highlight the sidebar
      hightlightSidebar = (idx)->
        # console.log 'Sidebar elem#' + idx + ' will be highlighted.'
        jQuery('[data-id=' + idx + ']').addClass 'is-highlight'
        # console.log jQuery('[data-id="' + idx + '"]')
    
      urlSlice = url.split('/').slice(1)
      console.log urlSlice
      # An array like ["target", "1+2+3", "category", "11+12"]
      if urlSlice.length is 2
        if urlSlice[0] is 'target' or urlSlice[0] is 'category'
          # Split "1+2+3" to ['1', '2', '3'] and highlight each '+'
          idList = urlSlice[1].split('+')
          # console.log idList
          hightlightSidebar(idx) for idx in idList  
      if urlSlice.length is 4
        idList = (urlSlice[1].split('+')).concat(urlSlice[3].split '+')
        # console.log idList
        hightlightSidebar(idx) for idx in idList

  addTick = ->
    for dom in jQuery('.notice-filter__item > .notice-filter__filters > .notice-filter__item')
      if jQuery(jQuery(dom).children()[0]).hasClass 'is-highlight'
        jQuery(jQuery(jQuery(dom).parents()[1]).children()[0]).addClass 'is-highlight'
        break
    jQuery('.notice-filter__item .is-highlight').append('&nbsp;<i class="icon-check"></i>')
      
  # Defer
  jQuery.when(noticeFilter()).done(addTick)
