<?php

add_action('init', function () {
  register_post_type('news', [
    'label' => 'News',
    'public' => true,
    'supports' => ['title'],
    'has_archive' => true,
  ]);

  register_taxonomy(
    'news_category',
    ['news'],
    [
      'label' => 'Category',
      'public' => true,
      'rewrite' => ['slug' => 'news/category'],
    ]
  );

  add_rewrite_rule(
    'news/category/([^/]+)/?$',
    'index.php?post_type=news&news_category=$matches[1]',
    'top'
  );
});

add_action('pre_get_posts', function ($query) {
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  if (is_post_type_archive('news') || is_tax('news_category')) {
    $query->set('posts_per_page', 12);
  }
});
