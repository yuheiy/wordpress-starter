<?php

declare(strict_types=1);

Timber::$dirname = "templates";

remove_action("wp_head", "feed_links_extra", 3);
remove_action("wp_head", "rsd_link");
remove_action("wp_head", "wlwmanifest_link");
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
add_filter("emoji_svg_url", "__return_false");
remove_action("wp_head", "wp_shortlink_wp_head", 10, 0);

add_action("after_setup_theme", function (): void {
  add_theme_support("title-tag");
  add_theme_support("html5", [
    "search-form",
    "comment-form",
    "comment-list",
    "gallery",
    "caption",
    "style",
    "script",
  ]);
});

add_filter("timber/context", function (array $context): array {
  $homeUrl = home_url("/");
  $context["home_path"] = Timber\URLHelper::get_rel_url($homeUrl, true);

  $aboutPost = Timber::get_post([
    "post_type" => "page",
    "title" => "私たちについて",
  ]);
  forceRelPath($aboutPost);
  $context["about_post"] = $aboutPost;

  $privacyPolicyPost = Timber::get_post([
    "post_type" => "page",
    "title" => "プライバシーポリシー",
  ]);
  forceRelPath($privacyPolicyPost);
  $context["privacy_policy_post"] = $privacyPolicyPost;

  $newsPostType = new Timber\PostType("news");
  setPostTypePath($newsPostType);
  $context["news_post_type"] = $newsPostType;

  return $context;
});

add_filter("timber/twig", function (object $twig): object {
  $twig->addFunction(
    new Timber\Twig_Function("asset_path", function (string $key): string {
      $manifest = webpackManifest();
      assert(
        isset($manifest[$key]),
        sprintf("%s does not exist in webpack-manifest.json", $key)
      );
      return $manifest[$key];
    })
  );

  $twig->addFilter(
    new Timber\Twig_Filter("rel_url", function (string $url): string {
      return Timber\URLHelper::get_rel_url($url, true);
    })
  );

  return $twig;
});

require get_theme_file_path("/inc/news.php");
require get_theme_file_path("/inc/head.php");
require get_theme_file_path("/inc/admin.php");

function webpackManifest(): array
{
  return json_decode(
    file_get_contents(get_theme_file_path("/assets/webpack-manifest.json")),
    true
  );
}

function setPostTypePath(Timber\PostType $postType): void
{
  $url = get_post_type_archive_link($postType->slug);
  $postType->path = Timber\URLHelper::get_rel_url($url, true);
}

function setTermQueried(Timber\Term $term): void
{
  $term->queried = $term->slug === get_query_var($term->taxonomy);
}

function forceRelPath(object $withPath): void
{
  $withPath->path = Timber\URLHelper::get_rel_url($withPath->path, true);
}
