<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

function sse_asset_path()
{
  static $path = null;
  if ($path === null) {
    $path = base_path().path_to_theme();
  }
  return $path;
}

/**
 * 获取不同区块的主题颜色
 */
function sse_get_section_color($key)
{
  /**
   * 这里指定的是 <meta name="theme-color"> 中的颜色，该属性可以指定 Android Chrome 浏览器中标题栏颜色
   * @var array
   */
  static $sse_section_colors = [
    'default' =>   '#53A0D4',
    'overview' =>  '#9FC1E3',
    'admission' => '#E3B48D',
    'education' => '#E1A6E3',
    'research' =>  '#F8DA89',
    'activity' =>  '#C9D36F',
    'news' =>      '#DEA1A1',
    'notice' =>    '#A4CBCC',
  ];
  if (isset($sse_section_colors[$key])) {
    return $sse_section_colors[$key];
  } else {
    return $sse_section_colors['default'];
  }
}

/**
 * 获取当前所在区块
 */
function sse_get_current_section()
{
  static $section = null;
  if ($section === null) {
    $route = menu_get_active_trail();
    if (count($route) >= 2) {
      // $route[0] 是首页
      $entity = menu_fields_load_by_mlid($route[1]['mlid']);
      $menu_id = $entity->wrapper()->field_id->value();
      $section = $menu_id;
    } else {
      $section = 'default';
    }
  }
  return $section;
}

/**
 * 获得当前导航面包屑
 */
function sse_get_breadcrumb()
{
  $route = menu_get_active_trail();
  return $route;
}

/**
 * 返回面包屑 HTML
 */
function sse_breadcrumb_output()
{

}

/**
 * 获取经过处理的导航栏
 * @param  boolean $extra 是否要包含新闻和通知
 */
function sse_get_navigations($extra = false)
{
  static $menu_name = 'menu-sse-navigation-menu';
  $result = [];
  $menu_tree = menu_tree_all_data($menu_name);
  foreach ($menu_tree as &$section) {
    if ($section['link']['hidden'] === true) {
      continue;
    }
    $entity = menu_fields_load_by_mlid($section['link']['mlid']);
    $menu_id = $entity->wrapper()->field_id->value();
    $items = [];
    foreach ($section['below'] as &$item) {
      $items[] = [
        'href' => url($item['link']['href']),
        'text' => $item['link']['title'],
      ];
    }
    unset($item);
    $result[] = [
      'text' => $section['link']['title'],
      'id' => $menu_id,
      'items' => $items
    ];
  }
  unset($section);
  return $result;
}

/**
 * 返回 footer 导航
 */
function sse_footer_navigation_output()
{
  $output = '';
  $navi = sse_get_navigations(false);
  foreach ($navi as &$section) {
    $output .= '<div class="footer__navi__section footer__navi__section__'.$section['id'].'">';
    $output .= '<h1 class="footer__navi__section__title">'.$section['text'].'</h1>';
    $output .= '<ul class="footer__navi__section__items">';
    foreach ($section['items'] as &$item) {
      $output .= '<li class="footer__navi__section__item"><a href="'.$item['href'].'" target="_self">'.$item['text'].'</a></li>';
    }
    unset($item);
    $output .= '</ul></div>';
  }
  unset($section);
  return $output;
}

/**
 * 渲染 footer 的语言项时增加一项「开发人员」
 */
function sse_links__locale_block(&$vars)
{
  $vars['links']['contributors'] = [
    'href' => '<front>',
    'title' => '开发人员'
  ];
  $content = theme_links($vars);
  return $content;
}

/**
 * 判断当前页面是否包含侧栏导航
 */
function sse_has_sidenav()
{
  static $has_side_nav = null;
  if ($has_side_nav === null) {
    $route = menu_get_active_trail();
    $has_side_nav = (count($route) >= 2 && $route[1]['menu_name'] === 'menu-sse-navigation-menu');
  }
  return $has_side_nav;
}

/**
 * 返回侧栏导航 HTML
 */
function sse_sidenav_output()
{
  if (!sse_has_sidenav()) {
    return '';
  }
  $route = menu_get_active_trail();
  $parent = $route[1];
  $param = [
    'active_trail' => array($parent['plid']),
    'only_active_trail' => false,
    'min_depth' => $parent['depth'] + 1,
    'max_depth' => $parent['depth'] + 1,
    'conditions' => array('plid' => $parent['mlid']),
  ];
  $items = menu_build_tree($parent['menu_name'], $param);
  $entity = menu_fields_load_by_mlid($parent['mlid']);
  $menu_id = $entity->wrapper()->field_id->value();
  $output = '<nav class="sidenav sidenav--section-'.$menu_id.'">';
  $output .= '<h1 class="sidenav__title"><a href="'.$parent['link_path'].'" target="_self" class="sidenav__title__link">'. $parent['title'] .'</a></h1>';
  $output .= '<div class="sidenav__edge"></div><ul class="sidenav__list">';
  foreach ($items as $item) {
    $output .= '<li class="sidenav__item'. ((count($route) >= 3 && $item['link']['mlid'] === $route[2]['mlid']) ? ' sidenav__item--active' : '') .'">';
    $output .= '<a href="'.url($item['link']['link_path']).'" target="_self" class="sidenav__item__link">'.$item['link']['title'].'</a>';
    $output .= '</li>';
  }
  $output .= '</ul></nav>';
  return $output;
}

/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  sse_preprocess_html($variables, $hook);
  sse_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
function sse_preprocess_html(&$variables, $hook) {
  $variables['base_path'] = base_path();
  $variables['path_to_sse'] = drupal_get_path('theme', 'sse');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_page(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // sse_preprocess_node_page() or sse_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function sse_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */
