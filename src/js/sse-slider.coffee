# Set global object

window.g = window.g or {}

window.g.$sliderScope =
  self: this
  settings:
    IntervalSec: 5
  status:
    currentShowingIndex: 0
    totalImageNum: 0
    LoadedBitmap: 0
    intervalId: -1
  fn:
    loadImg: (idx)->
      console.log 'Slider image #%d now loading...', idx
      loadingImg = $('.sse-slider .slide')[idx]
      url = loadingImg.getAttribute('data-src')
      $(loadingImg).attr('style', 'background-image: url("' + url + '");')
      $(loadingImg).removeAttr 'data-src'

    onload: (idx)->
      # if idx isnt -1
      console.log 'Slider image #%d loaded!', idx
      if idx isnt (window.g.$sliderScope.status.totalImageNum - 1)
        window.g.$sliderScope.fn.loadImg(idx + 1)

    setRoundButtonActivated: (idx)->
      for btn in $('.sse-slider--round-ctrl button')
        if $(btn).hasClass 'sse-slider--activated'
          $(btn).removeClass 'sse-slider--activated'
        if $(btn).index() is (idx + 1)
          $(btn).addClass 'sse-slider--activated'

    changeToSlide: (idx)->
      console.log 'Change to #' + idx + '...'
      window.g.$sliderScope.status.currentShowingIndex = idx
      this.setRoundButtonActivated(idx)

      for img, i in $('.sse-slider div.slide')
        if ($(img).hasClass 'is-show') and (i isnt idx)
          $(img).removeClass 'is-show'

      for text, j in $('.sse-slider > ul > li > .slide > .slide-content')
        if ($(text).hasClass 'is-show') and (j isnt idx + 1)
          $(text).removeClass 'is-show'

      imgToShow = $('.sse-slider > ul > li > .slide').eq(idx)
      if($(imgToShow).attr 'data-src')
        console.log 'Image #' + idx + ' not loaded, now loading...'
        window.g.$sliderScope.fn.loadImg(idx)

      textToShow = $('.sse-slider > ul > li > .slide > .slide-content').eq(idx)

      $(imgToShow).addClass 'is-show'
      $(textToShow).addClass 'is-show'

    changeToNext: ->
      if window.g.$sliderScope.status.currentShowingIndex + 1 is window.g.$sliderScope.status.totalImageNum
        window.g.$sliderScope.fn.changeToSlide 0
        return null
      window.g.$sliderScope.fn.changeToSlide window.g.$sliderScope.status.currentShowingIndex + 1

    changeToPrev: ->
      if window.g.$sliderScope.status.currentShowingIndex - 1 is -1
        window.g.$sliderScope.fn.changeToSlide(window.g.$sliderScope.status.totalImageNum - 1)
        return null
      window.g.$sliderScope.fn.changeToSlide window.g.$sliderScope.status.currentShowingIndex - 1


$(document).ready ->
  $('.sse-slider').append('
      <div class="sse-slider--indicator">
        <div class="spinner">
          <div class="spinner-container container1">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container2">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container3">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
        </div>
      </div>

      <div class="sse-slider--control-next" id="id-slider-next"><button><i class="icon-chevron_right"></i></button></div>
      <div class="sse-slider--control-prev" id="id-slider-prev"><button><i class="icon-chevron_left"></i></button></div>

      ')

  window.g.$sliderScope.status.totalImageNum = $('.sse-slider .slide').length

  roundCtrl = '<div class="sse-slider--round-ctrl">'
  roundCtrl += '<a class="round-ctrl-arrow" id="id-slider-prev-ctrl"><i class="icon-chevron_left"></i></a>'
  for i in [0..window.g.$sliderScope.status.totalImageNum - 1]
    roundCtrl += '<button><i class="icon-circle"></i></button>'
  roundCtrl += '<a class="round-ctrl-arrow" id="id-slider-next-ctrl"><i class="icon-chevron_right"></i></a>'
  roundCtrl += '</div>'

  $('.sse-slider').append(roundCtrl)

  # console.log 'Slides images num: %d', window.g.$sliderScope.status.totalImageNum

  for li in $('.sse-slider li')
    idx = $(li).index()
    # console.log $(li).index()
    img = $('.slide').eq(idx)
    $(img).attr 'onload', 'window.g.$sliderScope.fn.onload(' + (idx)+ ')'

  window.g.$sliderScope.fn.onload(-1)
  window.g.$sliderScope.fn.changeToSlide(0)

  for btn in $('.sse-slider--round-ctrl button')
    $(btn).click ->
      i = $(this).index()
      window.g.$sliderScope.fn.changeToSlide(i - 1)
      clearInterval window.g.$sliderScope.status.intervalId
      window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000


  $('#id-slider-prev').click ->
    window.g.$sliderScope.fn.changeToPrev()
    clearInterval window.g.$sliderScope.status.intervalId
    window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000
  $('#id-slider-prev-ctrl').click ->
    window.g.$sliderScope.fn.changeToPrev()
    clearInterval window.g.$sliderScope.status.intervalId
    window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000

  $('#id-slider-next').click ->
    window.g.$sliderScope.fn.changeToNext()
    clearInterval window.g.$sliderScope.status.intervalId
    window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000
  $('#id-slider-next-ctrl').click ->
    window.g.$sliderScope.fn.changeToNext()
    clearInterval window.g.$sliderScope.status.intervalId
    window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000


  window.g.$sliderScope.status.intervalId = setInterval window.g.$sliderScope.fn.changeToNext, window.g.$sliderScope.settings.IntervalSec * 1000

  # 调整slide大小至屏幕大小
  $('.sse-slider').css('height', window.innerHeight + 'px')