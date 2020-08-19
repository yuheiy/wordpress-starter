<?php

require __DIR__ . '/inc/news.php';
require __DIR__ . '/inc/head.php';
require __DIR__ . '/inc/admin.php';

remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head', 'wp_generator');
remove_action('wp_print_styles', 'print_emoji_styles');
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('html5', [
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script',
  ]);
});

function default_app_props()
{
  $news_post_type = get_post_type_object('news');
  $news_archive_url = get_post_type_archive_link('news');
  $sample_page = get_page_by_title('Sample Page');
  set_post_link($sample_page);

  return [
    'name' => get_bloginfo('name'),

    'site_url' => site_url(),

    'home_url' => strip_origin_from_url(home_url('/')),

    'is_home' => is_home(),

    'news_archive_url' => strip_origin_from_url($news_archive_url),

    'sample_page' => $sample_page,

    'header_nav_items' => [
      [
        'label' => $sample_page->post_title,
        'link' => $sample_page->link,
        'current' => $sample_page->ID === get_queried_object_id(),
      ],
      [
        'label' => $news_post_type->label,
        'link' => strip_origin_from_url($news_archive_url),
        'current' =>
          is_post_type_archive('news') ||
          is_tax('news_category') ||
          is_singular('news'),
      ],
    ],

    'footer_nav_items' => [
      [
        'label' => $sample_page->post_title,
        'link' => strip_origin_from_url(get_permalink($sample_page)),
        'current' => $sample_page->ID === get_queried_object_id(),
      ],
      [
        'label' => $news_post_type->label,
        'link' => strip_origin_from_url($news_archive_url),
        'current' => is_post_type_archive('news') || is_singular('news'),
      ],
    ],
  ];
}

function strip_origin_from_url($url)
{
  $parsed = parse_url($url);
  $result = '';
  if (array_key_exists('path', $parsed)) {
    $result .= $parsed['path'];
  }
  if (array_key_exists('query', $parsed)) {
    $result .= '?' . $parsed['query'];
  }
  if (array_key_exists('fragment', $parsed)) {
    $result .= '#' . $parsed['fragment'];
  }
  return $result;
}

function set_post_link($post)
{
  $post->link = strip_origin_from_url(get_permalink($post));
}

function set_post_acf($post)
{
  if (function_exists('get_fields')) {
    $post->acf = get_fields($post->ID);
  }
}
