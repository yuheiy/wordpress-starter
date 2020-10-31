<?php

Timber::$dirname = 'templates';

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

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

add_filter('timber/twig', function (object $twig): object {
  $twig->addFunction(
    new Timber\Twig_Function('asset_path', function ($key) {
      $manifest = webpack_manifest();
      assert(
        isset($manifest[$key]),
        sprintf('%s does not exist in webpack-manifest.json', $key)
      );
      return $manifest[$key];
    })
  );

  $twig->addFilter(
    new Timber\Twig_Filter('strip_origin_from_url', 'strip_origin_from_url')
  );

  return $twig;
});

function default_timber_context(): array
{
  $context = Timber::context();

  $home_url = strip_origin_from_url(home_url('/'));
  $context['site']->home_url = $home_url;

  $about_post = Timber::get_post([
    'post_type' => 'page',
    'title' => '私たちについて',
  ]);
  strip_origin_from_post_link($about_post);
  $context['about_post'] = $about_post;

  $privacy_policy_post = Timber::get_post([
    'post_type' => 'page',
    'title' => 'プライバシーポリシー',
  ]);
  strip_origin_from_post_link($privacy_policy_post);
  $context['privacy_policy_post'] = $privacy_policy_post;

  $news_post_type = new Timber\PostType('news');
  set_post_type_link($news_post_type);
  $context['news_post_type'] = $news_post_type;

  return $context;
}

function strip_origin_from_post_link(Timber\Post $post): void
{
  $post->link = strip_origin_from_url($post->link);
}

function set_post_type_link(Timber\PostType $post_type): void
{
  $post_type->link = strip_origin_from_url(
    get_post_type_archive_link($post_type->slug)
  );
}

function strip_origin_from_term_link(Timber\Term $term): void
{
  $term->link = strip_origin_from_url($term->link);
}

function set_term_queried(Timber\Term $term): void
{
  $term->queried = $term->slug === get_query_var($term->taxonomy);
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

function webpack_manifest(): array
{
  return json_decode(
    file_get_contents(get_theme_file_path('/assets/webpack-manifest.json')),
    true
  );
}

function requested_url(): string
{
  $result = is_ssl() ? 'https://' : 'http://';
  $result .= $_SERVER['HTTP_HOST'];
  $result .= $_SERVER['REQUEST_URI'];
  return $result;
}

require get_theme_file_path('/inc/news.php');
require get_theme_file_path('/inc/head.php');
require get_theme_file_path('/inc/admin.php');
