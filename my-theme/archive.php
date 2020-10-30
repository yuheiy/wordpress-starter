<?php

$context = default_timber_context();

foreach ($context['posts'] as $post) {
  strip_origin_from_post_link($post);
}

$templates = [];
if (is_post_type_archive()) {
  array_unshift($templates, 'archive-' . get_post_type() . '.twig');
}

Timber::render($templates, $context);
