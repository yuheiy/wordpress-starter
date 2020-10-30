<?php

require get_theme_file_path('/inc/news.php');
require get_theme_file_path('/inc/head.php');
require get_theme_file_path('/inc/admin.php');

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

add_action('after_setup_theme', function (): void {
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

function default_app_props(): array
{
  set_post_type_link($news_post_type = get_post_type_object('news'));
  set_post_link($sample_page = get_page_by_title('Sample Page'));

  return [
    'name' => get_bloginfo('name'),

    'site_url' => site_url(),

    'home_url' => strip_origin_from_url(home_url('/')),

    'is_home' => is_home(),

    'news_post_type' => $news_post_type,

    'sample_page' => $sample_page,

    'header_nav_items' => [
      [
        'label' => $sample_page->post_title,
        'link' => $sample_page->link,
        'current' => $sample_page->ID === get_queried_object_id(),
      ],
      [
        'label' => $news_post_type->label,
        'link' => $news_post_type->link,
        'current' =>
          is_post_type_archive('news') ||
          is_tax('news_category') ||
          is_singular('news'),
      ],
    ],

    'footer_nav_items' => [
      [
        'label' => $sample_page->post_title,
        'link' => $sample_page->link,
      ],
      [
        'label' => $news_post_type->label,
        'link' => $news_post_type->link,
      ],
    ],
  ];
}

function strip_origin_from_url(string $url): string
{
  $parsed = parse_url($url);
  $result = '';
  if (isset($parsed['path'])) {
    $result .= $parsed['path'];
  }
  if (isset($parsed['query'])) {
    $result .= '?' . $parsed['query'];
  }
  if (isset($parsed['fragment'])) {
    $result .= '#' . $parsed['fragment'];
  }
  return $result;
}

function set_post_link(WP_Post $post): void
{
  $post->link = strip_origin_from_url(get_permalink($post));
}

function set_post_acf(WP_Post $post): void
{
  if (function_exists('get_fields')) {
    $post->acf = get_fields($post->ID);
  }
}

function set_post_terms(WP_Post $post, object $taxonomy): void
{
  if ($terms = get_the_terms($post, $taxonomy)) {
    foreach ($terms as $term) {
      set_term_link($term);
    }
  }
  $property = $taxonomy . '_terms';
  $post->$property = $terms;
}

function set_term_link(object $term): void
{
  $term->link = strip_origin_from_url(get_term_link($term));
}

function set_post_type_link(object $post_type_object): void
{
  $post_type_object->link = strip_origin_from_url(
    get_post_type_archive_link($post_type_object->name)
  );
}
