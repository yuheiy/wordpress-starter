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
    new Timber\Twig_Function('asset_path', function (string $key): string {
      $manifest = webpack_manifest();
      assert(
        isset($manifest[$key]),
        sprintf('%s does not exist in webpack-manifest.json', $key)
      );
      return $manifest[$key];
    })
  );

  $twig->addFilter(
    new Timber\Twig_Filter('rel_url', function (string $url): string {
      return Timber\URLHelper::get_rel_url($url, true);
    })
  );

  return $twig;
});

function webpack_manifest(): array
{
  return json_decode(
    file_get_contents(get_theme_file_path('/assets/webpack-manifest.json')),
    true
  );
}

function default_timber_context(): array
{
  $context = Timber::context();

  $home_url = home_url('/');
  $context['home_path'] = Timber\URLHelper::get_rel_url($home_url, true);

  $about_post = Timber::get_post([
    'post_type' => 'page',
    'title' => '私たちについて',
  ]);
  force_rel_path($about_post);
  $context['about_post'] = $about_post;

  $privacy_policy_post = Timber::get_post([
    'post_type' => 'page',
    'title' => 'プライバシーポリシー',
  ]);
  force_rel_path($privacy_policy_post);
  $context['privacy_policy_post'] = $privacy_policy_post;

  $news_post_type = new Timber\PostType('news');
  set_post_type_path($news_post_type);
  $context['news_post_type'] = $news_post_type;

  return $context;
}

function set_post_type_path(Timber\PostType $post_type): void
{
  $url = get_post_type_archive_link($post_type->slug);
  $post_type->path = Timber\URLHelper::get_rel_url($url, true);
}

function set_term_queried(Timber\Term $term): void
{
  $term->queried = $term->slug === get_query_var($term->taxonomy);
}

function force_rel_path(object $with_path): void
{
  $with_path->path = Timber\URLHelper::get_rel_url($with_path->path, true);
}

require get_theme_file_path('/inc/news.php');
require get_theme_file_path('/inc/head.php');
require get_theme_file_path('/inc/admin.php');
