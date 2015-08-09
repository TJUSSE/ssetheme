<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

const sse_menu_navigation = 'menu-sse-navigation-menu';
const sse_menu_footer = 'menu-sse-footer-menu';
const sse_menu_main = 'menu-sse-main-menu';

/**
 * 返回该主题基目录的 URI
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
      if (isset($route[1]['menu_name']) && $route[1]['menu_name'] === sse_menu_navigation) {
        $entity = menu_fields_load_by_mlid($route[1]['mlid'])->wrapper();
        $menu_id = $entity->field_id->value();
        $section = $menu_id;
      } else {
        $section = 'default';
      }
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
 * 将一颗菜单树递归地转换为包含 ID 字段的菜单树
 */
function sse_process_menu_tree_with_id(&$tree)
{
  $items = [];
  foreach ($tree['below'] as &$item) {
    if ($item['link']['hidden'] !== true) {
      $items[] = sse_process_menu_tree_with_id($item);
    }
  }
  unset($item);

  $ret = [
    'href' => $tree['link']['href'],
    'title' => $tree['link']['title'],
    'items' => $items
  ];
  $entity = menu_fields_load_by_mlid($tree['link']['mlid']);
  if ($entity !== null) {
    $entity = $entity->wrapper();
    if (isset($entity->field_id)) {
      $ret['id'] = $entity->field_id->value();
    }
  }

  return $ret;
}

/**
 * 获取包含 ID 字段的菜单树
 */
function sse_get_menu_tree_with_id($menu_name)
{
  $result = [];
  $menu_tree = menu_tree_all_data($menu_name);
  foreach ($menu_tree as &$tree) {
    if ($tree['link']['hidden'] === true) {
      continue;
    }
    $result[] = sse_process_menu_tree_with_id($tree);
  }
  unset($tree);
  return $result;
}

/**
 * 获得一颗数组形式的 taxonomy_tree，该形式和 sse_get_menu_tree_with_id() 一致
 */
function sse_get_taxonomy_tree($taxonomy_name)
{
  $result = [];
  $vocabulary = taxonomy_vocabulary_machine_name_load($taxonomy_name);
  // 为了使用 entity_uri 接口，需要最后一个参数为 true
  $taxonomy_tree = i18n_taxonomy_localize_terms(taxonomy_get_tree($vocabulary->vid, 0, null, true));
  foreach ($taxonomy_tree as &$item) {
    $result[] = [
      // 使用 entity_uri 接口，这样这个 URI 会被 ssetaxonomy 插件转换成正确的地址
      'href' => entity_uri('taxonomy_term', $item)['path'],
      'title' => $item->name
    ];
  }
  unset($item);
  return $result;
}

/**
 * 获得顶部导航条
 */
function sse_get_navigation_main($is_frontpage = false)
{
  $result = [];
  $menu_tree = menu_tree_all_data(sse_menu_main);
  foreach ($menu_tree as &$item) {
    if ($item['link']['hidden'] === true) {
      continue;
    }
    $curitem = [
      'href' => $item['link']['href'],
      'title' => $item['link']['title'],
      'items' => []
    ];
    $entity = menu_fields_load_by_mlid($item['link']['mlid']);
    if ($entity === null) {
      $result[] = $curitem;
      continue;
    }
    $entity = $entity->wrapper();
    if (isset($entity->field_id)) {
      $curitem['id'] = $entity->field_id->value();
    }
    if (!isset($entity->field_type)) {
      $result[] = $curitem;
      continue;
    }
    $ref_type = $entity->field_type->value();
    if ($ref_type === 'none') {
      // 无引用：没有子项
      $result[] = $curitem;
      continue;
    }
    // 有引用
    if (!isset($entity->field_from)) {
      $result[] = $curitem;
      continue;
    }
    $ref_target = $entity->field_from->value();

    if ($ref_type === 'menu') {
      // 菜单引用
      $subitems = sse_get_menu_tree_with_id($ref_target);
    } else if ($ref_type === 'taxonomy') {
      $subitems = sse_get_taxonomy_tree($ref_target);
    } else {
      $result[] = $curitem;
      continue;
    }

    // 是否以子项代替当前项
    if ($entity->field_expand->value() === true && $is_frontpage === false) {
      foreach ($subitems as $subitem) {
        $result[] = $subitem;
      }
    } else {
      $curitem['items'] = $subitems;
      $result[] = $curitem;
    }
  }
  unset($item);
  return $result;
}

/**
 * 返回 footer 导航 HTML
 */
function sse_navigation_footer_output()
{
  $output = '';
  $navi = sse_get_menu_tree_with_id(sse_menu_navigation);
  foreach ($navi as &$section) {
    $output .= '<div class="footer__navi__section footer__navi__section--'.check_plain($section['id']).'">';
    $output .= '<h1 class="footer__navi__section__title">'.check_plain($section['title']).'</h1>';
    $output .= '<ul class="footer__navi__section__items">';
    foreach ($section['items'] as &$item) {
      $output .= '<li class="footer__navi__section__item"><a href="'.url($item['href']).'" target="_self">'.check_plain($item['title']).'</a></li>';
    }
    unset($item);
    $output .= '</ul></div>';
  }
  unset($section);
  return $output;
}

function sse_navigation_output_tree(&$tree, $level, $is_frontpage = false)
{
  $active_section = sse_get_current_section();
  if (count($tree) === 0) {
    return '';
  }
  // 是否首页
  $front_symbol = $is_frontpage ? 'f' : 'nf';
  // 第几级菜单
  $level_symbol = 'lv'.$level;

  $append_class = function($base) use ($front_symbol, $level_symbol) {
    return "$base--$front_symbol--$level_symbol $base--$level_symbol";
  };

  $output = '<ul class="'.$append_class('nav__list').'">'."\n";
  foreach ($tree as &$item) {
    $has_subitems = (isset($item['items']) && count($item['items']) > 0);
    $output .= '<li class="'.$append_class('nav__i');
    if (isset($item['id'])) {
      $output .= ' nav__i--section--'.check_plain($item['id']);
      if ($item['id'] === $active_section) {
        $output .= ' nav__i--active';
      }
    }
    $output .= '"><a class="nav__l '.$append_class('nav__l').'" href="'.url($item['href']).'" target="_self">'.check_plain($item['title']);
    // 如果是顶级菜单，则有子项的时候，显示 v 并且紧跟文字
    // 如果不是顶级菜单，则有子项的时候，显示 > 并且单独排列
    if ($has_subitems && $level === 0) {
      $output .= '<i class="icon-expand_more"></i>';
    } else if ($has_subitems && $level > 0) {
      $output .= '<div class="nav__icon"><i class="icon-chevron_right"></i></div>';
    }
    $output .= '</a>';
    // 增加子菜单
    if ($has_subitems) {
      $output .= '<div class="'.$append_class('nav__sublist').'">'.sse_navigation_output_tree($item['items'], $level + 1, $is_frontpage).'</div>';
    }
    $output .= '</li>'."\n";
  }
  unset($item);
  $output .= "</ul>";
  return $output;
}

/**
 * 返回头部导航 HTML
 */
function sse_navigation_main_output()
{
  $is_frontpage = drupal_is_front_page();
  $navi = sse_get_navigation_main($is_frontpage);
  return sse_navigation_output_tree($navi, 0, $is_frontpage);
}

/**
 * 渲染 footer 的语言项时增加 footer_menu
 */
function sse_links__locale_block(&$vars)
{
  $footer_menu = menu_tree_all_data(sse_menu_footer);
  foreach ($footer_menu as $key => &$item) {
    if ($item['link']['hidden'] === true) {
      continue;
    }
    $vars['links'][$key] = [
      'href' => $item['link']['href'],
      'title' => $item['link']['title']
    ];
  }
  unset($item);
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
    $has_side_nav = (count($route) >= 2 && isset($route[1]['menu_name']) && $route[1]['menu_name'] === sse_menu_navigation);
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
    'active_trail' => [$parent['plid']],
    'only_active_trail' => false,
    'min_depth' => $parent['depth'] + 1,
    'max_depth' => $parent['depth'] + 1,
    'conditions' => ['plid' => $parent['mlid']],
  ];
  $items = menu_build_tree($parent['menu_name'], $param);
  $entity = menu_fields_load_by_mlid($parent['mlid'])->wrapper();
  $menu_id = $entity->field_id->value();
  $output = '<nav class="sidenav sidenav--section-'.check_plain($menu_id).'">';
  $output .= '<h1 class="sidenav__title"><a href="'.url($parent['href']).'" target="_self" class="sidenav__title__link">'. check_plain($parent['title']) .'</a></h1>';
  $output .= '<div class="sidenav__edge"></div><ul class="sidenav__list">';
  foreach ($items as &$item) {
    $output .= '<li class="sidenav__item'. ((count($route) >= 3 && $item['link']['mlid'] === $route[2]['mlid']) ? ' sidenav__item--active' : '') .'">';
    $output .= '<a href="'.url($item['link']['href']).'" target="_self" class="sidenav__item__link">'.check_plain($item['link']['title']).'</a>';
    $output .= '</li>';
  }
  unset($item);
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

function sse_html_head_alter(&$head_elements)
{
  unset($head_elements['system_meta_generator']);
}

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
