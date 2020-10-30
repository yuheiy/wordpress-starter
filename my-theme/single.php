<?php

$context = default_timber_context();

$post = Timber::get_post();
strip_origin_from_post_link($post);
$context['post'] = $post;

Timber::render(
  [
    'single-' . $post->ID . '.twig',
    'single-' . $post->post_type . '.twig',
    'single-' . $post->slug . '.twig',
  ],
  $context
);
