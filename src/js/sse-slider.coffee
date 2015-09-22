$ = window.jQuery

class ImageLoader

  load: (url, callback) ->
    @loadCallback = callback
    image = new Image()
    image.src = url
    image.onload = =>
      if (@loadCallback and (@loadCallback is callback))
        @loadCallback() 
        @loadCallback = null

  removeCallbacks: ->
    @loadCallback = null

class IndexSlider

  init: ->
    @switchInterval = 4000
    @imageLoader = new ImageLoader()
    @slideNum = $('.index__slider--data-item').length
    @initDOM()
    @resize()
    @addListeners()
    @changeToSlide 0 if @slideNum > 0

  initDOM: ->
    $('
      <div class="index__slider">
        <div class="index__slider__spinner">
          <div class="sk-cube-grid">
            <div class="sk-cube sk-cube1"></div>
            <div class="sk-cube sk-cube2"></div>
            <div class="sk-cube sk-cube3"></div>
            <div class="sk-cube sk-cube4"></div>
            <div class="sk-cube sk-cube5"></div>
            <div class="sk-cube sk-cube6"></div>
            <div class="sk-cube sk-cube7"></div>
            <div class="sk-cube sk-cube8"></div>
            <div class="sk-cube sk-cube9"></div>
          </div>
        </div>
        <div class="index__slider__stage">
          <div class="index__slider__image-area"></div>
          <div class="index__slider__desc">
            <div class="content-container">
              <div class="index__slider__content-area"></div>
              <div class="index__slider__control-area"></div>
            </div>
          </div>
        </div>
        <div class="index__slider__move index__slider__role-prev"><i class="icon-chevron_left"></i></div>
        <div class="index__slider__move index__slider__role-next"><i class="icon-chevron_right"></i></div>
      </div>
    ').appendTo('.fp__slider')

    $('<span class="index__slider__control-item index__slider__role-prev"><i class="icon-chevron-left"></i></span>').appendTo('.index__slider__control-area')
    $('<span class="index__slider__control-item index__slider__role-switch"><i class="icon-circle"></i></span>').appendTo('.index__slider__control-area') for i in [0...@slideNum]
    $('<span class="index__slider__control-item index__slider__role-next"><i class="icon-chevron-right"></i></span>').appendTo('.index__slider__control-area')

  addListeners: ->
    self = @

    $('.index__slider__role-switch').click ->
      slideIndex = $(this).index() - 1
      self.changeToSlide slideIndex

    $('.index__slider__role-prev').click ->
      self.changeToPrev()

    $('.index__slider__role-next').click ->
      self.changeToNext()

    $(window).resize ->
      self.resize()

  resize: ->
    $('.index__slider').css('height', window.innerHeight + 'px')

  showSpinner: ->
    $('.index__slider__spinner').addClass('index__slider__spinner--show')

  hideSpinner: ->
    $('.index__slider__spinner').removeClass('index__slider__spinner--show')

  changeToPrev: ->
    @currentSlide--
    @currentSlide = @slideNum - 1 if @currentSlide < 0
    @changeToSlide @currentSlide

  changeToNext: ->
    @currentSlide++
    @currentSlide = 0 if @currentSlide >= @slideNum
    @changeToSlide @currentSlide

  changeToSlide: (idx) ->
    @cancelSwitch()
    @imageLoader.removeCallbacks()

    @currentSlide = idx
    $('.index__slider__role-switch').removeClass('index__slider__circle--activated')
    $('.index__slider__role-switch').eq(idx).addClass('index__slider__circle--activated')

    lastItem = $('.index__slider__item')
    lastItem.removeClass('index__slider__item--shown')
    setTimeout ->
      lastItem.remove()
    , 500

    $slide = $('.index__slider--data-item').eq(idx)
    imgUrl = $slide.attr('data-src')

    @showSpinner()
    @imageLoader.load imgUrl, =>
      @hideSpinner()
      @prepareSwitchToNext()

      # 预先载入下一个
      nextImageUrl = $('.index__slider--data-item').eq((idx + 1) % @slideNum).attr('data-src')
      @imageLoader.load nextImageUrl
    
    $newImage = $('<div>').addClass('index__slider__item index__slider__image').css('background-image', 'url("' + imgUrl + '")').appendTo('.index__slider__image-area')
    $newContent = $('<div>').addClass('index__slider__item index__slider__text').html($slide.html()).appendTo('.index__slider__content-area')

    $('.index__slider').width()
    $newImage.addClass('index__slider__item--shown')
    $newContent.addClass('index__slider__item--shown')

  cancelSwitch: ->
    clearTimeout @switcher if @switcher
    @switcher = null

  prepareSwitchToNext: ->
    @switcher = setTimeout =>
      @changeToNext()
      @switcher = null
    , @switchInterval

$(document).ready ->
  slider = new IndexSlider()
  slider.init()
