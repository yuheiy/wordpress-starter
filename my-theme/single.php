<?php

$context = defaultTimberContext();

$post = Timber::get_post();
forceRelPath($post);
$context['post'] = $post;

Timber::render(
  [
    'single-' . $post->ID . '.twig',
    'single-' . $post->post_type . '.twig',
    'single-' . $post->slug . '.twig',
  ],
  $context
);
