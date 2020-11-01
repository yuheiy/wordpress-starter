<?php

$context = default_timber_context();

$post = Timber::get_post();
force_rel_path($post);
$context['post'] = $post;

Timber::render(
  [
    'single-' . $post->ID . '.twig',
    'single-' . $post->post_type . '.twig',
    'single-' . $post->slug . '.twig',
  ],
  $context
);
