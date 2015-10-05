# 同济大学软件学院网站·主站主题

## banners

代码库中不自带各个栏目的 banners，可以使用以下方式获取我们默认提供的 banners：

```bash
# cd sseweb/sites/default/themes/sse_theme
cd img/banners
wget http://cdug.tongji.edu.cn/sse/banners.tar.gz
tar xzf banners.tar.gz
```

## 编译

### gulp

该项目前端使用 [gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md) 及相关工具链编译。如果您没有 gulp，请先安装 gulp。

```bash
cnpm install --global gulp
```

### 安装编译依赖

```bash
# cd sseweb/sites/default/themes/sse_theme
cnpm install --unsafe-perm
```

注意，对于 Windows 来说，请手工删除 `node_modules` 及子目录下的 `.info` 文件。

### 编译生成前端

```bash
# cd sseweb/sites/default/themes/sse_theme
gulp
```

### 调试

添加 `--watch` 参数，使得源码变更后自动重新编译：

```bash
# cd sseweb/sites/default/themes/sse_theme
gulp --watch
```

## 正确姿势

### 修改主菜单个数和颜色

1. 在 Drupal 中修改主菜单（顶级菜单需要指定 ID）

2. 修改 `src/css/ui/0_variable.styl`

3. 修改 `$sse_section_colors@template.php`

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
