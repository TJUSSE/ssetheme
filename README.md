# 同济大学软件学院网站·主站主题

## 编译

### gulp

该项目前端使用 [gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md) 及相关工具链编译。如果您没有 gulp，请先安装 gulp。

```bash
npm install --global gulp
```

### 安装编译依赖

```bash
# cd sseweb/sites/default/themes/sse
npm install
```

### 编译生成前端

```bash
# cd sseweb/sites/default/themes/sse
gulp
```

### 调试

添加 `--watch` 参数，使得源码变更后自动重新编译：

```bash
# cd sseweb/sites/default/themes/sse
gulp --watch
```

## 第三方项目及协议

该主题使用了以下第三方项目的代码，这些项目代码的开源协议请参阅其文档：

- [typo.css](https://github.com/sofish/typo.css/)

- [Modernizr](http://modernizr.com/)

- [selectivizr](http://selectivizr.com/)

- [Respond.js](https://github.com/scottjehl/Respond)

- [normalize.css](https://necolas.github.io/normalize.css/)

- [Web Font Loader](https://github.com/typekit/webfontloader)

- [paranoid-auto-spacing](https://github.com/vinta/paranoid-auto-spacing)

## License

MIT license
