<?php

declare(strict_types=1);

$context = Timber::context();

$post = Timber::get_post();
forceRelPath($post);
$context["post"] = $post;

Timber::render(
  [
    "single-" . $post->ID . ".twig",
    "single-" . $post->post_type . ".twig",
    "single-" . $post->slug . ".twig",
  ],
  $context
);
