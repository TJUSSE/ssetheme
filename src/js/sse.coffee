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
      # 点击侧边栏修改到对应的URL
      activatedIdList = idList.map (elemStr)-> parseInt elemStr
      addClickEvents = ->
        $ = jQuery
        $('.notice-filter__item').map( (elemIdx)->
          dom = $('.notice-filter__item')[elemIdx]
          domText = $(dom).children().eq(0)
          # 获取data-id中的值
          dataId = $(domText).attr('data-id')
          # url前缀
          urlHead = window.location.pathname.substr(0, Drupal.settings.basePath.length + Drupal.settings.pathPrefix.length) + 'notice/filter'
          # console.log urlHead
          # 添加点击事件
          $(dom).click ->
            idx = parseInt(dataId)
            if ((idx in activatedIdList) is false)
              # 当前未选，点击后需要激活选择
              console.log '已激活选择。'
              activatedIdList = activatedIdList.concat(idx).sort()
            else
              # 当前已选，点击后需要取消选择
              newActivatedIdList = []
              for elemId in activatedIdList
                if elemId isnt idx
                  newActivatedIdList = newActivatedIdList.concat(elemId)
              activatedIdList = newActivatedIdList
              console.log '已取消选择。'
            # 合成新的url
            newUrl = urlHead
            targetUrlSlice = ''
            categoryUrlSlice = ''
            hasTarget = false
            hasCategory = false
            for elem in activatedIdList
              # data-id < 10的认为是target，>= 10的认为是category（似乎是吧_(:з」∠)_）
              if elem <= 10
                hasTarget = true
                if targetUrlSlice isnt ''
                  targetUrlSlice += '+' + elem
                else
                  targetUrlSlice += elem
              if elem > 10
                hasCategory = true
                if categoryUrlSlice isnt ''
                  categoryUrlSlice += '+' + elem
                else
                  categoryUrlSlice += elem
            if hasTarget
              newUrl += '/target/' + targetUrlSlice
            if hasCategory
              newUrl += '/category/' + categoryUrlSlice
            console.log newUrl
            window.location.href = newUrl
        )
      addClickEvents()

  addTick = ->
    for dom in jQuery('.notice-filter__item > .notice-filter__filters > .notice-filter__item')
      if jQuery(jQuery(dom).children()[0]).hasClass 'is-highlight'
        jQuery(jQuery(jQuery(dom).parents()[1]).children()[0]).addClass 'is-highlight'
        break
    jQuery('.notice-filter__item .is-highlight').append('&nbsp;<i class="icon-check"></i>')
      
  # Defer
  jQuery.when(noticeFilter()).done(addTick)

  
  