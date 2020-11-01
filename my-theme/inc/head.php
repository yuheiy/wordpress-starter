<?php

// canonical
add_action('wp_head', function (): void {
  if (is_singular()) {
    // デフォルトで出力される
    // https://github.com/WordPress/WordPress/blob/106a6ba9e1e7bfc15a177aeef3e140433d7e47df/wp-includes/link-template.php#L3830-L3852
    return;
  }

  if (is_home()) {
    $url = home_url('/');
  }

  if (isset($url)) {
    echo sprintf('<link rel="canonical" href="%s">', esc_url($url)) . "\n";
  }
});

// ogp
add_action('wp_head', function (): void {
  if (is_singular()) {
    $title = get_the_title();
  } elseif (
    ($post_type = get_query_var('post_type')) &&
    is_post_type_archive($post_type)
  ) {
    $title = get_post_type_object($post_type)->label;
  } else {
    $title = get_bloginfo('name');
  }

  $description = get_bloginfo('description');

  if (is_single()) {
    $type = 'article';
  } else {
    $type = 'website';
  }

  $manifest = webpack_manifest();
  $image = home_url($manifest['ogp.png']);

  $site_name = get_bloginfo('name');

  $locale = get_locale();

  if (is_home()) {
    $url = home_url('/');
  } elseif (is_singular()) {
    $url = get_permalink();
  } else {
    $url = Timber\URLHelper::get_current_url();
  }

  $twitter_card = 'summary_large_image';

  foreach (
    [
      ['<meta name="description" content="%s">', esc_attr($description)],
      ['<meta name="twitter:card" content="%s">', esc_attr($twitter_card)],
      ['<meta property="og:title" content="%s">', esc_attr($title)],
      ['<meta property="og:type" content="%s">', esc_attr($type)],
      ['<meta property="og:image" content="%s">', esc_url($image)],
      ['<meta property="og:url" content="%s">', esc_url($url)],
      ['<meta property="og:description" content="%s">', esc_attr($description)],
      ['<meta property="og:site_name" content="%s">', esc_attr($site_name)],
      ['<meta property="og:locale" content="%s">', esc_attr($locale)],
    ]
    as list($format, $value)
  ) {
    echo sprintf($format, $value) . "\n";
  }
});

// resources
add_action('wp_head', function (): void {
  $manifest = webpack_manifest();

  echo sprintf(
    '<link rel="icon" href="%s" type="image/svg+xml">',
    esc_url($manifest['favicon.svg'])
  ) . "\n";

  echo sprintf(
    '<link rel="apple-touch-icon" href="%s">',
    esc_url($manifest['apple-touch-icon.png'])
  ) . "\n";

  if (isset($manifest['main.css'])) {
    echo sprintf(
      '<link rel="stylesheet" href="%s">',
      esc_url($manifest['main.css'])
    ) . "\n";
  }

  echo sprintf(
    '<script defer src="%s"></script>',
    esc_url($manifest['main.js'])
  ) . "\n";
});

add_filter('document_title_parts', function (array $title): array {
  if (isset($title['tagline'])) {
    unset($title['tagline']);
  }
  return $title;
});

add_filter('document_title_separator', function (): string {
  return '|';
});
