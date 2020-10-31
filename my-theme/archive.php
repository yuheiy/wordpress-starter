<?php

$context = default_timber_context();

foreach ($context['posts'] as $post) {
  strip_origin_from_post_link($post);
}

if (is_post_type_archive('news') || is_tax('news_category')) {
  $context['is_news_archive'] = is_post_type_archive('news');

  $context['news_category_terms'] = array_map(
    function (object $term): Timber\Term {
      $timber_term = new Timber\Term($term->term_id);
      strip_origin_from_term_link($timber_term);
      set_term_queried($timber_term);
      return $timber_term;
    },
    get_terms([
      'taxonomy' => 'news_category',
    ])
  );
}

$templates = [];
if (is_post_type_archive()) {
  array_unshift($templates, 'archive-' . get_post_type() . '.twig');
} elseif (is_tax()) {
  array_unshift(
    $templates,
    'taxonomy-' .
      get_queried_object()->taxonomy .
      '-' .
      get_queried_object()->slug .
      '.twig',
    'taxonomy-' . get_queried_object()->taxonomy . '.twig'
  );
}

Timber::render($templates, $context);
