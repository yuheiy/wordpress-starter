<?php

declare(strict_types=1);

$context = Timber::context();

$post = Timber::get_post();
forceRelPath($post);
$context['post'] = $post;

Timber::render('page-' . $post->post_name . '.twig', $context);
