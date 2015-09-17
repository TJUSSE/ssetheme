<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

const sse_menu_navigation = 'menu-sse-navigation';
const sse_menu_footer = 'menu-sse-footer';
const sse_menu_main = 'menu-sse-main';

const sse_cache_enabled = false;

/**
 * 这里指定的是 <meta name="theme-color"> 中的颜色，该属性可以指定 Android Chrome 浏览器中标题栏颜色
 * @var array
 */
global $sse_section_colors;
$sse_section_colors = [
  'default' =>   '#53A0D4',
  'overview' =>  '#9FC1E3',
  'admission' => '#E3B48D',
  'education' => '#E1A6E3',
  'research' =>  '#F8DA89',
  'activity' =>  '#C9D36F',
  'news' =>      '#DEA1A1',
  'notice' =>    '#A4CBCC',
];

global $sse_node_type_parent_menu;
$sse_node_type_parent_menu = [
  'club_content' => ['activity', 'club'],
  'chronology_content' => ['overview', 'chronology'],
  'teacher_content' => ['education', 'faculty'],
  'research_team_content' => ['research', 'team'],
  'excellent_curriculum_content' => ['education', 'achievements'],
  'news_content' => ['news'],
  'notice_content' => ['notice'],
];

global $sse_admission_type_parent_menu;
$sse_admission_type_parent_menu = [
  'undergraduate' => ['admission', 'undergraduate'],
  'master' => ['admission', 'master'],
  'doctoral' => ['admission', 'doctoral'],
];

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
  global $sse_section_colors;
  if (isset($sse_section_colors[$key])) {
    return $sse_section_colors[$key];
  } else {
    return $sse_section_colors['default'];
  }
}

function sse_transform_trail(&$trail, $node, $navi_menu_path)
{
  // 对于新闻和通知节点，菜单项是 sse_menu_main，需要特殊处理
  if ($navi_menu_path[0] === 'news' || $navi_menu_path[0] === 'notice') {
    $navi_menu = sse_get_menu_tree_with_id(sse_menu_main, 1, true);
    foreach ($navi_menu as &$top_menu) {
      if (isset($top_menu['id']) && $top_menu['id'] === $navi_menu_path[0]) {
        array_splice($trail, 1);
        // 增加一级菜单
        $shadow = $top_menu;
        unset($shadow['items']);
        $trail[] = $shadow;
        // 增加节点菜单
        $shadow = (array)$node;
        $shadow['href'] = node_uri($node)['path'];
        $trail[] = $shadow;
        return;
      }
    }
    unset($top_menu);
  } else {
    $navi_menu = sse_get_menu_tree_with_id(sse_menu_navigation, 2, true);
    foreach ($navi_menu as &$top_menu) {
      if (isset($top_menu['id']) && $top_menu['id'] === $navi_menu_path[0]) {
        foreach ($top_menu['items'] as &$item) {
          if (isset($item['subid']) && $item['subid'] === $navi_menu_path[1]) {
            array_splice($trail, 1);
            // 增加一级菜单
            $shadow = $top_menu;
            unset($shadow['items']);
            $trail[] = $shadow;
            // 增加二级菜单
            $trail[] = $item;
            // 增加节点菜单
            $shadow = (array)$node;
            $shadow['href'] = node_uri($node)['path'];
            $trail[] = $shadow;
            return;
          }
        }
        unset($item);
        return;
      }
    }
    unset($top_menu);
  }
}

/**
 * 获取经过处理的路径轨迹
 */
function sse_get_trail()
{
  global $sse_node_type_parent_menu, $sse_admission_type_parent_menu;
  static $trail;
  if ($trail === null) {
    $trail = menu_get_active_trail();
    if (count($trail) >= 2) {
      // 不在主菜单下，可能在子菜单下
      if (!isset($route[1]['menu_name']) || $route[1]['menu_name'] !== sse_menu_navigation) {
        // 当前在某个 node 下，则检查 node type 尝试匹配到某个菜单项下，或者如果是招生信息的话，尝试将 admission_type 字段匹配到某个菜单项下
        if ($node = menu_get_object()) {
          if (isset($node->field_admission_type)) {
            // 是招生信息
            $admission_type = $node->field_admission_type[LANGUAGE_NONE][0]['taxonomy_term']->field_admission_type_id[LANGUAGE_NONE][0]['value'];
            if (isset($sse_admission_type_parent_menu[$admission_type])) {
              sse_transform_trail($trail, $node, $sse_admission_type_parent_menu[$admission_type]);
            }
          } else {
            // 普通信息，尝试匹配节点类型
            if (isset($sse_node_type_parent_menu[$node->type])) {
              sse_transform_trail($trail, $node, $sse_node_type_parent_menu[$node->type]);
            }
          }
        }
      }
    }
  }
  $trail[count($trail) - 1]['__last'] = true;
  return $trail;
}

/**
 * 获取当前所在区块
 */
function sse_get_current_section()
{
  static $section = null;
  if ($section === null) {
    $route = sse_get_trail();
    if (count($route) >= 2) {
      // $route[0] 是首页
      if (isset($route[1]['menu_name']) && ($route[1]['menu_name'] === sse_menu_navigation || $route[1]['menu_name'] === sse_menu_main)) {
        $entity = menu_fields_load_by_mlid($route[1]['mlid'])->wrapper();
        $menu_id = $entity->field_navigation_menu_id->value();
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
  $route = sse_get_trail();
  return $route;
}

/**
 * 返回面包屑 HTML
 */
function sse_breadcrumb_output()
{
  $breadcrumb = sse_get_breadcrumb();
  $output = '<div class="breadcrumb-container">/ ';
  foreach ($breadcrumb as &$item) {
    if (!isset($item['__last'])) {
      $output .= '<a class="breadcrumb__item breadcrumb__item--link" target="_self" href="'.url($item['href']).'">'.check_plain($item['title']).'</a> / ';
    } else {
      $output .= '<span class="breadcrumb__item breadcrumb__item--text">'.check_plain($item['title']).'.html</span>';
    }
  }
  $output .= '</div>';
  unset($item);
  return $output;
}

/**
 * 将一颗菜单树递归地转换为包含 ID 字段的菜单树
 */
function sse_process_menu_tree_with_id(&$tree, $preserve_raw = false)
{
  $items = [];
  foreach ($tree['below'] as &$item) {
    if ($item['link']['hidden'] !== true) {
      $items[] = sse_process_menu_tree_with_id($item, $preserve_raw);
    }
  }
  unset($item);

  if (!$preserve_raw) {
    $ret = [
      'href' => $tree['link']['href'],
      'title' => $tree['link']['title'],
      'items' => $items
    ];    
  } else {
    $ret = $tree['link'];
    $ret['items'] = $items;
  }

  $entity = menu_fields_load_by_mlid($tree['link']['mlid']);
  if ($entity !== null) {
    $entity = $entity->wrapper();
    if (isset($entity->field_navigation_menu_id)) {
      $ret['id'] = $entity->field_navigation_menu_id->value();
    }
    if (isset($entity->field_navigation_menu_subid)) {
      $ret['subid'] = $entity->field_navigation_menu_subid->value();
    }
  }

  return $ret;
}

/**
 * 获取包含 ID 字段的菜单树
 */
function sse_get_menu_tree_with_id($menu_name, $depth = NULL, $preserve_raw = false)
{
  $cid = 'sse:menu_tree_with_id:'.$menu_name.':'.$GLOBALS['language']->language.':'.(int)$depth.':'.(int)$preserve_raw;
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data) && sse_cache_enabled) {
    return $cache->data;
  }

  $result = [];
  $menu_tree = menu_tree_all_data($menu_name, NULL, $depth);
  foreach ($menu_tree as &$tree) {
    if ($tree['link']['hidden'] === true) {
      continue;
    }
    $result[] = sse_process_menu_tree_with_id($tree, $preserve_raw);
  }
  unset($tree);

  cache_set($cid, $result, 'cache_menu');
  return $result;
}

/**
 * 获得一颗数组形式的 taxonomy_tree，该形式和 sse_get_menu_tree_with_id() 一致
 */
function sse_get_taxonomy_tree($taxonomy_name)
{
  $cid = 'sse:taxonomy_tree:'.$taxonomy_name.':'.$GLOBALS['language']->language;
  $cache = cache_get($cid);
  if ($cache && isset($cache->data) && sse_cache_enabled) {
    return $cache->data;
  }

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

  cache_set($cid, $result);
  return $result;
}

/**
 * 获得顶部导航条
 */
function sse_get_navigation_main($is_frontpage = false)
{
  $cid = 'sse:main_menu:'.(int)$is_frontpage.':'.$GLOBALS['language']->language;
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data) && sse_cache_enabled) {
    return $cache->data;
  }

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
    if (isset($entity->field_navigation_menu_id)) {
      $curitem['id'] = $entity->field_navigation_menu_id->value();
    }
    if (!isset($entity->field_main_menu_reference_type)) {
      $result[] = $curitem;
      continue;
    }
    $ref_type = $entity->field_main_menu_reference_type->value();
    if ($ref_type === 'none') {
      // 无引用：没有子项
      $result[] = $curitem;
      continue;
    }
    // 有引用
    if (!isset($entity->field_main_menu_reference_target)) {
      $result[] = $curitem;
      continue;
    }
    $ref_target = $entity->field_main_menu_reference_target->value();

    if ($ref_type === 'menu') {
      // 菜单引用，省略二级以上菜单
      $subitems = sse_get_menu_tree_with_id($ref_target, 2);
    } else if ($ref_type === 'taxonomy') {
      $subitems = sse_get_taxonomy_tree($ref_target);
    } else {
      $result[] = $curitem;
      continue;
    }

    // 是否以子项代替当前项
    if ($entity->field_main_menu_expand->value() === true && $is_frontpage === false) {
      foreach ($subitems as $subitem) {
        $result[] = $subitem;
      }
    } else {
      $curitem['items'] = $subitems;
      $result[] = $curitem;
    }
  }
  unset($item);

  cache_set($cid, $result, 'cache_menu');
  return $result;
}

/**
 * 返回 footer 导航 HTML
 */
function sse_navigation_footer_output()
{
  $cid = 'sse:html:navigation_footer:'.$GLOBALS['language']->language;
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data) && sse_cache_enabled) {
    return $cache->data;
  }

  $output = '';
  $navi = sse_get_menu_tree_with_id(sse_menu_navigation, 2);
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

  cache_set($cid, $output, 'cache_menu');
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
    // 顶级菜单，将链接修正到第一个子链接
    if ($level === 0 && $item['href'] === '<front>') {
      if ($has_subitems) {
        $item['href'] = $item['items'][0]['href'];
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

  $cid = 'sse:html:navigation_main:'.(int)$is_frontpage.':'.sse_get_current_section().':'.$GLOBALS['language']->language;
  $cache = cache_get($cid, 'cache_menu');
  if ($cache && isset($cache->data) && sse_cache_enabled) {
    return $cache->data;
  }
  
  $navi = sse_get_navigation_main($is_frontpage);
  $output = sse_navigation_output_tree($navi, 0, $is_frontpage);

  cache_set($cid, $output, 'cache_menu');
  return $output;
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
    $route = sse_get_trail();
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
  $route = sse_get_trail();
  $parent = $route[1];
  $param = [
    'active_trail' => [$parent['plid']],
    'only_active_trail' => false,
    'min_depth' => $parent['depth'] + 1,
    'max_depth' => $parent['depth'] + 1,
    'conditions' => ['plid' => $parent['mlid']],
  ];
  $items = array_values(menu_build_tree($parent['menu_name'], $param));
  $entity = menu_fields_load_by_mlid($parent['mlid'])->wrapper();
  $menu_id = $entity->field_navigation_menu_id->value();
  $output = '<nav class="sidenav sidenav--section-'.check_plain($menu_id).'">';
  // 修正链接到第一个子项
  if (count($items) > 0 && $parent['href'] === '<front>') {
    $parent['href'] = $items[0]['link']['href'];
  }
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
function sse_preprocess_html(&$variables, $hook)
{
  $variables['base_path'] = base_path();
  $variables['path_to_sse'] = drupal_get_path('theme', 'sse');

  // 为登录界面增加样式
  if (arg(0) == 'user' && !$GLOBALS['user']->uid) {
    if (!in_array('page-user-login', $variables['classes_array'])) {
      $variables['classes_array'][] = 'page-user-login';
    }
    return;
  }

  // 为错误界面增加样式
  $header = drupal_get_http_header('status');
  if ($header === '404 Not Found') {
    $variables['classes_array'][] = 'page-404 page-error';
  } elseif ($header === '403 Forbidden') {
    $variables['classes_array'][] = 'page-403 page-error';
  }
}

function sse_html_head_alter(&$head_elements)
{
  unset($head_elements['system_meta_generator']);
}

function sse_preprocess_page(&$variables, $hook)
{
  if (arg(0) == 'user' && !$GLOBALS['user']->uid) {
    // 给未登录的用户 /user 加上 user__login
    if (!in_array('page__user__login', $variables['theme_hook_suggestions'])) {
      $variables['theme_hook_suggestions'][] = 'page__user__login';
    }
    drupal_add_js(drupal_get_path('theme', 'sse') .'/js/login.js', 'file');
    return;
  }

  // 自定义 404 和 403 页面
  $header = drupal_get_http_header('status');
  if ($header === '404 Not Found' || $header === '403 Forbidden') {
    $variables['theme_hook_suggestions'][] = 'page__404_403';
    //drupal_add_js(drupal_get_path('theme', 'sse') .'/js/vendor/jquery.mousewheel.min.js', 'file');
    drupal_add_js(drupal_get_path('theme', 'sse') .'/js/vendor/jquery.terminal-0.8.8.min.js', 'file');
    drupal_add_js(drupal_get_path('theme', 'sse') .'/js/error-404-403.js', 'file');
  }
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
