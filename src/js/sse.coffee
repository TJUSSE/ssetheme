jQuery(document).ready ->

  # 有内容页面，对内容页面添加空格
  if jQuery('.format--pangu').length > 0
    pangu.element_spacing('.format--pangu')

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