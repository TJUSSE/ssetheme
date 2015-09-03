jQuery(document).ready ->

  canvas = jQuery('canvas').get(0)
  ctx = canvas.getContext('2d')

  pr = window.devicePixelRatio || 1
  w = window.innerWidth
  h = window.innerHeight

  step = 100
  hue = 0

  canvas.width = w*pr
  canvas.height = h*pr

  triangles = []

  draw = ->
    i = triangles[0]
    j = triangles[1]
    k = next j
    ctx.beginPath()
    ctx.moveTo i.x, i.y
    ctx.lineTo j.x, j.y
    ctx.lineTo k.x, k.y
    ctx.closePath()
    hue += ~~(Math.random() * 20)
    ch = hue
    cs = ~~(Math.random() * 20 + 50)
    cl = ~~(Math.random() * 30 + 50)
    ctx.fillStyle = "hsl(#{ch}, #{cs}%, #{cl}%)"
    ctx.fill()
    triangles[0] = j
    triangles[1] = k

  redraw = ->
    ctx.globalCompositeOperation = 'lighter'
    ctx.globalAlpha = 0.8
    ctx.clearRect 0, 0, w, h
    triangles = []
    triangles.push x: 0, y: h * 0.5 + step
    triangles.push x: 0, y: h * 0.5 - step
    hue = ~~(Math.random() * 360)
    draw() while triangles[1].x < w + step

  next = (last) ->
    k = x: -1, y: -1
    k.x = last.x + (Math.random() * 2 - 0.25) * step
    k.y = last.y + (Math.random() * 3 - 1.5) * step while (k.y > h) or (k.y < 0)
    k

  redraw()
